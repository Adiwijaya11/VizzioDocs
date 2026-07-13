<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;

class ProtectPdfController extends Controller
{
    private string $gsPath = 'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c.exe';
    protected $fileValidationService;

    public function __construct(FileValidationService $fileValidationService)
    {
        $this->fileValidationService = $fileValidationService;
    }

    public function index()
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $maxFileSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        return view('tools.protect-pdf', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file'           => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
            'user_password'  => 'required|string|min:1|max:100',
            'owner_password' => 'nullable|string|max:100',
        ]);

        if (!file_exists($this->gsPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript tidak ditemukan. Pastikan Ghostscript sudah terinstall.',
            ], 500);
        }

        $sessionId = Str::uuid()->toString();
        $tempDir   = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $inputPath     = $request->file('file')->getRealPath();
        $userPassword  = $request->input('user_password');  // open/read password (required)
        $ownerPassword = $request->input('owner_password') ?: $userPassword; // defaults to user password

        // Map checkbox fields from the form (allow_print, allow_copy)
        $permBitsMap = [
            'allow_print'  => 4,
            'allow_copy'   => 16,
            'allow_modify' => 8,
            'allow_annot'  => 32,
            'allow_forms'  => 256,
        ];
        $permBits = 0;
        foreach ($permBitsMap as $field => $bit) {
            if ($request->has($field)) {
                $permBits |= $bit;
            }
        }

        try {
            $outputPath = $tempDir . '/protected.pdf';

            // Build GS command
            $args = [
                escapeshellarg($this->gsPath),
                '-sDEVICE=pdfwrite',
                '-dCompatibilityLevel=1.4',
                '-dNOPAUSE',
                '-dQUIET',
                '-dBATCH',
                '-dSAFER',
                '-dEncryptionR=3',          // 128-bit RC4 encryption
                '-dKeyLength=128',
                '-dPermissions=' . $permBits,
                '-sOwnerPassword=' . escapeshellarg($ownerPassword),
                '-sUserPassword='  . escapeshellarg($userPassword),
                '-sOutputFile='    . escapeshellarg($outputPath),
                escapeshellarg($inputPath),
            ];

            $command = implode(' ', $args) . ' 2>&1';
            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($outputPath)) {
                $errorDetail = implode("\n", $output);
                \Log::error('Protect PDF GS Error', ['output' => $errorDetail]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproteksi PDF menggunakan Ghostscript.',
                ], 500);
            }

            return response()->json([
                'success'      => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => 'protected.pdf']),
                'filename'     => 'protected.pdf',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Protect PDF Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproteksi PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}

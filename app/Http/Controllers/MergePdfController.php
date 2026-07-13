<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;

class MergePdfController extends Controller
{
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

        return view('tools.merge', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'files' => 'required|array|min:2',
            'files.*' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024)
        ]);

        $files = $request->file('files');
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $outputPath = $tempDir . '/merged_document.pdf';

        try {
            $pdf = new Fpdi();
            $pdf->SetCompression(true);

            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                try {
                    $pageCount = $pdf->setSourceFile($filePath);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Salah satu file PDF tidak valid atau dilindungi password: ' . $file->getClientOriginalName()
                    ], 422);
                }

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplIdx = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tplIdx);
                    
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tplIdx);
                }
            }

            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'merged_document.pdf'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menggabungkan PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

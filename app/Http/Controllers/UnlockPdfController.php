<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;

class UnlockPdfController extends Controller
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

        return view('tools.unlock-pdf', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file'     => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
            'password' => 'nullable|string|max:100',
        ]);

        if (!file_exists($this->gsPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript tidak ditemukan. Pastikan Ghostscript sudah terinstall.',
            ], 500);
        }

        $sessionId  = Str::uuid()->toString();
        $tempDir    = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $inputPath  = $request->file('file')->getRealPath();
        $password   = trim($request->input('password', ''));
        $outputPath = $tempDir . '/unlocked.pdf';

        try {
            // ================================================================
            // STEP 1: Deteksi apakah PDF dienkripsi
            // ================================================================
            $isEncrypted = $this->isPdfEncrypted($inputPath);

            if ($isEncrypted && $password === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF ini dilindungi password. Masukkan password untuk membuka kunci.',
                ], 422);
            }

            // ================================================================
            // STEP 2: Jalankan Ghostscript — langsung dengan password (jika ada)
            //         GS adalah yang benar-benar bisa mendekripsi PDF.
            // ================================================================
            $gsPassword = ($isEncrypted && $password !== '') ? $password : null;
            [$exitCode, $stdout, $stderr] = $this->runGs($inputPath, $outputPath, $gsPassword);

            if ($exitCode !== 0 || $this->gsHasPasswordError($stdout, $stderr)) {
                @unlink($outputPath);
                $message = $isEncrypted
                    ? 'Password salah. Tidak dapat membuka kunci PDF dengan password yang diberikan.'
                    : 'Gagal memproses PDF.';
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            // STEP 3: Pastikan output file valid
            if (!file_exists($outputPath) || filesize($outputPath) < 500) {
                @unlink($outputPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Hasil proses tidak valid. Coba lagi.',
                ], 500);
            }

            return response()->json([
                'success'      => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => 'unlocked.pdf']),
                'filename'     => 'unlocked.pdf',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Unlock PDF Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuka kunci PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deteksi apakah PDF dienkripsi dengan membaca raw bytes.
     * Kamus /Encrypt dalam PDF menandakan file terenkripsi.
     */
    private function isPdfEncrypted(string $path): bool
    {
        $handle = fopen($path, 'rb');
        if (!$handle) return false;

        $content = fread($handle, 8192);
        fclose($handle);

        $size = filesize($path);
        if ($size > 8192) {
            $handle = fopen($path, 'rb');
            fseek($handle, -4096, SEEK_END);
            $content .= fread($handle, 4096);
            fclose($handle);
        }

        return (stripos($content, '/Encrypt') !== false);
    }

    /**
     * Jalankan Ghostscript dengan proc_open agar bisa menangkap stdout & stderr secara terpisah.
     * Returns [exitCode, stdout, stderr].
     */
    private function runGs(string $inputPath, string $outputPath, ?string $password): array
    {
        $cmd = [
            $this->gsPath,
            '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.4',
            '-dNOPAUSE',
            '-dBATCH',
            '-dSAFER',
        ];

        if ($password !== null && $password !== '') {
            $cmd[] = '-sPDFPassword=' . $password;
        }

        $cmd[] = '-sOutputFile=' . $outputPath;
        $cmd[] = $inputPath;

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            return [1, '', 'Failed to start Ghostscript process'];
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        \Log::debug('Unlock PDF GS run', [
            'exitCode' => $exitCode,
            'stdout'   => $stdout,
            'stderr'   => $stderr,
        ]);

        return [$exitCode, $stdout, $stderr];
    }

    /**
     * Cek apakah output GS mengandung pesan error password.
     */
    private function gsHasPasswordError(string $stdout, string $stderr): bool
    {
        $combined = strtolower($stdout . $stderr);
        return (
            str_contains($combined, 'password') ||
            str_contains($combined, 'cannot open') ||
            str_contains($combined, 'error: /') ||
            str_contains($combined, 'undefinedfilename')
        );
    }
}

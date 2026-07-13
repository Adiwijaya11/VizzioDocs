<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use ZipArchive;

class PdfToJpgController extends Controller
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

        return view('tools.pdf-to-jpg', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024)
        ]);

        $file = $request->file('file');
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $inputPath = $file->getRealPath();
        $jpgDir = $tempDir . '/pages';
        File::makeDirectory($jpgDir, 0755, true);
        
        $filenameBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        try {
            // Using Ghostscript for more reliable PDF-to-JPG conversion on Windows
            $gsPath = 'C:\Program Files\gs\gs10.07.1\bin\gswin64c.exe';
            
            // Render each page to JPG
            $gsCommand = sprintf(
                '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -r150 -dJPEGQ=90 -dPDFFitPage -sOutputFile="%s/page-%%d.jpg" "%s" 2>&1',
                $gsPath,
                $jpgDir,
                $inputPath
            );
            
            exec($gsCommand, $gsOutput, $gsReturn);

            if ($gsReturn !== 0) {
                \Log::error('Ghostscript failed', [
                    'command' => $gsCommand,
                    'output' => $gsOutput,
                    'return' => $gsReturn
                ]);
                throw new \Exception('Ghostscript gagal merender PDF.');
            }

            // Get generated JPG files
            $images = array_filter(scandir($jpgDir), function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
            });
            
            // Sort files numerically
            usort($images, function($a, $b) {
                preg_match('/page-(\d+)\.jpg/', $a, $matchA);
                preg_match('/page-(\d+)\.jpg/', $b, $matchB);
                return (int)$matchA[1] - (int)$matchB[1];
            });

            if (empty($images)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada halaman yang ditemukan dalam PDF.'
                ], 422);
            }

            // Create a zip file containing all pages
            $zipPath = $tempDir . '/' . $filenameBase . '_jpg_pages.zip';
            $zip = new ZipArchive();
            
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat file ZIP arsip.'
                ], 500);
            }

            foreach ($images as $imgFile) {
                $imgPath = $jpgDir . '/' . $imgFile;
                $zip->addFile($imgPath, $imgFile);
            }

            $zip->close();

            // Extract base64 previews
            $previews = [];
            $previewFiles = array_slice($images, 0, 3);
            foreach ($previewFiles as $imgFile) {
                $imgPath = $jpgDir . '/' . $imgFile;
                $data = file_get_contents($imgPath);
                $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                $previews[] = $base64;
            }

            // Clean up individual JPG files
            File::deleteDirectory($jpgDir);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => basename($zipPath),
                'previews' => $previews,
                'total_pages' => count($images)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonversi PDF ke JPG: ' . $e->getMessage()
            ], 500);
        }
    }
}

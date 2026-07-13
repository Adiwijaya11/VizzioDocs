<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;
use ZipArchive;

class SplitPdfController extends Controller
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

        return view('tools.split', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize),
            'mode' => 'required|string|in:all,range',
            'start_page' => 'required_if:mode,range|nullable|integer|min:1',
            'end_page' => 'required_if:mode,range|nullable|integer|min:1'
        ]);

        $file = $request->file('file');
        $mode = $request->input('mode');
        $startPage = (int) $request->input('start_page');
        $endPage = (int) $request->input('end_page');

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $inputPath = $file->getRealPath();
        $filenameBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        try {
            $pdf = new Fpdi();
            try {
                $pageCount = $pdf->setSourceFile($inputPath);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF tidak valid atau dilindungi password.'
                ], 422);
            }

            if ($mode === 'range') {
                if ($startPage > $pageCount || $endPage > $pageCount || $startPage > $endPage) {
                    return response()->json([
                        'success' => false,
                        'message' => "Rentang halaman tidak valid. Halaman total dokumen ini adalah: {$pageCount} halaman."
                    ], 422);
                }

                $outputPath = $tempDir . '/' . $filenameBase . "_halaman_{$startPage}-{$endPage}.pdf";
                $newPdf = new Fpdi();
                $newPdf->SetCompression(true);
                $newPdf->setSourceFile($inputPath);

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $tplIdx = $newPdf->importPage($i);
                    $size = $newPdf->getTemplateSize($tplIdx);
                    
                    $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $newPdf->useTemplate($tplIdx);
                }

                $newPdf->Output('F', $outputPath);

                return response()->json([
                    'success' => true,
                    'download_url' => route('download', ['id' => $sessionId]),
                    'filename' => basename($outputPath)
                ]);
            } else {
                // Split all pages into a zip file
                $zipPath = $tempDir . '/' . $filenameBase . '_split_pages.zip';
                $zip = new ZipArchive();
                
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat file ZIP arsip.'
                    ], 500);
                }

                for ($i = 1; $i <= $pageCount; $i++) {
                    $singlePagePdf = new Fpdi();
                    $singlePagePdf->SetCompression(true);
                    $singlePagePdf->setSourceFile($inputPath);

                    $tplIdx = $singlePagePdf->importPage($i);
                    $size = $singlePagePdf->getTemplateSize($tplIdx);
                    
                    $singlePagePdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $singlePagePdf->useTemplate($tplIdx);

                    $singlePagePath = $tempDir . "/page_{$i}.pdf";
                    $singlePagePdf->Output('F', $singlePagePath);

                    $zip->addFile($singlePagePath, "page_{$i}.pdf");
                }

                $zip->close();

                // Delete the separate page PDF files to clean space
                for ($i = 1; $i <= $pageCount; $i++) {
                    File::delete($tempDir . "/page_{$i}.pdf");
                }

                return response()->json([
                    'success' => true,
                    'download_url' => route('download', ['id' => $sessionId]),
                    'filename' => basename($zipPath)
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memisahkan PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

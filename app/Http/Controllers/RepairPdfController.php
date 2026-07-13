<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;

class RepairPdfController extends Controller
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

        return view('tools.repair', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');
        $inputPath = $file->getRealPath();

        try {
            // Try to read the PDF
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($inputPath);

            if ($pageCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF tidak memiliki halaman atau terlalu rusak untuk diperbaiki.'
                ], 422);
            }

            // Rebuild the PDF structure
            $pdf->SetCompression(true);

            $repairedPages = 0;
            $failedPages = 0;

            for ($i = 1; $i <= $pageCount; $i++) {
                try {
                    $tpl = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                    $repairedPages++;
                } catch (\Exception $e) {
                    $failedPages++;
                    // Continue with next page
                    continue;
                }
            }

            if ($repairedPages === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada halaman yang berhasil diperbaiki. PDF terlalu rusak.'
                ], 422);
            }

            $outputPath = $tempDir . '/output.pdf';
            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'repaired.pdf',
                'total_pages' => $pageCount,
                'repaired_pages' => $repairedPages,
                'failed_pages' => $failedPages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbaiki PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;

class ExtractPagesController extends Controller
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

        return view('tools.extract-pages', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required_without:session_id|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
            'session_id' => 'required_without:file|string',
            'pages' => 'required|string'
        ]);

        $sessionId = $request->input('session_id');
        $pagesInput = $request->input('pages');
        $file = $request->file('file');

        if ($file) {
            $sessionId = Str::uuid()->toString();
            $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            copy($file->getRealPath(), $tempDir . '/input.pdf');
        } else if ($sessionId) {
            $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
            $inputPath = $tempDir . '/input.pdf';
            if (!file_exists($inputPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi tidak ditemukan.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File atau ID sesi diperlukan.'
            ], 422);
        }

        $inputPath = $tempDir . '/input.pdf';

        try {
            $extractPages = $this->parsePages($pagesInput);

            $writer = new Fpdi();
            $writer->SetCompression(true);
            $pageCount = $writer->setSourceFile($inputPath);

            foreach ($extractPages as $p) {
                if ($p < 1 || $p > $pageCount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Halaman {$p} tidak ada. PDF memiliki {$pageCount} halaman."
                    ], 422);
                }
            }

            foreach ($extractPages as $pageNum) {
                $tpl = $writer->importPage($pageNum);
                $size = $writer->getTemplateSize($tpl);

                $writer->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $writer->useTemplate($tpl);
            }

            $outputPath = $tempDir . '/output.pdf';
            $writer->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => 'output.pdf']),
                'filename' => 'extracted_pages.pdf',
                'pages_extracted' => count($extractPages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal pada server (500). Silakan coba beberapa saat lagi.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    private function parsePages($pagesInput)
    {
        $pages = [];
        $ranges = explode(',', $pagesInput);
        foreach ($ranges as $range) {
            $range = trim($range);
            if (str_contains($range, '-')) {
                list($start, $end) = explode('-', $range);
                $pages = array_merge($pages, range((int)$start, (int)$end));
            } else {
                $pages[] = (int)$range;
            }
        }
        return array_unique($pages);
    }
}

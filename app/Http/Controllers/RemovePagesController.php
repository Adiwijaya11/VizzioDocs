<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;

class RemovePagesController extends Controller
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

        return view('tools.remove-pages', compact('maxFileSize'));
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
        $pagesToRemove = $request->input('pages');
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
                    'message' => 'Sesi tidak ditemukan atau telah kedaluwarsa.'
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
            // Parse pages to remove (supports "1,3,5-8" format)
            $removePages = $this->parsePages($pagesToRemove);

            // Create output PDF
            $writer = new Fpdi();
            $writer->SetCompression(true);
            $pageCount = $writer->setSourceFile($inputPath);

            // Validate pages
            foreach ($removePages as $p) {
                if ($p < 1 || $p > $pageCount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Halaman {$p} tidak ada. PDF memiliki {$pageCount} halaman."
                    ], 422);
                }
            }

            $pagesAdded = 0;
            for ($i = 1; $i <= $pageCount; $i++) {
                if (in_array($i, $removePages)) continue;

                $tpl = $writer->importPage($i);
                $size = $writer->getTemplateSize($tpl);

                $writer->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $writer->useTemplate($tpl);
                $pagesAdded++;
            }

            // Check if all pages were removed
            if ($pagesAdded === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua halaman dipilih untuk dihapus. Minimal sisakan 1 halaman.'
                ], 422);
            }

            $outputPath = $tempDir . '/output.pdf';
            $writer->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => 'output.pdf']),
                'filename' => 'pages_removed.pdf',
                'pages_removed' => count($removePages),
                'pages_remaining' => $pageCount - count($removePages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus halaman: ' . $e->getMessage()
            ], 500);
        }
    }

    private function parsePages($input)
    {
        $pages = [];
        $parts = explode(',', $input);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_contains($part, '-')) {
                $range = explode('-', $part);
                $start = (int) $range[0];
                $end = (int) ($range[1] ?? $start);
                for ($i = $start; $i <= $end; $i++) {
                    $pages[] = $i;
                }
            } else {
                $pages[] = (int) $part;
            }
        }
        return array_unique($pages);
    }
}

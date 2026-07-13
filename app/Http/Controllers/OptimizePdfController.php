<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SafeFpdi as Fpdi;

class OptimizePdfController extends Controller
{
    public function index()
    {
        return view('tools.optimize-pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:102400',
            'optimization_level' => 'nullable|in:low,medium,high'
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');
        $inputPath = $file->getRealPath();
        $optimizationLevel = $request->input('optimization_level', 'medium');

        try {
            $pdf = new Fpdi();
            $pdf->SetCompression(true);
            $pageCount = $pdf->setSourceFile($inputPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }

            $outputPath = $tempDir . '/output.pdf';
            $pdf->Output('F', $outputPath);

            $originalSize = filesize($inputPath);
            $optimizedSize = filesize($outputPath);
            $savedBytes = $originalSize - $optimizedSize;
            $savedPercent = $originalSize > 0 ? round(($savedBytes / $originalSize) * 100, 1) : 0;

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'optimized.pdf',
                'original_size' => $this->formatBytes($originalSize),
                'optimized_size' => $this->formatBytes($optimizedSize),
                'saved_percent' => $savedPercent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengoptimasi PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

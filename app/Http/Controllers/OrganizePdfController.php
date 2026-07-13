<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\RotatedFpdi as Fpdi;

class OrganizePdfController extends Controller
{
    public function index()
    {
        return view('tools.organize-pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:51200',
            'page_order' => 'nullable|string',
            'remove_pages' => 'nullable|string',
            'rotation' => 'nullable|integer|in:0,90,180,270'
        ]);

        $file = $request->file('file');
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $inputPath = $tempDir . '/input.pdf';
        copy($file->getRealPath(), $inputPath);

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

            // Parse page order
            $pages = [];
            $pageOrderInput = trim($request->input('page_order'));
            if (!empty($pageOrderInput)) {
                // Remove spaces and split by comma
                $orderParts = explode(',', str_replace(' ', '', $pageOrderInput));
                foreach ($orderParts as $part) {
                    if (is_numeric($part)) {
                        $pageNum = (int)$part;
                        if ($pageNum >= 1 && $pageNum <= $pageCount) {
                            $pages[] = $pageNum;
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => "Nomor halaman {$pageNum} di urutan baru tidak valid. Rentang halaman PDF adalah 1-{$pageCount}."
                            ], 422);
                        }
                    }
                }
            } else {
                // Default to original page order
                $pages = range(1, $pageCount);
            }

            // Parse remove pages
            $removePagesInput = trim($request->input('remove_pages'));
            if (!empty($removePagesInput)) {
                $removeParts = explode(',', str_replace(' ', '', $removePagesInput));
                $toRemove = [];
                foreach ($removeParts as $part) {
                    if (is_numeric($part)) {
                        $toRemove[] = (int)$part;
                    }
                }
                // Filter out removed pages
                $pages = array_values(array_filter($pages, function($p) use ($toRemove) {
                    return !in_array($p, $toRemove);
                }));
            }

            if (empty($pages)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada halaman yang tersisa untuk dibuat PDF setelah dihapus.'
                ], 422);
            }

            // Global rotation
            $globalRotation = (int) $request->input('rotation', 0);

            $writer = new Fpdi();
            $writer->SetCompression(true);
            $writer->setSourceFile($inputPath);

            foreach ($pages as $pageNum) {
                $tpl = $writer->importPage($pageNum);
                $size = $writer->getTemplateSize($tpl);

                $width = $size['width'];
                $height = $size['height'];
                $orientation = $size['orientation'];

                // Handle global rotation
                if ($globalRotation === 90) {
                    $newOrientation = ($orientation === 'P') ? 'L' : 'P';
                    $writer->AddPage($newOrientation, [$height, $width]);
                    $writer->rotate(270, 0, 0);
                    $writer->useTemplate($tpl, -$width, 0);
                } elseif ($globalRotation === 180) {
                    $writer->AddPage($orientation, [$width, $height]);
                    $writer->rotate(180, 0, 0);
                    $writer->useTemplate($tpl, -$width, -$height);
                } elseif ($globalRotation === 270) {
                    $newOrientation = ($orientation === 'P') ? 'L' : 'P';
                    $writer->AddPage($newOrientation, [$height, $width]);
                    $writer->rotate(90, 0, 0);
                    $writer->useTemplate($tpl, 0, -$height);
                } else {
                    $writer->AddPage($orientation, [$width, $height]);
                    $writer->useTemplate($tpl, 0, 0);
                }
            }

            $outputPath = $tempDir . '/organized_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
            $writer->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => basename($outputPath),
                'total_pages' => count($pages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengatur PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

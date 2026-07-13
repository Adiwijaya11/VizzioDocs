<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SafeFpdi as Fpdi;

class PageNumbersController extends Controller
{
    public function index()
    {
        return view('tools.page-numbers');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:51200',
            'position' => 'nullable|in:bottom-left,bottom-center,bottom-right,top-left,top-center,top-right',
            'format' => 'nullable|in:number,dash,of,page',
            'start_page' => 'nullable|numeric|min:1',
            'font_size' => 'nullable|numeric|min:6|max:24'
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');
        $inputPath = $file->getRealPath();
        $position = $request->input('position', 'bottom-center');
        $format = $request->input('format', 'number');
        $startFrom = (int) $request->input('start_page', 1);
        $fontSize = (int) $request->input('font_size', 10);

        try {
            $pdf = new Fpdi();
            $pdf->SetCompression(true);
            $pageCount = $pdf->setSourceFile($inputPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                // Calculate page number
                $pageNum = $i - 1 + $startFrom;

                // Format page number
                $pageText = $this->formatPageNumber($pageNum, $format, $pageCount);

                // Set font and position
                $pdf->SetFont('Helvetica', '', $fontSize);
                $pdf->SetTextColor(0, 0, 0);

                // Calculate position
                $pageWidth = $size['width'];
                $pageHeight = $size['height'];
                $margin = 15; // mm

                $x = $margin;
                $y = $pageHeight - $margin;

                if (str_contains($position, 'center')) {
                    $x = $pageWidth / 2 - 10;
                } elseif (str_contains($position, 'right')) {
                    $x = $pageWidth - $margin - 20;
                }

                if (str_contains($position, 'top')) {
                    $y = $margin;
                }

                $pdf->Text($x, $y, $pageText);
            }

            $outputPath = $tempDir . '/output.pdf';
            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => 'output.pdf']),
                'filename' => 'numbered.pdf'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan nomor halaman: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatPageNumber($num, $format, $totalPages = null)
    {
        switch ($format) {
            case 'dash':
                return "- {$num} -";
            case 'of':
                return "{$num} of {$totalPages}";
            case 'page':
                return "Halaman {$num}";
            case 'number':
            default:
                return (string) $num;
        }
    }

    private function toRoman($num)
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];

        $result = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $result .= $roman;
                $num -= $value;
            }
        }

        return $result;
    }
}

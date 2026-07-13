<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PdfToExcelController extends Controller
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

        return view('tools.pdf-to-excel', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024)
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');
        $inputPath = $file->getRealPath();

        try {
            // Parse PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($inputPath);
            $pages = $pdf->getPages();

            // Create Excel spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);

            foreach ($pages as $index => $page) {
                $text = $page->getText();
                $lines = explode("\n", $text);

                $sheetTitle = 'Halaman ' . ($index + 1);
                $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet();
                $sheet->setTitle($sheetTitle);
                $spreadsheet->addSheet($sheet);

                $row = 1;
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;

                    // Try to detect tabular data by splitting on multiple spaces
                    $cells = preg_split('/\s{2,}/', $line);

                    if (count($cells) > 1) {
                        // Multiple columns detected
                        $col = 0;
                        foreach ($cells as $cell) {
                            $cell = trim($cell);
                            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                            $sheet->setCellValue($colLetter . $row, $cell);
                            $col++;
                        }
                    } else {
                        // Single column
                        $sheet->setCellValue('A' . $row, $line);
                    }

                    $row++;
                }
            }

            if ($spreadsheet->getSheetCount() === 0) {
                $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet('Halaman 1');
                $spreadsheet->addSheet($sheet);
                $sheet->setCellValue('A1', 'Tidak ada teks yang dapat diekstrak.');
            }

            // Remove default empty sheet
            if ($spreadsheet->getSheetCount() > 1) {
                $spreadsheet->removeSheetByIndex(0);
            }

            $outputPath = $tempDir . '/output.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'converted.xlsx',
                'pages' => count($pages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonversi PDF ke Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}

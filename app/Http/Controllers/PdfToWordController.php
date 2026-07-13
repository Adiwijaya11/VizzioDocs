<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class PdfToWordController extends Controller
{
    public function index()
    {
        return view('tools.pdf-to-word');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:20480' // max 20MB
        ]);

        $file = $request->file('file');
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $inputPath = $file->getRealPath();
        $outputPath = $tempDir . '/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.docx';

        try {
            // Parse PDF using smalot/pdfparser
            $parser = new Parser();
            $pdf = $parser->parseFile($inputPath);
            
            // Extract text
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF ini tidak memiliki teks yang dapat diekstrak (kemungkinan berupa file hasil scan).'
                ], 422);
            }

            // Create Word Document
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            
            // Split extracted text by newline
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                // Remove non-printable control characters that break XML parsing in docx
                $cleanLine = trim(htmlspecialchars(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $line)));
                if ($cleanLine !== '') {
                    $section->addText($cleanLine);
                } else {
                    $section->addTextBreak(1);
                }
            }

            // Save Word document
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => basename($outputPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonversi PDF ke Word: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class PdfToTxtController extends Controller
{
    public function index()
    {
        return view('tools.pdf-to-txt');
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

        $outputPath = $tempDir . '/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.txt';

        try {
            // Parse PDF using smalot/pdfparser
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            
            // Extract text
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF ini tidak memiliki teks yang dapat diekstrak (kemungkinan berupa file hasil scan).'
                ], 422);
            }

            // Clean and write text to file
            $cleanText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
            File::put($outputPath, $cleanText);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => basename($outputPath)]),
                'filename' => basename($outputPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonversi PDF ke TXT: ' . $e->getMessage()
            ], 500);
        }
    }
}

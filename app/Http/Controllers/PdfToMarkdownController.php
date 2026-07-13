<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class PdfToMarkdownController extends Controller
{
    public function index()
    {
        return view('tools.pdf-to-markdown');
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

        $outputPath = $tempDir . '/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.md';

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

            // Clean text
            $cleanText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
            
            // Convert to Markdown format
            $lines = explode("\n", $cleanText);
            $markdown = '';
            $inCodeBlock = false;
            
            foreach ($lines as $line) {
                $trimmed = trim($line);
                
                // Skip empty lines, preserve as paragraph breaks
                if (empty($trimmed)) {
                    $markdown .= "\n\n";
                    continue;
                }
                
                // Detect potential headers (all caps short lines)
                if (mb_strtoupper($trimmed) === $trimmed && strlen($trimmed) < 100 && strlen($trimmed) > 3) {
                    $markdown .= "## " . $trimmed . "\n\n";
                    continue;
                }
                
                // Detect numbered list items
                if (preg_match('/^(\d+[\.\)])\s+(.+)$/', $trimmed, $matches)) {
                    $markdown .= $matches[1] . ' ' . $matches[2] . "\n\n";
                    continue;
                }
                
                // Detect bullet list items
                if (preg_match('/^[•\-\*]\s+(.+)$/', $trimmed, $matches)) {
                    $markdown .= "- " . $matches[1] . "\n\n";
                    continue;
                }
                
                // Regular paragraph text
                $markdown .= $trimmed . "\n\n";
            }
            
            // Clean up excessive newlines
            $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown);
            $markdown = trim($markdown);

            File::put($outputPath, $markdown);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => basename($outputPath)]),
                'filename' => basename($outputPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonversi PDF ke Markdown: ' . $e->getMessage()
            ], 500);
        }
    }
}

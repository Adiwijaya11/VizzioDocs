<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

class HtmlToPdfController extends Controller
{
    public function index()
    {
        return view('tools.html-to-pdf');
    }

    public function process(Request $request)
    {
        try {
            $request->validate([
                'html_content' => 'required|string|max:1000000',
                'page_size' => 'nullable|in:A4,Letter,Legal',
                'orientation' => 'nullable|in:portrait,landscape'
            ]);

            $sessionId = Str::uuid()->toString();
            $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $htmlContent = $request->input('html_content');
            $pageSize = $request->input('page_size', 'A4');
            $orientation = $request->input('orientation', 'portrait');

            try {
                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $options->set('defaultFont', 'Arial');

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($htmlContent);
                $dompdf->setPaper($pageSize, $orientation);
                $dompdf->render();

                $outputPath = $tempDir . '/output.pdf';
                file_put_contents($outputPath, $dompdf->output());

                return response()->json([
                    'success' => true,
                    'download_url' => route('download', ['id' => $sessionId]),
                    'filename' => 'html_to_pdf.pdf'
                ]);

            } catch (\Exception $e) {
                Log::error($e->getMessage(), ['exception' => $e]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengkonversi HTML ke PDF: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal pada server (500). Silakan coba beberapa saat lagi.'
            ], 500);
        }
    }
}

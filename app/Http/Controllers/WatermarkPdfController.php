<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SafeFpdi as Fpdi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\FileValidationService;

class WatermarkPdfController extends Controller
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

        return view('tools.watermark-pdf', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        try {
            $user = Auth::user();
            $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
            $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

            $request->validate([
                'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
                'watermark_type' => 'required|in:text,image',
                'watermark_text' => 'required_if:watermark_type,text|nullable|string|max:200',
                'watermark_image' => 'required_if:watermark_type,image|nullable|file|mimes:png,jpg,jpeg|max:5120',
                'opacity' => 'nullable|numeric|min:0|max:100',
                'position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right,center',
                'rotation' => 'nullable|numeric|min:0|max:360',
                'font_size' => 'nullable|numeric|min:8|max:200',
                'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/'
            ]);

            $sessionId = Str::uuid()->toString();
            $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file = $request->file('file');
            $inputPath = $file->getRealPath();
            $watermarkType = $request->input('watermark_type', 'text');
            $watermarkText = $request->input('watermark_text', 'CONFIDENTIAL');
            $opacity = (int) $request->input('opacity', 30) / 100;
            $position = $request->input('position', 'center');
            $rotation = (int) $request->input('rotation', 45);
            $fontSize = (int) $request->input('font_size', 60);
            $color = ltrim($request->input('color', '#FF0000'), '#');

            try {
                $pdf = new Fpdi();
                $pdf->SetCompression(true);
                $pageCount = $pdf->setSourceFile($inputPath);

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);

                    // Add watermark
                    $pdf->SetAlpha($opacity);

                    if ($watermarkType === 'text') {
                        $r = hexdec(substr($color, 0, 2));
                        $g = hexdec(substr($color, 2, 2));
                        $b = hexdec(substr($color, 4, 2));

                        $pdf->SetFont('Helvetica', 'B', $fontSize);
                        $pdf->SetTextColor($r, $g, $b);

                        $x = $size['width'] / 2 - 50;
                        $y = $size['height'] / 2;

                        $pdf->Rotate($rotation, $x + 50, $y);
                        $pdf->Text($x, $y, $watermarkText);
                        $pdf->Rotate(0);
                    }

                    $pdf->SetAlpha(1);
                }

                $outputPath = $tempDir . '/output.pdf';
                $pdf->Output('F', $outputPath);

                return response()->json([
                    'success' => true,
                    'download_url' => route('download', ['id' => $sessionId]),
                    'filename' => 'watermarked.pdf'
                ]);

            } catch (\Exception $e) {
                Log::error($e->getMessage(), ['exception' => $e]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan watermark: ' . $e->getMessage()
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

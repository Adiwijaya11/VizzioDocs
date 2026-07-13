<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\RotatedFpdi;

class RotatePdfController extends Controller
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

        return view('tools.rotate', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024),
            'angle' => 'required|integer|in:90,180,270'
        ]);

        $file = $request->file('file');
        $angle = (int) $request->input('angle');
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $inputPath = $file->getRealPath();
        $outputPath = $tempDir . '/rotated_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';

        try {
            $pdf = new RotatedFpdi();
            $pdf->SetCompression(true);
            
            try {
                $pageCount = $pdf->setSourceFile($inputPath);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF tidak valid atau dilindungi password.'
                ], 422);
            }

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplIdx);
                
                $w = $size['width'];
                $h = $size['height'];
                $orientation = $size['orientation'];

                // Rotations using mathematical transformation matrix coordinates
                if ($angle === 90) {
                    $newOrientation = ($orientation === 'P') ? 'L' : 'P';
                    $pdf->AddPage($newOrientation, [$h, $w]);
                    $pdf->rotate(270, 0, 0);
                    $pdf->useTemplate($tplIdx, -$w, 0);
                } elseif ($angle === 180) {
                    $pdf->AddPage($orientation, [$w, $h]);
                    $pdf->rotate(180, 0, 0);
                    $pdf->useTemplate($tplIdx, -$w, -$h);
                } elseif ($angle === 270) {
                    $newOrientation = ($orientation === 'P') ? 'L' : 'P';
                    $pdf->AddPage($newOrientation, [$h, $w]);
                    $pdf->rotate(90, 0, 0);
                    $pdf->useTemplate($tplIdx, 0, -$h);
                } else {
                    $pdf->AddPage($orientation, [$w, $h]);
                    $pdf->useTemplate($tplIdx, 0, 0);
                }
            }

            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => basename($outputPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memutar halaman PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

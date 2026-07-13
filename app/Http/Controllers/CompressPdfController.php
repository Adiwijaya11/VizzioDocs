<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use setasign\Fpdi\Fpdi as FpdiLib;

class CompressPdfController extends Controller
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

        return view('tools.compress', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:' . ($effectiveMaxSize / 1024), // Laravel validation uses KB
            'mode' => 'required|string|in:standard,extreme,target_size',
            'target_size_mb' => 'nullable|numeric|min:0.1|max:10'
        ]);

        $file = $request->file('file');
        $mode = $request->input('mode');
        $targetSizeMb = $request->input('target_size_mb', 1.0);
        $targetSizeInBytes = $targetSizeMb * 1024 * 1024;
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $inputPath = $file->getRealPath();
        $outputPath = $tempDir . '/compressed_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';

        try {
            if ($mode === 'standard') {
                // Use Ghostscript's optimized PDF compression for text-based files
                $gsPath = 'C:\Program Files\gs\gs10.07.1\bin\gswin64c.exe';
                $gsCommand = sprintf(
                    '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dQUIET -sOutputFile="%s" "%s" 2>&1',
                    $gsPath,
                    $outputPath,
                    $inputPath
                );
                exec($gsCommand, $gsOutput, $gsReturn);
                
                if ($gsReturn !== 0) {
                    throw new \Exception('Ghostscript gagal memproses mode standar.');
                }
            } 
            else if ($mode === 'extreme') {
                // For Extreme, we use /screen which is even more aggressive
                $gsPath = 'C:\Program Files\gs\gs10.07.1\bin\gswin64c.exe';
                $gsCommand = sprintf(
                    '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dQUIET -sOutputFile="%s" "%s" 2>&1',
                    $gsPath,
                    $outputPath,
                    $inputPath
                );
                exec($gsCommand, $gsOutput, $gsReturn);
            }
            else if ($mode === 'target_size') {
                // More granular steps to get closer to the target size
                $steps = [
                    ['quality' => 95, 'width' => 2500, 'dpi' => 300],
                    ['quality' => 90, 'width' => 2000, 'dpi' => 250],
                    ['quality' => 85, 'width' => 1800, 'dpi' => 200],
                    ['quality' => 80, 'width' => 1600, 'dpi' => 150],
                    ['quality' => 70, 'width' => 1400, 'dpi' => 130],
                    ['quality' => 60, 'width' => 1200, 'dpi' => 110],
                    ['quality' => 50, 'width' => 1000, 'dpi' => 96],
                    ['quality' => 30, 'width' => 800, 'dpi' => 72],
                ];
                
                foreach ($steps as $step) {
                    $this->compressByRendering(
                        $inputPath, 
                        $outputPath, 
                        $tempDir, 
                        $step['quality'], 
                        $step['width'], 
                        $step['dpi']
                    );
                    
                    // Stop as soon as we are under the target size
                    if (filesize($outputPath) <= $targetSizeInBytes) {
                        break;
                    }
                }
            }

            // Calculate size comparison
            $originalSize = filesize($inputPath);
            $compressedSize = filesize($outputPath);

            // FINAL GUARD: If compressed file is larger than original, use the original
            if ($compressedSize >= $originalSize) {
                File::copy($inputPath, $outputPath);
                $compressedSize = $originalSize;
            }

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => basename($outputPath),
                'original_size' => $originalSize,
                'compressed_size' => $compressedSize,
                'ratio' => round((1 - ($compressedSize / $originalSize)) * 100, 1)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengompres PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Re-renders PDF using Ghostscript for high-performance compression
     */
    private function compressByRendering($inputPath, $outputPath, $tempDir, $quality, $maxWidth, $dpi)
    {
        $gsPath = 'C:\Program Files\gs\gs10.07.1\bin\gswin64c.exe';
        $jpgDir = $tempDir . '/jpgs';
        
        // Ensure directory is clean
        if (File::exists($jpgDir)) {
            File::deleteDirectory($jpgDir);
        }
        File::makeDirectory($jpgDir, 0755, true);
        
        // Use the DPI passed from the steps loop

        // Command to render PDF to JPEGs using Ghostscript
        $gsCommand = sprintf(
            '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -r%d -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -dUseTrimBox -dJPEGQ=%d -sOutputFile="%s/page-%%03d.jpg" "%s" 2>&1',
            $gsPath,
            $dpi,
            $quality,
            $jpgDir,
            $inputPath
        );
        
        exec($gsCommand, $gsOutput, $gsReturn);
        
        if ($gsReturn !== 0) {
            throw new \Exception('Ghostscript gagal memproses file. Log: ' . implode("\n", $gsOutput));
        }

        // Get generated JPG files
        $imageFiles = array_filter(scandir($jpgDir), function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
        });
        sort($imageFiles);

        if (empty($imageFiles)) {
            throw new \Exception('Ghostscript tidak menghasilkan gambar halaman.');
        }

        // Rebuild PDF from compressed images
        $manager = new ImageManager(new GdDriver());
        $pdf = new FpdiLib();
        $pdf->SetCompression(true);

        foreach ($imageFiles as $imgFile) {
            $imgPath = $jpgDir . '/' . $imgFile;
            $image = $manager->decodePath($imgPath);
            
            // Auto-scale to maxWidth if needed
            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
                $image->save($imgPath, quality: $quality);
            }

            $imgSize = getimagesize($imgPath);
            $width = $imgSize[0] * 0.264583; // px to mm
            $height = $imgSize[1] * 0.264583;
            
            $orientation = ($width > $height) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$width, $height]);
            $pdf->Image($imgPath, 0, 0, $width, $height);
        }

        $pdf->Output('F', $outputPath);
        
        // Clean up
        File::deleteDirectory($jpgDir);
    }
}

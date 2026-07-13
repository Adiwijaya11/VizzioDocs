<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ScanToPdfController extends Controller
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

        return view('tools.scan-to-pdf', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $request->validate([
            'images'     => 'required|array|min:1',
            'images.*'   => 'required|file|mimes:jpg,jpeg,png,bmp,webp|max:' . ($effectiveMaxSize / 1024),
            'paper_size' => 'nullable|in:A4,Letter,Legal,Original',
            'page_size'  => 'nullable|in:A4,Letter,Legal,Original',
            'quality'    => 'nullable|numeric|min:1|max:100',
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $images = $request->file('images');
        $pageSize = $request->input('paper_size', $request->input('page_size', 'A4'));
        $quality = (int) $request->input('quality', 85);

        try {
            $manager = new ImageManager(new Driver());
            $pdf = new Fpdi();
            $pdf->SetCompression(true);

            foreach ($images as $image) {
                $img = $manager->read($image->getRealPath());

                // Get image dimensions
                $width = $img->width();
                $height = $img->height();

                // Determine page size
                if ($pageSize === 'Original') {
                    // Use image dimensions as page size (convert pixels to mm at 96 DPI)
                    $pageWidth = $width * 0.264583; // pixels to mm
                    $pageHeight = $height * 0.264583;
                    $orientation = $pageWidth > $pageHeight ? 'L' : 'P';
                } else {
                    // Standard page sizes
                    $sizes = [
                        'A4' => [210, 297],
                        'Letter' => [216, 279],
                        'Legal' => [216, 356]
                    ];

                    $pageWidth = $sizes[$pageSize][0];
                    $pageHeight = $sizes[$pageSize][1];
                    $orientation = 'P';

                    // Resize image to fit page while maintaining aspect ratio
                    $targetWidth = $pageWidth * 3.7795275591; // mm to pixels (96 DPI)
                    $targetHeight = $pageHeight * 3.7795275591;

                    if ($width > $targetWidth || $height > $targetHeight) {
                        $img->resize($targetWidth, $targetHeight, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }
                }

                // Save optimized image
                $tempImagePath = $tempDir . '/temp_' . uniqid() . '.jpg';
                $img->toJpeg($quality)->save($tempImagePath);

                // Add page to PDF
                $pdf->AddPage($orientation, [$pageWidth, $pageHeight]);

                // Calculate image position (center on page)
                $imgWidth = $img->width() * 0.264583;
                $imgHeight = $img->height() * 0.264583;
                $x = ($pageWidth - $imgWidth) / 2;
                $y = ($pageHeight - $imgHeight) / 2;

                $pdf->Image($tempImagePath, $x, $y, $imgWidth, $imgHeight);

                // Clean up temp image
                unlink($tempImagePath);
            }

            $outputPath = $tempDir . '/output.pdf';
            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'scan_to_pdf.pdf',
                'pages' => count($images)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF dari gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}

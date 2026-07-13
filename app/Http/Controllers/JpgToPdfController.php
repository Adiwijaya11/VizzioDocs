<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\FileValidationService;
use App\Services\SafeFpdi as Fpdi;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class JpgToPdfController extends Controller
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

        return view('tools.jpg-to-pdf', compact('maxFileSize'));
    }

    public function indexPng()
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $maxFileSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        return view('tools.png-to-pdf', compact('maxFileSize'));
    }

    public function process(Request $request)
    {
        return $this->handleImageToPdf($request, 'jpg');
    }

    public function processPng(Request $request)
    {
        return $this->handleImageToPdf($request, 'png');
    }

    private function handleImageToPdf(Request $request, string $type)
    {
        $user = Auth::user();
        $adminConfiguredMaxSizeMB = $this->fileValidationService->getAdminMaxFileSizeMB();
        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $mimes = $type === 'png' ? 'png' : 'jpeg,jpg,png';
        
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:' . $mimes . '|max:' . ($effectiveMaxSize / 1024)
        ]);

        $files = $request->file('files');
        
        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        File::makeDirectory($tempDir, 0755, true);

        $outputPath = $tempDir . '/converted_images.pdf';

        try {
            $pdf = new Fpdi();
            $pdf->SetCompression(true);

            // Initialize ImageManager
            $manager = new ImageManager(new Driver());

            foreach ($files as $file) {
                // Use Intervention Image to process and save the image with a proper extension
                $image = $manager->decodePath($file->getRealPath());
                $tempImagePath = $tempDir . '/' . Str::random(10) . '.jpg';
                $image->save($tempImagePath);

                $sizeInfo = getimagesize($tempImagePath);
                
                if (!$sizeInfo) {
                    continue;
                }

                $wPx = $sizeInfo[0];
                $hPx = $sizeInfo[1];

                // Convert pixels to millimeters (1 px = 0.264583 mm at 96 DPI)
                $width = $wPx * 0.264583;
                $height = $hPx * 0.264583;

                $orientation = ($width > $height) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$width, $height]);
                                $pdf->Image($tempImagePath, 0, 0, $width, $height);
            }

            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => $type === 'png' ? 'png_to_pdf.pdf' : 'jpg_to_pdf.pdf'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonversi gambar ke PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

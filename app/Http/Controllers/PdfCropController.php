<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\PdfCropService;
use App\Services\FileValidationService;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\Auth;

class PdfCropController extends Controller
{
    protected $pdfCropService;
    protected $fileValidationService;
    protected $fileStorageService;

    public function __construct(PdfCropService $pdfCropService, FileValidationService $fileValidationService, FileStorageService $fileStorageService)
    {
        $this->pdfCropService = $pdfCropService;
        $this->fileValidationService = $fileValidationService;
        $this->fileStorageService = $fileStorageService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $adminSettings = $this->getSettings();
        $adminConfiguredMaxSizeMB = $adminSettings['max_file_size'] ?? null;
        $maxFileSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        return view('tools.crop', compact('maxFileSize'));
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        $adminSettings = $this->getSettings();
        $adminConfiguredMaxSizeMB = $adminSettings['max_file_size'] ?? null;

        $effectiveMaxSize = $this->fileValidationService->getEffectiveMaxFileSize($user, $adminConfiguredMaxSizeMB);

        $validationRules = [
            'file' => ['required', 'file', 'mimes:pdf', 'max:' . ($effectiveMaxSize / 1024)], // Laravel validation uses KB
        ];

        $request->validate($validationRules);

        $file = $request->file('file');
        $sessionId = $this->fileStorageService->createSession();
        $storedFile = $this->fileStorageService->storeFile($file, $sessionId);

        return response()->json([
            'success' => true,
            'sessionId' => $sessionId,
            'filename' => $storedFile['filename'],
            'filePath' => $storedFile['full_path'],
            'pdf_url' => route('pdf-crop.view', ['sessionId' => $sessionId, 'filename' => $storedFile['filename']]),
            'message' => 'PDF diunggah berhasil.'
        ]);
    }

    /**
     * Load settings from the settings.json file.
     *
     * @return array
     */
    private function getSettings(): array
    {
        $settingsPath = storage_path('app/settings.json');
        if (file_exists($settingsPath)) {
            return json_decode(file_get_contents($settingsPath), true) ?? [];
        }
        return [];
    }

    public function viewPdf(string $sessionId, string $filename)
    {
        $filePath = storage_path('app/private/vizziodocs/' . $sessionId . '/input/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function crop(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'filename' => 'required|string',
            'page' => 'required|integer|min:1',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'rotation' => 'nullable|integer|in:0,90,180,270',
            'cropAllPages' => 'boolean',
        ]);

        $sessionId = $request->input('sessionId');
        $filename = $request->input('filename');
        $page = $request->input('page');
        $x = $request->input('x');
        $y = $request->input('y');
        $width = $request->input('width');
        $height = $request->input('height');
        $rotation = $request->input('rotation', 0);
        $cropAllPages = $request->input('cropAllPages', false);

        try {
            $croppedFilePath = $this->pdfCropService->cropPdf(
                $sessionId,
                $filename,
                $page,
                $x,
                $y,
                $width,
                $height,
                $rotation,
                $cropAllPages
            );

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId, 'filename' => basename($croppedFilePath)]),
                'filename' => basename($croppedFilePath),
                'message' => 'PDF berhasil di-crop.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat meng-crop PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

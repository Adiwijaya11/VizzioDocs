<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\FpdiClipping;
use setasign\Fpdi\PdfReader\PageBoundaries;

class PdfCropService
{
    public function cropPdf(
        string $sessionId,
        string $filename,
        int $page,
        float $x,
        float $y,
        float $width,
        float $height,
        int $rotation = 0,
        bool $cropAllPages = false
    ): string {
        $inputPath = storage_path('app/private/vizziodocs/' . $sessionId . '/' . $filename);

        if (!File::exists($inputPath)) {
            throw new \Exception('Input PDF file not found.');
        }

        $outputDir = storage_path('app/private/vizziodocs/' . $sessionId);
        $filenameBase = pathinfo($filename, PATHINFO_FILENAME);
        $outputFilename = $filenameBase . 'cropped.pdf';
        $outputPath = $outputDir . '/' . $outputFilename;

        $pdf = new FpdiClipping('P', 'pt');
        $pdf->SetCompression(true);

        try {
            $pageCount = $pdf->setSourceFile($inputPath);
        } catch (\Exception $e) {
            throw new \Exception('Invalid or password-protected PDF: ' . $e->getMessage());
        }

        $pagesToCrop = $cropAllPages ? range(1, $pageCount) : [$page];

        foreach ($pagesToCrop as $currentPageNum) {
            $tplIdx = $pdf->importPage($currentPageNum);
            $pageSize = $pdf->getTemplateSize($tplIdx);

            $pdfWidth = $pageSize['width'];
            $pdfHeight = $pageSize['height'];

            $w_orig = $pdfWidth;
            $h_orig = $pdfHeight;

            // Map frontend cropped coordinates based on page rotation
            // FPDF uses TOP-LEFT origin (Y=0 at top), same as the frontend.
            // So no Y-flip is needed.
            if ($rotation === 90) {
                // Page rotated 90° clockwise:
                // Frontend X (right) = page Y (down)
                // Frontend Y (down) = page left (w_orig - X)
                $x_crop = $y;
                $y_crop = $h_orig - ($x + $width);
                $w_crop = $height;
                $h_crop = $width;
            } elseif ($rotation === 180) {
                // Page rotated 180°:
                // Frontend X (right) = page left (w_orig - X)
                // Frontend Y (down) = page up (h_orig - Y)
                $x_crop = $w_orig - ($x + $width);
                $y_crop = $h_orig - ($y + $height);
                $w_crop = $width;
                $h_crop = $height;
            } elseif ($rotation === 270) {
                // Page rotated 270° clockwise:
                // Frontend X (right) = page Y (down)
                // Frontend Y (down) = page X (right)
                $x_crop = $w_orig - ($y + $height);
                $y_crop = $x;
                $w_crop = $height;
                $h_crop = $width;
            } else {
                // No rotation (0°): coordinates match directly
                $x_crop = $x;
                $y_crop = $y;
                $w_crop = $width;
                $h_crop = $height;
            }

            Log::debug('DEBUG CROP - Input: page=' . $currentPageNum . ', x=' . $x . ', y=' . $y . ', width=' . $width . ', height=' . $height . ', rotation=' . $rotation);
            Log::debug('DEBUG CROP - Page Size: pdfWidth=' . $pdfWidth . ', pdfHeight=' . $pdfHeight);
            Log::debug('DEBUG CROP - Transformed (' . $rotation . '): x_crop=' . $x_crop . ', y_crop=' . $y_crop . ', w_crop=' . $w_crop . ', h_crop=' . $h_crop);

            // Create output page with target crop dimensions
            $pdf->AddPage('', [$width, $height]);
            // Set clipping rectangle to the entire new page
            $pdf->ClippingRect(0, 0, $width, $height);

            // Place the imported page template, shifted so the crop area aligns with (0,0) of the new page
            // FPDF/FPDI uses top-left origin, so offset is simply -x_crop, -y_crop.
            // useTemplate internally converts Y to PDF bottom-left via ($this->h - y - templateHeight).
            $offsetX = -$x_crop;
            $offsetY = -$y_crop;
            Log::debug('DEBUG CROP - Final useTemplate offset: x=' . $offsetX . ', y=' . $offsetY);
            $pdf->useTemplate($tplIdx, $offsetX, $offsetY);

            $pdf->UnsetClipping();
        }

        $pdf->Output('F', $outputPath);

        return $outputPath;
    }
}

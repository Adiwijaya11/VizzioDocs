<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;

class PdfToPptxController extends Controller
{
    public function index()
    {
        return view('tools.pdf-to-pptx');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:51200'
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');

        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            $pages = $pdf->getPages();

            // Build PPTX using PHPPresentation library
            $outputPath = $tempDir . '/output.pptx';
            $this->buildPptx($pages, $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'converted.pptx',
                'pages' => count($pages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonversi PDF ke PowerPoint: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildPptx($pages, $outputPath)
    {
        $pptx = new PhpPresentation();

        // Remove default empty slide created by constructor
        $pptx->removeSlideByIndex(0);

        // PHPPresentation setters expect PIXELS (not EMU).
        // The writer internally converts pixels → EMU via pixelsToEmu() (× 9525).
        // Standard 4:3 slide = 8229600×5943600 EMU = 864×624 pixels.
        $margin = 48;                           // 0.5 inch = 457200 EMU = 48px
        $textWidth = 8229600 / 9525 - (2 * $margin); // 864 - 96 = 768px
        $textHeight = 5943600 / 9525 - (2 * $margin); // 624 - 96 = 528px
        $pageNumSize = 21;                      // ~200000 EMU = 21px

        foreach ($pages as $index => $page) {
            $text = $page->getText();
            $lines = array_filter(array_map('trim', explode("\n", $text)));

            $slide = $pptx->createSlide();

            // Create a text box for the content
            $shape = $slide->createRichTextShape();
            $shape->setWidthAndHeight($textWidth, $textHeight);
            $shape->setOffsetX($margin);
            $shape->setOffsetY($margin);
            $shape->setWrap(RichText::WRAP_SQUARE);
            $shape->setAutoFit(RichText::AUTOFIT_SHAPE);

            if (count($lines) > 0) {
                $isFirstLine = true;
                foreach ($lines as $line) {
                    if ($isFirstLine) {
                        $isFirstLine = false;
                    } else {
                        $shape->createParagraph();
                    }
                    $textRun = $shape->createTextRun($line);
                    $textRun->getFont()->setSize(14);
                    $textRun->getFont()->setName('Calibri');
                }
            } else {
                $textRun = $shape->createTextRun('(Halaman tanpa teks)');
                $textRun->getFont()->setSize(14);
                $textRun->getFont()->setColor(new Color('CCCCCC'));
                $textRun->getFont()->setName('Calibri');
            }

            // Slide number in bottom right corner
            $pageNumShape = $slide->createRichTextShape();
            $pageNumShape->setWidthAndHeight($pageNumSize, $pageNumSize);
            $pageNumShape->setOffsetX($margin + $textWidth - $pageNumSize);
            $pageNumShape->setOffsetY($margin + $textHeight - $pageNumSize);
            $pageNumShape->createParagraph();
            $pageNumRun = $pageNumShape->createTextRun('' . ($index + 1));
            $pageNumRun->getFont()->setSize(10);
            $pageNumRun->getFont()->setColor(new Color('999999'));
        }

        $writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
        $writer->save($outputPath);
    }
}

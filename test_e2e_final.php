<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;

echo "=== PHPPresentation E2E Test ===\n\n";

// STEP 1: Create PPTX exactly like the controller does
echo "--- STEP 1: Create PPTX ---\n";
$pptx = new PhpPresentation();
$pptx->removeSlideByIndex(0);

$margin = 457200; 
$textWidth = 8229600 - (2 * $margin);
$textHeight = 5943600 - (2 * $margin);

// Simulate 2 "pages" of text from a PDF
$pageTexts = [
    ["Hello World - Page 1", "This is a test", "Converting PDF to PPTX"],
    ["Page 2 Content", "More text here", "Hopefully it works!"],
];

foreach ($pageTexts as $pageIdx => $lines) {
    $slide = $pptx->createSlide();
    
    $shape = $slide->createRichTextShape();
    $shape->setWidthAndHeight($textWidth, $textHeight);
    $shape->setOffsetX($margin);
    $shape->setOffsetY($margin);
    $shape->setWrap(RichText::WRAP_SQUARE);
    $shape->setAutoFit(RichText::AUTOFIT_SHAPE);
    
    $isFirst = true;
    foreach ($lines as $line) {
        if (!$isFirst) {
            $shape->createParagraph();
        }
        $isFirst = false;
        $textRun = $shape->createTextRun($line);
        $textRun->getFont()->setSize(14);
        $textRun->getFont()->setName('Calibri');
    }
    
    // Page number
    $numShape = $slide->createRichTextShape();
    $numShape->setWidthAndHeight(200000, 200000);
    $numShape->setOffsetX($margin + $textWidth - 200000);
    $numShape->setOffsetY($margin + $textHeight - 200000);
    $numShape->createParagraph();
    $numRun = $numShape->createTextRun('' . ($pageIdx + 1));
    $numRun->getFont()->setSize(10);
    $numRun->getFont()->setColor(new Color('999999'));
}

$outputPath = __DIR__ . '/storage/app/private/vizziodocs/test_controller.pptx';
$writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
$writer->save($outputPath);
echo "PPTX saved: " . filesize($outputPath) . " bytes\n\n";

// STEP 2: Verify the slides XML
echo "--- STEP 2: Slide XML - looking for text content ---\n";
$zip = new ZipArchive();
$zip->open($outputPath);

foreach ([1, 2] as $idx) {
    $xml = $zip->getFromName("ppt/slides/slide{$idx}.xml");
    
    // Extract all <a:t> elements (text)
    preg_match_all('/<a:t[^>]*>([^<]*)<\/a:t>/', $xml, $matches);
    echo "Slide {$idx} text elements:\n";
    foreach ($matches[1] as $t) {
        echo "  -> \"$t\"\n";
    }
    
    // Also look for <p:sp> (shape) counts
    preg_match_all('/<p:sp>/', $xml, $spMatches);
    echo "  Shapes found: " . count($spMatches[0]) . "\n\n";
}

$zip->close();

// STEP 3: Now let's try to READ BACK the PPTX with PHPPresentation
echo "--- STEP 3: Read back PPTX with PHPPresentation ---\n";
try {
    $loadedPptx = IOFactory::load($outputPath);
    $loadedSlideCount = $loadedPptx->getSlideCount();
    echo "Slides loaded: $loadedSlideCount\n";
    
    foreach ($loadedPptx->getAllSlides() as $slideIndex => $slide) {
        $shapes = $slide->getShapeCollection();
        echo "Slide " . ($slideIndex + 1) . " has " . count($shapes) . " shapes\n";
        
        foreach ($shapes as $shapeIndex => $shape) {
            if ($shape instanceof RichText) {
                $text = $shape->getPlainText();
                echo "  Shape $shapeIndex text: \"" . str_replace(["\r", "\n"], ['', '|'], $text) . "\"\n";
            }
        }
    }
} catch (Exception $e) {
    echo "READ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== DONE ===\n";
echo "File ready: $outputPath\n";
echo "Open this file in PowerPoint to test!\n";

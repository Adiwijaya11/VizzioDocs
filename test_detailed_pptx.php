<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;

echo "=== Test PHPPresentation - Detailed Slide XML ===\n\n";

$pptx = new PhpPresentation();
$pptx->removeSlideByIndex(0);

echo "Default slide removed.\n";

// Slide 1
$slide = $pptx->createSlide();
$shape = $slide->createRichTextShape();
$shape->setWidthAndHeight(8000000, 5000000);
$shape->setOffsetX(500000);
$shape->setOffsetY(500000);

$textRun = $shape->createTextRun('Hello World - Page 1');
$textRun->getFont()->setSize(24);
$textRun->getFont()->setName('Calibri');

$shape->createParagraph();
$textRun2 = $shape->createTextRun('This is a test document');
$textRun2->getFont()->setSize(18);
$textRun2->getFont()->setName('Calibri');

// Slide 2
$slide2 = $pptx->createSlide();
$shape2 = $slide2->createRichTextShape();
$shape2->setWidthAndHeight(8000000, 5000000);
$shape2->setOffsetX(500000);
$shape2->setOffsetY(500000);

$textRun3 = $shape2->createTextRun('Slide 2 - More content');
$textRun3->getFont()->setSize(20);
$textRun3->getFont()->setName('Calibri');

$outputPath = __DIR__ . '/storage/app/private/vizziodocs/test_final.pptx';
echo "Saving to: $outputPath\n\n";

$writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
$writer->save($outputPath);
echo "SAVED! File size: " . filesize($outputPath) . " bytes\n\n";

// Extract and analyze slide XML
$zip = new ZipArchive();
$zip->open($outputPath);

echo "=== SLIDE 1 FULL XML ===\n";
echo $zip->getFromName('ppt/slides/slide1.xml') . "\n\n";

echo "=== SLIDE 2 FULL XML ===\n";
echo $zip->getFromName('ppt/slides/slide2.xml') . "\n\n";

echo "=== PRESENTATION.XML ===\n";
echo $zip->getFromName('ppt/presentation.xml') . "\n\n";

echo "=== SLIDE LAYOUT RELS ===\n";
echo $zip->getFromName('ppt/slideLayouts/_rels/slideLayout1.xml.rels') . "\n\n";

echo "=== SLIDE RELS (slide1.xml.rels) ===\n";
$slide1Rels = $zip->getFromName('ppt/slides/_rels/slide1.xml.rels');
echo $slide1Rels ?: "NOT FOUND" . "\n\n";

echo "=== SLIDE RELS (slide2.xml.rels) ===\n";
$slide2Rels = $zip->getFromName('ppt/slides/_rels/slide2.xml.rels');
echo $slide2Rels ?: "NOT FOUND" . "\n\n";

echo "=== SLIDE MASTER 1 ===\n";
echo $zip->getFromName('ppt/slideMasters/slideMaster1.xml') . "\n\n";

echo "=== SLIDE LAYOUT 1 ===\n";
echo $zip->getFromName('ppt/slideLayouts/slideLayout1.xml') . "\n\n";

// Check all files
echo "\n=== ALL FILES ===\n";
for ($i = 0; $i < $zip->numFiles; $i++) {
    echo "[" . $zip->getNameIndex($i) . "] (" . strlen($zip->getFromIndex($i)) . " bytes)\n";
}

$zip->close();

echo "\n=== DONE ===\n";

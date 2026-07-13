<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;

echo "=== Test PHPPresentation ===\n\n";

// Coba slide dengan teks sederhana
$pptx = new PhpPresentation();
$pptx->removeSlideByIndex(0);

echo "Default slide removed.\n";

// Cek layout
echo "Layout default: " . $pptx->getLayout()->getDocumentLayout() . "\n";
echo "CX: " . $pptx->getLayout()->getCX() . "\n";
echo "CY: " . $pptx->getLayout()->getCY() . "\n";

// Buat slide kustom dengan dimensi yang diketahui
$slide = $pptx->createSlide();
echo "Slide created.\n";

$shape = $slide->createRichTextShape();
$shape->setWidthAndHeight(8000000, 5000000);
$shape->setOffsetX(500000);
$shape->setOffsetY(500000);

$textRun = $shape->createTextRun('Hello World - Test PHPPresentation');
$textRun->getFont()->setSize(24);
$textRun->getFont()->setName('Calibri');

$shape->createParagraph();
$textRun2 = $shape->createTextRun('Line 2');
$textRun2->getFont()->setSize(18);
$textRun2->getFont()->setName('Calibri');

// Slide 2
$slide2 = $pptx->createSlide();
$shape2 = $slide2->createRichTextShape();
$shape2->setWidthAndHeight(8000000, 5000000);
$shape2->setOffsetX(500000);
$shape2->setOffsetY(500000);

$textRun3 = $shape2->createTextRun('Slide 2 Content');
$textRun3->getFont()->setSize(20);
$textRun3->getFont()->setName('Arial');

$outputPath = __DIR__ . '/storage/app/private/vizziodocs/test_simple.pptx';
echo "\nSaving to: $outputPath\n";

try {
    $writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
    $writer->save($outputPath);
    echo "SAVED! File size: " . filesize($outputPath) . " bytes\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Cek isi ZIP
echo "\n=== ZIP Contents ===\n";
$zip = new ZipArchive();
if ($zip->open($outputPath) === TRUE) {
    echo "Total files: " . $zip->numFiles . "\n\n";
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        echo str_repeat('-', 60) . "\n";
        echo "FILE: $name\n";
        echo str_repeat('-', 60) . "\n";
        $content = $zip->getFromIndex($i);
        echo $content . "\n\n";
    }
    $zip->close();
} else {
    echo "ERROR: Not a valid ZIP!\n";
}

// Cek dengan PHPOffice Common XML validity
echo "\n=== Cek XML validity slides ===\n";
$zip2 = new ZipArchive();
$zip2->open($outputPath);
for ($i = 1; $i <= 2; $i++) {
    $slideXml = $zip2->getFromName("ppt/slides/slide{$i}.xml");
    if ($slideXml) {
        $dom = new DOMDocument();
        $dom->loadXML($slideXml);
        if ($dom->validate()) {
            echo "Slide {$i} XML: VALID\n";
        } else {
            echo "Slide {$i} XML: INVALID (but DTD may not be available)\n";
        }
        echo "Slide {$i} length: " . strlen($slideXml) . " bytes\n";
    }
}
$zip2->close();

echo "\n=== DONE ===\n";

<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;

echo "=== PHPPresentation Version Check ===\n";
$ref = new ReflectionClass('PhpOffice\PhpPresentation\PhpPresentation');
echo "PHPPresentation installed at: " . str_replace('PhpPresentation.php', '', $ref->getFileName()) . "\n\n";

echo "=== Membuat PPTX dengan berbagai fitur ===\n";

$pptx = new PhpPresentation();
$pptx->removeSlideByIndex(0);

// Set document layout to widescreen (16:9)
$pptx->getLayout()->setDocumentLayout('custom');
$pptx->getLayout()->setDocumentLayoutType('screen4x3');
echo "Layout type: " . $pptx->getLayout()->getDocumentLayoutType() . "\n";
echo "Layout name: " . $pptx->getLayout()->getDocumentLayout() . "\n";

$margin = 457200;
$textWidth = 8229600 - (2 * $margin);
$textHeight = 5943600 - (2 * $margin);

// Create slide 1 with multiple text lines
echo "\n--- Slide 1 ---\n";
$slide = $pptx->createSlide();
$shape = $slide->createRichTextShape();
$shape->setWidthAndHeight($textWidth, $textHeight);
$shape->setOffsetX($margin);
$shape->setOffsetY($margin);
$shape->setWrap(RichText::WRAP_SQUARE);
$shape->setAutoFit(RichText::AUTOFIT_SHAPE);

// Check initial paragraph count
$initialParagraphs = $shape->getParagraphs();
echo "Initial paragraph count: " . count($initialParagraphs) . "\n";

// First line uses the default paragraph
$textRun = $shape->createTextRun('Line 1: Hello World');
$textRun->getFont()->setSize(24);
$textRun->getFont()->setName('Calibri');
$textRun->getFont()->setBold(true);
echo "Added: Line 1\n";

// Second line
$shape->createParagraph();
$textRun2 = $shape->createTextRun('Line 2: This is a test document');
$textRun2->getFont()->setSize(18);
$textRun2->getFont()->setName('Calibri');
echo "Added: Line 2\n";

// Third line
$shape->createParagraph();
$textRun3 = $shape->createTextRun('Line 3: Converting PDF to PowerPoint');
$textRun3->getFont()->setSize(16);
$textRun3->getFont()->setName('Calibri');
echo "Added: Line 3\n";

// Paragraph count after
$finalParagraphs = $shape->getParagraphs();
echo "Final paragraph count: " . count($finalParagraphs) . "\n";

// Page number
$pageNumShape = $slide->createRichTextShape();
$pageNumShape->setWidthAndHeight(200000, 200000);
$pageNumShape->setOffsetX($margin + $textWidth - 200000);
$pageNumShape->setOffsetY($margin + $textHeight - 200000);
$pageNumShape->createParagraph();
$pageNumRun = $pageNumShape->createTextRun('1');
$pageNumRun->getFont()->setSize(10);
$pageNumRun->getFont()->setColor(new Color('999999'));
echo "Added: Page number\n";

echo "\n--- Slide 2 ---\n";
$slide2 = $pptx->createSlide();
$shape2 = $slide2->createRichTextShape();
$shape2->setWidthAndHeight($textWidth, $textHeight);
$shape2->setOffsetX($margin);
$shape2->setOffsetY($margin);
$shape2->setWrap(RichText::WRAP_SQUARE);
$shape2->setAutoFit(RichText::AUTOFIT_SHAPE);

$textRun2_1 = $shape2->createTextRun('Slide 2 Content');
$textRun2_1->getFont()->setSize(28);
$textRun2_1->getFont()->setName('Calibri');
$textRun2_1->getFont()->setBold(true);
$textRun2_1->getFont()->setColor(new Color('2E75B6'));

$shape2->createParagraph();
$textRun2_2 = $shape2->createTextRun('More text on the second slide.');
$textRun2_2->getFont()->setSize(16);
$textRun2_2->getFont()->setName('Calibri');

$shape2->createParagraph();
$textRun2_3 = $shape2->createTextRun('Hopefully this works correctly!');
$textRun2_3->getFont()->setSize(16);
$textRun2_3->getFont()->setName('Calibri');

$pageNumShape2 = $slide2->createRichTextShape();
$pageNumShape2->setWidthAndHeight(200000, 200000);
$pageNumShape2->setOffsetX($margin + $textWidth - 200000);
$pageNumShape2->setOffsetY($margin + $textHeight - 200000);
$pageNumShape2->createParagraph();
$pageNumRun2 = $pageNumShape2->createTextRun('2');
$pageNumRun2->getFont()->setSize(10);
$pageNumRun2->getFont()->setColor(new Color('999999'));

// Save
$outputPath = __DIR__ . '/storage/app/private/vizziodocs/test_pptx.pptx';
echo "\n=== Saving PPTX ===\n";
echo "Output: $outputPath\n";

try {
    $writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
    $writer->save($outputPath);
    echo "SAVED SUCCESSFULLY!\n";
    echo "File size: " . filesize($outputPath) . " bytes\n";
} catch (Exception $e) {
    echo "SAVE ERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

// Verify PPTX structure
echo "\n=== Verifying PPTX structure ===\n";
$zip = new ZipArchive();
if ($zip->open($outputPath) === TRUE) {
    echo "ZIP OK - " . $zip->numFiles . " files\n\n";
    
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        $content = $zip->getFromIndex($i);
        $size = strlen($content);
        echo "  [$name] ($size bytes)\n";
        
        // Show first 200 chars of XML files for inspection
        if (strpos($name, '.xml') !== false) {
            echo "    Preview: " . substr($content, 0, 200) . "\n";
        }
    }
    
    // Specifically check if slides have content
    echo "\n--- Slide XML content check ---\n";
    for ($i = 1; $i <= 2; $i++) {
        $slideXml = $zip->getFromName("ppt/slides/slide{$i}.xml");
        if ($slideXml) {
            echo "Slide {$i}: " . strlen($slideXml) . " bytes\n";
            
            // Look for text content
            $dom = new DOMDocument();
            $dom->loadXML($slideXml);
            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
            $xpath->registerNamespace('p', 'http://schemas.openxmlformats.org/presentationml/2006/main');
            $xpath->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
            
            // Search for text elements
            $textNodes = $xpath->query('//a:t');
            echo "  Text runs found: " . $textNodes->length . "\n";
            foreach ($textNodes as $node) {
                echo "  -> \"" . $node->nodeValue . "\"\n";
            }
        } else {
            echo "Slide {$i}: NOT FOUND\n";
        }
    }
    
    $zip->close();
} else {
    echo "ERROR: Not a valid ZIP archive!\n";
    echo "File header: " . bin2hex(substr(file_get_contents($outputPath), 0, 20)) . "\n";
}

// Clean up test files except the pptx
echo "\n=== DONE ===\n";
echo "Check the PPTX file at: $outputPath\n";

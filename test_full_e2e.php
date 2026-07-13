<?php
require __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;

// ========== STEP 1: Create sample PDF to test with ==========
echo "=== STEP 1: Buat PDF sample ===\n";

$samplePdfPath = __DIR__ . '/storage/app/private/vizziodocs/sample.pdf';
$pdfContent = <<<'PDF'
%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R 5 0 R] /Count 2 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 7 0 R >> >> >>
endobj
4 0 obj
<< /Length 128 >>
stream
BT
/F1 24 Tf
100 700 Td
(Hello World - Page 1) Tj
ET
BT
/F1 14 Tf
100 650 Td
(Ini adalah teks percobaan) Tj
ET
BT
/F1 14 Tf
100 600 Td
(untuk testing PDF ke PPTX.) Tj
ET
endstream
endobj
5 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 6 0 R /Resources << /Font << /F1 7 0 R >> >> >>
endobj
6 0 obj
<< /Length 96 >>
stream
BT
/F1 24 Tf
100 700 Td
(Page 2 - Content) Tj
ET
BT
/F1 14 Tf
100 650 Td
(Testing halaman kedua.) Tj
ET
endstream
endobj
7 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
xref
0 8
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000266 00000 n 
0000000446 00000 n 
0000000597 00000 n 
0000000742 00000 n 
trailer
<< /Size 8 /Root 1 0 R >>
startxref
804
%%EOF
PDF;
file_put_contents($samplePdfPath, $pdfContent);
echo "Sample PDF created: $samplePdfPath\n";

// ========== STEP 2: Parse PDF ==========
echo "\n=== STEP 2: Parse PDF ===\n";
try {
    $parser = new Parser();
    $pdf = $parser->parseFile($samplePdfPath);
    $pages = $pdf->getPages();
    echo "Pages found: " . count($pages) . "\n";
    
    foreach ($pages as $i => $page) {
        $text = $page->getText();
        echo "Page " . ($i+1) . " text: [" . substr($text, 0, 100) . "]\n";
    }
} catch (Exception $e) {
    echo "PDF Parser Error: " . $e->getMessage() . "\n";
    exit(1);
}

// ========== STEP 3: Build PPTX using EXACT controller logic ==========
echo "\n=== STEP 3: Build PPTX (EXACT controller logic) ===\n";
try {
    $pptx = new PhpPresentation();
    $pptx->removeSlideByIndex(0);
    
    $margin = 457200;
    $textWidth = 8229600 - (2 * $margin);
    $textHeight = 5943600 - (2 * $margin);
    
    echo "Slide dimensions: textWidth=$textWidth, textHeight=$textHeight\n";
    
    foreach ($pages as $index => $page) {
        $text = $page->getText();
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        
        echo "Page " . ($index + 1) . ": " . count($lines) . " lines\n";
        
        $slide = $pptx->createSlide();
        
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
        
        $pageNumShape = $slide->createRichTextShape();
        $pageNumShape->setWidthAndHeight(200000, 200000);
        $pageNumShape->setOffsetX($margin + $textWidth - 200000);
        $pageNumShape->setOffsetY($margin + $textHeight - 200000);
        $pageNumShape->createParagraph();
        $pageNumRun = $pageNumShape->createTextRun('' . ($index + 1));
        $pageNumRun->getFont()->setSize(10);
        $pageNumRun->getFont()->setColor(new Color('999999'));
    }
    
    $outputPath = __DIR__ . '/storage/app/private/vizziodocs/output.pptx';
    $writer = IOFactory::createWriter($pptx, 'PowerPoint2007');
    $writer->save($outputPath);
    
    echo "PPTX saved to: $outputPath\n";
    echo "File size: " . filesize($outputPath) . " bytes\n";
    
} catch (Exception $e) {
    echo "PHPPresentation Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// ========== STEP 4: Verify PPTX structure ==========
echo "\n=== STEP 4: Verify PPTX structure ===\n";
$zip = new ZipArchive();
if ($zip->open($outputPath) === TRUE) {
    echo "PPTX is a valid ZIP archive!\n";
    echo "Files in PPTX:\n";
    for ($i = 0; $i < $zip->numFiles; $i++) {
        echo "  - " . $zip->getNameIndex($i) . " (" . $zip->getFromIndex($i) . ")\n";
    }
    
    // Check [Content_Types].xml
    $contentTypes = $zip->getFromName('[Content_Types].xml');
    echo "\n[Content_Types].xml exists: " . ($contentTypes !== false ? "YES" : "NO") . "\n";
    
    // Check presentation.xml
    $pres = $zip->getFromName('ppt/presentation.xml');
    echo "ppt/presentation.xml exists: " . ($pres !== false ? "YES" : "NO") . "\n";
    
    // Check slides
    for ($i = 1; $i <= count($pages); $i++) {
        $slideXml = $zip->getFromName("ppt/slides/slide{$i}.xml");
        echo "ppt/slides/slide{$i}.xml exists: " . ($slideXml !== false ? "YES" : "NO") . "\n";
    }
    
    $zip->close();
} else {
    echo "ERROR: Output is NOT a valid ZIP!\n";
    echo "First bytes: " . bin2hex(substr(file_get_contents($outputPath), 0, 50)) . "\n";
}

echo "\n=== DONE ===\n";

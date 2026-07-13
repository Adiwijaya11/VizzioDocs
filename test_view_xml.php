<?php
$zip = new ZipArchive();
$zip->open(__DIR__ . '/storage/app/private/vizziodocs/test_controller.pptx');

for ($i = 1; $i <= 2; $i++) {
    echo "=== SLIDE $i XML ===\n";
    echo $zip->getFromName("ppt/slides/slide{$i}.xml");
    echo "\n\n";
}

// Also check content types
echo "=== Content_Types.xml ===\n";
echo $zip->getFromName('[Content_Types].xml');
echo "\n\n";

$zip->close();

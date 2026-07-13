<?php
$js = @file_get_contents('http://127.0.0.1:5173/resources/js/app.js');
if ($js === false) {
    $js = @file_get_contents('http://[::1]:5173/resources/js/app.js');
}
if ($js === false) {
    echo "FAILED: Cannot fetch JS from Vite\n";
    exit(1);
}
echo "JS Loaded: " . strlen($js) . " bytes\n";
echo substr($js, 0, 150) . "\n";
echo "---\n";
echo "Contains 'displayResultDetails': " . (strpos($js, 'displayResultDetails') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 'download-btn': " . (strpos($js, 'download-btn') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 'success-section': " . (strpos($js, 'success-section') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 'DOMContentLoaded': " . (strpos($js, 'DOMContentLoaded') !== false ? 'YES' : 'NO') . "\n";

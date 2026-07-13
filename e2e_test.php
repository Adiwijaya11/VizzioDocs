<?php
// Test the full compress flow against port 8001
$baseUrl = 'http://127.0.0.1:8001';

// Step 1: Get CSRF token from compress page
$html = file_get_contents("$baseUrl/compress");
preg_match('/name="_token" value="([^"]+)"/', $html, $m);
$token = $m[1] ?? 'MISSING';
echo "CSRF Token: " . substr($token, 0, 20) . "...\n";

// Check if Vite JS is referenced
$hasVite = strpos($html, '5173') !== false;
echo "Has Vite reference (5173): " . ($hasVite ? 'YES' : 'NO') . "\n";
$hasAppJs = strpos($html, 'app.js') !== false;
echo "Has app.js: " . ($hasAppJs ? 'YES' : 'NO') . "\n";

// Step 2: Check Vite is serving
$viteJs = @file_get_contents('http://127.0.0.1:5173/resources/js/app.js');
echo "Vite serving JS: " . (strlen($viteJs) > 0 ? 'YES (' . strlen($viteJs) . ' bytes)' : 'NO') . "\n";

// Step 3: Create a minimal test PDF
$testPdf = __DIR__ . '/test_upload_compress.pdf';
$pdfContent = '%PDF-1.4
1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj
2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj
3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj
xref
0 4
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
trailer<</Size 4/Root 1 0 R>>
startxref
190
%%EOF';
file_put_contents($testPdf, $pdfContent);
echo "Test PDF created: " . filesize($testPdf) . " bytes\n";

// Step 4: POST to compress with standard mode
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/compress");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'file' => new CURLFile($testPdf, 'application/pdf', 'test.pdf'),
    '_token' => $token,
    'mode' => 'standard'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "\n=== COMPRESS RESULT ===\n";
echo "HTTP Code: $httpCode\n";
if ($error) echo "Curl Error: $error\n";
echo "Response: " . ($response ? substr($response, 0, 500) : 'EMPTY') . "\n";

// Step 5: If success, test download
if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['download_url'])) {
        $downloadUrl = $data['download_url'];
        echo "\n=== DOWNLOAD TEST ===\n";
        echo "Download URL: $downloadUrl\n";
        
        if (strpos($downloadUrl, 'http') !== 0) {
            $downloadUrl = $baseUrl . $downloadUrl;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $downloadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $dlResponse = curl_exec($ch);
        $dlHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $dlSize = strlen($dlResponse);
        curl_close($ch);
        
        echo "Download HTTP: $dlHttpCode\n";
        echo "Download Size: $dlSize bytes\n";
        echo "Download Success: " . ($dlHttpCode == 200 ? 'YES' : 'NO') . "\n";
    }
}

// Cleanup
@unlink($testPdf);
echo "\nDone.\n";

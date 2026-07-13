<?php
require 'vendor/autoload.php';

// Test FPDF
$fpdf = new FPDF();
echo 'FPDF class: ' . get_class($fpdf) . PHP_EOL;

// Test FPDI
$fpdi = new \setasign\Fpdi\Fpdi();
echo 'FPDI class: ' . get_class($fpdi) . PHP_EOL;

// Test RotatedFpdi
$rotated = new \App\Services\RotatedFpdi();
echo 'RotatedFpdi class: ' . get_class($rotated) . PHP_EOL;

echo 'All classes loaded successfully!' . PHP_EOL;

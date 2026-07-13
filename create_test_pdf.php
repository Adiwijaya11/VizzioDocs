<?php
require __DIR__ . '/vendor/autoload.php';

// FPDI extends FPDF, and FPDF can create PDFs from scratch
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 24);
$pdf->Cell(0, 20, 'VizzioDocs Test Document', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Helvetica', '', 12);
$pdf->MultiCell(0, 8, 'This is a sample PDF file created for testing the VizzioDocs document conversion tools. It contains enough content to verify that compression and other operations work correctly.');
$pdf->Ln(10);
$pdf->SetFont('Helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Page 1 - Test content', 0, 1, 'C');

$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 24);
$pdf->Cell(0, 20, 'Second Page', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Helvetica', '', 12);
$pdf->MultiCell(0, 8, 'This is the second page of the test document. It contains additional text to make the PDF multi-page, which is useful for testing split, merge, and compression tools.');
$pdf->Ln(10);
$pdf->SetFont('Helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Page 2 - Additional content', 0, 1, 'C');

$outputPath = __DIR__ . '/test-sample.pdf';
$pdf->Output('F', $outputPath);

echo 'OK: ' . filesize($outputPath) . ' bytes - ' . $outputPath;

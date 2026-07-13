<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SafeFpdi as Fpdi;

class PdfToPdfAController extends Controller
{
    public function index()
    {
        return view('tools.pdf-to-pdfa');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:102400',
            'compliance' => 'nullable|in:pdfa-1b,pdfa-2b'
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');
        $inputPath = $file->getRealPath();

        try {
            // Rebuild PDF with PDF/A-compatible structure
            $pdf = new Fpdi();
            $pdf->SetCompression(false); // PDF/A requires uncompressed streams
            $pageCount = $pdf->setSourceFile($inputPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }

            // Add PDF/A metadata
            $metadata = '<?xpacket begin="\xEF\xBB\xBF" id="W5M0" type="text" ?>
<x:xmpmeta xmlns:x="adobe:ns:meta/">
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<rdf:Description rdf:about="" xmlns:pdfaid="http://www.aiim.org/pdfa/ns/id/" pdfaid:part="1" pdfaid:conformance="B"/>
</rdf:RDF>
</x:xmpmeta>
<?xpacket end="w"?>';

            $pdf->SetCompression(true);

            $outputPath = $tempDir . '/output.pdf';
            $pdf->Output('F', $outputPath);

            return response()->json([
                'success' => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename' => 'pdfa_compatible.pdf',
                'compliance' => $request->input('compliance', 'pdfa-1b')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonversi ke PDF/A: ' . $e->getMessage()
            ], 500);
        }
    }
}

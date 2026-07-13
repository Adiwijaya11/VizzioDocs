<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;
use ZipArchive;
use SimpleXMLElement;

class PptxToPdfController extends Controller
{
    public function index()
    {
        return view('tools.pptx-to-pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $allowedMimes = [
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/vnd.ms-powerpoint',
                        'application/zip',
                        'application/octet-stream',
                        'application/x-zip-compressed',
                    ];

                    if ($extension !== 'pptx') {
                        $fail('File harus berformat .pptx');
                        return;
                    }

                    $mime = $value->getMimeType();
                    if (!in_array($mime, $allowedMimes)) {
                        $fail('File harus berformat .pptx (MIME type tidak didukung: ' . $mime . ')');
                    }
                }
            ]
        ]);

        $sessionId = Str::uuid()->toString();
        $tempDir   = storage_path('app/private/vizziodocs/' . $sessionId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $file = $request->file('file');

        try {
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) !== true) {
                throw new \Exception('Tidak dapat membuka file PPTX.');
            }

            // ── 1. Extract media (images) ──────────────────────────────────
            $mediaDir    = $tempDir . '/media';
            mkdir($mediaDir, 0755, true);
            $mediaBase64 = [];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (strpos($name, 'ppt/media/') === 0 && $name !== 'ppt/media/') {
                    $basename = basename($name);
                    $data     = $zip->getFromIndex($i);
                    if ($data !== false) {
                        $ext  = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
                        $mime = $this->getMimeFromExt($ext);
                        if ($mime) {
                            $mediaBase64[$basename] = 'data:' . $mime . ';base64,' . base64_encode($data);
                        }
                    }
                }
            }

            // ── 2. Parse presentation dimensions ──────────────────────────
            $presXml = $zip->getFromName('ppt/presentation.xml');
            [$slideWidthEmu, $slideHeightEmu] = $this->parsePresentationSize($presXml);

            // ── 3. Parse slides ────────────────────────────────────────────
            $slides    = [];
            $slideIndex = 1;

            while (true) {
                $slideName = "ppt/slides/slide{$slideIndex}.xml";
                $slideXml  = $zip->getFromName($slideName);
                if ($slideXml === false) break;

                // Parse slide relationships to map rId → media filename
                $relXml    = $zip->getFromName("ppt/slides/_rels/slide{$slideIndex}.xml.rels");
                $relsMap   = $this->parseRelationships($relXml);

                $slides[] = $this->parseSlide($slideXml, $relsMap, $mediaBase64, $slideWidthEmu, $slideHeightEmu);
                $slideIndex++;
            }

            $zip->close();

            if (empty($slides)) {
                throw new \Exception('Tidak ada slide yang ditemukan dalam file PPTX.');
            }

            // ── 4. Build HTML ──────────────────────────────────────────────
            $html = $this->buildHtml($slides, $slideWidthEmu, $slideHeightEmu);

            // ── 5. Render PDF with Dompdf ──────────────────────────────────
            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('chroot', $tempDir);

            // Determine page orientation from slide dimensions
            $isLandscape = $slideWidthEmu > $slideHeightEmu;

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', $isLandscape ? 'landscape' : 'portrait');
            $dompdf->render();

            $outputPath = $tempDir . '/output.pdf';
            file_put_contents($outputPath, $dompdf->output());

            return response()->json([
                'success'      => true,
                'download_url' => route('download', ['id' => $sessionId]),
                'filename'     => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
                'slides'       => count($slides),
            ]);

        } catch (\Exception $e) {
            Log::error('PPTX to PDF conversion failed: ' . $e->getMessage(), [
                'session_id' => $sessionId ?? null,
                'trace'      => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonversi PowerPoint ke PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function getMimeFromExt(string $ext): ?string
    {
        return match ($ext) {
            'png'           => 'image/png',
            'jpg', 'jpeg'   => 'image/jpeg',
            'gif'           => 'image/gif',
            'bmp'           => 'image/bmp',
            'webp'          => 'image/webp',
            'svg'           => 'image/svg+xml',
            'emf', 'wmf'    => null, // skip EMF/WMF – not supported by browsers
            default         => null,
        };
    }

    private function parsePresentationSize(?string $xml): array
    {
        // Default: widescreen 10 in × 7.5 in in EMU (1 inch = 914400 EMU)
        $defaultW = 9144000;
        $defaultH = 6858000;

        if (!$xml) return [$defaultW, $defaultH];

        try {
            $doc = new SimpleXMLElement($xml, LIBXML_NOERROR | LIBXML_NOWARNING);
            $doc->registerXPathNamespace('p', 'http://schemas.openxmlformats.org/presentationml/2006/main');
            $doc->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

            $sldSz = $doc->xpath('//p:sldSz');
            if (!empty($sldSz)) {
                $w = (int) $sldSz[0]->attributes()['cx'];
                $h = (int) $sldSz[0]->attributes()['cy'];
                if ($w > 0 && $h > 0) return [$w, $h];
            }
        } catch (\Exception $e) {}

        return [$defaultW, $defaultH];
    }

    private function parseRelationships(?string $relXml): array
    {
        $map = [];
        if (!$relXml) return $map;

        try {
            $xml = new SimpleXMLElement($relXml, LIBXML_NOERROR | LIBXML_NOWARNING);
            foreach ($xml->Relationship as $rel) {
                $rId    = (string) $rel->attributes()['Id'];
                $target = (string) $rel->attributes()['Target'];
                $map[$rId] = basename($target);
            }
        } catch (\Exception $e) {}

        return $map;
    }

    private function parseSlide(string $slideXml, array $relsMap, array $mediaBase64, int $slideW, int $slideH): array
    {
        $elements = [];

        try {
            $xml = new SimpleXMLElement($slideXml, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_PARSEHUGE);

            $ns = [
                'p'  => 'http://schemas.openxmlformats.org/presentationml/2006/main',
                'a'  => 'http://schemas.openxmlformats.org/drawingml/2006/main',
                'r'  => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
                'xdr'=> 'http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing',
                'p14'=> 'http://schemas.microsoft.com/office/powerpoint/2010/main',
            ];

            foreach ($ns as $prefix => $uri) {
                $xml->registerXPathNamespace($prefix, $uri);
            }

            // Iterate over all shape trees (spTree)
            $spTrees = $xml->xpath('//p:spTree');

            foreach ($spTrees as $spTree) {
                // ── Text shapes (sp) ──────────────────────────────────────
                foreach ($spTree->children('http://schemas.openxmlformats.org/presentationml/2006/main')->sp ?? [] as $sp) {
                    $el = $this->parseTextShape($sp, $slideW, $slideH);
                    if ($el) $elements[] = $el;
                }

                // ── Picture shapes (pic) ──────────────────────────────────
                foreach ($spTree->children('http://schemas.openxmlformats.org/presentationml/2006/main')->pic ?? [] as $pic) {
                    $el = $this->parsePictureShape($pic, $relsMap, $mediaBase64, $slideW, $slideH);
                    if ($el) $elements[] = $el;
                }

                // ── Group shapes (grpSp) – recurse one level ──────────────
                foreach ($spTree->children('http://schemas.openxmlformats.org/presentationml/2006/main')->grpSp ?? [] as $grpSp) {
                    foreach ($grpSp->children('http://schemas.openxmlformats.org/presentationml/2006/main')->sp ?? [] as $sp) {
                        $el = $this->parseTextShape($sp, $slideW, $slideH);
                        if ($el) $elements[] = $el;
                    }
                    foreach ($grpSp->children('http://schemas.openxmlformats.org/presentationml/2006/main')->pic ?? [] as $pic) {
                        $el = $this->parsePictureShape($pic, $relsMap, $mediaBase64, $slideW, $slideH);
                        if ($el) $elements[] = $el;
                    }
                }
            }

        } catch (\Exception $e) {
            Log::warning('Slide parse warning: ' . $e->getMessage());
        }

        // Sort by vertical position (top-to-bottom reading order)
        usort($elements, fn($a, $b) => $a['top'] <=> $b['top']);

        return $elements;
    }

    private function getSpxfrmOffsets(object $sp, int $slideW, int $slideH): array
    {
        // Navigate to spPr/xfrm/off and ext
        $nsP = 'http://schemas.openxmlformats.org/presentationml/2006/main';
        $nsA = 'http://schemas.openxmlformats.org/drawingml/2006/main';

        $spPr = $sp->children($nsP)->spPr ?? null;
        if (!$spPr) {
            $spPr = $sp->spPr ?? null;
        }

        $xfrm = null;
        if ($spPr) {
            $xfrm = $spPr->children($nsA)->xfrm ?? null;
            if (!$xfrm) {
                $xfrm = $spPr->xfrm ?? null;
            }
        }

        if (!$xfrm) return [0, 0, 100, 20]; // fallback %

        $off = $xfrm->children($nsA)->off ?? $xfrm->off ?? null;
        $ext = $xfrm->children($nsA)->ext ?? $xfrm->ext ?? null;

        $x = $off ? (float)($off->attributes()['x'] ?? 0) : 0;
        $y = $off ? (float)($off->attributes()['y'] ?? 0) : 0;
        $w = $ext ? (float)($ext->attributes()['cx'] ?? $slideW / 2) : $slideW / 2;
        $h = $ext ? (float)($ext->attributes()['cy'] ?? $slideH / 4) : $slideH / 4;

        // Convert EMU → percentage of slide dimensions
        return [
            round($x / $slideW * 100, 3),
            round($y / $slideH * 100, 3),
            round($w / $slideW * 100, 3),
            round($h / $slideH * 100, 3),
        ];
    }

    private function parseTextShape(object $sp, int $slideW, int $slideH): ?array
    {
        $nsA = 'http://schemas.openxmlformats.org/drawingml/2006/main';
        $nsP = 'http://schemas.openxmlformats.org/presentationml/2006/main';

        [$left, $top, $width, $height] = $this->getSpxfrmOffsets($sp, $slideW, $slideH);

        $txBody = $sp->children($nsP)->txBody ?? $sp->txBody ?? null;
        if (!$txBody) return null;

        $txBodyA = $txBody->children($nsA);
        $paragraphs = $txBodyA->p ?? $txBody->p ?? [];

        $paraHtmlArr = [];

        foreach ($paragraphs as $para) {
            $pA  = $para->children($nsA);
            $pPr = $pA->pPr ?? $para->pPr ?? null;

            // Paragraph alignment
            $align = 'left';
            if ($pPr) {
                $algn = (string) ($pPr->attributes()['algn'] ?? '');
                $align = match ($algn) {
                    'ctr', 'center' => 'center',
                    'r', 'right'    => 'right',
                    'just'          => 'justify',
                    default         => 'left',
                };
            }

            $runs    = $pA->r ?? $para->r ?? [];
            $runHtml = '';

            foreach ($runs as $run) {
                $rA   = $run->children($nsA);
                $text = (string) ($rA->t ?? $run->t ?? '');
                if ($text === '') continue;

                $rPr = $rA->rPr ?? $run->rPr ?? null;

                $fontSize   = 18; // default pt
                $bold       = false;
                $italic     = false;
                $underline  = false;
                $color      = '#000000';

                if ($rPr) {
                    $attrs = $rPr->attributes();
                    if (isset($attrs['sz'])) {
                        $fontSize = round((int)$attrs['sz'] / 100);
                    }
                    if (isset($attrs['b']) && (string)$attrs['b'] !== '0') $bold = true;
                    if (isset($attrs['i']) && (string)$attrs['i'] !== '0') $italic = true;
                    if (isset($attrs['u']) && (string)$attrs['u'] !== 'none') $underline = true;

                    // Color
                    $solidFill = $rPr->children($nsA)->solidFill ?? $rPr->solidFill ?? null;
                    if ($solidFill) {
                        $srgb = $solidFill->children($nsA)->srgbClr ?? $solidFill->srgbClr ?? null;
                        if ($srgb) {
                            $hex = (string) ($srgb->attributes()['val'] ?? '');
                            if (strlen($hex) === 6) $color = '#' . $hex;
                        }
                    }
                }

                // Clamp font size to reasonable range
                $fontSize = max(8, min($fontSize, 60));

                $style = "font-size:{$fontSize}pt;color:{$color};";
                if ($bold) $style     .= 'font-weight:bold;';
                if ($italic) $style   .= 'font-style:italic;';
                if ($underline) $style .= 'text-decoration:underline;';

                $runHtml .= '<span style="' . $style . '">' . htmlspecialchars($text) . '</span>';
            }

            if ($runHtml !== '') {
                $paraHtmlArr[] = '<p style="margin:2px 0;text-align:' . $align . ';line-height:1.4;">' . $runHtml . '</p>';
            } else {
                $paraHtmlArr[] = '<p style="margin:4px 0;">&nbsp;</p>';
            }
        }

        if (empty($paraHtmlArr)) return null;

        return [
            'type'    => 'text',
            'left'    => $left,
            'top'     => $top,
            'width'   => $width,
            'height'  => $height,
            'content' => implode('', $paraHtmlArr),
        ];
    }

    private function parsePictureShape(object $pic, array $relsMap, array $mediaBase64, int $slideW, int $slideH): ?array
    {
        $nsP = 'http://schemas.openxmlformats.org/presentationml/2006/main';
        $nsA = 'http://schemas.openxmlformats.org/drawingml/2006/main';
        $nsPic = 'http://schemas.openxmlformats.org/drawingml/2006/picture';
        $nsR = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

        [$left, $top, $width, $height] = $this->getSpxfrmOffsets($pic, $slideW, $slideH);

        // Get relationship ID
        $blipFill = $pic->children($nsPic)->blipFill ?? $pic->blipFill ?? null;
        if (!$blipFill) return null;

        $blip = $blipFill->children($nsA)->blip ?? $blipFill->blip ?? null;
        if (!$blip) return null;

        $rId = null;
        foreach ($blip->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships') as $key => $val) {
            if ($key === 'embed') {
                $rId = (string) $val;
                break;
            }
        }

        if (!$rId || !isset($relsMap[$rId])) return null;

        $mediaFile = $relsMap[$rId];
        if (!isset($mediaBase64[$mediaFile])) return null;

        return [
            'type'   => 'image',
            'left'   => $left,
            'top'    => $top,
            'width'  => $width,
            'height' => $height,
            'src'    => $mediaBase64[$mediaFile],
        ];
    }

    /**
     * Resolve overlapping text boxes: sort by top, push each box below
     * the previous one if they would intersect.
     */
    private function resolveOverlaps(array $pixelEls, int $canvasH): array
    {
        // Separate images and text
        $images = array_values(array_filter($pixelEls, fn($e) => $e['type'] === 'image'));
        $texts  = array_values(array_filter($pixelEls, fn($e) => $e['type'] === 'text'));

        // Sort text top→bottom
        usort($texts, fn($a, $b) => $a['topPx'] <=> $b['topPx']);

        // Push down overlapping text boxes (use estimated line height ~20px per para)
        for ($i = 1; $i < count($texts); $i++) {
            $prev      = $texts[$i - 1];
            $prevBottom = $prev['topPx'] + $prev['heightPx'] + 6; // 6px gap

            if ($texts[$i]['topPx'] < $prevBottom) {
                $texts[$i]['topPx'] = $prevBottom;
            }

            // Keep within canvas
            if ($texts[$i]['topPx'] > $canvasH - 20) {
                $texts[$i]['topPx'] = $canvasH - 20;
            }
        }

        // Images first (background), then text (foreground)
        return array_merge($images, $texts);
    }

    private function buildHtml(array $slides, int $slideW, int $slideH): string
    {
        $isLandscape = $slideW > $slideH;

        // Use fixed pixel canvas that matches A4 page proportions
        if ($isLandscape) {
            $canvasW = 1120;
            $canvasH = (int) round($slideH / $slideW * $canvasW);
        } else {
            $canvasW = 794;
            $canvasH = (int) round($slideH / $slideW * $canvasW);
        }

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { background: #fff; font-family: DejaVu Sans, Arial, sans-serif; }

  .slide-page {
    page-break-after: always;
    position: relative;
    width: {$canvasW}px;
    height: {$canvasH}px;
    overflow: hidden;
    background: #ffffff;
  }

  .slide-page:last-child { page-break-after: avoid; }

  .el {
    position: absolute;
  }

  .text-el {
    overflow: visible;
    word-wrap: break-word;
    padding: 2px 4px;
  }

  .text-el p {
    margin: 0 0 2px 0;
    padding: 0;
    line-height: 1.35;
  }

  .img-el img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }
</style>
</head>
<body>
HTML;

        foreach ($slides as $idx => $elements) {
            // ── Convert all elements to pixel coords first ──────────────
            $pixelEls = [];
            foreach ($elements as $el) {
                $leftPx   = (int) round($el['left']   / 100 * $canvasW);
                $topPx    = (int) round($el['top']    / 100 * $canvasH);
                $widthPx  = (int) round($el['width']  / 100 * $canvasW);
                $heightPx = (int) round($el['height'] / 100 * $canvasH);

                // Clamp to canvas
                $leftPx   = max(0, min($leftPx,  $canvasW - 20));
                $topPx    = max(0, min($topPx,   $canvasH - 20));
                $widthPx  = max(20, min($widthPx, $canvasW - $leftPx));
                $heightPx = max(16, min($heightPx, $canvasH - $topPx));

                $pixelEls[] = array_merge($el, [
                    'leftPx'   => $leftPx,
                    'topPx'    => $topPx,
                    'widthPx'  => $widthPx,
                    'heightPx' => $heightPx,
                ]);
            }

            // ── Resolve overlapping text boxes ──────────────────────────
            $resolvedEls = $this->resolveOverlaps($pixelEls, $canvasH);

            // ── Render slide ────────────────────────────────────────────
            $html .= '<div class="slide-page">';

            foreach ($resolvedEls as $el) {
                $l = $el['leftPx'];
                $t = $el['topPx'];
                $w = $el['widthPx'];
                $h = $el['heightPx'];

                if ($el['type'] === 'text') {
                    // Text: min-height (can grow), no fixed height clipping
                    $html .= sprintf(
                        '<div class="el text-el" style="left:%dpx;top:%dpx;width:%dpx;min-height:%dpx;">%s</div>',
                        $l, $t, $w, $h, $el['content']
                    );
                } elseif ($el['type'] === 'image') {
                    // Images: strict height/width to stay proportional
                    $html .= sprintf(
                        '<div class="el img-el" style="left:%dpx;top:%dpx;width:%dpx;height:%dpx;"><img src="%s" alt=""/></div>',
                        $l, $t, $w, $h, $el['src']
                    );
                }
            }

            $html .= '</div>';
        }

        $html .= '</body></html>';
        return $html;
    }
}


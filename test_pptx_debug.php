<?php
// Test the PPTX builder logic directly by extracting the buildPptx method

class TestPptxBuilder {
    private function buildPptx($pages, $tempDir)
    {
        $zip = new \ZipArchive();
        $tempPptx = $tempDir . '/temp.pptx';
        $zip->open($tempPptx, \ZipArchive::CREATE);

        $slideCount = count($pages);

        // Build slide references
        $slidesOverrideXml = '';
        $slidesRelsXml = '';
        $slideIdList = '';
        for ($i = 1; $i <= $slideCount; $i++) {
            $slidesOverrideXml .= '<Override PartName="/ppt/slides/slide' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slide+xml"/>';
            $slidesRelsXml .= '<Relationship Id="rId' . ($i + 2) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide" Target="slides/slide' . $i . '.xml"/>';
            $slideIdList .= '<p:sldId id="' . (255 + $i) . '" r:id="rId' . ($i + 2) . '"/>';
        }

        // ==========================================
        // [Content_Types].xml
        // ==========================================
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>
<Override PartName="/ppt/slideMasters/slideMaster1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideMaster+xml"/>
<Override PartName="/ppt/slideLayouts/slideLayout1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideLayout+xml"/>
<Override PartName="/ppt/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml"/>
' . $slidesOverrideXml . '
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // ==========================================
        // _rels/.rels
        // ==========================================
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // ==========================================
        // ppt/_rels/presentation.xml.rels
        // ==========================================
        $presRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="slideMasters/slideMaster1.xml"/>
' . $slidesRelsXml . '
</Relationships>';
        $zip->addFromString('ppt/_rels/presentation.xml.rels', $presRels);

        // ==========================================
        // ppt/presentation.xml
        // ==========================================
        $presentation = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:presentation xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
<p:sldMasterIdLst>
<p:sldMasterId id="2147483648" r:id="rId1"/>
</p:sldMasterIdLst>
<p:sldIdLst>' . $slideIdList . '</p:sldIdLst>
<p:sldSz cx="9144000" cy="6858000" type="custom"/>
<p:notesSz cx="6858000" cy="9144000"/>
</p:presentation>';
        $zip->addFromString('ppt/presentation.xml', $presentation);

        // ==========================================
        // ppt/theme/theme1.xml
        // ==========================================
        $themeXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<a:theme name="Default" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
<a:themeElements>
<a:clrScheme name="Default">
<a:dk1><a:srgbClr val="000000"/></a:dk1>
<a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>
<a:dk2><a:srgbClr val="44546A"/></a:dk2>
<a:lt2><a:srgbClr val="E7E6E6"/></a:lt2>
<a:accent1><a:srgbClr val="4472C4"/></a:accent1>
<a:accent2><a:srgbClr val="ED7D31"/></a:accent2>
<a:accent3><a:srgbClr val="A5A5A5"/></a:accent3>
<a:accent4><a:srgbClr val="FFC000"/></a:accent4>
<a:accent5><a:srgbClr val="5B9BD5"/></a:accent5>
<a:accent6><a:srgbClr val="70AD47"/></a:accent6>
<a:hlink><a:srgbClr val="0563C1"/></a:hlink>
<a:folHlink><a:srgbClr val="954F72"/></a:folHlink>
</a:clrScheme>
<a:fontScheme name="Default">
<a:majorFont><a:latin typeface="Calibri Light" panose="020F0302020204030204"/><a:ea typeface=""/><a:cs typeface=""/></a:majorFont>
<a:minorFont><a:latin typeface="Calibri" panose="020F0502020204030204"/><a:ea typeface=""/><a:cs typeface=""/></a:minorFont>
</a:fontScheme>
<a:fmtScheme name="Default">
<a:fillStyleLst>
<a:solidFill><a:schemeClr val="phClr"/></a:solidFill>
<a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"/></a:gs><a:gs pos="50000"><a:schemeClr val="phClr"/></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"/></a:gs></a:gsLst></a:gradFill>
<a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"/></a:gs><a:gs pos="50000"><a:schemeClr val="phClr"/></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"/></a:gs></a:gsLst></a:gradFill>
</a:fillStyleLst>
<a:lnStyleLst>
<a:ln w="6350"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln>
<a:ln w="6350"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln>
<a:ln w="6350"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln>
</a:lnStyleLst>
<a:effectStyleLst>
<a:effectStyle><a:effectLst/></a:effectStyle>
<a:effectStyle><a:effectLst/></a:effectStyle>
<a:effectStyle><a:effectLst/></a:effectStyle>
</a:effectStyleLst>
<a:bgFillStyleLst>
<a:solidFill><a:schemeClr val="phClr"/></a:solidFill>
<a:solidFill><a:schemeClr val="phClr"/></a:solidFill>
<a:solidFill><a:schemeClr val="phClr"/></a:solidFill>
</a:bgFillStyleLst>
</a:fmtScheme>
</a:themeElements>
</a:theme>';
        $zip->addFromString('ppt/theme/theme1.xml', $themeXml);

        // ==========================================
        // ppt/slideLayouts/slideLayout1.xml
        // ==========================================
        $layoutXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sldLayout type="custom" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
<p:cSld>
<p:spTree>
<p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>
<p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>
</p:spTree>
</p:cSld>
</p:sldLayout>';
        $zip->addFromString('ppt/slideLayouts/slideLayout1.xml', $layoutXml);

        // ==========================================
        // ppt/slideLayouts/_rels/slideLayout1.xml.rels
        // ==========================================
        $layoutRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="../theme/theme1.xml"/>
</Relationships>';
        $zip->addFromString('ppt/slideLayouts/_rels/slideLayout1.xml.rels', $layoutRels);

        // ==========================================
        // ppt/slideMasters/slideMaster1.xml
        // ==========================================
        $masterXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sldMaster xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
<p:cSld>
<p:spTree>
<p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>
<p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>
</p:spTree>
</p:cSld>
<p:sldLayoutIdLst>
<p:sldLayoutId id="2147483649" r:id="rId1"/>
</p:sldLayoutIdLst>
</p:sldMaster>';
        $zip->addFromString('ppt/slideMasters/slideMaster1.xml', $masterXml);

        // ==========================================
        // ppt/slideMasters/_rels/slideMaster1.xml.rels
        // ==========================================
        $masterRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="../theme/theme1.xml"/>
</Relationships>';
        $zip->addFromString('ppt/slideMasters/_rels/slideMaster1.xml.rels', $masterRels);

        // ==========================================
        // Create slides
        // ==========================================
        foreach ($pages as $index => $page) {
            $text = $page['text'];
            $lines = array_filter(array_map('trim', explode("\n", $text)));
            $paragraphs = '';

            foreach ($lines as $line) {
                if (empty($line)) continue;
                $escapedLine = htmlspecialchars($line, ENT_XML1, 'UTF-8');
                $paragraphs .= '<a:p><a:r><a:rPr lang="en-US" sz="1400" dirty="0"/><a:t>' . $escapedLine . '</a:t></a:r></a:p>';
            }

            if (empty($paragraphs)) {
                $paragraphs = '<a:p><a:r><a:rPr lang="en-US" sz="1400" dirty="0"/><a:t>(Halaman tanpa teks)</a:t></a:r></a:p>';
            }

            $slideXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sld xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
<p:cSld>
<p:spTree>
<p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>
<p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>
<p:sp>
<p:nvSpPr><p:cNvPr id="2" name="Text"/><p:cNvSpPr txBox="1"/><p:nvPr/></p:nvSpPr>
<p:spPr><a:xfrm><a:off x="457200" y="457200"/><a:ext cx="8229600" cy="5943600"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></p:spPr>
<p:txBody><a:bodyPr wrap="square" rtlCol="0" anchor="t"/>' . $paragraphs . '</p:txBody>
</p:sp>
</p:spTree>
</p:cSld>
</p:sld>';

            $slideNum = $index + 1;
            $zip->addFromString('ppt/slides/slide' . $slideNum . '.xml', $slideXml);

            // Slide rels
            $slideRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>
</Relationships>';
            $zip->addFromString('ppt/slides/_rels/slide' . $slideNum . '.xml.rels', $slideRels);
        }

        $zip->close();
        return file_get_contents($tempPptx);
    }

    public function test() {
        $tempDir = __DIR__ . '/_pptx_test';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pages = [
            ['text' => "Halaman 1\nIni adalah baris kedua\nBaris ketiga"],
            ['text' => "Halaman 2\nTesting lebih panjang\nBaris lain\nDan satu lagi"],
            ['text' => "Halaman 3\nKonten halaman terakhir"]
        ];

        $data = $this->buildPptx($pages, $tempDir);
        $outPath = __DIR__ . '/test_debug.pptx';
        file_put_contents($outPath, $data);
        echo "Created: $outPath (" . strlen($data) . " bytes)\n";

        // Inspect contents
        $zip = new ZipArchive();
        if ($zip->open($outPath) === TRUE) {
            echo "\n=== PPTX Contents ===\n";
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                $content = $zip->getFromName($name);
                echo "\n--- $name (" . strlen($content) . " bytes) ---\n";
                
                // Validate XML
                if (substr($name, -4) === '.xml' || substr($name, -5) === '.rels') {
                    $dom = new DOMDocument();
                    $dom->loadXML($content, LIBXML_NOERROR);
                    if ($dom->validate === false && $dom->schemaValidate === false) {
                        // Just check for parse errors
                        $oldLibXmlErrors = libxml_use_internal_errors(true);
                        $dom = new DOMDocument();
                        $loaded = $dom->loadXML($content);
                        $errors = libxml_get_errors();
                        libxml_use_internal_errors($oldLibXmlErrors);
                        
                        if (!$loaded) {
                            echo "  ** XML PARSE ERROR **\n";
                            foreach ($errors as $err) {
                                echo "  L" . $err->line . ": " . $err->message . "\n";
                            }
                        } else {
                            echo "  [XML OK]\n";
                        }
                    } else {
                        echo "  [XML OK]\n";
                    }
                }
                echo $content . "\n";
            }
            $zip->close();
        } else {
            echo "ERROR: Cannot open ZIP\n";
        }

        // Cleanup
        $this->delTree($tempDir);
    }

    private function delTree($dir) {
        $files = array_diff(scandir($dir), ['.','..']);
        foreach ($files as $f) {
            $p = "$dir/$f";
            is_dir($p) ? $this->delTree($p) : unlink($p);
        }
        return rmdir($dir);
    }
}

$t = new TestPptxBuilder();
$t->test();

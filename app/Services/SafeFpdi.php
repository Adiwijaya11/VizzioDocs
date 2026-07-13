<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class SafeFpdi extends Fpdi
{
    private array $tempFiles = [];

    /** @var array Daftar ExtGState yang sudah diregistrasi */
    protected $extgstates = [];

    /** @var int Counter untuk ID ExtGState */
    protected $extgstateId = 0;

    /** @var int Nomor object pertama ExtGState (diset saat _putresources) */
    protected $extgstateObjNum = 0;

    /** @var float|null Sudut rotasi saat ini (radian) */
    protected $angle = null;

    /** @var int Counter rotasi bersarang */
    protected $rotationDepth = 0;

    public function setSourceFile($file)
    {
        try {
            return parent::setSourceFile($file);
        } catch (\Exception $e) {
            Log::warning('SafeFpdi: Gagal membaca PDF asli, mencoba dekompresi ulang dengan Ghostscript', [
                'message' => $e->getMessage(),
                'file' => $file
            ]);

            // Check if it's the unsupported compression / version error
            if (
                str_contains($e->getMessage(), 'compression technique') || 
                str_contains($e->getMessage(), 'PDF version') || 
                str_contains($e->getMessage(), 'Unsupported version') ||
                str_contains($e->getMessage(), 'Cannot find xref table')
            ) {
                $decompressed = $this->decompressWithGhostscript($file);
                if ($decompressed) {
                    $this->tempFiles[] = $decompressed;
                    Log::info('SafeFpdi: Berhasil mendekompresi PDF dengan Ghostscript', ['file' => $decompressed]);
                    return parent::setSourceFile($decompressed);
                }

                Log::error('SafeFpdi: Gagal mendekompresi PDF dengan Ghostscript setelah error asli', [
                    'original_error' => $e->getMessage()
                ]);
            }

            throw $e;
        }
    }

    private function decompressWithGhostscript($inputPath): ?string
    {
        $gsPath = 'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c.exe';

        if (!file_exists($gsPath)) {
            Log::error('SafeFpdi: Ghostscript tidak ditemukan', ['path' => $gsPath]);
            return null;
        }

        $tempDir = storage_path('app/private/vizziodocs/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $outputPath = $tempDir . '/' . uniqid('safe_pdf_', true) . '.pdf';

        // Ghostscript membutuhkan temporary file sendiri, gunakan direktori tanpa spasi
        $gsTempDir = storage_path('app/private/vizziodocs/temp/gs');
        if (!file_exists($gsTempDir)) {
            mkdir($gsTempDir, 0755, true);
        }

        try {
            $process = new Process([
                $gsPath,
                '-sDEVICE=pdfwrite',
                '-dCompatibilityLevel=1.4',
                '-dNOPAUSE',
                '-dQUIET',
                '-dBATCH',
                '-dSAFER',
                '-sOutputFile=' . $outputPath,
                $inputPath
            ]);

            $process->setTimeout(60);
            $process->setEnv([
                'TEMP' => $gsTempDir,
                'TMP' => $gsTempDir,
            ]);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('SafeFpdi: Ghostscript gagal', [
                    'exit_code' => $process->getExitCode(),
                    'error_output' => $process->getErrorOutput(),
                    'output' => $process->getOutput()
                ]);
                return null;
            }

            if (!file_exists($outputPath) || filesize($outputPath) === 0) {
                Log::error('SafeFpdi: Output Ghostscript tidak ditemukan atau kosong');
                return null;
            }

            Log::info('SafeFpdi: Ghostscript berhasil', ['output' => $outputPath]);
            return $outputPath;

        } catch (\Throwable $e) {
            Log::error('SafeFpdi: Exception saat menjalankan Ghostscript', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Set alpha (transparency) for subsequent drawing operations.
     *
     * @param float $alpha Nilai 0.0 (transparan) hingga 1.0 (solid)
     * @param string $bm Blend mode (Normal, Multiply, Screen, dll)
     */
    public function SetAlpha($alpha, $bm = 'Normal')
    {
        // Aktifkan transparency group untuk page
        $this->WithAlpha = true;

        $gsKey = $this->addExtGState([
            'ca' => $alpha,
            'CA' => $alpha,
            'BM' => '/' . $bm
        ]);

        $this->_out(sprintf('/%s gs', $gsKey));
    }

    /**
     * Register an ExtGState dictionary and return its resource key.
     *
     * @param array $params Parameter ExtGState (ca, CA, BM, dll)
     * @return string Nama resource (GS1, GS2, ...)
     */
    protected function addExtGState($params)
    {
        $this->extgstateId++;
        $key = 'GS' . $this->extgstateId;
        $this->extgstates[$key] = $params;
        return $key;
    }

    /**
     * Rotate the current coordinate system.
     *
     * - Rotate($angle, $x, $y): Mulai rotasi sebesar $angle derajat di titik ($x, $y)
     * - Rotate(0): Kembalikan ke kondisi sebelum rotasi terakhir
     *
     * @param float $angle Sudut rotasi dalam derajat. 0 untuk merestore.
     * @param float|null $x Posisi X pusat rotasi
     * @param float|null $y Posisi Y pusat rotasi
     */
    public function Rotate($angle, $x = null, $y = null)
    {
        if ($angle == 0) {
            // Restore rotasi
            if ($this->rotationDepth > 0) {
                $this->_out('Q');
                $this->rotationDepth--;
                if ($this->rotationDepth == 0) {
                    $this->angle = null;
                }
            }
            return;
        }

        if ($x === null) {
            $x = $this->x;
        }
        if ($y === null) {
            $y = $this->y;
        }

        // Push graphics state, lalu terapkan rotasi + translasi
        $this->_out('q');

        $angleRad = deg2rad($angle);
        $c = cos($angleRad);
        $s = sin($angleRad);

        // Konversi ke PDF point space (y dari BOTTOM, bukan top)
        $cx = $x * $this->k;
        $cy = ($this->h - $y) * $this->k;

        // Matriks rotasi di sekitar (cx, cy):
        // cm: [c s -s c tx ty]
        // tx = cx*(1-c) + cy*s
        // ty = cy*(1-c) - cx*s
        $tx = $cx * (1 - $c) + $cy * $s;
        $ty = $cy * (1 - $c) - $cx * $s;

        $this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $c, $s, -$s, $c, $tx, $ty));

        $this->angle = $angleRad;
        $this->rotationDepth++;
    }

    /**
     * {@inheritdoc}
     *
     * Override untuk menulis ExtGState objects sebelum resource dictionary.
     */
    protected function _putresources()
    {
        // Simpan nomor object saat ini sebagai awal dari ExtGState objects
        // $this->n sudah berisi jumlah object yang telah dibuat
        $this->extgstateObjNum = $this->n + 1;

        // Tulis ExtGState objects terlebih dahulu
        foreach ($this->extgstates as $key => $params) {
            $this->_newobj();
            $this->_put('<<');
            foreach ($params as $k => $v) {
                if (is_float($v) || is_int($v)) {
                    $this->_put('/' . $k . ' ' . sprintf('%.3F', $v));
                } else {
                    $this->_put('/' . $k . ' ' . $v);
                }
            }
            $this->_put('>>');
            $this->_put('endobj');
        }

        parent::_putresources();
    }

    /**
     * {@inheritdoc}
     *
     * Override untuk menambahkan ExtGState ke resource dictionary.
     */
    protected function _putresourcedict()
    {
        parent::_putresourcedict();

        if (!empty($this->extgstates)) {
            $this->_put('/ExtGState <<');
            $objNum = $this->extgstateObjNum;
            foreach ($this->extgstates as $key => $params) {
                $this->_put('/' . $key . ' ' . $objNum . ' 0 R');
                $objNum++;
            }
            $this->_put('>>');
        }
    }

    public function __destruct()
    {
        foreach ($this->tempFiles as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
}

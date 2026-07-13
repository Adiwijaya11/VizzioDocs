@extends('admin.layouts.master')

@section('title', 'Server Monitoring')
@section('page_title', 'Monitoring')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>📊 Monitoring <span>Sumber Daya Server</span></h1>
        <p>Monitor performa CPU, memori RAM, penggunaan disk, kapasitas bandwidth, serta log error secara real-time.</p>
    </div>
</div>

<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .metric-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        text-align: center;
        position: relative;
    }

    .gage-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
    }

    .gage-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(var(--accent) 0%, #e2e8f0 0%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }

    .metric-card.cpu .gage-circle {
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(var(--accent) 34%, #e2e8f0 34%);
    }

    .metric-card.ram .gage-circle {
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(var(--info) 68%, #e2e8f0 68%);
    }

    .metric-card.storage .gage-circle {
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(var(--success) 45%, #e2e8f0 45%);
    }

    .metric-card.bandwidth .gage-circle {
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(var(--warning) 12%, #e2e8f0 12%);
    }

    .metric-info h3 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .metric-info p {
        font-size: 12px;
        color: var(--text-muted);
    }
</style>

<div class="metrics-grid">
    {{-- CPU GAGE --}}
    <div class="metric-card cpu">
        <div class="gage-container">
            <div class="gage-circle">34%</div>
        </div>
        <div class="metric-info">
            <h3>Beban CPU</h3>
            <p>AMD EPYC 8 Core vCPU</p>
        </div>
    </div>

    {{-- RAM GAGE --}}
    <div class="metric-card ram">
        <div class="gage-container">
            <div class="gage-circle">68%</div>
        </div>
        <div class="metric-info">
            <h3>Penggunaan RAM</h3>
            <p>10.88 GB dari 16 GB</p>
        </div>
    </div>

    {{-- STORAGE GAGE --}}
    <div class="metric-card storage">
        <div class="gage-container">
            <div class="gage-circle">45%</div>
        </div>
        <div class="metric-info">
            <h3>Penyimpanan SSD</h3>
            <p>112.5 GB dari 250 GB</p>
        </div>
    </div>

    {{-- BANDWIDTH GAGE --}}
    <div class="metric-card bandwidth">
        <div class="gage-container">
            <div class="gage-circle">12%</div>
        </div>
        <div class="metric-info">
            <h3>Bandwidth Bulanan</h3>
            <p>120 GB dari 1000 GB</p>
        </div>
    </div>
</div>

{{-- Error Log Console --}}
<div class="card">
    <div class="card-header">
        <h3>
            <i class="fas fa-exclamation-triangle" style="color: var(--danger); margin-right: 8px;"></i>
            Log Error Terbaru (laravel.log)
        </h3>
        <button class="btn btn-sm btn-outline" style="color: var(--danger); border-color: rgba(239,68,68,0.2);" onclick="alert('Log berhasil dibersihkan!')">
            Bersihkan Log
        </button>
    </div>
    <div class="card-body" style="background: #1e2530; color: #a5b1c2; font-family: monospace; font-size: 12px; padding: 20px; border-radius: 0 0 var(--radius) var(--radius); max-height: 300px; overflow-y: auto; line-height: 1.6;">
        <p style="color: #ff7675; margin-bottom: 8px;">[2026-07-02 16:34:12] local.ERROR: Attempt to read property "name" on null {"exception":"[object] (ErrorException(code: 0): Attempt to read property \"name\" on null at App\\Http\\Controllers\\PdfCropController:85)"}</p>
        <p style="color: #ffeaa7; margin-bottom: 8px;">[2026-07-02 15:10:45] local.WARNING: Memory limit warning: PDF rendering page 58 exceeded 128MB usage in App\Services\PdfService</p>
        <p style="color: #63cdda; margin-bottom: 8px;">[2026-07-02 14:22:01] local.INFO: Queue worker processing job: App\Jobs\ProcessPdfCompression (ID: job_99812A)</p>
        <p style="color: #63cdda; margin-bottom: 8px;">[2026-07-02 14:22:02] local.INFO: Queue worker successfully processed job: App\Jobs\ProcessPdfCompression (ID: job_99812A)</p>
    </div>
</div>
@endsection

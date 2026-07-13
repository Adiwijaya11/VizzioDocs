@extends('admin.layouts.master')

@section('title', 'Cache Manager')
@section('page_title', 'Cache Manager')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fas fa-broom" style="color:var(--accent-light);margin-right:8px;"></i>Cache <span>Manager</span></h1>
        <p>Bersihkan berbagai jenis cache aplikasi Laravel secara langsung tanpa perlu mengakses terminal server.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="display:flex;align-items:center;gap:10px;">
        <i class="fas fa-check-circle" style="font-size:18px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger" style="display:flex;align-items:center;gap:10px;">
        <i class="fas fa-times-circle" style="font-size:18px;"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

<style>
    .cache-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .cache-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: all 0.25s ease;
    }

    .cache-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(108, 92, 231, 0.06);
        border-color: rgba(108, 92, 231, 0.2);
    }

    .cache-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .cache-icon.purple  { background: rgba(108, 92, 231, 0.12); color: var(--accent-light); }
    .cache-icon.blue    { background: rgba(59, 130, 246, 0.12); color: var(--info); }
    .cache-icon.green   { background: rgba(16, 185, 129, 0.12); color: var(--success); }
    .cache-icon.orange  { background: rgba(245, 158, 11, 0.12); color: var(--warning); }

    .cache-card h3 { font-size: 15px; font-weight: 700; }
    .cache-card p  { font-size: 13px; color: var(--text-secondary); line-height: 1.6; flex: 1; }

    .cache-command {
        font-family: monospace;
        font-size: 12px;
        background: rgba(108, 92, 231, 0.06);
        border: 1px solid rgba(108, 92, 231, 0.12);
        color: var(--accent-light);
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>

<div class="cache-grid">

    {{-- Application Cache --}}
    <div class="cache-card">
        <div class="cache-icon purple"><i class="fas fa-database"></i></div>
        <h3>Application Cache</h3>
        <p>Hapus semua data yang tersimpan di cache aplikasi termasuk response cache, session cache, dan data sementara lainnya.</p>
        <code class="cache-command">php artisan cache:clear</code>
        <form action="{{ route('admin.cache.clear') }}" method="POST" style="margin-top:4px;">
            @csrf
            <input type="hidden" name="type" value="cache">
            <button type="submit" class="btn btn-primary" style="width:100%;" onclick="return confirm('Yakin ingin membersihkan application cache?')">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
        </form>
    </div>

    {{-- Config Cache --}}
    <div class="cache-card">
        <div class="cache-icon blue"><i class="fas fa-sliders-h"></i></div>
        <h3>Config Cache</h3>
        <p>Bersihkan cache konfigurasi aplikasi. Berguna setelah mengubah nilai di file <code>.env</code> atau <code>config/*.php</code>.</p>
        <code class="cache-command">php artisan config:clear</code>
        <form action="{{ route('admin.cache.clear') }}" method="POST" style="margin-top:4px;">
            @csrf
            <input type="hidden" name="type" value="config">
            <button type="submit" class="btn btn-primary" style="width:100%;background:var(--info);border-color:var(--info);" onclick="return confirm('Yakin ingin membersihkan config cache?')">
                <i class="fas fa-broom"></i> Clear Config
            </button>
        </form>
    </div>

    {{-- View Cache --}}
    <div class="cache-card">
        <div class="cache-icon green"><i class="fas fa-file-code"></i></div>
        <h3>View Cache</h3>
        <p>Hapus file template Blade yang sudah dikompilasi. Wajib dilakukan setelah mengubah file template <code>.blade.php</code>.</p>
        <code class="cache-command">php artisan view:clear</code>
        <form action="{{ route('admin.cache.clear') }}" method="POST" style="margin-top:4px;">
            @csrf
            <input type="hidden" name="type" value="view">
            <button type="submit" class="btn btn-primary" style="width:100%;background:var(--success);border-color:var(--success);" onclick="return confirm('Yakin ingin membersihkan view cache?')">
                <i class="fas fa-broom"></i> Clear View
            </button>
        </form>
    </div>

    {{-- Route Cache --}}
    <div class="cache-card">
        <div class="cache-icon orange"><i class="fas fa-route"></i></div>
        <h3>Route Cache</h3>
        <p>Bersihkan cache routing aplikasi. Perlu dibersihkan setelah menambah atau mengubah rute baru di <code>routes/web.php</code>.</p>
        <code class="cache-command">php artisan route:clear</code>
        <form action="{{ route('admin.cache.clear') }}" method="POST" style="margin-top:4px;">
            @csrf
            <input type="hidden" name="type" value="route">
            <button type="submit" class="btn btn-primary" style="width:100%;background:var(--warning);border-color:var(--warning);color:#1e293b;" onclick="return confirm('Yakin ingin membersihkan route cache?')">
                <i class="fas fa-broom"></i> Clear Route
            </button>
        </form>
    </div>

</div>

{{-- Clear All --}}
<div class="card">
    <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
        <div>
            <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">
                <i class="fas fa-fire-alt" style="color:var(--danger);margin-right:8px;"></i>
                Bersihkan Semua Cache Sekaligus
            </h3>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">
                Menjalankan <code>cache:clear</code>, <code>config:clear</code>, <code>view:clear</code>, dan <code>route:clear</code> sekaligus dalam satu perintah.
            </p>
        </div>
        <form action="{{ route('admin.cache.clear') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="all">
            <button type="submit" class="btn btn-primary"
                style="background:linear-gradient(135deg,#ef4444,#dc2626);border-color:#ef4444;white-space:nowrap;"
                onclick="return confirm('Yakin ingin membersihkan SEMUA cache? Halaman mungkin lebih lambat saat dimuat pertama kali.')">
                <i class="fas fa-fire-alt"></i> Clear All Cache
            </button>
        </form>
    </div>
</div>
@endsection

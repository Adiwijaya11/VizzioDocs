@extends('admin.layouts.master')

@section('title', 'Pengaturan')

@section('page_title', 'Pengaturan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Pengaturan <span>Platform</span></h1>
        <p>Kelola pengaturan dan konfigurasi VizzioDocs.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="grid-2col">
    {{-- Profile Info --}}
    <div class="chart-card">
        <h3><i class="fas fa-user-shield"></i> Profil Administrator</h3>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding: 16px; background: rgba(108,92,231,0.05); border-radius: var(--radius-sm);">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; color: #fff; flex-shrink: 0;">
                {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight: 700; font-size: 16px;">{{ $admin->name }}</div>
                <div style="color: var(--text-muted); font-size: 13px;">{{ $admin->email }}</div>
                <span class="badge badge-admin" style="margin-top: 4px;">
                    <i class="fas fa-shield-alt"></i> Administrator
                </span>
            </div>
        </div>

        <div class="stat-mini-list">
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(108,92,231,0.15); color: var(--accent-light);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="mini-label">Bergabung Sejak</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ $admin->created_at->format('d M Y') }}</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(0,184,148,0.15); color: var(--success);">
                        <i class="fas fa-globe"></i>
                    </div>
                    <span class="mini-label">Total Pengguna</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ $totalUsers }}</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(253,203,110,0.15); color: var(--warning);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <span class="mini-label">Total Admin</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ $totalAdmins }}</div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="chart-card">
        <h3><i class="fas fa-chart-simple"></i> Informasi Platform</h3>
        <div class="stat-mini-list">
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(116,185,255,0.15); color: var(--info);">
                        <i class="fab fa-laravel"></i>
                    </div>
                    <span class="mini-label">Framework</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">Laravel {{ app()->version() }}</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(253,121,168,0.15); color: #fd79a8;">
                        <i class="fas fa-database"></i>
                    </div>
                    <span class="mini-label">Database</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">MySQL</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(85,239,196,0.15); color: #55efc4;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="mini-label">Waktu Server</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ now()->format('H:i:s') }}</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(162,155,254,0.15); color: #a29bfe;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <span class="mini-label">Tanggal</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ now()->format('d M Y') }}</div>
            </div>
            <div class="stat-mini-item">
                <div class="left">
                    <div class="mini-icon" style="background: rgba(255,234,167,0.15); color: #ffeaa7;">
                        <i class="fas fa-php"></i>
                    </div>
                    <span class="mini-label">PHP Version</span>
                </div>
                <div class="right" style="font-size: 13px; font-weight: 500;">{{ PHP_VERSION }}</div>
            </div>
        </div>
    </div>
</div>

{{-- General Settings Form --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-sliders-h" style="color: var(--accent-light); margin-right: 8px;"></i> Pengaturan Umum</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="site_name">Nama Situs</label>
                    <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? 'VizzioDocs' }}" placeholder="VizzioDocs">
                    <div class="help-text">Nama platform yang akan ditampilkan.</div>
                </div>
                <div class="form-group">
                    <label for="max_file_size">Max File Size (MB)</label>
                    <input type="number" id="max_file_size" name="max_file_size" value="{{ $settings['max_file_size'] ?? '50' }}" min="1" max="200">
                    <div class="help-text">Batas maksimal ukuran file upload untuk akun gratis. Akun premium otomatis 200 MB.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Mode Pemeliharaan</label>
                    <div style="margin-top: 8px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="maintenance_mode" value="1" {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                            <span style="font-size: 13px; color: var(--text-secondary);">Aktifkan mode pemeliharaan</span>
                        </label>
                    </div>
                    <div class="help-text">Saat aktif, hanya admin yang bisa mengakses situs.</div>
                </div>
            </div>

            <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

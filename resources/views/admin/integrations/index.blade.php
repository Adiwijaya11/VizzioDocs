@extends('admin.layouts.master')

@section('title', 'Integrasi Layanan')
@section('page_title', 'Integrasi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Integrasi <span>Layanan Sistem</span></h1>
        <p>Status koneksi layanan yang aktif digunakan oleh platform VizzioDocs — berdasarkan konfigurasi .env secara real-time.</p>
    </div>
</div>

<style>
    .integrations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 10px;
    }
    .integration-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .integration-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(108, 92, 231, 0.05);
        border-color: rgba(108, 92, 231, 0.2);
    }
    .integration-card.connected-card {
        border-left: 3px solid var(--success);
    }
    .integration-card.disconnected-card {
        border-left: 3px solid var(--border);
        opacity: 0.75;
    }
    .integration-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .integration-logo-wrapper {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .integration-logo-wrapper.google   { background: rgba(225, 95, 65, 0.1); color: #eb4d4b; }
    .integration-logo-wrapper.github   { background: rgba(30, 39, 46, 0.15); color: var(--text-primary); }
    .integration-logo-wrapper.mail     { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .integration-logo-wrapper.db       { background: rgba(59, 130, 246, 0.12); color: var(--info); }
    .integration-logo-wrapper.session  { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .integration-logo-wrapper.cache    { background: rgba(108, 92, 231, 0.12); color: var(--accent-light); }
    .integration-body h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
    .integration-body p  { font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 12px; }
    .integration-detail  { font-size: 11px; color: var(--text-muted); font-family: monospace; background: rgba(255,255,255,0.03); padding: 6px 10px; border-radius: 6px; word-break: break-all; margin-bottom: 16px; }
    .integration-footer {
        display: flex; align-items: center; justify-content: space-between;
        border-top: 1px solid var(--border);
        padding-top: 14px; margin-top: auto;
    }
    .status-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px; }
    .status-badge.connected    { background: rgba(16, 185, 129, 0.12); color: var(--success); }
    .status-badge.disconnected { background: rgba(148, 163, 184, 0.1); color: var(--text-muted); }
</style>

<div class="integrations-grid">
    @foreach($integrations as $key => $item)
    <div class="integration-card {{ $item['connected'] ? 'connected-card' : 'disconnected-card' }}">
        <div class="integration-header">
            <div class="integration-logo-wrapper {{ $item['color'] }}">
                <i class="{{ $item['icon'] }}"></i>
            </div>
            <span class="status-badge {{ $item['connected'] ? 'connected' : 'disconnected' }}">
                <i class="fas fa-{{ $item['connected'] ? 'check-circle' : 'times-circle' }}"></i>
                {{ $item['connected'] ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>
        <div class="integration-body">
            <h3>{{ $item['label'] }}</h3>
            <p>{{ $item['description'] }}</p>
            <div class="integration-detail">{{ $item['detail'] }}</div>
        </div>
        <div class="integration-footer">
            <span style="font-size:11px;color:var(--text-muted);">
                <i class="fas fa-clock" style="margin-right:4px;"></i>
                Dikonfigurasi via .env
            </span>
            <a href="{{ route('admin.integrations.settings', $key) }}" class="btn btn-sm btn-outline">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection

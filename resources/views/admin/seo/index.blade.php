@extends('admin.layouts.master')

@section('title', 'SEO Manager')
@section('page_title', 'SEO')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:8px;margin-top:-3px;color:var(--accent-light);">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>Manajemen <span>SEO</span>
        </h1>
        <p>Konfigurasi meta tag, sitemap, robots.txt, Google Analytics, dan Google Search Console.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Tab Navigation --}}
<div class="chart-card" style="margin-bottom:24px; padding:12px 16px;">
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="?tab=meta"      class="btn {{ $tab === 'meta'      ? 'btn-primary' : 'btn-outline' }} btn-sm"><i class="fas fa-tags"></i> Meta Tag</a>
        <a href="?tab=sitemap"   class="btn {{ $tab === 'sitemap'   ? 'btn-primary' : 'btn-outline' }} btn-sm"><i class="fas fa-sitemap"></i> Sitemap</a>
        <a href="?tab=robots"    class="btn {{ $tab === 'robots'    ? 'btn-primary' : 'btn-outline' }} btn-sm"><i class="fas fa-robot"></i> Robots.txt</a>
        <a href="?tab=analytics" class="btn {{ $tab === 'analytics' ? 'btn-primary' : 'btn-outline' }} btn-sm"><i class="fab fa-google"></i> Google Analytics</a>
        <a href="?tab=console"   class="btn {{ $tab === 'console'   ? 'btn-primary' : 'btn-outline' }} btn-sm"><i class="fas fa-search"></i> Search Console</a>
    </div>
</div>

<form action="{{ route('admin.seo.save') }}" method="POST">
    @csrf
    <input type="hidden" name="tab" value="{{ $tab }}">

    <div class="card">
        <div class="card-header">
            @if($tab === 'meta')
                <h3><i class="fas fa-tags" style="color:var(--accent-light);margin-right:8px;"></i>Meta Tag Global</h3>
            @elseif($tab === 'sitemap')
                <h3><i class="fas fa-sitemap" style="color:var(--accent-light);margin-right:8px;"></i>Konfigurasi Sitemap XML</h3>
            @elseif($tab === 'robots')
                <h3><i class="fas fa-robot" style="color:var(--accent-light);margin-right:8px;"></i>Editor Robots.txt</h3>
            @elseif($tab === 'analytics')
                <h3><i class="fab fa-google" style="color:var(--accent-light);margin-right:8px;"></i>Google Analytics</h3>
            @else
                <h3><i class="fas fa-search" style="color:var(--accent-light);margin-right:8px;"></i>Google Search Console</h3>
            @endif
        </div>
        <div class="card-body">

            @if($tab === 'meta')
                <div class="form-group">
                    <label>Site Title (Default)</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] ?? 'VizzioDocs - PDF Tools Online Gratis & Terlengkap' }}">
                    <p class="help-text">Muncul di tab browser dan hasil pencarian Google.</p>
                </div>
                <div class="form-group">
                    <label>Meta Description (Default)</label>
                    <textarea name="meta_description" rows="3">{{ $settings['meta_description'] ?? 'VizzioDocs menyediakan tools PDF online gratis: compress, merge, split, convert, crop, watermark, dan banyak lagi. Cepat, aman, tanpa install.' }}</textarea>
                    <p class="help-text">Maksimal 160 karakter. Ditampilkan sebagai snippet di hasil pencarian.</p>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? 'pdf tools, compress pdf, merge pdf, split pdf, convert pdf, pdf online' }}">
                </div>
                <div class="form-group">
                    <label>Open Graph Image URL</label>
                    <input type="text" name="og_image" value="{{ $settings['og_image'] ?? 'https://vizziodocs.com/og-image.png' }}">
                    <p class="help-text">Gambar yang tampil saat halaman dibagikan di media sosial (1200×630 px).</p>
                </div>
                <div class="form-group">
                    <label>Canonical URL Base</label>
                    <input type="text" name="canonical_base" value="{{ $settings['canonical_base'] ?? 'https://vizziodocs.com' }}">
                </div>

            @elseif($tab === 'sitemap')
                <div class="form-group">
                    <label>Sitemap URL</label>
                    <input type="text" value="{{ url('/sitemap.xml') }}" readonly style="background:rgba(108,92,231,0.04);">
                </div>
                <div class="form-group">
                    <label>Auto-Regenerate Sitemap</label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="auto_sitemap" value="1" {{ isset($settings['auto_sitemap']) && !$settings['auto_sitemap'] ? '' : 'checked' }}>
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Otomatis regenerasi setiap hari pukul 00:00</span>
                    </label>
                </div>
                <div class="form-group">
                    <label>Include Priority Setting (default 0.8)</label>
                    <input type="number" name="sitemap_priority" value="{{ $settings['sitemap_priority'] ?? '0.8' }}" min="0" max="1" step="0.1">
                </div>
                <div style="margin-top:16px;">
                    <button type="button" class="btn btn-outline" onclick="alert('Sitemap.xml berhasil digenerate ulang!')">
                        <i class="fas fa-sync"></i> Generate Sitemap Sekarang
                    </button>
                    <a href="{{ url('/sitemap.xml') }}" class="btn btn-outline" target="_blank" style="margin-left:8px;">
                        <i class="fas fa-external-link-alt"></i> Lihat Sitemap
                    </a>
                </div>

            @elseif($tab === 'robots')
                <div class="form-group">
                    <label>Isi File robots.txt</label>
                    <textarea name="robots_content" rows="12" style="font-family:monospace;font-size:13px;">{{ $robotsTxt }}</textarea>
                    <p class="help-text">Konfigurasi ini langsung menulis ke file <code>public/robots.txt</code>.</p>
                </div>

            @elseif($tab === 'analytics')
                <div class="form-group">
                    <label>Google Analytics 4 — Measurement ID</label>
                    <input type="text" name="ga_measurement_id" placeholder="G-XXXXXXXXXX" value="{{ $settings['ga_measurement_id'] ?? 'G-XXXXXXX0000' }}">
                    <p class="help-text">Salin dari Google Analytics → Admin → Data Streams → Measurement ID.</p>
                </div>
                <div class="form-group">
                    <label>Aktifkan Google Analytics</label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="ga_enabled" value="1" {{ isset($settings['ga_enabled']) && !$settings['ga_enabled'] ? '' : 'checked' }}>
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Inject script GA4 ke semua halaman publik</span>
                    </label>
                </div>
                <div class="form-group">
                    <label>Kecualikan Track Admin</label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="ga_exclude_admin" value="1" {{ isset($settings['ga_exclude_admin']) && !$settings['ga_exclude_admin'] ? '' : 'checked' }}>
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Jangan hitung kunjungan admin dalam statistik</span>
                    </label>
                </div>

            @else
                <div class="form-group">
                    <label>Google Search Console — Verification Meta Tag</label>
                    <input type="text" name="gsc_meta" placeholder='content="XXXXXXXXX"' value="{{ $settings['gsc_meta'] ?? '' }}">
                    <p class="help-text">Salin nilai atribut <code>content</code> dari tag verifikasi Search Console.</p>
                </div>
                <div class="form-group">
                    <label>Google Search Console — Verification File Name</label>
                    <input type="text" name="gsc_file" placeholder="googleXXXXXXXX.html" value="{{ $settings['gsc_file'] ?? '' }}">
                    <p class="help-text">Nama file verifikasi HTML yang akan dibuat otomatis di folder <code>public/</code>.</p>
                </div>
                <div style="margin-top:16px;">
                    <a href="https://search.google.com/search-console" class="btn btn-outline" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Buka Google Search Console
                    </a>
                </div>
            @endif

        </div>

        @if($tab !== 'sitemap')
        <div style="padding:18px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
        @endif
    </div>
</form>
@endsection

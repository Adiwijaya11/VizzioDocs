@extends('admin.layouts.master')

@section('title', 'Mode Maintenance')
@section('page_title', 'Maintenance')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>⚙️ Mode <span>Maintenance</span></h1>
        <p>Aktifkan mode pemeliharaan untuk mengunci akses publik sementara saat memperbarui platform.</p>
    </div>
</div>

<style>
    .maintenance-banner {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-radius: var(--radius);
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 32px;
        margin-bottom: 28px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.2);
    }

    .maintenance-banner.inactive {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    }

    .maintenance-icon-large {
        font-size: 64px;
        animation: rotate-gear 8s infinite linear;
    }

    .maintenance-banner.inactive .maintenance-icon-large {
        animation: none;
    }

    @keyframes rotate-gear {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .maintenance-content h2 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .maintenance-content p {
        font-size: 15px;
        opacity: 0.9;
        line-height: 1.6;
    }

    .maintenance-control-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 30px;
    }

    .settings-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 24px;
    }

    .settings-info h4 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .settings-info p {
        font-size: 13px;
        color: var(--text-secondary);
    }
</style>

<div class="maintenance-banner inactive" id="maintenanceBanner">
    <div class="maintenance-icon-large">
        <i class="fas fa-cog"></i>
    </div>
    <div class="maintenance-content">
        <h2 id="bannerTitle">Website Berjalan Normal</h2>
        <p id="bannerDesc">Mode maintenance dinonaktifkan. Pengguna saat ini dapat mengakses semua fitur VizzioDocs secara penuh dan lancar tanpa kendala.</p>
    </div>
</div>

<div class="maintenance-control-card">
    <div class="settings-row">
        <div class="settings-info">
            <h4>Aktifkan Mode Maintenance</h4>
            <p>Alihkan rute utama pengunjung ke halaman placeholder pemeliharaan.</p>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="maintenanceToggle" onchange="toggleMaintenanceMode()">
            <span class="toggle-slider"></span>
        </label>
    </div>

    <div class="form-group" style="margin-bottom: 24px;">
        <label for="bypass_ip">IP Address Pengecualian (Bypass IP)</label>
        <input type="text" id="bypass_ip" value="192.168.1.1, 127.0.0.1" placeholder="Masukkan alamat IP dipisah koma...">
        <p class="help-text">IP yang terdaftar di atas tetap bisa mengakses website VizzioDocs untuk keperluan testing pengembang.</p>
    </div>

    <div class="form-group">
        <label for="maintenance_message">Pesan Custom Layanan Pemeliharaan</label>
        <textarea id="maintenance_message" rows="4" placeholder="Masukkan pesan yang ingin ditampilkan kepada pengunjung...">VizzioDocs sedang melakukan pemeliharaan server rutin. Kami akan segera kembali dalam beberapa menit. Terima kasih atas kesabaran Anda!</textarea>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
        <button class="btn btn-primary" onclick="alert('Konfigurasi pemeliharaan berhasil disimpan!')">
            <i class="fas fa-save"></i> Simpan Konfigurasi
        </button>
    </div>
</div>

<script>
    function toggleMaintenanceMode() {
        const toggle = document.getElementById('maintenanceToggle');
        const banner = document.getElementById('maintenanceBanner');
        const title = document.getElementById('bannerTitle');
        const desc = document.getElementById('bannerDesc');

        if (toggle.checked) {
            banner.className = 'maintenance-banner';
            title.innerText = 'Mode Maintenance Sedang Aktif';
            desc.innerText = 'Pengunjung website utama akan dialihkan ke halaman pemeliharaan. Akses fitur PDF sementara waktu dinonaktifkan untuk publik.';
            alert('Mode maintenance diaktifkan! Pengunjung publik dialihkan.');
        } else {
            banner.className = 'maintenance-banner inactive';
            title.innerText = 'Website Berjalan Normal';
            desc.innerText = 'Mode maintenance dinonaktifkan. Pengguna saat ini dapat mengakses semua fitur VizzioDocs secara penuh dan lancar tanpa kendala.';
            alert('Mode maintenance dinonaktifkan! Akses publik kembali dibuka.');
        }
    }
</script>
@endsection

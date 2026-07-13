@extends('admin.layouts.master')

@section('title', 'Rate Limit & Captcha')
@section('page_title', 'Rate Limit & Captcha')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fas fa-lock" style="color:var(--accent-light);margin-right:8px;"></i>Rate Limit <span>& Captcha</span></h1>
        <p>Cegah spam, brute force, dan bot otomatis melalui batas permintaan dan verifikasi captcha.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<style>
    .rl-section { margin-bottom: 28px; }
    .rl-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rl-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .rl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }

    .rl-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px 20px;
        transition: all 0.2s ease;
    }
    .rl-card:hover { border-color: rgba(108,92,231,0.2); box-shadow: 0 4px 12px rgba(108,92,231,0.04); }

    .rl-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }
    .rl-card-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .rl-card-icon.purple { background: rgba(108,92,231,0.12); color: var(--accent-light); }
    .rl-card-icon.orange { background: rgba(245,158,11,0.12); color: var(--warning); }
    .rl-card-icon.green  { background: rgba(16,185,129,0.12); color: var(--success); }
    .rl-card-icon.blue   { background: rgba(59,130,246,0.12); color: var(--info); }
    .rl-card-icon.red    { background: rgba(239,68,68,0.12); color: var(--danger); }

    .rl-card-header h3 { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
    .rl-card-header p  { font-size: 12px; color: var(--text-muted); }

    .rl-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        gap: 10px;
    }
    .rl-row:last-child { margin-bottom: 0; }
    .rl-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); flex: 1; }
    .rl-row input[type="number"],
    .rl-row input[type="text"] {
        width: 80px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: var(--radius-xs);
        padding: 6px 10px;
        font-size: 13px;
        color: var(--text-primary);
        outline: none;
        font-family: 'Inter', sans-serif;
        text-align: center;
    }
    .rl-row input:focus { border-color: var(--accent); }
    .rl-unit { font-size: 11px; color: var(--text-muted); white-space: nowrap; }

    /* Captcha card */
    .captcha-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px;
    }
    .captcha-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .captcha-option {
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .captcha-option:hover { border-color: var(--accent); background: rgba(108,92,231,0.03); }
    .captcha-option.selected { border-color: var(--accent); background: rgba(108,92,231,0.06); }
    .captcha-option .option-badge {
        position: absolute;
        top: 12px; right: 12px;
        font-size: 10px; font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        background: rgba(108,92,231,0.15);
        color: var(--accent-light);
    }
    .captcha-option h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
    .captcha-option p  { font-size: 12px; color: var(--text-secondary); line-height: 1.5; }
</style>

<form action="{{ route('admin.rate-limiter.save') }}" method="POST">
    @csrf

    {{-- ===== RATE LIMITING ===== --}}
    <div class="rl-section">
        <div class="rl-section-title"><i class="fas fa-tachometer-alt"></i> Rate Limiting</div>
        <div class="rl-grid">

            {{-- Tool Endpoint --}}
            <div class="rl-card">
                <div class="rl-card-header">
                    <div class="rl-card-icon purple"><i class="fas fa-tools"></i></div>
                    <div>
                        <h3>Tool Endpoint</h3>
                        <p>Batas penggunaan tool PDF per pengguna</p>
                    </div>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Max Request / Menit</span>
                    <input type="number" name="tool_per_minute" value="10" min="1">
                    <span class="rl-unit">req/min</span>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Max Request / Jam</span>
                    <input type="number" name="tool_per_hour" value="60" min="1">
                    <span class="rl-unit">req/jam</span>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Durasi Blokir</span>
                    <input type="number" name="tool_block" value="5" min="1">
                    <span class="rl-unit">menit</span>
                </div>
            </div>

            {{-- Login / Auth --}}
            <div class="rl-card">
                <div class="rl-card-header">
                    <div class="rl-card-icon orange"><i class="fas fa-sign-in-alt"></i></div>
                    <div>
                        <h3>Login / Autentikasi</h3>
                        <p>Cegah brute force percobaan login</p>
                    </div>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Max Percobaan Login</span>
                    <input type="number" name="login_attempts" value="5" min="1">
                    <span class="rl-unit">kali</span>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Jendela Waktu</span>
                    <input type="number" name="login_window" value="10" min="1">
                    <span class="rl-unit">menit</span>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Durasi Blokir IP</span>
                    <input type="number" name="login_block" value="30" min="1">
                    <span class="rl-unit">menit</span>
                </div>
            </div>

            {{-- Registrasi --}}
            <div class="rl-card">
                <div class="rl-card-header">
                    <div class="rl-card-icon green"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <h3>Registrasi Akun</h3>
                        <p>Cegah mass-signup spam dari satu IP</p>
                    </div>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Max Daftar / IP / Hari</span>
                    <input type="number" name="register_per_day" value="3" min="1">
                    <span class="rl-unit">akun</span>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Jeda Antar Daftar</span>
                    <input type="number" name="register_cooldown" value="60" min="1">
                    <span class="rl-unit">detik</span>
                </div>
            </div>

            {{-- API Global --}}
            <div class="rl-card">
                <div class="rl-card-header">
                    <div class="rl-card-icon blue"><i class="fas fa-code"></i></div>
                    <div>
                        <h3>API Global (/api/*)</h3>
                        <p>Batas semua endpoint API secara global</p>
                    </div>
                </div>
                <div class="rl-row">
                    <span class="rl-label">Max Request / Menit</span>
                    <input type="number" name="api_per_minute" value="60" min="1">
                    <span class="rl-unit">req/min</span>
                </div>
                <div class="rl-row" style="align-items:flex-start;">
                    <span class="rl-label" style="padding-top:6px;">Pesan Throttle</span>
                    <input type="text" name="api_message" value="Too many requests" style="width:140px;text-align:left;font-size:11px;">
                </div>
            </div>

        </div>

        {{-- Whitelist IP --}}
        <div class="rl-card" style="margin-top:16px;">
            <div class="rl-card-header">
                <div class="rl-card-icon red"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h3>IP Whitelist (Bebas Limit)</h3>
                    <p>IP yang dikecualikan dari semua aturan rate limit</p>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <textarea name="whitelist_ips" rows="3" placeholder="Satu IP per baris&#10;127.0.0.1&#10;192.168.1.10">127.0.0.1
192.168.1.10
::1</textarea>
                <p class="help-text">IP server internal dan developer agar tidak ter-throttle saat testing.</p>
            </div>
        </div>
    </div>

    {{-- ===== CAPTCHA ===== --}}
    <div class="rl-section">
        <div class="rl-section-title"><i class="fas fa-robot"></i> Captcha / Bot Protection</div>

        <div class="captcha-card">
            <p style="font-size:13px;color:var(--text-secondary);margin-bottom:20px;">
                Pilih penyedia captcha untuk melindungi form login, registrasi, dan endpoint sensitif dari bot otomatis.
            </p>

            {{-- Pilihan Provider --}}
            <div class="captcha-options">
                <div class="captcha-option selected" onclick="selectCaptcha(this, 'recaptcha_v3')">
                    <span class="option-badge">Aktif</span>
                    <h4><i class="fab fa-google" style="color:#ea4335;margin-right:6px;"></i>reCAPTCHA v3</h4>
                    <p>Verifikasi diam-diam (invisible) berdasarkan skor perilaku pengguna. Tidak ada checkbox yang harus diklik.</p>
                </div>
                <div class="captcha-option" onclick="selectCaptcha(this, 'recaptcha_v2')">
                    <h4><i class="fab fa-google" style="color:#4285f4;margin-right:6px;"></i>reCAPTCHA v2</h4>
                    <p>Captcha klasik "I'm not a robot" dengan checkbox yang terlihat oleh pengguna.</p>
                </div>
                <div class="captcha-option" onclick="selectCaptcha(this, 'hcaptcha')">
                    <h4>
                        <svg width="16" height="16" viewBox="0 0 100 100" style="vertical-align:middle;margin-right:6px;fill:#5C52E0;"><circle cx="50" cy="50" r="50"/></svg>
                        hCaptcha
                    </h4>
                    <p>Alternatif privacy-first dari Cloudflare. Lebih menghormati privasi pengguna dibanding reCAPTCHA.</p>
                </div>
                <div class="captcha-option" onclick="selectCaptcha(this, 'turnstile')">
                    <h4>
                        <svg width="16" height="16" viewBox="0 0 100 100" style="vertical-align:middle;margin-right:6px;fill:#F6821F;"><rect width="100" height="100" rx="20"/></svg>
                        Cloudflare Turnstile
                    </h4>
                    <p>Smart challenge Cloudflare yang sepenuhnya invisible dan tidak mengganggu pengalaman pengguna.</p>
                </div>
            </div>

            <input type="hidden" name="captcha_provider" id="captchaProvider" value="recaptcha_v3">

            {{-- Credentials --}}
            <div id="captchaFields" style="border-top:1px solid var(--border);padding-top:24px;margin-top:8px;">
                <h4 style="font-size:14px;font-weight:700;margin-bottom:16px;">
                    <i class="fas fa-key" style="color:var(--accent-light);margin-right:8px;"></i>API Credentials
                </h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Site Key</label>
                        <input type="text" name="captcha_site_key" placeholder="6Ld...AAAAAAA">
                        <p class="help-text">Digunakan di sisi frontend (browser pengguna).</p>
                    </div>
                    <div class="form-group">
                        <label>Secret Key</label>
                        <input type="text" name="captcha_secret_key" placeholder="6Ld...AAAAAAB">
                        <p class="help-text">Digunakan di sisi server untuk verifikasi response.</p>
                    </div>
                </div>

                {{-- Aktifkan di form mana --}}
                <h4 style="font-size:14px;font-weight:700;margin-bottom:14px;">
                    <i class="fas fa-check-square" style="color:var(--accent-light);margin-right:8px;"></i>Aktifkan Captcha Pada
                </h4>
                <div style="display:flex;flex-wrap:wrap;gap:16px;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="captcha_login" checked>
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Halaman Login</span>
                    </label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="captcha_register" checked>
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Halaman Registrasi</span>
                    </label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="captcha_contact">
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Form Kontak</span>
                    </label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="captcha_tool">
                        <span class="toggle-slider"></span>
                        <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">Form Tool PDF (Guest)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;justify-content:flex-end;gap:12px;">
        <button type="reset" class="btn btn-outline">
            <i class="fas fa-undo"></i> Reset Default
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Konfigurasi
        </button>
    </div>
</form>

<script>
    function selectCaptcha(el, provider) {
        document.querySelectorAll('.captcha-option').forEach(opt => {
            opt.classList.remove('selected');
            const badge = opt.querySelector('.option-badge');
            if (badge) badge.remove();
        });
        el.classList.add('selected');
        const badge = document.createElement('span');
        badge.className = 'option-badge';
        badge.innerText = 'Aktif';
        el.insertBefore(badge, el.firstChild);
        document.getElementById('captchaProvider').value = provider;
    }
</script>
@endsection

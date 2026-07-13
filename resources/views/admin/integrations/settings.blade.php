@extends('admin.layouts.master')

@section('title', $title)
@section('page_title', 'Pengaturan ' . strtoupper($service))

@section('content')
<style>
    /* ── Premium Layout Styles ── */
    .integration-wrapper {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 30px;
        align-items: start;
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @media (max-width: 991px) {
        .integration-wrapper {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    /* Premium Glass Card */
    .premium-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.06);
        border-color: rgba(139, 92, 246, 0.25);
    }

    /* Gradient Header Panel */
    .premium-card-header {
        position: relative;
        padding: 32px 32px 24px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.07) 0%, rgba(139, 92, 246, 0.05) 50%, rgba(236, 72, 153, 0.03) 100%);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .header-glow-orb {
        position: absolute;
        top: -20%; right: -10%;
        width: 150px; height: 150px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .premium-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: transform 0.3s ease;
    }

    .premium-card:hover .premium-icon-box {
        transform: scale(1.05) rotate(-3deg);
    }

    .premium-icon-box.google { color: #eb4d4b; }
    .premium-icon-box.github { color: #1e272e; }
    .premium-icon-box.mail { color: var(--success); }
    .premium-icon-box.database { color: var(--info); }
    .premium-icon-box.session { color: var(--warning); }
    .premium-icon-box.cache { color: var(--accent-light); }

    /* Inputs Overrides */
    .premium-form-group {
        margin-bottom: 24px;
    }

    .premium-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 18px;
        color: #94a3b8;
        font-size: 15px;
        transition: color 0.2s;
    }

    .input-icon-wrapper input,
    .input-icon-wrapper select {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 13px 18px 13px 46px;
        color: #1e293b;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: all 0.25s ease;
    }

    .input-icon-wrapper input:focus,
    .input-icon-wrapper select:focus {
        border-color: #8b5cf6;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }

    .input-icon-wrapper input:focus + i {
        color: #8b5cf6;
    }

    .premium-help-text {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Sidebar Status Card */
    .status-card {
        padding: 24px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .status-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background-color: var(--success);
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
        animation: pulse-dot 2s infinite;
    }

    .status-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .status-item-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-item-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        font-family: monospace;
        word-break: break-all;
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    /* Premium Button */
    .btn-gradient {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.25);
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.35);
    }

    .btn-gradient:active {
        transform: translateY(0);
    }
</style>

<!-- Page Header -->
<div class="page-header" style="margin-bottom: 28px;">
    <div class="page-header-left">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="{{ route('admin.integrations') }}" class="btn btn-outline" style="padding: 10px 16px; border-radius: 12px; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1>Pengaturan <span>{{ strtoupper($service) }}</span></h1>
        </div>
        <p style="margin-top: 10px; font-size: 14px; color: var(--text-secondary);">{{ $description }}</p>
    </div>
</div>

<!-- Main Wrapper -->
<div class="integration-wrapper">
    
    <!-- Left Side: Config Form -->
    <div class="premium-card">
        <div class="premium-card-header">
            <div class="header-glow-orb"></div>
            <div class="premium-icon-box {{ $service }}">
                @if($service === 'google')
                    <i class="fab fa-google"></i>
                @elseif($service === 'github')
                    <i class="fab fa-github"></i>
                @elseif($service === 'mail')
                    <i class="fas fa-envelope"></i>
                @elseif($service === 'database')
                    <i class="fas fa-database"></i>
                @elseif($service === 'session')
                    <i class="fas fa-cookie-bite"></i>
                @elseif($service === 'cache')
                    <i class="fas fa-bolt"></i>
                @endif
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 800; color: #1e293b;">Formulir Konfigurasi</h3>
                <p style="font-size: 12px; color: #64748b; margin-top: 2px;">Nilai di bawah tersimpan secara langsung di berkas .env Anda.</p>
            </div>
        </div>

        <div class="card-body" style="padding: 32px;">
            <form action="{{ route('admin.integrations.update', $service) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memperbarui konfigurasi {{ strtoupper($service) }} ini? Tindakan ini akan langsung merubah file .env server.')">
                @csrf

                @foreach($fields as $name => $field)
                    <div class="premium-form-group">
                        <label for="{{ $name }}">
                            {{ $field['label'] }}
                            @if(!empty($field['required']))
                                <span style="color: var(--danger);">*</span>
                            @endif
                        </label>
                        
                        <div class="input-icon-wrapper">
                            @if(str_contains(strtolower($name), 'key') || str_contains(strtolower($name), 'id'))
                                <i class="fas fa-key"></i>
                            @elseif(str_contains(strtolower($name), 'secret') || str_contains(strtolower($name), 'password'))
                                <i class="fas fa-lock"></i>
                            @elseif(str_contains(strtolower($name), 'uri') || str_contains(strtolower($name), 'url') || str_contains(strtolower($name), 'host'))
                                <i class="fas fa-link"></i>
                            @elseif(str_contains(strtolower($name), 'port'))
                                <i class="fas fa-plug"></i>
                            @elseif(str_contains(strtolower($name), 'driver') || str_contains(strtolower($name), 'connection') || str_contains(strtolower($name), 'mailer') || str_contains(strtolower($name), 'store'))
                                <i class="fas fa-cog"></i>
                            @elseif(str_contains(strtolower($name), 'name') || str_contains(strtolower($name), 'database') || str_contains(strtolower($name), 'username'))
                                <i class="fas fa-user-cog"></i>
                            @elseif(str_contains(strtolower($name), 'from_address') || str_contains(strtolower($name), 'email'))
                                <i class="fas fa-paper-plane"></i>
                            @else
                                <i class="fas fa-edit"></i>
                            @endif

                            @if($field['type'] === 'select')
                                <select name="{{ $name }}" id="{{ $name }}" @if(!empty($field['required'])) required @endif>
                                    @foreach($field['options'] as $optVal => $optLabel)
                                        <option value="{{ $optVal }}" {{ $field['value'] == $optVal ? 'selected' : '' }}>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'number')
                                <input type="number" name="{{ $name }}" id="{{ $name }}" 
                                       value="{{ $field['value'] }}" 
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       @if(!empty($field['required'])) required @endif>
                            @elseif($field['type'] === 'email')
                                <input type="email" name="{{ $name }}" id="{{ $name }}" 
                                       value="{{ $field['value'] }}" 
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       @if(!empty($field['required'])) required @endif>
                            @else
                                <input type="text" name="{{ $name }}" id="{{ $name }}" 
                                       value="{{ $field['value'] }}" 
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       @if(!empty($field['required'])) required @endif>
                            @endif
                        </div>
                        
                        @if($name === 'GOOGLE_REDIRECT_URI')
                            <span class="premium-help-text">
                                <i class="fas fa-info-circle"></i> Pastikan untuk mendaftarkan URL ini di panel Google Developers Console Anda.
                            </span>
                        @elseif($name === 'GITHUB_REDIRECT_URI')
                            <span class="premium-help-text">
                                <i class="fas fa-info-circle"></i> Pastikan URL callback di atas cocok dengan konfigurasi OAuth App Anda di GitHub.
                            </span>
                        @endif
                    </div>
                @endforeach

                <div style="display: flex; gap: 14px; margin-top: 32px; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 24px;">
                    <button type="submit" class="btn-gradient">
                        <i class="fas fa-save"></i> Simpan Konfigurasi
                    </button>
                    <a href="{{ route('admin.integrations') }}" class="btn btn-outline" style="border-radius: 14px; padding: 12px 24px; font-size: 13px; font-weight: 600;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Status Summary -->
    <div class="premium-card" style="padding: 4px;">
        <div class="status-card">
            <div class="status-header">
                <h4 style="font-size: 14px; font-weight: 800; color: #1e293b;">Ringkasan Status</h4>
                <span class="status-badge">
                    <span class="status-badge-dot"></span>
                    Aktif
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 4px;">
                @foreach($fields as $name => $field)
                    <div class="status-item">
                        <span class="status-item-label">{{ str_replace('_', ' ', $name) }}</span>
                        <span class="status-item-value">
                            @if(str_contains(strtolower($name), 'secret') || str_contains(strtolower($name), 'password'))
                                @if(!empty($field['value']))
                                    {{ substr($field['value'], 0, 4) }}••••••••••••••••
                                @else
                                    <span style="color:#94a3b8;font-style:italic;">[Belum Diisi]</span>
                                @endif
                            @else
                                {{ $field['value'] ?: '[Kosong]' }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 12px; background: rgba(99, 102, 241, 0.05); border: 1.5px dashed rgba(139, 92, 246, 0.2); border-radius: 12px; padding: 16px; font-size: 12px; line-height: 1.6; color: #475569;">
                <h5 style="font-weight: 700; color: #6366f1; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-shield-alt"></i> Keamanan Terjamin
                </h5>
                Data kredensial dan kunci API disamarkan demi keamanan visual. Penyimpanan data diproteksi langsung di level konfigurasi server.
            </div>
        </div>
    </div>

</div>
@endsection

@extends('admin.layouts.master')

@section('title', 'Kupon')

@section('page_title', 'Kupon')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Kelola <span>Kupon</span></h1>
        <p>Buat dan kelola kupon premium untuk pengguna VizzioDocs.</p>
    </div>
    <div class="page-header-right">
        <button class="btn btn-primary" id="btnBuatKupon" onclick="document.getElementById('modalBuatKupon').classList.add('show')">
            <i class="fas fa-plus"></i> Buat Kupon Baru
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-ticket-alt"></i></div>
        </div>
        <div class="stat-value">{{ $coupons->count() }}</div>
        <div class="stat-label">Total Kupon</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $coupons->filter(fn($c) => $c->isValid())->count() }}</div>
        <div class="stat-label">Kupon Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
        </div>
        <div class="stat-value">{{ $totalPremium }}</div>
        <div class="stat-label">Pengguna Premium</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
</div>

{{-- Coupon List --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-ticket-alt" style="color: var(--accent-light); margin-right: 8px;"></i>Daftar Kupon</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($coupons->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table" style="width:100%;border-collapse:collapse;min-width:700px;">
                    <thead>
                        <tr style="background: var(--bg-card-hover);">
                            <th style="padding:14px 18px;text-align:left;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Kode</th>
                            <th style="padding:14px 18px;text-align:left;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Nama</th>
                            <th style="padding:14px 18px;text-align:center;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Durasi</th>
                            <th style="padding:14px 18px;text-align:center;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Pakai / Limit</th>
                            <th style="padding:14px 18px;text-align:center;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Status</th>
                            <th style="padding:14px 18px;text-align:center;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            @php $valid = $coupon->isValid(); @endphp
                            <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                                <td style="padding:14px 18px;">
                                    <code style="background:var(--accent-bg);color:var(--accent-light);padding:4px 12px;border-radius:8px;font-size:13px;font-weight:700;letter-spacing:0.05em;">{{ $coupon->code }}</code>
                                </td>
                                <td style="padding:14px 18px;">
                                    <div style="font-weight:600;color:var(--text-primary);font-size:14px;">{{ $coupon->name }}</div>
                                    @if($coupon->description)
                                        <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ Str::limit($coupon->description, 40) }}</div>
                                    @endif
                                </td>
                                <td style="padding:14px 18px;text-align:center;font-weight:600;color:var(--text-primary);">{{ $coupon->duration_days }} hari</td>
                                <td style="padding:14px 18px;text-align:center;">
                                    <span style="font-weight:700;color:{{ $coupon->times_used >= $coupon->usage_limit ? '#ef4444' : 'var(--text-primary)' }}">{{ $coupon->times_used }}</span>
                                    <span style="color:var(--text-muted);"> / {{ $coupon->usage_limit }}</span>
                                </td>
                                <td style="padding:14px 18px;text-align:center;">
                                    @if($valid)
                                        <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(16,185,129,0.1);color:#10b981;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(239,68,68,0.1);color:#ef4444;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#ef4444;"></span>
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td style="padding:14px 18px;text-align:center;">
                                    <form method="POST" action="{{ route('admin.coupons.delete', $coupon->id) }}" onsubmit="return confirm('Hapus kupon {{ $coupon->code }}?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:16px;padding:4px 8px;border-radius:8px;transition:background 0.2s;" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center;padding:60px 24px;">
                <i class="fas fa-gift" style="font-size:48px;color:var(--text-muted);opacity:0.4;margin-bottom:16px;display:block;"></i>
                <h3 style="margin-bottom:8px;color:var(--text-primary);">Belum Ada Kupon</h3>
                <p style="color:var(--text-muted);font-size:14px;max-width:400px;margin:0 auto;">
                    Klik tombol "Buat Kupon Baru" untuk membuat kupon premium perdana.
                </p>
            </div>
        @endif
    </div>
</div>

{{-- Modal Buat Kupon --}}
<div class="modal-overlay" id="modalBuatKupon" onclick="if(event.target===this)this.classList.remove('show')">
    <div class="modal-card" style="max-width:520px;">
        <div class="modal-header">
            <h3><i class="fas fa-ticket-alt" style="margin-right:10px;color:var(--accent-light);"></i>Buat Kupon Baru</h3>
            <button class="modal-close" onclick="document.getElementById('modalBuatKupon').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.coupons.store') }}" style="padding:24px;">
            @csrf
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Nama Kupon</label>
                <input type="text" name="name" required placeholder="cth: Promo Awal Tahun" style="width:100%;padding:11px 14px;border:1.5px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-card);color:var(--text-primary);font-family:inherit;transition:border-color 0.2s;">
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Deskripsi <span style="color:var(--text-muted);font-weight:400;">(opsional)</span></label>
                <textarea name="description" rows="2" placeholder="Deskripsi singkat tentang kupon ini" style="width:100%;padding:11px 14px;border:1.5px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-card);color:var(--text-primary);font-family:inherit;resize:vertical;transition:border-color 0.2s;"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Batas Pemakaian</label>
                    <input type="number" name="usage_limit" required min="1" max="999999" value="100" style="width:100%;padding:11px 14px;border:1.5px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-card);color:var(--text-primary);font-family:inherit;transition:border-color 0.2s;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Durasi (hari)</label>
                    <input type="number" name="duration_days" required min="1" max="365" value="7" style="width:100%;padding:11px 14px;border:1.5px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-card);color:var(--text-primary);font-family:inherit;transition:border-color 0.2s;">
                </div>
            </div>
            <div style="background:var(--accent-bg);border-radius:12px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-info-circle" style="color:var(--accent-light);font-size:14px;"></i>
                <span style="font-size:13px;color:var(--text-secondary);">Kode kupon akan digenerate secara otomatis dan unik.</span>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalBuatKupon').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Kupon</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div style="position:fixed;bottom:24px;right:24px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:14px 22px;border-radius:16px;font-weight:600;font-size:14px;box-shadow:0 8px 32px rgba(16,185,129,0.3);z-index:9999;animation:fadeInUp 0.4s ease;">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i> {{ session('success') }}
    </div>
@endif

<style>
    .admin-table tbody tr:hover {
        background: var(--bg-card-hover);
    }
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.2s ease;
    }
    .modal-overlay.show {
        display: flex;
    }
    .modal-card {
        background: var(--bg-card);
        border-radius: 24px;
        width: 100%;
        box-shadow: 0 24px 80px rgba(0,0,0,0.2);
        border: 1px solid var(--border-color);
        animation: slideUp 0.25s ease;
        overflow: hidden;
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
    }
    .modal-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-primary);
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: var(--text-muted);
        cursor: pointer;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .modal-close:hover {
        background: var(--bg-card-hover);
        color: var(--text-primary);
    }
    input:focus, textarea:focus {
        border-color: var(--accent-light) !important;
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
        outline: none;
    }
    .btn-secondary {
        background: var(--bg-card-hover);
        color: var(--text-secondary);
        border: 1.5px solid var(--border-color);
    }
    .btn-secondary:hover {
        background: var(--border-color);
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes fadeInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection

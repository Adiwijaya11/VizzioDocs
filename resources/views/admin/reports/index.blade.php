@extends('admin.layouts.master')

@section('title', 'Laporan & Transaksi')

@section('page_title', 'Laporan & Transaksi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Laporan <span>Platform</span></h1>
        <p>Ringkasan lengkap data transaksi, kupon, dan pengguna platform VizzioDocs.</p>
    </div>
    <div class="page-header-right">
        <span class="btn btn-outline" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </span>
    </div>
</div>

{{-- Header Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
        </div>
        <div class="stat-value">{{ $totalPremium }}</div>
        <div class="stat-label">Premium Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-user"></i></div>
        </div>
        <div class="stat-value">{{ $totalFree }}</div>
        <div class="stat-label">Free</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-chart-pie"></i></div>
        </div>
        <div class="stat-value">{{ $totalPremium > 0 ? round(($totalPremium / max($totalUsers, 1)) * 100) : 0 }}%</div>
        <div class="stat-label">Konversi Premium</div>
    </div>
</div>

{{-- Transaction Stats --}}
<div class="stats-grid" style="margin-top: 24px;">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon pink"><i class="fas fa-receipt"></i></div>
        </div>
        <div class="stat-value">{{ $totalTransactions }}</div>
        <div class="stat-label">Total Transaksi</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan"><i class="fas fa-credit-card"></i></div>
        </div>
        <div class="stat-value">{{ $totalPurchases }}</div>
        <div class="stat-label">Pembelian Premium</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon amber"><i class="fas fa-ticket-alt"></i></div>
        </div>
        <div class="stat-value">{{ $totalCouponRedemptions }}</div>
        <div class="stat-label">Redemption Kupon</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo"><i class="fas fa-tags"></i></div>
        </div>
        <div class="stat-value">{{ $activeCoupons }} / {{ $totalCoupons }}</div>
        <div class="stat-label">Kupon Aktif / Total</div>
    </div>
</div>

{{-- Recent Transactions Table --}}
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color: var(--accent-light); margin-right: 8px;"></i>Riwayat Transaksi Terbaru</h3>
        <span class="badge badge-outline">{{ $totalTransactions }} total transaksi</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($recentTransactions->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p style="font-size: 15px;">Belum ada transaksi.</p>
            </div>
        @else
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pengguna</th>
                            <th>Tipe</th>
                            <th>Deskripsi</th>
                            <th>Durasi</th>
                            <th>Kupon</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $tx)
                            <tr>
                                <td><span class="tx-id">#{{ $tx->id }}</span></td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-sm">
                                            {{ strtoupper(substr($tx->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="user-info">
                                            <div class="user-name">{{ $tx->user->name ?? 'User Dihapus' }}</div>
                                            <div class="user-email">{{ $tx->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($tx->type === 'premium_purchase')
                                        <span class="tx-type purchase">
                                            <i class="fas fa-credit-card"></i> Pembelian
                                        </span>
                                    @elseif($tx->type === 'coupon_redemption')
                                        <span class="tx-type coupon">
                                            <i class="fas fa-ticket-alt"></i> Kupon
                                        </span>
                                    @endif
                                </td>
                                <td class="tx-desc">{{ $tx->description }}</td>
                                <td>
                                    @if($tx->duration_label)
                                        <span class="duration-badge">{{ $tx->duration_label }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tx->coupon_code)
                                        <code class="coupon-code">{{ $tx->coupon_code }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $tx->status }}">
                                        {{ $tx->status === 'completed' ? 'Berhasil' : ucfirst($tx->status) }}
                                    </span>
                                </td>
                                <td class="tx-date">
                                    <div>{{ $tx->created_at->format('d M Y') }}</div>
                                    <div class="tx-time">{{ $tx->created_at->format('H:i') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===== Stats Grid ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px 24px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
        opacity: 0.6;
    }
    .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #a78bfa, #6366f1); }
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #fb923c, #f97316); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #60a5fa, #3b82f6); }
    .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #34d399, #10b981); }

    .stats-grid + .stats-grid .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #f472b6, #ec4899); }
    .stats-grid + .stats-grid .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #22d3ee, #06b6d4); }
    .stats-grid + .stats-grid .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .stats-grid + .stats-grid .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #818cf8, #6366f1); }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent);
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.1);
    }
    .stat-header { margin-bottom: 10px; }
    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }
    .stat-icon.purple { background: rgba(167, 139, 250, 0.15); color: #a78bfa; }
    .stat-icon.orange { background: rgba(251, 146, 60, 0.15); color: #fb923c; }
    .stat-icon.blue { background: rgba(96, 165, 250, 0.15); color: #60a5fa; }
    .stat-icon.green { background: rgba(52, 211, 153, 0.15); color: #34d399; }
    .stat-icon.pink { background: rgba(244, 114, 182, 0.15); color: #f472b6; }
    .stat-icon.cyan { background: rgba(34, 211, 238, 0.15); color: #22d3ee; }
    .stat-icon.amber { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
    .stat-icon.indigo { background: rgba(129, 140, 248, 0.15); color: #818cf8; }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.5px;
    }
    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
        font-weight: 600;
    }

    /* ===== Card ===== */
    .card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: fadeInUp 0.4s ease;
    }
    .card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .card-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
    }
    .badge-outline {
        font-size: 11px;
        padding: 4px 12px;
        border: 1px solid var(--border);
        border-radius: 20px;
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* ===== Table ===== */
    .table-wrapper {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table thead th {
        background: rgba(99, 102, 241, 0.04);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: var(--text-primary);
        vertical-align: middle;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table tbody tr:hover {
        background: rgba(99, 102, 241, 0.03);
    }

    .tx-id {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-weight: 600;
        color: var(--accent-light);
        font-size: 12px;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.2));
        border: 1px solid rgba(99,102,241,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent-light);
        flex-shrink: 0;
    }
    .user-info {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-weight: 600;
        font-size: 13px;
        color: var(--text-primary);
    }
    .user-email {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .tx-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .tx-type.purchase {
        background: rgba(52, 211, 153, 0.12);
        color: #34d399;
    }
    .tx-type.coupon {
        background: rgba(251, 191, 36, 0.12);
        color: #fbbf24;
    }

    .tx-desc {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .duration-badge {
        background: rgba(99, 102, 241, 0.1);
        color: var(--accent-light);
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .coupon-code {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 11px;
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
        padding: 2px 8px;
        border-radius: 6px;
        border: 1px solid rgba(251, 191, 36, 0.15);
    }

    .status-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-completed {
        background: rgba(52, 211, 153, 0.12);
        color: #34d399;
    }
    .status-expired {
        background: rgba(248, 113, 113, 0.12);
        color: #f87171;
    }
    .status-cancelled {
        background: rgba(248, 113, 113, 0.08);
        color: var(--text-muted);
    }

    .tx-date {
        white-space: nowrap;
    }
    .tx-date div:first-child {
        font-weight: 600;
        font-size: 12px;
    }
    .tx-time {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .text-muted {
        color: var(--text-muted);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

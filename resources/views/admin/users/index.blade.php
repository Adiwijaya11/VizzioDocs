@extends('admin.layouts.master')

@section('title', 'Pengguna')

@section('page_title', 'Pengguna')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Kelola <span>Pengguna</span></h1>
        <p>Total {{ $totalUsers }} pengguna terdaftar di platform VizzioDocs.</p>
    </div>
    <div class="page-header-right">
        <span class="btn btn-outline" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </span>
    </div>
</div>

{{-- Alert Success --}}
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

{{-- Stats Mini --}}
<div class="stats-grid">
    <div class="stat-card" style="animation-delay: 0s;">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card" style="animation-delay: 0.08s;">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="stat-value">{{ $totalAdmins }}</div>
        <div class="stat-label">Admin</div>
    </div>
    <div class="stat-card" style="animation-delay: 0.16s;">
        <div class="stat-header">
            <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
        </div>
        <div class="stat-value">{{ $totalPremium }}</div>
        <div class="stat-label">Premium</div>
    </div>
    <div class="stat-card" style="animation-delay: 0.24s;">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-chart-bar"></i></div>
        </div>
        <div class="stat-value">{{ $totalGuestUsages }}</div>
        <div class="stat-label">Total Guest Usage</div>
    </div>
</div>

{{-- User Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color: var(--accent-light); margin-right: 8px;"></i>Daftar Pengguna</h3>
        <div style="display: flex; gap: 8px;">
            <span style="font-size: 12px; color: var(--text-muted);">{{ $users->total() }} total entries</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        {{-- Filter & Search --}}
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border);">
            <form method="GET" action="{{ route('admin.users') }}" class="filter-bar">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="search-input" placeholder="Cari nama, email, atau asal..." value="{{ request('search') }}">
                </div>
                <select name="role" onchange="this.form.submit()">
                    <option value="all" {{ request('role') == 'all' || !request('role') ? 'selected' : '' }}>Semua Role</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <select name="plan" onchange="this.form.submit()">
                    <option value="all" {{ request('plan') == 'all' || !request('plan') ? 'selected' : '' }}>Semua Plan</option>
                    <option value="free" {{ request('plan') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
                @if(request()->anyFilled(['search', 'role', 'plan']))
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Desktop Table --}}
        <div class="table-wrap">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama & Email</th>
                        <th>Role</th>
                        <th>Plan</th>
                        <th>Detail</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="td-num">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user' }}"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $user->plan === 'premium' ? 'badge-premium' : 'badge-free' }}" id="plan-badge-{{ $user->id }}">
                                    <i class="fas fa-{{ $user->plan === 'premium' ? 'crown' : 'user' }}"></i>
                                    <span class="plan-text">{{ ucfirst($user->plan) }}</span>
                                </span>
                            </td>
                            <td>
                                <div class="detail-cell">
                                    @if($user->origin)
                                        <span><i class="fas fa-globe-asia"></i> {{ $user->origin }}</span>
                                    @endif
                                    @if($user->country)
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $user->country }}</span>
                                    @endif
                                    @if($user->phone_number)
                                        <span><i class="fas fa-phone"></i> {{ $user->phone_number }}</span>
                                    @endif
                                    @if(!$user->origin && !$user->country && !$user->phone_number)
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="td-date">
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                                <small>{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="action-cell">
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline" onclick="openEditPlan({{ $user->id }}, '{{ $user->name }}', '{{ $user->plan }}')" title="Edit Plan">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock"></i> Anda</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="table-empty">
                                <i class="fas fa-inbox"></i>
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="pagination">
                <div class="info">
                    Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </div>
                <div class="links">
                    @if($users->currentPage() > 1)
                        <a href="{{ $users->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="{{ $page == $users->currentPage() ? 'active' : '' }}"
                           style="{{ $page == $users->currentPage() ? 'background: var(--accent); color: #fff;' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Edit Plan Modal --}}
<div class="modal-overlay" id="editPlanModal" onclick="closeEditPlan(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color: var(--accent-light);"></i> Edit Plan Pengguna</h3>
            <button class="modal-close" onclick="closeEditPlan()">&times;</button>
        </div>
        <form method="POST" action="" id="editPlanForm">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 16px; color: var(--text-secondary);">
                    Ubah plan untuk <strong id="editPlanUserName"></strong>
                </p>
                <div class="plan-options">
                    <label class="plan-option" id="planFree">
                        <input type="radio" name="plan" value="free">
                        <div class="plan-option-content">
                            <div class="plan-option-icon free-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="plan-option-name">Free</div>
                                <div class="plan-option-desc">Akses terbatas</div>
                            </div>
                        </div>
                    </label>
                    <label class="plan-option" id="planPremium">
                        <input type="radio" name="plan" value="premium">
                        <div class="plan-option-content">
                            <div class="plan-option-icon premium-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div>
                                <div class="plan-option-name">Premium</div>
                                <div class="plan-option-desc">Akses penuh semua fitur</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditPlan()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===== Improved Mobile-Friendly Table ===== */
    .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table.user-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 0;
    }

    table.user-table thead {
        background: rgba(99, 102, 241, 0.08);
    }

    table.user-table th {
        padding: 12px 14px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        font-weight: 600;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    table.user-table td {
        padding: 12px 14px;
        font-size: 13px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    table.user-table tr:last-child td { border-bottom: none; }
    table.user-table tr:hover td { background: rgba(99, 102, 241, 0.03); }

    .td-num {
        width: 40px;
        text-align: center;
        color: var(--text-muted) !important;
        font-size: 12px !important;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    .user-email {
        font-size: 12px;
        color: var(--text-muted);
        word-break: break-all;
    }

    .detail-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 12px;
    }

    .detail-cell span {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .detail-cell i {
        width: 14px;
        color: var(--text-muted);
        font-size: 11px;
    }

    .td-date {
        white-space: nowrap;
    }

    .td-date span {
        display: block;
        font-size: 12px;
        font-weight: 500;
    }

    .td-date small {
        font-size: 11px;
        color: var(--text-muted);
    }

    .action-cell {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .text-muted {
        color: var(--text-muted);
        font-size: 12px;
    }

    /* ===== Modal ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: var(--bg-card);
        border-radius: var(--radius);
        width: 90%;
        max-width: 440px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        animation: fadeInUp 0.3s ease;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .modal-header h3 {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--text-muted);
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: rgba(225,112,85,0.1);
        color: var(--danger);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Plan Options */
    .plan-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .plan-option {
        cursor: pointer;
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 4px;
        transition: all 0.2s ease;
        display: block;
    }

    .plan-option input {
        display: none;
    }

    .plan-option-content {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
    }

    .plan-option-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .free-icon {
        background: rgba(148, 163, 184, 0.15);
        color: var(--text-muted);
    }

    .premium-icon {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning);
    }

    .plan-option-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .plan-option-desc {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .plan-option:has(input:checked) {
        border-color: var(--accent);
        background: rgba(99, 102, 241, 0.05);
    }

    .plan-option:hover {
        border-color: var(--accent-light);
    }

    /* ===== Mobile Responsive: Stack Table ===== */
    @media (max-width: 768px) {
        table.user-table thead {
            display: none;
        }

        table.user-table,
        table.user-table tbody,
        table.user-table tr,
        table.user-table td {
            display: block;
            width: 100%;
        }

        table.user-table tr {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        table.user-table tr:last-child {
            border-bottom: none;
        }

        table.user-table td {
            padding: 4px 0;
            border: none;
            text-align: left;
        }

        table.user-table td:before {
            content: attr(data-label);
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 2px;
        }

        .td-num {
            position: absolute;
            top: 12px;
            right: 12px;
            width: auto;
            font-size: 11px !important;
            color: var(--text-muted) !important;
        }

        .detail-cell {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 8px;
        }

        .action-cell {
            margin-top: 8px;
        }

        table.user-table tr:hover td {
            background: transparent;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        .modal-content {
            width: 95%;
            margin: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function openEditPlan(userId, userName, currentPlan) {
        document.getElementById('editPlanUserName').textContent = userName;
        document.getElementById('editPlanForm').action = '{{ route("admin.users.update-plan", ["id" => "__ID__"]) }}'.replace('__ID__', userId);

        // Select the current plan
        const radios = document.querySelectorAll('input[name="plan"]');
        radios.forEach(r => r.checked = r.value === currentPlan);

        document.getElementById('editPlanModal').classList.add('show');
    }

    function closeEditPlan(e) {
        if (e && e.target !== e.currentTarget) return;
        document.getElementById('editPlanModal').classList.remove('show');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('editPlanModal').classList.remove('show');
        }
    });
</script>
@endpush

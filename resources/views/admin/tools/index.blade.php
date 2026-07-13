@extends('admin.layouts.master')

@section('title', 'Tools')

@section('page_title', 'Tools')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Manajemen <span>Tools</span></h1>
        <p>Kelola alat PDF, pantau penggunaan, dan kunci/buka akses tools.</p>
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

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-tools"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsage }}</div>
        <div class="stat-label">Total Penggunaan Tools</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-unlock"></i></div>
        </div>
        <div class="stat-value">{{ $activeTools }}</div>
        <div class="stat-label">Tools Aktif (Terbuka)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange"><i class="fas fa-chart-simple"></i></div>
        </div>
        <div class="stat-value">@if($popularTool !== '-') {{ $popularTool }} @else - @endif</div>
        <div class="stat-label">Tools Terpopuler ({{ $popularCount }} uses)</div>
    </div>
</div>

{{-- Tools Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color: var(--accent-light); margin-right: 8px;"></i>Daftar Tools ({{ $toolData->count() }})</h3>
        <span style="font-size: 13px; color: var(--text-muted);">
            <span style="display: inline-flex; align-items: center; gap: 4px; margin-right: 12px;">
                <span style="width: 10px; height: 10px; border-radius: 50%; background: var(--success); display: inline-block;"></span>
                Terbuka ({{ $activeTools }})
            </span>
            <span style="display: inline-flex; align-items: center; gap: 4px;">
                <span style="width: 10px; height: 10px; border-radius: 50%; background: var(--danger); display: inline-block;"></span>
                Terkunci ({{ $lockedTools }})
            </span>
        </span>
    </div>
    <div class="card-body" style="padding: 0;">
        {{-- Filter & Search --}}
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border);">
            <div class="filter-bar" style="margin-bottom: 0;">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="toolSearch" class="search-input" placeholder="Cari tools..." onkeyup="filterTools()">
                </div>
                <select id="statusFilter" onchange="filterTools()">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif (Terbuka)</option>
                    <option value="locked">Terkunci</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Tools</th>
                        <th>Slug</th>
                        <th style="text-align: center;">Penggunaan</th>
                        <th style="text-align: center;">Pengguna Unik</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="toolsTableBody">
                    @forelse($toolData as $index => $tool)
                    <tr class="tool-row" data-name="{{ strtolower($tool->tool_name) }}" data-slug="{{ $tool->tool_slug }}" data-status="{{ $tool->is_locked ? 'locked' : 'active' }}">
                        <td style="color: var(--text-muted); font-weight: 500;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(108, 92, 231, 0.1); display: flex; align-items: center; justify-content: center; color: var(--accent-light); font-size: 14px; flex-shrink: 0;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $tool->tool_name }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $tool->tool_route ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><code style="background: rgba(108, 92, 231, 0.08); padding: 3px 8px; border-radius: 4px; font-size: 12px; color: var(--accent-light);">{{ $tool->tool_slug }}</code></td>
                        <td style="text-align: center;">
                            <span style="font-weight: 700; color: var(--text-primary);">{{ number_format($tool->usage_count) }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: 600; color: var(--text-secondary);">{{ number_format($tool->unique_users) }}</span>
                        </td>
                        <td style="text-align: center;">
                            @if($tool->is_locked)
                                <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger);">
                                    <i class="fas fa-lock"></i> Terkunci
                                </span>
                            @else
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success);">
                                    <i class="fas fa-unlock"></i> Aktif
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button type="button"
                                class="btn btn-sm {{ $tool->is_locked ? 'btn-success' : 'btn-danger' }}"
                                style="min-width: 90px; justify-content: center;"
                                data-action="toggle-lock"
                                data-tool-id="{{ $tool->id }}"
                                data-tool-name="{{ $tool->tool_name }}"
                                data-tool-slug="{{ $tool->tool_slug }}"
                                data-tool-status="{{ $tool->is_locked ? 'locked' : 'active' }}"
                                data-form-action="{{ route('admin.tools.toggle-lock', $tool->id) }}"
                                onclick="openToolModal(this)">
                                <i class="fas fa-{{ $tool->is_locked ? 'unlock' : 'lock' }}"></i>
                                {{ $tool->is_locked ? 'Buka' : 'Kunci' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="table-empty">
                            <i class="fas fa-tools"></i>
                            Tidak ada tools ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 12px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 13px; color: var(--text-muted);">
                Menampilkan <strong>{{ $toolData->count() }}</strong> tools
            </span>
            <span style="font-size: 12px; color: var(--text-muted);">
                <i class="fas fa-info-circle"></i> Klik tombol Kunci/Buka untuk mengubah status tool
            </span>
        </div>
    </div>
</div>

{{-- Info Card --}}
<div class="card" style="margin-top: 20px; background: rgba(108, 92, 231, 0.03); border: 1px solid rgba(108, 92, 231, 0.1);">
    <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px 24px;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(108, 92, 231, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas fa-lightbulb" style="color: var(--warning); font-size: 18px;"></i>
        </div>
        <div>
            <div style="font-weight: 600; font-size: 14px; color: var(--text-primary); margin-bottom: 2px;">Informasi Status Tools</div>
            <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                Tools dengan status <strong style="color: var(--success);">Aktif</strong> dapat digunakan oleh pengguna. 
                Tools dengan status <strong style="color: var(--danger);">Terkunci</strong> akan menampilkan ikon gembok dan tidak bisa digunakan.
                Data penggunaan diambil dari riwayat penggunaan tools secara <strong>real</strong>.
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Modal Konfirmasi Buka/Tutup Tool --}}
<div class="modal-overlay" id="toolConfirmModal" onclick="closeToolModal(event)">
    <div class="modal-content modal-confirm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitle"><i class="fas fa-exchange-alt"></i> <span id="modalActionLabel">Konfirmasi</span></h3>
            <button class="modal-close" onclick="closeToolModal()">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 16px 24px 24px;">
            {{-- Icon dinamis --}}
            <div class="modal-confirm-icon" id="modalIconBox">
                <i class="fas fa-unlock" id="modalIcon"></i>
            </div>
            <h4 class="modal-confirm-title" id="modalConfirmTitle">Konfirmasi Tindakan</h4>
            <p class="modal-confirm-desc" id="modalConfirmDesc">Apakah Anda yakin ingin mengubah status tool ini?</p>
            <div class="modal-confirm-tool" id="modalToolInfo">
                <div class="modal-tool-icon-wrapper">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="modal-tool-details">
                    <div class="modal-tool-name" id="modalToolName">-</div>
                    <div class="modal-tool-badge" id="modalToolBadge">PDF Tool</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="closeToolModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <form id="modalToggleForm" method="POST" action="" style="flex: 1; display: block; margin: 0;">
                @csrf
                <button type="submit" class="modal-btn-confirm" id="modalConfirmBtn">
                    <i class="fas fa-check" id="modalConfirmIcon"></i>
                    <span id="modalConfirmText">Ya, Konfirmasi</span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-success {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
    }
    .btn-success:hover {
        background: rgba(16, 185, 129, 0.25);
    }
    .btn-danger {
        background: rgba(239, 68, 68, 0.12);
        color: var(--danger);
    }
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.25);
    }
    .card {
        animation: fadeInUp 0.4s ease;
    }
    .tool-row {
        transition: background 0.15s;
    }
    .tool-row:hover td {
        background: rgba(108, 92, 231, 0.03);
    }

    /* ===== Modal Base (overlay + content) ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: var(--bg-card, #ffffff);
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 30px 60px -15px rgba(108, 92, 231, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.6);
        max-width: 420px;
        width: 92%;
        transform: scale(0.92) translateY(15px);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.8);
        overflow: hidden;
    }

    .modal-overlay.show .modal-content {
        transform: scale(1) translateY(0);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 24px 0;
    }

    .modal-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modal-close {
        background: rgba(0, 0, 0, 0.03);
        border: none;
        font-size: 16px;
        color: var(--text-muted, #94a3b8);
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-close:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 24px 24px 16px;
    }

    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: stretch;
        gap: 12px;
        padding: 16px 24px 24px;
        border-top: 1px solid rgba(0, 0, 0, 0.03);
    }

    .modal-confirm {
        max-width: 420px;
        width: 92%;
    }

    .modal-confirm-icon {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 20px;
        position: relative;
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Glowing ring behind the squircle */
    .modal-confirm-icon::after {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 28px;
        opacity: 0.3;
        z-index: -1;
        transition: all 0.5s ease;
    }

    .modal-confirm-icon.unlock {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    }
    .modal-confirm-icon.unlock::after {
        background: rgba(16, 185, 129, 0.2);
        animation: pulseGlowGreen 2s infinite;
    }

    .modal-confirm-icon.lock {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
    }
    .modal-confirm-icon.lock::after {
        background: rgba(239, 68, 68, 0.2);
        animation: pulseGlowRed 2s infinite;
    }

    @keyframes pulseGlowGreen {
        0% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(1); opacity: 0.5; }
    }
    @keyframes pulseGlowRed {
        0% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(1); opacity: 0.5; }
    }

    .modal-confirm-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .modal-confirm-desc {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 24px;
        line-height: 1.6;
        max-width: 320px;
        margin-left: auto;
        margin-right: auto;
    }

    .modal-confirm-tool {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.04) 0%, rgba(108, 92, 231, 0.08) 100%);
        border: 1px dashed rgba(108, 92, 231, 0.25);
        padding: 14px 20px;
        border-radius: 16px;
        max-width: 320px;
        margin: 0 auto;
        transition: all 0.3s ease;
    }
    
    .modal-confirm-tool:hover {
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.08) 0%, rgba(108, 92, 231, 0.12) 100%);
        border-color: rgba(108, 92, 231, 0.4);
        transform: translateY(-2px);
    }

    .modal-tool-icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(108, 92, 231, 0.12);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .modal-tool-details {
        text-align: left;
    }
    
    .modal-tool-name {
        font-weight: 700;
        font-size: 15px;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 2px;
    }
    
    .modal-tool-badge {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* Beautiful custom buttons inside modal */
    .modal-btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 22px;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(148, 163, 184, 0.08);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }
    .modal-btn-cancel:hover {
        background: rgba(148, 163, 184, 0.15);
        border-color: rgba(148, 163, 184, 0.4);
        color: var(--text-primary);
        transform: translateY(-1px);
    }
    .modal-btn-cancel:active {
        transform: translateY(0) scale(0.97);
    }

    .modal-btn-confirm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 22px;
        border-radius: 14px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        color: #ffffff;
    }

    .modal-btn-confirm.btn-confirm-unlock {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
    }
    .modal-btn-confirm.btn-confirm-unlock:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
    }
    .modal-btn-confirm.btn-confirm-unlock:active {
        transform: translateY(0) scale(0.97);
    }

    .modal-btn-confirm.btn-confirm-lock {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
    }
    .modal-btn-confirm.btn-confirm-lock:hover {
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
        transform: translateY(-1px);
    }
    .modal-btn-confirm.btn-confirm-lock:active {
        transform: translateY(0) scale(0.97);
    }

    /* Icon entry animations */
    @keyframes lockShake {
        0%, 100% { transform: scale(1) rotate(0); }
        20% { transform: scale(1.1) rotate(-10deg); }
        40% { transform: scale(1.1) rotate(10deg); }
        60% { transform: scale(1.1) rotate(-10deg); }
        80% { transform: scale(1.1) rotate(10deg); }
    }
    @keyframes unlockBounce {
        0%, 100% { transform: scale(1) translateY(0); }
        50% { transform: scale(1.15) translateY(-8px); }
    }

    .modal-overlay.show .modal-confirm-icon.lock i {
        animation: lockShake 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
    .modal-overlay.show .modal-confirm-icon.unlock i {
        animation: unlockBounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
</style>
@endpush

@push('scripts')
<script>
    function filterTools() {
        const search = document.getElementById('toolSearch').value.toLowerCase().trim();
        const status = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.tool-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const slug = row.getAttribute('data-slug') || '';
            const rowStatus = row.getAttribute('data-status') || 'active';

            const matchesSearch = name.includes(search) || slug.includes(search);
            const matchesStatus = status === 'all' || rowStatus === status;

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    // ===== Modal Konfirmasi Tools =====
    const modal = document.getElementById('toolConfirmModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalIcon = document.getElementById('modalIcon');
    const modalIconBox = document.getElementById('modalIconBox');
    const modalActionLabel = document.getElementById('modalActionLabel');
    const modalConfirmTitle = document.getElementById('modalConfirmTitle');
    const modalConfirmDesc = document.getElementById('modalConfirmDesc');
    const modalToolName = document.getElementById('modalToolName');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const modalConfirmText = document.getElementById('modalConfirmText');
    const modalConfirmIcon = document.getElementById('modalConfirmIcon');
    const modalToggleForm = document.getElementById('modalToggleForm');

    let currentToolId = null;

    function openToolModal(btn) {
        const toolId = btn.getAttribute('data-tool-id');
        const toolName = btn.getAttribute('data-tool-name');
        const toolSlug = btn.getAttribute('data-tool-slug');
        const toolStatus = btn.getAttribute('data-tool-status');
        const formAction = btn.getAttribute('data-form-action');
        const isLocked = toolStatus === 'locked';

        currentToolId = toolId;

        // Set form action dari data attribute
        modalToggleForm.action = formAction;

        // Update icon dan UI berdasarkan status
        if (isLocked) {
            // Membuka tool
            modalIcon.className = 'fas fa-unlock';
            modalIconBox.className = 'modal-confirm-icon unlock';
            modalActionLabel.textContent = 'Buka Tool';
            modalConfirmTitle.textContent = 'Buka Akses Tool';
            modalConfirmDesc.textContent = 'Tool ini akan dapat digunakan oleh semua pengguna.';
            modalConfirmBtn.className = 'modal-btn-confirm btn-confirm-unlock';
            modalConfirmText.textContent = 'Ya, Buka Tool';
            modalConfirmIcon.className = 'fas fa-unlock';
        } else {
            // Mengunci tool
            modalIcon.className = 'fas fa-lock';
            modalIconBox.className = 'modal-confirm-icon lock';
            modalActionLabel.textContent = 'Kunci Tool';
            modalConfirmTitle.textContent = 'Kunci Akses Tool';
            modalConfirmDesc.textContent = 'Tool ini akan disembunyikan dan tidak bisa digunakan pengguna.';
            modalConfirmBtn.className = 'modal-btn-confirm btn-confirm-lock';
            modalConfirmText.textContent = 'Ya, Kunci Tool';
            modalConfirmIcon.className = 'fas fa-lock';
        }

        // Set tool name & slug badge
        modalToolName.textContent = toolName;
        document.getElementById('modalToolBadge').textContent = toolSlug ? '/' + toolSlug : 'PDF Tool';

        // Scrollbar width compensation (cegah layout shift)
        const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollBarWidth + 'px';
        document.body.style.overflow = 'hidden';

        // Tampilkan modal
        modal.classList.add('show');
    }

    function closeToolModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeToolModal();
        }
    });
</script>
@endpush

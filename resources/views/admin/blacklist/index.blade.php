@extends('admin.layouts.master')

@section('title', 'Blacklist')
@section('page_title', 'Blacklist')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Blacklist <span>Manajemen</span></h1>
        <p>Kelola pemblokiran Email, IP Address, dan Device ID untuk menjaga integritas platform.</p>
    </div>
    <div class="page-header-right">
        <button class="btn btn-primary" onclick="showAddBlockModal()">
            <i class="fas fa-plus"></i> Tambah Blokir baru
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; background: rgba(16, 185, 129, 0.1); color: var(--success); border-radius: 8px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="chart-card" style="margin-bottom: 24px;">
    <h3><i class="fas fa-filter"></i> Tipe Blacklist</h3>
    <div class="flex-row" style="display: flex; gap: 10px; margin-top: 10px;">
        <a href="?tab=email" class="btn {{ request('tab', 'email') === 'email' ? 'btn-primary' : 'btn-outline' }}">Email</a>
        <a href="?tab=ip" class="btn {{ request('tab') === 'ip' ? 'btn-primary' : 'btn-outline' }}">IP Address</a>
        <a href="?tab=device" class="btn {{ request('tab') === 'device' ? 'btn-primary' : 'btn-outline' }}">Device ID</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>
            <i class="fas fa-ban" style="color: var(--danger); margin-right: 8px;"></i>
            Daftar Blokir ({{ ucfirst(request('tab', 'email')) }})
        </h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Nilai / Identifier</th>
                        <th>Alasan Pemblokiran</th>
                        <th>Dicekal Oleh</th>
                        <th>Tanggal Blokir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blacklists as $item)
                        <tr>
                            <td>
                                @if($item->type === 'email')
                                    <span class="badge badge-admin" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">Email</span>
                                @elseif($item->type === 'ip')
                                    <span class="badge badge-admin" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">IP Address</span>
                                @else
                                    <span class="badge badge-admin" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">Device ID</span>
                                @endif
                            </td>
                            <td><strong>{{ $item->value }}</strong></td>
                            <td>{{ $item->reason }}</td>
                            <td>{{ $item->blocked_by }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="{{ route('admin.blacklist.delete', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin melepas cekalan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline" style="color: var(--danger); border-color: rgba(239,68,68,0.2);">Lepas</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada daftar blokir untuk kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blacklists->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
                {{ $blacklists->appends(['tab' => $tab])->links() }}
            </div>
        @endif
    </div>
</div>

<form id="addBlacklistForm" action="{{ route('admin.blacklist.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="type" id="blacklistType">
    <input type="hidden" name="value" id="blacklistValue">
    <input type="hidden" name="reason" id="blacklistReason">
</form>

<script>
    function showAddBlockModal() {
        const currentTab = '{{ request('tab', 'email') }}';
        const typeLabel = currentTab === 'email' ? 'Email' : (currentTab === 'ip' ? 'IP Address' : 'Device ID');
        
        const value = prompt("Masukkan " + typeLabel + " yang ingin diblokir:");
        if (!value || value.trim() === '') return;
        
        const reason = prompt("Masukkan alasan pemblokiran:");
        if (reason === null) return; // cancelled
        
        document.getElementById('blacklistType').value = currentTab;
        document.getElementById('blacklistValue').value = value.trim();
        document.getElementById('blacklistReason').value = reason.trim() || 'Penyalahgunaan sistem';
        
        document.getElementById('addBlacklistForm').submit();
    }
</script>
@endsection

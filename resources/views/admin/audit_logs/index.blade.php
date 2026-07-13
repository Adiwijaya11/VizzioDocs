@extends('admin.layouts.master')

@section('title', 'Audit & Security Logs')
@section('page_title', 'Audit Logs')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>🔒 Keamanan & <span>Log Aktivitas</span></h1>
        <p>Pantau log keamanan sistem, aktivitas admin, riwayat masuk, dan rekaman lalu lintas API.</p>
    </div>
    <div class="page-header-right">
        <button class="btn btn-outline" onclick="window.location.reload()">
            <i class="fas fa-sync"></i> Refresh Log
        </button>
    </div>
</div>

<div class="chart-card" style="margin-bottom: 24px;">
    <h3><i class="fas fa-layer-group"></i> Kategori Log</h3>
    <div class="flex-row" style="display: flex; gap: 10px; margin-top: 10px;">
        <a href="?tab=audit" class="btn {{ request('tab', 'audit') === 'audit' ? 'btn-primary' : 'btn-outline' }}">Audit Log Admin</a>
        <a href="?tab=activity" class="btn {{ request('tab') === 'activity' ? 'btn-primary' : 'btn-outline' }}">Activity Log</a>
        <a href="?tab=login" class="btn {{ request('tab') === 'login' ? 'btn-primary' : 'btn-outline' }}">Login Log</a>
        <a href="?tab=api" class="btn {{ request('tab') === 'api' ? 'btn-primary' : 'btn-outline' }}">API Log</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>
            <i class="fas fa-clipboard-list" style="color: var(--accent-light); margin-right: 8px;"></i>
            Riwayat Log Terbaru ({{ ucfirst(request('tab', 'audit')) }})
        </h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    @if(request('tab', 'audit') === 'audit')
                        <tr>
                            <th>Pelaku</th>
                            <th>Tindakan</th>
                            <th>Detail Perubahan</th>
                            <th>IP Address</th>
                            <th>Tanggal & Waktu</th>
                        </tr>
                    @elseif(request('tab') === 'activity')
                        <tr>
                            <th>Pengguna</th>
                            <th>Tool/Halaman</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            <th>Tanggal & Waktu</th>
                        </tr>
                    @elseif(request('tab') === 'login')
                        <tr>
                            <th>Pengguna</th>
                            <th>Status</th>
                            <th>Device / Browser</th>
                            <th>IP Address</th>
                            <th>Tanggal & Waktu</th>
                        </tr>
                    @else
                        <tr>
                            <th>Endpoint</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Latency</th>
                            <th>Waktu</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if(request('tab', 'audit') === 'audit')
                        <tr>
                            <td><strong>Admin A</strong></td>
                            <td><span class="badge badge-admin" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">Hapus User</span></td>
                            <td>Menghapus akun spammer123@trashmail.com</td>
                            <td>192.168.1.10</td>
                            <td>02 Jul 2026 16:15</td>
                        </tr>
                        <tr>
                            <td><strong>Admin B</strong></td>
                            <td><span class="badge badge-admin" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">Buat Kupon</span></td>
                            <td>Membuat kupon diskon baru "VIZZIOPREMIUM" (diskon 50%)</td>
                            <td>192.168.1.11</td>
                            <td>02 Jul 2026 15:42</td>
                        </tr>
                        <tr>
                            <td><strong>Admin C</strong></td>
                            <td><span class="badge badge-admin" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">Ubah Setting</span></td>
                            <td>Mengubah batas upload file maksimal menjadi 100MB</td>
                            <td>192.168.1.12</td>
                            <td>02 Jul 2026 14:02</td>
                        </tr>
                    @elseif(request('tab') === 'activity')
                        <tr>
                            <td><strong>Budi Santoso</strong></td>
                            <td>Compress PDF</td>
                            <td>Mengompres file "laporan_keuangan.pdf" (15MB -> 4MB)</td>
                            <td><span class="badge badge-user">Sukses</span></td>
                            <td>02 Jul 2026 16:30</td>
                        </tr>
                        <tr>
                            <td><strong>Guest (36.72.198.85)</strong></td>
                            <td>Merge PDF</td>
                            <td>Menggabungkan 3 file PDF</td>
                            <td><span class="badge badge-user">Sukses</span></td>
                            <td>02 Jul 2026 16:21</td>
                        </tr>
                    @elseif(request('tab') === 'login')
                        @forelse($loginHistories as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->name ?? ($log->user ? $log->user->name : 'User Tidak Dikenal') }}</strong>
                                    <div style="font-size:11px; color:var(--text-muted);">{{ $log->email }}</div>
                                </td>
                                <td>
                                    @if($log->status === 'success')
                                        <span class="badge badge-user" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">Berhasil{{ $log->reason ? ' (' . $log->reason . ')' : '' }}</span>
                                    @else
                                        <span class="badge badge-admin" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">Gagal{{ $log->reason ? ' (' . $log->reason . ')' : '' }}</span>
                                    @endif
                                </td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada riwayat login.</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td><strong>/api/progress/session_abc123</strong></td>
                            <td><span class="badge" style="background:#e2e8f0; color:var(--text-primary);">GET</span></td>
                            <td><span class="badge badge-user">200 OK</span></td>
                            <td>12ms</td>
                            <td>02 Jul 2026 16:35:10</td>
                        </tr>
                        <tr>
                            <td><strong>/pdf-crop/crop</strong></td>
                            <td><span class="badge" style="background:#818cf8; color:white;">POST</span></td>
                            <td><span class="badge badge-user">200 OK</span></td>
                            <td>120ms</td>
                            <td>02 Jul 2026 16:34:55</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if(request('tab') === 'login' && $loginHistories instanceof \Illuminate\Pagination\LengthAwarePaginator && $loginHistories->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
                {{ $loginHistories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

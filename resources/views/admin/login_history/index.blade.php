@extends('admin.layouts.master')

@section('title', 'Riwayat Login')
@section('page_title', 'Riwayat Login')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>🕒 Riwayat <span>Login Pengguna</span></h1>
        <p>Log audit menyeluruh mencatat aktivitas login pengunjung dan administrator platform.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-sign-in-alt" style="color: var(--accent-light); margin-right: 8px;"></i>Daftar Sesi Masuk Terbaru</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>IP Address</th>
                        <th>Device & Browser</th>
                        <th>Status</th>
                        <th>Waktu Login</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginHistories as $log)
                        <tr>
                            <td><strong>{{ $log->name ?? ($log->user ? $log->user->name : 'User Tidak Dikenal') }}</strong></td>
                            <td>{{ $log->email }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge badge-user">Berhasil{{ $log->reason ? ' (' . $log->reason . ')' : '' }}</span>
                                @else
                                    <span class="badge badge-admin" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">Gagal{{ $log->reason ? ' (' . $log->reason . ')' : '' }}</span>
                                @endif
                            </td>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada riwayat login.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loginHistories->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
                {{ $loginHistories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('admin.layouts.master')

@section('title', 'Session Aktif')
@section('page_title', 'Session Aktif')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>💻 Sesi <span>Pengguna Aktif</span></h1>
        <p>Lihat dan kelola token sesi (session tokens) pengguna yang sedang aktif menggunakan VizzioDocs saat ini.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; background: rgba(16, 185, 129, 0.1); color: var(--success); border-radius: 8px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-desktop" style="color: var(--success); margin-right: 8px;"></i>Daftar Sesi Aktif Realtime</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Device & Browser</th>
                        <th>Token ID</th>
                        <th>Waktu Aktivitas Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $sess)
                        <tr>
                            <td>
                                @if($sess->user_name)
                                    <strong>{{ $sess->user_name }}</strong> ({{ $sess->user_email }})
                                @else
                                    <span class="text-muted">Guest / Pengunjung</span>
                                @endif
                            </td>
                            <td>{{ $sess->ip_address }}</td>
                            <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $sess->user_agent }}">
                                {{ $sess->user_agent }}
                            </td>
                            <td><code style="background:#e2e8f0; padding:2px 6px; border-radius:4px;">{{ substr($sess->id, 0, 12) }}...</code></td>
                            <td>
                                @if($sess->id === session()->getId())
                                    <span class="badge badge-user">Sedang Aktif (Anda)</span>
                                @else
                                    {{ \Carbon\Carbon::createFromTimestamp($sess->last_activity)->diffForHumans() }}
                                @endif
                            </td>
                            <td>
                                @if($sess->id === session()->getId())
                                    <button class="btn btn-sm btn-outline" disabled>Putuskan Sesi</button>
                                @else
                                    <form action="{{ route('admin.active-sessions.terminate', $sess->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin memutus sesi pengguna ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline" style="color: var(--danger); border-color: rgba(239,68,68,0.2);">Putuskan Sesi</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada sesi aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

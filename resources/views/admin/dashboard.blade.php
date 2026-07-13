@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('content')
{{-- Stats Grid --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Dashboard <span>Admin</span></h1>
        <p>Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan platform VizzioDocs.</p>
    </div>
    <div class="page-header-right">
        <a href="{{ route('home') }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Website
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-change up">
                <i class="fas fa-arrow-up"></i> +{{ $recentUsers ?? 0 }}
            </div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-change up">
                <i class="fas fa-arrow-up"></i> Aktif
            </div>
        </div>
        <div class="stat-value">{{ $totalAdmins }}</div>
        <div class="stat-label">Total Admin</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-change {{ $totalPremium > 0 ? 'up' : 'down' }}">
                <i class="fas fa-{{ $totalPremium > 0 ? 'arrow-up' : 'minus' }}"></i> {{ $totalPremium > 0 ? $totalPremium : 0 }}
            </div>
        </div>
        <div class="stat-value">{{ $totalPremium }}</div>
        <div class="stat-label">Pengguna Premium</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stat-change up">
                <i class="fas fa-user-plus"></i> Baru
            </div>
        </div>
        <div class="stat-value">{{ $recentUsers }}</div>
        <div class="stat-label">Pendaftaran Baru</div>
    </div>
</div>

{{-- Recent Users Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color: var(--accent-light); margin-right: 8px;"></i>Pengguna Terbaru</h3>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-arrow-right"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsersList ?? [] as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user' }}"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-empty">
                                <i class="fas fa-inbox"></i>
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

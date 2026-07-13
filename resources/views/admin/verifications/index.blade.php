@extends('admin.layouts.master')

@section('title', 'Verifikasi Akun')
@section('page_title', 'Verifikasi Akun')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>✔️ Verifikasi <span>Akun Pengguna</span></h1>
        <p>Lihat status verifikasi email pengguna dan verifikasi manual yang tertunda.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-check-double" style="color: var(--success); margin-right: 8px;"></i>Status Verifikasi</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Status Email</th>
                        <th>Metode Registrasi</th>
                        <th>Tanggal Terverifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Budi Santoso</strong></td>
                        <td>budi@gmail.com</td>
                        <td><span class="badge badge-user">Terverifikasi</span></td>
                        <td>Google OAuth</td>
                        <td>02 Jul 2026 16:00</td>
                        <td>
                            <button class="btn btn-sm btn-outline" onclick="alert('Email sudah terverifikasi.')" disabled>Verifikasi</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Spammer Baru</strong></td>
                        <td>spammer@trashmail.com</td>
                        <td><span class="badge badge-free" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">Tertunda (Pending)</span></td>
                        <td>Email / Password</td>
                        <td>-</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="alert('Email berhasil diverifikasi secara manual.')">Verifikasi Manual</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

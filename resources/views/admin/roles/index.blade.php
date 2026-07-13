@extends('admin.layouts.master')

@section('title', 'Role & Permission')
@section('page_title', 'Role & Permission')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>🔑 Role & <span>Hak Akses</span></h1>
        <p>Kelola tingkatan pengguna (Admin, Premium, Free User) serta aturan perijinan (permissions).</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-shield" style="color: var(--accent-light); margin-right: 8px;"></i>Daftar Level / Role</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Role</th>
                        <th>Identifier</th>
                        <th>Jumlah Pengguna</th>
                        <th>Hak Akses Utama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Administrator</strong></td>
                        <td><span class="badge badge-admin">admin</span></td>
                        <td>1 akun</td>
                        <td>Akses penuh dashboard, lock tools, hapus pengguna, kelola sistem</td>
                        <td><span class="badge badge-user">Bawaan</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline" onclick="alert('Role sistem bawaan tidak dapat diubah.')">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Premium Member</strong></td>
                        <td><span class="badge badge-premium">premium</span></td>
                        <td>{{ \App\Models\User::where('plan', 'premium')->count() }} akun</td>
                        <td>Akses semua tools tanpa batas kuota harian, konversi format besar</td>
                        <td><span class="badge badge-user">Bawaan</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline" onclick="alert('Role sistem bawaan tidak dapat diubah.')">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Free Member</strong></td>
                        <td><span class="badge badge-free">free</span></td>
                        <td>{{ \App\Models\User::where('plan', 'free')->count() }} akun</td>
                        <td>Akses tools standar dengan limitasi kuota harian 5x konversi</td>
                        <td><span class="badge badge-user">Bawaan</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline" onclick="alert('Role sistem bawaan tidak dapat diubah.')">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

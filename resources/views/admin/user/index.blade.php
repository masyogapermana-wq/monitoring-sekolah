@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">👨‍🏫 Kelola Data Pengguna (Guru)</h3>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
        + Tambah Akun Baru
    </button>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Email / Username</th>
                        <th>Hak Akses (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-primary">Admin TU</span>
                            @elseif($user->role == 'piket')
                                <span class="badge bg-warning text-dark">Guru Piket</span>
                            @else
                                <span class="badge bg-info text-dark">Guru BK</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Akun Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso, S.Pd">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Untuk Login)</label>
                        <input type="email" name="email" class="form-control" required placeholder="Contoh: guru@smk.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="piket">Guru Piket</option>
                            <option value="bk">Guru Bimbingan Konseling (BK)</option>
                            <option value="admin">Administrator (TU)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

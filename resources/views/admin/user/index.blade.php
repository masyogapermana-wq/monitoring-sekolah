@extends('layouts.main')

@section('title', 'Kelola Data Pengguna')

@section('content')
    <div class="container-fluid">

        <!-- 1. ALERT PESAN SUKSES (Jika ada) -->
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4 border-0"
                style="background-color: rgba(46, 213, 115, 0.1); color: #2ed573; border-left: 4px solid #2ed573 !important;">
                <i class="fas fa-check-circle fs-4 me-3"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <!-- 2. JUDUL HALAMAN -->
        <h4 class="fw-bold mb-4 text-white">
            <i class="fas fa-chalkboard-teacher me-2" style="color: #00d2ff;"></i> Kelola Data Pengguna (Guru)
        </h4>

        <!-- 3. TOMBOL TAMBAH DATA -->
        <div class="mb-4">
            <!-- Tombol ini memicu Modal (Pop-up Form) di bawah -->
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modalTambahGuru"
                style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); color: white; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);">
                <i class="fas fa-plus me-1"></i> Tambah Akun Baru
            </button>
        </div>

        <!-- 4. KOTAK TABEL PREMIUM -->
        <div class="card p-0"
            style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">No</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Lengkap
                            </th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Email / Username
                            </th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Hak Akses (Role)
                            </th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- Menggunakan forelse agar jika database kosong, pesan khusus akan muncul -->
                    @forelse ($users as $no => $user)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td class="px-4 py-3 align-middle">{{ $no + 1 }}</td>

                        <!-- Memanggil nama guru dari database -->
                        <td class="px-4 py-3 align-middle fw-bold text-white">{{ $user->name }}</td>

                        <!-- Memanggil email dari database -->
                        <td class="px-4 py-3 align-middle text-info">{{ $user->email }}</td>

                        <td class="px-4 py-3 align-middle">
                            <!-- Logika untuk membedakan warna label berdasarkan hak akses -->
                            @if($user->role == 'piket')
                                <span class="badge" style="background-color: rgba(255, 171, 0, 0.2); color: #ffab00; border: 1px solid rgba(255,171,0,0.5);">Guru Piket</span>
                            @elseif($user->role == 'bk')
                                <span class="badge" style="background-color: rgba(0, 210, 255, 0.2); color: #00d2ff; border: 1px solid rgba(0,210,255,0.5);">Guru BK</span>
                            @elseif($user->role == 'admin')
                                <span class="badge" style="background-color: rgba(46, 213, 115, 0.2); color: #2ed573; border: 1px solid rgba(46,213,115,0.5);">Administrator</span>
                            @else
                                <span class="badge bg-secondary">Pengguna</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 align-middle">
                            <!-- Tombol Hapus dengan ID dinamis -->
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun guru ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Hapus Pengguna"><i class="fas fa-trash me-1"></i>Hapus</button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <!-- Tampilan jika belum ada satupun data guru di database -->
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-2 text-secondary"></i><br>
                            Belum ada data guru yang terdaftar di sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM TAMBAH GURU -->
    <!-- ============================================== -->
    <div class="modal fade" id="modalTambahGuru" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background-color: #1e293b; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white"><i class="fas fa-user-shield me-2 text-info"></i>Tambah Akun
                        Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Pastikan route 'user.store' sesuai dengan nama route di web.php lu -->
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Nama Lengkap Guru <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required placeholder="Contoh: Budi Santoso, S.Pd">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Email / Username <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required placeholder="Contoh: guru@smk.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Password Default <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required placeholder="Masukkan password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Hak Akses (Role) <span
                                    class="text-danger">*</span></label>
                            <select name="role" class="form-select"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required>
                                <option value="" selected disabled>-- Pilih Hak Akses --</option>
                                <option value="piket" style="color: white;">Guru Piket</option>
                                <option value="bk" style="color: white;">Guru BK</option>
                                <option value="admin" style="color: white;">Administrator</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                            style="background-color: rgba(255,255,255,0.1); border: none;">Batal</button>
                        <button type="submit" class="btn px-4 text-white fw-bold"
                            style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); border: none;">Simpan
                            Akun</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

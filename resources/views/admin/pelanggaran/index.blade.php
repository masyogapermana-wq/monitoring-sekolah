@extends('layouts.main')

@section('title', 'Jenis Pelanggaran')

<!-- Tambahan CSS khusus untuk mencerahkan teks placeholder -->
<style>
    .input-premium::placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }
</style>

@section('content')

<div class="container-fluid">

    <!-- 1. ALERT PESAN SUKSES (Jika ada notifikasi simpan/hapus) -->
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-4 border-0" style="background-color: rgba(46, 213, 115, 0.1); color: #2ed573; border-left: 4px solid #2ed573 !important;">
        <i class="fas fa-check-circle fs-4 me-3"></i>
        <div class="fw-bold">{{ session('success') }}</div>
    </div>
    @endif

    <!-- 2. JUDUL HALAMAN -->
    <h4 class="fw-bold mb-4 text-white">
        <i class="fas fa-exclamation-triangle me-2 text-warning"></i> Kelola Jenis Pelanggaran
    </h4>

    <!-- 3. KOTAK FORM TAMBAH PELANGGARAN -->
    <div class="card p-4 mb-4" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <!-- Sesuaikan route-nya jika nama route-mu berbeda -->
        <form action="{{ route('pelanggaran.store') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label text-secondary small fw-bold">Nama Pelanggaran <span class="text-danger">*</span></label>
                    <!-- Tambahan class input-premium diterapkan di sini -->
                    <input type="text" name="nama_pelanggaran" class="form-control p-3 input-premium" style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);" placeholder="Contoh: Terlambat Masuk Sekolah" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn w-100 text-white fw-bold p-3" style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); border: none; box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);">
                        <i class="fas fa-plus me-1"></i> Tambah Aturan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 4. KOTAK TABEL DAFTAR PELANGGARAN -->
    <div class="card p-0" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 10%;">No</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 75%;">Nama Pelanggaran</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Memulai perulangan data dari database -->
                    @forelse($pelanggarans as $no => $item)

                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <!-- Menampilkan nomor urut otomatis dengan warna yang dicerahkan -->
                        <td class="px-4 py-3 align-middle" style="color: #cbd5e1; font-weight: 500;">{{ $no + 1 }}</td>

                        <!-- Menampilkan nama pelanggaran dari database -->
                        <td class="px-4 py-3 align-middle fw-bold text-white">{{ $item->nama_pelanggaran }}</td>

                        <td class="px-4 py-3 align-middle">
                            <!-- Tombol hapus yang dinamis berdasarkan ID pelanggaran -->
                            <form action="{{ route('pelanggaran.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah lu yakin ingin menghapus jenis pelanggaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Hapus Pelanggaran"><i class="fas fa-trash me-1"></i>Hapus</button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <!-- Tampilan ini hanya akan muncul jika database pelanggaran masih kosong -->
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 text-secondary"></i><br>
                            Belum ada aturan pelanggaran yang ditambahkan ke dalam sistem.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

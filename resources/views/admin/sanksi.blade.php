@extends('layouts.main')
@section('title', 'Kelola Sanksi Edukatif')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman disamakan gayanya dengan halaman sebelah -->
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-balance-scale text-warning fs-4 me-2"></i>
        <h4 class="mb-0 text-white fw-bold">Kelola Sanksi Edukatif</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- BAGIAN FORM TAMBAH SANKSI -->
    <div class="card mb-4" style="background-color: #1e293b; border: none; border-radius: 8px;">
        <div class="card-body p-4">
            <label class="small fw-bold mb-3" style="color: #94a3b8;">Nama Sanksi / Hukuman <span class="text-danger">*</span></label>

            <form action="{{ route('sanksi.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-9">
                        <input type="text" name="nama_sanksi" class="form-control p-3 text-white"
                            style="background-color: #0f172a; border: 1px solid #334155; border-radius: 6px;"
                            placeholder="Contoh: Membersihkan Halaman Sekolah" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn w-100 p-3 text-white fw-bold"
                            style="background-color: #00bfff; border-radius: 6px; border: none;">
                            <i class="fas fa-plus me-1"></i> Tambah Sanksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BAGIAN TABEL DAFTAR SANKSI -->
    <div class="card" style="background-color: #1e293b; border: none; border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-hover mb-0 align-middle">
                    <thead style="border-bottom: 1px solid #334155;">
                        <tr>
                            <!-- Menambahkan background transparan agar mengikuti warna card gelap -->
                            <th class="p-4" style="color: #94a3b8; width: 10%; background-color: transparent;">No</th>
                            <th class="p-4" style="color: #94a3b8; width: 70%; background-color: transparent;">Nama Sanksi Edukatif</th>
                            <th class="p-4 text-start" style="color: #94a3b8; width: 20%; background-color: transparent;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanksis as $index => $sanksi)
                        <tr style="border-bottom: 1px solid #334155;">
                            <td class="p-4 fw-bold" style="color: white; background-color: transparent;">{{ $index + 1 }}</td>
                            <td class="p-4 fw-bold" style="color: white; background-color: transparent;">{{ $sanksi->nama_sanksi }}</td>
                            <td class="p-4" style="background-color: transparent;">
                                <form action="{{ route('sanksi.destroy', $sanksi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <!-- Menyesuaikan tombol Hapus agar persis seperti halaman Jenis Pelanggaran -->
                                    <button type="submit" class="btn btn-sm px-4 py-1 rounded-pill"
                                        style="border: 1px solid #ef4444; color: #ef4444; background: transparent;"
                                        onclick="return confirm('Yakin ingin menghapus sanksi ini?')">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center p-5" style="color: #94a3b8; background-color: transparent;">Belum ada data sanksi edukatif yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

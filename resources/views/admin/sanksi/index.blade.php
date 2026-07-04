@extends('layouts.main')

@section('title', 'Kelola Sanksi Edukatif')

@section('content')
<div class="container-fluid">
    <div class="mb-4 d-flex align-items-center">
        <i class="fas fa-gavel fa-2x me-3 text-warning"></i>
        <h3 class="fw-bold text-white mb-0">Kelola Sanksi Edukatif</h3>
    </div>

    <!-- Form Tambah Sanksi -->
    <div class="card p-4 mb-4" style="background-color: #1e293b; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px;">
        <form action="{{ route('sanksi.store') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-9">
                <label class="form-label text-secondary small fw-bold">Nama Sanksi Edukatif <span class="text-danger">*</span></label>
                <input type="text" name="nama_sanksi" class="form-control" style="background-color: #0f172a; border: 1px solid #334155; color: white;" placeholder="Contoh: Membersihkan Halaman Sekolah" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn w-100 text-white fw-bold" style="background: #00d2ff; padding: 0.6rem;">
                    <i class="fas fa-plus me-1"></i> Tambah Aturan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Sanksi -->
    <div class="card p-0" style="background-color: #1e293b; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 10%;">No</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Sanksi Edukatif</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sanksis as $no => $item)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td class="px-4 py-3 align-middle">{{ $no + 1 }}</td>
                            <td class="px-4 py-3 align-middle fw-bold text-light">{{ $item->nama_sanksi }}</td>
                            <td class="px-4 py-3 align-middle">
                                <form action="{{ route('sanksi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sanksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-secondary">Belum ada data sanksi edukatif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

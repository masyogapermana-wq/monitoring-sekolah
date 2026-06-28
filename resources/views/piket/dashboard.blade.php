@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Selamat Datang, Guru Piket 👋</h2>
            <p class="text-muted">Pilih menu di bawah ini untuk mengelola presensi kedatangan dan pelanggaran tata tertib siswa hari ini.</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-5">
                    <div class="display-3 text-primary mb-3">📷</div>
                    <h4 class="fw-bold">Scan QR Presensi</h4>
                    <p class="text-muted">Catat kehadiran otomatis menggunakan kamera web / barcode scanner.</p>
                    <a href="{{ route('piket.scan') }}" class="btn btn-primary mt-3 btn-lg px-5 fw-bold shadow-sm">Buka Scanner</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-5">
                    <div class="display-3 text-success mb-3">⌨️</div>
                    <h4 class="fw-bold">Presensi Manual</h4>
                    <p class="text-muted">Catat kehadiran siswa yang lupa membawa kartu dengan mengetik NIS.</p>
                    <a href="{{ route('piket.manual') }}" class="btn btn-success mt-3 btn-lg px-5 fw-bold shadow-sm">Input Manual</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pt-4 pb-3 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <h5 class="fw-bold mb-3 mb-md-0">📝 Riwayat Presensi Hari Ini</h5>

                    <form action="/piket/dashboard" method="GET" class="d-flex align-items-center">
                        <label class="me-2 fw-bold small text-muted">Filter Kelas:</label>
                        <select name="kelas" class="form-select form-select-sm border-primary" onchange="this.form.submit()" style="width: 150px;">
                            <option value="semua" {{ $kelasPilihan == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                            @foreach($daftarKelas as $kelas)
                                <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered table-striped align-middle" style="min-width: 600px;">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIS</th>
                                <th>Nama Siswa</th>
                                <th width="15%">Jam Masuk</th>
                                <th width="15%">Status</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensis as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->siswa->nis ?? '-' }}</td>
                                <td class="fw-bold">{{ $item->siswa->nama_siswa ?? 'Data Terhapus' }}</td>
                                <td>{{ $item->jam_masuk }}</td>
                                <td>
                                    @if($item->status == 'Terlambat')
                                        <span class="badge bg-warning text-dark">Terlambat</span>
                                    @elseif($item->status == 'Sakit' || $item->status == 'Izin')
                                        <span class="badge bg-info text-dark">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-success">Hadir</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#editAbsen-{{ $item->id }}">
                                        ✏️ Edit
                                    </button>

                                    <div class="modal fade text-start" id="editAbsen-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('piket.update-presensi', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title fw-bold">Update Kehadiran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Siswa: <strong>{{ $item->siswa->nama_siswa ?? '-' }}</strong></p>
                                                        <p class="mb-3 text-muted small">Jam Masuk Awal: {{ $item->jam_masuk }}</p>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Ubah Status</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="Hadir" {{ $item->status == 'Hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                                                <option value="Terlambat" {{ $item->status == 'Terlambat' ? 'selected' : '' }}>⏰ Terlambat</option>
                                                                <option value="Sakit" {{ $item->status == 'Sakit' ? 'selected' : '' }}>🤒 Sakit (Pulang Awal)</option>
                                                                <option value="Izin" {{ $item->status == 'Izin' ? 'selected' : '' }}>💌 Izin (Pulang Awal)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    📭 Belum ada data presensi hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

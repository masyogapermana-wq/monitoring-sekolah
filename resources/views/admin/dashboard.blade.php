@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid px-0">

        <!-- BARIS 1: KARTU STATISTIK (SUMMARY CARDS) -->
        <div class="row g-3 mb-4">
            <!-- Kartu Total Siswa -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100"
                    style="background-color: #1e293b; border-left: 5px solid #00d2ff !important;">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-uppercase text-secondary fw-bold mb-2"
                                style="font-size: 0.75rem; letter-spacing: 1px;">Total Siswa</h6>
                            <!-- Menampilkan variabel totalSiswa -->
                            <h3 class="fw-bold text-white mb-0">{{ $totalSiswa }} <span
                                    class="fs-6 fw-normal text-secondary">Siswa</span></h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px; background-color: rgba(0, 210, 255, 0.1);">
                            <i class="fas fa-users fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Total Guru -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100"
                    style="background-color: #1e293b; border-left: 5px solid #a855f7 !important;">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-uppercase text-secondary fw-bold mb-2"
                                style="font-size: 0.75rem; letter-spacing: 1px;">Total Guru</h6>
                            <!-- Menampilkan variabel totalGuru -->
                            <h3 class="fw-bold text-white mb-0">{{ $totalGuru }} <span
                                    class="fs-6 fw-normal text-secondary">Guru</span></h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px; background-color: rgba(168, 85, 247, 0.1);">
                            <i class="fas fa-chalkboard-teacher fs-4" style="color: #a855f7;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Terlambat Hari Ini -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100"
                    style="background-color: #1e293b; border-left: 5px solid #f59e0b !important;">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-uppercase text-secondary fw-bold mb-2"
                                style="font-size: 0.75rem; letter-spacing: 1px;">Terlambat Hari Ini</h6>
                            <!-- Menampilkan variabel terlambatHariIni -->
                            <h3 class="fw-bold text-white mb-0">{{ $terlambatHariIni }} <span
                                    class="fs-6 fw-normal text-secondary">Siswa</span></h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px; background-color: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-clock fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Pelanggaran Hari Ini -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100"
                    style="background-color: #1e293b; border-left: 5px solid #ef4444 !important;">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-uppercase text-secondary fw-bold mb-2"
                                style="font-size: 0.75rem; letter-spacing: 1px;">Pelanggaran Baru</h6>
                            <!-- Menampilkan variabel pelanggaranBaru -->
                            <h3 class="fw-bold text-white mb-0">{{ $pelanggaranBaru }} <span
                                    class="fs-6 fw-normal text-secondary">Kasus</span></h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px; background-color: rgba(239, 68, 68, 0.1);">
                            <i class="fas fa-exclamation-triangle fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS 2: TABEL AKTIVITAS TERBARU -->
        <div class="row g-4">
            <!-- Tabel Presensi Terakhir -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4" style="background-color: #1e293b;">
                    <div
                        class="card-header bg-transparent border-bottom border-secondary p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-white fw-bold"><i class="fas fa-list-ul me-2 text-info"></i> Log Presensi
                            Terakhir</h6>
                        <!-- Mengarahkan ke halaman laporan presensi milik BK -->
                        <a href="{{ route('bk.laporan-presensi') }}" class="text-info text-decoration-none small">Lihat
                            Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless table-hover mb-0 text-white align-middle"
                                style="background-color: transparent;">
                                <thead class="text-secondary small text-uppercase"
                                    style="background-color: rgba(0,0,0,0.2);">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Siswa</th>
                                        <th class="py-3">Waktu Scan</th>
                                        <th class="py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Melakukan perulangan data nyata dari database -->
                                    @forelse($logPresensi as $absen)
                                        <tr>
                                            <td class="ps-4 py-3">{{ $absen->siswa->nama_siswa ?? 'Unknown' }} <br><small
                                                    class="text-secondary">{{ $absen->siswa->kelas ?? '-' }}</small></td>
                                            <td class="py-3">{{ $absen->jam_masuk }}</td>
                                            <td class="py-3">
                                                @if ($absen->status == 'Tepat Waktu' || $absen->status == 'Hadir')
                                                    <span
                                                        class="badge bg-success rounded-pill px-3">{{ $absen->status }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-warning text-dark rounded-pill px-3">{{ $absen->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-secondary">Belum ada siswa yang
                                                absen hari ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pelanggaran Terbaru -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4" style="background-color: #1e293b;">
                    <div
                        class="card-header bg-transparent border-bottom border-secondary p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-white fw-bold"><i class="fas fa-clipboard-list me-2 text-danger"></i>
                            Pelanggaran Terbaru</h6>
                        <!-- Mengarahkan ke halaman laporan pelanggaran milik BK -->
                        <a href="{{ route('bk.laporan') }}" class="text-danger text-decoration-none small">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless table-hover mb-0 text-white align-middle"
                                style="background-color: transparent;">
                                <thead class="text-secondary small text-uppercase"
                                    style="background-color: rgba(0,0,0,0.2);">
                                    <tr>
                                        <th class="ps-4 py-3">Siswa</th>
                                        <th class="py-3">Jenis Pelanggaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Melakukan perulangan data nyata dari database -->
                                    @forelse($logPelanggaran as $kasus)
                                        <tr>
                                            <td class="ps-4 py-3">{{ $kasus->siswa->nama_siswa ?? 'Unknown' }} <br><small
                                                    class="text-secondary">{{ $kasus->siswa->kelas ?? '-' }}</small></td>
                                            <td class="py-3 text-truncate" style="max-width: 150px;">
                                                {{ $kasus->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <!-- Mengubah colspan menjadi 2 karena sisa 2 kolom -->
                                            <td colspan="2" class="text-center py-4 text-secondary">Tidak ada pelanggaran
                                                tercatat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

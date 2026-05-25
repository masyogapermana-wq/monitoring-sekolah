@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold mb-4">📊 Dashboard Guru BK</h3>

        <div class="row mb-4">
            <!-- Kartu Siswa Hadir -->
            <div class="col-md-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">Hadir Hari Ini</h5>
                        <h2 class="fw-bold">{{ $hadirHariIni }} Siswa</h2>
                    </div>
                </div>
            </div>

            <!-- Kartu Siswa Terlambat -->
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <h5 class="card-title">Terlambat Hari Ini</h5>
                        <h2 class="fw-bold">{{ $terlambatHariIni }} Siswa</h2>
                    </div>
                </div>
            </div>

            <!-- Kartu Pintasan Cetak Laporan -->
            <div class="col-md-4 mb-3">
    <div class="card bg-primary text-white shadow h-100 border-0">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
            <h5 class="card-title mb-3 fw-bold">Laporan Evaluasi</h5>
            <div class="dropdown">
                <button class="btn btn-light text-primary fw-bold dropdown-toggle" type="button"
                    id="dropdownEvaluasi" data-bs-toggle="dropdown" aria-expanded="false">
                    🖨️ Cetak PDF / Excel
                </button>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropdownEvaluasi">
                    <li>
                        <a class="dropdown-item fw-bold text-success py-2"
                            href="{{ url('/bk/laporan-presensi') }}">
                            📊 Buka Laporan Presensi
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item fw-bold text-danger py-2"
                            href="{{ url('/bk/laporan-pelanggaran') }}">
                            ⚠️ Buka Laporan Pelanggaran
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

            <!-- Tabel 5 Siswa Bermasalah -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    ⚠️ Riwayat Pelanggaran Terbaru
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaBermasalah as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-M-Y') }}</td>
                                    <td>{{ $item->siswa->nis ?? '-' }}</td>
                                    <td class="fw-bold">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                    <td>{{ $item->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                    <td>{{ $item->sanksi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data pelanggaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

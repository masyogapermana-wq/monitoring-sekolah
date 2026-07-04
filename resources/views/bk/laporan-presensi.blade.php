@extends('layouts.main')

@section('title', 'Laporan Presensi Siswa')

@section('content')
    <div class="container-fluid">

        <!-- HEADER & TOMBOL CETAK -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold text-white mb-1">
                    <i class="fas fa-calendar-check me-2 text-primary"></i> Laporan Presensi Siswa
                </h3>
                <p class="text-secondary small mb-0">Pantau kehadiran dan filter data presensi siswa.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <!-- Form untuk cetak PDF berdasarkan tanggal yang sedang difilter -->
                <!-- Form untuk cetak PDF berdasarkan tanggal yang sedang difilter -->
                <form action="{{ route('bk.cetak-pdf') }}" method="GET" target="_blank">
                    <!-- Mengirim parameter tanggal secara tersembunyi -->
                    <input type="hidden" name="tanggal" value="{{ $tanggalInput ?? request('tanggal') }}">

                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="...">
                        <i class="fas fa-print me-2"></i> Cetak Laporan (PDF)
                    </button>
                </form>
            </div>
        </div>

        <!-- KARTU FILTER -->
        <div class="card p-4 mb-4 border-0"
            style="background-color: #1e293b; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <h6 class="text-white fw-bold mb-3">
                <i class="fas fa-search me-2 text-info"></i> Filter Laporan Presensi
            </h6>

            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">

                <!-- Filter Rentang Waktu -->
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Rentang Waktu</label>
                    <select name="filter" class="form-select"
                        style="background-color: #0f172a; border: 1px solid #334155; color: white;"
                        onchange="this.form.submit()">
                        <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>📅 Harian</option>
                        <option value="mingguan" {{ $filter == 'mingguan' ? 'selected' : '' }}>📅 Mingguan</option>
                        <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>📅 Bulanan</option>
                    </select>
                </div>

                <!-- Filter Pilih Tanggal -->
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggalInput }}" class="form-control"
                        style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                </div>

                <!-- Filter Pilih Kelas -->
                <div class="col-md-4">
                    <label class="form-label text-secondary small fw-bold">Pilih Kelas</label>
                    <select name="kelas" class="form-select"
                        style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        <option value="semua">-- Semua Kelas --</option>
                        @foreach ($daftarKelas as $kelas)
                            <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Terapkan -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info w-100 fw-bold text-white shadow-sm"
                        style="border-radius: 8px;">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>

            </form>

            <!-- Info Tanggal -->
            <div class="mt-4 text-secondary small border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
                Menampilkan data dari:
                <span class="text-info fw-bold">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</span>
                s/d
                <span class="text-info fw-bold">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <!-- KARTU TABEL DATA -->
        <div class="card p-0 border-0"
            style="background-color: #1e293b; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 5%;">No
                            </th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">NIS</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Siswa</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Kelas</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Jam Masuk</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            @php
                                // Mengambil data presensi dari relasi yang sudah kita buat di Controller
                                $presensi = $siswa->presensi->first();
                            @endphp
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td class="px-4 py-3 align-middle text-secondary">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 align-middle text-light">{{ $siswa->nis }}</td>
                                <td class="px-4 py-3 align-middle text-light fw-bold">{{ $siswa->nama_siswa }}</td>
                                <td class="px-4 py-3 align-middle text-light">{{ $siswa->kelas }}</td>

                                <!-- Kolom Jam Masuk -->
                                <td class="px-4 py-3 align-middle text-light">
                                    {{ $presensi && $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') . ' WIB' : '-' }}
                                </td>

                                <!-- Kolom Status Pakai Badge -->
                                <td class="px-4 py-3 align-middle">
                                    @if ($presensi)
                                        @if ($presensi->status == 'Hadir')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Hadir</span>
                                        @elseif($presensi->status == 'Terlambat')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Terlambat</span>
                                        @elseif($presensi->status == 'Izin')
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Izin</span>
                                        @elseif($presensi->status == 'Sakit')
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Sakit</span>
                                        @else
                                            <span
                                                class="badge bg-secondary px-3 py-2 rounded-pill">{{ $presensi->status }}</span>
                                        @endif
                                    @else
                                        <!-- Jika tidak ada data presensi, otomatis Alpa -->
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">Alpa</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-2x mb-3 text-secondary" style="opacity: 0.5;"></i><br>
                                    Tidak ada data siswa yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

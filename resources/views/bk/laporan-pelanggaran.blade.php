@extends('layouts.main')

@section('title', 'Laporan Pelanggaran Siswa')

@section('content')
<div class="container-fluid">

    <!-- HEADER & TOMBOL CETAK -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1">
                <i class="fas fa-file-alt me-2 text-primary"></i> Laporan Pelanggaran Siswa
            </h3>
            <p class="text-secondary small mb-0">Kelola dan filter data riwayat pelanggaran tata tertib.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <!-- Form untuk cetak PDF yang terhubung dengan filter -->
            <form action="{{ route('bk.cetak-pelanggaran-pdf') }}" method="GET" target="_blank">
                <!-- Data filter dikirim secara tersembunyi agar hasil cetak sesuai dengan tampilan -->
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="hidden" name="tanggal" value="{{ $tanggalInput }}">
                <input type="hidden" name="bulan" value="{{ $bulanInput }}">
                <input type="hidden" name="kelas" value="{{ $kelasPilihan }}">

                <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #3742fa 0%, #5352ed 100%); border: none;">
                    <i class="fas fa-print me-2"></i> Cetak Laporan (PDF)
                </button>
            </form>
        </div>
    </div>

    <!-- KARTU FILTER -->
    <div class="card p-4 mb-4 border-0" style="background-color: #1e293b; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h6 class="text-white fw-bold mb-3">
            <i class="fas fa-search me-2 text-info"></i> Filter Rentang Laporan
        </h6>

        <!-- Form Filter Utama -->
        <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">

            <!-- Filter Rentang Waktu -->
            <div class="col-md-3">
                <label class="form-label text-secondary small fw-bold">Rentang Waktu</label>
                <select name="filter" class="form-select" style="background-color: #0f172a; border: 1px solid #334155; color: white;" onchange="this.form.submit()">
                    <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>📅 Harian</option>
                    <option value="mingguan" {{ $filter == 'mingguan' ? 'selected' : '' }}>📅 Mingguan</option>
                    <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>📅 Bulanan</option>
                </select>
            </div>

            <!-- Filter Pilih Tanggal (Harian/Mingguan) -->
            <div class="col-md-3">
                <label class="form-label text-secondary small fw-bold">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggalInput }}" class="form-control" style="background-color: #0f172a; border: 1px solid #334155; color: white;">
            </div>

            <!-- Filter Pilih Kelas -->
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold">Pilih Kelas</label>
                <select name="kelas" class="form-select" style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                    <option value="semua">-- Semua Kelas --</option>
                    @foreach($daftarKelas as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Terapkan -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-info w-100 fw-bold text-white shadow-sm" style="border-radius: 8px;">
                    <i class="fas fa-filter me-1"></i> Terapkan
                </button>
            </div>

        </form>

        <!-- Informasi Tanggal Aktif -->
        <div class="mt-4 text-secondary small border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
            Menampilkan data dari:
            <span class="text-info fw-bold">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</span>
            s/d
            <span class="text-info fw-bold">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <!-- KARTU TABEL DATA -->
    <div class="card p-0 border-0" style="background-color: #1e293b; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2); width: 5%;">No</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Tanggal</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">NIS</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Siswa</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Kelas</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Jenis Pelanggaran</th>
                        <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Sanksi Edukatif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggarans as $index => $pelanggaran)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td class="px-4 py-3 align-middle text-secondary">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ \Carbon\Carbon::parse($pelanggaran->tanggal_kejadian)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ $pelanggaran->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-3 align-middle text-light fw-bold">{{ $pelanggaran->siswa->nama_siswa ?? '-' }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ $pelanggaran->siswa->kelas ?? '-' }}</td>
                            <td class="px-4 py-3 align-middle text-warning">{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ $pelanggaran->sanksi }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-3 text-secondary" style="opacity: 0.5;"></i><br>
                                Tidak ada data pelanggaran yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

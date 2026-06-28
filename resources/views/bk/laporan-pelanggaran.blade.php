@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h3 class="fw-bold m-0">📑 Laporan Pelanggaran Siswa</h3>
            <a href="{{ route('bk.cetak', ['filter' => $filter, 'tanggal' => $tanggalInput, 'bulan' => $bulanInput]) }}" target="_blank" class="btn btn-primary fw-bold shadow-sm">
                🖨️ Cetak Laporan (PDF)
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">🔍 Filter Rentang Laporan</h5>
                <form action="{{ route('bk.laporan') }}" method="GET" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Tipe Filter</label>
                        <select name="filter" id="filterType" class="form-select" onchange="toggleFilterInput()">
                            <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>📅 Harian</option>
                            <option value="mingguan" {{ $filter == 'mingguan' ? 'selected' : '' }}>🗓️ Mingguan (Per 7 Hari)</option>
                            <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>📊 Bulanan</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="inputTanggalGroup">
                        <label class="form-label small fw-bold text-muted">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggalInput }}">
                        <span id="infoMinggu" class="text-muted small d-none">Sistem otomatis mengambil 1 minggu dari tanggal ini.</span>
                    </div>

                    <div class="col-md-4 d-none" id="inputBulanGroup">
                        <label class="form-label small fw-bold text-muted">Pilih Bulan & Tahun</label>
                        <input type="month" name="bulan" class="form-control" value="{{ $bulanInput }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">💾 Terapkan</button>
                    </div>
                </form>

                <div class="mt-3 text-muted small border-top pt-2 mt-3">
                    Menampilkan data dari: <strong class="text-primary">{{ $startDate->translatedFormat('d F Y') }}</strong> s/d <strong class="text-primary">{{ $endDate->translatedFormat('d F Y') }}</strong>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jurusan</th>
                            <th>Kelas</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Sanksi Edukatif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggarans as $no => $item)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-M-Y') }}</td>
                                <td>{{ $item->siswa->nis ?? '-' }}</td>
                                <td>{{ $item->siswa->nama_siswa ?? 'Data Terhapus' }}</td>
                                <td>{{ $item->siswa->jurusan ?? '-' }}</td>
                                <td>{{ $item->siswa->kelas ?? '-' }}</td>
                                <td>{{ $item->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                <td>{{ $item->sanksi ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted fw-bold py-4">📭 Tidak ada data pelanggaran di rentang waktu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleFilterInput() {
            const filterType = document.getElementById('filterType').value;
            const tanggalGroup = document.getElementById('inputTanggalGroup');
            const bulanGroup = document.getElementById('inputBulanGroup');
            const infoMinggu = document.getElementById('infoMinggu');

            if (filterType === 'harian') {
                tanggalGroup.classList.remove('d-none');
                bulanGroup.classList.add('d-none');
                infoMinggu.classList.add('d-none');
            } else if (filterType === 'mingguan') {
                tanggalGroup.classList.remove('d-none');
                bulanGroup.classList.add('d-none');
                infoMinggu.classList.remove('d-none');
            } else if (filterType === 'bulanan') {
                tanggalGroup.classList.add('d-none');
                bulanGroup.classList.remove('d-none');
                infoMinggu.classList.add('d-none');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            toggleFilterInput();
        });
    </script>
@endsection

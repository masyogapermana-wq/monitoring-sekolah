@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h3 class="fw-bold m-0">📑 Laporan Pelanggaran Siswa</h3>
            <a href="{{ route('bk.cetak', ['filter' => $filter, 'tanggal' => $tanggalInput, 'bulan' => $bulanInput, 'kelas' => $kelasPilihan]) }}" target="_blank" class="btn btn-primary fw-bold shadow-sm">
                🖨️ Cetak Laporan (PDF)
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">🔍 Filter Rentang Laporan</h5>

                <form action="{{ route('bk.laporan') }}" method="GET" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Rentang Waktu</label>
                        <select name="filter" id="filterType" class="form-select" onchange="toggleFilterInput()">
                            <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>📅 Harian</option>
                            <option value="mingguan" {{ $filter == 'mingguan' ? 'selected' : '' }}>🗓️ Mingguan (Per 7 Hari)</option>
                            <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>📊 Bulanan</option>
                        </select>
                    </div>

                    <div class="col-md-3" id="inputTanggalGroup">
                        <label class="form-label small fw-bold text-muted">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggalInput }}">
                    </div>

                    <div class="col-md-3 d-none" id="inputBulanGroup">
                        <label class="form-label small fw-bold text-muted">Pilih Bulan & Tahun</label>
                        <input type="month" name="bulan" class="form-control" value="{{ $bulanInput }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Pilih Kelas</label>
                        <select name="kelas" class="form-select border-primary">
                            <option value="semua" {{ $kelasPilihan == 'semua' ? 'selected' : '' }}>-- Semua Kelas --</option>
                            @foreach($daftarKelas as $kelas)
                                <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">💾 Terapkan</button>
                    </div>
                </form>

                <div class="mt-3 text-muted small border-top pt-2 mt-3">
                    Menampilkan data dari: <strong class="text-primary">{{ $startDate->translatedFormat('d F Y') }}</strong> s/d <strong class="text-primary">{{ $endDate->translatedFormat('d F Y') }}</strong>
                    @if($kelasPilihan != 'semua')
                        <span class="mx-2">|</span> Kelas: <strong class="text-primary">{{ $kelasPilihan }}</strong>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body overflow-auto">
                <table class="table table-bordered table-striped align-middle" style="min-width: 800px;">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
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
                                <td class="text-center">{{ $no + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-M-Y') }}</td>
                                <td>{{ $item->siswa->nis ?? '-' }}</td>
                                <td class="fw-bold text-uppercase">{{ $item->siswa->nama_siswa ?? 'Data Terhapus' }}</td>
                                <td>{{ $item->siswa->jurusan ?? '-' }}</td>
                                <td>{{ $item->siswa->kelas ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ $item->jenisPelanggaran->nama_pelanggaran ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $item->sanksi ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted fw-bold py-4">📭 Tidak ada data pelanggaran yang cocok dengan filter.</td>
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

            if (filterType === 'harian' || filterType === 'mingguan') {
                tanggalGroup.classList.remove('d-none');
                bulanGroup.classList.add('d-none');
            } else if (filterType === 'bulanan') {
                tanggalGroup.classList.add('d-none');
                bulanGroup.classList.remove('d-none');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            toggleFilterInput();
        });
    </script>
@endsection

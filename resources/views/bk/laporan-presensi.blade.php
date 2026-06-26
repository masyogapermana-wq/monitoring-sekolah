@extends('layouts.main')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <h4 class="fw-bold m-0">📄 Laporan Presensi Siswa</h4>
        <a href="{{ route('bk.cetak-presensi', ['filter' => $filter, 'tanggal' => $tanggalInput, 'bulan' => $bulanInput]) }}" target="_blank" class="btn btn-primary fw-bold shadow-sm">
            🖨️ Cetak Laporan (PDF)
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">🔍 Filter Rentang Laporan</h5>
            <form action="{{ route('bk.laporan-presensi') }}" method="GET" class="row g-3 align-items-end">

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
                    <span id="infoMinggu" class="text-muted small d-none">Sistem akan otomatis mengambil rentang 1 minggu penuh dari tanggal ini.</span>
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
            <div class="card-body overflow-auto">
                <table class="table table-bordered table-striped" style="min-width: 600px;">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">NIS</th>
                            <th>Nama Siswa</th>
                            <th width="15%">Kelas</th>
                            <th width="15%">Jam Masuk</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
            @forelse($siswas as $index => $siswa)
                @php
                    // Ambil data absen dari relasi yang udah kita saring di Controller
                    // Karena kalau mingguan/bulanan absennya bisa banyak, kita ambil yang terbaru (first)
                    $absen = $siswa->presensi->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td>{{ $siswa->kelas }}</td>

                    <td class="text-center">
                        @if($absen)
                            {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }} WIB
                        @else
                            <span class="text-danger fw-bold">-</span>
                        @endif
                    </td>

                    <td class="text-center">
                        @if($absen)
                            @if($absen->status == 'Hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif($absen->status == 'Terlambat')
                                <span class="badge bg-warning text-dark">Terlambat</span>
                            @else
                                <span class="badge bg-info text-dark">{{ $absen->status }}</span>
                            @endif
                        @else
                            <span class="badge bg-danger">Alpa</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data siswa ditemukan.</td>
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

    // Jalankan fungsi sekali saat halaman pertama kali dimuat
    document.addEventListener("DOMContentLoaded", function() {
        toggleFilterInput();
    });
</script>
@endsection

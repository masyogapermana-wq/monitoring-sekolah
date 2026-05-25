@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">📄 Laporan Presensi Siswa</h4>
    <a href="{{ route('bk.cetak-presensi') }}" target="_blank" class="btn btn-primary fw-bold">
        🖨️ Cetak Laporan (PDF)
    </a>
</div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('bk.laporan-presensi') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">🔍 Filter Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensis as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->siswa->nis ?? '-' }}</td>
                        <td class="fw-bold">{{ $item->siswa->nama_siswa ?? 'Data Terhapus' }}</td>
                        <td>{{ $item->siswa->kelas ?? '-' }}</td>
                        <td>{{ $item->jam_masuk }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'Terlambat' ? 'bg-danger' : 'bg-success' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data presensi pada tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

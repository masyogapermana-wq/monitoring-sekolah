@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
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
                                // Cocokin, apakah siswa ini ada datanya di tabel presensi hari ini?
                                $absen = $presensis->get($siswa->id);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $siswa->nis }}</td>
                                <td class="fw-bold">{{ $siswa->nama_siswa }}</td>
                                <td>{{ $siswa->kelas }}</td>

                                <td>{{ $absen ? $absen->jam_masuk : '-' }}</td>

                                <td>
                                    @if ($absen)
                                        @if ($absen->status == 'Terlambat')
                                            <span class="badge bg-warning text-dark">Terlambat</span>
                                        @elseif($absen->status == 'Sakit')
                                            <span class="badge bg-info text-dark">Sakit</span>
                                        @elseif($absen->status == 'Izin')
                                            <span class="badge bg-secondary">Izin</span>
                                        @else
                                            <span class="badge bg-success">Hadir</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Alpa</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Belum ada data siswa di database. Silakan tambah data siswa terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

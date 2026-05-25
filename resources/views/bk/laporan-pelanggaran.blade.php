@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">📑 Laporan Pelanggaran Siswa</h3>
            <a href="{{ route('bk.cetak') }}" target="_blank" class="btn btn-primary">
                🖨️ Cetak Laporan (PDF)
            </a>
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
                        @foreach ($pelanggarans as $no => $item)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold mb-4">🎓 Data Siswa</h3>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                    + Tambah Siswa Baru
                </button>
                <a href="{{ route('siswa.cetak-semua') }}" target="_blank" class="btn btn-dark fw-bold shadow-sm">
                    🖨️ Cetak Semua QR Code
                </a>
            </div>

            <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center bg-white p-2 rounded shadow-sm border">
                <label class="me-2 fw-bold small text-muted text-nowrap">🔍 Filter Kelas:</label>
                <select name="kelas" class="form-select form-select-sm border-primary" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="semua" {{ $kelasPilihan == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                    @foreach($daftarKelas as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                    @endforeach
                </select>
            </form>

        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="accordion mt-4" id="accordionSiswa">
            @forelse($siswaGrouped as $namaKelas => $daftarSiswa)
                @php
                    $idKelasSafe = str_replace(' ', '-', $namaKelas);
                @endphp

                <div class="accordion-item mb-2 border shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $kelasPilihan != 'semua' ? '' : 'collapsed' }} fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $idKelasSafe }}">
                            📂 {{ $namaKelas }} &nbsp;
                            <span class="badge bg-primary ms-2">{{ $daftarSiswa->count() }} Siswa</span>
                        </button>
                    </h2>
                    <div id="collapse-{{ $idKelasSafe }}" class="accordion-collapse collapse {{ $kelasPilihan != 'semua' ? 'show' : '' }}"
                        data-bs-parent="#accordionSiswa">
                        <div class="accordion-body p-0">

                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">QR Code</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daftarSiswa as $no => $siswa)
                                        <tr>
                                            <td>{{ $no + 1 }}</td>
                                            <td>
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $siswa->nis }}"
                                                    alt="QR" class="border rounded p-1 bg-white">
                                            </td>
                                            <td class="fw-bold">{{ $siswa->nis }}</td>
                                            <td class="fw-bold text-uppercase">{{ $siswa->nama_siswa }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('siswa.cetak-qr', $siswa->id) }}"
                                                        target="_blank" class="btn btn-sm btn-dark fw-bold">🖨️ Cetak</a>
                                                    <form action="{{ route('siswa.destroy', $siswa->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger fw-bold">🗑️ Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border text-center text-muted py-4 shadow-sm fw-bold">
                    📭 Tidak ada data siswa yang cocok dengan filter.
                </div>
            @endforelse
        </div>

        <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">Tambah Siswa Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('siswa.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIS</label>
                                <input type="text" name="nis" class="form-control" required
                                    placeholder="Masukkan NIS Siswa">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_siswa" class="form-control" required
                                    placeholder="Masukkan Nama Lengkap">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas / Jurusan</label>
                                <select name="kelas" class="form-select" required>
                                    <option value="">-- Pilih Kelas & Jurusan --</option>
                                    <optgroup label="Teknik Komputer Jaringan (TKJ)">
                                        <option value="X TKJ 1">X TKJ 1</option>
                                        <option value="X TKJ 2">X TKJ 2</option>
                                        <option value="XI TKJ 1">XI TKJ 1</option>
                                        <option value="XI TKJ 2">XI TKJ 2</option>
                                        <option value="XII TKJ 1">XII TKJ 1</option>
                                        <option value="XII TKJ 2">XII TKJ 2</option>
                                    </optgroup>
                                    <optgroup label="Desain Pemodelan dan Bangunan (DPB)">
                                        <option value="X DPB 1">X DPB 1</option>
                                        <option value="X DPB 2">X DPB 2</option>
                                        <option value="XI DPB 1">XI DPB 1</option>
                                        <option value="XI DPB 2">XI DPB 2</option>
                                        <option value="XII DPB 1">XII DPB 1</option>
                                        <option value="XII DPB 2">XII DPB 2</option>
                                    </optgroup>
                                    <optgroup label="Rekayasa Perangkat Lunak (RPL)">
                                        <option value="X RPL">X RPL</option>
                                        <option value="XI RPL">XI RPL</option>
                                        <option value="XII RPL">XII RPL</option>
                                    </optgroup>
                                    <optgroup label="Akuntansi">
                                        <option value="X AKUTANSI">X AKUTANSI</option>
                                        <option value="XI AKUTANSI">XI AKUTANSI</option>
                                        <option value="XII AKUTANSI">XII AKUTANSI</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

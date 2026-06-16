@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold mb-4">🎓 Data Siswa</h3>

        <div class="d-flex flex-column flex-md-row gap-2 mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                + Tambah Siswa Baru
            </button>
            <a href="{{ route('siswa.cetak-semua') }}" target="_blank" class="btn btn-dark">
                🖨️ Cetak Semua QR Code
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-pills nav-fill fw-bold mt-4 shadow-sm p-2 bg-light rounded" id="tabSiswa" role="tablist">
            @foreach (['X', 'XI', 'XII'] as $index => $tkt)
                <li class="nav-item">
                    <button class="nav-link {{ $index == 0 ? 'active' : '' }} py-2 text-uppercase shadow-sm"
                        id="tab-{{ $tkt }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $tkt }}"
                        type="button">
                        📁 KELAS TINGKAT {{ $tkt }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-4" id="tabSiswaContent">
            @foreach (['X', 'XI', 'XII'] as $index => $tkt)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="pane-{{ $tkt }}">

                    <div class="accordion" id="accordion-{{ $tkt }}">
                        @php
                            $kelasDiTingkatIni = $siswaGrouped->get($tkt, collect());
                        @endphp

                        @forelse($kelasDiTingkatIni as $namaKelas => $daftarSiswa)
                            @php
                                $idKelasSafe = str_replace(' ', '-', $namaKelas);
                            @endphp

                            <div class="accordion-item mb-2 border shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $idKelasSafe }}">
                                        📂 {{ $namaKelas }} &nbsp;
                                        <span class="badge bg-primary ms-2">{{ $daftarSiswa->count() }} Siswa</span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $idKelasSafe }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-{{ $tkt }}">
                                    <div class="accordion-body p-0">

                                        <table class="table table-bordered table-striped mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">QR Code</th>
                                                    <th>NIS</th>
                                                    <th>Nama Siswa</th>
                                                    <th width="20%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($daftarSiswa as $no => $siswa)
                                                    <tr>
                                                        <td>{{ $no + 1 }}</td>
                                                        <td>
                                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $siswa->nis }}"
                                                                alt="QR">
                                                        </td>
                                                        <td>{{ $siswa->nis }}</td>
                                                        <td class="fw-bold text-uppercase">{{ $siswa->nama_siswa }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <a href="{{ route('siswa.cetak-qr', $siswa->id) }}"
                                                                    target="_blank" class="btn btn-sm btn-dark">🖨️ Cetak
                                                                    QR</a>
                                                                <form action="{{ route('siswa.destroy', $siswa->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">🗑️
                                                                        Hapus</button>
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
                            <div class="alert alert-light border text-center text-muted py-4 shadow-sm">
                                📭 Belum ada data siswa yang terdaftar di Kelas Tingkat {{ $tkt }}.
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach
        </div>
        <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@extends('layouts.main')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">

        <!-- 1. ALERT PESAN SUKSES TAMBAH/HAPUS DATA -->
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4 border-0"
                style="background-color: rgba(46, 213, 115, 0.1); color: #2ed573; border-left: 4px solid #2ed573 !important;">
                <i class="fas fa-check-circle fs-4 me-3"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="alert d-flex align-items-center mb-4 border-0"
            style="background-color: rgba(0, 210, 255, 0.05); border-left: 4px solid #00d2ff !important; border-radius: 8px;">
            <span class="fs-4 me-3">👋</span>
            <div style="color: #cbd5e1;">
                <h6 class="mb-0 fw-bold text-white">Halo, Administrator (Admin)</h6>
                <small>Kelola data siswa untuk keperluan presensi dan pencatatan pelanggaran.</small>
            </div>
        </div>

        <!-- 2. JUDUL HALAMAN -->
        <h4 class="fw-bold mb-4 text-white">
            <i class="fas fa-user-graduate me-2" style="color: #00d2ff;"></i> Data Siswa
        </h4>

        <!-- 3. BARIS TOMBOL AKSI & FILTER -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div class="d-flex gap-2">
                <!-- Pemicu Modal Tambah Siswa -->
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa"
                    style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); color: white; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);">
                    <i class="fas fa-plus me-1"></i> Tambah Siswa Baru
                </button>

                <a href="{{ route('siswa.cetak-semua') }}" class="btn btn-secondary"
                    style="background-color: #273142; border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; font-weight: 500;">
                    <i class="fas fa-print me-1"></i> Cetak Semua QR Code
                </a>
            </div>

            <!-- Filter Kanan -->
            <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                <label class="text-secondary small fw-bold mb-0"><i class="fas fa-search me-1"></i> Filter Kelas:</label>
                <select name="kelas" onchange="this.form.submit()" class="form-select form-select-sm"
                    style="background-color: #1e293b; color: #ffffff; border-color: rgba(255,255,255,0.1); width: auto;">
                    <option value="semua" {{ $kelasPilihan == 'semua' ? 'selected' : '' }}>Semua Kelas</option>

                    @foreach ($daftarKelas as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- 4. DAFTAR KELAS DINAMIS -->
        <div class="accordion" id="accordionSiswa" data-bs-theme="dark">

            @forelse ($siswaGrouped as $namaKelas => $grupSiswa)
                @php $idCollapse = str_replace(' ', '', $namaKelas); @endphp

                <div class="accordion-item"
                    style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 10px; border-radius: 8px; overflow: hidden;">

                    <!-- PERUBAHAN ADA DI BAGIAN HEADER & BUTTON INI -->
                    <h2 class="accordion-header" id="heading-{{ $idCollapse }}">
                        <button class="accordion-button {{ session('open_folder') == $namaKelas ? '' : 'collapsed' }} text-white shadow-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $idCollapse }}"
                            style="background-color: #1e293b;" aria-expanded="{{ session('open_folder') == $namaKelas ? 'true' : 'false' }}">
                            <i class="fas fa-folder text-warning me-2"></i>
                            <span class="fw-bold me-2">{{ $namaKelas }}</span>
                            <span class="badge rounded-pill"
                                style="background-color: #00d2ff; color: #0b1320;">{{ $grupSiswa->count() }} Siswa</span>
                        </button>
                    </h2>

                    <!-- PERUBAHAN ADA DI CLASS DIV INI (Penambahan logika session) -->
                    <div id="collapse-{{ $idCollapse }}" class="accordion-collapse collapse {{ session('open_folder') == $namaKelas ? 'show' : '' }}"
                        data-bs-parent="#accordionSiswa">
                        <div class="accordion-body p-0"
                            style="background-color: #151e2d; border-top: 1px solid rgba(255,255,255,0.05);">

                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">No</th>
                                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">NIS</th>
                                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Lengkap</th>
                                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($grupSiswa as $no => $siswa)
                                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                <td class="px-4 py-3">{{ $no + 1 }}</td>
                                                <td class="px-4 py-3 fw-bold text-info">{{ $siswa->nis }}</td>
                                                <td class="px-4 py-3">{{ $siswa->nama_siswa }}</td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('siswa.cetak-qr', $siswa->id) }}"
                                                        class="btn btn-sm btn-outline-info rounded-pill px-3 me-1"
                                                        title="Cetak QR Code"><i class="fas fa-qrcode"></i></a>

                                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                            title="Hapus Siswa"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5" style="background-color: #1e293b; border-radius: 8px;">
                    <i class="fas fa-folder-open fa-3x text-secondary mb-3"></i>
                    <h6 class="text-muted">Data siswa belum tersedia atau kelas tidak ditemukan.</h6>
                </div>
            @endforelse

        </div>
    </div>

    <!-- MODAL FORM TAMBAH SISWA -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background-color: #1e293b; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white"><i class="fas fa-user-plus me-2 text-info"></i>Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                            <input type="text" name="nis" class="form-control"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required placeholder="Masukkan NIS">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama_siswa" class="form-control"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required placeholder="Contoh: Yoga Permana">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select"
                                style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1);"
                                required>
                                <option value="" selected disabled>-- Pilih Kelas Siswa --</option>
                                <optgroup label="Kelas X" style="background-color: #1e293b; color: #94a3b8;">
                                    <option value="X TKJ 1" style="color: white;">X TKJ 1</option>
                                    <option value="X TKJ 2" style="color: white;">X TKJ 2</option>
                                    <option value="X RPL" style="color: white;">X RPL</option>
                                    <option value="X AKUTANSI 1" style="color: white;">X AKUTANSI 1</option>
                                    <option value="X AKUTANSI 2" style="color: white;">X AKUTANSI 2</option>
                                    <option value="X DPB 1" style="color: white;">X DPB 1</option>
                                    <option value="X DPB 2" style="color: white;">X DPB 2</option>
                                </optgroup>
                                <optgroup label="Kelas XI" style="background-color: #1e293b; color: #94a3b8;">
                                    <option value="XI TKJ 1" style="color: white;">XI TKJ 1</option>
                                    <option value="XI TKJ 2" style="color: white;">XI TKJ 2</option>
                                    <option value="XI RPL" style="color: white;">XI RPL</option>
                                    <option value="XI AKUTANSI 1" style="color: white;">XI AKUTANSI 1</option>
                                    <option value="XI AKUTANSI 2" style="color: white;">XI AKUTANSI 2</option>
                                    <option value="XI DPB 1" style="color: white;">XI DPB 1</option>
                                    <option value="XI DPB 2" style="color: white;">XI DPB 2</option>
                                </optgroup>
                                <optgroup label="Kelas XII" style="background-color: #1e293b; color: #94a3b8;">
                                    <option value="XII TKJ 1" style="color: white;">XII TKJ 1</option>
                                    <option value="XII TKJ 2" style="color: white;">XII TKJ 2</option>
                                    <option value="XII RPL" style="color: white;">XII RPL</option>
                                    <option value="XII AKUTANSI 1" style="color: white;">XII AKUTANSI 1</option>
                                    <option value="XII AKUTANSI 2" style="color: white;">XII AKUTANSI 2</option>
                                    <option value="XII DPB 1" style="color: white;">XII DPB 1</option>
                                    <option value="XII DPB 2" style="color: white;">XII DPB 2</option>
                                </optgroup>
                            </select>
                            <small class="text-muted" style="font-size: 0.75rem;">Pilih kelas yang tepat agar jurusan otomatis terdeteksi oleh sistem.</small>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                            style="background-color: rgba(255,255,255,0.1); border: none;">Batal</button>
                        <button type="submit" class="btn px-4 text-white fw-bold"
                            style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); border: none;">Simpan Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

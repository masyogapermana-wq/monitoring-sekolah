@extends('layouts.main')

@section('title', 'Dashboard Guru Piket')

@section('content')
    <div class="container-fluid">

        <!-- 1. HEADER HALAMAN -->
        <div class="mb-4">
            <h3 class="fw-bold text-white mb-2">Selamat Datang, Guru Piket 👋</h3>
            <p class="text-secondary small">Pilih menu di bawah ini untuk mengelola presensi kedatangan dan pelanggaran tata tertib siswa hari ini.</p>
        </div>

        <!-- 2. KOTAK MENU UTAMA (SCAN & MANUAL) -->
        <div class="row g-4 mb-4">

            <!-- Menu Scan QR -->
            <div class="col-md-6">
                <div class="card h-100 text-center p-4"
                    style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); transition: transform 0.3s ease;">
                    <div class="mb-3">
                        <i class="fas fa-camera retro-icon" style="font-size: 3rem; color: #a5b1c2;"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Scan QR Presensi</h4>
                    <p class="text-muted small mb-4">Catat kehadiran otomatis menggunakan kamera web / barcode scanner.</p>
                    <a href="{{ route('piket.scan') }}" class="btn text-white fw-bold py-2 px-4 rounded-pill w-75 mx-auto"
                        style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); border: none; box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);">
                        Buka Scanner
                    </a>
                </div>
            </div>

            <!-- Menu Input Manual -->
            <div class="col-md-6">
                <div class="card h-100 text-center p-4"
                    style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); transition: transform 0.3s ease;">
                    <div class="mb-3">
                        <i class="fas fa-keyboard retro-icon" style="font-size: 3rem; color: #a5b1c2;"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Presensi Manual</h4>
                    <p class="text-muted small mb-4">Catat kehadiran siswa yang lupa membawa kartu dengan mengetik NIS.</p>
                    <a href="{{ route('piket.manual') }}" class="btn text-white fw-bold py-2 px-4 rounded-pill w-75 mx-auto"
                        style="background: linear-gradient(135deg, #2ed573 0%, #20bf6b 100%); border: none; box-shadow: 0 4px 15px rgba(46, 213, 115, 0.3);">
                        Input Manual
                    </a>
                </div>
            </div>

        </div>

        <!-- 3. KOTAK TABEL RIWAYAT PRESENSI -->
        <div class="card p-0"
            style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">

            <!-- Header Tabel & Filter -->
            <div class="card-header border-0 p-4 d-flex justify-content-between align-items-center"
                style="background-color: transparent;">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-clipboard-list me-2 text-warning"></i> Riwayat Presensi Hari Ini
                </h6>

                <!-- 🔥 PERBAIKAN: Form Filter Kelas Otomatis -->
                <form action="{{ url('/piket/dashboard') }}" method="GET" class="d-flex align-items-center m-0">
                    <label class="text-secondary small me-2 mb-0" for="filterKelas">Filter Kelas:</label>

                    <!-- onchange="this.form.submit()" berfungsi agar form otomatis terkirim saat opsi diubah -->
                    <select name="kelas" id="filterKelas" class="form-select form-select-sm"
                        style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1); width: auto;"
                        onchange="this.form.submit()">

                        <!-- Opsi bawaan untuk menampilkan semua data -->
                        <option value="semua" {{ $kelasPilihan == 'semua' ? 'selected' : '' }}>Semua Kelas</option>

                        <!-- Looping memanggil nama kelas asli dari database (Misal: X TKJ 1, X RPL 2) -->
                        @foreach($daftarKelas as $kelas)
                            <option value="{{ $kelas }}" {{ $kelasPilihan == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach

                    </select>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">No</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">NIS</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Siswa</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Jam Masuk</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Status</th>
                            <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($presensis as $no => $item)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td class="px-4 py-3 align-middle text-light">{{ $no + 1 }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ $item->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-3 align-middle text-light fw-bold">{{ $item->siswa->nama_siswa ?? 'Nama Tidak Ditemukan' }}</td>
                            <td class="px-4 py-3 align-middle text-light">{{ $item->jam_masuk }}</td>

                            <td class="px-4 py-3 align-middle">
                                @if($item->status == 'Hadir')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Hadir</span>
                                @elseif($item->status == 'Terlambat')
                                    <span class="badge text-dark px-3 py-2 rounded-pill" style="background-color: #ffab00;">Terlambat</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $item->status }}</span>
                                @endif
                            </td>

                            <!-- Kolom Aksi (Sudah Ditambahkan Modal Edit) -->
                            <td class="px-4 py-3 align-middle">

                                <!-- Tombol Memicu Modal Edit -->
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit Status">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Modal Edit Status Presensi (Pop-up) -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">

                                      <!-- Kepala Modal -->
                                      <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <h5 class="modal-title text-white fw-bold" id="editModalLabel{{ $item->id }}">
                                            <i class="fas fa-edit text-warning me-2"></i> Edit Status
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>

                                      <!-- Formulir Edit -->
                                      <form action="{{ route('piket.update-presensi', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body text-start">
                                            <p class="text-secondary small mb-3">Ubah status kehadiran untuk <strong>{{ $item->siswa->nama_siswa ?? 'Siswa' }}</strong>.</p>

                                            <div class="mb-3">
                                                <label class="form-label text-light small fw-bold">Status Kehadiran</label>
                                                <select name="status" class="form-select form-control-dark" style="background-color: #273142; color: #fff; border: 1px solid #334155;" required>
                                                    <option value="Hadir" {{ $item->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="Terlambat" {{ $item->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                                    <option value="Sakit" {{ $item->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="Izin" {{ $item->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="Alpha" {{ $item->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Kaki Modal (Tombol) -->
                                        <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Simpan</button>
                                        </div>
                                      </form>

                                    </div>
                                  </div>
                                </div>
                                <!-- Akhir Modal -->

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 text-secondary" style="opacity: 0.5;"></i><br>
                                Belum ada data presensi yang masuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Sedikit tambahan animasi hover untuk kartu menu -->
    <style>
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
@endsection

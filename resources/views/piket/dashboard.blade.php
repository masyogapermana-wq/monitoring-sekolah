@extends('layouts.main')

@section('title', 'Dashboard Guru Piket')

@section('content')
    <div class="container-fluid">

        <!-- 1. HEADER HALAMAN -->
        <div class="mb-4">
            <h3 class="fw-bold text-white mb-2">Selamat Datang, Guru Piket 👋</h3>
            <p class="text-secondary small">Berikut adalah riwayat presensi kedatangan siswa pada hari ini.</p>
        </div>

        <!-- KARTU STATISTIK GURU PIKET -->
        <div class="row g-4 mb-4">

            <!-- Kartu Total Masuk -->
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2); transition: transform 0.3s;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white mb-1 opacity-75 fw-bold">Total Masuk Hari Ini</p>
                            <h2 class="text-white fw-bold mb-0">{{ $totalMasuk ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-users text-white fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Tepat Waktu -->
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2); transition: transform 0.3s;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white mb-1 opacity-75 fw-bold">Tepat Waktu</p>
                            <h2 class="text-white fw-bold mb-0">{{ $totalTepatWaktu ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle text-white fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Terlambat -->
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.2); transition: transform 0.3s;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white mb-1 opacity-75 fw-bold">Terlambat</p>
                            <h2 class="text-white fw-bold mb-0">{{ $totalTerlambat ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-clock text-white fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 2. KOTAK TABEL RIWAYAT PRESENSI -->
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

        /* Kita menghapus kodingan min-height agar tombol Simpan kembali naik,
           lalu menggunakan trik overflow ini agar dropdown tetap aman jika panjang */
        .modal-body {
            overflow: visible !important;
        }

        .modal {
            overflow-y: visible !important;
        }
    </style>
@endsection

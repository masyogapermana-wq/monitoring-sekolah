@extends('layouts.main')

@section('title', 'Dashboard Guru BK')

@section('content')
<div class="container-fluid">

    <!-- HEADER: HANYA ADA TOMBOL INPUT SANKSI -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-white mb-2">Dashboard Guru BK 👩‍🏫</h3>
            <p class="text-secondary small mb-0">Pantau evaluasi presensi dan pelanggaran tata tertib siswa hari ini.</p>
        </div>

        <!-- Tombol Aksi BK (Tombol Cetak Laporan Telah Dihapus) -->
        <div class="d-flex gap-2">
            <a href="{{ route('bk.input-pelanggaran') }}" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Input Sanksi
            </a>
        </div>
    </div>

    <!-- KARTU STATISTIK (Hadir, Terlambat, & Alpa) -->
    <div class="row g-4 mb-4">

        <!-- Kartu Hadir -->
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #2ed573 0%, #20bf6b 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(46, 213, 115, 0.2); transition: transform 0.3s;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white mb-1 opacity-75 fw-bold">Hadir Hari Ini</p>
                        <h2 class="text-white fw-bold mb-0">{{ $totalHadir ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-check text-white fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Terlambat -->
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #ffa502 0%, #ff7f50 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(255, 165, 2, 0.2); transition: transform 0.3s;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white mb-1 opacity-75 fw-bold">Terlambat Hari Ini</p>
                        <h2 class="text-white fw-bold mb-0">{{ $totalTerlambat ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock text-white fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Alpa (Merah) -->
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0" style="background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(255, 71, 87, 0.2); transition: transform 0.3s;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white mb-1 opacity-75 fw-bold">Alpa Hari Ini</p>
                        <h2 class="text-white fw-bold mb-0">{{ $totalAlpa ?? 0 }} <span class="fs-6 fw-normal">Siswa</span></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-times text-white fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- BAGIAN TENGAH: Tabel Riwayat & Grafik -->
    <div class="row g-4">

        <!-- Kolom Tabel Pelanggaran -->
        <div class="col-lg-8">
            <div class="card p-0 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
                <div class="card-header border-0 p-4 d-flex justify-content-between align-items-center" style="background-color: transparent;">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i> Riwayat Pelanggaran Terbaru
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Tanggal</th>
                                <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">NIS</th>
                                <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Nama Siswa</th>
                                <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Jenis Pelanggaran</th>
                                <th class="px-4 py-3 text-secondary" style="background-color: rgba(0,0,0,0.2);">Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggarans ?? [] as $pelanggaran)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td class="px-4 py-3 align-middle text-light">{{ \Carbon\Carbon::parse($pelanggaran->tanggal_kejadian)->format('d M Y') }}</td>
                                    <td class="px-4 py-3 align-middle text-light">{{ $pelanggaran->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 align-middle text-light fw-bold">{{ $pelanggaran->siswa->nama_siswa ?? '-' }}</td>
                                    <td class="px-4 py-3 align-middle text-warning">{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                    <td class="px-4 py-3 align-middle text-light">{{ $pelanggaran->sanksi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-2x mb-3 text-success" style="opacity: 0.5;"></i><br>
                                        Aman! Belum ada data pelanggaran terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Grafik Presensi -->
        <div class="col-lg-4">
            <div class="card p-4 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <h6 class="text-white fw-bold mb-4 text-center">
                    <i class="fas fa-chart-pie me-2 text-info"></i> Statistik Kehadiran Hari Ini
                </h6>
                <div class="d-flex justify-content-center align-items-center" style="height: 250px;">
                    <!-- Canvas Grafik Chart.js -->
                    <canvas id="kehadiranChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Efek Hover Kartu -->
<style>
    .card:hover {
        transform: translateY(-5px);
    }
</style>

<!-- Import Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dataHadir = {{ $totalHadir ?? 0 }};
        const dataTerlambat = {{ $totalTerlambat ?? 0 }};
        const dataAlpa = {{ $totalAlpa ?? 0 }};

        const ctx = document.getElementById('kehadiranChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Terlambat', 'Alpa'],
                datasets: [{
                    data: [dataHadir, dataTerlambat, dataAlpa],
                    backgroundColor: ['#2ed573', '#ffa502', '#ff4757'],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#a5b1c2',
                            padding: 20,
                            font: {
                                size: 13,
                                family: "'Nunito', sans-serif"
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

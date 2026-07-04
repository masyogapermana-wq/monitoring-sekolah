@extends('layouts.main')

@section('title', 'Dashboard Statistik')

@section('content')
<div class="container-fluid">

    <!-- 1. KARTU TOTAL SISWA (Diperbesar) -->
    <div class="row mb-4">
        <!-- PERUBAHAN: col-md-4 diubah menjadi col-md-6 agar kartunya lebih panjang (setengah layar) -->
        <div class="col-md-6">
            <!-- PERUBAHAN: p-3 diubah menjadi p-4 agar ruang di dalam kartu lebih luas -->
            <div class="card p-4 border-start border-info border-4" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <!-- PERUBAHAN: Ukuran teks disesuaikan -->
                        <p class="mb-2 fw-bold text-uppercase" style="color: #94a3b8; font-size: 0.95rem;">Total Siswa</p>
                        <h2 class="text-white fw-bold mb-0">{{ $totalSiswa }} <span class="fs-6 fw-normal">Siswa</span></h2>
                    </div>
                    <!-- PERUBAHAN: Kotak ikon diperbesar dengan p-4 dan fa-2x -->
                    <div class="p-4 rounded-circle" style="background-color: rgba(0, 210, 255, 0.1); color: #00d2ff;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ruang di sebelah kanan kartu ini sengaja dibiarkan kosong, nantinya bisa kamu isi dengan kartu lain (misalnya: Total Guru atau Total Pelanggaran) -->
    </div>

    <!-- 2. GRAFIK KOTAK-KOTAK (Dibuat Full Lebar) -->
    <div class="row">
        <!-- PERUBAHAN: col-lg-8 diubah menjadi col-12 agar grafik memenuhi lebar layar -->
        <div class="col-12">
            <div class="card p-4" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <!-- PERUBAHAN: Judul grafik sedikit diperbesar ukurannya -->
                <h4 class="fw-bold mb-4 text-white">
                    <i class="fas fa-chart-bar me-2" style="color: #00d2ff;"></i>Statistik Kehadiran Siswa
                </h4>

                <!-- PERUBAHAN: Atribut height diubah dari 100 menjadi 120 agar grafik lebih tinggi -->
                <canvas id="presensiChart" height="120"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

<!-- SCRIPT UNTUK MEMUNCULKAN GRAFIK -->
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('presensiChart').getContext('2d');

        Chart.defaults.color = '#94a3b8';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                datasets: [
                    {
                        label: 'Tepat Waktu',
                        data: [420, 435, 410, 440, 400],
                        backgroundColor: 'rgba(0, 210, 255, 0.8)',
                        borderColor: '#00d2ff',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Terlambat',
                        data: [15, 5, 20, 3, 25],
                        backgroundColor: 'rgba(255, 71, 87, 0.8)',
                        borderColor: '#ff4757',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });
    });
</script>
@endpush

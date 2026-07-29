@extends('layouts.main')

@section('title', 'Pengaturan Sistem')

<!-- CSS Khusus untuk membalikkan warna ikon jam bawaan browser menjadi terang -->
<style>
    .time-premium {
        color-scheme: dark;
    }
</style>

@section('content')
    <div class="container-fluid">

        <!-- 1. ALERT PESAN SUKSES -->
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4 border-0"
                style="background-color: rgba(46, 213, 115, 0.1); color: #2ed573; border-left: 4px solid #2ed573 !important;">
                <i class="fas fa-check-circle fs-4 me-3"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <!-- 2. JUDUL HALAMAN -->
        <h4 class="fw-bold mb-4 text-white">
            <i class="fas fa-cogs me-2" style="color: #00d2ff;"></i> Pengaturan Sistem
        </h4>

        <!-- 3. KOTAK FORM PENGATURAN -->
        <div class="row">
            <!-- col-md-8 dan col-lg-6 digunakan agar form tidak terlalu lebar di layar besar -->
            <div class="col-md-8 col-lg-6">

                <div class="card p-4"
                    style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">

                    <!-- Form Pengaturan Jam Presensi Dinamis -->
                    <form action="{{ route('pengaturan.update') }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Sesuaikan dengan method route lu -->

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-white mb-2">Jam Mulai Absen Pagi <span
                                        class="text-danger">*</span></label>
                                <input type="time" name="mulai_hadir" class="form-control"
                                    style="background-color: #1a2234; color: white; border: 1px solid #2d3748;"
                                    value="{{ $pengaturan->mulai_hadir ?? '06:00' }}" required>
                                <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Waktu paling awal
                                    siswa bisa absen.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-white mb-2">Batas Jam Hadir <span class="text-danger">*</span></label>
                                <input type="time" name="batas_hadir" class="form-control"
                                    style="background-color: #1a2234; color: white; border: 1px solid #2d3748;"
                                    value="{{ $pengaturan->batas_hadir ?? '07:30' }}" required>
                                <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Lewat dari jam ini,
                                    status menjadi Terlambat.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-white mb-2">Batas Akhir Keterlambatan <span
                                        class="text-danger">*</span></label>
                                <input type="time" name="batas_terlambat" class="form-control"
                                    style="background-color: #1a2234; color: white; border: 1px solid #2d3748;"
                                    value="{{ $pengaturan->batas_terlambat ?? '08:00' }}" required>
                                <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Lewat dari jam ini,
                                    status otomatis menjadi Alpa.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-white mb-2">Batas Alpa (Tutup Absen) <span
                                        class="text-danger">*</span></label>
                                <input type="time" name="batas_alpa" class="form-control"
                                    style="background-color: #1a2234; color: white; border: 1px solid #2d3748;"
                                    value="{{ $pengaturan->batas_alpa ?? '15:00' }}" required>
                                <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Jam operasional
                                    sistem presensi akan ditutup.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info w-100 fw-bold mt-3">
                            <i class="fas fa-save me-2"></i> Simpan Peraturan
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>
@endsection

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
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-4 border-0" style="background-color: rgba(46, 213, 115, 0.1); color: #2ed573; border-left: 4px solid #2ed573 !important;">
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

            <div class="card p-4" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">

                <!-- Sesuaikan nama route action-nya dengan yang ada di web.php lu -->
                <form action="{{ route('pengaturan.update') }}" method="POST">
                    @csrf
                    <!-- Biasanya form update menggunakan method PUT -->
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label text-white fw-bold mb-3">Batas Jam Masuk (Keterlambatan) <span class="text-danger">*</span></label>

                        <!-- Input type="time" dengan class time-premium agar ikonnya terang -->
                        <!-- Value 07:30 adalah nilai bawaan jika data dari database kosong -->
                        <input type="time" name="batas_jam_masuk" class="form-control p-3 time-premium" style="background-color: #0b1320; color: white; border-color: rgba(255,255,255,0.1); font-size: 1.1rem; border-radius: 8px;" value="{{ $pengaturan->batas_jam_masuk ?? '07:30' }}" required>

                        <!-- Teks bantuan yang dibuat lebih redup dan rapi -->
                        <div class="form-text mt-3" style="color: #94a3b8; font-size: 0.85rem;">
                            <i class="fas fa-info-circle me-1 text-info"></i> Siswa yang melakukan presensi melewati jam ini akan otomatis berstatus <strong class="text-white">Terlambat</strong>.
                        </div>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 20px;">

                    <!-- Tombol Simpan -->
                    <button type="submit" class="btn text-white fw-bold px-4 py-3 w-100" style="background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%); border: none; box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3); border-radius: 8px;">
                        <i class="fas fa-save me-2"></i> Simpan Peraturan
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>
@endsection

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route untuk Halaman Login (Tamu/Belum Login)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// Route Sementara (Untuk cek dashboard setelah login)
Route::middleware(['auth'])->group(function () {
    // Nanti kita amankan ini pakai Middleware Role

    Route::middleware(['role:admin'])->group(function () {
        // Route Dashboard Admin yang baru
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

        // Tambahkan Route Siswa di sini:
        Route::get('/admin/siswa', [App\Http\Controllers\SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/admin/siswa', [App\Http\Controllers\SiswaController::class, 'store'])->name('siswa.store');
        Route::delete('/admin/siswa/{id}', [App\Http\Controllers\SiswaController::class, 'destroy'])->name('siswa.destroy');
        // Route untuk Cetak SEMUA QR Code Siswa Sekaligus
        Route::get('/admin/siswa/cetak-semua-qr', [App\Http\Controllers\SiswaController::class, 'cetakSemuaQr'])->name('siswa.cetak-semua');

        // (Rute cetak per siswa yang kemarin biarkan saja di bawahnya)
        Route::get('/admin/siswa/{id}/cetak-qr', [App\Http\Controllers\SiswaController::class, 'cetakQr'])->name('siswa.cetak-qr');

        // Route untuk Cetak QR Code Siswa
        Route::get('/admin/siswa/{id}/cetak-qr', [App\Http\Controllers\SiswaController::class, 'cetakQr'])->name('siswa.cetak-qr');

        // Route Data Guru (User)
        Route::get('/admin/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
        Route::post('/admin/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
        Route::delete('/admin/user/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
        // Route Jenis Pelanggaran
        Route::get('/admin/pelanggaran', [App\Http\Controllers\JenisPelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::post('/admin/pelanggaran', [App\Http\Controllers\JenisPelanggaranController::class, 'store'])->name('pelanggaran.store');
        Route::delete('/admin/pelanggaran/{id}', [App\Http\Controllers\JenisPelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');
        // Ganti route '/piket/dashboard' yang lama dengan controller baru:

    });

    // Route Guru Piket
    // Pastikan ini ada di dalam middleware auth
    Route::middleware(['role:piket'])->group(function () {
        Route::get('/piket/dashboard', [App\Http\Controllers\PiketController::class, 'index']);
        Route::get('/piket/scan', [App\Http\Controllers\PiketController::class, 'scan'])->name('piket.scan');
        Route::post('/piket/cek-siswa', [App\Http\Controllers\PiketController::class, 'cekSiswa'])->name('piket.cek-siswa');
        Route::get('/piket/manual', [App\Http\Controllers\PiketController::class, 'manual'])->name('piket.manual');

        // TAMBAHKAN INI:
        Route::post('/piket/simpan-presensi', [App\Http\Controllers\PiketController::class, 'simpanPresensi'])->name('piket.simpan');

        Route::get('/piket/input-pelanggaran', [App\Http\Controllers\PiketController::class, 'inputPelanggaran'])->name('piket.input');
        Route::post('/piket/input-pelanggaran', [App\Http\Controllers\PiketController::class, 'storePelanggaran'])->name('piket.store-pelanggaran');
    });
    // Route Guru BK
    Route::middleware(['role:bk'])->group(function () {
        Route::get('/bk/dashboard', [App\Http\Controllers\BkController::class, 'index'])->name('bk.dashboard');
        // TAMBAHKAN DUA BARIS INI:
        Route::get('/bk/laporan-pelanggaran', [App\Http\Controllers\BkController::class, 'laporanPelanggaran'])->name('bk.laporan');
        Route::get('/bk/cetak-laporan', [App\Http\Controllers\BkController::class, 'cetakLaporan'])->name('bk.cetak');
        // Route Laporan Presensi
        Route::get('/bk/laporan-presensi', [App\Http\Controllers\BkController::class, 'laporanPresensi'])->name('bk.laporan-presensi');
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/bk/cetak-presensi', [App\Http\Controllers\BkController::class, 'cetakPresensi'])->name('bk.cetak-presensi');
});

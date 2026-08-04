<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTE HALAMAN LOGIN (Tamu / Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login', function () {
        return redirect('/');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTE SETELAH LOGIN (Membutuhkan Autentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // 1. ROUTE KHUSUS ADMIN
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

        // Manajemen Data Siswa
        Route::get('/admin/siswa', [App\Http\Controllers\SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/admin/siswa', [App\Http\Controllers\SiswaController::class, 'store'])->name('siswa.store');
        Route::delete('/admin/siswa/{id}', [App\Http\Controllers\SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Cetak QR Code
        Route::get('/admin/siswa/cetak-semua-qr', [App\Http\Controllers\SiswaController::class, 'cetakSemuaQr'])->name('siswa.cetak-semua');
        Route::get('/admin/siswa/{id}/cetak-qr', [App\Http\Controllers\SiswaController::class, 'cetakQr'])->name('siswa.cetak-qr');

        // Pengaturan Jam Masuk (Fitur Jam Fleksibel)
        Route::get('/admin/pengaturan', [\App\Http\Controllers\AdminController::class, 'pengaturan'])->name('admin.pengaturan');
        Route::put('/admin/pengaturan/update', [\App\Http\Controllers\AdminController::class, 'updatePengaturan'])->name('pengaturan.update');

        // Manajemen Data Guru (User)
        Route::get('/admin/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
        Route::post('/admin/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
        Route::delete('/admin/user/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');

        // Manajemen Jenis Pelanggaran
        Route::get('/admin/pelanggaran', [App\Http\Controllers\JenisPelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::post('/admin/pelanggaran', [App\Http\Controllers\JenisPelanggaranController::class, 'store'])->name('pelanggaran.store');
        Route::delete('/admin/pelanggaran/{id}', [App\Http\Controllers\JenisPelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

        // Manajemen Sanksi Edukatif
        Route::get('/admin/sanksi', [App\Http\Controllers\SanksiEdukatifController::class, 'index'])->name('sanksi.index');
        Route::post('/admin/sanksi', [App\Http\Controllers\SanksiEdukatifController::class, 'store'])->name('sanksi.store');
        Route::delete('/admin/sanksi/{id}', [App\Http\Controllers\SanksiEdukatifController::class, 'destroy'])->name('sanksi.destroy');
    });

    // ==========================================
    // 2. ROUTE KHUSUS GURU PIKET
    // ==========================================
    Route::middleware(['role:piket'])->group(function () {
        Route::get('/piket/dashboard', [App\Http\Controllers\PiketController::class, 'index']);

        // Fitur Presensi
        Route::get('/piket/scan', [App\Http\Controllers\PiketController::class, 'scan'])->name('piket.scan');
        Route::post('/piket/cek-siswa', [App\Http\Controllers\PiketController::class, 'cekSiswa'])->name('piket.cek-siswa');
        Route::get('/piket/manual', [App\Http\Controllers\PiketController::class, 'manual'])->name('piket.manual');
        Route::post('/piket/simpan-presensi', [App\Http\Controllers\PiketController::class, 'simpanPresensi'])->name('piket.simpan');
        Route::put('/piket/presensi/update/{id}', [\App\Http\Controllers\PiketController::class, 'updatePresensi'])->name('piket.update-presensi');

        // Fitur Pelanggaran
        Route::get('/piket/input-pelanggaran', [App\Http\Controllers\PiketController::class, 'inputPelanggaran'])->name('piket.input');
        Route::post('/piket/input-pelanggaran', [App\Http\Controllers\PiketController::class, 'storePelanggaran'])->name('piket.simpan-pelanggaran');
    });

    // ==========================================
    // 3. ROUTE KHUSUS GURU BK
    // ==========================================
    Route::middleware(['role:bk'])->group(function () {
        Route::get('/bk/dashboard', [App\Http\Controllers\BkController::class, 'index'])->name('bk.dashboard');
    });

    // ==========================================
    // 4. ROUTE BERSAMA (BISA DIAKSES ADMIN & BK)
    // ==========================================
    Route::middleware(['role:admin,bk'])->group(function () {
        // Laporan Pelanggaran
        Route::get('/bk/laporan-pelanggaran', [App\Http\Controllers\BkController::class, 'laporanPelanggaran'])->name('bk.laporan');
        Route::get('/bk/cetak-laporan', [App\Http\Controllers\BkController::class, 'cetakLaporan'])->name('bk.cetak');

        // Laporan Presensi
        Route::get('/bk/laporan-presensi', [App\Http\Controllers\BkController::class, 'laporanPresensi'])->name('bk.laporan-presensi');
        Route::get('/bk/cetak-presensi', [App\Http\Controllers\BkController::class, 'cetakPresensi'])->name('bk.cetak-presensi');

        // Cetak PDF
        Route::get('/bk/cetak-pdf', [App\Http\Controllers\BkController::class, 'cetakPdf'])->name('bk.cetak-pdf');
        Route::get('/bk/cetak-pelanggaran-pdf', [App\Http\Controllers\BkController::class, 'cetakPelanggaranPdf'])->name('bk.cetak-pelanggaran-pdf');

        // Route untuk menampilkan halaman form input pelanggaran BK
        Route::get('/bk/input-pelanggaran', [App\Http\Controllers\BkController::class, 'inputPelanggaran'])->name('bk.input-pelanggaran');

        // Route untuk memproses penyimpanan data dari form tersebut
        Route::post('/bk/store-pelanggaran', [App\Http\Controllers\BkController::class, 'storePelanggaran'])->name('bk.store-pelanggaran');
    });

    // ==========================================
    // 5. ROUTE LOGOUT (Bisa diakses semua role)
    // ==========================================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);
});

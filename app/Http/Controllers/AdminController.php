<?php

namespace App\Http\Controllers;

use App\Models\DataPelanggaran;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Fungsi untuk menampilkan Dashboard Admin
    public function index()
    {
        // Ambil tanggal hari ini menggunakan Carbon
        $hariIni = Carbon::today();

        // 1. MENGHITUNG KARTU STATISTIK (SINKRON DENGAN GURU BK)
        // Menghitung total seluruh siswa
        $totalSiswa = Siswa::count();

        // Menghitung semua user yang role-nya BUKAN 'admin' (semua guru pasti terhitung)
        $totalGuru = User::where('role', '!=', 'admin')->count();

        // Menghitung siswa yang terlambat pada hari ini
        $terlambatHariIni = Presensi::whereDate('tanggal', $hariIni)
            ->where('status', 'Terlambat')
            ->count();

        // Menghitung jumlah ORANG (siswa unik) yang melakukan pelanggaran hari ini
        // Kita gunakan kolom bawaan 'created_at' agar filter tanggal jauh lebih akurat
        $pelanggaranBaru = DataPelanggaran::whereDate('created_at', $hariIni)
            ->distinct('siswa_id')
            ->count('siswa_id');

        // ---------------------------------------------------------------------
        // TAMBAHAN BARU: MENGHITUNG DATA UNTUK GRAFIK PIE CHART HARI INI
        // ---------------------------------------------------------------------
        $jmlHadir = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $jmlTerlambat = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Terlambat')->count();
        $jmlSakit = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Sakit')->count();
        $jmlIzin = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Izin')->count();
        $jmlAlpha = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Alpha')->count();

        // 2. MENGAMBIL DATA UNTUK TABEL (Hanya 5 data terakhir)
        $logPresensi = Presensi::with('siswa')
            ->whereDate('tanggal', $hariIni)
            ->latest('jam_masuk')
            ->take(5)
            ->get();

        // Filter tabel pelanggaran khusus HARI INI menggunakan 'created_at'
        $logPelanggaran = DataPelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->whereDate('created_at', $hariIni)
            ->latest()
            ->take(5)
            ->get();

        // 3. MELEMPAR DATA KE TAMPILAN
        // Variabel untuk chart sudah ditambahkan ke dalam compact
        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'terlambatHariIni', 'pelanggaranBaru', 'logPresensi', 'logPelanggaran',
            'jmlHadir', 'jmlTerlambat', 'jmlSakit', 'jmlIzin', 'jmlAlpha'
        ));
    }

    // =========================================================================
    // FUNGSI DI BAWAH INI TETAP DIPERTAHANKAN ASLI (TIDAK ADA PERUBAHAN FUNGSI)
    // =========================================================================

    // Menampilkan halaman form pengaturan
    public function pengaturan()
    {
        // Mencari data pengaturan pertama, jika belum ada di database, sistem otomatis membuatkan jam 07:00:00
        $pengaturan = Pengaturan::firstOrCreate(
            ['id' => 1],
            ['jam_masuk' => '07:00:00']
        );

        // Mengirim data pengaturan ke halaman tampilan blade
        return view('admin.pengaturan', compact('pengaturan'));
    }

    // Memproses data jam baru yang dikirim dari form
    public function updatePengaturan(Request $request)
    {
        // Validasi input form
        $request->validate([
            'mulai_hadir' => 'required',
            'batas_hadir' => 'required',
            'batas_terlambat' => 'required',
            'batas_alpha' => 'required',
        ]);

        // Ambil data pengaturan pertama (karena biasanya tabel setting hanya punya 1 baris)
        $pengaturan = \App\Models\Pengaturan::first();

        // Update data ke database
        $pengaturan->update([
            'mulai_hadir' => $request->mulai_hadir,
            'batas_hadir' => $request->batas_hadir,
            'batas_terlambat' => $request->batas_terlambat,
            'batas_alpha' => $request->batas_alpha,
        ]);

        return back()->with('success', 'Pengaturan jam presensi berhasil diperbarui!');
    }
}

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
        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'terlambatHariIni', 'pelanggaranBaru', 'logPresensi', 'logPelanggaran'
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
        // Memastikan kolom jam masuk tidak dibiarkan kosong
        $request->validate([
            'jam_masuk' => 'required',
        ]);

        // Memperbarui data jam di database
        $pengaturan = Pengaturan::first();
        $pengaturan->update([
            'jam_masuk' => $request->jam_masuk,
        ]);

        // Mengembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Batas jam masuk berhasil diperbarui menjadi '.$request->jam_masuk);
    }
}

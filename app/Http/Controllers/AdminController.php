<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\DataPelanggaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $hariIni = date('Y-m-d');

        // Ngitung otomatis dari database
        $totalSiswa = Siswa::count();
        $hadirHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $pelanggaranHariIni = DataPelanggaran::whereDate('tanggal_kejadian', $hariIni)->count();

        // Kirim datanya ke file view
        return view('admin.dashboard', compact('totalSiswa', 'hadirHariIni', 'pelanggaranHariIni'));
    }

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
            'jam_masuk' => 'required'
        ]);

        // Memperbarui data jam di database
        $pengaturan = Pengaturan::first();
        $pengaturan->update([
            'jam_masuk' => $request->jam_masuk
        ]);

        // Mengembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Batas jam masuk berhasil diperbarui menjadi ' . $request->jam_masuk);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Siswa;

class BkController extends Controller
{
    public function index()
    {
        // Mengambil tanggal hari ini
        $hariIni = date('Y-m-d');

        // Menghitung jumlah kehadiran hari ini
        $hadirHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $terlambatHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Terlambat')->count();

        // Mengambil 5 siswa dengan poin tertinggi
        // Mengambil 5 riwayat pelanggaran terbaru
$siswaBermasalah = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest()->take(5)->get();

        // Mengirim data ke halaman view
        return view('bk.dashboard', compact('hadirHariIni', 'terlambatHariIni', 'siswaBermasalah'));
    }
    // Fungsi untuk Halaman Laporan Pelanggaran
    public function laporanPelanggaran()
    {
        // Ambil semua data pelanggaran dari yang terbaru
        $pelanggarans = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest()->get();
        return view('bk.laporan-pelanggaran', compact('pelanggarans'));
    }

    // Fungsi untuk Cetak Laporan (Halaman Khusus Print)
    public function cetakLaporan()
    {
        $pelanggarans = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest()->get();
        return view('bk.cetak-laporan', compact('pelanggarans'));
    }
    // =================================================================
    // 1. FUNGSI LAPORAN PRESENSI (Ditambah fitur Filter Tanggal)
    // =================================================================
    public function laporanPresensi(\Illuminate\Http\Request $request)
    {
        $tanggal = $request->input('tanggal');
        $query = \App\Models\Presensi::with('siswa')->orderBy('tanggal', 'desc');

        // Kalau ada filter tanggal
        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $presensis = $query->get();
        return view('bk.laporan-presensi', compact('presensis', 'tanggal'));
    }

    // =================================================================
    // 2. FUNGSI CETAK LAPORAN (Biar tombol birunya jalan!)
    // =================================================================
    public function cetakPresensi(\Illuminate\Http\Request $request)
    {
        $tanggal = $request->input('tanggal');
        $query = \App\Models\Presensi::with('siswa')->orderBy('tanggal', 'desc');

        // Kalau cetak pas lagi difilter, yang dicetak cuma tanggal itu aja
        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $presensis = $query->get();
        return view('bk.cetak-presensi', compact('presensis'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\DataPelanggaran;
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

        // Kirim datanya ke file view lu
        return view('admin.dashboard', compact('totalSiswa', 'hadirHariIni', 'pelanggaranHariIni'));
    }
}

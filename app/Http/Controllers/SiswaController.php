<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // 1. Menampilkan Data Siswa (Dengan Tab & Folder Accordion)
    public function index()
    {
        // Ambil semua siswa dan urutkan berdasarkan nama secara alfabetis
        $siswas = Siswa::orderBy('nama_siswa', 'asc')->get();

        // LOGIKA PENGELOMPOKAN: Kelompokkan berdasarkan Tingkat (X, XI, XII), lalu per Kelas
        $siswaGrouped = $siswas->groupBy(function($item) {
            return explode(' ', $item->kelas)[0]; // Ambil kata pertama (X, XI, atau XII)
        })->map(function($tingkat) {
            return $tingkat->groupBy('kelas'); // Kelompokkan lagi sesuai nama kelas lengkap
        });

        return view('admin.siswa.index', compact('siswaGrouped'));
    }

    // 2. Menyimpan Data Siswa Baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required'
        ]);

        // TRIK SAKTI: Mengambil nama jurusan dari string kelas (Misal: dari "X TKJ 1" kita ambil "TKJ"-nya saja)
        $pecah = explode(' ', $request->kelas);
        $jurusan = isset($pecah[1]) ? $pecah[1] : '-';

        Siswa::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'jurusan' => $jurusan, // <--- INI OBAT PENAWAR ERROR-NYA
        ]);

        return back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    // 3. Menghapus Data Siswa
    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Data siswa berhasil dihapus!');
    }

    // 4. Cetak 1 QR Code Siswa
    public function cetakQr($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.cetak-qr', compact('siswa'));
    }

    // 5. Cetak SEMUA QR Code Siswa Sekaligus
    public function cetakSemuaQr()
    {
        $siswas = Siswa::orderBy('kelas', 'asc')->get();
        return view('admin.cetak-semua-qr', compact('siswas'));
    }
}

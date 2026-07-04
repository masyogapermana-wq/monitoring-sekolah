<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasPilihan = $request->get('kelas', 'semua');
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        // Query dasar ambil data siswa (diurutkan berdasarkan kelas, lalu nama)
        $query = Siswa::orderBy('kelas', 'asc')->orderBy('nama_siswa', 'asc');

        if ($kelasPilihan != 'semua') {
            $query->where('kelas', $kelasPilihan);
        }

        $siswas = $query->get();

        // LOGIKA BARU: Langsung kelompokkan berdasarkan nama kelas saja (tanpa tingkat)
        $siswaGrouped = $siswas->groupBy('kelas');

        return view('admin.siswa.index', compact('siswaGrouped', 'daftarKelas', 'kelasPilihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required'
        ]);

        $pecah = explode(' ', $request->kelas);
        $jurusan = isset($pecah[1]) ? $pecah[1] : '-';

        Siswa::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'jurusan' => $jurusan,
        ]);

        return back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    // Fungsi untuk menghapus data siswa
    public function destroy($id)
    {
        // 1. Cari data siswa yang mau dihapus
        $siswa = Siswa::find($id);

        // 2. Simpan nama kelasnya ke dalam variabel sebelum datanya benar-benar dihapus
        $kelasSiswa = $siswa->kelas;

        // 3. Hapus data siswa
        $siswa->delete();

        // 4. Kembali ke halaman sebelumnya dan bawa "pesan rahasia" bernama 'open_folder'
        return back()
            ->with('success', 'Data siswa berhasil dihapus!')
            ->with('open_folder', $kelasSiswa); // Ini kunci utamanya!
    }

    public function cetakQr($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.cetak-qr', compact('siswa'));
    }

    public function cetakSemuaQr()
    {
        $siswas = Siswa::orderBy('kelas', 'asc')->get();
        return view('admin.cetak-semua-qr', compact('siswas'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    // FUNGSI LAPORAN PELANGGARAN BK (Ditambah Filter Tanggal & Kelas)
    public function laporanPelanggaran(Request $request)
    {
        // 1. Ambil parameter filter waktu
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));

        // 2. Ambil parameter filter kelas
        $kelasPilihan = $request->get('kelas', 'semua');
        // Ambil daftar kelas dari model Siswa untuk opsi dropdown
        $daftarKelas = \App\Models\Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        // 3. Query dasar ambil data pelanggaran + relasi siswa dan jenis pelanggarannya
        $query = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest();

        // 4. 🔥 Kalau Guru BK milih kelas tertentu, saring pelanggaran berdasarkan kelas siswanya!
        if ($kelasPilihan != 'semua') {
            $query->whereHas('siswa', function ($q) use ($kelasPilihan) {
                $q->where('kelas', $kelasPilihan);
            });
        }

        // 5. Tentukan rentang waktu filter
        if ($filter == 'harian') {
            $startDate = Carbon::parse($tanggalInput)->startOfDay();
            $endDate = Carbon::parse($tanggalInput)->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = Carbon::parse($tanggalInput)->startOfWeek();
            $endDate = Carbon::parse($tanggalInput)->endOfWeek();
        } elseif ($filter == 'bulanan') {
            $startDate = Carbon::parse($bulanInput . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulanInput . '-01')->endOfMonth();
        }

        // 6. Saring data berdasarkan tanggal_kejadian
        $query->whereBetween('tanggal_kejadian', [$startDate->toDateString(), $endDate->toDateString()]);

        // 7. Eksekusi query
        $pelanggarans = $query->get();

        return view('bk.laporan-pelanggaran', compact('pelanggarans', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate', 'daftarKelas', 'kelasPilihan'));
    }

    // Fungsi untuk Cetak Laporan (Halaman Khusus Print)
    public function cetakLaporan()
    {
        $pelanggarans = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest()->get();

        return view('bk.cetak-laporan', compact('pelanggarans'));
    }

    // =================================================================
    // 1. FUNGSI LAPORAN PRESENSI (Ditambah Filter Tanggal & Kelas)
    // =================================================================
    public function laporanPresensi(Request $request)
    {
        // Ambil parameter filter waktu
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));

        // Ambil parameter filter kelas (Baru nih!)
        $kelasPilihan = $request->get('kelas', 'semua');
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        // Query dasar ambil data siswa
        $querySiswa = Siswa::orderBy('nama_siswa', 'asc');

        // 🔥 Kalau Guru BK milih kelas tertentu, saring siswanya!
        if ($kelasPilihan != 'semua') {
            $querySiswa->where('kelas', $kelasPilihan);
        }

        // Tentukan rentang tanggal
        if ($filter == 'harian') {
            $startDate = Carbon::parse($tanggalInput)->startOfDay();
            $endDate = Carbon::parse($tanggalInput)->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = Carbon::parse($tanggalInput)->startOfWeek();
            $endDate = Carbon::parse($tanggalInput)->endOfWeek();
        } elseif ($filter == 'bulanan') {
            $startDate = Carbon::parse($bulanInput . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulanInput . '-01')->endOfMonth();
        }

        // Ambil data siswa + relasi presensinya yang udah disaring tanggalnya
        $siswas = $querySiswa->with(['presensi' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
        }])->get();

        return view('bk.laporan-presensi', compact('siswas', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate', 'daftarKelas', 'kelasPilihan'));
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

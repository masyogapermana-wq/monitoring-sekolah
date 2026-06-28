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

    // FUNGSI LAPORAN PELANGGARAN BK DENGAN FILTER
    public function laporanPelanggaran(Request $request)
    {
        // 1. Ambil parameter filter dari request (default harian hari ini)
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));

        // 2. Query dasar ambil data pelanggaran + relasi siswa dan jenis pelanggarannya
        $query = \App\Models\DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest();

        // 3. Tentukan rentang waktu filter
        if ($filter == 'harian') {
            $startDate = Carbon::parse($tanggalInput)->startOfDay();
            $endDate = Carbon::parse($tanggalInput)->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = Carbon::parse($tanggalInput)->startOfWeek();
            $endDate = Carbon::parse($tanggalInput)->endOfWeek();
        } elseif ($filter == 'bulanan') {
            $startDate = Carbon::parse($bulanInput.'-01')->startOfMonth();
            $endDate = Carbon::parse($bulanInput.'-01')->endOfMonth();
        }

        // 4. Saring data berdasarkan tanggal_kejadian
        $query->whereBetween('tanggal_kejadian', [$startDate->toDateString(), $endDate->toDateString()]);

        // 5. Eksekusi query
        $pelanggarans = $query->get();

        // Panggil view dan kirim data (Pastikan nama view sesuai dengan file blade lu, misalnya 'bk.laporan-pelanggaran' atau 'bk.laporan')
        return view('bk.laporan-pelanggaran', compact('pelanggarans', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate'));
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
    public function laporanPresensi(Request $request)
    {
        // 1. Ambil parameter filter dari request (jika tidak ada, default ke harian hari ini)
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));

        // 2. Mulai query dasar untuk mengambil semua siswa beserta relasi presensinya
        $querySiswa = Siswa::orderBy('nama_siswa', 'asc');

        // 3. Tentukan rentang tanggal berdasarkan tipe filter menggunakan Carbon
        if ($filter == 'harian') {
            $startDate = Carbon::parse($tanggalInput)->startOfDay();
            $endDate = Carbon::parse($tanggalInput)->endOfDay();
        } elseif ($filter == 'mingguan') {
            // Mengambil awal dan akhir minggu dari tanggal yang dipilih siswa
            $startDate = Carbon::parse($tanggalInput)->startOfWeek();
            $endDate = Carbon::parse($tanggalInput)->endOfWeek();
        } elseif ($filter == 'bulanan') {
            // Mengambil awal dan akhir bulan dari input bulan (Format: Y-m)
            $startDate = Carbon::parse($bulanInput.'-01')->startOfMonth();
            $endDate = Carbon::parse($bulanInput.'-01')->endOfMonth();
        }

        // 4. Ambil data siswa beserta riwayat presensinya yang sudah disaring berdasarkan rentang tanggal
        $siswas = $querySiswa->with(['presensi' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
        }])->get();

        // 5. Kirim semua variabel ke tampilan blade
        return view('bk.laporan-presensi', compact('siswas', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate'));
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

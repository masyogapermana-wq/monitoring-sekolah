<?php

namespace App\Http\Controllers;

use App\Models\DataPelanggaran;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
// Tambahan Library untuk PDF
use Barryvdh\DomPDF\Facade\Pdf;

class BkController extends Controller
{
    // =================================================================
    // 1. FUNGSI DASHBOARD
    // =================================================================
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        // Menghitung Hadir dan Terlambat
        $totalHadir = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $totalTerlambat = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Terlambat')->count();

        // LOGIKA BARU: Menghitung Alpa
        $totalSiswa = Siswa::count(); // Ambil total semua siswa di database
        $siswaSudahAbsen = Presensi::whereDate('tanggal', $hariIni)->count(); // Berapa yang sudah absen hari ini (hadir + telat)

        $totalAlpa = $totalSiswa - $siswaSudahAbsen;

        // Mencegah angka minus (berjaga-jaga jika ada siswa absen 2 kali)
        $totalAlpa = $totalAlpa < 0 ? 0 : $totalAlpa;

        $pelanggarans = DataPelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->latest()
            ->take(5)
            ->get();

        // Jangan lupa tambahkan 'totalAlpa' ke dalam compact()
        return view('bk.dashboard', compact('totalHadir', 'totalTerlambat', 'totalAlpa', 'pelanggarans'));
    }

    // =================================================================
    // 2. FUNGSI LAPORAN PELANGGARAN BK (Filter Tanggal & Kelas)
    // =================================================================
    public function laporanPelanggaran(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());

        // FIX: bulan diambil dari tanggalInput yang dipilih user,
        // bukan selalu fallback ke bulan hari ini.
        $bulanInput = $request->get('bulan', Carbon::parse($tanggalInput)->format('Y-m'));

        $kelasPilihan = $request->get('kelas', 'semua');
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        $query = DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest();

        if ($kelasPilihan != 'semua') {
            $query->whereHas('siswa', function ($q) use ($kelasPilihan) {
                $q->where('kelas', $kelasPilihan);
            });
        }

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

        $query->whereBetween('tanggal_kejadian', [$startDate->toDateString(), $endDate->toDateString()]);
        $pelanggarans = $query->get();

        return view('bk.laporan-pelanggaran', compact('pelanggarans', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate', 'daftarKelas', 'kelasPilihan'));
    }

    // =================================================================
    // 3. FUNGSI CETAK LAPORAN PELANGGARAN (Versi Biasa / Old)
    // =================================================================
    public function cetakLaporan(Request $request)
    {
        $dataPelanggaran = DataPelanggaran::with('siswa', 'jenisPelanggaran')->get();
        return view('bk.cetak-laporan', compact('dataPelanggaran'));
    }

    // =================================================================
    // 4. FUNGSI LAPORAN PRESENSI
    // =================================================================
    public function laporanPresensi(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::parse($tanggalInput)->format('Y-m'));
        $kelasPilihan = $request->get('kelas', 'semua');
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        $querySiswa = Siswa::orderBy('nama_siswa', 'asc');

        if ($kelasPilihan != 'semua') {
            $querySiswa->where('kelas', $kelasPilihan);
        }

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

        $siswas = $querySiswa->with(['presensi' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
        }])->get();

        return view('bk.laporan-presensi', compact('siswas', 'filter', 'tanggalInput', 'bulanInput', 'startDate', 'endDate', 'daftarKelas', 'kelasPilihan'));
    }

    // =================================================================
    // 5. FUNGSI CETAK LAPORAN PRESENSI (Versi Biasa / Old)
    // =================================================================
    public function cetakPresensi(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $query = Presensi::with('siswa')->orderBy('tanggal', 'desc');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $presensis = $query->get();
        return view('bk.cetak-presensi', compact('presensis'));
    }

    // =================================================================
    // 6. FUNGSI CETAK PDF LAPORAN PRESENSI (Dengan Logika Alpa)
    // =================================================================
    public function cetakPdf(Request $request)
    {
        $filter = $request->input('filter', 'harian');
        $tanggalInput = $request->input('tanggal', Carbon::today()->toDateString());
        $kelasPilihan = $request->input('kelas', 'semua');

        // Tentukan rentang tanggal sesuai filter, sama seperti di laporanPresensi()
        if ($filter == 'harian') {
            $startDate = Carbon::parse($tanggalInput)->startOfDay();
            $endDate = Carbon::parse($tanggalInput)->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = Carbon::parse($tanggalInput)->startOfWeek();
            $endDate = Carbon::parse($tanggalInput)->endOfWeek();
        } elseif ($filter == 'bulanan') {
            $startDate = Carbon::parse($tanggalInput)->startOfMonth();
            $endDate = Carbon::parse($tanggalInput)->endOfMonth();
        }

        // Query siswa, filter kelas kalau dipilih spesifik
        $querySiswa = Siswa::orderBy('kelas', 'asc')->orderBy('nama_siswa', 'asc');
        if ($kelasPilihan != 'semua') {
            $querySiswa->where('kelas', $kelasPilihan);
        }
        $siswas = $querySiswa->get();

        // Ambil semua presensi dalam rentang tanggal, dikelompokkan per siswa
        // (satu siswa bisa punya beberapa baris presensi kalau mingguan/bulanan)
        $presensis = Presensi::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->groupBy('siswa_id');

        $laporan = [];

        foreach ($siswas as $siswa) {
            $dataSiswa = $presensis->get($siswa->id);

            if ($dataSiswa && $dataSiswa->count() > 0) {
                // Kalau harian, biasanya cuma ada 1 baris. Kalau mingguan/bulanan,
                // tampilkan tiap tanggal sebagai baris terpisah.
                foreach ($dataSiswa as $item) {
                    $laporan[] = [
                        'nis' => $siswa->nis,
                        'nama_siswa' => $siswa->nama_siswa,
                        'kelas' => $siswa->kelas,
                        'tanggal' => $item->tanggal,
                        'jam_masuk' => $item->jam_masuk ?? '-',
                        'status' => $item->status ?? 'Hadir',
                    ];
                }
            } else {
                // Siswa sama sekali tidak absen di rentang ini -> Alpa
                // (untuk mingguan/bulanan, cukup 1 baris ringkasan "Alpa")
                $isMinggu = $filter == 'harian' && Carbon::parse($tanggalInput)->isSunday();
                $laporan[] = [
                    'nis' => $siswa->nis,
                    'nama_siswa' => $siswa->nama_siswa,
                    'kelas' => $siswa->kelas,
                    'tanggal' => $tanggalInput,
                    'jam_masuk' => '-',
                    'status' => $isMinggu ? 'Libur' : 'Alpa',
                ];
            }
        }

        $pdf = Pdf::loadView('bk.pdf-presensi', compact('laporan', 'tanggalInput', 'filter', 'startDate', 'endDate'));
        return $pdf->stream('Laporan_Presensi_' . $tanggalInput . '.pdf');
    }

    // =================================================================
    // 7. FUNGSI CETAK PDF LAPORAN PELANGGARAN (Sudah support bulan spesifik)
    // =================================================================
    public function cetakPelanggaranPdf(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());

        // FIX: bulan diambil dari tanggalInput yang dipilih user,
        // bukan selalu fallback ke bulan hari ini.
        $bulanInput = $request->get('bulan', Carbon::parse($tanggalInput)->format('Y-m'));

        $kelasPilihan = $request->get('kelas', 'semua');

        $query = DataPelanggaran::with(['siswa', 'jenisPelanggaran'])->latest();

        if ($kelasPilihan != 'semua') {
            $query->whereHas('siswa', function ($q) use ($kelasPilihan) {
                $q->where('kelas', $kelasPilihan);
            });
        }

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

        $query->whereBetween('tanggal_kejadian', [$startDate->toDateString(), $endDate->toDateString()]);

        $pelanggarans = $query->get();

        $pdf = Pdf::loadView('bk.pdf_pelanggaran', compact('pelanggarans', 'tanggalInput', 'filter', 'startDate', 'endDate'));
        return $pdf->stream('Laporan_Pelanggaran_'.$tanggalInput.'.pdf');
    }
}

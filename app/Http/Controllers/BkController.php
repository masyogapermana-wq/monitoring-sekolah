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
        $totalHadir = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $totalTerlambat = Presensi::whereDate('tanggal', $hariIni)->where('status', 'Terlambat')->count();

        $pelanggarans = DataPelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->latest()
            ->take(5)
            ->get();

        return view('bk.dashboard', compact('totalHadir', 'totalTerlambat', 'pelanggarans'));
    }

    // =================================================================
    // 2. FUNGSI LAPORAN PELANGGARAN BK (Filter Tanggal & Kelas)
    // =================================================================
    public function laporanPelanggaran(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));
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
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));
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
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $hariIni = Carbon::parse($tanggal);
        $isMinggu = $hariIni->isSunday();

        $siswas = Siswa::orderBy('kelas', 'asc')->orderBy('nama_siswa', 'asc')->get();
        $presensis = Presensi::whereDate('tanggal', $tanggal)->get()->keyBy('siswa_id');

        $laporan = [];

        foreach ($siswas as $siswa) {
            if (isset($presensis[$siswa->id])) {
                $status = $presensis[$siswa->id]->status ?? 'Hadir';
                $jam_masuk = $presensis[$siswa->id]->jam_masuk;
            } else {
                $status = $isMinggu ? 'Libur' : 'Alpa';
                $jam_masuk = '-';
            }

            $laporan[] = [
                'nis' => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas,
                'jam_masuk' => $jam_masuk,
                'status' => $status
            ];
        }

        $pdf = Pdf::loadView('bk.pdf_laporan', compact('laporan', 'tanggal'));
        return $pdf->stream('Laporan_Presensi_'.$tanggal.'.pdf');
    }

    // =================================================================
    // 7. FUNGSI CETAK PDF LAPORAN PELANGGARAN (YANG SEMPAT HILANG)
    // =================================================================
    public function cetakPelanggaranPdf(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $tanggalInput = $request->get('tanggal', Carbon::today()->toDateString());
        $bulanInput = $request->get('bulan', Carbon::today()->format('Y-m'));
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

        $pdf = Pdf::loadView('bk.pdf_pelanggaran', compact('pelanggarans', 'tanggalInput', 'filter'));
        return $pdf->stream('Laporan_Pelanggaran_'.$tanggalInput.'.pdf');
    }
}

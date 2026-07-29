<?php

namespace App\Http\Controllers;

use App\Models\DataPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PiketController extends Controller
{
    // 1. Halaman Depan / Dashboard Guru Piket
    public function index(Request $request)
    {
        $hariIni = \Carbon\Carbon::today()->toDateString();

        // Tangkap kelas yang dipilih dari URL (jika ada)
        $kelasPilihan = $request->get('kelas', 'semua');

        // Ambil daftar kelas yang unik untuk menu dropdown
        $daftarKelas = \App\Models\Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        // 🔥 PERBAIKAN 1: Gunakan created_at untuk pencarian tanggal yang 100% akurat dari sistem
        $query = \App\Models\Presensi::with('siswa')
            ->whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'desc');

        // Jika guru milih kelas tertentu (bukan 'semua'), filter datanya!
        if ($kelasPilihan != 'semua') {
            $query->whereHas('siswa', function ($q) use ($kelasPilihan) {
                $q->where('kelas', $kelasPilihan);
            });
        }

        // Jalankan query-nya untuk tabel
        $presensis = $query->get();

        // =========================================================================
        // TAMBAHAN BARU: Menghitung angka untuk kartu statistik Guru Piket
        // =========================================================================
        $totalMasuk = \App\Models\Presensi::whereDate('created_at', $hariIni)->count();

        // Menghitung yang tepat waktu / hadir (sesuaikan dengan string status di database-mu)
        $totalTepatWaktu = \App\Models\Presensi::whereDate('created_at', $hariIni)
            ->whereIn('status', ['Hadir', 'Tepat Waktu'])
            ->count();

        $totalTerlambat = \App\Models\Presensi::whereDate('created_at', $hariIni)
            ->where('status', 'Terlambat')
            ->count();

        return view('piket.dashboard', compact(
            'presensis',
            'daftarKelas',
            'kelasPilihan',
            'totalMasuk',
            'totalTepatWaktu',
            'totalTerlambat'
        ));
    }

    // 2. Halaman Scanner Kamera QR
    public function scan()
    {
        return view('piket.scan');
    }

    // 3. Halaman Presensi Manual
    public function manual()
    {
        // Mengambil data siswa untuk dropdown form manual
        $siswas = Siswa::orderBy('nama_siswa', 'asc')->get();
        return view('piket.manual', compact('siswas'));
    }

    // 4. Proses Menyimpan Absen (Dipakai barengan oleh Scanner & Input Manual)
    public function simpanPresensi(Request $request)
    {
        try {
            $request->validate([
                'nis' => 'required',
            ]);

            // Cek apakah NIS siswa terdaftar di database
            $siswa = Siswa::where('nis', $request->nis)->first();

            if (! $siswa) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Siswa dengan NIS tersebut tidak ditemukan!',
                    ]);
                }
                return back()->with('error', 'Siswa dengan NIS tersebut tidak ditemukan!');
            }

            $hariIni = Carbon::today()->toDateString();
            $jamSekarang = Carbon::now()->toTimeString();

            // Cek apakah siswa sudah absen hari ini
            $sudahAbsen = Presensi::where('siswa_id', $siswa->id)
                ->whereDate('created_at', $hariIni)
                ->first();

            if ($sudahAbsen) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Siswa ini sudah melakukan presensi hari ini!',
                    ]);
                }
                return back()->with('error', 'Siswa ini sudah melakukan presensi hari ini!');
            }

            // Menentukan status kehadiran dari form manual atau default Hadir
            $statusKehadiran = $request->status ?? 'Hadir';

            // MENGAMBIL VARIABEL WAKTU DARI DATABASE
            $pengaturanSistem = Pengaturan::first();
            $mulaiHadir = $pengaturanSistem->mulai_hadir ?? '06:00:00';
            $batasHadir = $pengaturanSistem->batas_hadir ?? '07:30:00';
            $batasTerlambat = $pengaturanSistem->batas_terlambat ?? '08:00:00';
            $batasAlpa = $pengaturanSistem->batas_alpa ?? '15:00:00';

            // LOGIKA WAKTU HANYA BERJALAN JIKA STATUS AWAL ADALAH 'Hadir' (Misal dari Scanner QR)
            if ($statusKehadiran == 'Hadir') {

                // Cek apakah di luar jam operasional
                if ($jamSekarang < $mulaiHadir || $jamSekarang > $batasAlpa) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Sistem Presensi Ditutup! Waktu absensi pukul ' . substr($mulaiHadir,0,5) . ' s/d ' . substr($batasAlpa,0,5),
                        ]);
                    }
                    return back()->with('error', 'Sistem Presensi Ditutup! Waktu absensi pukul ' . substr($mulaiHadir,0,5) . ' s/d ' . substr($batasAlpa,0,5));
                }

                // Cek status berdasarkan jam
                if ($jamSekarang >= $mulaiHadir && $jamSekarang <= $batasHadir) {
                    $statusKehadiran = 'Hadir';
                } elseif ($jamSekarang > $batasHadir && $jamSekarang <= $batasTerlambat) {
                    $statusKehadiran = 'Terlambat';
                } else {
                    $statusKehadiran = 'Alpha'; // Memakai ejaan Alpha agar tidak ditolak database
                }
            }

            // Mencegah error NULL: Selalu kirimkan jam sekarang meskipun statusnya Sakit/Izin/Alpha
            $jamMasukSimpan = $jamSekarang;
            $keterangan = $request->keterangan ?? null;

            // Simpan data ke tabel presensi
            Presensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $jamMasukSimpan,
                'status' => $statusKehadiran,
                'keterangan' => $keterangan
            ]);

            // Respons untuk Scanner QR
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'nama' => $siswa->nama_siswa,
                    'jam' => Carbon::now()->format('H:i'),
                    'status_kehadiran' => $statusKehadiran,
                ]);
            }

            // Respons untuk Form Manual
            return back()->with('success', 'Data presensi manual berhasil disimpan!');

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    // 5. Halaman Form Input Pelanggaran Manual
    public function inputPelanggaran()
    {
        $siswas = Siswa::orderBy('nama_siswa', 'asc')->get();
        $jenisPelanggaran = JenisPelanggaran::orderBy('nama_pelanggaran', 'asc')->get();

        // Tambahan: Mengambil data sanksi dari database
        $sanksiEdukatifs = \App\Models\SanksiEdukatif::orderBy('nama_sanksi', 'asc')->get();

        // Jangan lupa tambahkan 'sanksiEdukatifs' ke dalam compact
        return view('piket.input-pelanggaran', compact('siswas', 'jenisPelanggaran', 'sanksiEdukatifs'));
    }

    // 6. Menyimpan Data Pelanggaran Siswa
    public function storePelanggaran(Request $request)
    {
        // 🔥 PERBAIKAN 2: Ubah validasi 'tanggal_kejadian' menjadi 'tanggal' agar cocok dengan HTML Blade
        $request->validate([
            'nis' => 'required',
            'jenis_pelanggaran_id' => 'required',
            'sanksi' => 'required',
            'tanggal' => 'required',
        ]);

        $siswa = Siswa::where('nis', $request->nis)->first();

        if (! $siswa) {
            return back()->withErrors(['Siswa dengan NIS tersebut tidak ditemukan di database!'])->withInput();
        }

        DataPelanggaran::create([
            'siswa_id' => $siswa->id,
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'sanksi' => $request->sanksi,
            'tanggal_kejadian' => $request->tanggal, // 🔥 Mengambil nilai dari input name="tanggal"
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Data pelanggaran siswa berhasil dicatat!');
    }

    // 7. Melayani pencarian NIS otomatis di form pelanggaran
    public function cekSiswa(Request $request)
    {
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $siswa->id,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas,
            ],
        ]);
    }

    // 8. Proses Update Status Kehadiran
    public function updatePresensi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $presensi = Presensi::findOrFail($id);

        $presensi->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status kehadiran berhasil diperbarui!');
    }
}

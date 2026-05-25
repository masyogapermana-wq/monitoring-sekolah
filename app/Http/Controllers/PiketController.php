<?php

namespace App\Http\Controllers;

use App\Models\DataPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PiketController extends Controller
{
    // 1. Halaman Depan / Dashboard Guru Piket
    public function index()
    {
        return view('piket.dashboard');
    }

    // 2. Halaman Scanner Kamera QR
    public function scan()
    {
        return view('piket.scan');
    }

    // 3. Halaman Presensi Manual (YANG BIKIN ERROR TADI SEKARANG UDAH ADA)
    public function manual()
    {
        return view('piket.manual');
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Siswa dengan NIS tersebut tidak ditemukan!',
                ]);
            }

            $hariIni = Carbon::today()->toDateString();
            $jamSekarang = Carbon::now()->toTimeString();

            // Cek apakah siswa sudah absen hari ini
            $sudahAbsen = Presensi::where('siswa_id', $siswa->id)
                ->whereDate('created_at', $hariIni)
                ->first();

            if ($sudahAbsen) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Siswa ini sudah melakukan presensi hari ini!',
                ]);
            }

            // Tentukan Status Kehadiran (Batas telat contoh jam 07:15)
            $statusKehadiran = 'Hadir';
            if ($jamSekarang > '07:15:00') {
                $statusKehadiran = 'Terlambat';
            }

            // Simpan data ke tabel presensi
            Presensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $jamSekarang,   // <--- NAH INI DIA LENGKAPNYA!
                'status' => $statusKehadiran,
            ]);

            return response()->json([
                'status' => 'success',
                'nama' => $siswa->nama_siswa,
                'jam' => Carbon::now()->format('H:i'),
                'status_kehadiran' => $statusKehadiran,
            ]);

        } catch (\Exception $e) {
            // INI JURUS DETEKTIFNYA: Menampilkan error database asli ke layar
            return response()->json([
                'status' => 'error',
                'message' => 'ERROR DATABASE: '.$e->getMessage(),
            ]);
        }
    }

    // 5. Halaman Form Input Pelanggaran Manual (Nama fungsi disesuaikan dengan web.php lu)
    public function inputPelanggaran()
    {
        // Ambil data siswa
        $siswas = Siswa::orderBy('nama_siswa', 'asc')->get();

        // SAKTI: Variabel dihilangkan huruf 's' di belakang agar COCOK dengan blade lu ($jenisPelanggaran)
        $jenisPelanggaran = JenisPelanggaran::orderBy('nama_pelanggaran', 'asc')->get();

        // Panggil nama file view lu yang asli: input-pelanggaran
        return view('piket.input-pelanggaran', compact('siswas', 'jenisPelanggaran'));
    }

    // 6. Menyimpan Data Pelanggaran Siswa
    public function storePelanggaran(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'jenis_pelanggaran_id' => 'required',
            'sanksi' => 'required',
            'tanggal_kejadian' => 'required',
        ]);

        DataPelanggaran::create([
            'siswa_id' => $request->siswa_id,
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'sanksi' => $request->sanksi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
        ]);

        return back()->with('success', 'Data pelanggaran siswa berhasil dicatat!');
    }
}

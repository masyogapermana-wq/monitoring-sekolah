<?php

namespace App\Http\Controllers;

use App\Models\DataPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\Siswa; // <-- Udah gua tambahin panggil model Pengaturan di sini
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

        // Query dasar presensi hari ini
        $query = \App\Models\Presensi::with('siswa')
            ->whereDate('tanggal', $hariIni)
            ->orderBy('jam_masuk', 'desc');

        // Jika guru milih kelas tertentu (bukan 'semua'), filter datanya!
        if ($kelasPilihan != 'semua') {
            $query->whereHas('siswa', function ($q) use ($kelasPilihan) {
                $q->where('kelas', $kelasPilihan);
            });
        }

        // Jalankan query-nya
        $presensis = $query->get();

        return view('piket.dashboard', compact('presensis', 'daftarKelas', 'kelasPilihan'));
    }

    // 2. Halaman Scanner Kamera QR
    public function scan()
    {
        return view('piket.scan');
    }

    // 3. Halaman Presensi Manual
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

            // Tentukan Status Kehadiran (Hadir, Sakit, Izin)
            // Kalau request status kosong (misal dari Scanner), otomatis anggap 'Hadir'
            $statusKehadiran = $request->status ?? 'Hadir';

            // 🔥 LOGIKA BARU: AMBIL JAM MASUK DARI DATABASE PENGATURAN ADMIN
            // Jika belum ada data di tabel pengaturan, sistem otomatis pakai jam 07:00:00
            $pengaturanSistem = Pengaturan::first();
            $batasJamMasuk = $pengaturanSistem ? $pengaturanSistem->jam_masuk : '07:30:00';

            // Cek keterlambatan HANYA JIKA statusnya 'Hadir'
            if ($statusKehadiran == 'Hadir') {
                // Membandingkan waktu scan dengan batas jam masuk dari database
                if ($jamSekarang > $batasJamMasuk) {
                    $statusKehadiran = 'Terlambat';
                }
            }

            // Simpan data ke tabel presensi
            Presensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $jamSekarang,
                'status' => $statusKehadiran,
            ]);

            return response()->json([
                'status' => 'success',
                'nama' => $siswa->nama_siswa,
                'jam' => Carbon::now()->format('H:i'),
                'status_kehadiran' => $statusKehadiran,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'ERROR DATABASE: '.$e->getMessage(),
            ]);
        }
    }

    // 5. Halaman Form Input Pelanggaran Manual
    public function inputPelanggaran()
    {
        // Ambil data siswa
        $siswas = Siswa::orderBy('nama_siswa', 'asc')->get();

        // SAKTI: Variabel dihilangkan huruf 's' di belakang agar COCOK dengan blade lu
        $jenisPelanggaran = JenisPelanggaran::orderBy('nama_pelanggaran', 'asc')->get();

        // Panggil nama file view lu yang asli
        return view('piket.input-pelanggaran', compact('siswas', 'jenisPelanggaran'));
    }

    // 6. Menyimpan Data Pelanggaran Siswa (VERSI ANTI GAGAL)
    public function storePelanggaran(Request $request)
    {
        // 1. Validasi kita ubah, sekarang yang wajib itu 'nis', bukan 'siswa_id'
        $request->validate([
            'nis' => 'required',
            'jenis_pelanggaran_id' => 'required',
            'sanksi' => 'required',
            'tanggal_kejadian' => 'required',
        ]);

        // 2. Kita cari data siswa berdasarkan NIS yang diketik di form
        $siswa = Siswa::where('nis', $request->nis)->first();

        // 3. Kalau ternyata NIS ngasal / nggak ada di database, tolak!
        if (! $siswa) {
            return back()->withErrors(['Siswa dengan NIS tersebut tidak ditemukan di database!'])->withInput();
        }

        // 4. Kalau ketemu, langsung simpan datanya pakai ID si Siswa
        DataPelanggaran::create([
            'siswa_id' => $siswa->id,
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'sanksi' => $request->sanksi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Data pelanggaran siswa berhasil dicatat!');
    }

    // 7. Melayani pencarian NIS otomatis di form pelanggaran
    public function cekSiswa(Request $request)
    {
        // Cari siswa berdasarkan NIS yang diketik
        $siswa = Siswa::where('nis', $request->nis)->first();

        // Kalau siswanya nggak ada di database
        if (! $siswa) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        // Kalau ada, kirim data nama dan ID-nya ke Javascript
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $siswa->id,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas,
            ],
        ]);
    }

    // 8. Proses Update Status Kehadiran (Untuk Kasus Pulang Awal/Sakit)
    public function updatePresensi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        // Cari data presensi berdasarkan ID yang diklik
        $presensi = Presensi::findOrFail($id);

        // Update statusnya saja (Jam masuknya tidak diubah agar riwayat kedatangan tetap ada)
        $presensi->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status kehadiran berhasil diperbarui!');
    }
}

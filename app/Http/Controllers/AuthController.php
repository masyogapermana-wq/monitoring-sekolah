<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function index()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba Login
        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Auth::attempt mengecek database
        if (Auth::attempt($infologin, $request->has('remember'))) {

            // PENTING: Mencegah serangan pembajakan sesi
            $request->session()->regenerate();

            // Mengambil data user yang sedang login
            $user = Auth::user();

            // LOGIKA PENGARAHAN HALAMAN MUTLAK
            // Pastikan tulisan URL di dalam redirect('/...') sesuai dengan Route::get lu di web.php
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role == 'guru_bk' || $user->role == 'bk') {
                return redirect('/bk/dashboard');
            } elseif ($user->role == 'piket' || $user->role == 'guru_piket') {
                return redirect('/piket/dashboard');
            }

            // FITUR BANTUAN DETEKSI ROLE (Jika role salah/tidak cocok)
            // Sistem akan memaksa logout dan memberitahu lu apa nama role aslinya
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Role akun tidak cocok dengan kodingan. Role asli di database adalah: ' . $user->role
            ]);
        }

        // ====================================================================
        // JEBAKAN DEBUGGING
        // Jika layar putih dan tulisan ini muncul, FIX 100% PASSWORD SALAH / BELUM DI-HASH
        // ====================================================================
        dd('LOGIN GAGAL! Layar putih ini membuktikan kalau Email atau Password Guru BK salah, atau password di database belum di-hash secara benar.');

        // Jika email/password salah
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Logout
    public function logout(Request $request)
    {
        // 1. Menghapus sesi login secara resmi
        Auth::logout();

        // 2. Membersihkan sisa memori sesi agar aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Mengarahkan paksa kembali ke halaman utama (URL: /)
        return redirect('/');
    }
}

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

        // Auth::attempt mengecek database. Parameter kedua untuk mengecek checkbox "Ingat Saya"
        if (Auth::attempt($infologin, $request->has('remember'))) {

            // PENTING: Mencegah serangan pembajakan sesi (Session Fixation)
            $request->session()->regenerate();

            // Kalau sukses, cek role-nya
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->role == 'piket') {
                return redirect()->intended('piket/dashboard');
            } elseif ($user->role == 'bk') {
                return redirect()->intended('bk/dashboard');
            }
        }

        // Kalau gagal, kembalikan ke halaman form login bawa pesan error
        return back()->withErrors([
            'email' => 'Email atau Kata Sandi yang Anda masukkan salah.',
        ])->onlyInput('email'); // Mempertahankan input email biar gak ngetik ulang
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

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

        if (Auth::attempt($infologin)) {
            // Kalau sukses, cek role-nya
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect('admin/dashboard');
            } elseif ($user->role == 'piket') {
                return redirect('piket/dashboard');
            } elseif ($user->role == 'bk') {
                return redirect('bk/dashboard');
            }
        }

        // Kalau gagal
        return redirect('/')->withErrors('Email atau Password salah')->withInput();
    }

    // 3. Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

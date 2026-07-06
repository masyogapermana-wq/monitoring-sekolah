<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles (Titik tiga untuk menangkap banyak role sekaligus)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Cek apakah jabatan user ADA DI DALAM DAFTAR role yang diizinkan
        // Misalnya $roles isinya adalah ['admin', 'bk']
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // 3. Kalau tidak ada di daftar, tendang balik dan kasih pesan error
        abort(403, 'Akses Ditolak! Anda tidak memiliki izin ke halaman ini.');
    }
}

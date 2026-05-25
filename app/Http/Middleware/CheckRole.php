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
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Cek apakah jabatannya sesuai
        // user()->role diambil dari database (admin, piket, atau bk)
        if (Auth::user()->role == $role) {
            return $next($request);
        }

        // 3. Kalau tidak sesuai, tendang balik atau kasih pesan error
        abort(403, 'Akses Ditolak! Anda tidak memiliki izin ke halaman ini.');
    }
}

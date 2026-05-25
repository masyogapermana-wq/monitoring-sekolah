<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cek role user yang sedang login
            $role = Auth::user()->role;

            if ($role == 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($role == 'piket') {
                return redirect('/piket/dashboard');
            } elseif ($role == 'bk') {
                return redirect('/bk/dashboard');
            }

            // --- LOGIKA TAMBAHAN SELESAI ---

            // Default (jaga-jaga)
            return redirect('/admin/dashboard');
            }
        }

        return $next($request);
    }
}

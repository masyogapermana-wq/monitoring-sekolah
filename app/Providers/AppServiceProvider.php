<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- TAMBAHKAN BARIS INI

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // TAMBAHKAN 3 BARIS INI UNTUK VERCEL
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}

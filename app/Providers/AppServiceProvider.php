<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 🔥 1. Tambahkan baris ini di bagian atas

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔥 2. Tambahkan logika ini untuk memaksa HTTPS jika aplikasi sedang online
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
     *
     * Jika APP_URL menggunakan skema https (contoh: ngrok),
     * paksa semua URL yang digenerate Laravel menjadi HTTPS
     * sehingga asset, redirect, dan cookie Secure bekerja dengan benar.
     */
    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
}

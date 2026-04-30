<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 1. Tambahkan ini

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
        // 2. Tambahkan kode ini agar link otomatis jadi HTTPS di Railway
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. Tambahkan 3 baris use ini di sini (di luar class)
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // 2. Masukkan kodenya ke dalam fungsi boot() bawaan Laravel
        // Pastikan tabel ada agar tidak eror saat fresh migrate
        if (Schema::hasTable('settings')) {
            $siteSettings = Setting::pluck('value', 'key')->toArray();
            View::share('siteSettings', $siteSettings);
        }
    }
}
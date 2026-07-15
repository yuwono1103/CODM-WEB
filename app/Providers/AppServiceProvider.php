<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Gabungkan pengecekan console dan pengecekan tabel menggunakan &&
        if (!app()->runningInConsole() && Schema::hasTable('settings')) {
            $siteSettings = Setting::pluck('value', 'key')->toArray();
            View::share('siteSettings', $siteSettings);
        }
    }
}
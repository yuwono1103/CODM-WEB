<?php

use App\Http\Middleware\RoleAdmin;
use App\Http\Middleware\RoleSeller;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions; // <-- Ditambahkan agar sistem pembaca error aktif

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mendaftarkan alias middleware untuk hak akses
        $middleware->alias([
            'admin' => RoleAdmin::class,
            'seller' => RoleSeller::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Biarkan kosong, yang penting blok ini wajib ada agar Laravel tidak error
    })
    ->create();
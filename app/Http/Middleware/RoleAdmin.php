<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole; // Tambahkan Enum
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan Enum UserRole::ADMIN
        if (Auth::check() && Auth::user()->role === UserRole::ADMIN) {
            
            // if (Auth::user()->is_suspended) {
            //     Auth::logout();
            //     return redirect()->route('login')->with('error', 'Akun ditangguhkan.');
            // }
            
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
    }
}
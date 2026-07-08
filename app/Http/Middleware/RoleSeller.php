<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole; // <-- Tambahkan import Enum ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        // REVISI: Ubah pengecekan string biasa menjadi Enum
        if (Auth::check() && Auth::user()->role === UserRole::SELLER) {
            
            // (Opsional) Jika Anda punya pengecekan suspend, biarkan seperti ini:
            // if (Auth::user()->is_suspended) {
            //     Auth::logout();
            //     return redirect()->route('login')->with('error', 'Akun Anda telah ditangguhkan.');
            // }
            
            return $next($request);
        }

        abort(403, 'AKSES DITOLAK. HALAMAN INI HANYA UNTUK SELLER.');
    }
}
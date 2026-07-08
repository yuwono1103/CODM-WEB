<?php

namespace App\Http\Controllers\Seller;

use App\Models\Listing;
use App\Models\User;
use App\Enums\ListingStatus; // <-- Tambahkan import Enum ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua iklan milik seller ini
        $allListings = Listing::where('user_id', $userId)->latest()->get();

        // 1. Ringkasan Statistik Utama (MENGGUNAKAN ENUM)
        $stats = [
            'total' => $allListings->count(),
            // Hitung yang aktif saja
            'aktif' => $allListings->where('status', ListingStatus::ACTIVE)->filter(fn($item) => !$item->is_expired)->count(),
            // (Opsional) Hitung yang terjual jika Anda punya enum SOLD. Jika tidak, anggap 0 dulu atau sesuaikan.
            'terjual' => $allListings->where('status', 'terjual')->count(), // Ganti ke ListingStatus::SOLD jika Anda punya
            'total_views' => $allListings->sum('view_count'), 
        ];

        // 2. Ranking 3 Iklan Terpopuler milik seller berdasarkan View
        $topListings = Listing::where('user_id', $userId)
            ->orderBy('view_count', 'desc')
            ->take(3)
            ->get();

        return view('seller.dashboard', compact('allListings', 'stats', 'topListings'));
    }
}
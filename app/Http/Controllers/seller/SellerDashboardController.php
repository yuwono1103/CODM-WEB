<?php

namespace App\Http\Controllers\Seller;

use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController
{
    public function index()
    {
        $userId = Auth::id();
        
        // Ambil semua iklan milik seller ini
        $allListings = Listing::where('user_id', $userId)->get();

        // 1. Ringkasan Statistik Utama (Menangani status Expired secara dinamis)
        $stats = [
            'total' => $allListings->count(),
            'aktif' => $allListings->where('status', 'Aktif')->filter(fn($item) => !$item->is_expired)->count(),
            'terjual' => $allListings->where('status', 'Terjual')->count(),
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
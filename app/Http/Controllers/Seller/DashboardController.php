<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller; // <-- Tambahkan ini agar class bisa extend Controller
use App\Models\Listing;
use App\Models\Escrow; // <-- Tambahkan import Model Escrow/Rekber kamu di sini
use App\Enums\ListingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller // <-- Pastikan ada 'extends Controller'
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

        // ======================================================================
        // 3. MONITORING STATUS REKBER (ESCROW) UNTUK SELLER
        // ======================================================================
        // Mengambil semua transaksi Rekber (baik yang aktif maupun selesai) yang berkaitan dengan akun milik Seller ini
        $escrowTransactions = Escrow::with(['listing', 'buyer'])
            ->whereHas('listing', function ($query) use ($userId) {
                $query->where('user_id', $userId); // Memastikan listing adalah milik seller ini
            })
            ->latest()
            ->get();

        // Kirim semua variabel ke view, termasuk 'escrowTransactions'
        return view('seller.dashboard', compact('allListings', 'stats', 'topListings', 'escrowTransactions'));
    }
}
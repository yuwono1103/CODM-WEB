<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Enums\ListingStatus; // <-- Tambahkan Enum Listing
use App\Enums\UserRole; // <-- Tambahkan Enum Role untuk cek Admin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        // 1. PERBAIKAN: Gunakan Enum ACTIVE, bukan teks 'Aktif'
        $query = Listing::where('status', ListingStatus::ACTIVE);

        // Fitur Pencarian Dinamis (Berdasarkan Judul atau List Item Koleksi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('mythic_weapon_list', 'like', "%{$search}%")
                  ->orWhere('legendary_weapon_list', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Spesifikasi Akun Terintegrasi
        $query->when($request->filled('price_min'), function ($q) use ($request) {
            return $q->where('price', '>=', $request->price_min);
        })->when($request->filled('price_max'), function ($q) use ($request) {
            return $q->where('price', '<=', $request->price_max);
        })->when($request->filled('level'), function ($q) use ($request) {
            return $q->where('level', '>=', $request->level);
        })->when($request->filled('rank_mp'), function ($q) use ($request) {
            return $q->where('rank_mp', $request->rank_mp);
        })->when($request->filled('rank_br'), function ($q) use ($request) {
            return $q->where('rank_br', $request->rank_br);
        })->when($request->filled('border_s1'), function ($q) {
            return $q->where('border_s1', true);
        })->when($request->filled('damascus'), function ($q) {
            return $q->where('damascus', true);
        })->when($request->filled('mythic_only'), function ($q) {
            return $q->where('mythic_weapon_count', '>', 0);
        })->when($request->filled('legendary_only'), function ($q) {
            return $q->where('legendary_weapon_count', '>', 0);
        });

        // Pengurutan (Sorting)
        $listings = $query->orderByRaw("
            CASE ad_type 
                WHEN 'Featured' THEN 1 
                WHEN 'Premium' THEN 2 
                WHEN 'Gratis' THEN 3 
                ELSE 4 
            END
        ")
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('marketplace.index', compact('listings'));
    }

    public function show($slug)
    {
        // 2. PERBAIKAN: Hapus filter status di sini agar Admin tetap bisa menarik data iklan yang Pending
        $listing = Listing::where('slug', $slug)->firstOrFail();

        // 3. JALUR VIP: Jika iklan belum Aktif, pastikan HANYA Admin atau Pemilik Iklan yang boleh melihatnya.
        // Jika pembeli biasa/tamu yang mencoba melihat URL ini, mereka akan diblokir (404)
        if ($listing->status !== ListingStatus::ACTIVE) {
            $isAdmin = Auth::check() && Auth::user()->role === UserRole::ADMIN;
            $isOwner = Auth::check() && Auth::id() === $listing->user_id;

            if (!$isAdmin && !$isOwner) {
                abort(404, 'Iklan tidak ditemukan atau masih dalam peninjauan Admin.');
            }
        }

        // Increment View Counter Otomatis Saat Detail Iklan Dibuka
        // (Kita tidak hitung view jika yang melihat adalah Admin/Pemilik agar statistik tidak bocor)
        if (!Auth::check() || (Auth::id() !== $listing->user_id && Auth::user()->role !== UserRole::ADMIN)) {
            $listing->increment('view_count');
        }

        return view('marketplace.show', compact('listing'));
    }
}
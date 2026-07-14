<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Enums\ListingStatus;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua iklan yang statusnya ACTIVE
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

        // 2. JALUR AMAN: Mengakomodasi case-sensitive PostgreSQL 
        // Mencari variasi penulisan 'Featured Premium', 'featured_premium', dsb.
        $featuredListings = Listing::where('status', ListingStatus::ACTIVE)
            ->where(function($q) {
                $q->whereIn('ad_type', [
                    'Featured', 'featured', 
                    'Premium', 'premium', 
                    'Featured Premium', 'featured_premium', 'featured premium'
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Pengurutan (Sorting) Katalog Utama
        // Menambahkan penanganan huruf kecil/besar di CASE statement ORDER BY
        $listings = $query->orderByRaw("
            CASE LOWER(ad_type) 
                WHEN 'featured' THEN 1 
                WHEN 'featured premium' THEN 1 
                WHEN 'featured_premium' THEN 1 
                WHEN 'premium' THEN 2 
                WHEN 'gratis' THEN 3 
                WHEN 'reguler' THEN 3 
                ELSE 4 
            END
        ")
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        // Mengirimkan kedua variabel ke view utama
        return view('marketplace.index', compact('listings', 'featuredListings'));
    }

    public function show($slug)
    {
        // 1. Ambil data iklan
        $listing = Listing::where('slug', $slug)->firstOrFail();

        // 2. JALUR VIP: Jika iklan belum Aktif, pastikan HANYA Admin atau Pemilik Iklan yang boleh melihatnya.
        if ($listing->status !== ListingStatus::ACTIVE) {
            $isAdmin = Auth::check() && Auth::user()->role === UserRole::ADMIN;
            $isOwner = Auth::check() && Auth::id() === $listing->user_id;

            if (!$isAdmin && !$isOwner) {
                abort(404, 'Iklan tidak ditemukan atau masih dalam peninjauan Admin.');
            }
        }

        // 3. Increment View Counter Otomatis Saat Detail Iklan Dibuka
        if (!Auth::check() || (Auth::id() !== $listing->user_id && Auth::user()->role !== UserRole::ADMIN)) {
            $listing->increment('view_count');
        }

        // 4. Ambil data fee rekber, kalau kosong defaultnya 3
        $rekberFee = \App\Models\Setting::where('key', 'rekber_fee_percent')->value('value') ?? 3;

        // 5. Lempar HANYA 1 KALI ke tampilan view
        return view('marketplace.show', compact('listing', 'rekberFee'));
    }
}
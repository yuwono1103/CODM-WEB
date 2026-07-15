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

        // 2. Fitur Pencarian Dinamis (Sekarang HANYA berdasarkan Judul Iklan)
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // 3. Fitur Filter Spesifikasi Dasar (Koleksi dihilangkan)
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
        });

        // 4. JALUR BARU: Mengambil Featured
        $now = now()->toDateTimeString();
        $featuredListings = Listing::where('status', ListingStatus::ACTIVE)
            ->where(function($q) use ($now) {
                $q->where(function($sub) use ($now) {
                    $sub->where('featured_status', 'approved')
                        ->where('featured_until', '>=', $now);
                })
                ->orWhereIn('ad_type', ['Featured', 'featured', 'Premium', 'premium', 'Featured Premium']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Pengurutan (Sorting) Katalog Utama
        $listings = $query->orderByRaw("
            CASE 
                WHEN featured_status = 'approved' AND featured_until >= '{$now}' THEN 1
                WHEN ad_type IN ('Featured', 'featured', 'Premium', 'premium', 'Featured Premium') THEN 1
                ELSE 2 
            END
        ")
        ->orderBy('created_at', 'desc')
        ->paginate(12);

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
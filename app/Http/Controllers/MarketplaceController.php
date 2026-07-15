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

        // 2. Fitur Pencarian Dinamis (Berdasarkan Judul)
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // 3. Fitur Filter Spesifikasi Dasar
        $query->when($request->filled('price_min'), function ($q) use ($request) {
            $q->where('price', '>=', $request->price_min);
        })->when($request->filled('price_max'), function ($q) use ($request) {
            $q->where('price', '<=', $request->price_max);
        })->when($request->filled('level'), function ($q) use ($request) {
            $q->where('level', '>=', $request->level);
        })->when($request->filled('rank_mp'), function ($q) use ($request) {
            $q->where('rank_mp', $request->rank_mp);
        })->when($request->filled('rank_br'), function ($q) use ($request) {
            $q->where('rank_br', $request->rank_br);
        });

        // --- TAMBAHAN BARU: Menangkap Filter Tipe Login (Checkbox) ---
        if ($request->filled('login_type') && is_array($request->login_type)) {
            // Catatan: Pastikan nama kolom di database kamu benar. 
            // Jika nama kolom tipe login di tabel listings adalah 'login', pakai ini:
            $query->whereIn('login', $request->login_type); 
            // (Ubah 'login' jadi 'login_type' jika nama kolom di databasemu begitu)
        }

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
        if ($request->filled('sort') && $request->sort !== 'newest') {
            // Jika user memilih filter spesifik, ABAIKAN prioritas Featured 
            // agar urutan 100% akurat berdasarkan pilihan user
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc'); // Harga Termurah
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc'); // Harga Termahal
            } elseif ($request->sort === 'views') {
                $query->orderBy('view_count', 'desc'); // View Terbanyak
            } elseif ($request->sort === 'oldest') {
                $query->orderBy('created_at', 'asc'); // Terlama
            }
        } else {
            // Default jika user tidak memilih filter ATAU memilih "Terbaru":
            // Tetap prioritaskan akun Featured di atas, lalu urutkan dari yang terbaru
            $query->orderByRaw("
                CASE 
                    WHEN featured_status = 'approved' AND featured_until >= '{$now}' THEN 1
                    WHEN ad_type IN ('Featured', 'featured', 'Premium', 'premium', 'Featured Premium') THEN 1
                    ELSE 2 
                END
            ")->orderBy('created_at', 'desc'); 
        }

        // Langkah C: --- TAMBAHAN BARU: withQueryString() ---
        // Ini WAJIB agar filter tidak reset saat user pindah ke Page 2, Page 3, dst.
        $listings = $query->paginate(12)->withQueryString();

        return view('marketplace.index', compact('listings', 'featuredListings'));
    } // <--- INI YANG TADI KELUPAAN (Tutup fungsi index)

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
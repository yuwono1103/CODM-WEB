<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Enums\ListingStatus; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{   
    public function index()
    {
        // 1. Ambil statistik
        $totalUsers = User::count();
        $totalIklan = Listing::count();
        $totalAktif = Listing::where('status', ListingStatus::ACTIVE)->count();

        // 2. Ambil antrean iklan yang berstatus PENDING (Iklan Baru)
        $pendingListings = Listing::where('status', ListingStatus::PENDING)
                                  ->with('user')
                                  ->latest()
                                  ->get();

        // 3. Ambil antrean pengajuan Premium yang sedang pending
        $pendingFeatured = Listing::where('featured_status', 'pending')
                                  ->with('user')
                                  ->latest()
                                  ->get();

        // 4. PERUBAHAN: Ambil semua transaksi Rekber yang BELUM selesai
        $activeEscrows = \App\Models\Escrow::with(['buyer', 'listing.user'])
                        ->whereIn('status', ['menunggu_admin', 'diproses', 'group_dibuat'])
                        ->latest()
                        ->get();

        // 5. GABUNGKAN SEMUA VARIABEL DI SATU RETURN SAJA (Ubah ke $activeEscrows)
        return view('admin.dashboard', compact('totalUsers', 'totalIklan', 'totalAktif', 'pendingListings', 'pendingFeatured', 'activeEscrows'));
    }

    public function allListings(Request $request)
    {
        // Ambil query pencarian dan filter status
        $search = $request->input('search');
        $status = $request->input('status');

        // Query builder dasar untuk mengambil semua listing beserta data user/penjualnya
        $query = Listing::with('user')->latest();

        // Fitur Pencarian Sederhana (Judul iklan atau email/nama penjual)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Fitur Filter Status (Menggunakan Enum sesuai sistem kamu)
        if ($status) {
            $query->where('status', $status);
        }

        // Pagination: Tampilkan 10 data per halaman
        $listings = $query->paginate(10)->withQueryString();

        return view('admin.listings_index', compact('listings', 'search', 'status'));
    }

    public function adminHistory()
    {
        // SINKRONISASI: Menggunakan Enum untuk mengambil data yang ACTIVE dan REJECTED
        $listings = Listing::with('user')
            ->whereIn('status', [ListingStatus::ACTIVE, ListingStatus::REJECTED]) 
            ->latest()
            ->paginate(10);

        return view('admin.history', compact('listings'));
    }

    public function approve($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update([
            'status' => ListingStatus::ACTIVE,
            'expires_at' => now()->addDays(30) // <-- Tambahkan waktu 30 hari di sini
        ]);
        
        return back()->with('success', 'Iklan berhasil disetujui dan sekarang tayang!');
    }

    public function reject(Request $request, $id)
    {
        // Validasi alasan penolakan wajib diisi
        $request->validate([
            'review_notes' => 'required|string|min:10'
        ]);

        $listing = Listing::findOrFail($id);
        $listing->update([
            'status' => ListingStatus::REJECTED,
            'review_notes' => $request->review_notes
        ]);

        return back()->with('success', 'Iklan ditolak dan catatan telah dikirim ke penjual.');
    }
    
    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();
        
        return back()->with('success', 'Iklan berhasil dihapus secara permanen.');
    }

    // ========================================================
    // ALUR BARU: FUNGSI PERSETUJUAN & PENOLAKAN FITUR PREMIUM
    // ========================================================
    
    public function approveFeatured($id)
    {
        $listing = \App\Models\Listing::findOrFail($id);

        // Ambil waktu kedaluwarsa saat ini
        $currentExpiresAt = $listing->expires_at ? \Carbon\Carbon::parse($listing->expires_at) : null;
        
        // LOGIKA PINTAR: 
        // Jika masa tayang masih ada (di masa depan), tambahkan 7 hari ke sisa waktu tersebut.
        // Tapi jika masa tayang sudah habis (past) atau kosong, set masa tayangnya jadi 7 hari dari hari ini.
        if ($currentExpiresAt && $currentExpiresAt->isFuture()) {
            $newExpiresAt = $currentExpiresAt->addDays(7);
        } else {
            $newExpiresAt = now()->addDays(7);
        }

        // Eksekusi update
        $listing->update([
            'featured_status' => 'approved',
            'featured_until'  => now()->addDays(7), // Aktif di bagian "Lagi BU" selama 7 hari
            'expires_at'      => $newExpiresAt      // Bonus penambahan masa tayang reguler
        ]);

        return back()->with('success', 'Featured Premium diaktifkan selama 7 Hari dan bonus masa tayang ditambahkan!');
    }

    public function rejectFeatured($id)
    {
        $listing = Listing::findOrFail($id);
        
        // Kembalikan status featured ke 'none' agar seller bisa mengajukan ulang jika ada kesalahan komunikasi/pembayaran
        $listing->update([
            'featured_status' => 'none'
        ]);
        
        return back()->with('success', 'Pengajuan Premium berhasil ditolak.');
    }

    // ========================================================
    // ALUR TRANSAKSI REKBER (ESCROW) MULTI-STEP
    // ========================================================

    public function prosesEscrow($id)
    {
        $escrow = \App\Models\Escrow::findOrFail($id);
        $escrow->update(['status' => 'diproses']);
        
        return back()->with('success', 'Status: SEDANG DIPROSES. Silakan buat Grup WA sekarang.');
    }

    public function markGroupCreated($id)
    {
        $escrow = \App\Models\Escrow::findOrFail($id);
        $escrow->update(['status' => 'group_dibuat']);
        
        return back()->with('success', 'Status: GRUP WA DIBUAT. Buyer & Seller telah mendapat notifikasi.');
    }

    public function completeEscrow($id)
    {
        $escrow = \App\Models\Escrow::findOrFail($id);
        $escrow->update(['status' => 'selesai']);
        
        return back()->with('success', 'Transaksi SELESAI. Data telah dipindahkan ke riwayat.');
    }
}
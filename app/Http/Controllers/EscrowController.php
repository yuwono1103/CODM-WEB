<?php

namespace App\Http\Controllers;

use App\Models\Escrow;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EscrowController extends Controller
{
    /**
     * Proses Pengajuan Rekber oleh Buyer (Dari Modal Halaman Detail Iklan)
     */
    public function store(Request $request, $listing_id)
    {
        // Validasi input dari modal form
        $request->validate([
            'buyer_wa' => 'required|string|max:20',
            'notes' => 'nullable|string'
        ]);

        $listing = Listing::findOrFail($listing_id);
        
        // Perhitungan Fee (Sesuai dengan yang ada di show.blade.php yaitu 3%)
        $fee_percentage = 3; 
        $fee_amount = ($listing->price * $fee_percentage) / 100;
        $total_amount = $listing->price + $fee_amount;

        // Simpan data pengajuan Rekber ke Database
        Escrow::create([
            'transaction_code' => strtoupper(Str::random(8)), // Generate kode unik otomatis
            'listing_id' => $listing->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $listing->user_id,
            'buyer_wa' => $request->buyer_wa,
            'notes' => $request->notes,
            'listing_price' => $listing->price,
            'fee_percentage' => $fee_percentage,
            'fee_amount' => $fee_amount,
            'total_amount' => $total_amount,
            'status' => 'pending' // Status awal saat pertama kali diajukan
        ]);

        return back()->with('success', 'Pengajuan Rekber berhasil dikirim! Silakan tunggu instruksi Admin via WhatsApp.');
    }

    /**
     * Menampilkan Halaman Riwayat Transaksi Rekber (Sisi Admin)
     */
    public function history()
    {
        // Hanya mengambil transaksi yang statusnya 'selesai'
        $histories = Escrow::where('status', 'selesai')
            ->with(['listing', 'buyer', 'seller'])
            ->latest('completed_at') // Urutkan dari waktu selesai terbaru
            ->paginate(10); 

        return view('admin.escrow.history', compact('histories'));
    }

    /**
     * Menampilkan Detail Riwayat Transaksi Rekber (Sisi Admin)
     */
    public function show($id)
    {
        // Mengambil data spesifik beserta relasi untuk halaman detail
        $escrow = Escrow::with(['listing', 'buyer', 'seller'])->findOrFail($id);
        
        return view('admin.escrow.show', compact('escrow'));
    }
}
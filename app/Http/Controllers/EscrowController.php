<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escrow;
use App\Models\Listing;
use App\Models\Setting;
use Illuminate\Support\Str;

class EscrowController extends Controller
{
    public function store(Request $request, $listing_id)
    {
        // 1. Validasi Input Pembeli
        $request->validate([
            'buyer_wa' => 'required|string|min:10|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        // 2. Ambil data iklan dan fee rekber dari database
        $listing = Listing::findOrFail($listing_id);
        $feePercentage = Setting::where('key', 'rekber_fee_percent')->value('value') ?? 3;
        
        // 3. Kalkulasi Snapshot Keuangan (Biar aman dari perubahan masa depan)
        $feeAmount = ($listing->price * $feePercentage) / 100;
        $totalAmount = $listing->price + $feeAmount;

        // 4. Simpan Transaksi Rekber
        Escrow::create([
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'listing_id' => $listing->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $listing->user_id,
            'buyer_wa' => $request->buyer_wa,
            'notes' => $request->notes,
            'listing_price' => $listing->price,
            'fee_percentage' => $feePercentage,
            'fee_amount' => $feeAmount,
            'total_amount' => $totalAmount,
            'status' => 'menunggu_admin'
        ]);

        return back()->with('success', 'Pengajuan Rekber berhasil dikirim! Silakan tunggu Admin membuatkan Grup WhatsApp.');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    protected $fillable = [
        'transaction_code', 'listing_id', 'buyer_id', 'seller_id',
        'buyer_wa', 'notes', 'listing_price', 'fee_percentage',
        'fee_amount', 'total_amount', 'status',
        'completed_at' // <-- Tambahkan ini agar kolom completed_at bisa diisi saat transaksi selesai
    ];

    // Mengubah string datetime dari database menjadi objek Carbon agar bisa diformat menggunakan ->format() di Blade
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relasi ke Iklan
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Relasi ke Pembeli (Buyer)
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Relasi ke Penjual (Seller)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
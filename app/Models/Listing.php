<?php

namespace App\Models;

use App\Enums\ListingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => ListingStatus::class,
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
            'featured_until' => 'datetime', // Kita pakai kolom bawaan ini untuk masa aktif 30 hari!
            'featured_status' => 'string',   // TAMBAHKAN INI: untuk status 'none', 'pending', 'approved'
            'expired_at' => 'datetime',
            'reserved_at' => 'datetime',
            'sold_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // REVISI: Relasi agar bisa mengambil data pembayaran dari listing
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // REVISI: Relasi agar bisa mengambil data laporan pelanggaran (Polymorphic)
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
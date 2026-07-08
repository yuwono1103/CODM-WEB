<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Standar Laravel modern untuk casting
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'last_login' => 'datetime',
        ];
    }

    // Relasi ke Listing
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    // Relasi ke Payment
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Laporan yang DIBUAT oleh user ini
    public function reportsSubmitted(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    // Laporan yang DITANGANI oleh user ini (Admin)
    public function reportsHandled(): HasMany
    {
        return $this->hasMany(Report::class, 'handled_by');
    }

    // Jika user ini dilaporkan oleh orang lain (Polymorphic)
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
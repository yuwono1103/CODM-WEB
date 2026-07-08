<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    // Tidak memakai SoftDeletes sesuai arsitektur kita
    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'array', // Men-decode JSON properties secara otomatis
    ];

    public function performedBy(): MorphTo
    {
        return $this->morphTo();
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
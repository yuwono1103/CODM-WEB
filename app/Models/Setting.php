<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'is_public' => 'boolean',
        'autoload' => 'boolean',
    ];

    // Cukup gunakan fillable saja
    protected $fillable = ['key', 'value', 'is_public', 'autoload']; 
}
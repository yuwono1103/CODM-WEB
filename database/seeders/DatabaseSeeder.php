<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole; // <-- Panggil Enum Role Anda jika menggunakan Enum
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin Utama otomatis (Tanpa kolom 'name')
        User::updateOrCreate(
            ['email' => 'admin@marketplace.com'], 
            [
                'username' => 'admin_utama',
                'password' => Hash::make('password123'), 
                'role' => 'admin', // Mengikuti string 'admin' sesuai log error Anda
                'email_verified_at' => now(),
            ]
        );

        // Membuat 1 akun Seller contoh untuk testing (Tanpa kolom 'name')
        User::updateOrCreate(
            ['email' => 'seller@marketplace.com'],
            [
                'username' => 'seller_test',
                'password' => Hash::make('password123'),
                'role' => 'seller', 
                'email_verified_at' => now(),
            ]
        );
    }
}
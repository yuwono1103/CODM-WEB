<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun Admin Utama otomatis
        User::updateOrCreate(
            ['email' => 'admin@marketplace.com'], 
            [
                'username' => 'admin_utama',
                'password' => Hash::make('password123'), 
                'role' => 'admin', 
                'email_verified_at' => now(),
            ]
        );

        // 2. Membuat 1 akun Seller contoh untuk testing
        User::updateOrCreate(
            ['email' => 'seller@marketplace.com'],
            [
                'username' => 'seller_test',
                'password' => Hash::make('password123'),
                'role' => 'seller', 
                'email_verified_at' => now(),
            ]
        );

        // 3. Menambahkan Data Pengaturan Default Web (Dynamic Settings)
        $settings = [
            ['key' => 'wa_admin', 'value' => '6281234567890'],
            ['key' => 'bank_name', 'value' => 'BCA'],
            ['key' => 'bank_account', 'value' => '1234567890'],
            ['key' => 'bank_owner', 'value' => 'Admin CODM Market'],
            ['key' => 'rekber_fee_percent', 'value' => '3'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], 
                ['value' => $setting['value']]
            );
        }
    }
}
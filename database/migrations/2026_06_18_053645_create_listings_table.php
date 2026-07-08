<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('ign');
            $table->string('uid')->unique();
            $table->string('whatsapp');
            $table->decimal('price', 12, 2);
            $table->integer('level');
            $table->string('rank_mp');
            $table->string('rank_br');
            $table->enum('login_type', ['Garena', 'Facebook']);
            $table->integer('cp_remaining')->default(0);
            $table->boolean('border_s1')->default(false);
            $table->boolean('damascus')->default(false);

            // 6 COUNT KOLEKSI VALID SIFATNYA (CODM VALIDATED)
            $table->integer('mythic_weapon_count')->default(0);
            $table->integer('prestige_weapon_count')->default(0);
            $table->integer('legendary_weapon_count')->default(0);
            $table->integer('mythic_character_count')->default(0);
            $table->integer('legendary_character_count')->default(0);
            $table->integer('legendary_vehicle_count')->default(0);

            // SCREENSHOTS
            $table->string('thumbnail');
            $table->string('lobby_image');
            $table->string('weapon_image');
            $table->string('character_image');
            $table->string('vehicle_image');

            // AD MANAGEMENT & LIFECYCLE
            $table->enum('status', ['Draft', 'Pending Review', 'Aktif', 'Ditolak', 'Terjual', 'Expired'])->default('Pending Review');
            $table->string('ad_type')->default('Gratis');
            $table->integer('view_count')->default(0);
            
            // PENYESUAIAN WAKTU
            $table->timestamp('expires_at')->nullable(); // Diisi hanya saat admin menyetujui iklan
            $table->timestamp('featured_until')->nullable(); // Persiapan Fitur Premium Featured Listing
            
            $table->timestamps(); // Menggenerate otomatis created_at dan updated_at
            $table->softDeletes(); // Fitur soft delete bawaan laravel
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
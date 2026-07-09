<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // Contoh: TRX-123456
            
            // Relasi ke tabel lain
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            
            // Data Input Buyer
            $table->string('buyer_wa');
            $table->text('notes')->nullable();
            
            // Snapshot Keuangan (disimpan mati agar tidak berubah kalau fee admin berubah)
            $table->integer('listing_price');
            $table->decimal('fee_percentage', 5, 2); // Misal 3.00
            $table->integer('fee_amount');           // Hasil dari price * percentage
            $table->integer('total_amount');         // price + fee_amount
            
            // Status Rekber
            $table->string('status')->default('menunggu_admin'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrows');
    }
};

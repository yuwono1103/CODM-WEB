<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->nullable()->comment('Format rapi untuk tagihan/PDF pengguna');
            $table->string('payment_code')->unique()->comment('Kode hash random untuk sistem referensi');
            $table->string('gateway_reference')->nullable()->unique();
            
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); 
            $table->foreignId('listing_id')->nullable()->constrained('listings')->nullOnDelete(); 
            
            $table->string('payment_category')->comment('featured, premium, rekber, commission, refund'); 
            $table->string('payment_method');
            
            $table->string('currency', 3)->default('IDR');
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->json('meta')->nullable();
            
            $table->string('proof_image')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); 

            // Indexing Optimal: Untuk Dashboard Penjualan (Tidak over-indexing)
            $table->index(['status', 'payment_category', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
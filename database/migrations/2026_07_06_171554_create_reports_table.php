<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Menggunakan helper morphs (Otomatis membuat reportable_type, reportable_id, dan index-nya)
            $table->morphs('reportable'); 
            
            $table->string('category'); 
            $table->text('reason');
            $table->json('evidences')->nullable()->comment('Menampung array bukti foto/dokumen'); 
            
            $table->string('status')->default('pending');
            
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); 

            // Indexing Optimal: Status dan Kategori cukup di-composite untuk tabel filter admin
            $table->index(['status', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
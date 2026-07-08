<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // Hanya kolom esensial yang belum ada di tabel utama Anda
            $table->integer('sort_order')->default(0)->after('price'); 
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamp('published_at')->nullable();
            $table->text('review_notes')->nullable();

            // Indexing tetap dipasang karena kolom 'status', 'is_featured', dan 'expired_at' 
            // dipastikan sudah ada di tabel utama Anda.
            $table->index(['status', 'is_featured', 'sort_order']); 
            $table->index(['status', 'expired_at']); 
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            $table->dropIndex(['status', 'is_featured', 'sort_order']);
            $table->dropIndex(['status', 'expired_at']);
            
            $table->dropColumn([
                'sort_order', 'approved_by', 'approved_at', 
                'rejected_by', 'rejected_at', 'published_at', 'review_notes'
            ]);
        });
    }
};
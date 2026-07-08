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
        Schema::table('listings', function (Blueprint $table) {
            // Cek dulu, jika kolom featured_status belum ada, baru kita buat
            if (!Schema::hasColumn('listings', 'featured_status')) {
                $table->string('featured_status')->default('none')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (Schema::hasColumn('listings', 'featured_status')) {
                $table->dropColumn('featured_status');
            }
        });
    }
};
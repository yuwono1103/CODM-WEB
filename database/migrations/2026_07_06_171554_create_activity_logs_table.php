<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Helper nullableMorphs otomatis menangani _type, _id, dan index-nya. 
            $table->nullableMorphs('performed_by'); 
            
            $table->string('event')->nullable()->comment('created, updated, deleted, login');
            $table->string('action'); // Spesifik: "Approved Listing", "Banned User"
            
            $table->nullableMorphs('target'); 
            
            $table->json('properties')->nullable();
            
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable()->index(); 
            $table->text('user_agent')->nullable();
            $table->timestamps();
            // Catatan: TIDAK ADA Soft Deletes, untuk menjaga integritas Audit Trail
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
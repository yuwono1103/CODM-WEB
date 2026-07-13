<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\EscrowController; // <-- TAMBAHAN IMPORT ESCROW CONTROLLER
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\ListingController as SellerListing;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// ==================== RUTE PUBLIK (GUEST) ====================
Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/listing/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// ==================== RUTE PROTEKSI LOGIN (AUTH) ====================
Route::middleware(['auth'])->group(function () {

    // ROUTE PENGAJUAN REKBER OLEH BUYER
    Route::post('/escrow/{listing_id}/store', [EscrowController::class, 'store'])->name('escrow.store');

    // 1. Pengecekan Akses Utama untuk Halaman /dashboard generic
    Route::get('/dashboard', function () {
        if (auth()->user()->role === \App\Enums\UserRole::ADMIN) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('seller.dashboard');
    })->name('dashboard'); 

    // 2. === GROUP AKSES: SELLER ===
    Route::middleware(['seller'])->prefix('seller')->name('seller.')->group(function () {
        // Dashboard Statistik
        Route::get('/dashboard', [SellerDashboard::class, 'index'])->name('dashboard');

        // Manajemen Profil & Password
        Route::get('/profile', [SellerDashboard::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [SellerDashboard::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [SellerDashboard::class, 'updatePassword'])->name('profile.password');

        // CRUD Iklan Akun CODM
        Route::get('/listings/create', [SellerListing::class, 'create'])->name('listings.create');
        Route::post('/listings/store', [SellerListing::class, 'store'])->name('listings.store');
        Route::get('/listings/{id}/edit', [SellerListing::class, 'edit'])->name('listings.edit');
        Route::put('/listings/{id}/update', [SellerListing::class, 'update'])->name('listings.update');
        Route::delete('/listings/{id}/destroy', [SellerListing::class, 'destroy'])->name('listings.destroy');

        // Perpanjang Iklan Biasa
        Route::post('/listings/{id}/renew', [SellerListing::class, 'renew'])->name('listings.renew');

        // ALUR BARU FEATURED: Mengajukan featured listing (Ubah status ke Pending)
        Route::post('/listings/{id}/request-featured', [SellerListing::class, 'requestFeatured'])->name('listings.featured.request');
    });

    // 3. === GROUP AKSES: ADMIN ===
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard & Moderasi Konten
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Manajemen Semua Listing
        Route::get('/listings', [AdminDashboard::class, 'allListings'])->name('listings.index');

        Route::post('/listings/{id}/approve', [AdminDashboard::class, 'approve'])->name('listings.approve');
        Route::post('/listings/{id}/reject', [AdminDashboard::class, 'reject'])->name('listings.reject');
        Route::post('/listings/{id}/approve-featured', [AdminDashboard::class, 'approveFeatured'])->name('listings.approve_featured');
        Route::post('/listings/{id}/reject-featured', [AdminDashboard::class, 'rejectFeatured'])->name('listings.reject_featured');
        Route::delete('/listings/{id}/destroy', [AdminDashboard::class, 'destroy'])->name('listings.destroy');

        // Sinkronisasi History
        Route::get('/history', [AdminDashboard::class, 'adminHistory'])->name('history');
    });

    // 4. === RUTE REKBER (KHUSUS ADMIN) ===
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::post('/dashboard/escrow/{id}/proses', [AdminDashboard::class, 'prosesEscrow'])->name('escrow.proses');
        Route::post('/dashboard/escrow/{id}/group-created', [AdminDashboard::class, 'markGroupCreated'])->name('escrow.group_created');
        Route::post('/dashboard/escrow/{id}/complete', [AdminDashboard::class, 'completeEscrow'])->name('escrow.complete');

        // ROUTE BARU UNTUK HALAMAN RIWAYAT REKBER (Disambung ke EscrowController)
        Route::get('/escrow/history', [EscrowController::class, 'history'])->name('admin.escrow.history');
        Route::get('/escrow/history/{id}', [EscrowController::class, 'show'])->name('admin.escrow.show');
    });

});

// ==================== ROUTE AUTENTIKASI (LOGIN / REGISTER) ====================
if (file_exists(base_path('routes/auth.php'))) {
    require __DIR__.'/auth.php';
} else {
    Auth::routes();
}
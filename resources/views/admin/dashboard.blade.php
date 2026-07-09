@extends('layouts/admin')

@section('content')
<div class="container-fluid py-4" style="color: #e0e0e0;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3" style="border-bottom: 1px solid #333;">
        <h3 class="fw-bold mb-3 mb-md-0 text-uppercase" style="color: #f3af22; letter-spacing: 1px;">
            Dashboard Kendali Utama
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.listings.index') }}" class="btn fw-bold text-dark text-uppercase shadow-sm" style="background-color: #f3af22; border: 1px solid #f3af22; font-size: 0.75rem; letter-spacing: 0.5px;">
                Kelola Semua Listing
            </a>
            <a href="{{ route('admin.history') }}" class="btn btn-outline-dark fw-bold text-uppercase shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                Riwayat Moderasi
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100" style="background-color: rgba(30, 32, 45, 0.95); border-radius: 12px;">
                <div class="card-body py-4">
                    <h1 class="fw-bolder text-white display-5 mb-0">{{ $totalUsers ?? 0 }}</h1>
                    <small class="text-uppercase fw-bold mt-2 d-block" style="color: #888; letter-spacing: 1px; font-size: 0.75rem;">Total User</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.listings.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 transition-transform" style="background-color: rgba(34, 37, 56, 0.95); border-radius: 12px; border: 1px solid rgba(243, 175, 34, 0.3) !important;">
                    <div class="card-body py-4">
                        <h1 class="fw-bolder display-5 mb-0" style="color: #f3af22;">{{ $totalIklan ?? 0 }}</h1>
                        <small class="text-uppercase fw-bold mt-2 d-block" style="color: #ccc; letter-spacing: 1px; font-size: 0.75rem;">Total Seluruh Iklan</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100" style="background-color: rgba(20, 50, 30, 0.85); border-radius: 12px;">
                <div class="card-body py-4">
                    <h1 class="fw-bolder text-success display-5 mb-0">{{ $totalAktif ?? 0 }}</h1>
                    <small class="text-uppercase fw-bold mt-2 d-block text-success" style="letter-spacing: 1px; font-size: 0.75rem;">Iklan Penjualan Aktif</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg overflow-visible mb-4" style="background-color: rgba(30, 32, 45, 0.95); border-radius: 12px;">
        <div class="card-header border-0 py-3" style="background-color: #111; border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <h6 class="mb-0 fw-bold text-uppercase text-white" style="letter-spacing: 1px; font-size: 0.85rem;">Persetujuan / Verifikasi Iklan Tertunda</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 200px;">
                <table class="table table-borderless table-hover align-middle mb-0 text-white">
                    <thead style="background-color: rgba(255,255,255,0.05); border-bottom: 1px solid #333;">
                        <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; color: #aaa;">
                            <th class="py-3 px-4 w-25">Penjual</th>
                            <th class="py-3 w-25">Judul Akun</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3 text-center" style="width: 200px;">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingListings ?? [] as $p)
                            <tr style="border-bottom: 1px solid #222;">
                                <td class="px-4 py-3 fw-bold">{{ $p->user->username ?? 'User' }}</td>
                                <td class="py-3 text-light">{{ $p->title }}</td>
                                <td class="py-3 fw-bold" style="color: #f3af22;">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                
                                <td class="py-3">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <a href="{{ route('marketplace.show', $p->slug) }}" target="_blank" class="btn btn-sm btn-info text-white fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">
                                            Lihat
                                        </a>
                                        <form action="{{ route('admin.listings.approve', $p->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-bold" style="font-size: 0.7rem; text-transform: uppercase;" onclick="return confirm('Setujui iklan ini tayang di marketplace?')">
                                                Approve
                                            </button>
                                        </form>
                                        <div class="dropdown m-0">
                                            <button class="btn btn-sm btn-danger fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.7rem; text-transform: uppercase;">
                                                Tolak
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end shadow-lg p-3" style="background-color: #1a1a24; border: 1px solid #444; width: 250px;">
                                                <form action="{{ route('admin.listings.reject', $p->id) }}" method="POST">
                                                    @csrf
                                                    <label class="form-label text-warning mb-1" style="font-size: 0.75rem; font-weight: bold;">Alasan Penolakan:</label>
                                                    <textarea name="review_notes" rows="2" class="form-control form-control-sm bg-dark text-white border-secondary mb-2" required placeholder="Cth: Deskripsi kurang jelas..."></textarea>
                                                    <button type="submit" class="btn btn-sm btn-danger w-100 fw-bold" style="font-size: 0.75rem;">Kirim Penolakan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">Tidak ada antrean persetujuan iklan baru saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
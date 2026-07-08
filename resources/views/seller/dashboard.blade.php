@extends('layouts.app')

@section('title', 'Dashboard Penjual')

@section('content')
<div class="container py-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="h3 fw-bold mb-1 text-white">Ops Center <span class="text-gold">Seller</span></h2>
            <p class="text-muted small mb-0">Pantau performa dagangan dan analitik akun CODM Anda.</p>
        </div>
        <a href="{{ route('seller.listings.create') }}" class="btn btn-gold px-4 py-2 shadow">
            <i class="bi bi-rocket-takeoff me-2"></i> Pasang Iklan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-dark-card border-gold text-white alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill text-gold me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger bg-dark-card border-danger text-white alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card card-gaming h-100 p-4 border-0 d-flex flex-row align-items-center gap-3">
                <div class="bg-dark p-3 rounded-3"><i class="bi bi-collection-play fs-3 text-white"></i></div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Total Iklan</div>
                    <div class="fs-2 fw-bold text-white lh-1 mt-1">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gaming h-100 p-4 border-0 d-flex flex-row align-items-center gap-3">
                <div class="bg-dark p-3 rounded-3 border border-success"><i class="bi bi-broadcast fs-3 text-success"></i></div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Iklan Aktif</div>
                    <div class="fs-2 fw-bold text-white lh-1 mt-1">{{ $stats['aktif'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gaming h-100 p-4 border-0 d-flex flex-row align-items-center gap-3">
                <div class="bg-dark p-3 rounded-3"><i class="bi bi-cart-check-fill fs-3 text-secondary"></i></div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Terjual</div>
                    <div class="fs-2 fw-bold text-white lh-1 mt-1">{{ $stats['terjual'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gaming h-100 p-4 border-0 border-gold d-flex flex-row align-items-center gap-3" style="background: linear-gradient(145deg, #1e1e1e, #2a2205);">
                <div class="bg-dark p-3 rounded-3"><i class="bi bi-eye-fill fs-3 text-gold"></i></div>
                <div>
                    <div class="text-gold small text-uppercase fw-bold letter-spacing-1">Total Views</div>
                    <div class="fs-2 fw-bold text-white lh-1 mt-1">{{ number_format($stats['total_views']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-gaming border-0 overflow-hidden">
                <div class="card-header bg-dark-card border-bottom border-dark py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-list-task text-gold me-2"></i> Inventaris Akun</h5>
                </div>
                <div class="card-body p-0">
                    @if($allListings->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">Belum ada senjata (akun) di arsenal Anda.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-gaming w-100 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Detail Akun</th>
                                        <th>Status</th>
                                        <th>Durasi tayang</th>
                                        <th>Views</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allListings as $listing)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/' . $listing->thumbnail) }}" class="rounded-3 border border-secondary" style="width: 55px; height: 55px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold text-white text-truncate" style="max-width: 200px;">{{ $listing->title }}</div>
                                                    <div class="text-gold small fw-semibold">Rp {{ number_format($listing->price, 0, ',', '.') }}</div>
                                                    
                                                    @if($listing->status === App\Enums\ListingStatus::REJECTED)
                                                        <div class="text-danger mt-1 p-1 bg-dark rounded border border-danger" style="font-size: 0.75rem; white-space: normal; max-width: 250px;">
                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                                                            <strong>Ditolak:</strong> {{ $listing->review_notes ?? 'Melanggar aturan.' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($listing->status === App\Enums\ListingStatus::PENDING)
                                                <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split"></i> Menunggu Review</span>
                                            @elseif($listing->status === App\Enums\ListingStatus::REJECTED)
                                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle"></i> Ditolak Admin</span>
                                            @elseif($listing->is_expired)
                                                <span class="badge bg-secondary px-2 py-1">Expired</span>
                                            @elseif($listing->featured_status === 'pending')
                                                <span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-star"></i> Featured Pending</span>
                                            @elseif($listing->featured_until && \Carbon\Carbon::parse($listing->featured_until)->isFuture() && $listing->featured_status === 'approved')
                                                <span class="badge bg-gold text-dark px-2 py-1" style="background-color: #f3af22;"><i class="bi bi-star-fill text-dark me-1"></i> Featured Premium</span>
                                            @elseif($listing->status === App\Enums\ListingStatus::ACTIVE)
                                                <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle"></i> Aktif Tayang</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ $listing->status->name ?? 'Terjual' }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @php
                                                $sisaHari = 0;
                                                $tipeMasaAktif = '';
            
                                                // 1. Cek Apakah Premium Aktif
                                                if ($listing->featured_until && \Carbon\Carbon::parse($listing->featured_until)->isFuture() && $listing->featured_status === 'approved') {
                                                    // Gunakan ceil() dan floatDiffInDays agar dibulatkan ke atas menjadi angka utuh (integer)
                                                    $sisaHari = (int) ceil(now()->floatDiffInDays(\Carbon\Carbon::parse($listing->featured_until)));
                                                    $tipeMasaAktif = 'Premium';
                                                } 
                                                // 2. Cek Apakah Reguler Aktif
                                                elseif ($listing->expires_at && \Carbon\Carbon::parse($listing->expires_at)->isFuture()) {
                                                    $sisaHari = (int) ceil(now()->floatDiffInDays(\Carbon\Carbon::parse($listing->expires_at)));
                                                    $tipeMasaAktif = 'Reguler';
                                                }
                                            @endphp
            
                                            @if($sisaHari > 0)
                                                <span class="text-white small fw-bold">
                                                    <i class="bi bi-clock-history me-1 {{ $tipeMasaAktif === 'Premium' ? 'text-warning' : 'text-muted' }}"></i> 
                                                    {{ $sisaHari }} Hari
                                                    <br>
                                                    <small style="font-size: 0.65rem; color: {{ $tipeMasaAktif === 'Premium' ? '#f3af22' : '#888' }};">
                                                        ({{ $tipeMasaAktif }})
                                                    </small>
                                                </span>
                                            @elseif($listing->is_expired || ($listing->expires_at && \Carbon\Carbon::parse($listing->expires_at)->isPast()))
                                                <span class="text-danger small fw-bold"><i class="bi bi-exclamation-circle me-1"></i> Waktu Habis</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="text-white small"><i class="bi bi-eye text-muted me-1"></i> {{ $listing->view_count }}</div>
                                        </td>
                                        
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                @if($listing->is_expired || ($listing->expires_at && \Carbon\Carbon::parse($listing->expires_at)->isPast()))
                                                    <form action="{{ route('seller.listings.renew', $listing->id) }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-sm btn-outline-success rounded-3"><i class="bi bi-arrow-repeat"></i> Perpanjang</button>
                                                    </form>
                                                @endif

                                                @if($listing->status === App\Enums\ListingStatus::ACTIVE && !$listing->is_expired && $listing->featured_status !== 'approved' && $listing->featured_status !== 'pending')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-gold rounded-3 btn-trigger-featured" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#featuredModal" 
                                                            data-id="{{ $listing->id }}" 
                                                            data-title="{{ $listing->title }}"
                                                            title="Ajukan ke Featured Premium">
                                                        <i class="bi bi-star"></i>
                                                    </button>
                                                @elseif($listing->featured_status === 'pending')
                                                    <button class="btn btn-sm btn-dark border-info text-info rounded-3" disabled title="Menunggu Konfirmasi Admin">
                                                        <i class="bi bi-hourglass-split"></i>
                                                    </button>
                                                @elseif($listing->featured_status === 'approved' && $listing->featured_until && \Carbon\Carbon::parse($listing->featured_until)->isFuture())
                                                    <button class="btn btn-sm btn-gold rounded-3" style="background-color: #f3af22;" disabled title="Premium Aktif">
                                                        <i class="bi bi-star-fill text-dark"></i>
                                                    </button>
                                                @endif

                                                <a href="{{ route('seller.listings.edit', $listing->id) }}" class="btn btn-sm btn-dark border-secondary rounded-3"><i class="bi bi-pencil-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-gaming border-0">
                <div class="card-header bg-dark-card border-bottom border-dark py-3">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-trophy-fill text-gold me-2"></i> Top Performa</h5>
                </div>
                <div class="card-body bg-dark-card rounded-bottom">
                    @if($topListings->isEmpty())
                        <p class="text-muted small text-center py-4">Belum ada statistik kunjungan.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($topListings as $index => $top)
                                <div class="d-flex justify-content-between align-items-center bg-dark p-3 rounded-3 border border-secondary {{ $index == 0 ? 'border-gold shadow-sm' : '' }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="fs-4 fw-bold {{ $index == 0 ? 'text-gold' : 'text-secondary' }}">#{{ $index + 1 }}</div>
                                        <div>
                                            <div class="fw-bold text-white small text-truncate" style="max-width: 150px;">{{ $top->title }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">IGN: {{ $top->ign }}</div>
                                        </div>
                                    </div>
                                    <div class="badge bg-dark border {{ $index == 0 ? 'border-gold text-gold' : 'border-secondary text-white' }} p-2 rounded-pill">
                                        <i class="bi bi-fire me-1"></i> {{ $top->view_count }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="featuredModal" tabindex="-1" aria-labelledby="featuredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content bg-dark-card border-gold text-white" style="background-color: #1a1a24; border: 1px solid #f3af22;">
            <div class="modal-header border-bottom border-dark">
                <h5 class="modal-title fw-bold" style="color: #f3af22;" id="featuredModalLabel">
                    <i class="bi bi-star-fill me-2"></i> Upgrade Ke Featured Premium
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formFeaturedRequest" method="POST" action="">
                @csrf
                <div class="modal-body py-4 px-4">
                    <p class="text-muted mb-4 text-center" style="font-size: 0.9rem;">
                        Tingkatkan penjualan Anda untuk akun: 
                        <strong id="modalListingTitle" class="text-white fs-5 d-block mt-1"></strong>
                    </p>

                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <div class="bg-dark p-4 rounded-3 border border-secondary h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-white mb-3"><i class="bi bi-lightning-charge-fill me-2" style="color: #f3af22;"></i> Keuntungan Layanan</h6>
                                    <ul class="small mb-4 ps-3 text-muted">
                                        <li class="mb-2">Tampil eksklusif di <strong style="color: #f3af22;">Posisi Paling Atas</strong>.</li>
                                        <li class="mb-2">Mendapat <strong class="text-white">Badge Premium</strong> khusus.</li>
                                        <li class="mb-2">Masa tayang Premium <strong style="color: #f3af22;">7 Hari</strong>.</li>
                                        <li><strong class="text-success">BONUS:</strong> +7 Hari masa tayang reguler.</li>
                                    </ul>
                                </div>
                                <div class="p-3 rounded bg-black border border-secondary text-center">
                                    <span class="d-block text-muted small mb-1">Biaya Layanan</span>
                                    <span class="fs-4 fw-bold" style="color: #f3af22;">Rp 25.000</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-dark p-4 rounded-3 border border-secondary h-100">
                                <h6 class="fw-bold text-white mb-3"><i class="bi bi-wallet2 me-2 text-info"></i> Panduan Pembayaran</h6>
                                <p class="small text-muted mb-3">Silakan transfer sesuai nominal ke salah satu rekening berikut:</p>
                                
                                <div class="mb-2 p-2 rounded border border-secondary bg-black d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block small text-muted" style="font-size: 0.7rem;">BCA (a.n Admin CODM)</span>
                                        <strong class="text-white font-monospace">1234-5678-90</strong>
                                    </div>
                                    <i class="bi bi-bank text-muted fs-4"></i>
                                </div>
                                <div class="mb-4 p-2 rounded border border-secondary bg-black d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block small text-muted" style="font-size: 0.7rem;">DANA / GOPAY</span>
                                        <strong class="text-white font-monospace">0812-3456-7890</strong>
                                    </div>
                                    <i class="bi bi-phone text-muted fs-4"></i>
                                </div>

                                <div class="alert alert-warning bg-black border-warning text-white p-2 mb-0" style="font-size: 0.75rem;" role="alert">
                                    <strong>Langkah Terakhir:</strong> Klik <i>Ajukan Premium</i>, lalu segera kirim bukti transfer ke WhatsApp Admin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-dark d-flex justify-content-between">
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20sudah%20mengajukan%20Featured%20Listing%20di%20website%20dan%20ingin%20mengirimkan%20bukti%20pembayaran." target="_blank" class="btn btn-sm btn-outline-success rounded-3">
                        <i class="bi bi-whatsapp me-1"></i> Konfirmasi ke WA
                    </a>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm px-4 shadow text-dark fw-bold rounded-3" style="background-color: #f3af22;">Ajukan Premium</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const featuredButtons = document.querySelectorAll('.btn-trigger-featured');
        const modalForm = document.getElementById('formFeaturedRequest');
        const modalTitleElement = document.getElementById('modalListingTitle');

        featuredButtons.forEach(button => {
            button.addEventListener('click', function () {
                const listingId = this.getAttribute('data-id');
                const listingTitle = this.getAttribute('data-title');

                // Update teks judul di dalam modal
                modalTitleElement.textContent = listingTitle;

                // Update URL Action form agar mengarah ke route request-featured yang baru
                modalForm.action = `/seller/listings/${listingId}/request-featured`;
            });
        });
    });
</script>
@endsection
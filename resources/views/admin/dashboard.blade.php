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
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.listings.index') }}" class="btn fw-bold text-dark text-uppercase shadow-sm" style="background-color: #f3af22; border: 1px solid #f3af22; font-size: 0.75rem; letter-spacing: 0.5px;">
                Kelola Semua Listing
            </a>
            <a href="{{ route('admin.history') }}" class="btn btn-outline-dark fw-bold text-uppercase shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                Riwayat Moderasi
            </a>
            <a href="{{ route('admin.escrow.history') }}" class="btn btn-success fw-bold text-uppercase shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-archive-fill me-1"></i> Riwayat Rekber
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
    </div>

    <div class="card border-0 shadow-lg overflow-visible mt-5" style="background-color: rgba(30, 32, 45, 0.95); border-radius: 12px; border: 1px solid rgba(243, 175, 34, 0.3) !important;">
        <div class="card-header border-0 py-3 d-flex justify-content-between align-items-center" style="background-color: #1a1a11; border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <h6 class="mb-0 fw-bold text-uppercase" style="color: #f3af22; letter-spacing: 1px; font-size: 0.85rem;">
                <i class="bi bi-star-fill me-2"></i> Pengajuan Fitur Premium (Featured)
            </h6>
            <span class="badge text-dark rounded-pill" style="background-color: #f3af22;">{{ count($pendingFeatured ?? []) }} Antrean</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 150px;">
                <table class="table table-borderless table-hover align-middle mb-0 text-white">
                    <thead style="background-color: rgba(243, 175, 34, 0.1); border-bottom: 1px solid #44340a;">
                        <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; color: #ddd;">
                            <th class="py-3 px-4 w-25">Penjual</th>
                            <th class="py-3 w-25">Judul Akun</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3 text-center" style="width: 200px;">Aksi Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingFeatured ?? [] as $f)
                            <tr style="border-bottom: 1px solid #222;">
                                <td class="px-4 py-3 fw-bold">{{ $f->user->username ?? 'User' }}</td>
                                <td class="py-3 text-light">{{ $f->title }}</td>
                                <td class="py-3 fw-bold" style="color: #f3af22;">Rp {{ number_format($f->price, 0, ',', '.') }}</td>
                                
                                <td class="py-3">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <form action="{{ route('admin.listings.approve_featured', $f->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm fw-bold shadow-sm" style="background-color: #f3af22; color: #000; font-size: 0.7rem; text-transform: uppercase;" onclick="return confirm('Aktifkan Premium untuk iklan ini selama 30 Hari?')">
                                                <i class="bi bi-check-lg"></i> Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.listings.reject_featured', $f->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold" style="font-size: 0.7rem; text-transform: uppercase;" onclick="return confirm('Tolak pengajuan Premium ini?')">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: #666;">
                                    <i class="bi bi-star" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada pengajuan Premium dari seller saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg overflow-visible mt-5" style="background-color: rgba(30, 32, 45, 0.95); border-radius: 12px; border: 1px solid rgba(40, 167, 69, 0.3) !important;">
        <div class="card-header border-0 py-3 d-flex justify-content-between align-items-center" style="background-color: #111a14; border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <h6 class="mb-0 fw-bold text-uppercase" style="color: #28a745; letter-spacing: 1px; font-size: 0.85rem;">
                <i class="bi bi-shield-lock-fill me-2"></i> Pantauan Transaksi Rekber Berjalan
            </h6>
            <span class="badge text-white rounded-pill" style="background-color: #28a745;">{{ count($activeEscrows ?? []) }} Transaksi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 150px;">
                <table class="table table-borderless table-hover align-middle mb-0 text-white">
                    <thead style="background-color: rgba(40, 167, 69, 0.1); border-bottom: 1px solid #14331c;">
                        <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; color: #ddd;">
                            <th class="py-3 px-4">Info Transaksi</th>
                            <th class="py-3">Hubungi Pihak WA</th>
                            <th class="py-3">Status Sistem</th>
                            <th class="py-3 text-center" style="width: 180px;">Aksi Alur Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeEscrows ?? [] as $escrow)
                            <tr style="border-bottom: 1px solid #222;">
                                <td class="px-4 py-3">
                                    @if($escrow->listing)
                                        <a href="{{ route('marketplace.show', $escrow->listing->slug) }}" target="_blank" class="text-info fw-bold text-decoration-none d-block mb-1" style="font-size: 0.85rem;">
                                            {{ Str::limit($escrow->listing->title, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted d-block mb-1" style="font-size: 0.85rem;">Iklan Telah Dihapus</span>
                                    @endif
                                    <span class="fw-bold text-success d-block" style="font-size: 0.85rem;">Total: Rp {{ number_format($escrow->total_amount, 0, ',', '.') }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Fee: Rp {{ number_format($escrow->fee_amount, 0, ',', '.') }}</small>
                                </td>

                                <td class="py-3" style="font-size: 0.75rem;">
                                    <div class="mb-1">
                                        <span class="text-muted">Buyer: </span>
                                        <a href="https://wa.me/{{ $escrow->buyer_wa }}" target="_blank" class="text-success text-decoration-none fw-bold"><i class="bi bi-whatsapp"></i> {{ $escrow->buyer_wa }}</a> ({{ $escrow->buyer->username ?? 'User' }})
                                    </div>
                                    <div>
                                        <span class="text-muted">Seller: </span>
                                        @if($escrow->listing)
                                            <a href="https://wa.me/{{ $escrow->listing->whatsapp }}" target="_blank" class="text-success text-decoration-none fw-bold"><i class="bi bi-whatsapp"></i> {{ $escrow->listing->whatsapp }}</a> ({{ $escrow->listing->user->username ?? 'Seller' }})
                                        @else
                                            <span class="text-danger">Tidak tersedia</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3">
                                    @if($escrow->status == 'menunggu_admin')
                                        <span class="badge bg-danger text-white px-2 py-1.5" style="font-size: 0.7rem;"><i class="bi bi-hourglass-split"></i> Menunggu Admin</span>
                                    @elseif($escrow->status == 'diproses')
                                        <span class="badge bg-warning text-dark px-2 py-1.5" style="font-size: 0.7rem;"><i class="bi bi-patch-exclamation-fill"></i> Sedang Diproses</span>
                                    @elseif($escrow->status == 'transaksi_berlangsung')
                                        <span class="badge bg-info text-dark px-2 py-1.5" style="font-size: 0.7rem;"><i class="bi bi-chat-left-dots-fill"></i> Transaksi Berlangsung</span>
                                    @endif
                                </td>

                                <td class="py-3 text-center">
                                    @if($escrow->status == 'menunggu_admin')
                                        <form action="{{ route('escrow.proses', $escrow->id) }}" method="POST" class="m-0" onsubmit="return confirm('Mulai proses transaksi ini? (Status akan berubah menjadi Sedang Diproses dan sistem akan meminta Buyer & Seller standby di WA)')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm w-100" style="font-size: 0.75rem;">
                                                <i class="bi bi-play-fill"></i> 1. Proses Rekber
                                            </button>
                                        </form>
                                    @elseif($escrow->status == 'diproses')
                                        <form action="{{ route('escrow.group_created', $escrow->id) }}" method="POST" class="m-0" onsubmit="return confirm('Konfirmasi bahwa Grup WA untuk Buyer & Seller telah selesai dibuat? (Status akan berubah menjadi Transaksi Berlangsung)')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark shadow-sm w-100" style="font-size: 0.75rem;">
                                                <i class="bi bi-whatsapp"></i> 2. Grup Dibuat
                                            </button>
                                        </form>
                                    @elseif($escrow->status == 'transaksi_berlangsung')
                                        <form action="{{ route('escrow.complete', $escrow->id) }}" method="POST" class="m-0" onsubmit="return confirm('KONFIRMASI PENYELESAIAN TRANSAKSI:\n\n☑️ Buyer sudah menerima akun.\n☑️ Seller sudah menerima pembayaran.\n☑️ Tidak ada kendala transaksi.\n\nKlik OK jika transaksi benar-benar sudah selesai.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-bold shadow-sm w-100" style="font-size: 0.75rem;">
                                                <i class="bi bi-check-circle-fill"></i> 3. Selesaikan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: #666;">
                                    <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Semua aman! Tidak ada transaksi rekber aktif yang perlu dipantau.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
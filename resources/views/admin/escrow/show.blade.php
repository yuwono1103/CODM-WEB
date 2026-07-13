@extends('layouts.app')

@section('title', 'Detail Riwayat Rekber #' . $escrow->transaction_code)

@section('content')
<div class="container mt-4">
    <div class="mb-4">
        <a href="{{ route('admin.escrow.history') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="card card-gaming border-0 mb-4">
        <div class="card-header bg-dark-card text-white fw-bold py-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill text-gold me-2"></i>Detail Transaksi Resmi</h5>
            <span class="badge bg-success px-3 py-2 fs-6">Selesai</span>
        </div>
        <div class="card-body bg-dark-card rounded-bottom p-4">
            
            <div class="row g-3 mb-4 pb-3 border-bottom border-secondary text-white">
                <div class="col-md-4">
                    <span class="text-muted small d-block">Kode Transaksi</span>
                    <strong class="text-gold fs-5">#{{ $escrow->transaction_code }}</strong>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small d-block">Tanggal Pengajuan</span>
                    <strong>{{ $escrow->created_at->format('d M Y H:i') }}</strong>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small d-block">Tanggal Selesai</span>
                    <strong>{{ $escrow->completed_at ? $escrow->completed_at->format('d M Y H:i') : '-' }}</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7 mb-4">
                    <h5 class="fw-bold text-white mb-3"><i class="bi bi-info-square-fill text-gold me-2"></i> Informasi Transaksi</h5>
                    
                    <table class="table table-gaming mb-4 small">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-4 w-40">Judul Akun</td>
                                <td class="fw-bold text-white pe-4">{{ $escrow->listing->title ?? 'Akun Dihapus' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4">Pembeli (Buyer)</td>
                                <td class="fw-bold text-white pe-4">{{ $escrow->buyer->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4">WhatsApp Buyer</td>
                                <td class="fw-bold text-gold pe-4">{{ $escrow->buyer_wa }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4">Penjual (Seller)</td>
                                <td class="fw-bold text-white pe-4">{{ $escrow->seller->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4">Catatan Pengajuan</td>
                                <td class="text-muted pe-4 text-wrap">{{ $escrow->notes ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-5">
                    <div class="bg-black p-4 rounded-4 border border-secondary shadow-sm text-white">
                        <h6 class="text-muted small mb-3 fw-bold uppercase text-gold"><i class="bi bi-receipt me-1"></i> Audit Rincian Keuangan</h6>
                        
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Harga Akun (Seller Receive)</span>
                            <span>Rp {{ number_format($escrow->listing_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Biaya Jasa Rekber ({{ $escrow->fee_percentage }}%)</span>
                            <span class="text-warning">+ Rp {{ number_format($escrow->fee_amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="border-secondary my-3">
                        
                        <div class="d-flex justify-content-between fw-bold align-items-center">
                            <span>Total Dana Masuk</span>
                            <span class="text-success fs-4">Rp {{ number_format($escrow->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
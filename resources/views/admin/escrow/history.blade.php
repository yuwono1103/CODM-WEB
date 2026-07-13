@extends('layouts.app') {{-- Menyesuaikan layout utama proyekmu --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Riwayat Transaksi Rekber</h2>
            <p class="text-muted small mb-0">Daftar seluruh transaksi Rekber (Escrow) yang telah selesai diselesaikan oleh Admin.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            Arsip Transaksi Selesai
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Judul Akun</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Harga Akun</th>
                            <th>Fee Rekber</th>
                            <th>Total Pembayaran</th>
                            <th>Tgl Pengajuan</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $item)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $item->transaction_code }}</td>
                                <td>{{ $item->listing->title ?? 'Akun Dihapus' }}</td>
                                <td>{{ $item->buyer->name ?? 'Unknown' }}</td>
                                <td>{{ $item->seller->name ?? 'Unknown' }}</td>
                                <td>Rp {{ number_format($item->listing_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->fee_amount, 0, ',', '.') }}</td>
                                <td class="fw-bold text-success">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                <td class="text-muted small">{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td class="text-muted small">{{ $item->completed_at ? $item->completed_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    <span class="badge bg-success px-3 py-2">Selesai</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.escrow.show', $item->id) }}" class="btn btn-primary btn-sm px-2 py-1" style="font-size: 0.8rem;">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    Belum ada riwayat transaksi rekber yang diselesaikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Navigasi Halaman / Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $histories->links() }}
    </div>
</div>
@endsection
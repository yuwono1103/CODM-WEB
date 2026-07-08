@extends('layouts.app') {{-- Atau sesuaikan dengan layout induk yang kamu pakai di dashboard --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Riwayat Moderasi Iklan</h2>
            <p class="text-muted small mb-0">Daftar seluruh iklan yang telah disetujui atau ditolak oleh sistem.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            Arsip Tindakan Moderasi
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Penjual</th>
                            <th>Judul Akun</th>
                            <th>Harga</th>
                            <th>Tanggal Diproses</th>
                            <th>Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listings as $listing)
                            <tr>
                                <td class="fw-bold">{{ $listing->user->username ?? 'Unknown' }}</td>
                                <td>{{ $listing->title }}</td>
                                <td>Rp {{ number_format($listing->price, 0, ',', '.') }}</td>
                                <td class="text-muted small">{{ $listing->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($listing->status->value === \App\Enums\ListingStatus::ACTIVE->value)
                                        <span class="badge bg-success px-3 py-2">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Belum ada riwayat tindakan moderasi iklan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $listings->links() }}
    </div>
</div>
@endsection
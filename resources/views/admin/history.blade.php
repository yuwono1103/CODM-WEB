@extends('layouts.app') {{-- Menyesuaikan layout utama proyekmu --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Riwayat Moderasi Iklan</h2>
            <p class="text-muted small mb-0">Daftar seluruh iklan (Listing) yang telah diproses (Disetujui atau Ditolak) oleh Admin.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            Arsip Moderasi Iklan Penjualan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Penjual</th>
                            <th>Judul Akun</th>
                            <th>Harga Akun</th>
                            <th>Tanggal Proses</th>
                            <th class="text-center">Status Moderasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listings as $item)
                            <tr>
                                <td class="fw-bold text-secondary">
                                    {{ $item->user->name ?? 'User Terhapus' }}
                                </td>
                                <td>{{ $item->title }}</td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-muted small">
                                    {{ $item->updated_at->format('d M Y H:i') }}
                                </td>
                                <td class="text-center">
                                    @if($item->status->value === 'active')
                                        <span class="badge bg-success px-3 py-2 text-uppercase" style="font-size: 0.75rem;">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2 text-uppercase" style="font-size: 0.75rem;">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Belum ada riwayat moderasi iklan yang diproses.
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
        {{ $listings->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 pb-5 border-b border-gray-800 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#f3af22] tracking-wider uppercase">
                Pusat Komando Listing
            </h1>
            <p class="text-gray-400 text-xs sm:text-sm mt-1">Pantau, cari, dan kelola seluruh arsip iklan marketplace CODM.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-block px-4 py-2 bg-[#1a1c2e] hover:bg-[#252841] rounded-lg text-xs font-bold uppercase tracking-widest text-white border border-gray-750 transition duration-200">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="bg-[#141522]/90 border border-gray-800 rounded-xl p-5 mb-8 shadow-xl">
        <form action="{{ route('admin.listings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            <div class="md:col-span-6">
                <label class="block text-[11px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Kata Kunci Pencarian</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul iklan, nama, atau email penjual..." 
                       class="w-full bg-[#0b0c14] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-200 placeholder-gray-650 focus:outline-none focus:border-[#f3af22] transition">
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Status Iklan</label>
                <select name="status" class="w-full bg-[#0b0c14] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-[#f3af22] transition cursor-pointer">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Aktif (Approved)</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#f3af22] hover:bg-[#db9d1e] rounded-lg text-xs font-black uppercase tracking-wider text-black transition duration-200">
                    Terapkan
                </button>
                @if($search || $status)
                    <a href="{{ route('admin.listings.index') }}" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-xs font-bold uppercase tracking-wider text-gray-300 border border-gray-700 text-center transition duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-[#141522]/90 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-[#0b0c14] border-b border-gray-800 text-gray-400 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">Profil Penjual</th>
                        <th class="p-4">Konfigurasi Iklan</th>
                        <th class="p-4">Nilai Aset</th>
                        <th class="p-4 text-center">Status Operasional</th>
                        <th class="p-4">Kronologi Dibuat</th>
                        <th class="py-3">Masa Aktif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50 text-sm">
                    @forelse($listings as $item)
                        <tr class="hover:bg-[#1a1c2e]/40 transition duration-150">
                            <td class="p-4">
                                <div class="font-bold text-gray-200">{{ $item->user->name ?? 'User Anonim' }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $item->user->email }}</div>
                            </td>
                            <td class="p-4 text-gray-300 font-medium">
                                <div class="max-w-xs truncate">{{ $item->title }}</div>
                                <div class="text-[10px] text-gray-600 font-mono mt-0.5">ID: {{ $item->id }}</div>
                            </td>
                            <td class="p-4 font-mono text-[#f3af22] font-black text-sm">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($item->status->value === 'pending')
                                    <span class="inline-block px-3 py-1 text-[11px] font-bold rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wide">
                                        Tertunda
                                    </span>
                                @elseif($item->status->value === 'approved' || $item->status->value === 'active')
                                    <span class="inline-block px-3 py-1 text-[11px] font-bold rounded bg-green-500/10 text-green-400 border border-green-500/20 uppercase tracking-wide">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 text-[11px] font-bold rounded bg-red-500/10 text-red-400 border border-red-500/20 uppercase tracking-wide">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-gray-500 font-mono">
                                {{ $item->created_at->format('d M Y, H:i') }}
                            </td>
                            
                            <!-- ================= BAGIAN DURASI TAYANG (ADMIN) ================= -->
                            <td class="p-4">
                                @php
                                    $sisaHari = 0;
                                    $tipeMasaAktif = '';
                            
                                    // 1. Cek Apakah Premium Aktif (Menggunakan $item sesuai variabel loop admin)
                                    if ($item->featured_until && \Carbon\Carbon::parse($item->featured_until)->isFuture() && $item->featured_status === 'approved') {
                                        $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($item->featured_until));
                                        $tipeMasaAktif = 'Premium';
                                    } 
                                    // 2. Cek Apakah Reguler Aktif
                                    elseif ($item->expires_at && \Carbon\Carbon::parse($item->expires_at)->isFuture()) {
                                        $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($item->expires_at));
                                        $tipeMasaAktif = 'Reguler';
                                    }
                                @endphp
                            
                                @if($sisaHari > 0)
                                    <span class="text-gray-200 text-sm font-bold">
                                        <i class="bi bi-clock-history mr-1 {{ $tipeMasaAktif === 'Premium' ? 'text-[#f3af22]' : 'text-gray-400' }}"></i> 
                                        {{ $sisaHari }} Hari
                                        <br>
                                        <span class="text-[10px] {{ $tipeMasaAktif === 'Premium' ? 'text-[#f3af22]' : 'text-gray-500' }} uppercase tracking-wider">
                                            ({{ $tipeMasaAktif }})
                                        </span>
                                    </span>
                                @elseif($item->is_expired || ($item->expires_at && \Carbon\Carbon::parse($item->expires_at)->isPast()))
                                    <span class="inline-block px-2 py-1 text-[10px] font-bold rounded bg-red-500/10 text-red-400 border border-red-500/20 uppercase tracking-wide">Habis</span>
                                @else
                                    <span class="text-gray-600 font-bold">-</span>
                                @endif
                            </td>
                            <!-- ================================================================ -->
                            
                        </tr>
                    @empty
                        <tr>
                            <!-- Colspan diubah dari 5 menjadi 6 karena ada kolom baru -->
                            <td colspan="6" class="p-12 text-center text-gray-500 font-medium">
                                Tidak ada data listing yang ditemukan dalam sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($listings->hasPages())
            <div class="p-4 border-t border-gray-800 bg-[#0b0c14]/50">
                {{ $listings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
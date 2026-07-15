@extends('layouts.admin') @section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-white">Pengaturan Marketplace</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md text-white">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nomor WhatsApp Admin (Gunakan 62)</label>
            <input type="text" name="wa_admin" value="{{ $settings['wa_admin'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Bank (cth: BCA, Mandiri)</label>
            <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nomor Rekening</label>
            <input type="text" name="bank_account" value="{{ $settings['bank_account'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Atas Nama Rekening</label>
            <input type="text" name="bank_owner" value="{{ $settings['bank_owner'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Fee Rekber (%)</label>
            <input type="number" name="rekber_fee_percent" value="{{ $settings['rekber_fee_percent'] ?? '3' }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
        </div>

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection
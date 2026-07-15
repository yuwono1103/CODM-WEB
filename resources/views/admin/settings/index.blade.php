@extends('layouts.admin') 

@section('content')
<div class="container mt-2">
    
    <div class="mb-4">
        <h2 class="fw-bold text-dark">⚙️ Pengaturan Marketplace</h2>
        <p class="text-muted">Kelola nomor WhatsApp admin, rekening, dan biaya Rekber di sini.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 bg-white rounded">

            @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fw-bold" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
            @endif
            
<!-- Pastikan nama route-nya sesuai dengan yang ada di routes/web.php kamu -->
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Nomor WA Admin -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Nomor WhatsApp Admin (Gunakan 62)</label>
                        <input type="text" name="admin_wa" class="form-control bg-light" placeholder="Contoh: 628123456789" value="{{ $siteSettings['admin_wa'] ?? '' }}">
                    </div>

                    <!-- Nama Bank -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Nama Bank (cth: BCA, Mandiri)</label>
                        <input type="text" name="bank_name" class="form-control bg-light" placeholder="Contoh: BCA" value="{{ $siteSettings['bank_name'] ?? '' }}">
                    </div>

                    <!-- Nomor Rekening -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Nomor Rekening</label>
                        <input type="text" name="bank_account" class="form-control bg-light" placeholder="Masukkan Nomor Rekening" value="{{ $siteSettings['bank_account'] ?? '' }}">
                    </div>

                    <!-- Atas Nama Rekening -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Atas Nama Rekening</label>
                        <input type="text" name="account_name" class="form-control bg-light" placeholder="Masukkan Atas Nama" value="{{ $siteSettings['account_name'] ?? '' }}">
                    </div>

                    <!-- Fee Rekber -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Fee Rekber (%)</label>
                        <input type="number" name="rekber_fee" class="form-control bg-light" placeholder="Contoh: 5" value="{{ $siteSettings['rekber_fee'] ?? '' }}">
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-danger fw-bold px-4">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
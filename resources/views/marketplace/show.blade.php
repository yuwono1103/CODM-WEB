@extends('layouts.app')

@section('title', $listing->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card card-gaming border-0 mb-4">
                <div class="card-body bg-dark-card rounded-4 p-4">
                    <h2 class="fw-bold text-white mb-2">{{ $listing->title }}</h2>
                    <h3 class="text-gold fw-bold mb-3">Rp {{ number_format($listing->price, 0, ',', '.') }}</h3>
                    <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom border-secondary">
                        <span><i class="bi bi-eye text-gold"></i> Dilihat: <strong>{{ $listing->view_count }} kali</strong></span>
                        <span><i class="bi bi-person-circle text-gold"></i> Penjual: <strong>{{ $listing->user->username ?? 'Unknown' }}</strong></span>
                    </div>
                    
                    <h5 class="fw-bold text-white mb-3"><i class="bi bi-images text-gold me-2"></i> Galeri Screenshot</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="position-relative">
                                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Lobby</span>
                                <img src="{{ asset('storage/'.$listing->lobby_image) }}" class="img-fluid rounded border border-secondary" alt="Lobby">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="position-relative">
                                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Weapon</span>
                                <img src="{{ asset('storage/'.$listing->weapon_image) }}" class="img-fluid rounded border border-secondary" alt="Weapon">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="position-relative">
                                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Character</span>
                                <img src="{{ asset('storage/'.$listing->character_image) }}" class="img-fluid rounded border border-secondary" alt="Character">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="position-relative">
                                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Vehicle</span>
                                <img src="{{ asset('storage/'.$listing->vehicle_image) }}" class="img-fluid rounded border border-secondary" alt="Vehicle">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-gold bg-dark-card shadow mb-4 text-center rounded-4" style="background: linear-gradient(145deg, #1e1e1e, #2a2205);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-white mb-2">Tertarik dengan Akun Ini?</h5>
                    <p class="small text-muted mb-3">
                        Transaksi langsung via WhatsApp dengan penjual. Harap selalu gunakan jasa Rekber pihak ketiga yang terpercaya!
                    </p>
                    <button type="button" class="btn btn-outline-warning btn-sm fw-bold mb-4 px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#rekberModal">
                        <i class="bi bi-shield-check me-1"></i> Pelajari Rekber
                    </button>
                    
                    <a href="https://wa.me/{{ $listing->whatsapp }}?text=Halo,%20saya%20tertarik%20dengan%20iklan%20akun%20CODM%20Anda:%20{{ urlencode($listing->title) }}" 
                       target="_blank" class="btn btn-gold btn-lg w-100 fw-bold shadow">
                        <i class="bi bi-whatsapp me-2"></i> Chat Penjual
                    </a>
                </div>
            </div>

            <div class="card card-gaming border-0 mb-4">
                <div class="card-header bg-dark-card border-bottom border-secondary py-3">
                    <h6 class="mb-0 fw-bold text-white"><i class="bi bi-info-square-fill text-gold me-2"></i> Detail Spesifikasi</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-gaming mb-0 small">
                        <tbody>
                            <tr><td class="text-muted ps-4 w-50">IGN (In-Game Name)</td><td class="fw-bold text-white pe-4">{{ $listing->ign }}</td></tr>
                            <tr><td class="text-muted ps-4">UID Akun</td><td class="fw-bold text-white pe-4 text-break">{{ $listing->uid }}</td></tr>
                            <tr><td class="text-muted ps-4">Level Akun</td><td class="fw-bold text-white pe-4">{{ $listing->level }}</td></tr>
                            <tr><td class="text-muted ps-4">Rank Multiplayer</td><td class="fw-bold text-white pe-4">{{ $listing->rank_mp }}</td></tr>
                            <tr><td class="text-muted ps-4">Rank Battle Royale</td><td class="fw-bold text-white pe-4">{{ $listing->rank_br }}</td></tr>
                            <tr><td class="text-muted ps-4">Tipe Login</td><td class="fw-bold text-gold pe-4">{{ $listing->login_type }}</td></tr>
                            <tr><td class="text-muted ps-4">Status Bind</td><td class="fw-bold text-white pe-4">{{ $listing->bind_status ?? 'Clean' }}</td></tr>
                            <tr><td class="text-muted ps-4">Sisa CP</td><td class="fw-bold text-white pe-4">{{ $listing->cp_remaining }} CP</td></tr>
                            <tr><td class="text-muted ps-4">Border S1 Asli</td><td class="pe-4">{!! $listing->border_s1 ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>' !!}</td></tr>
                            <tr><td class="text-muted ps-4">Camo Damascus</td><td class="pe-4">{!! $listing->damascus ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>' !!}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-gaming border-0">
                <div class="card-header bg-dark-card border-bottom border-secondary py-3">
                    <h6 class="mb-0 fw-bold text-white"><i class="bi bi-box-seam-fill text-gold me-2"></i> Ringkasan Koleksi</h6>
                </div>
                <div class="card-body bg-dark-card rounded-bottom p-0">
                    @if(!empty($listing->mythic_weapon_list) || !empty($listing->legendary_weapon_list) || !empty($listing->mythic_character_list))
                        <div class="p-3 small">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-white">Mythic Weapons</span>
                                    <span class="badge bg-danger">{{ $listing->mythic_weapon_count }} Item</span>
                                </div>
                                <div class="bg-dark p-2 rounded text-muted text-break border border-secondary">{{ $listing->mythic_weapon_list }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-white">Legendary Weapons</span>
                                    <span class="badge bg-warning text-dark">{{ $listing->legendary_weapon_count }} Item</span>
                                </div>
                                <div class="bg-dark p-2 rounded text-muted text-break border border-secondary">{{ $listing->legendary_weapon_list }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-white">Mythic Characters</span>
                                    <span class="badge bg-danger">{{ $listing->mythic_character_count }} Item</span>
                                </div>
                                <div class="bg-dark p-2 rounded text-muted text-break border border-secondary">{{ $listing->mythic_character_list }}</div>
                            </div>
                        </div>
                    @else
                        <table class="table table-gaming mb-0 small">
                            <tbody>
                                <tr><td class="text-muted ps-4 w-50">Mythic Weapon</td><td class="fw-bold text-white pe-4">: {{ $listing->mythic_weapon_count ?? 0 }}</td></tr>
                                <tr><td class="text-muted ps-4">Legendary Weapon</td><td class="fw-bold text-white pe-4">: {{ $listing->legendary_weapon_count ?? 0 }}</td></tr>
                                <tr><td class="text-muted ps-4">Prestige Weapon</td><td class="fw-bold text-white pe-4">: {{ $listing->prestige_weapon_count ?? 0 }}</td></tr>
                                <tr><td class="text-muted ps-4">Mythic Character</td><td class="fw-bold text-white pe-4">: {{ $listing->mythic_character_count ?? 0 }}</td></tr>
                                <tr><td class="text-muted ps-4">Legendary Character</td><td class="fw-bold text-white pe-4">: {{ $listing->legendary_character_count ?? 0 }}</td></tr>
                                <tr><td class="text-muted ps-4">Legendary Vehicle</td><td class="fw-bold text-white pe-4">: {{ $listing->legendary_vehicle_count ?? 0 }}</td></tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
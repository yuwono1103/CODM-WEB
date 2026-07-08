@extends('layouts.app')

@section('title', 'Ubah Iklan: ' . $listing->title)

@section('content')
<div class="container py-3">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="h3 fw-bold text-white mb-1">Revisi <span class="text-gold">Iklan Akun</span></h2>
            <p class="text-muted small mb-0">Perbaiki detail informasi akun CODM Anda.</p>
        </div>
        <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger bg-dark-card border-danger text-white alert-dismissible fade show mb-4">
            <strong><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Oops! Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('seller.listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card card-gaming border-0 mb-4 shadow-sm">
            <div class="card-header bg-dark-card border-bottom border-dark py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-info-circle-fill text-gold me-2"></i> 1. Informasi Dasar</h5>
            </div>
            <div class="card-body bg-dark-card rounded-bottom p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label text-muted small fw-bold text-uppercase">Judul Iklan *</label>
                        <input type="text" name="title" class="form-control form-control-dark" value="{{ old('title', $listing->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">Harga (Rp) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-gold fw-bold">Rp</span>
                            <input type="number" name="price" class="form-control form-control-dark" value="{{ old('price', intval($listing->price)) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">Nomor WhatsApp Aktif *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="whatsapp" class="form-control form-control-dark" value="{{ old('whatsapp', $listing->whatsapp) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-gaming border-0 mb-4 shadow-sm">
            <div class="card-header bg-dark-card border-bottom border-dark py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-controller text-gold me-2"></i> 2. Spesifikasi Akun</h5>
            </div>
            <div class="card-body bg-dark-card rounded-bottom p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">IGN (In-Game Name) *</label>
                        <input type="text" name="ign" class="form-control form-control-dark" value="{{ old('ign', $listing->ign) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">Open ID / UID *</label>
                        <input type="text" name="uid" class="form-control form-control-dark" value="{{ old('uid', $listing->uid) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Level Akun *</label>
                        <input type="number" name="level" class="form-control form-control-dark" value="{{ old('level', $listing->level) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Rank MP *</label>
                        <select name="rank_mp" class="form-select form-control-dark" required>
                            <option value="">Pilih Rank MP...</option>
                            <option value="Legendary" {{ old('rank_mp', $listing->rank_mp) == 'Legendary' ? 'selected' : '' }}>Legendary</option>
                            <option value="Grand Master" {{ old('rank_mp', $listing->rank_mp) == 'Grand Master' ? 'selected' : '' }}>Grand Master</option>
                            <option value="Master" {{ old('rank_mp', $listing->rank_mp) == 'Master' ? 'selected' : '' }}>Master</option>
                            <option value="Pro" {{ old('rank_mp', $listing->rank_mp) == 'Pro' ? 'selected' : '' }}>Pro</option>
                            <option value="Elite" {{ old('rank_mp', $listing->rank_mp) == 'Elite' ? 'selected' : '' }}>Elite</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Rank BR *</label>
                        <select name="rank_br" class="form-select form-control-dark" required>
                            <option value="">Pilih Rank BR...</option>
                            <option value="Legendary" {{ old('rank_br', $listing->rank_br) == 'Legendary' ? 'selected' : '' }}>Legendary</option>
                            <option value="Grand Master" {{ old('rank_br', $listing->rank_br) == 'Grand Master' ? 'selected' : '' }}>Grand Master</option>
                            <option value="Master" {{ old('rank_br', $listing->rank_br) == 'Master' ? 'selected' : '' }}>Master</option>
                            <option value="Pro" {{ old('rank_br', $listing->rank_br) == 'Pro' ? 'selected' : '' }}>Pro</option>
                            <option value="Elite" {{ old('rank_br', $listing->rank_br) == 'Elite' ? 'selected' : '' }}>Elite</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Tipe Login *</label>
                        <select name="login_type" class="form-select form-control-dark" required>
                            <option value="" disabled>Pilih Tipe Login</option>
                            <option value="Garena" {{ old('login_type', $listing->login_type) == 'Garena' ? 'selected' : '' }}>Garena</option>
                            <option value="Facebook" {{ old('login_type', $listing->login_type) == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Sisa CP</label>
                        <input type="number" name="cp_remaining" class="form-control form-control-dark" value="{{ old('cp_remaining', $listing->cp_remaining) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end pb-2 gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="border_s1" value="1" id="borderS1" {{ old('border_s1', $listing->border_s1) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="borderS1">Border S1 Asli</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="damascus" value="1" id="damascus" {{ old('damascus', $listing->damascus) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="damascus">Camo Damascus</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-gaming border-0 mb-4 shadow-sm">
            <div class="card-header bg-dark-card border-bottom border-dark py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-box-seam text-gold me-2"></i> 3. Jumlah Koleksi Sultan</h5>
            </div>
            <div class="card-body bg-dark-card rounded-bottom p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Mythic Weapon</label>
                        <input type="number" name="mythic_weapon_count" class="form-control form-control-dark" value="{{ old('mythic_weapon_count', $listing->mythic_weapon_count) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Legendary Weapon</label>
                        <input type="number" name="legendary_weapon_count" class="form-control form-control-dark" value="{{ old('legendary_weapon_count', $listing->legendary_weapon_count) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Prestige Weapon</label>
                        <input type="number" name="prestige_weapon_count" class="form-control form-control-dark" value="{{ old('prestige_weapon_count', $listing->prestige_weapon_count) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Mythic Character</label>
                        <input type="number" name="mythic_character_count" class="form-control form-control-dark" value="{{ old('mythic_character_count', $listing->mythic_character_count) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Legendary Character</label>
                        <input type="number" name="legendary_character_count" class="form-control form-control-dark" value="{{ old('legendary_character_count', $listing->legendary_character_count) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Jml Legendary Vehicle</label>
                        <input type="number" name="legendary_vehicle_count" class="form-control form-control-dark" value="{{ old('legendary_vehicle_count', $listing->legendary_vehicle_count) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-gaming border-0 mb-4 shadow-sm">
            <div class="card-header bg-dark-card border-bottom border-dark py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-images text-gold me-2"></i> 4. Upload Screenshot</h5>
            </div>
            <div class="card-body bg-dark-card rounded-bottom p-4">
                <div class="alert alert-dark bg-dark border-secondary text-light small mb-4">
                    <i class="bi bi-info-circle text-gold me-2"></i> <strong>Catatan:</strong> Biarkan kolom file di bawah ini <strong>KOSONG</strong> jika Anda tidak ingin mengganti gambar yang sudah ada sebelumnya.
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ganti Thumbnail Utama</label>
                        <input type="file" name="thumbnail" class="form-control form-control-dark" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ganti SS Lobby</label>
                        <input type="file" name="lobby_image" class="form-control form-control-dark" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ganti SS Weapon</label>
                        <input type="file" name="weapon_image" class="form-control form-control-dark" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ganti SS Character</label>
                        <input type="file" name="character_image" class="form-control form-control-dark" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ganti SS Vehicle</label>
                        <input type="file" name="vehicle_image" class="form-control form-control-dark" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid mb-5">
            <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold fs-5 shadow-lg">
                <i class="bi bi-save-fill me-2"></i> Simpan Revisi & Ajukan Ulang
            </button>
        </div>
    </form>
</div>
@endsection
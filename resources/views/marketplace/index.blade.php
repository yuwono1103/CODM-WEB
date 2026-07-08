@extends('layouts.app')

@section('title', 'Jelajahi Akun')

@section('content')
<div class="container-fluid px-xl-5">
    
    @php
        // Pisahkan data Featured dan Reguler
        $featuredAccounts = $listings->filter(fn($l) => $l->featured_until && $l->featured_until->isFuture());
        $regularAccounts = $listings->reject(fn($l) => $l->featured_until && $l->featured_until->isFuture());
    @endphp

    <div class="text-center mb-4">
        <h1 class="fw-bold text-white mb-2">Marketplace <span class="text-gold">Akun CODM</span></h1>
        <p class="text-muted">Temukan akun impianmu dengan spesifikasi terbaik.</p>
    </div>

    @if($featuredAccounts->isNotEmpty())
        <div class="mb-4">
            <h5 class="fw-bold text-white mb-3"><i class="bi bi-star-fill text-gold me-2"></i> AKUN LAGI BU</h5>
            
            <div id="featuredCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden border border-secondary" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($featuredAccounts as $index => $listing)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">
                            <div class="row g-0" style="background-color: #0f1922;">
                                
                                <div class="col-lg-8 position-relative">
                                    <img src="{{ asset('storage/'.$listing->thumbnail) }}" class="w-100 object-fit-cover" style="height: 450px;" alt="Thumbnail">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-4" style="background: linear-gradient(to top, rgba(15,25,34,1) 0%, rgba(15,25,34,0.7) 40%, transparent 100%);">
                                        <div class="mb-2">
                                            <span class="badge bg-gold text-dark fw-bold px-3 py-2 border border-warning"><i class="bi bi-fire"></i> FEATURED</span>
                                        </div>
                                        <h2 class="text-white fw-bold mb-1 text-truncate" title="{{ $listing->title }}">{{ $listing->title }}</h2>
                                        <h3 class="text-gold fw-bold mb-3">Rp {{ number_format($listing->price, 0, ',', '.') }}</h3>
                                        
                                        <div class="d-flex flex-wrap gap-2 mb-4">
                                            <div class="stat-box-hero" title="Mythic Weapon"><i class="bi bi-crosshair text-danger"></i> {{ $listing->mythic_weapon_count }} Mythic Wp</div>
                                            <div class="stat-box-hero" title="Prestige Weapon"><i class="bi bi-shield-shaded text-info"></i> {{ $listing->prestige_weapon_count }} Prestige</div>
                                            <div class="stat-box-hero" title="Legendary Weapon"><i class="bi bi-lightning-fill text-warning"></i> {{ $listing->legendary_weapon_count }} Legend Wp</div>
                                            <div class="stat-box-hero" title="Mythic Character"><i class="bi bi-person-fill-gear text-danger"></i> {{ $listing->mythic_character_count }} Mythic Char</div>
                                            <div class="stat-box-hero" title="Legendary Character"><i class="bi bi-person-fill text-warning"></i> {{ $listing->legendary_character_count }} Legend Char</div>
                                            <div class="stat-box-hero" title="Legendary Vehicle"><i class="bi bi-car-front-fill text-warning"></i> {{ $listing->legendary_vehicle_count }} Legend Veh</div>
                                            @if($listing->border_s1)
                                                <div class="stat-box-hero bg-opacity-25 bg-success text-success border-success"><i class="bi bi-check-circle-fill"></i> Border S1</div>
                                            @endif
                                            @if($listing->damascus)
                                                <div class="stat-box-hero bg-opacity-25 bg-primary text-primary border-primary"><i class="bi bi-check-circle-fill"></i> Damascus</div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <a href="{{ route('marketplace.show', $listing->slug) }}" class="btn btn-gold px-5 py-2 fw-bold text-uppercase">Lihat Detail Akun</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 d-none d-lg-block p-3">
                                    <h6 class="text-muted small fw-bold mb-3 text-uppercase">Preview In-Game</h6>
                                    <div class="row g-2 h-100 pb-4">
                                        <div class="col-6">
                                            <img src="{{ asset('storage/'.($listing->lobby_image ?? $listing->thumbnail)) }}" class="w-100 rounded-2 border border-secondary object-fit-cover steam-preview-img" alt="Lobby">
                                        </div>
                                        <div class="col-6">
                                            <img src="{{ asset('storage/'.($listing->weapon_image ?? $listing->thumbnail)) }}" class="w-100 rounded-2 border border-secondary object-fit-cover steam-preview-img" alt="Weapon">
                                        </div>
                                        <div class="col-6">
                                            <img src="{{ asset('storage/'.($listing->character_image ?? $listing->thumbnail)) }}" class="w-100 rounded-2 border border-secondary object-fit-cover steam-preview-img" alt="Character">
                                        </div>
                                        <div class="col-6">
                                            <img src="{{ asset('storage/'.($listing->vehicle_image ?? $listing->thumbnail)) }}" class="w-100 rounded-2 border border-secondary object-fit-cover steam-preview-img" alt="Vehicle">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($featuredAccounts->count() > 1)
                    <button class="carousel-control-prev featured-control" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next featured-control" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <div class="card bg-dark-card border-gold mb-5 rounded-4 shadow-sm" style="background: linear-gradient(90deg, #1e1e1e 0%, #2a2205 100%);">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-dark p-3 rounded-circle border border-gold">
                    <i class="bi bi-shield-check text-gold fs-1 lh-1"></i>
                </div>
                <div>
                    <h4 class="text-white fw-bold mb-1">Transaksi Lebih Aman dengan Layanan Rekber</h4>
                    <p class="text-muted mb-0">Gunakan layanan Rekber CODM Market untuk mencegah penipuan. Dana ditahan dengan aman hingga akun Anda terima.</p>
                </div>
            </div>
            <button type="button" class="btn btn-outline-gold px-4 py-2 fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#rekberModal">
                Pelajari Rekber
            </button>
        </div>
    </div>

    <div class="d-flex flex-column flex-lg-row gap-4">
        
        <aside class="sidebar-wrapper">
            <div class="card card-gaming border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-dark-card border-bottom border-dark py-3">
                    <h6 class="mb-0 fw-bold text-white"><i class="bi bi-funnel-fill text-gold me-2"></i> Filter Pencarian</h6>
                </div>
                <div class="card-body bg-dark-card rounded-bottom p-0">
                    <form action="{{ route('marketplace.index') }}" method="GET" class="sidebar-scroll p-3">
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Pencarian Keyword</label>
                            <input type="text" name="search" class="form-control form-control-dark mb-2" placeholder="Judul iklan..." value="{{ request('search') }}">
                            <input type="text" name="ign" class="form-control form-control-dark" placeholder="IGN In-game..." value="{{ request('ign') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Rentang Harga (Rp)</label>
                            <input type="number" name="min_price" class="form-control form-control-dark mb-2" placeholder="Min. Harga" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="form-control form-control-dark" placeholder="Max. Harga" value="{{ request('max_price') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Tipe Login</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="login_type[]" value="Garena" id="loginGarena" {{ is_array(request('login_type')) && in_array('Garena', request('login_type')) ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="loginGarena">Garena</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="login_type[]" value="Facebook" id="loginFb" {{ is_array(request('login_type')) && in_array('Facebook', request('login_type')) ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="loginFb">Facebook</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Atribut Akun</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="border_s1" value="1" id="filterS1" {{ request('border_s1') ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="filterS1">Border S1 Asli</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="damascus" value="1" id="filterDamascus" {{ request('damascus') ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="filterDamascus">Camo Damascus</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Koleksi Minimum</label>
                            <div class="row g-2">
                                <div class="col-6"><input type="number" name="min_mythic_wp" class="form-control form-control-dark form-control-sm" placeholder="Mythic Wp" title="Minimum Mythic Weapon"></div>
                                <div class="col-6"><input type="number" name="min_legend_wp" class="form-control form-control-dark form-control-sm" placeholder="Legend Wp" title="Minimum Legendary Weapon"></div>
                                <div class="col-6"><input type="number" name="min_prestige" class="form-control form-control-dark form-control-sm" placeholder="Prestige" title="Minimum Prestige Weapon"></div>
                                <div class="col-6"><input type="number" name="min_mythic_ch" class="form-control form-control-dark form-control-sm" placeholder="Mythic Ch" title="Minimum Mythic Character"></div>
                                <div class="col-6"><input type="number" name="min_legend_ch" class="form-control form-control-dark form-control-sm" placeholder="Legend Ch" title="Minimum Legendary Character"></div>
                                <div class="col-6"><input type="number" name="min_vehicle" class="form-control form-control-dark form-control-sm" placeholder="Vehicle" title="Minimum Legendary Vehicle"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Urutkan Berdasarkan</label>
                            <select name="sort" class="form-select form-control-dark form-select-sm">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>View Terbanyak</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-2"><i class="bi bi-search me-2"></i> Terapkan Filter</button>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary w-100 mt-2 py-2">Reset</a>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-grow-1">
            <h5 class="fw-bold text-white section-title"><i class="bi bi-grid-fill text-gold me-2"></i> KATALOG AKUN</h5>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-3">
                @forelse($regularAccounts as $listing)
                    <div class="col">
                        <div class="card card-gaming h-100 border-0 overflow-hidden text-decoration-none">
                            <a href="{{ route('marketplace.show', $listing->slug) }}" class="text-decoration-none">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/'.$listing->thumbnail) }}" class="card-img-top object-fit-cover" height="180" alt="Thumbnail">
                                    <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark border border-secondary text-white opacity-75"><i class="bi bi-eye"></i> {{ $listing->view_count }}</span>
                                </div>
                                <div class="card-body bg-dark-card p-3">
                                    <h6 class="card-title fw-bold text-white text-truncate mb-2" title="{{ $listing->title }}">{{ $listing->title }}</h6>
                                    <h5 class="text-gold fw-bold mb-3">Rp {{ number_format($listing->price, 0, ',', '.') }}</h5>
                                    
                                    <div class="d-flex flex-wrap gap-2 mb-0">
                                        <div class="stat-box" title="Level Akun"><i class="bi bi-person-up text-secondary"></i> <span>{{ $listing->level }}</span></div>
                                        <div class="stat-box" title="Mythic Weapon"><i class="bi bi-crosshair text-danger"></i> <span>{{ $listing->mythic_weapon_count }}</span></div>
                                        <div class="stat-box" title="Prestige Weapon"><i class="bi bi-shield-shaded text-info"></i> <span>{{ $listing->prestige_weapon_count }}</span></div>
                                        <div class="stat-box" title="Legendary Weapon"><i class="bi bi-lightning-fill text-warning"></i> <span>{{ $listing->legendary_weapon_count }}</span></div>
                                        <div class="stat-box" title="Mythic Character"><i class="bi bi-person-fill-gear text-danger"></i> <span>{{ $listing->mythic_character_count }}</span></div>
                                        <div class="stat-box" title="Legendary Character"><i class="bi bi-person-fill text-warning"></i> <span>{{ $listing->legendary_character_count }}</span></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    @if($featuredAccounts->isEmpty())
                        <div class="col-12">
                            <div class="card bg-dark-card border-0 py-5 text-center rounded-4">
                                <i class="bi bi-search text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-white">Tidak Ada Akun Ditemukan</h5>
                                <p class="text-muted small">Coba ubah filter pencarian Anda atau kembali ke halaman utama.</p>
                            </div>
                        </div>
                    @endif
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5" data-bs-theme="dark">
                {{ $listings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
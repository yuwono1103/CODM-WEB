<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CODM Marketplace</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #f8f9fa;
        }
        
        /* Tema Warna & Komponen Dasar */
        .bg-dark-card { background-color: #1E1E1E !important; }
        .text-gold { color: #FFC107 !important; }
        .border-gold { border-color: #FFC107 !important; }
        
        /* Tombol Gaming */
        .btn-gold {
            background-color: #FFC107;
            color: #121212;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: #e0a800;
            color: #121212;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }
        .btn-outline-gold {
            border: 1px solid #FFC107;
            color: #FFC107;
            background: transparent;
            transition: all 0.3s ease;
        }
        .btn-outline-gold:hover {
            background-color: #FFC107;
            color: #121212;
        }

        /* Card Marketplace & Dashboard */
        .card-gaming {
            background-color: #1E1E1E;
            border: 1px solid #333;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .card-gaming:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            border-color: #FFC107;
        }

        /* Navbar Custom */
        .navbar-gaming {
            background-color: #0a0a0a !important;
            border-bottom: 2px solid #FFC107;
        }
        .navbar-gaming .nav-link {
            color: #ccc;
            font-weight: 500;
            transition: color 0.3s;
        }
        .navbar-gaming .nav-link:hover {
            color: #FFC107;
        }

        /* Status Badges */
        .badge-aktif { background-color: rgba(25, 135, 84, 0.15); color: #20c997; border: 1px solid #198754; }
        .badge-pending { background-color: rgba(255, 193, 7, 0.15); color: #FFC107; border: 1px solid #FFC107; }
        .badge-ditolak { background-color: rgba(220, 53, 69, 0.15); color: #ff6b6b; border: 1px solid #dc3545; }
        .badge-expired { background-color: rgba(108, 117, 125, 0.15); color: #adb5bd; border: 1px solid #6c757d; }
        .badge-featured { background: linear-gradient(45deg, #FFC107, #ff8c00); color: #000; font-weight: bold; border: none; }

        /* Custom Table Dark Mode */
        .table-gaming { color: #fff; vertical-align: middle; margin-bottom: 0; }
        .table-gaming th { background-color: #151515; border-bottom: 1px solid #333; color: #adb5bd; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; padding: 1rem; }
        .table-gaming td { background-color: #1E1E1E; border-bottom: 1px solid #333; padding: 1rem; }
        .table-gaming tbody tr:hover td { background-color: #252525; }
        
        /* Form Inputs */
        .form-control-dark {
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: #fff;
        }
        .form-control-dark:focus {
            background-color: #2a2a2a;
            border-color: #FFC107;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }

        /* Sidebar presisi */
        .sidebar-wrapper {
            width: 280px;
            flex-shrink: 0;
        }
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                width: 100%;
            }
        }
        .sidebar-scroll {
            max-height: 85vh;
            overflow-y: auto;
        }
        /* Scrollbar custom untuk sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #121212; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #FFC107; }

        /* Stat Badge untuk Card (Padat & Informatif) */
        .stat-box {
            background-color: #151515;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #adb5bd;
        }
        .stat-box span { font-weight: 600; color: #fff; }

        /* Section Header */
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #2a2a2a;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 60px;
            height: 2px;
            background-color: #FFC107;
        }

        /* Helper Background */
        .bg-gold { background-color: #FFC107 !important; }

        /* Stat Box Khusus Hero/Featured */
        .stat-box-hero {
            background-color: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
        }

        /* Gambar Preview Kotak-kotak di sebelah kanan Hero */
        .steam-preview-img {
            height: 200px;
            transition: opacity 0.3s;
        }
        .steam-preview-img:hover {
            opacity: 0.8;
        }

        /* Custom Navigasi Slider Featured */
        .featured-control {
            width: 5%;
            opacity: 0;
            transition: opacity 0.3s;
        }
        #featuredCarousel:hover .featured-control {
            opacity: 1;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            padding: 20px;
        }

        /* Memastikan card tetap mempertahankan warna gelapnya dan tidak tertembus background */
        .card, .bg-dark-card {
            background-color: #1b1b1b !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important; 
        }

        /* Kustomisasi CSS Modal */
        .modal.fade .modal-dialog {
            transition: transform 0.25s ease-out, opacity 0.25s ease-out;
        }
        
        /* Gaya scrollbar tipis kustom di dalam modal jika panjang */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #131314;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #2a2a2c;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #ffc107;
        }
        
        /* Animasi Nadi (Pulse) untuk icon notifikasi */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }
        .bi-pulse {
            animation: pulse-ring 2s infinite;
            display: inline-block;
        }
    </style>
</head>
<body style="
    background-color: #121212 !important;
    background-image: linear-gradient(rgba(18, 18, 18, 0.50), rgba(18, 18, 18, 0.50)), url('/images/bg-codm.jpeg') !important;
    background-size: cover !important;
    background-attachment: fixed !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    min-height: 100vh;
">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-gaming shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2" href="{{ route('marketplace.index') }}">
                <i class="bi bi-crosshair text-gold fs-4"></i>
                <span>CODM <span class="text-gold">MARKET</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('marketplace.index') }}">Jelajahi Akun</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white fw-bold" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle text-gold me-1"></i> {{ Auth::user()->username ?? 'Akun Saya' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark bg-dark-card border-secondary mt-2 shadow">
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2 text-warning"></i> Dashboard Admin</a></li>
                                @else
                                    <li><a class="dropdown-item py-2" href="{{ route('seller.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-gold"></i> Dashboard Seller</a></li>
                                @endif
                                
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.listings.create') }}" class="btn btn-gold rounded-pill px-4 py-2">
                                <i class="bi bi-plus-lg me-1"></i> Jual Akun
                            </a>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-outline-gold rounded-pill px-4" href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @auth
        @php
            // Cek apakah user login punya transaksi rekber yang sedang jalan (sebagai pembeli ATAU penjual)
            $notifEscrows = \App\Models\Escrow::with('listing')
                ->where(function($q) {
                    $q->where('buyer_id', auth()->id())
                      ->orWhere('seller_id', auth()->id());
                })
                ->whereIn('status', ['diproses', 'group_dibuat'])
                ->get();
        @endphp

        @foreach($notifEscrows as $notif)
            <div class="alert alert-warning alert-dismissible fade show rounded-0 mb-0 border-0 border-bottom border-warning text-center shadow-sm" style="background-color: #2a2205; color: #f3af22;" role="alert">
                <i class="bi bi-bell-fill me-2 bi-pulse text-warning"></i>
                
                @if($notif->status == 'diproses')
                    Transaksi rekber <strong>{{ Str::limit($notif->listing->title, 25) }}</strong> sedang disiapkan oleh Admin! 
                    Mohon tunggu, Admin sedang mengecek data dan akan segera membuat Grup WhatsApp.
                @elseif($notif->status == 'group_dibuat')
                    Grup WhatsApp untuk transaksi <strong>{{ Str::limit($notif->listing->title, 25) }}</strong> telah dibuat oleh Admin! 
                    <span class="text-white text-decoration-underline ms-1">Silakan cek aplikasi WhatsApp Anda sekarang.</span>
                @endif
                
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem; padding-top: 1rem;"></button>
            </div>
        @endforeach
    @endauth
    <main class="py-5">
        @yield('content')
    </main>

    <div class="modal fade" id="rekberModal" tabindex="-1" aria-labelledby="rekberModalLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark-card border-gold" style="background-color: #121212;">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title fw-bold text-white" id="rekberModalLabel">
                        <i class="bi bi-shield-check text-gold me-2"></i> Pusat Edukasi Rekber CODM Market
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-light px-4">
                    <h6 class="text-info fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i> Apa Itu Rekber?</h6>
                    <p class="small mb-4 text-muted">Rekber (Rekening Bersama) adalah layanan pengamanan transaksi antara pembeli dan penjual. Dana pembeli akan ditahan sementara oleh CODM Market dan hanya diteruskan ke penjual setelah akun berhasil diterima sesuai deskripsi.</p>

                    <h6 class="text-primary fw-bold mb-2"><i class="bi bi-arrow-repeat me-2"></i> Cara Kerja Rekber</h6>
                    <ol class="small text-muted mb-4">
                        <li>Pembeli memilih akun yang diinginkan.</li>
                        <li>Pembeli melakukan pembayaran ke sistem rekber CODM Market.</li>
                        <li>Penjual menyerahkan akun kepada pembeli.</li>
                        <li>Pembeli memeriksa akun sesuai deskripsi.</li>
                        <li>Jika akun sesuai, dana diteruskan ke penjual.</li>
                        <li>Transaksi selesai.</li>
                    </ol>

                    <h6 class="text-success fw-bold mb-2"><i class="bi bi-check-circle-fill me-2"></i> Keuntungan Rekber</h6>
                    <ul class="small text-muted mb-0" style="list-style-type: none; padding-left: 0;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Mengurangi risiko penipuan.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Dana lebih aman.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Ada bukti transaksi.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Ada bantuan admin jika terjadi sengketa.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Pembeli dapat memeriksa akun terlebih dahulu.</li>
                    </ul>
                </div>
                <div class="modal-footer border-top border-dark d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal" id="btnMengertiRekber">Saya Mengerti</button>
                    <button type="button" class="btn btn-gold fw-bold px-4" data-bs-dismiss="modal">Mulai Cari Akun</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (!localStorage.getItem('rekber_popup_shown')) {
                var rekberModal = new bootstrap.Modal(document.getElementById('rekberModal'));
                rekberModal.show();
                
                document.getElementById('btnMengertiRekber').addEventListener('click', function() {
                    localStorage.setItem('rekber_popup_shown', 'true');
                });
                
                document.getElementById('rekberModal').addEventListener('hidden.bs.modal', function () {
                    localStorage.setItem('rekber_popup_shown', 'true');
                });
            }
        });
    </script>

    <div class="floating-wa-container" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
        <div class="wa-tooltip bg-dark text-white p-2 rounded-3 shadow border border-secondary text-center" style="font-size: 0.75rem; min-width: 130px; opacity: 0.9;">
            Butuh bantuan?<br>
            <strong class="text-success" style="letter-spacing: 0.5px;">Hubungi Admin</strong>
        </div>
        <a href="https://wa.me/{{ $siteSettings['admin_wa'] ?? '6281234567890' }}?text=Halo%20Admin%20Marketplace%20CODM,%20saya%20butuh%20bantuan%20terkait..." target="_blank" class="wa-button d-flex align-items-center justify-content-center shadow-lg" style="width: 55px; height: 55px; background-color: #25D366; color: white; border-radius: 50%; text-decoration: none; font-size: 1.8rem; transition: transform 0.2s ease;">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>

    <style>
        .wa-button:hover { transform: scale(1.1); color: white; }
        .wa-tooltip { transition: opacity 0.3s; }
        .floating-wa-container:hover .wa-tooltip { opacity: 1 !important; }
    </style>
</body>
</html>
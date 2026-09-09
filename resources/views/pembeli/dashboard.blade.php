@extends('layouts.pembeli')

@section('title', 'Dashboard Pembeli')

@push('styles')
<style>
    /* HERO WELCOME BANNER */
    .hero-welcome {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
        border-radius: 24px;
        padding: 32px 36px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
        box-shadow: 0 12px 32px rgba(37, 99, 235, 0.2);
    }
    .hero-welcome::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-welcome::after {
        content: '';
        position: absolute;
        bottom: -80px;
        right: 120px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,122,89,0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,0.25);
    }
    .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.2;
    }
    .hero-title span {
        color: #ffedd5;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .hero-desc {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.88);
        max-width: 620px;
        line-height: 1.6;
        margin-bottom: 0;
    }
    .hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }
    .btn-hero-primary {
        background: var(--coral);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 22px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 6px 18px rgba(255, 122, 89, 0.4);
    }
    .btn-hero-primary:hover {
        background: var(--coral-dark);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 122, 89, 0.5);
    }
    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-weight: 600;
        font-size: 13px;
        padding: 10px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: all 0.25s ease;
    }
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .hero-illustration {
        width: 110px;
        height: 110px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    /* STATS GRID */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 32px;
    }
    .stat-card-dash {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.06);
        transition: all 0.28s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card-dash:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(37, 99, 235, 0.12);
        border-color: #cbd5e1;
    }
    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-green { background: #ecfdf5; color: #10b981; }
    .icon-orange { background: #fff7ed; color: #f59e0b; }
    .icon-red { background: #fef2f2; color: #ef4444; }
    .stat-number {
        margin-top: 14px;
        font-size: 28px;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1;
    }
    .stat-label {
        margin-top: 6px;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 600;
    }
    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 14px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: gap 0.2s ease;
    }
    .stat-link:hover {
        gap: 8px;
        color: var(--primary-dark);
    }

    /* SECTION TITLES */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .section-title {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.3px;
    }
    .section-subtitle {
        margin: 3px 0 0;
        color: var(--text-muted);
        font-size: 12px;
    }
    .see-all {
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.2s ease;
    }
    .see-all:hover {
        gap: 7px;
        color: var(--primary-dark);
    }

    /* CATEGORIES GRID */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }
    .category-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 10px;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .category-card:hover {
        transform: translateY(-4px);
        background: #ffffff;
        border-color: #bfdbfe;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
        color: var(--primary);
    }
    .category-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.25s ease;
    }
    .category-card:hover .category-icon {
        background: #2563eb;
        color: #ffffff;
        transform: scale(1.1);
    }
    .category-card span {
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
    }

    /* PROMOTED SECTION */
    .sponsored-card {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1.5px solid #bfdbfe;
        border-radius: 22px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 10px 30px rgba(37,99,235,0.08);
    }
    .sponsored-badge {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }

    /* CONTENT LAYOUT & SIDEBAR */
    .content-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 310px;
        gap: 24px;
        align-items: start;
    }
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .sidebar-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.05);
    }
    .sidebar-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        font-size: 14px;
        font-weight: 800;
        color: var(--text-dark);
        border-bottom: 1px dashed var(--border-color);
        padding-bottom: 10px;
    }
    .quick-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .quick-menu li {
        margin-bottom: 8px;
    }
    .quick-menu a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 12px;
        color: var(--text-dark);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .quick-menu a:hover {
        background: #eff6ff;
        color: var(--primary);
        transform: translateX(3px);
    }
    .quick-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .quick-left i {
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #ffffff;
        color: var(--primary);
        font-size: 13px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .quick-badge {
        min-width: 22px;
        padding: 3px 8px;
        border-radius: 20px;
        background: var(--coral);
        color: #ffffff;
        text-align: center;
        font-size: 10px;
        font-weight: 800;
    }

    .creator-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .creator-item:last-child {
        border-bottom: 0;
    }
    .creator-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .creator-info {
        flex: 1;
        min-width: 0;
    }
    .creator-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .creator-sales {
        color: var(--text-muted);
        font-size: 10px;
    }
    .creator-rating {
        color: #f59e0b;
        font-size: 11px;
        font-weight: 700;
        background: #fffbeb;
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid #fef3c7;
    }

    /* RESPONSIVE */
    @media(max-width: 1200px) {
        .category-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media(max-width: 1000px) {
        .content-layout { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media(max-width: 700px) {
        .hero-welcome { padding: 24px; }
        .hero-illustration { display: none; }
        .hero-title { font-size: 22px; }
        .category-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; }
    }
    @media(max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .category-grid { grid-template-columns: repeat(4, 1fr); }
        .hero-actions { flex-direction: column; align-items: stretch; }
    }
</style>
@endpush

@section('content')

    {{-- HERO WELCOME BANNER --}}
    <section class="hero-welcome">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="hero-badge">
                    <i class="bi bi-stars"></i> Workspace Pembeli Karyaku
                </div>
                <h1 class="hero-title">Selamat Datang, <span>{{ auth()->user()->name ?? 'Pembeli' }}</span> 👋</h1>
                <p class="hero-desc">
                    Temukan dan miliki berbagai karya digital premium, aset kreatif terbaik, serta jasa profesional dari para kreator berbakat di seluruh Indonesia.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('pembeli.marketplace') }}" class="btn-hero-primary">
                        <i class="bi bi-shop"></i> Jelajahi Marketplace
                    </a>
                    <a href="{{ route('pembeli.pesanan') }}" class="btn-hero-secondary">
                        <i class="bi bi-receipt"></i> Cek Pesanan Saya
                    </a>
                </div>
            </div>
            <div class="hero-illustration d-none d-lg-flex">
                <i class="bi bi-bag-heart-fill"></i>
            </div>
        </div>
    </section>

    {{-- STATISTIK GRID --}}
    <section class="stats-grid">
        <div class="stat-card-dash">
            <div class="stat-top">
                <div class="stat-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div>
                <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size: 10px;">Total Pesanan</span>
            </div>
            <div class="stat-number">{{ number_format($totalPesanan ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Seluruh pesanan Anda</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link">Lihat Pesanan <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="stat-card-dash">
            <div class="stat-top">
                <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
                <span class="badge bg-success-subtle text-success fw-bold" style="font-size: 10px;">Selesai</span>
            </div>
            <div class="stat-number">{{ number_format($totalSelesai ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Transaksi berhasil</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link">Lihat Riwayat <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="stat-card-dash">
            <div class="stat-top">
                <div class="stat-icon icon-orange"><i class="bi bi-clock-history"></i></div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold" style="font-size: 10px;">Menunggu</span>
            </div>
            <div class="stat-number">{{ number_format($totalBelumBayar ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Perlu pembayaran</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link text-warning-emphasis">Cek Sekarang <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="stat-card-dash">
            <div class="stat-top">
                <div class="stat-icon icon-red"><i class="bi bi-cart-fill"></i></div>
                <span class="badge bg-danger-subtle text-danger fw-bold" style="font-size: 10px;">Keranjang</span>
            </div>
            <div class="stat-number">{{ number_format($totalKeranjang ?? $cartCount ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Produk di keranjang</div>
            <a href="{{ route('pembeli.keranjang') }}" class="stat-link text-danger">Buka Keranjang <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>

    {{-- JELAJAHI KATEGORI --}}
    <section class="mb-4">
        <div class="section-header">
            <div>
                <h3 class="section-title">Jelajahi Kategori</h3>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="see-all">Lihat Semua Kategori <i class="bi bi-chevron-right"></i></a>
        </div>

        <div class="category-grid">
            @php
                $catIcons = [
                    'desain' => 'bi-palette-fill',
                    'logo' => 'bi-vector-pen',
                    'ui/ux' => 'bi-phone-fill',
                    'website' => 'bi-code-slash',
                    'web' => 'bi-code-slash',
                    '3d' => 'bi-box-seam-fill',
                    'video' => 'bi-camera-video-fill',
                    'ilustrasi' => 'bi-image-fill',
                    'social' => 'bi-share-fill',
                    'jasa' => 'bi-briefcase-fill',
                ];
            @endphp
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $cat)
                    @php
                        $iconClass = 'bi-grid-fill';
                        $catNameLower = strtolower($cat->name);
                        foreach($catIcons as $key => $icon) {
                            if(str_contains($catNameLower, $key)) {
                                $iconClass = $icon;
                                break;
                            }
                        }
                    @endphp
                    <a href="{{ route('pembeli.marketplace', ['category' => $cat->id_category]) }}" class="category-card">
                        <div class="category-icon"><i class="bi {{ $cat->icon ?: $iconClass }}"></i></div>
                        <span>{{ $cat->name }}</span>
                    </a>
                @endforeach
            @else
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-palette-fill"></i></div><span>Desain</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-vector-pen"></i></div><span>Logo</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-phone-fill"></i></div><span>UI/UX</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-code-slash"></i></div><span>Website</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-box-seam-fill"></i></div><span>3D Model</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-camera-video-fill"></i></div><span>Video</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-image-fill"></i></div><span>Ilustrasi</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-share-fill"></i></div><span>Medsos</span></a>
            @endif
        </div>
    </section>

    {{-- SEKSI IKLAN & PROMOSI VIDEO (SPONSORED PRODUCTS) --}}
    @if(isset($promotedProducts) && $promotedProducts->count() > 0)
        <section class="sponsored-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="sponsored-badge">
                        <i class="bi bi-star-fill me-1"></i> IKLAN RESMI
                    </span>
                    <h4 class="fw-extrabold text-dark mb-0 fs-5">
                        <i class="bi bi-megaphone-fill text-primary me-2"></i>Iklan & Promosi Penjual Pilihan
                    </h4>
                </div>
                <span class="text-muted small d-none d-sm-inline">Promosi Terverifikasi Kreator Karyaku</span>
            </div>

            <div class="row g-3">
                @foreach($promotedProducts as $promo)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-shadow bg-white d-flex flex-column">
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2 shadow-sm" style="font-size: 10px; z-index: 5;">
                                <i class="bi bi-broadcast me-1"></i> IKLAN
                            </span>

                            @if($promo->video_url)
                                <div class="position-relative bg-black overflow-hidden" style="height: 160px;">
                                    <video src="{{ $promo->video_url }}" class="w-100 h-100 object-fit-cover opacity-85" muted loop playsinline></video>
                                    <button type="button" class="btn btn-light btn-sm rounded-circle position-absolute top-50 start-50 translate-middle shadow-lg d-flex align-items-center justify-content-center"
                                            style="width: 44px; height: 44px; background: rgba(255,255,255,0.92); border: none;"
                                            data-bs-toggle="modal" data-bs-target="#adVideoModal{{ $promo->id_product }}" title="Perbesar & Tonton Iklan">
                                        <i class="bi bi-play-fill text-primary fs-3 style-play-icon" style="margin-left: 2px;"></i>
                                    </button>
                                    <span class="position-absolute bottom-0 end-0 badge bg-dark bg-opacity-75 m-2 small" style="font-size:9px;">
                                        <i class="bi bi-fullscreen me-1"></i> Perbesar Video
                                    </span>
                                </div>
                            @else
                                <div class="position-relative overflow-hidden" style="height: 160px; background: #eff6ff;">
                                    <img src="{{ $promo->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $promo->title }}">
                                </div>
                            @endif

                            <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <div class="d-flex align-items-center gap-1.5 mb-1 text-truncate">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($promo->seller->name ?? 'Penjual') }}&background=dbeafe&color=1e3a8a" class="rounded-circle" style="width:18px;height:18px;object-fit:cover;">
                                        <span class="text-muted small text-truncate" style="font-size: 11px;">{{ $promo->seller->name ?? 'Penjual Karyaku' }}</span>
                                    </div>
                                    <h6 class="fw-bold text-dark text-truncate mb-1" title="{{ $promo->title }}">{{ $promo->title }}</h6>
                                    <div class="fw-extrabold text-primary mb-2" style="font-size: 14px;">
                                        Rp {{ number_format($promo->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="pt-2 border-top d-flex gap-1.5">
                                    @if($promo->video_url)
                                        <button type="button" class="btn btn-primary btn-sm flex-fill rounded-3 fw-bold" style="font-size: 11px;"
                                                data-bs-toggle="modal" data-bs-target="#adVideoModal{{ $promo->id_product }}">
                                            <i class="bi bi-play-circle-fill me-1"></i> Tonton Iklan
                                        </button>
                                    @endif
                                    <a href="{{ route('pembeli.produk.detail', $promo->id_product) }}" class="btn btn-outline-primary btn-sm flex-fill rounded-3 fw-bold" style="font-size: 11px;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL PERBESAR IKLAN VIDEO --}}
                    <div class="modal fade" id="adVideoModal{{ $promo->id_product }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                                <div class="modal-header bg-dark text-white border-0 py-3">
                                    <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                                        <i class="bi bi-film text-warning fs-5"></i> Iklan Promosi: {{ $promo->title }}
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 bg-black text-center position-relative">
                                    @if($promo->video_url)
                                        <video controls autoplay loop class="w-100 rounded-0" style="max-height: 440px; object-fit: contain;">
                                            <source src="{{ $promo->video_url }}" type="video/mp4">
                                            Browser Anda tidak mendukung penayangan video ini.
                                        </video>
                                    @else
                                        <img src="{{ $promo->image_url }}" class="w-100 object-fit-contain" style="max-height: 380px;" alt="{{ $promo->title }}">
                                    @endif
                                </div>
                                <div class="modal-footer bg-white d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 gap-2">
                                    <div class="text-start">
                                        <h6 class="fw-bold text-dark mb-0 fs-6">{{ $promo->title }}</h6>
                                        <span class="text-primary fw-extrabold small">Rp {{ number_format($promo->price, 0, ',', '.') }}</span>
                                        <span class="text-muted ms-2 small">&bull; Penjual: {{ $promo->seller->name ?? 'Kreator Karyaku' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-end">
                                        {{-- TOMBOL INFO APLIKASI LEBIH LANJUT --}}
                                        <button type="button" class="btn btn-light border btn-sm rounded-3 fw-bold text-dark px-3 py-2" data-bs-toggle="modal" data-bs-target="#appInfoModal">
                                            <i class="bi bi-info-circle-fill text-primary me-1"></i> Info Aplikasi Lebih Lanjut
                                        </button>
                                        {{-- TOMBOL INFO PRODUK LEBIH LANJUT --}}
                                        <a href="{{ route('pembeli.produk.detail', $promo->id_product) }}" class="btn btn-primary btn-sm rounded-3 fw-bold px-3 py-2">
                                            <i class="bi bi-bag-check-fill me-1"></i> Info Produk Lebih Lanjut
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- MODAL INFO APLIKASI KARYAKU --}}
        <div class="modal fade" id="appInfoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header bg-primary text-white border-0 py-3">
                        <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check fs-5 text-warning"></i> Tentang Aplikasi Karyaku
                        </h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" style="font-size: 13px; color: #334155; line-height: 1.7;">
                        <div class="text-center mb-3">
                            <div class="bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center rounded-circle p-3 mb-2" style="width: 60px; height: 60px;">
                                <i class="bi bi-bag-heart-fill fs-2"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Karyaku Digital Marketplace</h5>
                            <span class="badge bg-primary text-white px-3 py-1 rounded-pill">Platform Resmi Digital & Jasa Indonesia</span>
                        </div>

                        <h6 class="fw-bold text-dark mt-3"><i class="bi bi-check-circle-fill text-success me-1"></i> Mengapa Membeli di Karyaku?</h6>
                        <ul class="ps-3 mb-3">
                            <li><strong>Jaminan Akses File Unduhan:</strong> Setelah pembayaran dikonfirmasi, Anda dapat langsung mengunduh file produk digital tanpa batas waktu.</li>
                            <li><strong>Transaksi Aman & Terverifikasi:</strong> Seluruh identitas penjual dan produk melewati sistem verifikasi resmi.</li>
                            <li><strong>Dukungan CS 24/7:</strong> Layanan Tiket Customer Service siap membantu kendala Anda kapan pun.</li>
                        </ul>

                        <h6 class="fw-bold text-dark"><i class="bi bi-star-fill text-warning me-1"></i> Fitur Iklan & Promosi Penjual</h6>
                        <p class="mb-2">
                            Fitur Iklan Video di Karyaku memungkinkan penjual membagikan video promosi singkat berdurasi hingga <strong>10 detik</strong> dengan ukuran <strong>maksimal 10 MB</strong> untuk memperkenalkan karya digital terbaiknya secara langsung kepada para pembeli.
                        </p>
                    </div>
                    <div class="modal-footer border-top p-3 justify-content-center">
                        <button type="button" class="btn btn-primary rounded-3 fw-bold px-4" data-bs-dismiss="modal">
                            <i class="bi bi-check2-circle me-1"></i> Saya Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- PRODUK REKOMENDASI --}}
    <section>
        <div class="section-header">
            <div>
                <h3 class="section-title">Rekomendasi Karya Untukmu</h3>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>
        
        <div class="product-grid w-100" id="productGrid">
            @forelse($rekomendasi ?? [] as $product)
                @include('pembeli.partials.product-card', ['product' => $product])
            @empty
                <div class="w-100 grid-column-full text-center py-5 bg-white rounded-4 border border-light shadow-sm" style="grid-column: 1 / -1;">
                    <i class="bi bi-box-seam display-4 text-muted mb-3 d-block"></i>
                    <h6 class="fw-bold text-dark fs-5">Belum Ada Produk Tersedia</h6>
                    <p class="text-muted small mb-0">Silakan kembali lagi nanti untuk melihat produk terbaru dari para kreator.</p>
                </div>
            @endforelse
        </div>
    </section>

@endsection
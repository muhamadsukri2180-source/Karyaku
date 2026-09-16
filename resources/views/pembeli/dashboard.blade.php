@extends('layouts.pembeli')

@section('title', 'Dashboard Pembeli')

@push('styles')
<style>
    /* ==========================================================================
       1. TOP HERO SECTION: FULL-BLEED AD VIDEO CARD (LEFT) + POPULAR GRID (RIGHT)
       ========================================================================== */
    .top-hero-section {
        margin-bottom: 32px;
    }

    /* Left Ad Hero Card (Full Bleed Video Banner Landscape 16:9) */
    .ad-hero-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        max-height: 420px;
        background: #000000;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .ad-hero-track {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .ad-hero-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        align-items: stretch;
    }

    .ad-fullscreen-link {
        display: block;
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        cursor: pointer;
    }

    .ad-media-full {
        width: 100%;
        height: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .ad-fullscreen-link:hover .ad-media-full {
        transform: scale(1.02);
    }

    .ad-placeholder-full {
        width: 100%;
        height: 100%;
        min-height: 380px;
        background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
    }

    .ad-tag-floating {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 5;
    }
    .ad-badge-clean {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.2);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .ad-sound-btn {
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        z-index: 10;
        backdrop-filter: blur(6px);
        transition: all 0.2s ease;
    }
    .ad-sound-btn:hover {
        transform: scale(1.1);
        background: #1ed760;
        color: #000;
    }

    /* Left & Right Circular Navigation Arrows */
    .ad-nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.65);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        backdrop-filter: blur(6px);
    }
    .ad-nav-arrow:hover {
        background: rgba(15, 23, 42, 0.95);
        color: #ffffff;
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
    }
    .ad-nav-arrow.prev { left: 14px; }

    /* Tooltip Next Pill */
    .ad-next-wrapper {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 10;
    }
    .ad-next-pill {
        background: rgba(15, 23, 42, 0.8);
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(6px);
        pointer-events: none;
    }

    /* Bottom Controls: Animated Circular Pie Timer & Dots */
    .ad-bottom-bar {
        position: absolute;
        bottom: 14px;
        left: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        pointer-events: none;
    }
    .ad-timer-container {
        position: absolute;
        left: 16px;
        bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(15, 23, 42, 0.6);
        padding: 4px 10px;
        border-radius: 16px;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.9);
        font-size: 11px;
        font-weight: 700;
    }
    .ad-timer-svg {
        width: 16px;
        height: 16px;
        transform: rotate(-90deg);
    }
    .ad-timer-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.25);
        stroke-width: 3.5;
    }
    .ad-timer-progress {
        fill: none;
        stroke: #ffffff;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 100;
        stroke-dashoffset: 0;
        transition: stroke-dashoffset 0.1s linear;
    }

    .ad-dots-list {
        display: flex;
        align-items: center;
        gap: 6px;
        pointer-events: auto;
    }
    .ad-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.25s ease;
        border: none;
        padding: 0;
    }
    .ad-dot.active {
        background: #ffffff;
        width: 22px;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
    }

    /* Right Column: Popular Products Showcase */
    .hero-popular-col {
        display: flex;
        flex-direction: column;
        gap: 14px;
        height: 100%;
        min-height: 380px;
    }

    .hero-pop-card-top {
        flex: 1.25;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: #1e293b;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.12);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: block;
        min-height: 200px;
    }
    .hero-pop-card-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.2);
    }

    .hero-pop-bottom-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        flex: 0.95;
    }

    .hero-pop-card-sm {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        background: #1e293b;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: block;
        min-height: 140px;
    }
    .hero-pop-card-sm:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
    }

    .pop-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        position: absolute;
        inset: 0;
        transition: transform 0.35s ease;
    }
    .hero-pop-card-top:hover .pop-card-img,
    .hero-pop-card-sm:hover .pop-card-img {
        transform: scale(1.05);
    }

    .pop-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.05) 35%, rgba(0, 0, 0, 0.88) 100%);
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 2;
    }
    .pop-card-overlay-sm {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.88) 100%);
        padding: 14px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        z-index: 2;
    }

    .pop-badge-pill {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .pop-price-pill {
        background: rgba(16, 185, 129, 0.9);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 800;
    }
    .pop-title-text {
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .pop-meta-text {
        color: rgba(255, 255, 255, 0.82);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pop-title-sm {
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .pop-price-sm {
        color: #34d399;
        font-size: 12.5px;
        font-weight: 800;
    }
    .pop-seller-sm {
        color: rgba(255, 255, 255, 0.75);
        font-size: 11px;
        max-width: 90px;
    }

    /* ==========================================================================
       2. STATS & CATEGORIES
       ========================================================================== */
    .stat-card-dash {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card-dash:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        border-color: #cbd5e1;
    }
    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-icon {
        width: 46px;
        height: 46px;
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
        padding: 14px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 8px;
        color: var(--text-dark);
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }
    .category-card:hover {
        transform: translateY(-4px);
        border-color: #93c5fd;
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
        transition: var(--transition);
    }
    .category-card:hover .category-icon {
        background: #2563eb;
        color: #ffffff;
        transform: scale(1.08);
    }

    /* RESPONSIVE */
    @media(max-width: 1200px) {
        .category-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media(max-width: 992px) {
        .ad-hero-wrapper {
            min-height: 280px;
            height: 280px;
        }
        .ad-media-full, .ad-placeholder-full {
            min-height: 280px;
        }
        .hero-popular-col {
            min-height: auto;
        }
        .hero-pop-card-top {
            min-height: 180px;
            height: 180px;
        }
        .hero-pop-card-sm {
            min-height: 130px;
            height: 130px;
        }
        .ad-next-pill { display: none; }
    }
    @media(max-width: 576px) {
        .category-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .hero-pop-bottom-row { grid-template-columns: 1fr; }
        .ad-timer-container { display: none; }
    }
</style>
@endpush

@section('content')

    {{-- =========================================================================
         1. TOP HERO SECTION: FULL-BLEED SELLER AD (LEFT) + POPULAR PRODUCTS (RIGHT)
         ========================================================================= --}}
    @php
        // Iklan Penjual (HANYA BERUPA VIDEO LANDSCAPE, maks 10 detik)
        $sellerAds = isset($promotedProducts) && $promotedProducts->count() > 0 
            ? $promotedProducts->filter(fn($ad) => !empty($ad->video_url))->values()
            : collect();

        // Produk Populer & Terlaris untuk kolom kanan
        $popList = isset($popularProducts) && $popularProducts->count() > 0 
            ? $popularProducts 
            : ($rekomendasi ?? collect());
        $pop1 = $popList->get(0);
        $pop2 = $popList->get(1);
        $pop3 = $popList->get(2);
    @endphp

    <section class="top-hero-section">
        <div class="row g-3 align-items-stretch">
            {{-- KOLOM KIRI: CARD IKLAN PENJUAL (KHUSUS VIDEO LANDSCAPE MAKS 10 DETIK) --}}
            <div class="col-12 col-lg-7">
                <div class="ad-hero-wrapper" id="adHeroBanner">
                    <div class="ad-hero-track" id="adHeroTrack">
                        @forelse($sellerAds as $idx => $ad)
                            @php
                                $adUrl = route('pembeli.produk.detail', $ad->id_product);
                            @endphp
                            <div class="ad-hero-slide" data-slide-index="{{ $idx }}">
                                <a href="{{ $adUrl }}" class="ad-fullscreen-link" title="{{ $ad->title }}">
                                    <video src="{{ $ad->video_url }}" autoplay muted loop playsinline class="ad-media-full" ontimeupdate="if(this.currentTime>=10){ this.currentTime=0; }"></video>

                                    {{-- Tag Iklan Video Landscape Minimalis di Pojok --}}
                                    <div class="ad-tag-floating">
                                        <span class="ad-badge-clean">
                                            <i class="bi bi-camera-reels-fill text-warning me-1"></i> Iklan Video (10s Landscape)
                                        </span>
                                    </div>
                                </a>

                                <button type="button" class="ad-sound-btn" onclick="event.preventDefault(); event.stopPropagation(); toggleAdSound(this);" title="Nyalakan/Matikan Suara">
                                    <i class="bi bi-volume-mute-fill"></i>
                                </button>
                            </div>
                        @empty
                            <div class="ad-hero-slide" data-slide-index="0">
                                <a href="{{ route('pembeli.marketplace') }}" class="ad-fullscreen-link">
                                    <div class="ad-placeholder-full">
                                        <i class="bi bi-film display-3 mb-2 text-warning"></i>
                                        <h5 class="fw-bold">Iklan Video Promosi Produk</h5>
                                        <p class="small text-white-50 mb-0">Hanya menampilkan iklan berformat video landscape (Maksimal 10 detik)</p>
                                    </div>
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol Navigasi Panah Geser Kiri / Kanan --}}
                    <button type="button" class="ad-nav-arrow prev" id="adHeroPrev" aria-label="Iklan Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="ad-next-wrapper">
                        <span class="ad-next-pill">Berikutnya</span>
                        <button type="button" class="ad-nav-arrow next" id="adHeroNext" aria-label="Iklan Berikutnya" style="position: static; transform: none;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>

                    {{-- Bottom Controls (Animated SVG Pie Timer & Pagination Dots) --}}
                    <div class="ad-bottom-bar">
                        <div class="ad-timer-container" title="Iklan otomatis berganti setiap 10 detik">
                            <svg class="ad-timer-svg" viewBox="0 0 36 36">
                                <path class="ad-timer-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path id="adTimerProgress" class="ad-timer-progress" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <span>10s</span>
                        </div>
                        <div class="ad-dots-list" id="adDotsList">
                            @for($i = 0; $i < max(1, count($sellerAds)); $i++)
                                <button type="button" class="ad-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Iklan {{ $i + 1 }}"></button>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PRODUK POPULER (1 CARD BESAR ATAS + 2 CARD BAWAH) --}}
            <div class="col-12 col-lg-5">
                <div class="hero-popular-col">
                    {{-- Top Big Popular Card --}}
                    @if($pop1)
                        <a href="{{ route('pembeli.produk.detail', $pop1->id_product) }}" class="hero-pop-card-top text-decoration-none" title="{{ $pop1->title }}">
                            <img src="{{ $pop1->image_url }}" alt="{{ $pop1->title }}" class="pop-card-img" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pop1->title) }}&background=2563eb&color=fff&size=512&bold=true'">
                            <div class="pop-card-overlay">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="pop-badge-pill">
                                        <i class="bi bi-stars text-warning"></i> Populer
                                    </span>
                                    <span class="pop-price-pill">
                                        Rp {{ number_format($pop1->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div>
                                    <h5 class="pop-title-text text-truncate">{{ $pop1->title }}</h5>
                                    <div class="pop-meta-text">
                                        <span><i class="bi bi-person-fill"></i> {{ $pop1->seller->name ?? 'Kreator' }}</span>
                                        <span>&bull;</span>
                                        <span><i class="bi bi-tag-fill"></i> {{ $pop1->category->name ?? 'Aset Digital' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                    {{-- Bottom 2 Mini Popular Cards --}}
                    <div class="hero-pop-bottom-row">
                        @if($pop2)
                            <a href="{{ route('pembeli.produk.detail', $pop2->id_product) }}" class="hero-pop-card-sm text-decoration-none" title="{{ $pop2->title }}">
                                <img src="{{ $pop2->image_url }}" alt="{{ $pop2->title }}" class="pop-card-img" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pop2->title) }}&background=4f46e5&color=fff&size=256&bold=true'">
                                <div class="pop-card-overlay-sm">
                                    <h6 class="pop-title-sm text-truncate">{{ $pop2->title }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pop-price-sm">Rp {{ number_format($pop2->price, 0, ',', '.') }}</span>
                                        <span class="pop-seller-sm text-truncate">{{ $pop2->seller->name ?? '' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if($pop3)
                            <a href="{{ route('pembeli.produk.detail', $pop3->id_product) }}" class="hero-pop-card-sm text-decoration-none" title="{{ $pop3->title }}">
                                <img src="{{ $pop3->image_url }}" alt="{{ $pop3->title }}" class="pop-card-img" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pop3->title) }}&background=059669&color=fff&size=256&bold=true'">
                                <div class="pop-card-overlay-sm">
                                    <h6 class="pop-title-sm text-truncate">{{ $pop3->title }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pop-price-sm">Rp {{ number_format($pop3->price, 0, ',', '.') }}</span>
                                        <span class="pop-seller-sm text-truncate">{{ $pop3->seller->name ?? '' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================================
         2. STATISTIK TRANSAKSI & BELANJA
         ========================================================================= --}}
    <section class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-dash h-100">
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div>
                    <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size: 10px;">Total Pesanan</span>
                </div>
                <div class="mt-3">
                    <div class="h4 fw-extrabold text-dark mb-0">{{ number_format($totalPesanan ?? 0, 0, ',', '.') }}</div>
                    <div class="text-muted small" style="font-size: 11px;">Semua pesanan Anda</div>
                </div>
                <a href="{{ route('pembeli.pesanan') }}" class="small fw-bold text-primary text-decoration-none mt-2 d-inline-flex align-items-center gap-1">
                    Lihat Pesanan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card-dash h-100">
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
                    <span class="badge bg-success-subtle text-success fw-bold" style="font-size: 10px;">Selesai</span>
                </div>
                <div class="mt-3">
                    <div class="h4 fw-extrabold text-dark mb-0">{{ number_format($totalSelesai ?? 0, 0, ',', '.') }}</div>
                    <div class="text-muted small" style="font-size: 11px;">Transaksi lunas</div>
                </div>
                <a href="{{ route('pembeli.pesanan', ['tab' => 'selesai']) }}" class="small fw-bold text-success text-decoration-none mt-2 d-inline-flex align-items-center gap-1">
                    Riwayat Lunas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card-dash h-100">
                <div class="stat-top">
                    <div class="stat-icon icon-orange"><i class="bi bi-clock-history"></i></div>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-bold" style="font-size: 10px;">Menunggu</span>
                </div>
                <div class="mt-3">
                    <div class="h4 fw-extrabold text-dark mb-0">{{ number_format($totalBelumBayar ?? 0, 0, ',', '.') }}</div>
                    <div class="text-muted small" style="font-size: 11px;">Perlu pembayaran</div>
                </div>
                <a href="{{ route('pembeli.pesanan', ['tab' => 'diproses']) }}" class="small fw-bold text-warning-emphasis text-decoration-none mt-2 d-inline-flex align-items-center gap-1">
                    Bayar Sekarang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card-dash h-100">
                <div class="stat-top">
                    <div class="stat-icon icon-red"><i class="bi bi-cart-fill"></i></div>
                    <span class="badge bg-danger-subtle text-danger fw-bold" style="font-size: 10px;">Keranjang</span>
                </div>
                <div class="mt-3">
                    <div class="h4 fw-extrabold text-dark mb-0">{{ number_format($totalKeranjang ?? 0, 0, ',', '.') }}</div>
                    <div class="text-muted small" style="font-size: 11px;">Item tersimpan</div>
                </div>
                <a href="{{ route('pembeli.keranjang') }}" class="small fw-bold text-danger text-decoration-none mt-2 d-inline-flex align-items-center gap-1">
                    Buka Keranjang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================================
         3. JELAJAHI KATEGORI KARYA DIGITAL
         ========================================================================= --}}
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 fs-5">Jelajahi Kategori</h4>
                <p class="text-muted small mb-0">Temukan aset berdasarkan bidang keahlian</p>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="small fw-bold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                Semua Kategori <i class="bi bi-chevron-right"></i>
            </a>
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
                    <a href="{{ route('pembeli.marketplace', ['category' => $cat->id_category]) }}" class="category-card text-decoration-none">
                        <div class="category-icon"><i class="bi {{ $cat->icon ?: $iconClass }}"></i></div>
                        <span class="small fw-bold">{{ $cat->name }}</span>
                    </a>
                @endforeach
            @else
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-palette-fill"></i></div><span class="small fw-bold">Desain</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-vector-pen"></i></div><span class="small fw-bold">Logo</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-phone-fill"></i></div><span class="small fw-bold">UI/UX</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-code-slash"></i></div><span class="small fw-bold">Website</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-box-seam-fill"></i></div><span class="small fw-bold">3D Model</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-camera-video-fill"></i></div><span class="small fw-bold">Video</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-image-fill"></i></div><span class="small fw-bold">Ilustrasi</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card text-decoration-none"><div class="category-icon"><i class="bi bi-share-fill"></i></div><span class="small fw-bold">Medsos</span></a>
            @endif
        </div>
    </section>

    {{-- =========================================================================
         4. REKOMENDASI PRODUK KARYA TERBARU
         ========================================================================= --}}
    <section>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 fs-5">Rekomendasi Karya Untukmu</h4>
                <p class="text-muted small mb-0">Pilihan produk terpopuler dan terverifikasi</p>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="small fw-bold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                Lihat Semua <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        
        <div class="product-grid w-100" id="productGrid">
            @forelse($rekomendasi ?? [] as $product)
                @include('pembeli.partials.product-card', ['product' => $product])
            @empty
                <div class="w-100 text-center py-5 bg-white rounded-4 border shadow-sm" style="grid-column: 1 / -1;">
                    <i class="bi bi-box-seam display-4 text-muted mb-3 d-block"></i>
                    <h6 class="fw-bold text-dark fs-5">Belum Ada Produk Tersedia</h6>
                    <p class="text-muted small mb-0">Silakan kembali lagi nanti untuk melihat produk terbaru dari para kreator.</p>
                </div>
            @endforelse
        </div>
    </section>

@endsection

@push('scripts')
<script>
    function toggleAdSound(btn) {
        const slide = btn.closest('.ad-hero-slide');
        if (!slide) return;
        const video = slide.querySelector('video');
        if (!video) return;

        video.muted = !video.muted;
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = video.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
        }
    }

    (function() {
        const track = document.getElementById('adHeroTrack');
        const container = document.getElementById('adHeroBanner');
        const prevBtn = document.getElementById('adHeroPrev');
        const nextBtn = document.getElementById('adHeroNext');
        const dots = document.querySelectorAll('.ad-dot');
        const progressCircle = document.getElementById('adTimerProgress');
        const totalSlides = dots.length;

        if (!track || totalSlides <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            const nextWrapper = document.querySelector('.ad-next-wrapper');
            if (nextWrapper) nextWrapper.style.display = 'none';
            const timerContainer = document.querySelector('.ad-timer-container');
            if (timerContainer) timerContainer.style.display = 'none';
            return;
        }

        let currentIndex = 0;
        const AD_INTERVAL_MS = 10000; // 10 Detik
        let startTime = Date.now();
        let animationFrameId = null;
        let isPaused = false;
        let pausedElapsed = 0;

        function updateProgress() {
            if (isPaused) return;

            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / AD_INTERVAL_MS, 1);

            if (progressCircle) {
                const offset = 100 - (progress * 100);
                progressCircle.style.strokeDashoffset = offset;
            }

            if (elapsed >= AD_INTERVAL_MS) {
                nextSlide();
            } else {
                animationFrameId = requestAnimationFrame(updateProgress);
            }
        }

        function resetTimer() {
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            startTime = Date.now();
            if (progressCircle) {
                progressCircle.style.strokeDashoffset = 100;
            }
            if (!isPaused) {
                animationFrameId = requestAnimationFrame(updateProgress);
            }
        }

        function goToSlide(index) {
            if (index < 0) {
                currentIndex = totalSlides - 1;
            } else if (index >= totalSlides) {
                currentIndex = 0;
            } else {
                currentIndex = index;
            }

            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });

            // Restart video in current slide if exists
            const slides = track.querySelectorAll('.ad-hero-slide');
            slides.forEach((slide, i) => {
                const vid = slide.querySelector('video');
                if (vid) {
                    if (i === currentIndex) {
                        vid.currentTime = 0;
                        vid.play().catch(() => {});
                    } else {
                        vid.pause();
                    }
                }
            });

            resetTimer();
        }

        function nextSlide() {
            goToSlide(currentIndex + 1);
        }

        function prevSlide() {
            goToSlide(currentIndex - 1);
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                nextSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                prevSlide();
            });
        }

        dots.forEach((dot) => {
            dot.addEventListener('click', (e) => {
                e.stopPropagation();
                const target = parseInt(dot.getAttribute('data-slide'), 10);
                goToSlide(target);
            });
        });

        if (container) {
            container.addEventListener('mouseenter', () => {
                isPaused = true;
                pausedElapsed = Date.now() - startTime;
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
            });
            container.addEventListener('mouseleave', () => {
                isPaused = false;
                startTime = Date.now() - pausedElapsed;
                animationFrameId = requestAnimationFrame(updateProgress);
            });
        }

        // Jalankan autoplay 10 detik dengan progress animasi SVG
        resetTimer();
    })();
</script>
@endpush
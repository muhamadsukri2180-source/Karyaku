@extends('layouts.pembeli')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* WELCOME CARD */
    .welcome-card { background: #fff; border: 1px solid var(--border-color); border-radius: 18px; padding: 25px 28px; margin-bottom: 22px; box-shadow: var(--shadow); display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .welcome-title { margin: 0; font-size: 24px; font-weight: 800; }
    .welcome-title span { color: var(--primary); }
    .welcome-desc { margin: 7px 0 0; color: var(--text-muted); font-size: 12px; max-width: 700px; line-height: 1.7; }
    .welcome-icon { width: 100px; height: 100px; border-radius: 25px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 45px; }

    /* STATISTICS GRID */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card-dash { background: #fff; border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; box-shadow: var(--shadow); transition: all .2s ease; }
    .stat-card-dash:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .icon-blue { background: var(--primary-light); color: var(--primary); }
    .icon-green { background: #ecfdf5; color: #16a34a; }
    .icon-orange { background: #fff7ed; color: #f59e0b; }
    .icon-red { background: #fef2f2; color: #ef4444; }
    .stat-number { margin-top: 16px; font-size: 26px; font-weight: 800; }
    .stat-label { margin-top: 2px; color: var(--text-muted); font-size: 10px; }
    .stat-link { display: inline-flex; align-items: center; gap: 4px; margin-top: 10px; color: var(--primary); font-size: 9px; font-weight: 700; }

    /* SECTIONS */
    .section { margin-bottom: 30px; }
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; }
    .section-title { margin: 0; font-size: 19px; font-weight: 800; }
    .section-subtitle { margin: 4px 0 0; color: var(--text-muted); font-size: 11px; }
    .see-all { color: var(--primary); font-size: 11px; font-weight: 700; }

    /* LAYOUT & SIDEBAR */
    .content-layout { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 20px; align-items: start; }
    .sidebar { display: flex; flex-direction: column; gap: 16px; }
    .sidebar-card { background: #fff; border: 1px solid var(--border-color); border-radius: 16px; padding: 17px; box-shadow: var(--shadow); }
    .sidebar-title { display: flex; align-items: center; gap: 7px; margin-bottom: 12px; font-size: 13px; font-weight: 800; }
    .quick-menu { list-style: none; padding: 0; margin: 0; }
    .quick-menu li { margin-bottom: 5px; }
    .quick-menu a { display: flex; align-items: center; justify-content: space-between; padding: 9px 8px; border-radius: 9px; color: var(--text-dark); font-size: 10px; transition: all .2s ease; }
    .quick-menu a:hover { background: var(--primary-light); color: var(--primary); }
    .quick-left { display: flex; align-items: center; gap: 8px; }
    .quick-left i { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; border-radius: 6px; background: var(--primary-light); color: var(--primary); }
    .quick-badge { min-width: 20px; padding: 3px 6px; border-radius: 20px; background: var(--primary-soft); color: var(--primary); text-align: center; font-size: 8px; font-weight: 700; }

    .creator { display: flex; align-items: center; gap: 9px; padding: 8px 0; border-bottom: 1px solid #eef2ff; }
    .creator:last-child { border-bottom: 0; }
    .creator img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; }
    .creator-info { flex: 1; }
    .creator-name { font-size: 10px; font-weight: 700; }
    .creator-sales { color: var(--text-muted); font-size: 8px; }
    .creator-rating { color: #f59e0b; font-size: 9px; font-weight: 700; }

    /* RESPONSIVE */
    @media(max-width: 1000px) { .content-layout { grid-template-columns: 1fr; } .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 700px) { .welcome-card { padding: 20px; } .welcome-icon { display: none; } .welcome-title { font-size: 20px; } }
    @media(max-width: 450px) { .stats-grid { grid-template-columns: 1fr; } .section-title { font-size: 16px; } }
</style>
@endpush

@section('content')

    {{-- WELCOME CARD --}}
    <section class="welcome-card">
        <div>
            <h2 class="welcome-title">Halo, <span>{{ auth()->user()->name ?? 'Pembeli' }}</span> 👋</h2>
            <p class="welcome-desc">Selamat datang kembali di Dashboard Karyaku. Jelajahi berbagai karya digital premium, kelola pesananmu, dan temukan kreator favoritmu.</p>
        </div>
        <div class="welcome-icon"><i class="bi bi-bag-heart-fill"></i></div>
    </section>

    {{-- STATISTIK --}}
    <section class="stats-grid">
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div><i class="bi bi-arrow-up-right text-primary"></i></div>
            <div class="stat-number">{{ $totalPesanan ?? 0 }}</div>
            <div class="stat-label">Total Pesanan Dibuat</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link">Lihat Pesanan <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div><i class="bi bi-check2 text-success"></i></div>
            <div class="stat-number">{{ $totalSelesai ?? 0 }}</div>
            <div class="stat-label">Pesanan Selesai</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link">Lihat Riwayat <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-orange"><i class="bi bi-clock-history"></i></div><i class="bi bi-hourglass-split text-warning"></i></div>
            <div class="stat-number">{{ $totalBelumBayar ?? 0 }}</div>
            <div class="stat-label">Menunggu Pembayaran</div>
            <a href="{{ route('pembeli.pesanan') }}" class="stat-link">Cek Sekarang <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-red"><i class="bi bi-cart-fill"></i></div><i class="bi bi-arrow-up-right text-danger"></i></div>
            <div class="stat-number">{{ $totalKeranjang ?? $cartCount ?? 0 }}</div>
            <div class="stat-label">Produk di Keranjang</div>
            <a href="{{ route('pembeli.keranjang') }}" class="stat-link">Buka Keranjang <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>

    {{-- KATEGORI --}}
    <section class="section">
        <div class="section-header">
            <div>
                <h3 class="section-title">Jelajahi Kategori</h3>
                <p class="section-subtitle">Temukan karya sesuai kebutuhanmu</p>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="category-grid">
            @php
                $catIcons = [
                    'desain' => 'bi-palette',
                    'logo' => 'bi-vector-pen',
                    'ui/ux' => 'bi-phone',
                    'website' => 'bi-code-slash',
                    'web' => 'bi-code-slash',
                    '3d' => 'bi-box',
                    'video' => 'bi-camera-video',
                    'ilustrasi' => 'bi-image',
                    'social' => 'bi-share',
                    'jasa' => 'bi-briefcase',
                ];
            @endphp
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $cat)
                    @php
                        $iconClass = 'bi-grid';
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
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-palette"></i></div><span>Desain</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-vector-pen"></i></div><span>Logo & Branding</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-phone"></i></div><span>UI/UX</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-code-slash"></i></div><span>Website</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-box"></i></div><span>3D & Blender</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-camera-video"></i></div><span>Video</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-image"></i></div><span>Ilustrasi</span></a>
                <a href="{{ route('pembeli.marketplace') }}" class="category-card"><div class="category-icon"><i class="bi bi-share"></i></div><span>Social Media</span></a>
            @endif
        </div>
    </section>

    {{-- SEKSI IKLAN & PROMOSI (SPONSORED PRODUCTS) --}}
    @if(isset($promotedProducts) && $promotedProducts->count() > 0)
        <section class="section mb-4">
            <div class="p-4 rounded-4 shadow-sm border border-primary-subtle" style="background: linear-gradient(135deg, #f0f7ff 0%, #e0eefe 100%);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark font-weight-bold px-2 py-1 rounded-pill" style="font-size:11px;">
                            <i class="bi bi-star-fill me-1 text-dark"></i> SPONSORED
                        </span>
                        <h4 class="fw-bold text-dark mb-0 fs-5"><i class="bi bi-megaphone-fill text-primary me-2"></i>Produk Promosi & Iklan Pilihan</h4>
                    </div>
                    <span class="text-muted small">Promosi Resmi Penjual Karyaku</span>
                </div>
                <div class="row g-3">
                    @foreach($promotedProducts as $promo)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden position-relative hover-shadow" style="transition: transform 0.2s;">
                                <span class="position-absolute top-0 start-0 badge bg-danger m-2 shadow-sm" style="font-size: 9px; z-index: 2;">IKLAN</span>
                                <a href="{{ route('pembeli.produk.detail', $promo->id_product) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                    <img src="{{ $promo->image_url }}" 
                                        class="card-img-top object-fit-cover" style="height: 120px;" alt="{{ $promo->title }}">
                                    <div class="card-body p-2 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <div class="text-truncate fw-bold small mb-1" title="{{ $promo->title }}">{{ $promo->title }}</div>
                                            <div class="text-muted" style="font-size: 10px;">{{ $promo->category->name ?? 'Karya' }}</div>
                                        </div>
                                        <div class="mt-2 pt-1 border-top d-flex align-items-center justify-content-between">
                                            <span class="fw-bold text-primary small">Rp {{ number_format($promo->price, 0, ',', '.') }}</span>
                                            <span class="badge bg-light text-muted border" style="font-size: 9px;"><i class="bi bi-eye"></i> {{ $promo->view_count }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- PRODUK + SIDEBAR --}}
    <section class="section">
        <div class="section-header">
            <div>
                <h3 class="section-title">Rekomendasi Untukmu</h3>
                <p class="section-subtitle">Produk dan jasa yang sedang populer</p>
            </div>
            <a href="{{ route('pembeli.marketplace') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>
        
        <div class="content-layout">
            <div>
                <div class="product-grid" id="productGrid">
                    @forelse($rekomendasi ?? [] as $product)
                        @include('pembeli.partials.product-card', ['product' => $product])
                    @empty
                        {{-- Fallback Produk --}}
                        @php
                            $dummyProducts = [
                                ['cat' => 'Poster', 'img' => '1626785774573-4b799315345d', 'title' => 'Desain Poster Promosi Cafe & Resto', 'price' => 'Rp75.000', 'seller' => 'Dinda Studio'],
                                ['cat' => '3D', 'img' => '1618172193622-ae2d025f4032', 'title' => 'Model 3D Karakter Game Low-Poly', 'price' => 'Rp480.000', 'seller' => 'Rangga.blend'],
                                ['cat' => 'Logo', 'img' => '1611162617213-7d7a39e9b1d7', 'title' => 'Paket Logo & Brand Identity Kit', 'price' => 'Rp150.000', 'seller' => 'Kirana Design'],
                                ['cat' => 'Social Media', 'img' => '1611926653458-09294b3142bf', 'title' => 'Paket 15 Feed & Story Instagram', 'price' => 'Rp120.000', 'seller' => 'Sasi Creative']
                            ];
                        @endphp
                        @foreach($dummyProducts as $idx => $dp)
                            <div class="product-card">
                                <div class="product-thumb">
                                    <span class="cat-badge">{{ $dp['cat'] }}</span>
                                    <button class="wish-btn" type="button"><i class="bi bi-heart"></i></button>
                                    <img src="https://images.unsplash.com/photo-{{ $dp['img'] }}?auto=format&fit=crop&w=600&q=80" alt="{{ $dp['cat'] }}">
                                </div>
                                <div class="product-body">
                                    <h6>{{ $dp['title'] }}</h6>
                                    <div class="product-price">{{ $dp['price'] }}</div>
                                    <div class="product-meta"><span class="rating">★ 4.9</span><span>Terjual 100+</span></div>
                                    <div class="product-seller"><img src="https://ui-avatars.com/api/?name={{ urlencode($dp['seller']) }}" alt="">{{ $dp['seller'] }}</div>
                                    <button class="btn-add-cart" data-product="{{ $idx+1 }}" type="button"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside class="sidebar">
                <div class="sidebar-card">
                    <h4 class="sidebar-title"><i class="bi bi-grid-fill text-primary"></i> Menu Pintas</h4>
                    <ul class="quick-menu">
                        <li><a href="{{ route('pembeli.keranjang') }}"><div class="quick-left"><i class="bi bi-cart3"></i> Keranjang Belanja</div><span class="quick-badge">{{ $totalKeranjang ?? $cartCount ?? 0 }}</span></a></li>
                        <li><a href="{{ route('pembeli.wishlist') }}"><div class="quick-left"><i class="bi bi-heart"></i> Daftar Keinginan</div><span class="quick-badge">{{ $totalWishlist ?? $wishlistCount ?? 0 }}</span></a></li>
                        <li><a href="{{ route('pembeli.pesanan') }}"><div class="quick-left"><i class="bi bi-receipt"></i> Pesanan Saya</div><i class="bi bi-chevron-right"></i></a></li>
                        <li><a href="{{ route('pembeli.download') }}"><div class="quick-left"><i class="bi bi-cloud-arrow-down"></i> File Unduhan</div><i class="bi bi-chevron-right"></i></a></li>
                        <li><a href="{{ route('pembeli.profile') }}"><div class="quick-left"><i class="bi bi-person"></i> Pengaturan Akun</div><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </div>

                <div class="sidebar-card">
                    <h4 class="sidebar-title"><i class="bi bi-award-fill text-warning"></i> Top Kreator</h4>
                    @php
                        $topCreators = [
                            ['name' => 'Dinda Studio', 'sales' => '320+'],
                            ['name' => 'CodeCraft', 'sales' => '97+'],
                            ['name' => 'Nadia UX', 'sales' => '84+']
                        ];
                    @endphp
                    @foreach($topCreators as $creator)
                        <div class="creator">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($creator['name']) }}" alt="">
                            <div class="creator-info"><div class="creator-name">{{ $creator['name'] }}</div><div class="creator-sales">{{ $creator['sales'] }} produk terjual</div></div>
                            <div class="creator-rating">★ 4.9</div>
                        </div>
                    @endforeach
                </div>

                <div class="sidebar-card" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <h4 class="sidebar-title"><i class="bi bi-shop text-primary"></i> Cari Karya Baru</h4>
                    <p style="color:#64748b;font-size:10px;line-height:1.7;">Temukan berbagai produk digital dan jasa kreatif dari kreator Karyaku.</p>
                    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm w-100" style="font-size:10px;border-radius:9px;"><i class="bi bi-shop"></i> Buka Marketplace</a>
                </div>
            </aside>
        </div>
    </section>

@endsection
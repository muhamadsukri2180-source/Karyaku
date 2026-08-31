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
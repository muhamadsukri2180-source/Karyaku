<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Karyaku') - Karyaku</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    :root{
        --primary: #2563eb;
        --primary-dark: #1e3a8a;
        --primary-darker: #14225c;
        --primary-light: #eff6ff;
        --primary-soft: #dbeafe;
        --coral: #FF7A59;
        --coral-dark: #F0623F;
        --white: #ffffff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e5edff;
        --radius: 18px;
        --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
        --shadow-hover: 0 16px 34px rgba(37, 99, 235, 0.16);
    }
    *{ box-sizing: border-box; }
    body{ font-family: 'Poppins', sans-serif; background: var(--primary-light); color: var(--text-dark); overflow-x: hidden; }
    a{ text-decoration: none; }

    .bg-decor{ position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
    .bg-decor span{ position: absolute; border-radius: 50%; background: radial-gradient(circle at 30% 30%, var(--primary-soft), transparent 70%); opacity: .5; animation: floatBlob 14s ease-in-out infinite; }
    .bg-decor span:nth-child(1){ width: 380px; height: 380px; top: -120px; right: -100px; animation-duration: 16s; }
    .bg-decor span:nth-child(2){ width: 260px; height: 260px; bottom: -80px; left: -60px; animation-duration: 20s; animation-delay: 2s; }
    @keyframes floatBlob{ 0%,100%{ transform: translate(0,0) scale(1); } 50%{ transform: translate(20px,-30px) scale(1.08); } }

    .site-navbar{ background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary)); position: sticky; top: 0; z-index: 1030; box-shadow: 0 10px 30px rgba(20,34,92,0.18); }
    .navbar-top{ display: flex; align-items: center; gap: 18px; padding: 12px 28px; max-width: 1440px; margin: 0 auto; }
    .brand{ display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .brand-icon{ width: 40px; height: 40px; background: var(--white); color: var(--primary); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; font-weight: 700; }
    .brand-text h5{ margin: 0; font-weight: 700; font-size: 15.5px; color: var(--white); line-height: 1.1; }
    .brand-text small{ color: rgba(255,255,255,0.6); font-size: 10.5px; }
    .mobile-toggle{ width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.12); border: none; color: #fff; display: none; align-items: center; justify-content: center; flex-shrink: 0; transition: background .2s ease; }
    .mobile-toggle:hover{ background: rgba(255,255,255,0.22); }
    .nav-menu{ display: flex; align-items: center; gap: 2px; flex: 1; }
    .nav-menu .nav-link{ position: relative; display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.78); padding: 9px 14px; border-radius: 10px; font-size: 13.5px; font-weight: 500; white-space: nowrap; transition: all .2s ease; }
    .nav-menu .nav-link i{ font-size: 16px; }
    .nav-menu .nav-link:hover{ background: rgba(255,255,255,0.1); color: var(--white); }
    .nav-menu .nav-link.active{ background: rgba(255,255,255,0.16); color: var(--white); font-weight: 600; }
    .nav-menu .nav-link.active::after{ content: ""; position: absolute; left: 14px; right: 14px; bottom: -1px; height: 2.5px; background: var(--coral); border-radius: 4px; }
    .nav-menu .badge-count{ background: var(--coral); color: #fff; font-size: 10.5px; font-weight: 700; min-width: 17px; height: 17px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }
    .navbar-right{ display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .btn-jual{ display: inline-flex; align-items: center; gap: 8px; background: var(--coral); color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; white-space: nowrap; transition: all .2s ease; }
    .btn-jual:hover{ background: var(--coral-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,122,89,0.35); }
    .user-menu{ position: relative; flex-shrink: 0; }
    .user-chip{ display: flex; align-items: center; gap: 9px; background: rgba(255,255,255,0.12); padding: 5px 12px 5px 5px; border-radius: 30px; transition: background .2s ease; border: none; cursor: pointer; }
    .user-chip:hover{ background: rgba(255,255,255,0.2); }
    .user-chip img{ width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
    .user-chip .name{ font-size: 12.5px; font-weight: 600; line-height: 1.1; color: #fff; text-align: left; }
    .user-chip .role{ font-size: 10.5px; color: rgba(255,255,255,0.65); }
    .user-chip .bi-chevron-down{ font-size: 11px; color: rgba(255,255,255,0.7); margin-left: 2px; transition: transform .2s ease; }
    .user-menu.open .user-chip .bi-chevron-down{ transform: rotate(180deg); }
    .user-dropdown{ position: absolute; right: 0; top: calc(100% + 10px); width: 220px; background: #fff; border-radius: 14px; box-shadow: var(--shadow-hover); padding: 8px; opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all .18s ease; z-index: 1040; }
    .user-menu.open .user-dropdown{ opacity: 1; visibility: visible; transform: translateY(0); }
    .user-dropdown a, .user-dropdown button{ width: 100%; text-align: left; background: none; border: none; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; font-size: 13.5px; font-weight: 500; color: var(--text-dark); transition: background .15s ease; }
    .user-dropdown a:hover, .user-dropdown button:hover{ background: var(--primary-light); color: var(--primary-dark); }
    .user-dropdown .text-danger:hover{ background: #fef2f2; }
    .user-dropdown hr{ margin: 6px 4px; border-color: var(--border-color); }
    .navbar-search{ padding: 0 28px 14px; max-width: 1440px; margin: 0 auto; }
    .search-combo{ display: flex; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 22px rgba(0,0,0,0.15); }
    .search-combo select{ border: none; background: var(--primary-light); color: var(--text-dark); font-size: 13.5px; font-weight: 600; padding: 0 12px; max-width: 190px; border-right: 1px solid var(--border-color); outline: none; }
    .search-combo input{ border: none; flex: 1; padding: 12px 14px; font-size: 14px; outline: none; min-width: 0; }
    .search-combo button{ border: none; background: var(--coral); color: #fff; padding: 0 20px; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: background .2s ease; }
    .search-combo button:hover{ background: var(--coral-dark); }
    .mobile-menu-panel{ display: none; max-height: 0; overflow: hidden; background: var(--primary-darker); transition: max-height .28s ease; }
    .mobile-menu-panel.show{ max-height: 640px; }
    .mobile-menu-panel .nav-link{ display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.82); padding: 13px 22px; font-size: 14px; font-weight: 500; border-top: 1px solid rgba(255,255,255,0.08); }
    .mobile-menu-panel .nav-link i{ font-size: 17px; width: 20px; }
    .mobile-menu-panel .nav-link.active{ color: #fff; background: rgba(255,255,255,0.08); font-weight: 600; }
    .mobile-menu-panel .badge-count{ margin-left: auto; background: var(--coral); color: #fff; font-size: 10.5px; font-weight: 700; min-width: 18px; height: 18px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 5px; }
    .mobile-menu-panel .logout-link{ color: #fecaca; }
    @media (max-width: 992px){ .mobile-toggle{ display: flex; } .nav-menu{ display: none; } .mobile-menu-panel{ display: block; } .btn-jual span{ display: none; } .user-chip .d-lg-block{ display: none !important; } }
    @media (max-width: 576px){ .navbar-top{ padding: 10px 16px; gap: 10px; } .navbar-search{ padding: 0 16px 12px; } }

    .main-content{ padding: 24px 28px 60px; max-width: 1440px; margin: 0 auto; }
    @media (max-width: 576px){ .main-content{ padding: 18px 16px 50px; } }

    .stat-card{ background: #fff; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--border-color); }
    .stat-card .icon{ width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff; }
    .stat-card .value{ font-size: 24px; font-weight: 800; color: var(--text-dark); }
    .stat-card .label{ font-size: 12px; color: var(--text-muted); font-weight: 600; }

    .product-grid{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    @media (max-width: 1200px){ .product-grid{ grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px){ .product-grid{ grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px){ .product-grid{ grid-template-columns: 1fr 1fr; gap: 12px; } }
    .product-card{ background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow); transition: transform .25s ease, box-shadow .25s ease; position: relative; display: flex; flex-direction: column; }
    .product-card:hover{ transform: translateY(-6px); box-shadow: var(--shadow-hover); }
    .product-thumb{ position: relative; height: 150px; overflow: hidden; background: var(--primary-light); }
    .product-thumb img{ width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
    .product-card:hover .product-thumb img{ transform: scale(1.08); }
    .product-thumb .cat-badge{ position: absolute; top: 10px; left: 10px; background: rgba(20,34,92,0.75); color: #fff; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; backdrop-filter: blur(2px); }
    .wish-btn{ position: absolute; top: 8px; right: 8px; width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 14px; transition: all .2s ease; }
    .wish-btn:hover, .wish-btn.active{ color: var(--coral); background: #fff; }
    .product-body{ padding: 12px 13px 14px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
    .product-body h6{ font-size: 13px; font-weight: 600; margin: 0; line-height: 1.35; min-height: 34px; }
    .product-body h6 a{ color: inherit; }
    .product-price{ color: var(--coral); font-weight: 800; font-size: 15px; }
    .product-meta{ display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--text-muted); }
    .product-seller{ display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .product-seller img{ width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }
    .btn-add-cart{ margin-top: 6px; width: 100%; border: none; background: var(--primary-light); color: var(--primary); font-weight: 700; font-size: 12px; padding: 8px 0; border-radius: 9px; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all .2s ease; }
    .btn-add-cart:hover{ background: var(--primary); color: #fff; }

    .card-box{ background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border-color); }
    .badge-status{ font-size: 10.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .table thead th{ font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }

    ::-webkit-scrollbar{ width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }


    .icon-btn-light{ width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.12); border: none; display: flex; align-items: center; justify-content: center; color: #fff; position: relative; font-size: 17px; transition: all .2s ease; flex-shrink: 0; }
    .icon-btn-light:hover{ background: rgba(255,255,255,0.22); color: #fff; }
    .icon-btn-light .dot{ position: absolute; top: 4px; right: 4px; min-width: 16px; height: 16px; padding: 0 3px; background: var(--coral); border-radius: 20px; border: 2px solid var(--primary-dark); font-size: 9.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
    .notif-dropdown{ position: absolute; right: 0; top: calc(100% + 10px); width: 300px; max-height: 380px; overflow-y: auto; background: #fff; border-radius: 14px; box-shadow: var(--shadow-hover); padding: 8px; opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all .18s ease; z-index: 1040; }
    .notif-menu.open .notif-dropdown{ opacity: 1; visibility: visible; transform: translateY(0); }
    .notif-item{ display: block; padding: 10px 12px; border-radius: 10px; transition: background .15s ease; }
    .notif-item:hover{ background: var(--primary-light); }
    .notif-item .n-title{ font-size: 12.5px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .notif-item .n-desc{ font-size: 11.5px; color: var(--text-muted); line-height: 1.4; }
    .notif-item .n-time{ font-size: 10px; color: var(--text-muted); margin-top: 3px; }


</style>
@stack('styles')
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>

@php
    $navUser = auth()->user();
    $navCartCount = $navUser ? \App\Models\Cart::where('user_id', $navUser->id_user)->count() : 0;
    $navWishlistCount = $navUser ? \App\Models\Wishlist::where('user_id', $navUser->id_user)->count() : 0;
    $navCategories = \App\Models\Category::orderBy('name')->get();
    $hasSellerReg = $navUser ? \App\Models\IdentityVerification::where('user_id', $navUser->id_user)->exists() : false;
    $isPenjualNav = ($navUser->role->role_name ?? null) === 'penjual';
@endphp

<header class="site-navbar">
    <div class="navbar-top">
        <button class="mobile-toggle" id="btnToggleMenu" aria-label="Buka menu" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a href="{{ route('pembeli.dashboard') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-bag-check-fill"></i></div>
            <div class="brand-text d-none d-sm-block">
                <h5>Karyaku</h5>
                <small>Marketplace Pembeli</small>
            </div>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('pembeli.dashboard') }}" class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="{{ route('pembeli.marketplace') }}" class="nav-link {{ request()->routeIs('pembeli.marketplace') ? 'active' : '' }}"><i class="bi bi-shop"></i> Marketplace</a>
            <a href="{{ route('pembeli.wishlist') }}" class="nav-link {{ request()->routeIs('pembeli.wishlist') ? 'active' : '' }}"><i class="bi bi-heart-fill"></i> Wishlist @if($navWishlistCount > 0)<span class="badge-count">{{ $navWishlistCount }}</span>@endif</a>
            <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><i class="bi bi-cart-fill"></i> Keranjang @if($navCartCount > 0)<span class="badge-count">{{ $navCartCount }}</span>@endif</a>
            <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Pesanan</a>
            <a href="{{ route('pembeli.download') }}" class="nav-link {{ request()->routeIs('pembeli.download') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-down-fill"></i> Download</a>
        </nav>

       <div class="navbar-right">
            @if ($isPenjualNav)
                <a href="{{ route('penjual.dashboard') }}" class="btn-jual d-none d-md-inline-flex">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard Penjual</span>
                </a>
            @elseif ($hasSellerReg)
                <a href="{{ route('pembeli.seller.registration.status') }}" class="btn-jual d-none d-md-inline-flex" style="background: var(--primary);">
                    <i class="bi bi-person-check-fill"></i> <span>Cek Status Penjual</span>
                </a>
            @else
                <a href="{{ route('pembeli.seller.registration.create') }}" class="btn-jual d-none d-md-inline-flex">
                    <i class="bi bi-shop-window"></i> <span>Daftar Sebagai Penjual</span>
                </a>
            @endif

            @php
                $latestNotifications = \App\Models\Notification::latest()->take(5)->get();
                $newNotifCount = \App\Models\Notification::where('created_at', '>=', now()->subDays(3))->count();
            @endphp
            <div class="user-menu notif-menu" id="notifMenu">
                <button class="icon-btn-light" id="btnNotif" type="button" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if ($newNotifCount > 0)<span class="dot">{{ $newNotifCount }}</span>@endif
                </button>
                <div class="notif-dropdown">
                    @forelse ($latestNotifications as $notif)
                        <div class="notif-item">
                            <div class="n-title">{{ $notif->name }}</div>
                            <div class="n-desc">{{ \Illuminate\Support\Str::limit($notif->description, 80) }}</div>
                            <div class="n-time">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted small py-3">Belum ada notifikasi.</div>
                    @endforelse
                    <hr>
                    <a href="{{ route('pembeli.notifications') }}" class="d-block text-center small fw-semibold py-2" style="color: var(--primary);">Lihat Semua Notifikasi</a>
                </div>
            </div>

            <div class="user-menu" id="userMenu">
                <button class="user-chip" id="btnUserChip" type="button">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($navUser->name ?? 'Pembeli') }}&background=ffffff&color=1e3a8a" alt="avatar">
                    <div class="d-none d-lg-block">
                        <div class="name">{{ $navUser->name ?? 'Pembeli' }}</div>
                        <div class="role">Pembeli</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="user-dropdown">
                    <a href="{{ route('pembeli.profile') }}"><i class="bi bi-person-fill"></i> Profile</a>
                    <a href="{{ route('reports.create') }}"><i class="bi bi-flag-fill"></i> Laporkan Pelanggaran</a>
                    <a href="{{ route('reports.index') }}"><i class="bi bi-clock-history"></i> Riwayat Laporan Saya</a>
                    <a href="{{ route('pembeli.peringatan') }}"><i class="bi bi-exclamation-triangle-fill"></i> Peringatan Saya</a>
                    <a href="{{ route('pembeli.seller.registration.status') }}">
                    <i class="bi bi-person-check-fill"></i>
                    Status Pendaftaran Penjual
                    </a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                </div>
            </div>
        </div>





    </div>

    <div class="mobile-menu-panel" id="mobileMenuPanel">
        <a href="{{ route('pembeli.dashboard') }}" class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="{{ route('pembeli.marketplace') }}" class="nav-link {{ request()->routeIs('pembeli.marketplace') ? 'active' : '' }}"><i class="bi bi-shop"></i> Marketplace</a>
        <a href="{{ route('pembeli.wishlist') }}" class="nav-link {{ request()->routeIs('pembeli.wishlist') ? 'active' : '' }}"><i class="bi bi-heart-fill"></i> Wishlist @if($navWishlistCount > 0)<span class="badge-count">{{ $navWishlistCount }}</span>@endif</a>
        <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><i class="bi bi-cart-fill"></i> Keranjang @if($navCartCount > 0)<span class="badge-count">{{ $navCartCount }}</span>@endif</a>
        <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Pesanan Saya</a>
        <a href="{{ route('pembeli.download') }}" class="nav-link {{ request()->routeIs('pembeli.download') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-down-fill"></i> Download Saya</a>
        <a href="{{ route('pembeli.profile') }}" class="nav-link {{ request()->routeIs('pembeli.profile') ? 'active' : '' }}"><i class="bi bi-person-fill"></i> Profile</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link logout-link w-100 border-0 bg-transparent text-start"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>

    <div class="navbar-search">
        <form class="search-combo" action="{{ route('pembeli.marketplace') }}" method="GET">
            <select name="category" aria-label="Pilih kategori">
                <option value="">Semua Kategori</option>
                @foreach ($navCategories as $navCat)
                    <option value="{{ $navCat->id_category }}" {{ request('category') == $navCat->id_category ? 'selected' : '' }}>{{ $navCat->name }}</option>
                @endforeach
            </select>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari jasa, kreator, atau kata kunci...">
            <button type="submit"><i class="bi bi-search"></i><span class="d-none d-sm-inline">Cari</span></button>
        </form>
    </div>
</header>

<main class="main-content">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('error') }}
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btnToggleMenu   = document.getElementById('btnToggleMenu');
    const mobileMenuPanel = document.getElementById('mobileMenuPanel');
    if (btnToggleMenu && mobileMenuPanel) {
        btnToggleMenu.addEventListener('click', () => {
            const isOpen = mobileMenuPanel.classList.toggle('show');
            btnToggleMenu.setAttribute('aria-expanded', isOpen);
            btnToggleMenu.querySelector('i').className = isOpen ? 'bi bi-x-lg fs-5' : 'bi bi-list fs-5';
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992 && mobileMenuPanel.classList.contains('show')) {
                mobileMenuPanel.classList.remove('show');
                btnToggleMenu.setAttribute('aria-expanded', false);
                btnToggleMenu.querySelector('i').className = 'bi bi-list fs-5';
            }
        });
    }

    const userMenu    = document.getElementById('userMenu');
    const btnUserChip = document.getElementById('btnUserChip');
    if (btnUserChip && userMenu) {
        btnUserChip.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) userMenu.classList.remove('open');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') userMenu.classList.remove('open');
        });
    }


    const notifMenu = document.getElementById('notifMenu');
    const btnNotif  = document.getElementById('btnNotif');
    if (btnNotif && notifMenu) {
        btnNotif.addEventListener('click', (e) => {
            e.stopPropagation();
            notifMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!notifMenu.contains(e.target)) notifMenu.classList.remove('open');
        });
    }
    




    // Toggle wishlist via AJAX (dipakai di marketplace, produk, wishlist page)
    document.querySelectorAll('.wish-btn[data-url]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.getAttribute('data-url');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'added') {
                    btn.classList.add('active');
                    btn.querySelector('i').className = 'bi bi-heart-fill';
                } else if (data.status === 'removed') {
                    btn.classList.remove('active');
                    btn.querySelector('i').className = 'bi bi-heart';
                    if (btn.dataset.removeOnUnwish === '1') {
                        btn.closest('[data-wishlist-row]')?.remove();
                    }
                }
            })
            .catch(() => alert('Gagal memperbarui wishlist. Coba lagi.'));
        });
    });
</script>
@stack('scripts')
</body>
</html>

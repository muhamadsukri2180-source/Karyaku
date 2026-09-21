<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Karyaku') - Karyaku Digital Marketplace</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<!Google Fonts>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!Bootstrap 5 & Bootstrap Icons>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --primary-dark: #1e3a8a;
        --primary-darker: #0f172a;
        --primary-light: #eff6ff;
        --primary-soft: #dbeafe;
        --coral: #ff7a59;
        --coral-dark: #f0623f;
        --accent-purple: #6366f1;
        --white: #ffffff;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --border-light: #f1f5f9;
        --radius: 16px;
        --radius-lg: 22px;
        --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
        --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
        --shadow-hover: 0 16px 32px rgba(37, 99, 235, 0.14);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * { box-sizing: border-box; }
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8fafc;
        color: var(--text-dark);
        overflow-x: hidden;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    a { text-decoration: none; }

    /* Ambient Background Glow */
    .bg-decor {
        position: fixed;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }
    .bg-decor span {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(37, 99, 235, 0.07), transparent 70%);
        opacity: 0.8;
        animation: floatBlob 18s ease-in-out infinite;
    }
    .bg-decor span:nth-child(1) {
        width: 450px;
        height: 450px;
        top: -150px;
        right: -100px;
        animation-duration: 20s;
    }
    .bg-decor span:nth-child(2) {
        width: 380px;
        height: 380px;
        bottom: -100px;
        left: -80px;
        animation-duration: 24s;
        animation-delay: 3s;
    }
    @keyframes floatBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(25px, -25px) scale(1.05); }
    }

    /* Navbar Modern */
    .site-navbar {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        position: sticky;
        top: 0;
        z-index: 1030;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
    }
    .navbar-top {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 24px;
        max-width: 1440px;
        margin: 0 auto;
    }
    .brand {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .brand-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ffffff, #eff6ff);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
    }
    .brand:hover .brand-icon {
        transform: scale(1.05) rotate(-5deg);
    }
    .brand-text h5 {
        margin: 0;
        font-weight: 800;
        font-size: 16px;
        color: var(--white);
        line-height: 1.1;
        letter-spacing: -0.3px;
    }
    .brand-text small {
        color: rgba(255, 255, 255, 0.7);
        font-size: 10.5px;
        font-weight: 500;
    }

    .mobile-toggle {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
    }
    .mobile-toggle:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    .nav-menu {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        margin-left: 8px;
    }
    .nav-menu .nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 7px;
        color: rgba(255, 255, 255, 0.85);
        padding: 8px 13px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        white-space: nowrap;
        transition: var(--transition);
    }
    .nav-menu .nav-link i {
        font-size: 15px;
    }
    .nav-menu .nav-link:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        transform: translateY(-1px);
    }
    .nav-menu .nav-link.active {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
    }
    .nav-menu .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 14px;
        right: 14px;
        height: 3px;
        background: var(--coral);
        border-radius: 4px;
    }
    .badge-count {
        background: var(--coral);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        margin-left: 2px;
        box-shadow: 0 2px 6px rgba(255, 122, 89, 0.4);
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .btn-jual {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--coral), var(--coral-dark));
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 11px;
        font-weight: 700;
        font-size: 12.5px;
        white-space: nowrap;
        transition: var(--transition);
        box-shadow: 0 4px 14px rgba(255, 122, 89, 0.35);
    }
    .btn-jual:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 122, 89, 0.45);
        color: #fff;
    }

    .icon-btn-light {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        position: relative;
        font-size: 16px;
        transition: var(--transition);
        flex-shrink: 0;
    }
    .icon-btn-light:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #fff;
        transform: translateY(-1px);
    }
    .icon-btn-light .dot {
        position: absolute;
        top: 2px;
        right: 2px;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        background: var(--coral);
        border-radius: 20px;
        border: 2px solid #1e3a8a;
        font-size: 9.5px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-menu {
        position: relative;
        flex-shrink: 0;
    }
    .user-chip {
        display: flex;
        align-items: center;
        gap: 9px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 4px 12px 4px 4px;
        border-radius: 30px;
        transition: var(--transition);
        cursor: pointer;
    }
    .user-chip:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    .user-chip img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-chip .name {
        font-size: 12.5px;
        font-weight: 600;
        line-height: 1.1;
        color: #fff;
        text-align: left;
    }
    .user-chip .role {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
    }
    .user-chip .bi-chevron-down {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.75);
        margin-left: 2px;
        transition: transform 0.2s ease;
    }
    .user-menu.open .user-chip .bi-chevron-down {
        transform: rotate(180deg);
    }

    .user-dropdown, .notif-dropdown {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        width: 240px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
        border: 1px solid var(--border-color);
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: var(--transition);
        z-index: 1040;
    }
    .notif-dropdown {
        width: 320px;
        max-height: 400px;
        overflow-y: auto;
    }
    .user-menu.open .user-dropdown,
    .notif-menu.open .notif-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .user-dropdown a, .user-dropdown button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
        transition: background 0.15s ease;
    }
    .user-dropdown a:hover, .user-dropdown button:hover {
        background: var(--primary-light);
        color: var(--primary-dark);
    }
    .user-dropdown .text-danger:hover {
        background: #fef2f2;
        color: #ef4444;
    }
    .user-dropdown hr {
        margin: 6px 4px;
        border-color: var(--border-color);
    }

    .notif-item {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        transition: background 0.15s ease;
        text-decoration: none;
    }
    .notif-item:hover {
        background: var(--primary-light);
    }
    .notif-item .n-title {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2px;
    }
    .notif-item .n-desc {
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.4;
    }
    .notif-item .n-time {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Search Combo */
    .navbar-search {
        padding: 0 24px 14px;
        max-width: 1440px;
        margin: 0 auto;
    }
    .search-combo {
        display: flex;
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .search-combo input {
        border: none;
        flex: 1;
        padding: 11px 18px;
        font-size: 13.5px;
        outline: none;
        min-width: 0;
        color: var(--text-dark);
    }
    .search-combo input::placeholder {
        color: var(--text-muted);
        font-size: 13px;
    }
    .search-combo button {
        border: none;
        background: linear-gradient(135deg, var(--coral), var(--coral-dark));
        color: #fff;
        padding: 0 22px;
        font-weight: 700;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        cursor: pointer;
    }
    .search-combo button:hover {
        background: var(--coral-dark);
    }

    /* Mobile Drawer */
    .mobile-menu-panel {
        display: none;
        max-height: 0;
        overflow: hidden;
        background: var(--primary-darker);
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .mobile-menu-panel.show {
        max-height: 580px;
    }
    .mobile-menu-panel .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, 0.85);
        padding: 12px 20px;
        font-size: 13.5px;
        font-weight: 500;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-decoration: none;
    }
    .mobile-menu-panel .nav-link i {
        font-size: 17px;
        width: 22px;
        color: rgba(255, 255, 255, 0.7);
    }
    .mobile-menu-panel .nav-link.active {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
        font-weight: 600;
    }
    .mobile-menu-panel .nav-link.active i {
        color: var(--coral);
    }

    /* Main Container */
    .main-content {
        padding: 24px 24px 60px;
        max-width: 1440px;
        margin: 0 auto;
        width: 100%;
        flex: 1;
    }

    /* Global Utility Card Boxes */
    .card-box {
        background: #ffffff;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }
    .badge-status {
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    /* Product Grid & Card Base Styles */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    @media (max-width: 1200px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }
    @media (max-width: 480px) { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

    .product-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.22s ease, border-color 0.22s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: #cbd5e1;
    }
    .product-thumb {
        position: relative;
        height: 165px;
        overflow: hidden;
        background: #f1f5f9;
    }
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .product-card:hover .product-thumb img {
        transform: scale(1.06);
    }
    .product-thumb .cat-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(15, 23, 42, 0.82);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(6px);
        z-index: 2;
    }
    .wish-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 14px;
        transition: var(--transition);
        z-index: 3;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        cursor: pointer;
    }
    .wish-btn:hover, .wish-btn.active {
        color: #ef4444;
        background: #ffffff;
        transform: scale(1.12);
    }
    .product-body {
        padding: 14px 15px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .wish-icon-btn {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 13px;
        transition: var(--transition);
        cursor: pointer;
    }
    .wish-icon-btn:hover, .wish-icon-btn.active {
        background: #ef4444;
        color: #fff;
        transform: scale(1.1);
    }
    .wish-icon-btn:hover i, .wish-icon-btn.active i {
        color: #fff !important;
    }
    .btn-buy-rectangular {
        width: 100%;
        border: none;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        font-weight: 700;
        font-size: 12.5px;
        padding: 9px 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        cursor: pointer;
    }
    .btn-buy-rectangular:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        color: #fff;
    }

    /* Scrollbars */
    ::-webkit-scrollbar { width: 7px; height: 7px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Responsive Breakpoints & Laptop Fixes */
    @media (max-width: 1366px) {
        .navbar-top { padding: 10px 18px; gap: 8px; }
        .nav-menu { gap: 2px; margin-left: 4px; }
        .nav-menu .nav-link { padding: 6px 9px; font-size: 12.5px; gap: 5px; }
        .nav-menu .nav-link i { font-size: 14px; }
        .btn-jual { padding: 7px 12px; font-size: 12px; }
        .user-chip { padding: 4px 10px 4px 4px; gap: 6px; }
        .user-chip .name { max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    }

    @media (max-width: 1180px) {
        .mobile-toggle { display: flex; }
        .nav-menu { display: none; }
        .mobile-menu-panel { display: block; }
        .btn-jual span { display: none; }
    }

    @media (max-width: 576px) {
        .navbar-top { padding: 10px 14px; gap: 8px; }
        .navbar-search { padding: 0 14px 12px; }
        .main-content { padding: 16px 12px 50px; }
        .search-combo input { padding: 8px 10px; font-size: 12.5px; }
        .search-combo button { padding: 0 12px; }
    }
</style>
@stack('styles')
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>

@php
    $navUser = auth()->user();
    $navCartCount = $navUser ? \App\Models\Cart::where('user_id', $navUser->id_user)->count() : 0;
    $navWishlistCount = $navUser ? \App\Models\Wishlist::where('user_id', $navUser->id_user)->count() : 0;
    $navCategories = \App\Models\Category::where('status', 'aktif')->orderBy('name')->get();
    if ($navCategories->isEmpty()) {
        $navCategories = \App\Models\Category::orderBy('name')->get();
    }
    $hasSellerReg = $navUser ? \App\Models\IdentityVerification::where('user_id', $navUser->id_user)->exists() : false;
    $isPenjualNav = ($navUser->role->role_name ?? null) === 'penjual';
@endphp

<header class="site-navbar">
    <div class="navbar-top">
        <button class="mobile-toggle" id="btnToggleMenu" aria-label="Buka menu" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a href="{{ route('pembeli.dashboard') }}" class="brand">
            <div class="brand-icon" style="background:transparent; padding:0; overflow:hidden;">
                <img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" style="width:34px; height:34px; object-fit:contain; border-radius:8px;">
            </div>
            <div class="brand-text d-none d-sm-block">
                <h5>KaryaKu</h5>
                <small>Marketplace Pembeli</small>
            </div>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('pembeli.dashboard') }}" class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
            <a href="{{ route('pembeli.marketplace') }}" class="nav-link {{ request()->routeIs('pembeli.marketplace') ? 'active' : '' }}"><i class="bi bi-shop"></i> Pasar</a>
            <a href="{{ route('pembeli.wishlist') }}" class="nav-link {{ request()->routeIs('pembeli.wishlist') ? 'active' : '' }}"><i class="bi bi-heart-fill"></i> Disukai @if($navWishlistCount > 0)<span class="badge-count">{{ $navWishlistCount }}</span>@endif</a>
            <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><i class="bi bi-cart-fill"></i> Keranjang @if($navCartCount > 0)<span class="badge-count">{{ $navCartCount }}</span>@endif</a>
            <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Pesanan</a>
            <a href="{{ route('pembeli.download') }}" class="nav-link {{ request()->routeIs('pembeli.download') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-down-fill"></i> Download</a>
            <a href="{{ route('pembeli.laporan') }}" class="nav-link {{ request()->routeIs('reports.*') || request()->routeIs('pembeli.laporan') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
        </nav>

        <div class="navbar-right">
            @if ($isPenjualNav)
                <a href="{{ route('penjual.dashboard') }}" class="btn-jual d-none d-md-inline-flex">
                    <i class="bi bi-shop"></i> <span>Beranda Penjual</span>
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
                $currentUserId = \Illuminate\Support\Facades\Auth::id();
                $latestNotifications = $currentUserId
                    ? \App\Models\Notification::where(function ($q) use ($currentUserId) {
                        $q->whereNull('user_id')
                          ->orWhere('user_id', $currentUserId);
                    })->latest()->take(5)->get()
                    : collect();

                $newNotifCount = $currentUserId
                    ? \App\Models\Notification::where(function ($q) use ($currentUserId) {
                        $q->whereNull('user_id')
                          ->orWhere('user_id', $currentUserId);
                    })->where('created_at', '>=', now()->subDays(3))->count()
                    : 0;
            @endphp
            <div class="user-menu notif-menu" id="notifMenu">
                <button class="icon-btn-light" id="btnNotif" type="button" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if ($newNotifCount > 0)<span class="dot">{{ $newNotifCount }}</span>@endif
                </button>
                <div class="notif-dropdown">
                    <div class="d-flex align-items-center justify-content-between px-2 py-1 mb-1 border-bottom">
                        <span class="fw-bold small text-dark"><i class="bi bi-bell-fill text-primary me-1"></i> Notifikasi</span>
                        @if($newNotifCount > 0)<span class="badge bg-danger rounded-pill">{{ $newNotifCount }} Baru</span>@endif
                    </div>
                    @forelse ($latestNotifications as $notif)
                        <div class="notif-item">
                            <div class="n-title">{{ $notif->name }}</div>
                            <div class="n-desc">{{ \Illuminate\Support\Str::limit($notif->description, 75) }}</div>
                            <div class="n-time"><i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted small py-3">Belum ada notifikasi.</div>
                    @endforelse
                    <hr>
                    <a href="{{ route('pembeli.notifications') }}" class="d-block text-center small fw-semibold py-1" style="color: var(--primary);">Lihat Semua Notifikasi &rarr;</a>
                </div>
            </div>

            <div class="user-menu" id="userMenu">
                <button class="user-chip" id="btnUserChip" type="button">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($navUser->name ?? 'Pembeli') }}&background=dbeafe&color=1e3a8a&bold=true" alt="avatar">
                    <div class="d-none d-lg-block">
                        <div class="name">{{ $navUser->name ?? 'Pembeli' }}</div>
                        <div class="role">Pembeli</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="user-dropdown">
                    <div class="px-3 py-2 border-bottom mb-1">
                        <div class="fw-bold text-dark text-truncate">{{ $navUser->name ?? 'Pembeli' }}</div>
                        <div class="text-muted small text-truncate">@safeEmail($navUser->email ?? '')</div>
                    </div>
                    <a href="{{ route('pembeli.profile') }}"><i class="bi bi-person-fill text-primary"></i> Pengaturan Profil</a>
                    <a href="{{ route('pembeli.laporan') }}"><i class="bi bi-shield-exclamation text-danger"></i> Pusat Laporan & Pengaduan</a>
                    <a href="{{ route('pembeli.seller.registration.status') }}"><i class="bi bi-person-check-fill text-info"></i> Status Pendaftaran Penjual</a>
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
        <a href="{{ route('pembeli.dashboard') }}" class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
        <a href="{{ route('pembeli.marketplace') }}" class="nav-link {{ request()->routeIs('pembeli.marketplace') ? 'active' : '' }}"><i class="bi bi-shop"></i> Pasar</a>
        <a href="{{ route('pembeli.wishlist') }}" class="nav-link {{ request()->routeIs('pembeli.wishlist') ? 'active' : '' }}"><i class="bi bi-heart-fill"></i> Disukai @if($navWishlistCount > 0)<span class="badge-count ms-auto">{{ $navWishlistCount }}</span>@endif</a>
        <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><i class="bi bi-cart-fill"></i> Keranjang @if($navCartCount > 0)<span class="badge-count ms-auto">{{ $navCartCount }}</span>@endif</a>
        <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Pesanan Saya</a>
        <a href="{{ route('pembeli.download') }}" class="nav-link {{ request()->routeIs('pembeli.download') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-down-fill"></i> Download Saya</a>
        <a href="{{ route('pembeli.laporan') }}" class="nav-link {{ request()->routeIs('reports.*') || request()->routeIs('pembeli.laporan') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan Pelanggaran</a>
        <a href="{{ route('pembeli.profile') }}" class="nav-link {{ request()->routeIs('pembeli.profile') ? 'active' : '' }}"><i class="bi bi-person-fill"></i> Profil</a>
        @if ($isPenjualNav)
            <a href="{{ route('penjual.dashboard') }}" class="nav-link text-warning"><i class="bi bi-speedometer2"></i> Dashboard Penjual</a>
        @else
            <a href="{{ route('pembeli.seller.registration.create') }}" class="nav-link text-warning"><i class="bi bi-shop-window"></i> Daftar Sebagai Penjual</a>
        @endif
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link logout-link w-100 border-0 bg-transparent text-start text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>

    <div class="navbar-search">
        <form class="search-combo" action="{{ route('pembeli.marketplace') }}" method="GET">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari karya digital, aset UI/UX, 3D, atau jasa kreator...">
            <button type="submit"><i class="bi bi-search"></i><span class="d-none d-sm-inline">Cari Produk</span></button>
        </form>
    </div>
</header>

<main class="main-content">
    @yield('content')
</main>

{{-- ========== CUSTOM TOAST NOTIFICATION ========== --}}
<style>
    .karyaku-toast-wrap {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        pointer-events: none;
    }
    .karyaku-toast {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 300px;
        max-width: 380px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 16px 40px rgba(15,23,42,0.16), 0 4px 12px rgba(15,23,42,0.08);
        border: 1px solid var(--border-color);
        padding: 14px 16px;
        pointer-events: all;
        position: relative;
        overflow: hidden;
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.38s cubic-bezier(0.34,1.56,0.64,1), opacity 0.28s ease;
    }
    .karyaku-toast.show {
        transform: translateX(0);
        opacity: 1;
    }
    .karyaku-toast.hide {
        transform: translateX(120%);
        opacity: 0;
    }
    .karyaku-toast .toast-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .karyaku-toast.toast-success .toast-icon { background: #ecfdf5; color: #10b981; }
    .karyaku-toast.toast-error   .toast-icon { background: #fef2f2; color: #ef4444; }
    .karyaku-toast.toast-warning .toast-icon { background: #fffbeb; color: #f59e0b; }
    .karyaku-toast.toast-info    .toast-icon { background: #eff6ff; color: #3b82f6; }
    .karyaku-toast .toast-body {
        flex: 1;
        min-width: 0;
    }
    .karyaku-toast .toast-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2px;
        line-height: 1.3;
    }
    .karyaku-toast .toast-msg {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .karyaku-toast .toast-close {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 16px;
        padding: 0;
        cursor: pointer;
        line-height: 1;
        flex-shrink: 0;
        transition: color .15s;
    }
    .karyaku-toast .toast-close:hover { color: var(--text-dark); }
    .karyaku-toast .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        border-radius: 0 0 16px 16px;
        animation: toastProgress 4s linear forwards;
    }
    .karyaku-toast.toast-success .toast-progress { background: #10b981; }
    .karyaku-toast.toast-error   .toast-progress { background: #ef4444; }
    .karyaku-toast.toast-warning .toast-progress { background: #f59e0b; }
    .karyaku-toast.toast-info    .toast-progress { background: #3b82f6; }
    @keyframes toastProgress {
        from { width: 100%; }
        to   { width: 0%; }
    }
    @media (max-width: 576px) {
        .karyaku-toast-wrap { top: 12px; right: 12px; left: 12px; }
        .karyaku-toast { min-width: unset; max-width: 100%; }
    }
</style>

<div class="karyaku-toast-wrap" id="toastWrap"></div>

<script>
    function showKaryakuToast(type, title, message, duration) {
        duration = duration || 4000;
        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-circle-fill',
            info:    'bi-info-circle-fill'
        };
        const titles = { success: 'Berhasil!', error: 'Gagal!', warning: 'Peringatan', info: 'Info' };
        const wrap = document.getElementById('toastWrap');
        const toast = document.createElement('div');
        toast.className = 'karyaku-toast toast-' + type;
        toast.innerHTML =
            '<div class="toast-icon"><i class="bi ' + icons[type] + '"></i></div>' +
            '<div class="toast-body">' +
              '<div class="toast-title">' + (title || titles[type]) + '</div>' +
              (message ? '<div class="toast-msg">' + message + '</div>' : '') +
            '</div>' +
            '<button class="toast-close" data-toast-close><i class="bi bi-x-lg"></i></button>' +
            '<div class="toast-progress"></div>';
        toast.querySelector('[data-toast-close]').addEventListener('click', function() {
            dismissToast(toast);
        });
        wrap.appendChild(toast);
        requestAnimationFrame(() => { requestAnimationFrame(() => { toast.classList.add('show'); }); });
        const timer = setTimeout(() => dismissToast(toast), duration);
        toast._timer = timer;
    }
    function dismissToast(toast) {
        if (!toast || toast._dismissed) return;
        toast._dismissed = true;
        clearTimeout(toast._timer);
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 400);
    }

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showKaryakuToast('success', 'Berhasil!', @json(session('success')));
        });
    @endif
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showKaryakuToast('error', 'Gagal!', @json(session('error')));
        });
    @endif
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const errs = @json($errors->all());
            errs.forEach(function(err) {
                showKaryakuToast('error', 'Validasi Gagal', err);
            });
        });
    @endif
    @if(session('warning'))
        document.addEventListener('DOMContentLoaded', function() {
            showKaryakuToast('warning', 'Peringatan', @json(session('warning')));
        });
    @endif
    @if(session('info'))
        document.addEventListener('DOMContentLoaded', function() {
            showKaryakuToast('info', 'Info', @json(session('info')));
        });
    @endif
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile Drawer Toggle
    const btnToggleMenu = document.getElementById('btnToggleMenu');
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

    // User Dropdown
    const userMenu = document.getElementById('userMenu');
    const btnUserChip = document.getElementById('btnUserChip');
    if (btnUserChip && userMenu) {
        btnUserChip.addEventListener('click', (e) => {
            e.stopPropagation();
            if (typeof notifMenu !== 'undefined' && notifMenu) notifMenu.classList.remove('open');
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) userMenu.classList.remove('open');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') userMenu.classList.remove('open');
        });
    }

    // Notifications Dropdown
    const notifMenu = document.getElementById('notifMenu');
    const btnNotif  = document.getElementById('btnNotif');
    if (btnNotif && notifMenu) {
        btnNotif.addEventListener('click', (e) => {
            e.stopPropagation();
            if (typeof userMenu !== 'undefined' && userMenu) userMenu.classList.remove('open');
            notifMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!notifMenu.contains(e.target)) notifMenu.classList.remove('open');
        });
    }

    // Toggle Wishlist via AJAX
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.wish-btn[data-url], .wish-icon-btn[data-url]');
        if (!btn) return;
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
            const card = btn.closest('.product-card') || btn.closest('[data-wishlist-row]') || btn.closest('.card-box');
            const wishButtons = card ? card.querySelectorAll('.wish-btn, .wish-icon-btn') : [btn];
            
            wishButtons.forEach(b => {
                if (data.status === 'added') {
                    b.classList.add('active');
                    const icon = b.querySelector('i');
                    if (icon) {
                        icon.className = b.classList.contains('wish-icon-btn') ? 'bi bi-heart-fill text-danger' : 'bi bi-heart-fill';
                    }
                } else if (data.status === 'removed') {
                    b.classList.remove('active');
                    const icon = b.querySelector('i');
                    if (icon) {
                        icon.className = 'bi bi-heart';
                    }
                    if (b.dataset.removeOnUnwish === '1' || window.location.pathname.includes('/wishlist')) {
                        b.closest('[data-wishlist-row]')?.remove();
                    }
                }
            });
        })
        .catch(() => alert('Gagal memperbarui Disukai. Coba lagi.'));
    });
</script>
@stack('scripts')
</body>
</html>

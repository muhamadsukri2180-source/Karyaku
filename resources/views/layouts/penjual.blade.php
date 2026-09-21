<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Pusat Penjual') - Karyaku</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    /* Ambient background glow */
    .bg-decor { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
    .bg-decor span { position: absolute; border-radius: 50%; background: radial-gradient(circle at 30% 30%, rgba(37, 99, 235, 0.07), transparent 70%); opacity: .8; animation: floatBlob 18s ease-in-out infinite; }
    .bg-decor span:nth-child(1) { width: 450px; height: 450px; top: -150px; right: -100px; animation-duration: 20s; }
    .bg-decor span:nth-child(2) { width: 380px; height: 380px; bottom: -100px; left: -80px; animation-duration: 24s; animation-delay: 3s; }
    @keyframes floatBlob { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(25px,-25px) scale(1.05); } }

    /* ================= NAVBAR ================= */
    .site-navbar {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        position: sticky;
        top: 0;
        z-index: 1030;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(12px);
    }
    .navbar-top { display: flex; align-items: center; gap: 16px; padding: 12px 24px; max-width: 1440px; margin: 0 auto; }

    .brand { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #ffffff, #eff6ff); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; box-shadow: 0 4px 12px rgba(0,0,0,.1); transition: var(--transition); overflow: hidden; padding: 0; }
    .brand:hover .brand-icon { transform: scale(1.05) rotate(-5deg); }
    .brand-icon img { width: 34px; height: 34px; object-fit: contain; border-radius: 8px; }
    .brand-text h5 { margin: 0; font-weight: 800; font-size: 16px; color: var(--white); line-height: 1.1; letter-spacing: -.3px; }
    .brand-text small { color: rgba(255,255,255,.7); font-size: 10.5px; font-weight: 500; }

    .mobile-toggle { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.15); color: #fff; display: none; align-items: center; justify-content: center; flex-shrink: 0; transition: var(--transition); }
    .mobile-toggle:hover { background: rgba(255,255,255,.22); }

    .nav-menu { display: flex; align-items: center; gap: 4px; flex: 1; margin-left: 8px; }
    .nav-menu .nav-link { position: relative; display: flex; align-items: center; gap: 7px; color: rgba(255,255,255,.85); padding: 8px 13px; border-radius: 10px; font-size: 13px; font-weight: 500; white-space: nowrap; transition: var(--transition); }
    .nav-menu .nav-link i { font-size: 15px; }
    .nav-menu .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; transform: translateY(-1px); }
    .nav-menu .nav-link.active { background: rgba(255,255,255,.18); color: #fff; font-weight: 700; box-shadow: inset 0 0 0 1px rgba(255,255,255,.2); }
    .nav-menu .nav-link.active::after { content: ""; position: absolute; bottom: -2px; left: 14px; right: 14px; height: 3px; background: var(--coral); border-radius: 4px; }

    .badge-count { background: var(--coral); color: #fff; font-size: 10px; font-weight: 700; min-width: 18px; height: 18px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 5px; margin-left: 2px; box-shadow: 0 2px 6px rgba(255,122,89,.4); }

    .navbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .btn-jual { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--coral), var(--coral-dark)); color: #fff; border: none; padding: 8px 16px; border-radius: 11px; font-weight: 700; font-size: 12.5px; white-space: nowrap; transition: var(--transition); box-shadow: 0 4px 14px rgba(255,122,89,.35); }
    .btn-jual:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,122,89,.45); color: #fff; }

    .icon-btn-light { width: 38px; height: 38px; border-radius: 11px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; color: #fff; position: relative; font-size: 16px; transition: var(--transition); flex-shrink: 0; cursor: pointer; }
    .icon-btn-light:hover { background: rgba(255,255,255,.22); color: #fff; transform: translateY(-1px); }
    .icon-btn-light .dot { position: absolute; top: 2px; right: 2px; min-width: 17px; height: 17px; padding: 0 4px; background: var(--coral); border-radius: 20px; border: 2px solid #1e3a8a; font-size: 9.5px; font-weight: 800; display: flex; align-items: center; justify-content: center; color: #fff; }

    .user-menu { position: relative; flex-shrink: 0; }
    .user-chip { display: flex; align-items: center; gap: 9px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.15); padding: 4px 12px 4px 4px; border-radius: 30px; transition: var(--transition); cursor: pointer; }
    .user-chip:hover { background: rgba(255,255,255,.2); }
    .user-chip img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
    .user-chip .name { font-size: 12.5px; font-weight: 600; line-height: 1.1; color: #fff; text-align: left; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .user-chip .role { font-size: 10px; color: rgba(255,255,255,.7); }
    .user-chip .bi-chevron-down { font-size: 11px; color: rgba(255,255,255,.75); margin-left: 2px; transition: transform .2s ease; }
    .user-menu.open .user-chip .bi-chevron-down { transform: rotate(180deg); }

    .user-dropdown, .notif-dropdown {
        position: absolute; right: 0; top: calc(100% + 10px);
        width: 250px; max-width: calc(100vw - 24px);
        background: #fff; border-radius: 16px;
        box-shadow: 0 16px 36px rgba(15,23,42,.16);
        border: 1px solid var(--border-color);
        padding: 8px;
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: var(--transition);
        z-index: 1040;
    }
    .notif-dropdown { width: 320px; max-height: 400px; overflow-y: auto; }
    .user-menu.open .user-dropdown,
    .notif-menu.open .notif-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }

    .user-dropdown a, .user-dropdown button {
        width: 100%; text-align: left; background: none; border: none;
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 10px;
        font-size: 13px; font-weight: 500; color: var(--text-dark);
        transition: background .15s ease;
    }
    .user-dropdown a i, .user-dropdown button i { width: 16px; text-align: center; flex-shrink: 0; color: var(--text-muted); }
    .user-dropdown a:hover, .user-dropdown button:hover { background: var(--primary-light); color: var(--primary-dark); }
    .user-dropdown a:hover i, .user-dropdown button:hover i { color: var(--primary-dark); }
    .user-dropdown .text-danger:hover { background: #fef2f2; color: #ef4444; }
    .user-dropdown .text-danger i { color: #ef4444; }
    .user-dropdown hr { margin: 6px 4px; border-color: var(--border-color); }
    .user-dropdown .dropdown-membership { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; }

    .notif-item { display: block; padding: 10px 12px; border-radius: 10px; transition: background .15s ease; text-decoration: none; margin-bottom: 2px; }
    .notif-item:hover { background: var(--primary-light); }
    .notif-item .n-title { font-size: 12.5px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .notif-item .n-desc { font-size: 11.5px; color: var(--text-muted); line-height: 1.4; }
    .notif-item .n-time { font-size: 10px; color: var(--text-muted); margin-top: 4px; }

    /* Panel mobile */
    .mobile-menu-panel { display: none; max-height: 0; overflow: hidden; background: var(--primary-darker); transition: max-height .3s cubic-bezier(0.4,0,0.2,1); border-top: 1px solid rgba(255,255,255,.08); }
    .mobile-menu-panel.show { max-height: 680px; overflow-y: auto; }
    .mobile-menu-panel .nav-link { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,.85); padding: 12px 20px; font-size: 13.5px; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,.05); text-decoration: none; }
    .mobile-menu-panel .nav-link i { font-size: 17px; width: 22px; color: rgba(255,255,255,.7); }
    .mobile-menu-panel .nav-link.active { color: #fff; background: rgba(255,255,255,.1); font-weight: 600; }
    .mobile-menu-panel .nav-link.active i { color: var(--coral); }
    .mobile-menu-panel .badge-count { margin-left: auto; }
    .mobile-menu-panel .logout-link { color: #fecaca; }
    .mobile-menu-panel .logout-link i { color: #fecaca; }

    @media (max-width: 1366px) {
        .navbar-top { padding: 10px 18px; gap: 8px; }
        .nav-menu { gap: 2px; margin-left: 4px; }
        .nav-menu .nav-link { padding: 6px 9px; font-size: 12px; gap: 5px; }
        .nav-menu .nav-link i { font-size: 14px; }
        .btn-jual { padding: 7px 12px; font-size: 12px; }
    }
    @media (max-width: 1180px) {
        .mobile-toggle { display: flex; }
        .nav-menu { display: none; }
        .mobile-menu-panel { display: block; }
        .btn-jual span { display: none; }
    }
    @media (max-width: 576px) {
        .navbar-top { padding: 10px 14px; gap: 8px; }
        .main-content { padding: 16px 12px 50px; }
        .user-chip .d-lg-block { display: none !important; }
        .user-chip { padding: 4px; }
    }

    /* ================= MAIN CONTENT ================= */
    .main-content { padding: 24px 24px 60px; max-width: 1440px; margin: 0 auto; width: 100%; flex: 1; }

    .stat-card { background: #fff; border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--border-color); }
    .stat-card .icon { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff; }
    .stat-card .value { font-size: 24px; font-weight: 800; color: var(--text-dark); }
    .stat-card .label { font-size: 12px; color: var(--text-muted); font-weight: 600; }

    .card-box { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); transition: var(--transition); }
    .hover-shadow { transition: transform .2s ease, box-shadow .2s ease; }
    .hover-shadow:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
    .badge-status { font-size: 10.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .table thead th { font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }

    ::-webkit-scrollbar { width: 7px; height: 7px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@stack('styles')
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>

@php
    $sideUser = Auth::user();
    $sideMembership = $sideUser->membership->name ?? 'Standar';
    $currentUserId = $sideUser->id_user ?? Auth::id();

    $latestNotifications = $currentUserId
        ? \App\Models\Notification::where(function ($q) use ($currentUserId) {
            $q->whereNull('user_id')->orWhere('user_id', $currentUserId);
        })->latest()->take(5)->get()
        : collect();

    $unreadNotifCount = $currentUserId
        ? \App\Models\Notification::where(function ($q) use ($currentUserId) {
            $q->whereNull('user_id')->orWhere('user_id', $currentUserId);
        })->where('is_read', false)->count()
        : 0;
@endphp

<header class="site-navbar">
    <div class="navbar-top">
        <button class="mobile-toggle" id="btnToggleMenu" aria-label="Buka menu" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a href="{{ route('penjual.dashboard') }}" class="brand">
            <div class="brand-icon">
                <img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo">
            </div>
            <div class="brand-text d-none d-sm-block">
                <h5>KaryaKu</h5>
                <small>Pusat Penjual</small>
            </div>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('penjual.dashboard') }}" class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
            <a href="{{ route('penjual.produk.index') }}" class="nav-link {{ request()->routeIs('penjual.produk*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Produk</a>
            <a href="{{ route('penjual.pesanan.index') }}" class="nav-link {{ request()->routeIs('penjual.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff"></i> Pesanan</a>
            <a href="{{ route('penjual.iklan.index') }}" class="nav-link {{ request()->routeIs('penjual.iklan*') ? 'active' : '' }}"><i class="bi bi-megaphone-fill"></i> Iklan</a>
            <a href="{{ route('penjual.keuangan.index') }}" class="nav-link {{ request()->routeIs('penjual.keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Saldo</a>
            <a href="{{ route('penjual.membership.index') }}" class="nav-link {{ request()->routeIs('penjual.membership*') ? 'active' : '' }}"><i class="bi bi-gem"></i> Membership</a>
            <a href="{{ route('penjual.laporan.index') }}" class="nav-link {{ request()->routeIs('penjual.laporan*') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
        </nav>

        <div class="navbar-right">
            <a href="{{ route('pembeli.marketplace') }}" class="btn-jual d-none d-md-inline-flex">
                <i class="bi bi-shop"></i> <span>Ke Toko</span>
            </a>

            {{-- NOTIFIKASI PENJUAL --}}
            <div class="user-menu notif-menu" id="notifMenu">
                <button class="icon-btn-light" id="btnNotif" type="button" title="Notifikasi Penjual" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if ($unreadNotifCount > 0)<span class="dot">{{ $unreadNotifCount }}</span>@endif
                </button>
                <div class="notif-dropdown">
                    <div class="d-flex align-items-center justify-content-between px-2 py-1 mb-1 border-bottom">
                        <span class="fw-bold small text-dark"><i class="bi bi-bell-fill text-primary me-1"></i> Notifikasi Penjual</span>
                        @if($unreadNotifCount > 0)
                            <span class="badge bg-danger rounded-pill" style="font-size:10px;">{{ $unreadNotifCount }} Baru</span>
                        @endif
                    </div>
                    @forelse ($latestNotifications as $notif)
                        <a href="{{ route('penjual.notifikasi') }}" class="notif-item">
                            <div class="n-title d-flex justify-content-between align-items-center">
                                <span class="text-truncate me-1">{{ $notif->name }}</span>
                                @if(!$notif->is_read)
                                    <span class="badge bg-primary" style="font-size:8px; padding:2px 5px;">Baru</span>
                                @endif
                            </div>
                            <div class="n-desc">{{ \Illuminate\Support\Str::limit($notif->description, 68) }}</div>
                            <div class="n-time"><i class="bi bi-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}</div>
                        </a>
                    @empty
                        <div class="text-center text-muted small py-3">Belum ada notifikasi baru.</div>
                    @endforelse
                    <hr>
                    <a href="{{ route('penjual.notifikasi') }}" class="d-block text-center small fw-semibold py-1" style="color: var(--primary);">Lihat Semua Notifikasi &rarr;</a>
                </div>
            </div>

            {{-- MENU PENGGUNA --}}
            <div class="user-menu" id="userMenu">
                <button class="user-chip" id="btnUserChip" type="button">
                    <img src="{{ $sideUser->avatar ? asset('storage/' . $sideUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($sideUser->name) . '&background=ffffff&color=1e3a8a' }}" alt="avatar">
                    <div class="d-none d-lg-block">
                        <div class="name">{{ $sideUser->name }}</div>
                        <div class="role">{{ $sideMembership }}</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="user-dropdown">
                    <div class="dropdown-membership">
                        <span class="small fw-semibold" style="color:var(--text-muted);">Paket Aktif</span>
                        <span class="badge" style="background:var(--primary-light); color:var(--primary); font-size:10.5px;">{{ $sideMembership }}</span>
                    </div>
                    <hr>
                    <a href="{{ route('penjual.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
                    <a href="{{ route('penjual.notifikasi') }}">
                        <i class="bi bi-bell-fill"></i> Notifikasi
                        @if($unreadNotifCount > 0)<span class="badge bg-danger ms-auto" style="font-size:10px;">{{ $unreadNotifCount }}</span>@endif
                    </a>
                    <a href="{{ route('penjual.produk.index') }}"><i class="bi bi-box-seam-fill"></i> Produk Saya</a>
                    <a href="{{ route('penjual.pesanan.index') }}"><i class="bi bi-receipt-cutoff"></i> Pesanan Masuk</a>
                    <a href="{{ route('penjual.iklan.index') }}"><i class="bi bi-megaphone-fill"></i> Iklan & Promosi</a>
                    <a href="{{ route('penjual.keuangan.index') }}"><i class="bi bi-wallet2"></i> Saldo & Penarikan</a>
                    <a href="{{ route('penjual.membership.index') }}"><i class="bi bi-gem"></i> Paket Membership</a>
                    <a href="{{ route('penjual.laporan.index') }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
                    <hr>
                    <a href="{{ route('pembeli.marketplace') }}"><i class="bi bi-bag-check-fill"></i> Belanja Karya Lain</a>
                    <a href="{{ route('pembeli.dashboard') }}"><i class="bi bi-person-workspace"></i> Beranda Pembeli</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL MOBILE --}}
    <div class="mobile-menu-panel" id="mobileMenuPanel">
        <a href="{{ route('penjual.dashboard') }}" class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
        <a href="{{ route('penjual.notifikasi') }}" class="nav-link {{ request()->routeIs('penjual.notifikasi*') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i> Notifikasi
            @if($unreadNotifCount > 0)<span class="badge-count">{{ $unreadNotifCount }}</span>@endif
        </a>
        <a href="{{ route('penjual.produk.index') }}" class="nav-link {{ request()->routeIs('penjual.produk*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Produk Saya</a>
        <a href="{{ route('penjual.pesanan.index') }}" class="nav-link {{ request()->routeIs('penjual.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff"></i> Pesanan Masuk</a>
        <a href="{{ route('penjual.iklan.index') }}" class="nav-link {{ request()->routeIs('penjual.iklan*') ? 'active' : '' }}"><i class="bi bi-megaphone-fill"></i> Iklan & Promosi</a>
        <a href="{{ route('penjual.keuangan.index') }}" class="nav-link {{ request()->routeIs('penjual.keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Saldo & Penarikan</a>
        <a href="{{ route('penjual.membership.index') }}" class="nav-link {{ request()->routeIs('penjual.membership*') ? 'active' : '' }}"><i class="bi bi-gem"></i> Paket Membership</a>
        <a href="{{ route('penjual.laporan.index') }}" class="nav-link {{ request()->routeIs('penjual.laporan*') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
        <a href="{{ route('penjual.produk.create') }}" class="nav-link"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
        <a href="{{ route('pembeli.marketplace') }}" class="nav-link"><i class="bi bi-bag-check-fill"></i> Belanja Karya Lain</a>
        <a href="{{ route('pembeli.dashboard') }}" class="nav-link"><i class="bi bi-person-workspace"></i> Beranda Pembeli</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link logout-link w-100 border-0 bg-transparent text-start"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</header>

<main class="main-content">
    @yield('content')
</main>

{{-- ========== TOAST NOTIFIKASI ========== --}}
<style>
    .karyaku-toast-wrap { position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none; }
    .karyaku-toast { display: flex; align-items: flex-start; gap: 12px; min-width: 300px; max-width: 380px; background: #fff; border-radius: 16px; box-shadow: 0 16px 40px rgba(15,23,42,.16), 0 4px 12px rgba(15,23,42,.08); border: 1px solid var(--border-color); padding: 14px 16px; pointer-events: all; position: relative; overflow: hidden; transform: translateX(120%); opacity: 0; transition: transform .38s cubic-bezier(0.34,1.56,0.64,1), opacity .28s ease; }
    .karyaku-toast.show { transform: translateX(0); opacity: 1; }
    .karyaku-toast.hide { transform: translateX(120%); opacity: 0; }
    .karyaku-toast .toast-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .karyaku-toast.toast-success .toast-icon { background: #ecfdf5; color: #10b981; }
    .karyaku-toast.toast-error   .toast-icon { background: #fef2f2; color: #ef4444; }
    .karyaku-toast.toast-warning .toast-icon { background: #fffbeb; color: #f59e0b; }
    .karyaku-toast.toast-info    .toast-icon { background: #eff6ff; color: #3b82f6; }
    .karyaku-toast .toast-body { flex: 1; min-width: 0; }
    .karyaku-toast .toast-title { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; line-height: 1.3; }
    .karyaku-toast .toast-msg { font-size: 12px; color: var(--text-muted); line-height: 1.5; }
    .karyaku-toast .toast-close { background: none; border: none; color: var(--text-muted); font-size: 16px; padding: 0; cursor: pointer; line-height: 1; flex-shrink: 0; transition: color .15s; }
    .karyaku-toast .toast-close:hover { color: var(--text-dark); }
    .karyaku-toast .toast-progress { position: absolute; bottom: 0; left: 0; height: 3px; border-radius: 0 0 16px 16px; animation: toastProgress 4s linear forwards; }
    .karyaku-toast.toast-success .toast-progress { background: #10b981; }
    .karyaku-toast.toast-error   .toast-progress { background: #ef4444; }
    .karyaku-toast.toast-warning .toast-progress { background: #f59e0b; }
    .karyaku-toast.toast-info    .toast-progress { background: #3b82f6; }
    @keyframes toastProgress { from { width: 100%; } to { width: 0%; } }
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
    // Toggle panel menu mobile
    const btnToggleMenu   = document.getElementById('btnToggleMenu');
    const mobileMenuPanel = document.getElementById('mobileMenuPanel');
    if (btnToggleMenu && mobileMenuPanel) {
        btnToggleMenu.addEventListener('click', () => {
            const isOpen = mobileMenuPanel.classList.toggle('show');
            btnToggleMenu.setAttribute('aria-expanded', isOpen);
            btnToggleMenu.querySelector('i').className = isOpen ? 'bi bi-x-lg fs-5' : 'bi bi-list fs-5';
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1180 && mobileMenuPanel.classList.contains('show')) {
                mobileMenuPanel.classList.remove('show');
                btnToggleMenu.setAttribute('aria-expanded', false);
                btnToggleMenu.querySelector('i').className = 'bi bi-list fs-5';
            }
        });
    }

    // Dropdown menu pengguna & notifikasi
    const userMenu    = document.getElementById('userMenu');
    const btnUserChip = document.getElementById('btnUserChip');
    const notifMenu   = document.getElementById('notifMenu');
    const btnNotif    = document.getElementById('btnNotif');

    if (btnUserChip && userMenu) {
        btnUserChip.addEventListener('click', (e) => {
            e.stopPropagation();
            if (notifMenu) notifMenu.classList.remove('open');
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) userMenu.classList.remove('open');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') userMenu.classList.remove('open');
        });
    }

    if (btnNotif && notifMenu) {
        btnNotif.addEventListener('click', (e) => {
            e.stopPropagation();
            if (userMenu) userMenu.classList.remove('open');
            notifMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!notifMenu.contains(e.target)) notifMenu.classList.remove('open');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') notifMenu.classList.remove('open');
        });
    }
</script>
@stack('scripts')
</body>
</html>
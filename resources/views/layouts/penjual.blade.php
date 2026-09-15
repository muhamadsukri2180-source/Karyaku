<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Seller Center') - Karyaku</title>
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
    .user-dropdown{ position: absolute; right: 0; top: calc(100% + 10px); width: 230px; background: #fff; border-radius: 14px; box-shadow: var(--shadow-hover); padding: 8px; opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all .18s ease; z-index: 1040; }
    .user-menu.open .user-dropdown{ opacity: 1; visibility: visible; transform: translateY(0); }
    .user-dropdown a, .user-dropdown button{ width: 100%; text-align: left; background: none; border: none; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; font-size: 13.5px; font-weight: 500; color: var(--text-dark); transition: background .15s ease; }
    .user-dropdown a:hover, .user-dropdown button:hover{ background: var(--primary-light); color: var(--primary-dark); }
    .user-dropdown .text-danger:hover{ background: #fef2f2; }
    .user-dropdown hr{ margin: 6px 4px; border-color: var(--border-color); }
    .user-dropdown .dropdown-membership{ display:flex; align-items:center; justify-content:space-between; padding: 8px 12px; }
    .mobile-menu-panel{ display: none; max-height: 0; overflow: hidden; background: var(--primary-darker); transition: max-height .28s ease; }
    .mobile-menu-panel.show{ max-height: 640px; }
    .mobile-menu-panel .nav-link{ display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.82); padding: 13px 22px; font-size: 14px; font-weight: 500; border-top: 1px solid rgba(255,255,255,0.08); }
    .mobile-menu-panel .nav-link i{ font-size: 17px; width: 20px; }
    .mobile-menu-panel .nav-link.active{ color: #fff; background: rgba(255,255,255,0.08); font-weight: 600; }
    .mobile-menu-panel .logout-link{ color: #fecaca; }
    @media (max-width: 992px){ .mobile-toggle{ display: flex; } .nav-menu{ display: none; } .mobile-menu-panel{ display: block; } .btn-jual span{ display: none; } .user-chip .d-lg-block{ display: none !important; } }
    @media (max-width: 576px){ .navbar-top{ padding: 10px 16px; gap: 10px; } }

    .main-content{ padding: 24px 28px 60px; max-width: 1440px; margin: 0 auto; }
    @media (max-width: 576px){ .main-content{ padding: 18px 16px 50px; } }

    .stat-card{ background: #fff; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--border-color); }
    .stat-card .icon{ width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff; }
    .stat-card .value{ font-size: 24px; font-weight: 800; color: var(--text-dark); }
    .stat-card .label{ font-size: 12px; color: var(--text-muted); font-weight: 600; }

    .card-box{ background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border-color); }
    .hover-shadow{ transition: transform .2s ease, box-shadow .2s ease; }
    .hover-shadow:hover{ transform: translateY(-3px); box-shadow: var(--shadow-hover); }
    .badge-status{ font-size: 10.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .table thead th{ font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }

    ::-webkit-scrollbar{ width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }
</style>
@stack('styles')
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>

@php
    $sideUser = Auth::user();
    $sideMembership = $sideUser->membership->name ?? 'Standar';
@endphp

<header class="site-navbar">
    <div class="navbar-top">
        <button class="mobile-toggle" id="btnToggleMenu" aria-label="Buka menu" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a href="{{ route('penjual.dashboard') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-shop"></i></div>
            <div class="brand-text d-none d-sm-block">
                <h5>Karyaku</h5>
                <small>Seller Center</small>
            </div>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('penjual.dashboard') }}" class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="{{ route('penjual.produk.index') }}" class="nav-link {{ request()->routeIs('penjual.produk*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Produk Saya</a>
            <a href="{{ route('penjual.pesanan.index') }}" class="nav-link {{ request()->routeIs('penjual.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff"></i> Pesanan</a>
            <a href="{{ route('penjual.iklan.index') }}" class="nav-link {{ request()->routeIs('penjual.iklan*') ? 'active' : '' }}"><i class="bi bi-megaphone-fill"></i> Iklan</a>
            <a href="{{ route('penjual.keuangan.index') }}" class="nav-link {{ request()->routeIs('penjual.keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Saldo</a>
            <a href="{{ route('penjual.membership.index') }}" class="nav-link {{ request()->routeIs('penjual.membership*') ? 'active' : '' }}"><i class="bi bi-gem"></i> Membership</a>
            <a href="{{ route('penjual.laporan.index') }}" class="nav-link {{ request()->routeIs('penjual.laporan*') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
            <a href="{{ route('penjual.peringatan') }}" class="nav-link {{ request()->routeIs('penjual.peringatan') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Peringatan Saya</a>
        </nav>

            <a href="{{ route('pembeli.marketplace') }}" class="btn-jual d-none d-md-inline-flex">
                <i class="bi bi-shop"></i> <span>Ke Marketplace</span>
            </a>

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
                    <a href="{{ route('penjual.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                    <a href="{{ route('penjual.produk.index') }}"><i class="bi bi-box-seam-fill"></i> Produk Saya</a>
                    <a href="{{ route('penjual.pesanan.index') }}"><i class="bi bi-receipt-cutoff"></i> Pesanan Masuk</a>
                    <a href="{{ route('penjual.iklan.index') }}"><i class="bi bi-megaphone-fill"></i> Iklan & Promosi</a>
                    <a href="{{ route('penjual.keuangan.index') }}"><i class="bi bi-wallet2"></i> Saldo & Penarikan</a>
                    <a href="{{ route('penjual.membership.index') }}"><i class="bi bi-gem"></i> Paket Membership</a>
                    <a href="{{ route('penjual.laporan.index') }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
                    <a href="{{ route('penjual.peringatan') }}"><i class="bi bi-shield-exclamation"></i> Peringatan Saya</a>
                    <hr>
                    <a href="{{ route('pembeli.marketplace') }}"><i class="bi bi-bag-check-fill"></i> Belanja Karya Lain</a>
                    <a href="{{ route('pembeli.dashboard') }}"><i class="bi bi-person-workspace"></i> Dashboard Pembeli</a>
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
        <a href="{{ route('penjual.dashboard') }}" class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="{{ route('penjual.produk.index') }}" class="nav-link {{ request()->routeIs('penjual.produk*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Produk Saya</a>
        <a href="{{ route('penjual.pesanan.index') }}" class="nav-link {{ request()->routeIs('penjual.pesanan*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff"></i> Pesanan Masuk</a>
        <a href="{{ route('penjual.iklan.index') }}" class="nav-link {{ request()->routeIs('penjual.iklan*') ? 'active' : '' }}"><i class="bi bi-megaphone-fill"></i> Iklan & Promosi</a>
        <a href="{{ route('penjual.keuangan.index') }}" class="nav-link {{ request()->routeIs('penjual.keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Saldo & Penarikan</a>
        <a href="{{ route('penjual.membership.index') }}" class="nav-link {{ request()->routeIs('penjual.membership*') ? 'active' : '' }}"><i class="bi bi-gem"></i> Paket Membership</a>
        <a href="{{ route('penjual.laporan.index') }}" class="nav-link {{ request()->routeIs('penjual.laporan*') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Laporan</a>
         <a href="{{ route('penjual.peringatan') }}" class="nav-link {{ request()->routeIs('penjual.peringatan') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i> Peringatan Saya</a>
        <a href="{{ route('penjual.produk.create') }}" class="nav-link"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
        <a href="{{ route('pembeli.marketplace') }}" class="nav-link"><i class="bi bi-bag-check-fill"></i> Belanja Karya Lain</a>
        <a href="{{ route('pembeli.dashboard') }}" class="nav-link"><i class="bi bi-person-workspace"></i> Dashboard Pembeli</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link logout-link w-100 border-0 bg-transparent text-start"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</header>

<main class="main-content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show card-box p-3 border-0 border-start border-4 border-success mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span class="fw-medium small">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show card-box p-3 border-0 border-start border-4 border-danger mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <span class="fw-medium small">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
</script>
@stack('scripts')
</body>
</html>

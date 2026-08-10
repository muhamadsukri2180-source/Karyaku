<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya - Karyaku</title>

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
        --navbar-h: 68px;
    }
    *{ box-sizing: border-box; }
    body{
        font-family: 'Poppins', sans-serif;
        background: var(--primary-light);
        color: var(--text-dark);
        overflow-x: hidden;
    }
    a{ text-decoration: none; }

    /* ---------------- Background decor ---------------- */
    .bg-decor{ position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
    .bg-decor span{
        position: absolute; border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, var(--primary-soft), transparent 70%);
        opacity: .5; animation: floatBlob 14s ease-in-out infinite;
    }
    .bg-decor span:nth-child(1){ width: 380px; height: 380px; top: -120px; right: -100px; animation-duration: 16s; }
    .bg-decor span:nth-child(2){ width: 260px; height: 260px; bottom: -80px; left: -60px; animation-duration: 20s; animation-delay: 2s; }
    @keyframes floatBlob{ 0%,100%{ transform: translate(0,0) scale(1); } 50%{ transform: translate(20px,-30px) scale(1.08); } }

    /* ================= SITE NAVBAR (sama seperti Dashboard) ================= */
    .site-navbar{
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary));
        position: sticky; top: 0; z-index: 1030;
        box-shadow: 0 10px 30px rgba(20,34,92,0.18);
    }

    .navbar-top{
        display: flex; align-items: center; gap: 18px;
        padding: 12px 28px;
        max-width: 1440px; margin: 0 auto;
    }

    .brand{ display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .brand-icon{ width: 40px; height: 40px; background: var(--white); color: var(--primary); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; font-weight: 700; }
    .brand-text h5{ margin: 0; font-weight: 700; font-size: 15.5px; color: var(--white); line-height: 1.1; }
    .brand-text small{ color: rgba(255,255,255,0.6); font-size: 10.5px; }

    .mobile-toggle{
        width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.12);
        border: none; color: #fff; display: none; align-items: center; justify-content: center; flex-shrink: 0;
        transition: background .2s ease;
    }
    .mobile-toggle:hover{ background: rgba(255,255,255,0.22); }

    .nav-menu{ display: flex; align-items: center; gap: 2px; flex: 1; }
    .nav-menu .nav-link{
        position: relative; display: flex; align-items: center; gap: 8px;
        color: rgba(255,255,255,0.78); padding: 9px 14px; border-radius: 10px;
        font-size: 13.5px; font-weight: 500; white-space: nowrap; transition: all .2s ease;
    }
    .nav-menu .nav-link i{ font-size: 16px; }
    .nav-menu .nav-link:hover{ background: rgba(255,255,255,0.1); color: var(--white); }
    .nav-menu .nav-link.active{ background: rgba(255,255,255,0.16); color: var(--white); font-weight: 600; }
    .nav-menu .nav-link.active::after{
        content: ""; position: absolute; left: 14px; right: 14px; bottom: -1px; height: 2.5px;
        background: var(--coral); border-radius: 4px;
    }
    .nav-menu .badge-count{
        background: var(--coral); color: #fff; font-size: 10.5px; font-weight: 700;
        min-width: 17px; height: 17px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 4px;
    }

    .navbar-right{ display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

    .btn-jual{
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--coral); color: #fff; border: none;
        padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 13px;
        white-space: nowrap; transition: all .2s ease;
    }
    .btn-jual:hover{ background: var(--coral-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,122,89,0.35); }

    .icon-btn-light{
        width: 40px; height: 40px; border-radius: 12px;
        background: rgba(255,255,255,0.12); border: none;
        display: flex; align-items: center; justify-content: center;
        color: #fff; position: relative; font-size: 17px;
        transition: all .2s ease; flex-shrink: 0;
    }
    .icon-btn-light:hover{ background: rgba(255,255,255,0.22); color: #fff; }
    .icon-btn-light .dot{
        position: absolute; top: 4px; right: 4px; min-width: 16px; height: 16px; padding: 0 3px;
        background: var(--coral); border-radius: 20px; border: 2px solid var(--primary-dark);
        font-size: 9.5px; font-weight: 700; display: flex; align-items: center; justify-content: center;
    }

    .user-menu{ position: relative; flex-shrink: 0; }
    .user-chip{
        display: flex; align-items: center; gap: 9px;
        background: rgba(255,255,255,0.12); padding: 5px 12px 5px 5px; border-radius: 30px;
        transition: background .2s ease; border: none; cursor: pointer;
    }
    .user-chip:hover{ background: rgba(255,255,255,0.2); }
    .user-chip img{ width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
    .user-chip .name{ font-size: 12.5px; font-weight: 600; line-height: 1.1; color: #fff; text-align: left; }
    .user-chip .role{ font-size: 10.5px; color: rgba(255,255,255,0.65); }
    .user-chip .bi-chevron-down{ font-size: 11px; color: rgba(255,255,255,0.7); margin-left: 2px; transition: transform .2s ease; }
    .user-menu.open .user-chip .bi-chevron-down{ transform: rotate(180deg); }

    .user-dropdown{
        position: absolute; right: 0; top: calc(100% + 10px); width: 220px;
        background: #fff; border-radius: 14px; box-shadow: var(--shadow-hover);
        padding: 8px; opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all .18s ease; z-index: 1040;
    }
    .user-menu.open .user-dropdown{ opacity: 1; visibility: visible; transform: translateY(0); }
    .user-dropdown a{
        display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px;
        font-size: 13.5px; font-weight: 500; color: var(--text-dark); transition: background .15s ease;
    }
    .user-dropdown a:hover{ background: var(--primary-light); color: var(--primary-dark); }
    .user-dropdown a.text-danger:hover{ background: #fef2f2; }
    .user-dropdown hr{ margin: 6px 4px; border-color: var(--border-color); }
    .dropdown-logout-btn{
        display: flex; align-items: center; gap: 10px; width: 100%; text-align: left;
        padding: 10px 12px; border-radius: 10px; border: none; background: transparent;
        font-size: 13.5px; font-weight: 500; font-family: 'Poppins', sans-serif; transition: background .15s ease;
    }
    .dropdown-logout-btn:hover{ background: #fef2f2; }
    .mobile-logout-btn{
        width: 100%; text-align: left; border: none; font-family: 'Poppins', sans-serif; cursor: pointer;
    }

    /* ---- mobile dropdown menu ---- */
    .mobile-menu-panel{
        display: none;
        max-height: 0; overflow: hidden;
        background: var(--primary-darker);
        transition: max-height .28s ease;
    }
    .mobile-menu-panel.show{ max-height: 640px; }
    .mobile-menu-panel .nav-link{
        display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.82);
        padding: 13px 22px; font-size: 14px; font-weight: 500; border-top: 1px solid rgba(255,255,255,0.08);
    }
    .mobile-menu-panel .nav-link i{ font-size: 17px; width: 20px; }
    .mobile-menu-panel .nav-link.active{ color: #fff; background: rgba(255,255,255,0.08); font-weight: 600; }
    .mobile-menu-panel .nav-link .badge-count{
        margin-left: auto; background: var(--coral); color: #fff; font-size: 10.5px; font-weight: 700;
        min-width: 18px; height: 18px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 5px;
    }
    .mobile-menu-panel .logout-link{ color: #fecaca; }

    @media (max-width: 992px){
        .mobile-toggle{ display: flex; }
        .nav-menu{ display: none; }
        .mobile-menu-panel{ display: block; }
        .btn-jual span{ display: none; }
        .user-chip .d-lg-block{ display: none !important; }
    }
    @media (max-width: 576px){
        .navbar-top{ padding: 10px 16px; gap: 10px; }
        .btn-jual{ padding: 10px 12px; }
    }

    .main-content{ padding: 0 0 50px; }

    /* ================= PAGE HEADER ================= */
    .page-header-wrap{ padding: 30px 28px 0; max-width: 1200px; margin: 0 auto; }
    .page-header{
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary));
        border-radius: var(--radius); padding: 26px 32px; color: #fff; box-shadow: var(--shadow);
        position: relative; overflow: hidden;
    }
    .page-header::after{
        content: ""; position: absolute; width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,122,89,0.16); right: -50px; top: -60px;
    }
    .page-header .htext{ position: relative; z-index: 1; }
    .page-header h2{ font-weight: 800; font-size: 24px; margin: 0 0 6px; display: flex; align-items: center; gap: 10px; }
    .page-header p{ margin: 0; font-size: 13px; color: rgba(255,255,255,0.8); }

    /* ================= TABS ================= */
    .tabs-wrap{ max-width: 1200px; margin: 0 auto; padding: 20px 28px 0; }
    .status-tabs{
        display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px;
    }
    .status-tabs::-webkit-scrollbar{ display: none; }
    .status-tab{
        border: 1px solid var(--border-color); background: #fff; color: var(--text-dark);
        padding: 9px 18px; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer;
        white-space: nowrap; transition: all .2s ease; flex-shrink: 0;
    }
    .status-tab.active, .status-tab:hover{ background: var(--primary); color: #fff; border-color: var(--primary); }

    /* ================= ORDER LIST ================= */
    .order-wrap{ max-width: 1200px; margin: 0 auto; padding: 20px 28px 0; display: flex; flex-direction: column; gap: 16px; }

    .order-card{
        background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color);
        box-shadow: var(--shadow); overflow: hidden; transition: transform .25s ease, box-shadow .25s ease;
    }
    .order-card:hover{ transform: translateY(-4px); box-shadow: var(--shadow-hover); }

    .order-head{
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        padding: 14px 20px; border-bottom: 1px solid var(--border-color); background: var(--primary-light);
    }
    .order-head .order-no{ font-size: 12.5px; font-weight: 700; color: var(--primary-dark); }
    .order-head .order-date{ font-size: 11.5px; color: var(--text-muted); }

    .badge-status{
        font-size: 11px; font-weight: 700; padding: 5px 13px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-status.menunggu{ background: #fef3c7; color: #b45309; }
    .badge-status.diproses{ background: var(--primary-soft); color: var(--primary-dark); }
    .badge-status.dikirim{ background: #ede9fe; color: #6d28d9; }
    .badge-status.selesai{ background: #d1fae5; color: #047857; }
    .badge-status.dibatalkan{ background: #fee2e2; color: #b91c1c; }

    .order-body{ display: flex; align-items: center; gap: 16px; padding: 18px 20px; flex-wrap: wrap; }
    .order-body img{ width: 74px; height: 74px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
    .order-info{ flex: 1; min-width: 200px; }
    .order-info h6{ font-size: 14px; font-weight: 700; margin: 0 0 4px; }
    .order-info .seller{ font-size: 12px; color: var(--text-muted); margin-bottom: 4px; }
    .order-info .qty{ font-size: 12px; color: var(--text-muted); }

    .order-total{ text-align: right; flex-shrink: 0; }
    .order-total .lbl{ font-size: 11px; color: var(--text-muted); }
    .order-total .val{ font-size: 16px; font-weight: 800; color: var(--coral); }

    .order-footer{
        display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--border-color); flex-wrap: wrap;
    }
    .btn-order-detail{
        border: 1px solid var(--primary); background: #fff; color: var(--primary);
        padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 12.5px; transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-order-detail:hover{ background: var(--primary); color: #fff; }
    .btn-order-again{
        border: none; background: var(--coral); color: #fff;
        padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 12.5px; transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-order-again:hover{ background: var(--coral-dark); transform: translateY(-2px); box-shadow: 0 8px 18px rgba(255,122,89,.3); }

    .reveal{ opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .reveal.active{ opacity: 1; transform: translateY(0); }

    /* ================= EMPTY STATE ================= */
    .empty-state{ max-width: 1200px; margin: 20px auto 0; padding: 0 28px; }
    .empty-state .inner{
        background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);
        text-align: center; padding: 70px 20px; position: relative; overflow: hidden;
    }
    .empty-state .inner::before{
        content: ""; position: absolute; width: 280px; height: 280px; border-radius: 50%;
        background: var(--primary-soft); opacity: .5; top: -100px; left: -80px;
    }
    .empty-state .inner::after{
        content: ""; position: absolute; width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,122,89,0.12); bottom: -80px; right: -60px;
    }
    .empty-state .icon-circle{
        position: relative; z-index: 1; width: 108px; height: 108px; border-radius: 50%;
        background: var(--primary-light); display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; font-size: 46px; color: var(--coral);
        border: 2px dashed var(--primary-soft);
    }
    .empty-state h4{ position: relative; z-index: 1; font-weight: 800; font-size: 19px; margin-bottom: 8px; }
    .empty-state p{ position: relative; z-index: 1; color: var(--text-muted); font-size: 13.5px; max-width: 380px; margin: 0 auto 22px; }
    .empty-state .btn-belanja{
        position: relative; z-index: 1;
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--coral); color: #fff; border: none;
        padding: 12px 26px; border-radius: 12px; font-weight: 700; font-size: 14px;
        transition: all .2s ease;
    }
    .empty-state .btn-belanja:hover{ background: var(--coral-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 10px 22px rgba(255,122,89,.35); }

    @media (max-width: 576px){
        .order-body{ padding: 16px; }
        .order-total{ text-align: left; width: 100%; }
        .order-footer{ justify-content: flex-start; }
    }

    ::-webkit-scrollbar{ width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }
</style>
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>

{{-- ===================== NAVBAR ===================== --}}
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
            <a href="{{ route('pembeli.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="{{ route('pembeli.marketplace') }}" class="nav-link"><i class="bi bi-shop"></i> Marketplace</a>
            <a href="{{ route('pembeli.wishlist') }}" class="nav-link"><i class="bi bi-heart-fill"></i> Wishlist <span class="badge-count">5</span></a>
            <a href="{{ route('pembeli.keranjang') }}" class="nav-link"><i class="bi bi-cart-fill"></i> Keranjang <span class="badge-count">3</span></a>
            <a href="{{ route('pembeli.pesanan') }}" class="nav-link active"><i class="bi bi-receipt"></i> Pesanan</a>
            <a href="{{ route('pembeli.download') }}" class="nav-link"><i class="bi bi-cloud-arrow-down-fill"></i> Download</a>
        </nav>

        <div class="navbar-right">
            <a href="#" class="btn-jual d-none d-md-inline-flex">
                <i class="bi bi-shop-window"></i> <span>Daftar Sebagai Penjual</span>
            </a>
            <button class="icon-btn-light" title="Notifikasi">
                <i class="bi bi-bell"></i><span class="dot">2</span>
            </button>

            <div class="user-menu" id="userMenu">
                <button class="user-chip" id="btnUserChip">
                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ffffff&color=1e3a8a" alt="avatar">
                    <div class="d-none d-lg-block">
                        <div class="name">Budi Santoso</div>
                        <div class="role">Pembeli</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="user-dropdown">
                    <a href="{{ route('pembeli.profile') }}"><i class="bi bi-person-fill"></i> Profile</a>
                    <a href="{{ route('pembeli.pesanan') }}"><i class="bi bi-receipt"></i> Pesanan Saya</a>
                    <a href="{{ route('pembeli.download') }}"><i class="bi bi-cloud-arrow-down-fill"></i> Download Saya</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-logout-btn text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-menu-panel" id="mobileMenuPanel">
        <a href="{{ route('pembeli.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="{{ route('pembeli.marketplace') }}" class="nav-link"><i class="bi bi-shop"></i> Marketplace</a>
        <a href="{{ route('pembeli.wishlist') }}" class="nav-link"><i class="bi bi-heart-fill"></i> Wishlist <span class="badge-count">5</span></a>
        <a href="{{ route('pembeli.keranjang') }}" class="nav-link"><i class="bi bi-cart-fill"></i> Keranjang <span class="badge-count">3</span></a>
        <a href="{{ route('pembeli.pesanan') }}" class="nav-link active"><i class="bi bi-receipt"></i> Pesanan Saya</a>
        <a href="{{ route('pembeli.download') }}" class="nav-link"><i class="bi bi-cloud-arrow-down-fill"></i> Download Saya</a>
        <a href="{{ route('pembeli.profile') }}" class="nav-link"><i class="bi bi-person-fill"></i> Profile</a>
        <a href="#" class="nav-link"><i class="bi bi-shop-window"></i> Daftar Sebagai Penjual</a>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link logout-link mobile-logout-btn"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</header>

{{-- ===================== MAIN CONTENT ===================== --}}
<main class="main-content">

    <div class="page-header-wrap">
        <div class="page-header">
            <div class="htext">
                <h2><i class="bi bi-receipt"></i> Pesanan Saya</h2>
                <p>Pantau status pesanan jasa dan produk digital yang sudah kamu beli</p>
            </div>
        </div>
    </div>

    {{-- ============= TAB FILTER ============= --}}
    <div class="tabs-wrap">
        <div class="status-tabs" id="statusTabs">
            <button class="status-tab active" data-status="semua">Semua</button>
            <button class="status-tab" data-status="menunggu">Menunggu</button>
            <button class="status-tab" data-status="diproses">Diproses</button>
            <button class="status-tab" data-status="dikirim">Dikirim</button>
            <button class="status-tab" data-status="selesai">Selesai</button>
            <button class="status-tab" data-status="dibatalkan">Dibatalkan</button>
        </div>
    </div>

    {{-- ============= LIST PESANAN ============= --}}
    <div class="order-wrap" id="orderList">

        <!-- Pesanan 1 - Menunggu -->
        <div class="order-card reveal" data-status="menunggu">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260801-001</div>
                    <div class="order-date">1 Agustus 2026, 10:24</div>
                </div>
                <span class="badge-status menunggu"><i class="bi bi-hourglass-split"></i> Menunggu Pembayaran</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Desain Poster Promosi Kafe & Resto</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Dinda Studio</div>
                    <div class="qty">Jumlah: 1 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp75.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 1) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
            </div>
        </div>

        <!-- Pesanan 2 - Diproses -->
        <div class="order-card reveal" data-status="diproses">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260728-014</div>
                    <div class="order-date">28 Juli 2026, 15:02</div>
                </div>
                <span class="badge-status diproses"><i class="bi bi-gear-fill"></i> Diproses</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Model 3D Karakter Game Low-Poly</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Rangga.blend</div>
                    <div class="qty">Jumlah: 1 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp480.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 2) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
            </div>
        </div>

        <!-- Pesanan 3 - Dikirim -->
        <div class="order-card reveal" data-status="dikirim">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260722-009</div>
                    <div class="order-date">22 Juli 2026, 09:40</div>
                </div>
                <span class="badge-status dikirim"><i class="bi bi-truck"></i> Dikirim</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Paket Logo & Brand Identity Kit</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Kirana Design</div>
                    <div class="qty">Jumlah: 1 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp150.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 3) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
            </div>
        </div>

        <!-- Pesanan 4 - Selesai -->
        <div class="order-card reveal" data-status="selesai">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260710-002</div>
                    <div class="order-date">10 Juli 2026, 13:15</div>
                </div>
                <span class="badge-status selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Paket 15 Feed & Story Instagram</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Sasi Creative</div>
                    <div class="qty">Jumlah: 1 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp120.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 4) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
                <button class="btn-order-again"><i class="bi bi-arrow-repeat"></i> Beli Lagi</button>
            </div>
        </div>

        <!-- Pesanan 5 - Dibatalkan -->
        <div class="order-card reveal" data-status="dibatalkan">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260705-018</div>
                    <div class="order-date">5 Juli 2026, 08:51</div>
                </div>
                <span class="badge-status dibatalkan"><i class="bi bi-x-circle-fill"></i> Dibatalkan</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Desain UI Aplikasi Mobile Lengkap</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Nadia UX</div>
                    <div class="qty">Jumlah: 1 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp650.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 5) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
            </div>
        </div>

        <!-- Pesanan 6 - Selesai -->
        <div class="order-card reveal" data-status="selesai">
            <div class="order-head">
                <div>
                    <div class="order-no">#ORD-20260628-006</div>
                    <div class="order-date">28 Juni 2026, 19:30</div>
                </div>
                <span class="badge-status selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
            </div>
            <div class="order-body">
                <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=200&q=80" alt="">
                <div class="order-info">
                    <h6>Ilustrasi Vektor Karakter Custom</h6>
                    <div class="seller"><i class="bi bi-shop"></i> Ilma.art</div>
                    <div class="qty">Jumlah: 2 paket</div>
                </div>
                <div class="order-total">
                    <div class="lbl">Total Pembayaran</div>
                    <div class="val">Rp190.000</div>
                </div>
            </div>
            <div class="order-footer">
                <a href="{{ route('pembeli.pesanan.detail', 6) }}" class="btn-order-detail"><i class="bi bi-eye"></i> Lihat Detail</a>
                <button class="btn-order-again"><i class="bi bi-arrow-repeat"></i> Beli Lagi</button>
            </div>
        </div>

    </div>

    {{-- ============= EMPTY STATE (disembunyikan, muncul jika hasil filter kosong) ============= --}}
    <div class="empty-state d-none" id="emptyOrder">
        <div class="inner">
            <div class="icon-circle"><i class="bi bi-receipt"></i></div>
            <h4>Belum Ada Pesanan</h4>
            <p>Kamu belum memiliki pesanan pada status ini. Yuk mulai belanja karya digital dari kreator terbaik!</p>
            <a href="{{ route('pembeli.marketplace') }}" class="btn-belanja"><i class="bi bi-shop"></i> Belanja Sekarang</a>
        </div>
    </div>

</main>

<script>
    // ---- Toggle mobile menu ----
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

    // ---- Dropdown user chip ----
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

    // ---- Scroll reveal ----
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealEls.forEach(el => observer.observe(el));

    // ---- Filter tab pesanan ----
    const statusTabs = document.querySelectorAll('.status-tab');
    const orderCards = document.querySelectorAll('.order-card');
    const emptyOrder  = document.getElementById('emptyOrder');
    const orderList   = document.getElementById('orderList');

    statusTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            statusTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const status = tab.dataset.status;
            let visibleCount = 0;

            orderCards.forEach(card => {
                const match = status === 'semua' || card.dataset.status === status;
                card.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (visibleCount === 0) {
                orderList.classList.add('d-none');
                emptyOrder.classList.remove('d-none');
            } else {
                orderList.classList.remove('d-none');
                emptyOrder.classList.add('d-none');
            }
        });
    });

    // ---- Beli lagi feedback ----
    document.querySelectorAll('.btn-order-again').forEach(btn => {
        btn.addEventListener('click', () => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Ditambahkan ke Keranjang';
            setTimeout(() => { btn.innerHTML = original; }, 1400);
        });
    });
</script>
</body>
</html>
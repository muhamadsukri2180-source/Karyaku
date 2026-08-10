<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile Saya - Karyaku</title>

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

    /* ================= COVER PROFILE ================= */
    .profile-wrap{ max-width: 1140px; margin: 0 auto; padding: 30px 28px 0; }

    .cover-card{
        position: relative; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 55%, var(--primary));
        height: 180px;
    }
    .cover-card::after{
        content: ""; position: absolute; width: 260px; height: 260px; border-radius: 50%;
        background: rgba(255,122,89,0.16); right: -60px; top: -70px;
    }
    .cover-card::before{
        content: ""; position: absolute; width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,0.06); left: 30%; bottom: -100px;
    }
    .btn-edit-cover{
        position: absolute; top: 14px; right: 16px; z-index: 2;
        background: rgba(255,255,255,0.16); color: #fff; border: none; border-radius: 10px;
        padding: 8px 14px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s ease;
    }
    .btn-edit-cover:hover{ background: rgba(255,255,255,0.28); }

    .profile-head{
        display: flex; align-items: flex-end; gap: 20px; margin-top: -58px; padding: 0 30px; position: relative; z-index: 2; flex-wrap: wrap;
    }
    .profile-avatar-wrap{ position: relative; flex-shrink: 0; }
    .profile-avatar-wrap img{
        width: 112px; height: 112px; border-radius: 50%; object-fit: cover; border: 5px solid #fff; box-shadow: var(--shadow);
        background: #fff;
    }
    .btn-edit-avatar{
        position: absolute; bottom: 4px; right: 4px; width: 32px; height: 32px; border-radius: 50%;
        background: var(--coral); color: #fff; border: 3px solid #fff; display: flex; align-items: center; justify-content: center;
        font-size: 13px; transition: all .2s ease;
    }
    .btn-edit-avatar:hover{ background: var(--coral-dark); transform: scale(1.06); }

    .profile-namebox{ padding-bottom: 10px; flex: 1; min-width: 220px; }
    .profile-namebox h3{ font-weight: 800; font-size: 21px; margin: 0 0 4px; }
    .profile-namebox .badge-role{
        background: var(--primary-soft); color: var(--primary-dark); font-size: 11px; font-weight: 700;
        padding: 4px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;
    }

    /* ================= INFO CARD ================= */
    .info-card{
        background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color);
        box-shadow: var(--shadow); margin-top: 24px; padding: 22px 26px;
    }
    .info-card h5{ font-weight: 700; font-size: 15px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .info-row{ display: flex; align-items: center; gap: 14px; padding: 11px 0; border-bottom: 1px solid var(--border-color); }
    .info-row:last-child{ border-bottom: none; }
    .info-row .ic{
        width: 38px; height: 38px; border-radius: 10px; background: var(--primary-light); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
    }
    .info-row .txt .lbl{ font-size: 11px; color: var(--text-muted); }
    .info-row .txt .val{ font-size: 13.5px; font-weight: 600; color: var(--text-dark); }

    /* ================= STATISTIK ================= */
    .stat-grid{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 24px; }
    @media (max-width: 768px){ .stat-grid{ grid-template-columns: repeat(2, 1fr); } }

    .stat-card{
        display: block; color: inherit;
        background: #fff; border-radius: 16px; border: 1px solid var(--border-color); box-shadow: var(--shadow);
        padding: 20px; text-align: center; transition: transform .25s ease, box-shadow .25s ease; position: relative; overflow: hidden;
    }
    .stat-card:hover{ transform: translateY(-6px); box-shadow: var(--shadow-hover); }
    .stat-card .ic{
        width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
        font-size: 21px; margin: 0 auto 10px;
    }
    .stat-card.c1 .ic{ background: var(--primary-soft); color: var(--primary-dark); }
    .stat-card.c2 .ic{ background: #ffe3da; color: var(--coral-dark); }
    .stat-card.c3 .ic{ background: #dcfce7; color: #15803d; }
    .stat-card.c4 .ic{ background: #ede9fe; color: #6d28d9; }
    .stat-card .num{ font-weight: 800; font-size: 22px; margin-bottom: 2px; }
    .stat-card .lbl{ font-size: 12px; color: var(--text-muted); font-weight: 600; }

    /* ================= MENU PROFILE ================= */
    .menu-card{
        background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color);
        box-shadow: var(--shadow); margin-top: 24px; overflow: hidden;
    }
    .menu-card h5{ font-weight: 700; font-size: 15px; padding: 20px 26px 8px; margin: 0; }
    .menu-item{
        display: flex; align-items: center; gap: 14px; padding: 15px 26px; border-top: 1px solid var(--border-color);
        transition: background .2s ease; color: var(--text-dark);
    }
    .menu-item:hover{ background: var(--primary-light); }
    .menu-item .ic{
        width: 40px; height: 40px; border-radius: 11px; background: var(--primary-light); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0;
    }
    .menu-item .txt{ flex: 1; }
    .menu-item .txt .t1{ font-size: 13.5px; font-weight: 700; }
    .menu-item .txt .t2{ font-size: 11.5px; color: var(--text-muted); }
    .menu-item .bi-chevron-right{ color: var(--text-muted); font-size: 14px; }

    .menu-item.logout{ color: #ef4444; }
    .menu-item.logout .ic{ background: #fef2f2; color: #ef4444; }
    .menu-item.logout:hover{ background: #fef2f2; }
    .menu-item-btn{
        width: 100%; border: none; background: transparent; font-family: 'Poppins', sans-serif; cursor: pointer;
    }
    form#formLogout{ margin: 0; }

    .reveal{ opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .reveal.active{ opacity: 1; transform: translateY(0); }

    @media (max-width: 576px){
        .profile-wrap{ padding: 24px 16px 0; }
        .profile-head{ padding: 0 16px; margin-top: -50px; }
        .info-card, .menu-card{ padding: 18px; }
        .menu-card h5{ padding: 16px 18px 6px; }
        .menu-item{ padding: 14px 18px; }
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
            <a href="{{ route('pembeli.pesanan') }}" class="nav-link"><i class="bi bi-receipt"></i> Pesanan</a>
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
        <a href="{{ route('pembeli.pesanan') }}" class="nav-link"><i class="bi bi-receipt"></i> Pesanan Saya</a>
        <a href="{{ route('pembeli.download') }}" class="nav-link"><i class="bi bi-cloud-arrow-down-fill"></i> Download Saya</a>
        <a href="{{ route('pembeli.profile') }}" class="nav-link active"><i class="bi bi-person-fill"></i> Profile</a>
        <a href="#" class="nav-link"><i class="bi bi-shop-window"></i> Daftar Sebagai Penjual</a>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link logout-link mobile-logout-btn"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</header>

{{-- ===================== MAIN CONTENT ===================== --}}
<main class="main-content">

    <div class="profile-wrap">

        {{-- ============= COVER + AVATAR ============= --}}
        <div class="cover-card">
            <button class="btn-edit-cover"><i class="bi bi-camera-fill"></i> Ubah Cover</button>
        </div>

        <div class="profile-head">
            <div class="profile-avatar-wrap">
                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&size=200&background=2563eb&color=ffffff" alt="Foto Profile">
                <button class="btn-edit-avatar" title="Ubah Foto"><i class="bi bi-camera-fill"></i></button>
            </div>
            <div class="profile-namebox">
                <h3>Budi Santoso</h3>
                <span class="badge-role"><i class="bi bi-patch-check-fill"></i> Pembeli Terverifikasi</span>
            </div>
        </div>

        {{-- ============= INFORMASI AKUN ============= --}}
        <div class="info-card reveal">
            <h5><i class="bi bi-person-lines-fill"></i> Informasi Akun</h5>
            <div class="info-row">
                <div class="ic"><i class="bi bi-envelope-fill"></i></div>
                <div class="txt">
                    <div class="lbl">Email</div>
                    <div class="val">budi.santoso@gmail.com</div>
                </div>
            </div>
            <div class="info-row">
                <div class="ic"><i class="bi bi-telephone-fill"></i></div>
                <div class="txt">
                    <div class="lbl">Nomor HP</div>
                    <div class="val">+62 812-3456-7890</div>
                </div>
            </div>
            <div class="info-row">
                <div class="ic"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="txt">
                    <div class="lbl">Alamat</div>
                    <div class="val">Jl. Kemang Raya No. 45, Bekasi, Jawa Barat, Indonesia</div>
                </div>
            </div>
        </div>

        {{-- ============= STATISTIK ============= --}}
        <div class="stat-grid">
            <a href="{{ route('pembeli.pesanan') }}" class="stat-card c1 reveal">
                <div class="ic"><i class="bi bi-receipt"></i></div>
                <div class="num">12</div>
                <div class="lbl">Total Pesanan</div>
            </a>
            <a href="{{ route('pembeli.wishlist') }}" class="stat-card c2 reveal">
                <div class="ic"><i class="bi bi-heart-fill"></i></div>
                <div class="num">5</div>
                <div class="lbl">Wishlist</div>
            </a>
            <a href="{{ route('pembeli.keranjang') }}" class="stat-card c3 reveal">
                <div class="ic"><i class="bi bi-cart-fill"></i></div>
                <div class="num">3</div>
                <div class="lbl">Keranjang</div>
            </a>
            <a href="{{ route('pembeli.download') }}" class="stat-card c4 reveal">
                <div class="ic"><i class="bi bi-cloud-arrow-down-fill"></i></div>
                <div class="num">8</div>
                <div class="lbl">Download</div>
            </a>
        </div>

        {{-- ============= MENU PROFILE ============= --}}
        <div class="menu-card reveal">
            <h5><i class="bi bi-gear-fill"></i> Pengaturan Akun</h5>

            <a href="#" class="menu-item">
                <div class="ic"><i class="bi bi-pencil-square"></i></div>
                <div class="txt">
                    <div class="t1">Edit Profil</div>
                    <div class="t2">Ubah nama, email, dan foto profil</div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="menu-item">
                <div class="ic"><i class="bi bi-shield-lock-fill"></i></div>
                <div class="txt">
                    <div class="t1">Ubah Password</div>
                    <div class="t2">Perbarui kata sandi akunmu secara berkala</div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="menu-item">
                <div class="ic"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="txt">
                    <div class="t1">Alamat Saya</div>
                    <div class="t2">Kelola daftar alamat pengiriman</div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="menu-item">
                <div class="ic"><i class="bi bi-credit-card-fill"></i></div>
                <div class="txt">
                    <div class="t1">Metode Pembayaran</div>
                    <div class="t2">Kelola kartu dan metode pembayaran</div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="menu-item">
                <div class="ic"><i class="bi bi-sliders"></i></div>
                <div class="txt">
                    <div class="t1">Pengaturan Akun</div>
                    <div class="t2">Notifikasi, privasi, dan preferensi lain</div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <form action="{{ route('logout') }}" method="POST" id="formLogout">
                @csrf
                <button type="submit" class="menu-item logout menu-item-btn" id="btnLogout">
                    <div class="ic"><i class="bi bi-box-arrow-right"></i></div>
                    <div class="txt">
                        <div class="t1">Logout</div>
                        <div class="t2">Keluar dari akun Karyaku kamu</div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </form>
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

    // ---- Konfirmasi logout ----
    const formLogout = document.getElementById('formLogout');
    if (formLogout) {
        formLogout.addEventListener('submit', (e) => {
            if (!confirm('Yakin ingin keluar dari akun Karyaku?')) {
                e.preventDefault();
            }
        });
    }

    // ---- Ganti foto profil (preview lokal) ----
    const btnEditAvatar = document.querySelector('.btn-edit-avatar');
    if (btnEditAvatar) {
        btnEditAvatar.addEventListener('click', () => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        document.querySelector('.profile-avatar-wrap img').src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };
            fileInput.click();
        });
    }
</script>
</body>
</html>
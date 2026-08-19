<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profile Saya - Karyaku</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>

:root{
    --primary:#2563eb;
    --primary-dark:#1e3a8a;
    --primary-darker:#14225c;
    --primary-light:#eff6ff;
    --primary-soft:#dbeafe;

    --coral:#ff7a59;
    --coral-dark:#f0623f;

    --white:#ffffff;

    --text-dark:#1e293b;
    --text-muted:#64748b;

    --border-color:#e5edff;

    --radius:18px;

    --shadow:0 8px 24px rgba(37,99,235,.08);
    --shadow-hover:0 16px 34px rgba(37,99,235,.16);
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:var(--primary-light);
    color:var(--text-dark);
    overflow-x:hidden;
}

a{
    text-decoration:none;
}


/* =====================================================
   BACKGROUND
===================================================== */

.bg-decor{
    position:fixed;
    inset:0;
    z-index:-1;
    overflow:hidden;
    pointer-events:none;
}

.bg-decor span{
    position:absolute;
    border-radius:50%;
    background:
        radial-gradient(
            circle at 30% 30%,
            var(--primary-soft),
            transparent 70%
        );

    opacity:.5;
    animation:floatBlob 14s ease-in-out infinite;
}

.bg-decor span:nth-child(1){
    width:380px;
    height:380px;
    top:-120px;
    right:-100px;
}

.bg-decor span:nth-child(2){
    width:260px;
    height:260px;
    bottom:-80px;
    left:-60px;
    animation-delay:2s;
}

@keyframes floatBlob{

    0%,100%{
        transform:translate(0,0) scale(1);
    }

    50%{
        transform:translate(20px,-30px) scale(1.08);
    }

}


/* =====================================================
   NAVBAR
===================================================== */

.site-navbar{

    background:
        linear-gradient(
            120deg,
            var(--primary-darker),
            var(--primary-dark) 60%,
            var(--primary)
        );

    position:sticky;
    top:0;

    z-index:1030;

    box-shadow:
        0 10px 30px rgba(20,34,92,.18);
}

.navbar-top{

    display:flex;
    align-items:center;

    gap:18px;

    padding:12px 28px;

    max-width:1440px;

    margin:auto;
}


/* BRAND */

.brand{

    display:flex;
    align-items:center;

    gap:10px;

    flex-shrink:0;
}

.brand-icon{

    width:40px;
    height:40px;

    background:white;

    color:var(--primary);

    border-radius:11px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:19px;
}

.brand-text h5{

    margin:0;

    font-weight:700;

    font-size:15.5px;

    color:white;
}

.brand-text small{

    color:rgba(255,255,255,.6);

    font-size:10.5px;
}


/* MOBILE BUTTON */

.mobile-toggle{

    width:40px;
    height:40px;

    border-radius:10px;

    background:rgba(255,255,255,.12);

    border:none;

    color:white;

    display:none;

    align-items:center;
    justify-content:center;
}


/* NAV MENU */

.nav-menu{

    display:flex;

    align-items:center;

    gap:2px;

    flex:1;
}

.nav-menu .nav-link{

    position:relative;

    display:flex;

    align-items:center;

    gap:8px;

    color:rgba(255,255,255,.78);

    padding:9px 14px;

    border-radius:10px;

    font-size:13.5px;

    font-weight:500;

    white-space:nowrap;

    transition:.2s;
}

.nav-menu .nav-link:hover{

    background:rgba(255,255,255,.1);

    color:white;
}

.nav-menu .nav-link.active{

    background:rgba(255,255,255,.16);

    color:white;

    font-weight:600;
}

.nav-menu .nav-link.active::after{

    content:"";

    position:absolute;

    left:14px;
    right:14px;

    bottom:-1px;

    height:2.5px;

    background:var(--coral);

    border-radius:4px;
}


/* BADGE */

.badge-count{

    background:var(--coral);

    color:white;

    font-size:10px;

    font-weight:700;

    min-width:17px;

    height:17px;

    border-radius:20px;

    display:flex;

    align-items:center;
    justify-content:center;

    padding:0 4px;
}


/* RIGHT */

.navbar-right{

    display:flex;

    align-items:center;

    gap:10px;

    flex-shrink:0;
}


/* SELL BUTTON */

.btn-jual{

    display:inline-flex;

    align-items:center;

    gap:8px;

    background:var(--coral);

    color:white;

    border:none;

    padding:10px 18px;

    border-radius:10px;

    font-weight:700;

    font-size:13px;

    transition:.2s;
}

.btn-jual:hover{

    background:var(--coral-dark);

    color:white;

    transform:translateY(-2px);
}


/* NOTIFICATION */

.icon-btn-light{

    width:40px;
    height:40px;

    border-radius:12px;

    background:rgba(255,255,255,.12);

    border:none;

    color:white;

    position:relative;

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:17px;
}

.icon-btn-light:hover{

    background:rgba(255,255,255,.22);
}

.icon-btn-light .dot{

    position:absolute;

    top:4px;
    right:4px;

    min-width:16px;

    height:16px;

    background:var(--coral);

    border-radius:20px;

    border:2px solid var(--primary-dark);

    font-size:9px;

    display:flex;

    align-items:center;
    justify-content:center;
}


/* =====================================================
   USER MENU
===================================================== */

.user-menu{

    position:relative;

    flex-shrink:0;
}

.user-chip{

    display:flex;

    align-items:center;

    gap:9px;

    background:rgba(255,255,255,.12);

    padding:5px 12px 5px 5px;

    border-radius:30px;

    border:none;

    cursor:pointer;

    transition:.2s;
}

.user-chip:hover{

    background:rgba(255,255,255,.2);
}

.user-chip img{

    width:30px;
    height:30px;

    border-radius:50%;

    object-fit:cover;

    background:white;
}

.user-chip .name{

    font-size:12.5px;

    font-weight:600;

    color:white;

    line-height:1.1;
}

.user-chip .role{

    font-size:10.5px;

    color:rgba(255,255,255,.65);
}

.user-chip .bi-chevron-down{

    font-size:11px;

    color:rgba(255,255,255,.7);

    transition:.2s;
}

.user-menu.open .bi-chevron-down{

    transform:rotate(180deg);
}


/* DROPDOWN */

.user-dropdown{

    position:absolute;

    right:0;

    top:calc(100% + 10px);

    width:230px;

    background:white;

    border-radius:14px;

    box-shadow:var(--shadow-hover);

    padding:8px;

    opacity:0;

    visibility:hidden;

    transform:translateY(-8px);

    transition:.18s;
}

.user-menu.open .user-dropdown{

    opacity:1;

    visibility:visible;

    transform:translateY(0);
}

.user-dropdown a{

    display:flex;

    align-items:center;

    gap:10px;

    padding:10px 12px;

    border-radius:10px;

    font-size:13px;

    font-weight:500;

    color:var(--text-dark);
}

.user-dropdown a:hover{

    background:var(--primary-light);

    color:var(--primary-dark);
}

.user-dropdown hr{

    border-color:var(--border-color);

    margin:6px 4px;
}

.dropdown-logout-btn{

    display:flex;

    align-items:center;

    gap:10px;

    width:100%;

    padding:10px 12px;

    border:none;

    background:transparent;

    border-radius:10px;

    font-family:'Poppins';

    font-size:13px;

    cursor:pointer;
}

.dropdown-logout-btn:hover{

    background:#fef2f2;
}


/* =====================================================
   MOBILE MENU
===================================================== */

.mobile-menu-panel{

    display:none;

    max-height:0;

    overflow:hidden;

    background:var(--primary-darker);

    transition:.28s;
}

.mobile-menu-panel.show{

    max-height:700px;
}

.mobile-menu-panel .nav-link{

    display:flex;

    align-items:center;

    gap:12px;

    color:rgba(255,255,255,.82);

    padding:13px 22px;

    border-top:1px solid rgba(255,255,255,.08);

    font-size:14px;
}

.mobile-menu-panel .nav-link.active{

    color:white;

    background:rgba(255,255,255,.08);
}

.mobile-menu-panel .badge-count{

    margin-left:auto;
}


/* =====================================================
   PROFILE
===================================================== */

.profile-wrap{

    max-width:1140px;

    margin:auto;

    padding:30px 28px 60px;
}


/* COVER */

.cover-card{

    position:relative;

    height:190px;

    border-radius:var(--radius);

    overflow:hidden;

    background:
        linear-gradient(
            120deg,
            var(--primary-darker),
            var(--primary-dark) 55%,
            var(--primary)
        );

    box-shadow:var(--shadow);
}

.cover-card::after{

    content:"";

    position:absolute;

    width:300px;
    height:300px;

    border-radius:50%;

    background:rgba(255,122,89,.16);

    right:-70px;

    top:-80px;
}

.cover-card::before{

    content:"";

    position:absolute;

    width:200px;
    height:200px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:30%;

    bottom:-120px;
}


/* COVER BUTTON */

.btn-edit-cover{

    position:absolute;

    top:14px;

    right:16px;

    z-index:2;

    background:rgba(255,255,255,.15);

    color:white;

    border:none;

    padding:8px 14px;

    border-radius:10px;

    font-size:12px;

    font-weight:700;
}


/* PROFILE HEADER */

.profile-head{

    display:flex;

    align-items:flex-end;

    gap:20px;

    margin-top:-58px;

    padding:0 30px;

    position:relative;

    z-index:5;
}


/* AVATAR */

.profile-avatar-wrap{

    position:relative;

    flex-shrink:0;
}

.profile-avatar{

    width:112px;

    height:112px;

    border-radius:50%;

    object-fit:cover;

    border:5px solid white;

    box-shadow:var(--shadow);

    background:white;
}

.btn-edit-avatar{

    position:absolute;

    bottom:4px;

    right:4px;

    width:32px;

    height:32px;

    border-radius:50%;

    background:var(--coral);

    color:white;

    border:3px solid white;

    display:flex;

    align-items:center;
    justify-content:center;

    cursor:pointer;
}


/* PROFILE NAME */

.profile-namebox{

    padding-bottom:10px;

    flex:1;
}

.profile-namebox h3{

    margin:0 0 5px;

    font-size:21px;

    font-weight:800;
}

.profile-namebox p{

    margin:0;

    color:var(--text-muted);

    font-size:12px;
}

.badge-role{

    display:inline-flex;

    align-items:center;

    gap:5px;

    margin-top:7px;

    padding:4px 12px;

    border-radius:20px;

    background:var(--primary-soft);

    color:var(--primary-dark);

    font-size:11px;

    font-weight:700;
}


/* =====================================================
   INFO CARD
===================================================== */

.info-card{

    background:white;

    border:1px solid var(--border-color);

    border-radius:var(--radius);

    box-shadow:var(--shadow);

    margin-top:24px;

    padding:22px 26px;
}

.info-card h5{

    font-size:15px;

    font-weight:700;

    margin-bottom:15px;

    display:flex;

    align-items:center;

    gap:8px;
}

.info-row{

    display:flex;

    align-items:center;

    gap:14px;

    padding:11px 0;

    border-bottom:1px solid var(--border-color);
}

.info-row:last-child{

    border-bottom:none;
}

.info-row .ic{

    width:38px;

    height:38px;

    border-radius:10px;

    background:var(--primary-light);

    color:var(--primary);

    display:flex;

    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.info-row .lbl{

    font-size:11px;

    color:var(--text-muted);
}

.info-row .val{

    font-size:13.5px;

    font-weight:600;

    word-break:break-word;
}


/* =====================================================
   STATISTICS
===================================================== */

.stat-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:16px;

    margin-top:24px;
}

.stat-card{

    background:white;

    border:1px solid var(--border-color);

    border-radius:16px;

    box-shadow:var(--shadow);

    padding:20px;

    text-align:center;

    color:var(--text-dark);

    transition:.25s;
}

.stat-card:hover{

    transform:translateY(-5px);

    box-shadow:var(--shadow-hover);
}

.stat-card .ic{

    width:48px;

    height:48px;

    border-radius:14px;

    margin:auto auto 10px;

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:20px;
}

.stat-card.c1 .ic{

    background:var(--primary-soft);

    color:var(--primary-dark);
}

.stat-card.c2 .ic{

    background:#ffe3da;

    color:var(--coral-dark);
}

.stat-card.c3 .ic{

    background:#dcfce7;

    color:#15803d;
}

.stat-card.c4 .ic{

    background:#ede9fe;

    color:#6d28d9;
}

.stat-card .num{

    font-size:22px;

    font-weight:800;
}

.stat-card .lbl{

    font-size:12px;

    color:var(--text-muted);

    font-weight:600;
}


/* =====================================================
   MENU SETTINGS
===================================================== */

.menu-card{

    background:white;

    border:1px solid var(--border-color);

    border-radius:var(--radius);

    box-shadow:var(--shadow);

    margin-top:24px;

    overflow:hidden;
}

.menu-card h5{

    margin:0;

    padding:20px 26px 10px;

    font-size:15px;

    font-weight:700;
}

.menu-item{

    display:flex;

    align-items:center;

    gap:14px;

    padding:15px 26px;

    border-top:1px solid var(--border-color);

    color:var(--text-dark);

    transition:.2s;
}

.menu-item:hover{

    background:var(--primary-light);
}

.menu-item .ic{

    width:40px;

    height:40px;

    border-radius:11px;

    background:var(--primary-light);

    color:var(--primary);

    display:flex;

    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.menu-item .txt{

    flex:1;
}

.menu-item .t1{

    font-size:13.5px;

    font-weight:700;
}

.menu-item .t2{

    font-size:11.5px;

    color:var(--text-muted);
}

.menu-item.logout{

    color:#ef4444;

    background:transparent;
}

.menu-item.logout:hover{

    background:#fef2f2;
}

.menu-item-btn{

    width:100%;

    border:none;

    font-family:'Poppins';

    text-align:left;

    cursor:pointer;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:992px){

    .mobile-toggle{

        display:flex;
    }

    .nav-menu{

        display:none;
    }

    .mobile-menu-panel{

        display:block;
    }

    .btn-jual span{

        display:none;
    }

    .user-chip .user-info{

        display:none!important;
    }
}

@media(max-width:768px){

    .stat-grid{

        grid-template-columns:repeat(2,1fr);
    }

    .profile-head{

        padding:0 16px;

        margin-top:-50px;
    }

}

@media(max-width:576px){

    .navbar-top{

        padding:10px 16px;

        gap:10px;
    }

    .profile-wrap{

        padding:24px 16px 50px;
    }

    .profile-head{

        align-items:center;

        flex-direction:column;

        text-align:center;
    }

    .profile-namebox{

        padding-bottom:0;
    }

    .info-card{

        padding:18px;
    }

    .menu-card h5{

        padding:16px 18px 8px;
    }

    .menu-item{

        padding:14px 18px;
    }
}

::-webkit-scrollbar{

    width:8px;
}

::-webkit-scrollbar-thumb{

    background:var(--primary-soft);

    border-radius:10px;
}

</style>

</head>

<body>

<div class="bg-decor">
    <span></span>
    <span></span>
</div>


{{-- =====================================================
     NAVBAR
===================================================== --}}

<header class="site-navbar">

    <div class="navbar-top">

        {{-- MOBILE --}}
        <button
            class="mobile-toggle"
            id="btnToggleMenu"
            type="button"
            aria-label="Buka menu"
            aria-expanded="false">

            <i class="bi bi-list fs-5"></i>

        </button>


        {{-- BRAND --}}

        <a
            href="{{ route('pembeli.dashboard') }}"
            class="brand">

            <div class="brand-icon">

                <i class="bi bi-bag-check-fill"></i>

            </div>

            <div class="brand-text d-none d-sm-block">

                <h5>Karyaku</h5>

                <small>Marketplace Pembeli</small>

            </div>

        </a>


        {{-- NAV MENU --}}

        <nav class="nav-menu">

            <a
                href="{{ route('pembeli.dashboard') }}"
                class="nav-link">

                <i class="bi bi-grid-1x2-fill"></i>

                Dashboard

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="nav-link">

                <i class="bi bi-shop"></i>

                Marketplace

            </a>


            <a
                href="{{ route('pembeli.wishlist') }}"
                class="nav-link">

                <i class="bi bi-heart-fill"></i>

                Wishlist

            </a>


            <a
                href="{{ route('pembeli.keranjang') }}"
                class="nav-link">

                <i class="bi bi-cart-fill"></i>

                Keranjang

            </a>


            <a
                href="{{ route('pembeli.pesanan') }}"
                class="nav-link">

                <i class="bi bi-receipt"></i>

                Pesanan

            </a>


            <a
                href="{{ route('pembeli.download') }}"
                class="nav-link">

                <i class="bi bi-cloud-arrow-down-fill"></i>

                Download

            </a>

        </nav>


        {{-- RIGHT --}}

        <div class="navbar-right">


            {{-- JADI PENJUAL --}}

            <a
                href="#"
                class="btn-jual d-none d-md-inline-flex">

                <i class="bi bi-shop-window"></i>

                <span>Daftar Sebagai Penjual</span>

            </a>


            {{-- NOTIFICATION --}}

            <button
                type="button"
                class="icon-btn-light"
                title="Notifikasi">

                <i class="bi bi-bell"></i>

                <span class="dot">2</span>

            </button>


            {{-- USER --}}

            <div
                class="user-menu"
                id="userMenu">


                <button
                    type="button"
                    class="user-chip"
                    id="btnUserChip">


                    {{-- AVATAR USER LOGIN --}}

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pembeli') }}&background=ffffff&color=1e3a8a"
                        alt="Avatar">


                    <div class="user-info d-none d-lg-block">

                        <div class="name">

                            {{ auth()->user()->name ?? 'Pembeli' }}

                        </div>

                        <div class="role">

                            Pembeli

                        </div>

                    </div>


                    <i class="bi bi-chevron-down"></i>

                </button>


                {{-- USER DROPDOWN --}}

                <div class="user-dropdown">


                    <a href="{{ route('pembeli.profile') }}">

                        <i class="bi bi-person-fill"></i>

                        Profile

                    </a>


                    <a href="{{ route('pembeli.pesanan') }}">

                        <i class="bi bi-receipt"></i>

                        Pesanan Saya

                    </a>


                    <a href="{{ route('pembeli.download') }}">

                        <i class="bi bi-cloud-arrow-down-fill"></i>

                        Download Saya

                    </a>


                    <hr>


                    <form
                        action="{{ route('logout') }}"
                        method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-logout-btn text-danger">

                            <i class="bi bi-box-arrow-right"></i>

                            Keluar

                        </button>

                    </form>


                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MOBILE MENU
    ===================================================== --}}

    <div
        class="mobile-menu-panel"
        id="mobileMenuPanel">


        <a
            href="{{ route('pembeli.dashboard') }}"
            class="nav-link">

            <i class="bi bi-grid-1x2-fill"></i>

            Dashboard

        </a>


        <a
            href="{{ route('pembeli.marketplace') }}"
            class="nav-link">

            <i class="bi bi-shop"></i>

            Marketplace

        </a>


        <a
            href="{{ route('pembeli.wishlist') }}"
            class="nav-link">

            <i class="bi bi-heart-fill"></i>

            Wishlist

        </a>


        <a
            href="{{ route('pembeli.keranjang') }}"
            class="nav-link">

            <i class="bi bi-cart-fill"></i>

            Keranjang

        </a>


        <a
            href="{{ route('pembeli.pesanan') }}"
            class="nav-link">

            <i class="bi bi-receipt"></i>

            Pesanan Saya

        </a>


        <a
            href="{{ route('pembeli.download') }}"
            class="nav-link">

            <i class="bi bi-cloud-arrow-down-fill"></i>

            Download Saya

        </a>


        <a
            href="{{ route('pembeli.profile') }}"
            class="nav-link active">

            <i class="bi bi-person-fill"></i>

            Profile

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-shop-window"></i>

            Daftar Sebagai Penjual

        </a>


        <form
            action="{{ route('logout') }}"
            method="POST">

            @csrf

            <button
                type="submit"
                class="nav-link"
                style="
                    width:100%;
                    border:none;
                    background:transparent;
                    color:#fecaca;
                    text-align:left;
                ">

                <i class="bi bi-box-arrow-right"></i>

                Keluar

            </button>

        </form>


    </div>

</header>


{{-- =====================================================
     MAIN
===================================================== --}}

<main>

<div class="profile-wrap">


    {{-- =================================================
         COVER
    ================================================= --}}

    <div class="cover-card">


        <button
            type="button"
            class="btn-edit-cover">

            <i class="bi bi-camera-fill"></i>

            Ubah Cover

        </button>


    </div>


    {{-- =================================================
         PROFILE HEADER
    ================================================= --}}

    <div class="profile-head">


        <div class="profile-avatar-wrap">


            <img
                id="profileAvatar"
                class="profile-avatar"
                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pembeli') }}&background=dbeafe&color=1e3a8a"
                alt="Foto Profil">


            <button
                type="button"
                class="btn-edit-avatar"
                id="btnEditAvatar">

                <i class="bi bi-camera-fill"></i>

            </button>


        </div>


        <div class="profile-namebox">


            <h3>

                {{ auth()->user()->name ?? 'Pembeli' }}

            </h3>


            <p>

                {{ auth()->user()->email ?? 'Email belum tersedia' }}

            </p>


            <span class="badge-role">

                <i class="bi bi-person-check-fill"></i>

                Pembeli

            </span>


        </div>


    </div>


    {{-- =================================================
         INFORMASI AKUN
    ================================================= --}}

    <div class="info-card">


        <h5>

            <i class="bi bi-person-lines-fill"></i>

            Informasi Akun

        </h5>


        {{-- EMAIL --}}

        <div class="info-row">

            <div class="ic">

                <i class="bi bi-envelope-fill"></i>

            </div>

            <div>

                <div class="lbl">
                    Email
                </div>

                <div class="val">

                    {{ auth()->user()->email ?? '-' }}

                </div>

            </div>

        </div>


        {{-- PHONE --}}

        <div class="info-row">

            <div class="ic">

                <i class="bi bi-telephone-fill"></i>

            </div>

            <div>

                <div class="lbl">
                    Nomor HP
                </div>

                <div class="val">

                    {{ auth()->user()->phone ?? auth()->user()->no_hp ?? '-' }}

                </div>

            </div>

        </div>


        {{-- ADDRESS --}}

        <div class="info-row">

            <div class="ic">

                <i class="bi bi-geo-alt-fill"></i>

            </div>

            <div>

                <div class="lbl">
                    Alamat
                </div>

                <div class="val">

                    {{ auth()->user()->address ?? auth()->user()->alamat ?? 'Alamat belum diisi' }}

                </div>

            </div>

        </div>


    </div>


    {{-- =================================================
         STATISTIK
    ================================================= --}}

    <div class="stat-grid">


        <a
            href="{{ route('pembeli.pesanan') }}"
            class="stat-card c1">

            <div class="ic">

                <i class="bi bi-receipt"></i>

            </div>

            <div class="num">

                12

            </div>

            <div class="lbl">

                Total Pesanan

            </div>

        </a>


        <a
            href="{{ route('pembeli.wishlist') }}"
            class="stat-card c2">

            <div class="ic">

                <i class="bi bi-heart-fill"></i>

            </div>

            <div class="num">

                5

            </div>

            <div class="lbl">

                Wishlist

            </div>

        </a>


        <a
            href="{{ route('pembeli.keranjang') }}"
            class="stat-card c3">

            <div class="ic">

                <i class="bi bi-cart-fill"></i>

            </div>

            <div class="num">

                3

            </div>

            <div class="lbl">

                Keranjang

            </div>

        </a>


        <a
            href="{{ route('pembeli.download') }}"
            class="stat-card c4">

            <div class="ic">

                <i class="bi bi-cloud-arrow-down-fill"></i>

            </div>

            <div class="num">

                8

            </div>

            <div class="lbl">

                Download

            </div>

        </a>


    </div>


    {{-- =================================================
         PENGATURAN
    ================================================= --}}

    <div class="menu-card">


        <h5>

            <i class="bi bi-gear-fill"></i>

            Pengaturan Akun

        </h5>


        <a
            href="{{ route('pembeli.profile') }}"
            class="menu-item">

            <div class="ic">

                <i class="bi bi-pencil-square"></i>

            </div>

            <div class="txt">

                <div class="t1">
                    Edit Profil
                </div>

                <div class="t2">
                    Ubah informasi profil akunmu
                </div>

            </div>

            <i class="bi bi-chevron-right"></i>

        </a>


        <a
            href="{{ route('pembeli.profile') }}"
            class="menu-item">

            <div class="ic">

                <i class="bi bi-shield-lock-fill"></i>

            </div>

            <div class="txt">

                <div class="t1">
                    Keamanan Akun
                </div>

                <div class="t2">
                    Kelola keamanan akunmu
                </div>

            </div>

            <i class="bi bi-chevron-right"></i>

        </a>


        <a
            href="{{ route('pembeli.profile') }}"
            class="menu-item">

            <div class="ic">

                <i class="bi bi-geo-alt-fill"></i>

            </div>

            <div class="txt">

                <div class="t1">
                    Alamat Saya
                </div>

                <div class="t2">
                    Kelola alamat pengiriman
                </div>

            </div>

            <i class="bi bi-chevron-right"></i>

        </a>


        <a
            href="{{ route('pembeli.profile') }}"
            class="menu-item">

            <div class="ic">

                <i class="bi bi-sliders"></i>

            </div>

            <div class="txt">

                <div class="t1">
                    Pengaturan
                </div>

                <div class="t2">
                    Kelola preferensi akun
                </div>

            </div>

            <i class="bi bi-chevron-right"></i>

        </a>


        {{-- LOGOUT --}}

        <form
            action="{{ route('logout') }}"
            method="POST"
            id="formLogout">

            @csrf

            <button
                type="submit"
                class="menu-item menu-item-btn logout">

                <div class="ic">

                    <i class="bi bi-box-arrow-right"></i>

                </div>

                <div class="txt">

                    <div class="t1">
                        Logout
                    </div>

                    <div class="t2">
                        Keluar dari akun Karyaku
                    </div>

                </div>

                <i class="bi bi-chevron-right"></i>

            </button>

        </form>


    </div>


</div>

</main>


<script>


/* =====================================================
   MOBILE MENU
===================================================== */

const btnToggleMenu =
    document.getElementById('btnToggleMenu');

const mobileMenuPanel =
    document.getElementById('mobileMenuPanel');


if(btnToggleMenu && mobileMenuPanel){

    btnToggleMenu.addEventListener('click',function(){

        const isOpen =
            mobileMenuPanel.classList.toggle('show');

        btnToggleMenu.setAttribute(
            'aria-expanded',
            isOpen
        );

        btnToggleMenu
            .querySelector('i')
            .className =
            isOpen
            ? 'bi bi-x-lg fs-5'
            : 'bi bi-list fs-5';

    });

}


/* =====================================================
   USER DROPDOWN
===================================================== */

const userMenu =
    document.getElementById('userMenu');

const btnUserChip =
    document.getElementById('btnUserChip');


if(userMenu && btnUserChip){

    btnUserChip.addEventListener(
        'click',
        function(e){

            e.stopPropagation();

            userMenu.classList.toggle('open');

        }
    );


    document.addEventListener(
        'click',
        function(e){

            if(!userMenu.contains(e.target)){

                userMenu.classList.remove('open');

            }

        }
    );


    document.addEventListener(
        'keydown',
        function(e){

            if(e.key === 'Escape'){

                userMenu.classList.remove('open');

            }

        }
    );

}


/* =====================================================
   EDIT AVATAR PREVIEW
===================================================== */

const btnEditAvatar =
    document.getElementById('btnEditAvatar');

const profileAvatar =
    document.getElementById('profileAvatar');


if(btnEditAvatar && profileAvatar){

    btnEditAvatar.addEventListener(
        'click',
        function(){

            const input =
                document.createElement('input');

            input.type = 'file';

            input.accept = 'image/*';


            input.addEventListener(
                'change',
                function(e){

                    const file =
                        e.target.files[0];

                    if(!file) return;


                    const reader =
                        new FileReader();


                    reader.onload =
                        function(event){

                            profileAvatar.src =
                                event.target.result;

                        };


                    reader.readAsDataURL(file);

                }
            );


            input.click();

        }
    );

}


/* =====================================================
   LOGOUT CONFIRMATION
===================================================== */

const formLogout =
    document.getElementById('formLogout');


if(formLogout){

    formLogout.addEventListener(
        'submit',
        function(e){

            const yakin =
                confirm(
                    'Yakin ingin keluar dari akun Karyaku?'
                );

            if(!yakin){

                e.preventDefault();

            }

        }
    );

}


/* =====================================================
   RESIZE MOBILE MENU
===================================================== */

window.addEventListener(
    'resize',
    function(){

        if(
            window.innerWidth > 992 &&
            mobileMenuPanel &&
            mobileMenuPanel.classList.contains('show')
        ){

            mobileMenuPanel.classList.remove('show');

            if(btnToggleMenu){

                btnToggleMenu.setAttribute(
                    'aria-expanded',
                    'false'
                );

                btnToggleMenu
                    .querySelector('i')
                    .className =
                    'bi bi-list fs-5';

            }

        }

    }
);

</script>

</body>
</html>
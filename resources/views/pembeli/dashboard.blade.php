<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Karyaku</title>

    {{-- GOOGLE FONT --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- BOOTSTRAP --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- BOOTSTRAP ICON --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


<style>

/* =====================================================
   ROOT
===================================================== */

:root {

    --primary: #2563eb;
    --primary-dark: #1e3a8a;
    --primary-darker: #14225c;

    --primary-light: #eff6ff;
    --primary-soft: #dbeafe;

    --coral: #ff7a59;
    --coral-dark: #f0623f;

    --white: #ffffff;

    --text-dark: #1e293b;
    --text-muted: #64748b;

    --border-color: #e5edff;

    --bg: #f5f8ff;

    --green: #16a34a;
    --orange: #f59e0b;
    --red: #ef4444;

    --shadow:
        0 8px 24px rgba(37, 99, 235, .08);

    --shadow-hover:
        0 16px 34px rgba(37, 99, 235, .16);
}


* {
    box-sizing: border-box;
}


body {

    margin: 0;

    font-family: 'Poppins', sans-serif;

    background: var(--bg);

    color: var(--text-dark);
}


a {
    text-decoration: none;
}


/* =====================================================
   NAVBAR
===================================================== */

.site-navbar {

    background:
        linear-gradient(
            120deg,
            var(--primary-darker),
            var(--primary-dark) 60%,
            var(--primary)
        );

    position: sticky;

    top: 0;

    z-index: 1000;

    box-shadow:
        0 10px 30px rgba(20,34,92,.18);
}


.navbar-top {

    max-width: 1450px;

    margin: auto;

    padding: 12px 28px;

    display: flex;

    align-items: center;

    gap: 18px;
}


.mobile-toggle {

    display: none;

    width: 40px;

    height: 40px;

    border: 0;

    border-radius: 10px;

    background:
        rgba(255,255,255,.12);

    color: white;
}


.brand {

    display: flex;

    align-items: center;

    gap: 10px;

    flex-shrink: 0;
}


.brand-icon {

    width: 42px;

    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: white;

    color: var(--primary);

    font-size: 19px;
}


.brand-text h5 {

    margin: 0;

    color: white;

    font-size: 15px;

    font-weight: 700;
}


.brand-text small {

    color:
        rgba(255,255,255,.6);

    font-size: 10px;
}


.nav-menu {

    display: flex;

    align-items: center;

    gap: 3px;

    flex: 1;
}


.nav-link {

    position: relative;

    display: flex;

    align-items: center;

    gap: 7px;

    color:
        rgba(255,255,255,.78);

    padding: 9px 13px;

    border-radius: 10px;

    font-size: 13px;

    font-weight: 500;

    transition: .2s;
}


.nav-link:hover,
.nav-link.active {

    color: white;

    background:
        rgba(255,255,255,.12);
}


.nav-link.active {

    font-weight: 600;
}


.badge-count {

    min-width: 18px;

    height: 18px;

    padding: 0 5px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: var(--coral);

    color: white;

    border-radius: 20px;

    font-size: 9px;

    font-weight: 700;
}


/* =====================================================
   NAVBAR RIGHT
===================================================== */

.navbar-right {

    display: flex;

    align-items: center;

    gap: 10px;
}


.btn-jual {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    background: var(--coral);

    color: white;

    padding: 10px 15px;

    border-radius: 10px;

    font-size: 12px;

    font-weight: 700;

    transition: .2s;
}


.btn-jual:hover {

    background: var(--coral-dark);

    color: white;

    transform: translateY(-2px);
}


.icon-btn-light {

    position: relative;

    width: 40px;

    height: 40px;

    border: 0;

    border-radius: 11px;

    background:
        rgba(255,255,255,.12);

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;
}


.icon-btn-light .dot {

    position: absolute;

    top: 3px;

    right: 3px;

    min-width: 16px;

    height: 16px;

    border-radius: 20px;

    background: var(--coral);

    border: 2px solid var(--primary-dark);

    font-size: 8px;

    display: flex;

    align-items: center;

    justify-content: center;
}


/* =====================================================
   USER
===================================================== */

.user-menu {

    position: relative;
}


.user-chip {

    display: flex;

    align-items: center;

    gap: 8px;

    border: 0;

    border-radius: 30px;

    background:
        rgba(255,255,255,.12);

    padding: 5px 11px 5px 5px;

    color: white;
}


.user-chip img {

    width: 30px;

    height: 30px;

    border-radius: 50%;
}


.user-name {

    font-size: 12px;

    font-weight: 600;
}


.user-role {

    font-size: 9px;

    color:
        rgba(255,255,255,.65);
}


.user-dropdown {

    position: absolute;

    right: 0;

    top: 50px;

    width: 215px;

    background: white;

    border-radius: 14px;

    box-shadow: var(--shadow-hover);

    padding: 8px;

    display: none;
}


.user-menu.open
.user-dropdown {

    display: block;
}


.user-dropdown a {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px 12px;

    color: var(--text-dark);

    border-radius: 9px;

    font-size: 13px;
}


.user-dropdown a:hover {

    background:
        var(--primary-light);
}


/* =====================================================
   SEARCH
===================================================== */

.navbar-search {

    max-width: 1450px;

    margin: auto;

    padding:
        0 28px 14px;
}


.search-combo {

    display: flex;

    overflow: hidden;

    background: white;

    border-radius: 12px;

    box-shadow:
        0 8px 22px rgba(0,0,0,.15);
}


.search-combo select {

    width: 180px;

    border: 0;

    outline: 0;

    background: #f1f5ff;

    padding: 0 12px;

    font-size: 12px;

    font-weight: 600;

    color: var(--text-dark);

    border-right:
        1px solid var(--border-color);
}


.search-combo input {

    flex: 1;

    min-width: 0;

    border: 0;

    outline: 0;

    padding: 13px 15px;

    font-size: 13px;
}


.search-combo button {

    width: 90px;

    border: 0;

    background: var(--coral);

    color: white;

    font-weight: 700;
}


.search-combo button:hover {

    background: var(--coral-dark);
}


/* =====================================================
   MOBILE MENU
===================================================== */

.mobile-menu {

    display: none;

    max-width: 1450px;

    margin: auto;

    padding: 0 16px 12px;
}


.mobile-menu.show {

    display: block;
}


.mobile-menu a {

    display: flex;

    align-items: center;

    gap: 10px;

    color: white;

    padding: 11px 13px;

    border-radius: 10px;

    font-size: 12px;
}


.mobile-menu a:hover,
.mobile-menu a.active {

    background:
        rgba(255,255,255,.12);
}


/* =====================================================
   MAIN
===================================================== */

.main-content {

    max-width: 1450px;

    margin: auto;

    padding:
        28px 28px 60px;
}


/* =====================================================
   WELCOME
===================================================== */

.welcome-card {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 18px;

    padding: 25px 28px;

    margin-bottom: 22px;

    box-shadow: var(--shadow);

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;
}


.welcome-title {

    margin: 0;

    font-size: 24px;

    font-weight: 800;
}


.welcome-title span {

    color: var(--primary);
}


.welcome-desc {

    margin: 7px 0 0;

    color: var(--text-muted);

    font-size: 12px;

    max-width: 700px;

    line-height: 1.7;
}


.profile-progress {

    margin-top: 16px;

    max-width: 400px;
}


.progress-label {

    display: flex;

    justify-content: space-between;

    font-size: 10px;

    color: var(--text-muted);

    margin-bottom: 6px;
}


.progress {

    height: 7px;

    background: #e2e8f0;

    border-radius: 20px;
}


.progress-bar {

    background: var(--primary);

    border-radius: 20px;
}


.btn-profile {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    margin-top: 8px;

    font-size: 10px;

    font-weight: 700;

    color: var(--primary);
}


.welcome-icon {

    width: 100px;

    height: 100px;

    border-radius: 25px;

    background:
        var(--primary-light);

    color: var(--primary);

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 45px;
}


/* =====================================================
   STATISTICS
===================================================== */

.stats-grid {

    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 16px;

    margin-bottom: 28px;
}


.stat-card {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 16px;

    padding: 18px;

    box-shadow: var(--shadow);

    transition: .2s;
}


.stat-card:hover {

    transform: translateY(-4px);

    box-shadow: var(--shadow-hover);
}


.stat-top {

    display: flex;

    align-items: center;

    justify-content: space-between;
}


.stat-icon {

    width: 42px;

    height: 42px;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 18px;
}


.icon-blue {

    background: var(--primary-light);

    color: var(--primary);
}


.icon-green {

    background: #ecfdf5;

    color: var(--green);
}


.icon-orange {

    background: #fff7ed;

    color: var(--orange);
}


.icon-red {

    background: #fef2f2;

    color: var(--red);
}


.stat-number {

    margin-top: 16px;

    font-size: 26px;

    font-weight: 800;
}


.stat-label {

    margin-top: 2px;

    color: var(--text-muted);

    font-size: 10px;
}


.stat-link {

    display: inline-flex;

    align-items: center;

    gap: 4px;

    margin-top: 10px;

    color: var(--primary);

    font-size: 9px;

    font-weight: 700;
}


/* =====================================================
   SECTION
===================================================== */

.section {

    margin-bottom: 30px;
}


.section-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 15px;
}


.section-title {

    margin: 0;

    font-size: 19px;

    font-weight: 800;
}


.section-subtitle {

    margin: 4px 0 0;

    color: var(--text-muted);

    font-size: 11px;
}


.see-all {

    color: var(--primary);

    font-size: 11px;

    font-weight: 700;
}


/* =====================================================
   CATEGORY
===================================================== */

.category-grid {

    display: grid;

    grid-template-columns:
        repeat(8,1fr);

    gap: 12px;
}


.category-card {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 14px;

    padding: 15px 8px;

    text-align: center;

    color: var(--text-dark);

    transition: .2s;

    box-shadow: var(--shadow);
}


.category-card:hover {

    color: var(--primary);

    transform: translateY(-4px);

    box-shadow: var(--shadow-hover);
}


.category-icon {

    width: 43px;

    height: 43px;

    margin: auto auto 8px;

    border-radius: 12px;

    background:
        var(--primary-light);

    color: var(--primary);

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 18px;
}


.category-card span {

    font-size: 9px;

    font-weight: 600;
}


/* =====================================================
   CONTENT LAYOUT
===================================================== */

.content-layout {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        300px;

    gap: 20px;

    align-items: start;
}


/* =====================================================
   PRODUCT GRID
===================================================== */

.product-grid {

    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 15px;
}


.product-card {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 15px;

    overflow: hidden;

    box-shadow: var(--shadow);

    transition: .25s;
}


.product-card:hover {

    transform: translateY(-5px);

    box-shadow: var(--shadow-hover);
}


.product-thumb {

    height: 155px;

    position: relative;

    overflow: hidden;

    background: #eaf1ff;
}


.product-thumb img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    transition: .4s;
}


.product-card:hover
.product-thumb img {

    transform: scale(1.06);
}


.cat-badge {

    position: absolute;

    top: 9px;

    left: 9px;

    padding: 4px 9px;

    background:
        rgba(20,34,92,.85);

    color: white;

    border-radius: 20px;

    font-size: 8px;

    font-weight: 700;

    z-index: 2;
}


.wish-btn {

    position: absolute;

    top: 8px;

    right: 8px;

    width: 31px;

    height: 31px;

    border: 0;

    border-radius: 50%;

    background:
        rgba(255,255,255,.94);

    color: #64748b;

    z-index: 3;
}


.wish-btn:hover {

    color: var(--coral);
}


.product-body {

    padding: 12px;
}


.product-body h6 {

    margin: 0;

    min-height: 36px;

    font-size: 11px;

    line-height: 1.5;

    font-weight: 600;
}


.product-price {

    margin-top: 5px;

    color: var(--coral);

    font-size: 14px;

    font-weight: 800;
}


.product-meta {

    display: flex;

    justify-content: space-between;

    margin-top: 5px;

    color: var(--text-muted);

    font-size: 8px;
}


.rating {

    color: #f59e0b;

    font-weight: 700;
}


.product-seller {

    display: flex;

    align-items: center;

    gap: 6px;

    margin-top: 8px;

    color: var(--text-muted);

    font-size: 8px;
}


.product-seller img {

    width: 20px;

    height: 20px;

    border-radius: 50%;
}


.btn-add-cart {

    width: 100%;

    margin-top: 9px;

    border: 0;

    border-radius: 9px;

    background: var(--primary-light);

    color: var(--primary);

    padding: 8px;

    font-size: 9px;

    font-weight: 700;

    transition: .2s;
}


.btn-add-cart:hover {

    background: var(--primary);

    color: white;
}


/* =====================================================
   SIDEBAR
===================================================== */

.sidebar {

    display: flex;

    flex-direction: column;

    gap: 16px;
}


.sidebar-card {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 16px;

    padding: 17px;

    box-shadow: var(--shadow);
}


.sidebar-title {

    display: flex;

    align-items: center;

    gap: 7px;

    margin-bottom: 12px;

    font-size: 13px;

    font-weight: 800;
}


.quick-menu {

    list-style: none;

    padding: 0;

    margin: 0;
}


.quick-menu li {

    margin-bottom: 5px;
}


.quick-menu a {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 9px 8px;

    border-radius: 9px;

    color: var(--text-dark);

    font-size: 10px;

    transition: .2s;
}


.quick-menu a:hover {

    background:
        var(--primary-light);

    color: var(--primary);
}


.quick-left {

    display: flex;

    align-items: center;

    gap: 8px;
}


.quick-left i {

    width: 22px;

    height: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 6px;

    background:
        var(--primary-light);

    color: var(--primary);
}


.quick-badge {

    min-width: 20px;

    padding: 3px 6px;

    border-radius: 20px;

    background:
        var(--primary-soft);

    color: var(--primary);

    text-align: center;

    font-size: 8px;

    font-weight: 700;
}


/* =====================================================
   CREATOR
===================================================== */

.creator {

    display: flex;

    align-items: center;

    gap: 9px;

    padding: 8px 0;

    border-bottom:
        1px solid #eef2ff;
}


.creator:last-child {

    border-bottom: 0;
}


.creator img {

    width: 34px;

    height: 34px;

    border-radius: 50%;
}


.creator-info {

    flex: 1;
}


.creator-name {

    font-size: 10px;

    font-weight: 700;
}


.creator-sales {

    color: var(--text-muted);

    font-size: 8px;
}


.creator-rating {

    color: #f59e0b;

    font-size: 9px;

    font-weight: 700;
}


/* =====================================================
   EMPTY
===================================================== */

.empty-box {

    background: white;

    border:
        1px solid var(--border-color);

    border-radius: 16px;

    padding: 50px 20px;

    text-align: center;
}


.empty-box i {

    font-size: 45px;

    color: #94a3b8;
}


.empty-box h5 {

    margin-top: 12px;

    font-weight: 700;
}


.empty-box p {

    color: var(--text-muted);

    font-size: 11px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:1200px) {

    .nav-link {

        padding:
            9px 9px;

        font-size: 11px;
    }

    .product-grid {

        grid-template-columns:
            repeat(3,1fr);
    }

    .category-grid {

        grid-template-columns:
            repeat(4,1fr);
    }

}


@media(max-width:1000px) {

    .mobile-toggle {

        display: flex;

        align-items: center;

        justify-content: center;
    }

    .nav-menu {

        display: none;
    }

    .btn-jual {

        display: none;
    }

    .content-layout {

        grid-template-columns: 1fr;
    }

    .stats-grid {

        grid-template-columns:
            repeat(2,1fr);
    }

}


@media(max-width:700px) {

    .navbar-top {

        padding:
            10px 16px;
    }

    .navbar-search {

        padding:
            0 16px 12px;
    }

    .main-content {

        padding:
            20px 16px 50px;
    }

    .welcome-card {

        padding: 20px;

    }

    .welcome-icon {

        display: none;
    }

    .welcome-title {

        font-size: 20px;
    }

    .category-grid {

        grid-template-columns:
            repeat(4,1fr);

        gap: 8px;
    }

    .product-grid {

        grid-template-columns:
            repeat(2,1fr);

        gap: 12px;
    }

    .product-thumb {

        height: 140px;
    }

    .search-combo select {

        width: 110px;
    }

}


@media(max-width:450px) {

    .brand-text {

        display: none;
    }

    .user-name,
    .user-role {

        display: none;
    }

    .stats-grid {

        grid-template-columns: 1fr;
    }

    .category-grid {

        grid-template-columns:
            repeat(2,1fr);
    }

    .product-grid {

        grid-template-columns: 1fr;
    }

    .product-thumb {

        height: 180px;
    }

    .section-title {

        font-size: 16px;
    }

}

</style>

</head>


<body>


{{-- =====================================================
     NAVBAR
===================================================== --}}

<header class="site-navbar">


    <div class="navbar-top">


        {{-- MOBILE --}}
        <button
            class="mobile-toggle"
            id="mobileToggle"
            type="button"
        >

            <i class="bi bi-list"></i>

        </button>


        {{-- BRAND --}}
        <a
            href="{{ route('pembeli.dashboard') }}"
            class="brand"
        >

            <div class="brand-icon">

                <i class="bi bi-bag-check-fill"></i>

            </div>


            <div class="brand-text">

                <h5>Karyaku</h5>

                <small>
                    Marketplace Pembeli
                </small>

            </div>

        </a>


        {{-- DESKTOP NAV --}}
        <nav class="nav-menu">


            <a
                href="{{ route('pembeli.dashboard') }}"
                class="nav-link active"
            >

                <i class="bi bi-grid-1x2-fill"></i>

                Dashboard

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="nav-link"
            >

                <i class="bi bi-shop"></i>

                Marketplace

            </a>


            <a
                href="{{ route('pembeli.wishlist') }}"
                class="nav-link"
            >

                <i class="bi bi-heart-fill"></i>

                Wishlist

                <span class="badge-count">

                    {{ $wishlistCount ?? $totalWishlist ?? 0 }}

                </span>

            </a>


            <a
                href="{{ route('pembeli.keranjang') }}"
                class="nav-link"
            >

                <i class="bi bi-cart-fill"></i>

                Keranjang

                <span class="badge-count">

                    {{ $cartCount ?? $totalKeranjang ?? 0 }}

                </span>

            </a>


            <a
                href="{{ route('pembeli.pesanan') }}"
                class="nav-link"
            >

                <i class="bi bi-receipt"></i>

                Pesanan

            </a>


            <a
                href="{{ route('pembeli.download') }}"
                class="nav-link"
            >

                <i class="bi bi-cloud-arrow-down-fill"></i>

                Download

            </a>


        </nav>


        {{-- RIGHT --}}
        <div class="navbar-right">


            <a
                href="#"
                class="btn-jual"
            >

                <i class="bi bi-shop-window"></i>

                Daftar Penjual

            </a>


            <button
                class="icon-btn-light"
                type="button"
            >

                <i class="bi bi-bell"></i>

                <span class="dot">
                    2
                </span>

            </button>


            {{-- USER --}}
            <div
                class="user-menu"
                id="userMenu"
            >

                <button
                    class="user-chip"
                    id="userChip"
                    type="button"
                >

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Pembeli') }}&background=ffffff&color=1e3a8a"
                        alt="Avatar"
                    >


                    <div>

                        <div class="user-name">

                            {{ Auth::user()->name ?? 'Pembeli' }}

                        </div>


                        <div class="user-role">

                            Pembeli

                        </div>

                    </div>


                    <i class="bi bi-chevron-down"></i>

                </button>


                <div class="user-dropdown">


                    <a
                        href="{{ route('pembeli.profile') }}"
                    >

                        <i class="bi bi-person"></i>

                        Profile

                    </a>


                    <a
                        href="{{ route('pembeli.pesanan') }}"
                    >

                        <i class="bi bi-receipt"></i>

                        Pesanan Saya

                    </a>


                    <a
                        href="{{ route('pembeli.download') }}"
                    >

                        <i class="bi bi-download"></i>

                        Download Saya

                    </a>


                    <hr>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            style="
                                border:0;
                                background:none;
                                width:100%;
                                text-align:left;
                                padding:10px 12px;
                                border-radius:9px;
                                color:#dc2626;
                                font-size:13px;
                            "
                        >

                            <i class="bi bi-box-arrow-right"></i>

                            Keluar

                        </button>

                    </form>


                </div>

            </div>


        </div>

    </div>


    {{-- MOBILE MENU --}}
    <div
        class="mobile-menu"
        id="mobileMenu"
    >


        <a
            href="{{ route('pembeli.dashboard') }}"
            class="active"
        >

            <i class="bi bi-grid-1x2-fill"></i>

            Dashboard

        </a>


        <a
            href="{{ route('pembeli.marketplace') }}"
        >

            <i class="bi bi-shop"></i>

            Marketplace

        </a>


        <a
            href="{{ route('pembeli.wishlist') }}"
        >

            <i class="bi bi-heart-fill"></i>

            Wishlist

        </a>


        <a
            href="{{ route('pembeli.keranjang') }}"
        >

            <i class="bi bi-cart-fill"></i>

            Keranjang

        </a>


        <a
            href="{{ route('pembeli.pesanan') }}"
        >

            <i class="bi bi-receipt"></i>

            Pesanan

        </a>


        <a
            href="{{ route('pembeli.download') }}"
        >

            <i class="bi bi-cloud-arrow-down-fill"></i>

            Download

        </a>


        <a
            href="{{ route('pembeli.profile') }}"
        >

            <i class="bi bi-person-fill"></i>

            Profile

        </a>


    </div>


    {{-- SEARCH --}}
    <div class="navbar-search">


        <form
            class="search-combo"
            id="searchForm"
        >


            <select id="categoryFilter">

                <option value="">
                    Semua Kategori
                </option>

                <option value="desain">
                    Desain
                </option>

                <option value="logo">
                    Logo & Branding
                </option>

                <option value="uiux">
                    UI/UX
                </option>

                <option value="website">
                    Website
                </option>

                <option value="3d">
                    3D & Blender
                </option>

                <option value="video">
                    Video & Editing
                </option>

                <option value="ilustrasi">
                    Ilustrasi
                </option>

                <option value="sosmed">
                    Social Media
                </option>

            </select>


            <input
                type="text"
                id="searchInput"
                placeholder="Cari barang, jasa, kreator..."
                autocomplete="off"
            >


            <button type="submit">

                <i class="bi bi-search"></i>

                <span class="d-none d-sm-inline">
                    Cari
                </span>

            </button>


        </form>

    </div>

</header>



{{-- =====================================================
     MAIN CONTENT
===================================================== --}}

<main class="main-content">


    {{-- =================================================
         WELCOME
    ================================================== --}}

    <section class="welcome-card">


        <div>

            <h2 class="welcome-title">

                Halo,
                <span>
                    {{ $navUser->name ?? Auth::user()->name ?? 'Pembeli' }}
                </span>
                👋

            </h2>


            <p class="welcome-desc">

                Selamat datang kembali di Dashboard Karyaku.
                Jelajahi berbagai karya digital premium,
                kelola pesananmu, dan temukan kreator favoritmu.

            </p>


            <div class="profile-progress">

                <div class="progress-label">

                    <span>
                        Kelengkapan Profil
                    </span>

                    <strong>
                        80%
                    </strong>

                </div>


                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width:80%"
                    ></div>

                </div>


                <a
                    href="{{ route('pembeli.profile') }}"
                    class="btn-profile"
                >

                    Lengkapi Profil

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        <div class="welcome-icon">

            <i class="bi bi-bag-heart-fill"></i>

        </div>


    </section>



    {{-- =================================================
         STATISTIK
    ================================================== --}}

    <section class="stats-grid">


        {{-- PESANAN --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon icon-blue">

                    <i class="bi bi-bag-check-fill"></i>

                </div>

                <i class="bi bi-arrow-up-right text-primary"></i>

            </div>


            <div class="stat-number">

                {{ $totalPesanan ?? 0 }}

            </div>


            <div class="stat-label">

                Total Pesanan Dibuat

            </div>


            <a
                href="{{ route('pembeli.pesanan') }}"
                class="stat-link"
            >

                Lihat Pesanan

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        {{-- SELESAI --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon icon-green">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <i class="bi bi-check2 text-success"></i>

            </div>


            <div class="stat-number">

                {{ $totalSelesai ?? 0 }}

            </div>


            <div class="stat-label">

                Pesanan Selesai

            </div>


            <a
                href="{{ route('pembeli.pesanan') }}"
                class="stat-link"
            >

                Lihat Riwayat

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        {{-- BELUM BAYAR --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon icon-orange">

                    <i class="bi bi-clock-history"></i>

                </div>

                <i class="bi bi-hourglass-split text-warning"></i>

            </div>


            <div class="stat-number">

                {{ $totalBelumBayar ?? 0 }}

            </div>


            <div class="stat-label">

                Menunggu Pembayaran

            </div>


            <a
                href="{{ route('pembeli.pesanan') }}"
                class="stat-link"
            >

                Cek Sekarang

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        {{-- KERANJANG --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon icon-red">

                    <i class="bi bi-cart-fill"></i>

                </div>

                <i class="bi bi-arrow-up-right text-danger"></i>

            </div>


            <div class="stat-number">

                {{ $totalKeranjang ?? $cartCount ?? 0 }}

            </div>


            <div class="stat-label">

                Produk di Keranjang

            </div>


            <a
                href="{{ route('pembeli.keranjang') }}"
                class="stat-link"
            >

                Buka Keranjang

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


    </section>



    {{-- =================================================
         CATEGORY
    ================================================== --}}

    <section class="section">


        <div class="section-header">

            <div>

                <h3 class="section-title">

                    Jelajahi Kategori

                </h3>


                <p class="section-subtitle">

                    Temukan karya sesuai kebutuhanmu

                </p>

            </div>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="see-all"
            >

                Lihat Semua

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>


        <div class="category-grid">


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-palette"></i>

                </div>

                <span>
                    Desain
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-vector-pen"></i>

                </div>

                <span>
                    Logo & Branding
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-phone"></i>

                </div>

                <span>
                    UI/UX
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-code-slash"></i>

                </div>

                <span>
                    Website
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-box"></i>

                </div>

                <span>
                    3D & Blender
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-camera-video"></i>

                </div>

                <span>
                    Video
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-image"></i>

                </div>

                <span>
                    Ilustrasi
                </span>

            </a>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="category-card"
            >

                <div class="category-icon">

                    <i class="bi bi-share"></i>

                </div>

                <span>
                    Social Media
                </span>

            </a>


        </div>

    </section>



    {{-- =================================================
         PRODUK + SIDEBAR
    ================================================== --}}

    <section class="section">


        <div class="section-header">

            <div>

                <h3 class="section-title">

                    Rekomendasi Untukmu

                </h3>


                <p class="section-subtitle">

                    Produk dan jasa yang sedang populer

                </p>

            </div>


            <a
                href="{{ route('pembeli.marketplace') }}"
                class="see-all"
            >

                Lihat Semua

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>



        <div class="content-layout">


            {{-- PRODUCT --}}
            <div>


                <div
                    class="product-grid"
                    id="productGrid"
                >


                    {{-- PRODUK 1 --}}
                    <div
                        class="product-card"
                        data-name="Desain Poster Promosi Cafe Resto"
                        data-category="desain poster"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Poster
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80"
                                alt="Poster"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Desain Poster Promosi Cafe & Resto
                            </h6>


                            <div class="product-price">
                                Rp75.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.9
                                </span>

                                <span>
                                    Terjual 320
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Dinda+Studio"
                                    alt=""
                                >

                                Dinda Studio

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="1"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 2 --}}
                    <div
                        class="product-card"
                        data-name="Model 3D Karakter Game"
                        data-category="3d blender"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                3D
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80"
                                alt="3D"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Model 3D Karakter Game Low-Poly
                            </h6>


                            <div class="product-price">
                                Rp480.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 5.0
                                </span>

                                <span>
                                    Terjual 128
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Rangga"
                                    alt=""
                                >

                                Rangga.blend

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="2"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 3 --}}
                    <div
                        class="product-card"
                        data-name="Paket Logo Brand Identity"
                        data-category="logo branding"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Logo
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80"
                                alt="Logo"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Paket Logo & Brand Identity Kit
                            </h6>


                            <div class="product-price">
                                Rp150.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.8
                                </span>

                                <span>
                                    Terjual 210
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Kirana+Design"
                                    alt=""
                                >

                                Kirana Design

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="3"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 4 --}}
                    <div
                        class="product-card"
                        data-name="Paket Feed Instagram"
                        data-category="sosmed social media"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Social Media
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80"
                                alt="Social Media"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Paket 15 Feed & Story Instagram
                            </h6>


                            <div class="product-price">
                                Rp120.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.7
                                </span>

                                <span>
                                    Terjual 176
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Sasi+Creative"
                                    alt=""
                                >

                                Sasi Creative

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="4"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 5 --}}
                    <div
                        class="product-card"
                        data-name="Desain UI Aplikasi Mobile"
                        data-category="uiux"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                UI/UX
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80"
                                alt="UI UX"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Desain UI Aplikasi Mobile Lengkap
                            </h6>


                            <div class="product-price">
                                Rp650.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.9
                                </span>

                                <span>
                                    Terjual 84
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Nadia+UX"
                                    alt=""
                                >

                                Nadia UX

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="5"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 6 --}}
                    <div
                        class="product-card"
                        data-name="Ilustrasi Vektor Karakter"
                        data-category="ilustrasi"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Ilustrasi
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80"
                                alt="Ilustrasi"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Ilustrasi Vektor Karakter Custom
                            </h6>


                            <div class="product-price">
                                Rp95.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.8
                                </span>

                                <span>
                                    Terjual 260
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Ilma+Art"
                                    alt=""
                                >

                                Ilma Art

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="6"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 7 --}}
                    <div
                        class="product-card"
                        data-name="Desain Poster Webinar"
                        data-category="desain poster"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Poster
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=600&q=80"
                                alt="Poster Webinar"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Desain Poster Event & Webinar
                            </h6>


                            <div class="product-price">
                                Rp65.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.6
                                </span>

                                <span>
                                    Terjual 142
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=Studio+Elang"
                                    alt=""
                                >

                                Studio Elang

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="7"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>



                    {{-- PRODUK 8 --}}
                    <div
                        class="product-card"
                        data-name="Website Laravel"
                        data-category="website laravel"
                    >

                        <div class="product-thumb">

                            <span class="cat-badge">
                                Website
                            </span>


                            <button
                                class="wish-btn"
                                type="button"
                            >

                                <i class="bi bi-heart"></i>

                            </button>


                            <img
                                src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80"
                                alt="Website"
                            >

                        </div>


                        <div class="product-body">

                            <h6>
                                Jasa Pembuatan Website Laravel
                            </h6>


                            <div class="product-price">
                                Rp850.000
                            </div>


                            <div class="product-meta">

                                <span class="rating">
                                    ★ 4.9
                                </span>

                                <span>
                                    Terjual 97
                                </span>

                            </div>


                            <div class="product-seller">

                                <img
                                    src="https://ui-avatars.com/api/?name=CodeCraft"
                                    alt=""
                                >

                                CodeCraft

                            </div>


                            <button
                                class="btn-add-cart"
                                data-product="8"
                                type="button"
                            >

                                <i class="bi bi-cart-plus"></i>

                                Tambah Keranjang

                            </button>

                        </div>

                    </div>


                </div>


            </div>



            {{-- =================================================
                 SIDEBAR
            ================================================== --}}

            <aside class="sidebar">


                {{-- MENU PINTAS --}}
                <div class="sidebar-card">

                    <h4 class="sidebar-title">

                        <i class="bi bi-grid-fill text-primary"></i>

                        Menu Pintas

                    </h4>


                    <ul class="quick-menu">


                        <li>

                            <a
                                href="{{ route('pembeli.keranjang') }}"
                            >

                                <div class="quick-left">

                                    <i class="bi bi-cart3"></i>

                                    Keranjang Belanja

                                </div>


                                <span class="quick-badge">

                                    {{ $totalKeranjang ?? $cartCount ?? 0 }}

                                </span>

                            </a>

                        </li>


                        <li>

                            <a
                                href="{{ route('pembeli.wishlist') }}"
                            >

                                <div class="quick-left">

                                    <i class="bi bi-heart"></i>

                                    Daftar Keinginan

                                </div>


                                <span class="quick-badge">

                                    {{ $totalWishlist ?? $wishlistCount ?? 0 }}

                                </span>

                            </a>

                        </li>


                        <li>

                            <a
                                href="{{ route('pembeli.pesanan') }}"
                            >

                                <div class="quick-left">

                                    <i class="bi bi-receipt"></i>

                                    Pesanan Saya

                                </div>


                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </li>


                        <li>

                            <a
                                href="{{ route('pembeli.download') }}"
                            >

                                <div class="quick-left">

                                    <i class="bi bi-cloud-arrow-down"></i>

                                    File Unduhan

                                </div>


                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </li>


                        <li>

                            <a
                                href="{{ route('pembeli.profile') }}"
                            >

                                <div class="quick-left">

                                    <i class="bi bi-person"></i>

                                    Pengaturan Akun

                                </div>


                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </li>


                    </ul>

                </div>



                {{-- TOP CREATOR --}}
                <div class="sidebar-card">

                    <h4 class="sidebar-title">

                        <i class="bi bi-award-fill text-warning"></i>

                        Top Kreator

                    </h4>


                    <div class="creator">

                        <img
                            src="https://ui-avatars.com/api/?name=Dinda+Studio"
                            alt=""
                        >


                        <div class="creator-info">

                            <div class="creator-name">
                                Dinda Studio
                            </div>

                            <div class="creator-sales">
                                320+ produk terjual
                            </div>

                        </div>


                        <div class="creator-rating">
                            ★ 4.9
                        </div>

                    </div>


                    <div class="creator">

                        <img
                            src="https://ui-avatars.com/api/?name=CodeCraft"
                            alt=""
                        >


                        <div class="creator-info">

                            <div class="creator-name">
                                CodeCraft
                            </div>

                            <div class="creator-sales">
                                97+ produk terjual
                            </div>

                        </div>


                        <div class="creator-rating">
                            ★ 4.9
                        </div>

                    </div>


                    <div class="creator">

                        <img
                            src="https://ui-avatars.com/api/?name=Nadia+UX"
                            alt=""
                        >


                        <div class="creator-info">

                            <div class="creator-name">
                                Nadia UX
                            </div>

                            <div class="creator-sales">
                                84+ produk terjual
                            </div>

                        </div>


                        <div class="creator-rating">
                            ★ 4.9
                        </div>

                    </div>


                </div>



                {{-- AJAKAN MARKETPLACE --}}
                <div
                    class="sidebar-card"
                    style="
                        background:
                            linear-gradient(
                                135deg,
                                #eff6ff,
                                #dbeafe
                            );
                    "
                >

                    <h4 class="sidebar-title">

                        <i class="bi bi-shop text-primary"></i>

                        Cari Karya Baru

                    </h4>


                    <p
                        style="
                            color:#64748b;
                            font-size:10px;
                            line-height:1.7;
                        "
                    >

                        Temukan berbagai produk digital
                        dan jasa kreatif dari kreator
                        Karyaku.

                    </p>


                    <a
                        href="{{ route('pembeli.marketplace') }}"
                        class="btn btn-primary btn-sm w-100"
                        style="
                            font-size:10px;
                            border-radius:9px;
                        "
                    >

                        <i class="bi bi-shop"></i>

                        Buka Marketplace

                    </a>

                </div>


            </aside>


        </div>

    </section>


</main>



<script>

/* =====================================================
   MOBILE MENU
===================================================== */

const mobileToggle =
    document.getElementById('mobileToggle');

const mobileMenu =
    document.getElementById('mobileMenu');


if (
    mobileToggle &&
    mobileMenu
) {

    mobileToggle.addEventListener(
        'click',
        function () {

            mobileMenu.classList.toggle(
                'show'
            );

            const icon =
                mobileToggle.querySelector('i');

            if (
                mobileMenu.classList.contains('show')
            ) {

                icon.className =
                    'bi bi-x-lg';

            } else {

                icon.className =
                    'bi bi-list';

            }

        }
    );

}


/* =====================================================
   USER DROPDOWN
===================================================== */

const userMenu =
    document.getElementById('userMenu');

const userChip =
    document.getElementById('userChip');


if (
    userMenu &&
    userChip
) {

    userChip.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            userMenu.classList.toggle(
                'open'
            );

        }
    );


    document.addEventListener(
        'click',
        function (event) {

            if (
                !userMenu.contains(
                    event.target
                )
            ) {

                userMenu.classList.remove(
                    'open'
                );

            }

        }
    );

}


/* =====================================================
   SEARCH
===================================================== */

const searchForm =
    document.getElementById(
        'searchForm'
    );

const searchInput =
    document.getElementById(
        'searchInput'
    );

const categoryFilter =
    document.getElementById(
        'categoryFilter'
    );


const productCards =
    Array.from(
        document.querySelectorAll(
            '.product-card'
        )
    );


function searchProducts() {

    const keyword =
        searchInput.value
            .toLowerCase()
            .trim();


    const category =
        categoryFilter.value
            .toLowerCase()
            .trim();


    productCards.forEach(
        function (card) {

            const name =
                card.dataset.name
                    .toLowerCase();


            const cardCategory =
                card.dataset.category
                    .toLowerCase();


            const keywordMatch =
                keyword === '' ||
                name.includes(keyword) ||
                cardCategory.includes(keyword);


            const categoryMatch =
                category === '' ||
                cardCategory.includes(category);


            if (
                keywordMatch &&
                categoryMatch
            ) {

                card.style.display =
                    '';

            } else {

                card.style.display =
                    'none';

            }

        }
    );

}


if (searchForm) {

    searchForm.addEventListener(
        'submit',
        function (event) {

            event.preventDefault();

            searchProducts();

        }
    );

}


if (searchInput) {

    searchInput.addEventListener(
        'input',
        searchProducts
    );

}


if (categoryFilter) {

    categoryFilter.addEventListener(
        'change',
        searchProducts
    );

}


/* =====================================================
   WISHLIST BUTTON
===================================================== */

document
    .querySelectorAll('.wish-btn')
    .forEach(
        function (button) {

            button.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();


                    const icon =
                        this.querySelector('i');


                    this.classList.toggle(
                        'active'
                    );


                    if (
                        this.classList.contains(
                            'active'
                        )
                    ) {

                        icon.classList.remove(
                            'bi-heart'
                        );

                        icon.classList.add(
                            'bi-heart-fill'
                        );

                        this.style.color =
                            '#ff7a59';

                    } else {

                        icon.classList.remove(
                            'bi-heart-fill'
                        );

                        icon.classList.add(
                            'bi-heart'
                        );

                        this.style.color =
                            '';

                    }

                }
            );

        }
    );


/* =====================================================
   ADD CART
===================================================== */

document
    .querySelectorAll('.btn-add-cart')
    .forEach(
        function (button) {

            button.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();


                    const original =
                        this.innerHTML;


                    this.innerHTML =
                        '<i class="bi bi-check2"></i> Ditambahkan';


                    this.style.background =
                        '#2563eb';

                    this.style.color =
                        '#fff';


                    setTimeout(
                        () => {

                            this.innerHTML =
                                original;

                            this.style.background =
                                '';

                            this.style.color =
                                '';

                        },
                        1200
                    );

                }
            );

        }
    );


/* =====================================================
   PRODUCT DETAIL
===================================================== */

document
    .querySelectorAll('.product-card')
    .forEach(
        function (card) {

            card.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target.closest(
                            '.wish-btn'
                        ) ||
                        event.target.closest(
                            '.btn-add-cart'
                        )
                    ) {

                        return;

                    }


                    const button =
                        this.querySelector(
                            '.btn-add-cart'
                        );


                    const id =
                        button.dataset.product;


                    if (id) {

                        window.location.href =
                            "{{ url('/pembeli/produk') }}/"
                            + id;

                    }

                }
            );

        }
    );

</script>


</body>

</html>
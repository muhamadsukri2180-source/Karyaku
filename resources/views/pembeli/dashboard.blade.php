<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Karyaku</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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

            --border: #e5e7eb;
            --radius: 16px;

            --shadow: 0 5px 20px rgba(15, 23, 42, .07);
            --shadow-hover: 0 12px 30px rgba(15, 23, 42, .13);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* =====================================================
           NAVBAR
        ===================================================== */

        .site-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: linear-gradient(
                120deg,
                var(--primary-darker),
                var(--primary-dark) 60%,
                var(--primary)
            );

            box-shadow: 0 8px 25px rgba(20, 34, 92, .18);
        }

        .navbar-top {
            max-width: 1450px;
            margin: auto;

            display: flex;
            align-items: center;
            gap: 16px;

            padding: 12px 28px;
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

            border-radius: 12px;

            background: white;
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;
        }

        .brand-text h5 {
            margin: 0;
            color: white;

            font-size: 16px;
            font-weight: 700;
        }

        .brand-text small {
            color: rgba(255,255,255,.6);
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

            color: rgba(255,255,255,.78);

            padding: 9px 13px;

            border-radius: 9px;

            font-size: 13px;
            font-weight: 500;

            transition: .2s;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }

        .nav-link.active {
            color: white;
            background: rgba(255,255,255,.16);
            font-weight: 600;
        }

        .badge-count {
            min-width: 17px;
            height: 17px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--coral);
            color: white;

            border-radius: 20px;

            font-size: 9px;
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;

            border: none;
            border-radius: 11px;

            background: rgba(255,255,255,.12);
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;
        }

        .icon-btn:hover {
            background: rgba(255,255,255,.2);
        }

        .notification-dot {
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

        .user-menu {
            position: relative;
        }

        .user-chip {
            border: none;

            display: flex;
            align-items: center;
            gap: 8px;

            padding: 5px 10px 5px 5px;

            border-radius: 30px;

            background: rgba(255,255,255,.12);

            color: white;
        }

        .user-chip:hover {
            background: rgba(255,255,255,.2);
        }

        .user-chip img {
            width: 31px;
            height: 31px;

            border-radius: 50%;
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
        }

        .user-role {
            font-size: 9px;
            color: rgba(255,255,255,.6);
        }

        .user-dropdown {
            position: absolute;

            right: 0;
            top: 48px;

            width: 210px;

            background: white;

            padding: 8px;

            border-radius: 14px;

            box-shadow: var(--shadow-hover);

            opacity: 0;
            visibility: hidden;

            transform: translateY(-8px);

            transition: .2s;
        }

        .user-menu.open .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 10px;

            color: var(--text-dark);

            font-size: 13px;

            border-radius: 9px;
        }

        .user-dropdown a:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .mobile-toggle {
            display: none;

            border: none;

            width: 40px;
            height: 40px;

            border-radius: 10px;

            background: rgba(255,255,255,.12);
            color: white;
        }

        /* =====================================================
           MOBILE MENU
        ===================================================== */

        .mobile-menu {
            display: none;

            background: var(--primary-darker);
        }

        .mobile-menu.show {
            display: block;
        }

        .mobile-menu a {
            display: flex;
            align-items: center;
            gap: 12px;

            color: rgba(255,255,255,.8);

            padding: 13px 20px;

            border-top: 1px solid rgba(255,255,255,.07);

            font-size: 13px;
        }

        .mobile-menu a.active {
            color: white;
            background: rgba(255,255,255,.1);
        }

        /* =====================================================
           SEARCH
        ===================================================== */

        .search-area {
            max-width: 1450px;
            margin: auto;

            padding: 0 28px 15px;
        }

        .search-box {
            background: white;

            display: flex;

            border-radius: 12px;

            overflow: hidden;

            box-shadow: 0 8px 22px rgba(0,0,0,.15);
        }

        .search-box select {
            border: none;

            background: var(--primary-light);

            padding: 0 14px;

            font-size: 12px;
            font-weight: 600;

            outline: none;
        }

        .search-box input {
            flex: 1;

            border: none;
            outline: none;

            padding: 12px 14px;

            font-size: 13px;
        }

        .search-box button {
            border: none;

            background: var(--coral);
            color: white;

            padding: 0 22px;

            font-weight: 700;
        }

        .search-box button:hover {
            background: var(--coral-dark);
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .container-main {
            max-width: 1450px;
            margin: auto;

            padding: 20px 28px 60px;
        }

        /* =====================================================
           WELCOME
        ===================================================== */

        .welcome {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 20px;
        }

        .welcome h2 {
            font-size: 23px;
            font-weight: 800;

            margin: 0 0 4px;
        }

        .welcome p {
            margin: 0;

            color: var(--text-muted);

            font-size: 12px;
        }

        .sell-button {
            display: flex;
            align-items: center;
            gap: 7px;

            padding: 10px 16px;

            background: var(--coral);
            color: white;

            border-radius: 10px;

            font-size: 12px;
            font-weight: 700;

            transition: .2s;
        }

        .sell-button:hover {
            color: white;
            background: var(--coral-dark);
            transform: translateY(-2px);
        }

        /* =====================================================
           ADS / HERO
        ===================================================== */

        .hero-grid {
            display: grid;

            grid-template-columns: 2fr 1fr;

            gap: 18px;

            margin-bottom: 25px;
        }

        .hero-main {
            min-height: 250px;

            border-radius: 18px;

            overflow: hidden;

            position: relative;

            background:
                linear-gradient(
                    90deg,
                    rgba(15,35,92,.96),
                    rgba(37,99,235,.72)
                ),
                url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1200&q=80');

            background-size: cover;
            background-position: center;

            padding: 34px;
        }

        .hero-content {
            position: relative;
            z-index: 2;

            max-width: 500px;

            color: white;
        }

        .hero-tag {
            display: inline-block;

            padding: 5px 11px;

            border-radius: 20px;

            background: rgba(255,255,255,.15);

            font-size: 10px;
            font-weight: 700;
        }

        .hero-content h1 {
            margin: 12px 0 8px;

            font-size: 28px;
            font-weight: 800;

            line-height: 1.2;
        }

        .hero-content p {
            color: rgba(255,255,255,.8);

            font-size: 12px;

            max-width: 450px;

            margin-bottom: 17px;
        }

        .hero-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 10px 17px;

            background: white;
            color: var(--primary-dark);

            border-radius: 10px;

            font-size: 12px;
            font-weight: 700;
        }

        .hero-side {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .small-ad {
            flex: 1;

            border-radius: 16px;

            padding: 22px;

            position: relative;

            overflow: hidden;

            color: white;
        }

        .small-ad.orange {
            background: linear-gradient(135deg,#ff7a59,#f0523b);
        }

        .small-ad.blue {
            background: linear-gradient(135deg,#2563eb,#1e3a8a);
        }

        .small-ad h5 {
            font-size: 16px;
            font-weight: 800;

            margin: 0 0 6px;
        }

        .small-ad p {
            font-size: 10px;

            color: rgba(255,255,255,.8);

            margin: 0;
        }

        .small-ad i {
            position: absolute;

            right: 18px;
            bottom: 12px;

            font-size: 55px;

            opacity: .15;
        }

        /* =====================================================
           CATEGORY
        ===================================================== */

        .section {
            margin-top: 28px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 15px;
        }

        .section-title {
            margin: 0;

            font-size: 18px;
            font-weight: 800;
        }

        .section-subtitle {
            margin: 3px 0 0;

            color: var(--text-muted);

            font-size: 11px;
        }

        .see-all {
            color: var(--primary);

            font-size: 12px;
            font-weight: 700;
        }

        .category-grid {
            display: grid;

            grid-template-columns: repeat(7,1fr);

            gap: 12px;
        }

        .category-card {
            background: white;

            border: 1px solid var(--border);

            border-radius: 14px;

            padding: 17px 10px;

            text-align: center;

            color: var(--text-dark);

            transition: .2s;
        }

        .category-card:hover {
            transform: translateY(-4px);

            border-color: var(--primary-soft);

            box-shadow: var(--shadow);
        }

        .category-icon {
            width: 47px;
            height: 47px;

            margin: auto auto 8px;

            border-radius: 14px;

            background: var(--primary-light);
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;
        }

        .category-card span {
            font-size: 10.5px;
            font-weight: 600;
        }

        /* =====================================================
           OLX STYLE LISTING
        ===================================================== */

        .listing-layout {
            display: grid;

            grid-template-columns: 1fr 300px;

            gap: 20px;
        }

        .listing-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .listing-card {
            background: white;

            border: 1px solid var(--border);

            border-radius: 14px;

            padding: 10px;

            display: flex;

            gap: 15px;

            transition: .2s;

            cursor: pointer;
        }

        .listing-card:hover {
            border-color: #cbd5e1;

            box-shadow: var(--shadow);

            transform: translateY(-2px);
        }

        .listing-image {
            width: 180px;
            height: 130px;

            flex-shrink: 0;

            border-radius: 10px;

            overflow: hidden;

            position: relative;
        }

        .listing-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        .listing-image .promo-label {
            position: absolute;

            top: 8px;
            left: 8px;

            background: var(--coral);

            color: white;

            padding: 4px 7px;

            border-radius: 6px;

            font-size: 8px;
            font-weight: 700;
        }

        .listing-info {
            flex: 1;

            padding: 3px 5px;

            position: relative;
        }

        .listing-category {
            color: var(--primary);

            font-size: 9px;
            font-weight: 700;

            text-transform: uppercase;
        }

        .listing-info h5 {
            font-size: 14px;
            font-weight: 700;

            margin: 5px 0 7px;
        }

        .listing-price {
            color: var(--coral);

            font-size: 17px;
            font-weight: 800;

            margin-bottom: 7px;
        }

        .listing-location {
            color: var(--text-muted);

            font-size: 10px;

            display: flex;
            align-items: center;
            gap: 4px;
        }

        .listing-seller {
            color: var(--text-muted);

            font-size: 10px;

            margin-top: 8px;
        }

        .listing-heart {
            position: absolute;

            top: 2px;
            right: 2px;

            width: 30px;
            height: 30px;

            border: none;

            border-radius: 50%;

            background: #f8fafc;

            color: #64748b;
        }

        .listing-heart:hover {
            color: var(--coral);
            background: #fff1ed;
        }

        /* =====================================================
           SIDEBAR AD
        ===================================================== */

        .side-box {
            background: white;

            border: 1px solid var(--border);

            border-radius: 15px;

            overflow: hidden;

            height: fit-content;
        }

        .side-box-header {
            padding: 15px;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            gap: 8px;
        }

        .side-box-header h6 {
            margin: 0;

            font-size: 13px;
            font-weight: 700;
        }

        .ad-item {
            padding: 13px;

            display: flex;
            gap: 10px;

            border-bottom: 1px solid #f1f5f9;
        }

        .ad-item:last-child {
            border-bottom: none;
        }

        .ad-item img {
            width: 65px;
            height: 55px;

            object-fit: cover;

            border-radius: 8px;
        }

        .ad-item h6 {
            margin: 0 0 4px;

            font-size: 10.5px;
            font-weight: 600;
        }

        .ad-item strong {
            color: var(--coral);

            font-size: 11px;
        }

        .ad-item small {
            display: block;

            color: var(--text-muted);

            font-size: 8px;

            margin-top: 2px;
        }

        /* =====================================================
           PRODUCT GRID
        ===================================================== */

        .product-grid {
            display: grid;

            grid-template-columns: repeat(4,1fr);

            gap: 15px;
        }

        .product-card {
            background: white;

            border: 1px solid var(--border);

            border-radius: 14px;

            overflow: hidden;

            transition: .25s;

            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);

            box-shadow: var(--shadow-hover);
        }

        .product-image {
            height: 160px;

            position: relative;

            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: .4s;
        }

        .product-card:hover img {
            transform: scale(1.06);
        }

        .product-tag {
            position: absolute;

            top: 9px;
            left: 9px;

            background: rgba(20,34,92,.82);

            color: white;

            padding: 4px 8px;

            border-radius: 6px;

            font-size: 8px;
            font-weight: 700;
        }

        .product-fav {
            position: absolute;

            top: 8px;
            right: 8px;

            width: 30px;
            height: 30px;

            border: none;

            border-radius: 50%;

            background: rgba(255,255,255,.92);

            color: #64748b;
        }

        .product-fav.active {
            color: var(--coral);
        }

        .product-body {
            padding: 12px;
        }

        .product-body h6 {
            font-size: 12px;
            font-weight: 600;

            line-height: 1.4;

            min-height: 34px;

            margin: 0 0 5px;
        }

        .product-price {
            color: var(--coral);

            font-size: 14px;
            font-weight: 800;
        }

        .product-rating {
            display: flex;
            justify-content: space-between;

            color: var(--text-muted);

            font-size: 9px;

            margin-top: 7px;
        }

        .product-rating .star {
            color: #f59e0b;
            font-weight: 700;
        }

        .seller {
            display: flex;
            align-items: center;
            gap: 6px;

            margin-top: 9px;

            color: var(--text-muted);

            font-size: 9px;
        }

        .seller img {
            width: 20px;
            height: 20px;

            border-radius: 50%;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media(max-width:1100px) {

            .nav-menu {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero-side {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .category-grid {
                grid-template-columns: repeat(4,1fr);
            }

            .listing-layout {
                grid-template-columns: 1fr;
            }

            .product-grid {
                grid-template-columns: repeat(3,1fr);
            }
        }

        @media(max-width:700px) {

            .navbar-top {
                padding: 10px 15px;
            }

            .search-area {
                padding: 0 15px 12px;
            }

            .container-main {
                padding: 18px 15px 40px;
            }

            .welcome {
                align-items: flex-start;
            }

            .welcome h2 {
                font-size: 19px;
            }

            .sell-button {
                display: none;
            }

            .hero-main {
                min-height: 240px;

                padding: 25px;
            }

            .hero-content h1 {
                font-size: 23px;
            }

            .category-grid {
                grid-template-columns: repeat(4,1fr);
            }

            .category-card {
                padding: 13px 5px;
            }

            .category-icon {
                width: 40px;
                height: 40px;
            }

            .listing-card {
                gap: 10px;
            }

            .listing-image {
                width: 125px;
                height: 110px;
            }

            .listing-info h5 {
                font-size: 12px;
            }

            .listing-price {
                font-size: 14px;
            }

            .product-grid {
                grid-template-columns: repeat(2,1fr);
                gap: 10px;
            }

            .product-image {
                height: 135px;
            }
        }

        @media(max-width:480px) {

            .brand-text {
                display: none !important;
            }

            .navbar-right .icon-btn {
                width: 36px;
                height: 36px;
            }

            .user-chip {
                padding: 4px;
            }

            .user-chip .bi-chevron-down {
                display: none;
            }

            .search-box select {
                display: none;
            }

            .hero-side {
                grid-template-columns: 1fr;
            }

            .category-grid {
                grid-template-columns: repeat(4,1fr);

                gap: 7px;
            }

            .category-card span {
                font-size: 8px;
            }

            .listing-image {
                width: 105px;
                height: 100px;
            }

            .listing-seller {
                display: none;
            }
        }
    </style>
</head>

<body>

<!-- =====================================================
     NAVBAR
===================================================== -->

<header class="site-navbar">

    <div class="navbar-top">

        <button class="mobile-toggle" id="mobileToggle">
            <i class="bi bi-list"></i>
        </button>

        <a href="{{ url('/pembeli/dashboard') }}" class="brand">

            <div class="brand-icon">
                <i class="bi bi-bag-check-fill"></i>
            </div>

            <div class="brand-text">
                <h5>Karyaku</h5>
                <small>Marketplace Pembeli</small>
            </div>

        </a>

        <nav class="nav-menu">

            <a href="{{ url('/pembeli/dashboard') }}"
               class="nav-link active">

                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard

            </a>

            <a href="{{ url('/pembeli/marketplace') }}"
               class="nav-link">

                <i class="bi bi-shop"></i>
                Marketplace

            </a>

            <a href="{{ url('/pembeli/wishlist') }}"
               class="nav-link">

                <i class="bi bi-heart-fill"></i>
                Wishlist

                <span class="badge-count">5</span>

            </a>

            <a href="{{ url('/pembeli/keranjang') }}"
               class="nav-link">

                <i class="bi bi-cart-fill"></i>
                Keranjang

                <span class="badge-count">3</span>

            </a>

            <a href="{{ url('/pembeli/pesanan') }}"
               class="nav-link">

                <i class="bi bi-receipt"></i>
                Pesanan

            </a>

        </nav>

        <div class="navbar-right">

            <a href="#" class="sell-button d-none d-lg-flex">

                <i class="bi bi-shop-window"></i>

                Daftar Sebagai Penjual

            </a>

            <button class="icon-btn">

                <i class="bi bi-bell"></i>

                <span class="notification-dot">2</span>

            </button>

            <div class="user-menu" id="userMenu">

                <button class="user-chip" id="userButton">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Pembeli') }}&background=ffffff&color=1e3a8a"
                        alt="Avatar"
                    >

                    <div class="d-none d-lg-block">

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

                    <a href="{{ url('/pembeli/profile') }}">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>

                    <a href="{{ url('/pembeli/pesanan') }}">
                        <i class="bi bi-receipt"></i>
                        Pesanan Saya
                    </a>

                    <a href="#">
                        <i class="bi bi-download"></i>
                        Download Saya
                    </a>

                    <hr>

                    <a href="{{ url('/logout') }}" class="text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Keluar
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- MOBILE MENU -->

    <div class="mobile-menu" id="mobileMenu">

        <a href="{{ url('/pembeli/dashboard') }}" class="active">
            <i class="bi bi-grid-1x2-fill"></i>
            Dashboard
        </a>

        <a href="{{ url('/pembeli/marketplace') }}">
            <i class="bi bi-shop"></i>
            Marketplace
        </a>

        <a href="{{ url('/pembeli/wishlist') }}">
            <i class="bi bi-heart-fill"></i>
            Wishlist
        </a>

        <a href="{{ url('/pembeli/keranjang') }}">
            <i class="bi bi-cart-fill"></i>
            Keranjang
        </a>

        <a href="{{ url('/pembeli/pesanan') }}">
            <i class="bi bi-receipt"></i>
            Pesanan
        </a>

        <a href="{{ url('/pembeli/profile') }}">
            <i class="bi bi-person"></i>
            Profile
        </a>

    </div>


    <!-- SEARCH -->

    <div class="search-area">

        <form class="search-box" id="searchForm">

            <select id="categorySelect">

                <option value="">Semua Kategori</option>

                <option value="poster">
                    Poster Canva
                </option>

                <option value="3d">
                    Model & Animasi 3D
                </option>

                <option value="logo">
                    Logo & Branding
                </option>

                <option value="sosmed">
                    Konten Media Sosial
                </option>

                <option value="uiux">
                    UI/UX Design
                </option>

                <option value="ilustrasi">
                    Ilustrasi Digital
                </option>

            </select>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari jasa, desain, kreator atau kata kunci..."
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


<!-- =====================================================
     MAIN
===================================================== -->

<main class="container-main">


    <!-- WELCOME -->

    <div class="welcome">

        <div>

            <h2>
                Halo, {{ Auth::user()->name ?? 'Pembeli' }} 👋
            </h2>

            <p>
                Temukan karya digital dan jasa terbaik dari kreator Karyaku.
            </p>

        </div>

        <a href="#" class="sell-button">

            <i class="bi bi-plus-lg"></i>

            Mulai Jualan

        </a>

    </div>


    <!-- =================================================
         IKLAN / HERO
    ================================================== -->

    <div class="hero-grid">

        <div class="hero-main">

            <div class="hero-content">

                <span class="hero-tag">
                    🔥 PROMO MINGGU INI
                </span>

                <h1>
                    Temukan Jasa Kreatif
                    Untuk Kebutuhanmu
                </h1>

                <p>
                    Cari desain poster, logo, UI/UX,
                    ilustrasi, animasi 3D dan berbagai
                    karya kreator lainnya.
                </p>

                <a href="{{ url('/pembeli/marketplace') }}"
                   class="hero-button">

                    Jelajahi Marketplace

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        <div class="hero-side">

            <div class="small-ad orange">

                <h5>
                    Diskon 25%
                </h5>

                <p>
                    Jasa desain pilihan
                    minggu ini.
                </p>

                <i class="bi bi-percent"></i>

            </div>

            <div class="small-ad blue">

                <h5>
                    Kreator Baru
                </h5>

                <p>
                    Temukan karya kreator
                    terbaik.
                </p>

                <i class="bi bi-stars"></i>

            </div>

        </div>

    </div>


    <!-- =================================================
         KATEGORI
    ================================================== -->

    <section class="section">

        <div class="section-header">

            <div>

                <h3 class="section-title">
                    Kategori
                </h3>

                <p class="section-subtitle">
                    Cari berdasarkan kebutuhanmu
                </p>

            </div>

            <a href="{{ url('/pembeli/marketplace') }}"
               class="see-all">

                Lihat Semua
                <i class="bi bi-chevron-right"></i>

            </a>

        </div>


        <div class="category-grid">

            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-image"></i>
                </div>

                <span>
                    Poster Canva
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-box"></i>
                </div>

                <span>
                    3D & Blender
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-vector-pen"></i>
                </div>

                <span>
                    Logo & Brand
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-instagram"></i>
                </div>

                <span>
                    Sosial Media
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-phone"></i>
                </div>

                <span>
                    UI/UX
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-palette"></i>
                </div>

                <span>
                    Ilustrasi
                </span>

            </a>


            <a href="#" class="category-card">

                <div class="category-icon">
                    <i class="bi bi-three-dots"></i>
                </div>

                <span>
                    Lainnya
                </span>

            </a>

        </div>

    </section>


    <!-- =================================================
         IKLAN PILIHAN / LISTING
    ================================================== -->

    <section class="section">

        <div class="section-header">

            <div>

                <h3 class="section-title">
                    Iklan Pilihan
                </h3>

                <p class="section-subtitle">
                    Jasa yang sedang banyak dicari pembeli
                </p>

            </div>

            <a href="{{ url('/pembeli/marketplace') }}"
               class="see-all">

                Lihat Semua
                <i class="bi bi-chevron-right"></i>

            </a>

        </div>


        <div class="listing-layout">


            <!-- LISTING -->

            <div class="listing-list">


                <!-- LISTING 1 -->

                <div class="listing-card">

                    <div class="listing-image">

                        <span class="promo-label">
                            PROMO
                        </span>

                        <img
                            src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=500&q=80"
                            alt="Poster"
                        >

                    </div>


                    <div class="listing-info">

                        <button class="listing-heart">

                            <i class="bi bi-heart"></i>

                        </button>

                        <span class="listing-category">
                            Poster Canva
                        </span>

                        <h5>
                            Desain Poster Promosi Kafe & Resto
                        </h5>

                        <div class="listing-price">
                            Rp75.000
                        </div>

                        <div class="listing-location">

                            <i class="bi bi-geo-alt"></i>

                            Jakarta • Bisa Online

                        </div>

                        <div class="listing-seller">

                            <i class="bi bi-person"></i>

                            Dinda Studio • ⭐ 4.9

                        </div>

                    </div>

                </div>


                <!-- LISTING 2 -->

                <div class="listing-card">

                    <div class="listing-image">

                        <img
                            src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=500&q=80"
                            alt="3D"
                        >

                    </div>


                    <div class="listing-info">

                        <button class="listing-heart">

                            <i class="bi bi-heart"></i>

                        </button>

                        <span class="listing-category">
                            3D Blender
                        </span>

                        <h5>
                            Model 3D Karakter Game Low-Poly
                        </h5>

                        <div class="listing-price">
                            Rp480.000
                        </div>

                        <div class="listing-location">

                            <i class="bi bi-geo-alt"></i>

                            Bandung • Bisa Online

                        </div>

                        <div class="listing-seller">

                            <i class="bi bi-person"></i>

                            Rangga.blend • ⭐ 5.0

                        </div>

                    </div>

                </div>


                <!-- LISTING 3 -->

                <div class="listing-card">

                    <div class="listing-image">

                        <span class="promo-label">
                            TERLARIS
                        </span>

                        <img
                            src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=500&q=80"
                            alt="Logo"
                        >

                    </div>


                    <div class="listing-info">

                        <button class="listing-heart">

                            <i class="bi bi-heart"></i>

                        </button>

                        <span class="listing-category">
                            Logo & Branding
                        </span>

                        <h5>
                            Paket Logo & Brand Identity Kit
                        </h5>

                        <div class="listing-price">
                            Rp150.000
                        </div>

                        <div class="listing-location">

                            <i class="bi bi-geo-alt"></i>

                            Depok • Bisa Online

                        </div>

                        <div class="listing-seller">

                            <i class="bi bi-person"></i>

                            Kirana Design • ⭐ 4.8

                        </div>

                    </div>

                </div>


                <!-- LISTING 4 -->

                <div class="listing-card">

                    <div class="listing-image">

                        <img
                            src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=500&q=80"
                            alt="Social Media"
                        >

                    </div>


                    <div class="listing-info">

                        <button class="listing-heart">

                            <i class="bi bi-heart"></i>

                        </button>

                        <span class="listing-category">
                            Social Media
                        </span>

                        <h5>
                            Paket 15 Feed & Story Instagram
                        </h5>

                        <div class="listing-price">
                            Rp120.000
                        </div>

                        <div class="listing-location">

                            <i class="bi bi-geo-alt"></i>

                            Jakarta • Bisa Online

                        </div>

                        <div class="listing-seller">

                            <i class="bi bi-person"></i>

                            Sasi Creative • ⭐ 4.7

                        </div>

                    </div>

                </div>


            </div>


            <!-- SIDEBAR -->

            <div class="side-box">

                <div class="side-box-header">

                    <i class="bi bi-megaphone-fill"
                       style="color:var(--coral)">
                    </i>

                    <h6>
                        Iklan Terbaru
                    </h6>

                </div>


                <div class="ad-item">

                    <img
                        src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=200&q=80"
                        alt=""
                    >

                    <div>

                        <h6>
                            Desain UI Aplikasi Mobile
                        </h6>

                        <strong>
                            Rp650.000
                        </strong>

                        <small>
                            Nadia UX
                        </small>

                    </div>

                </div>


                <div class="ad-item">

                    <img
                        src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=200&q=80"
                        alt=""
                    >

                    <div>

                        <h6>
                            Ilustrasi Vektor Custom
                        </h6>

                        <strong>
                            Rp95.000
                        </strong>

                        <small>
                            Ilma.art
                        </small>

                    </div>

                </div>


                <div class="ad-item">

                    <img
                        src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=200&q=80"
                        alt=""
                    >

                    <div>

                        <h6>
                            Poster Event & Webinar
                        </h6>

                        <strong>
                            Rp65.000
                        </strong>

                        <small>
                            Studio Elang
                        </small>

                    </div>

                </div>


                <div class="ad-item">

                    <img
                        src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?auto=format&fit=crop&w=200&q=80"
                        alt=""
                    >

                    <div>

                        <h6>
                            Render Interior Rumah
                        </h6>

                        <strong>
                            Rp720.000
                        </strong>

                        <small>
                            Vio 3D Studio
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =================================================
         JASA TERBARU
    ================================================== -->

    <section class="section">

        <div class="section-header">

            <div>

                <h3 class="section-title">
                    Jasa Terbaru
                </h3>

                <p class="section-subtitle">
                    Karya terbaru dari para kreator
                </p>

            </div>

            <a href="{{ url('/pembeli/marketplace') }}"
               class="see-all">

                Lihat Semua
                <i class="bi bi-chevron-right"></i>

            </a>

        </div>


        <div class="product-grid">


            <!-- PRODUCT 1 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        POSTER
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Desain Poster Promosi Kafe & Resto
                    </h6>

                    <div class="product-price">
                        Rp75.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.9
                        </span>

                        <span>
                            320 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Dinda+Studio&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Dinda Studio

                    </div>

                </div>

            </div>


            <!-- PRODUCT 2 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        3D
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Model 3D Karakter Game Low-Poly
                    </h6>

                    <div class="product-price">
                        Rp480.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            5.0
                        </span>

                        <span>
                            128 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Rangga&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Rangga.blend

                    </div>

                </div>

            </div>


            <!-- PRODUCT 3 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        BRANDING
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Paket Logo & Brand Identity Kit
                    </h6>

                    <div class="product-price">
                        Rp150.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.8
                        </span>

                        <span>
                            210 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Kirana+Design&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Kirana Design

                    </div>

                </div>

            </div>


            <!-- PRODUCT 4 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        UI/UX
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Desain UI Aplikasi Mobile Lengkap
                    </h6>

                    <div class="product-price">
                        Rp650.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.9
                        </span>

                        <span>
                            84 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Nadia+UX&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Nadia UX

                    </div>

                </div>

            </div>


            <!-- PRODUCT 5 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        ILUSTRASI
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Ilustrasi Vektor Karakter Custom
                    </h6>

                    <div class="product-price">
                        Rp95.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.8
                        </span>

                        <span>
                            260 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Ilma+Art&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Ilma.art

                    </div>

                </div>

            </div>


            <!-- PRODUCT 6 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        SOCIAL MEDIA
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Paket 15 Feed & Story Instagram
                    </h6>

                    <div class="product-price">
                        Rp120.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.7
                        </span>

                        <span>
                            176 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Sasi+Creative&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Sasi Creative

                    </div>

                </div>

            </div>


            <!-- PRODUCT 7 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        POSTER
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Desain Poster Event & Webinar
                    </h6>

                    <div class="product-price">
                        Rp65.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            4.6
                        </span>

                        <span>
                            142 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Studio+Elang&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Studio Elang

                    </div>

                </div>

            </div>


            <!-- PRODUCT 8 -->

            <div class="product-card">

                <div class="product-image">

                    <span class="product-tag">
                        3D
                    </span>

                    <button class="product-fav">

                        <i class="bi bi-heart"></i>

                    </button>

                    <img
                        src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?auto=format&fit=crop&w=500&q=80"
                        alt=""
                    >

                </div>

                <div class="product-body">

                    <h6>
                        Render Visualisasi Interior Rumah
                    </h6>

                    <div class="product-price">
                        Rp720.000
                    </div>

                    <div class="product-rating">

                        <span class="star">
                            <i class="bi bi-star-fill"></i>
                            5.0
                        </span>

                        <span>
                            56 terjual
                        </span>

                    </div>

                    <div class="seller">

                        <img
                            src="https://ui-avatars.com/api/?name=Vio+3D&background=dbeafe&color=1e3a8a"
                            alt=""
                        >

                        Vio 3D Studio

                    </div>

                </div>

            </div>

=======
@extends('layouts.pembeli')
@section('title', 'Dashboard')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Halo, {{ $navUser->name ?? 'Pembeli' }}</h4>
    <p class="text-muted mb-0" style="font-size: 13px;">Berikut ringkasan aktivitas belanja kamu di Karyaku.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalPesanan }}</div>
                <div class="label">Total Pesanan</div>
            </div>
            <div class="icon" style="background:#2563eb;"><i class="bi bi-receipt"></i></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalSelesai }}</div>
                <div class="label">Pesanan Selesai</div>
            </div>
            <div class="icon" style="background:#10b981;"><i class="bi bi-check2-circle"></i></div>
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value" style="font-size:17px;">Rp{{ number_format($totalBelanja, 0, ',', '.') }}</div>
                <div class="label">Total Belanja</div>
            </div>
            <div class="icon" style="background:#FF7A59;"><i class="bi bi-wallet2"></i></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalWishlist }}</div>
                <div class="label">Wishlist</div>
            </div>
            <div class="icon" style="background:#e11d48;"><i class="bi bi-heart-fill"></i></div>
        </div>
    </div>
</div>

<<<<<<< HEAD
    </section>

=======
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-box p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
                <a href="{{ route('pembeli.pesanan') }}" class="small fw-semibold" style="color: var(--primary);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>

            @forelse ($recentOrders as $order)
                <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="d-flex align-items-center justify-content-between py-3 border-bottom text-decoration-none text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width:42px;height:42px;background:var(--primary-light);color:var(--primary);">
                            <i class="bi bi-bag-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $order->kode_order }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $order->items->first()->product->title ?? '-' }}@if($order->items->count() > 1) (+{{ $order->items->count() - 1 }} lainnya)@endif</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold small" style="color: var(--coral);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</div>
                        @php
                            $statusColor = match($order->status) {
                                'selesai' => 'bg-success-subtle text-success',
                                'dibatalkan' => 'bg-danger-subtle text-danger',
                                default => 'bg-warning-subtle text-warning',
                            };
                        @endphp
                        <span class="badge-status {{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Belum ada pesanan. Yuk mulai belanja di <a href="{{ route('pembeli.marketplace') }}">Marketplace</a>.
                </div>
            @endforelse
        </div>
    </div>
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895

    <div class="col-lg-4">
        <div class="card-box p-4 h-100">
            <h6 class="fw-bold mb-3">Akses Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('pembeli.marketplace') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-shop"></i> Jelajahi Marketplace</a>
                <a href="{{ route('pembeli.keranjang') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-cart-fill"></i> Lihat Keranjang ({{ $totalKeranjang }})</a>
                <a href="{{ route('pembeli.wishlist') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-heart-fill"></i> Wishlist Saya ({{ $totalWishlist }})</a>
                <a href="{{ route('pembeli.download') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-cloud-arrow-down-fill"></i> Karya yang Sudah Dibeli</a>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD

<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

    /* ================================================
       MOBILE MENU
    ================================================ */

    const mobileToggle =
        document.getElementById('mobileToggle');

    const mobileMenu =
        document.getElementById('mobileMenu');

    mobileToggle?.addEventListener('click', function () {

        mobileMenu.classList.toggle('show');

        const icon =
            this.querySelector('i');

        if (mobileMenu.classList.contains('show')) {

            icon.className = 'bi bi-x-lg';

        } else {

            icon.className = 'bi bi-list';

        }

    });


    /* ================================================
       USER DROPDOWN
    ================================================ */

    const userMenu =
        document.getElementById('userMenu');

    const userButton =
        document.getElementById('userButton');

    userButton?.addEventListener('click', function (e) {

        e.stopPropagation();

        userMenu.classList.toggle('open');

    });


    document.addEventListener('click', function (e) {

        if (
            userMenu &&
            !userMenu.contains(e.target)
        ) {

            userMenu.classList.remove('open');

        }

    });


    /* ================================================
       WISHLIST
    ================================================ */

    document
        .querySelectorAll('.product-fav, .listing-heart')
        .forEach(button => {

            button.addEventListener('click', function (e) {

                e.stopPropagation();

                this.classList.toggle('active');

                const icon =
                    this.querySelector('i');

                if (
                    icon.classList.contains('bi-heart')
                ) {

                    icon.classList.remove('bi-heart');

                    icon.classList.add('bi-heart-fill');

                } else {

                    icon.classList.remove('bi-heart-fill');

                    icon.classList.add('bi-heart');

                }

            });

        });


    /* ================================================
       SEARCH
    ================================================ */

    const searchForm =
        document.getElementById('searchForm');

    searchForm?.addEventListener('submit', function (e) {

        e.preventDefault();

        const keyword =
            document.getElementById('searchInput')
                .value
                .trim();

        const category =
            document.getElementById('categorySelect')
                .value;

        if (!keyword && !category) {

            alert('Masukkan kata kunci atau pilih kategori.');

            return;

        }

        console.log(
            'Search:',
            keyword,
            'Category:',
            category
        );

    });


    /* ================================================
       PRODUCT CARD
    ================================================ */

    document
        .querySelectorAll('.product-card')
        .forEach(card => {

            card.addEventListener('click', function () {

                window.location.href =
                    "{{ url('/pembeli/produk') }}";

            });

        });


    /* ================================================
       LISTING CARD
    ================================================ */

    document
        .querySelectorAll('.listing-card')
        .forEach(card => {

            card.addEventListener('click', function (e) {

                if (
                    e.target.closest('.listing-heart')
                ) {
                    return;
                }

                window.location.href =
                    "{{ url('/pembeli/produk') }}";

            });

        });


</script>

</body>
</html>
=======
@if ($rekomendasi->isNotEmpty())
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Rekomendasi Untuk Kamu</h6>
        <a href="{{ route('pembeli.marketplace') }}" class="small fw-semibold" style="color: var(--primary);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="product-grid">
        @foreach ($rekomendasi as $product)
            @include('pembeli.partials.product-card', ['product' => $product, 'wishlistIds' => []])
        @endforeach
    </div>
</div>
@endif

@endsection
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895

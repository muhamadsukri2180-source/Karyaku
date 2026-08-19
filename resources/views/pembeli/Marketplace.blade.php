<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Marketplace - Karyaku</title>

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

            --border-color: #e5edff;

            --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
            --shadow-hover: 0 16px 34px rgba(37, 99, 235, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f8ff;
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
        }

        /* ================= NAVBAR ================= */

        .site-navbar {
            background: linear-gradient(
                120deg,
                var(--primary-darker),
                var(--primary-dark) 60%,
                var(--primary)
            );

            position: sticky;
            top: 0;
            z-index: 1000;

            box-shadow: 0 10px 30px rgba(20, 34, 92, 0.18);
        }

        .navbar-top {
            max-width: 1450px;
            margin: auto;

            padding: 12px 28px;

            display: flex;
            align-items: center;
            gap: 18px;
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
            color: rgba(255,255,255,.6);
            font-size: 10px;
        }

        .mobile-toggle {
            display: none;

            width: 40px;
            height: 40px;

            border: 0;
            border-radius: 10px;

            background: rgba(255,255,255,.12);
            color: white;
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

            border-radius: 10px;

            font-size: 13px;
            font-weight: 500;

            transition: .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(255,255,255,.12);
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

            border: 0;

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

            background: rgba(255,255,255,.12);
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

        /* ================= USER ================= */

        .user-menu {
            position: relative;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;

            border: 0;
            border-radius: 30px;

            background: rgba(255,255,255,.12);

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
            color: rgba(255,255,255,.65);
        }

        .user-dropdown {
            position: absolute;

            right: 0;
            top: 50px;

            width: 210px;

            background: white;

            border-radius: 14px;

            box-shadow: var(--shadow-hover);

            padding: 8px;

            display: none;
        }

        .user-menu.open .user-dropdown {
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
            background: var(--primary-light);
        }

        /* ================= SEARCH ================= */

        .navbar-search {
            max-width: 1450px;
            margin: auto;

            padding: 0 28px 14px;
        }

        .search-combo {
            display: flex;

            overflow: hidden;

            background: white;

            border-radius: 12px;

            box-shadow: 0 8px 22px rgba(0,0,0,.15);
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

            border-right: 1px solid var(--border-color);
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

        /* ================= MAIN ================= */

        .main-content {
            max-width: 1450px;
            margin: auto;

            padding: 24px 28px 60px;
        }

        /* ================= HEADER ================= */

        .market-header {
            margin-bottom: 24px;
        }

        .market-header h2 {
            margin: 0;

            font-size: 25px;
            font-weight: 800;
        }

        .market-header p {
            margin: 5px 0 0;

            color: var(--text-muted);

            font-size: 13px;
        }

        /* ================= FILTER ================= */

        .market-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-bottom: 20px;

            flex-wrap: wrap;
        }

        .filter-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-pill {
            border: 1px solid var(--border-color);

            background: white;

            color: var(--text-dark);

            padding: 8px 15px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            transition: .2s;
        }

        .filter-pill:hover,
        .filter-pill.active {
            background: var(--primary);
            color: white;

            border-color: var(--primary);
        }

        .result-count {
            color: var(--text-muted);

            font-size: 12px;
        }

        /* ================= PRODUCT GRID ================= */

        .product-grid {
            display: grid;

            grid-template-columns: repeat(5, 1fr);

            gap: 18px;
        }

        .product-card {
            position: relative;

            background: white;

            border-radius: 15px;

            overflow: hidden;

            border: 1px solid var(--border-color);

            box-shadow: var(--shadow);

            transition: .25s;

            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);

            box-shadow: var(--shadow-hover);
        }

        .product-thumb {
            height: 165px;

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

        .product-card:hover .product-thumb img {
            transform: scale(1.06);
        }

        .cat-badge {
            position: absolute;

            top: 9px;
            left: 9px;

            padding: 4px 9px;

            background: rgba(20,34,92,.8);

            color: white;

            border-radius: 20px;

            font-size: 9px;
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

            background: rgba(255,255,255,.94);

            color: #64748b;

            z-index: 3;

            transition: .2s;
        }

        .wish-btn:hover,
        .wish-btn.active {
            color: var(--coral);
        }

        .product-body {
            padding: 12px;

            display: flex;
            flex-direction: column;

            gap: 6px;
        }

        .product-body h6 {
            margin: 0;

            min-height: 36px;

            font-size: 12px;
            line-height: 1.5;

            font-weight: 600;
        }

        .product-price {
            color: var(--coral);

            font-size: 15px;

            font-weight: 800;
        }

        .product-price small {
            color: #94a3b8;

            text-decoration: line-through;

            font-size: 9px;

            font-weight: 500;

            margin-left: 4px;
        }

        .product-meta {
            display: flex;

            justify-content: space-between;

            color: var(--text-muted);

            font-size: 9px;
        }

        .rating {
            color: #f59e0b;

            font-weight: 700;
        }

        .product-seller {
            display: flex;
            align-items: center;

            gap: 6px;

            color: var(--text-muted);

            font-size: 9px;
        }

        .product-seller img {
            width: 20px;
            height: 20px;

            border-radius: 50%;
        }

        .btn-add-cart {
            width: 100%;

            margin-top: 5px;

            border: 0;
            border-radius: 9px;

            background: var(--primary-light);

            color: var(--primary);

            padding: 8px;

            font-size: 10px;

            font-weight: 700;

            transition: .2s;
        }

        .btn-add-cart:hover {
            background: var(--primary);
            color: white;
        }

        /* ================= NO RESULT ================= */

        .no-result {
            display: none;

            text-align: center;

            padding: 70px 20px;

            background: white;

            border-radius: 18px;

            border: 1px solid var(--border-color);

            margin-top: 10px;
        }

        .no-result i {
            font-size: 45px;

            color: #94a3b8;
        }

        .no-result h5 {
            margin-top: 15px;

            font-weight: 700;
        }

        .no-result p {
            color: var(--text-muted);

            font-size: 12px;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width: 1250px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media(max-width: 1000px) {
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

            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width: 700px) {
            .navbar-top {
                padding: 10px 16px;
            }

            .navbar-search {
                padding: 0 16px 12px;
            }

            .main-content {
                padding: 20px 16px 50px;
            }

            .search-combo select {
                width: 110px;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-thumb {
                height: 140px;
            }
        }

        @media(max-width: 450px) {
            .brand-text {
                display: none;
            }

            .user-name,
            .user-role {
                display: none;
            }

            .product-thumb {
                height: 125px;
            }
        }
    </style>
</head>

<body>

<header class="site-navbar">

    <div class="navbar-top">

        <button class="mobile-toggle" id="mobileToggle">
            <i class="bi bi-list"></i>
        </button>

        <a href="{{ route('pembeli.dashboard') }}" class="brand">

            <div class="brand-icon">
                <i class="bi bi-bag-check-fill"></i>
            </div>

            <div class="brand-text">
                <h5>Karyaku</h5>
                <small>Marketplace Pembeli</small>
            </div>

        </a>

        <nav class="nav-menu">

            <a href="{{ route('pembeli.dashboard') }}" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <a href="{{ route('pembeli.marketplace') }}" class="nav-link active">
                <i class="bi bi-shop"></i>
                Marketplace
            </a>

            <a href="{{ route('pembeli.wishlist') }}" class="nav-link">
                <i class="bi bi-heart-fill"></i>
                Wishlist
                <span class="badge-count">5</span>
            </a>

            <a href="{{ route('pembeli.keranjang') }}" class="nav-link">
                <i class="bi bi-cart-fill"></i>
                Keranjang
                <span class="badge-count">3</span>
            </a>

            <a href="{{ route('pembeli.pesanan') }}" class="nav-link">
                <i class="bi bi-receipt"></i>
                Pesanan
            </a>

            <a href="{{ route('pembeli.download') }}" class="nav-link">
                <i class="bi bi-cloud-arrow-down-fill"></i>
                Download
            </a>

        </nav>

        <div class="navbar-right">

            <a href="#" class="btn-jual">
                <i class="bi bi-shop-window"></i>
                Daftar Penjual
            </a>

            <button class="icon-btn-light">
                <i class="bi bi-bell"></i>
                <span class="dot">2</span>
            </button>

            <div class="user-menu" id="userMenu">

                <button class="user-chip" id="userChip">

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

                    <a href="{{ route('pembeli.profile') }}">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>

                    <a href="{{ route('pembeli.pesanan') }}">
                        <i class="bi bi-receipt"></i>
                        Pesanan Saya
                    </a>

                    <a href="{{ route('pembeli.download') }}">
                        <i class="bi bi-download"></i>
                        Download Saya
                    </a>

                    <hr>

                    <form method="POST" action="{{ route('logout') }}">
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

    {{-- SEARCH --}}
    <div class="navbar-search">

        <form
            class="search-combo"
            id="searchForm"
        >

            <select id="categoryFilter">

                <option value="">Semua Kategori</option>

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
                <span class="d-none d-sm-inline">Cari</span>
            </button>

        </form>

    </div>

</header>


<main class="main-content">

    <div class="market-header">

        <h2>Marketplace</h2>

        <p>
            Temukan berbagai barang dan jasa digital dari kreator Karyaku.
        </p>

    </div>


    <div class="market-toolbar">

        <div class="filter-pills">

            <button class="filter-pill active" data-filter="all">
                Semua
            </button>

            <button class="filter-pill" data-filter="terlaris">
                Terlaris
            </button>

            <button class="filter-pill" data-filter="terbaru">
                Terbaru
            </button>

            <button class="filter-pill" data-filter="rating">
                Rating Tertinggi
            </button>

            <button class="filter-pill" data-filter="murah">
                Harga Terendah
            </button>

        </div>

        <div class="result-count" id="resultCount">
            24 produk
        </div>

    </div>


    {{-- ================= PRODUCT GRID ================= --}}

    <div class="product-grid" id="productGrid">


        {{-- 1 --}}
        <div
            class="product-card"
            data-name="Desain Poster Promosi Cafe Resto"
            data-category="desain poster"
            data-seller="Dinda Studio"
            data-status="terlaris"
            data-rating="4.9"
            data-price="75000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Poster
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Desain Poster Promosi Cafe & Resto</h6>

                <div class="product-price">
                    Rp75.000
                    <small>Rp100.000</small>
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

                    <img src="https://ui-avatars.com/api/?name=Dinda+Studio">

                    Dinda Studio

                </div>

                <button
                    class="btn-add-cart"
                    data-product="1"
                >
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 2 --}}
        <div
            class="product-card"
            data-name="Model 3D Karakter Game Low Poly"
            data-category="3d blender"
            data-seller="Rangga Blend"
            data-status="terlaris"
            data-rating="5.0"
            data-price="480000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    3D Blender
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Model 3D Karakter Game Low-Poly</h6>

                <div class="product-price">
                    Rp480.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 5.0</span>
                    <span>Terjual 128</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Rangga">

                    Rangga.blend

                </div>

                <button class="btn-add-cart" data-product="2">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 3 --}}
        <div
            class="product-card"
            data-name="Paket Logo Brand Identity"
            data-category="logo branding"
            data-seller="Kirana Design"
            data-status="terbaru"
            data-rating="4.8"
            data-price="150000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Logo
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Paket Logo & Brand Identity Kit</h6>

                <div class="product-price">
                    Rp150.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 210</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Kirana+Design">

                    Kirana Design

                </div>

                <button class="btn-add-cart" data-product="3">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 4 --}}
        <div
            class="product-card"
            data-name="Paket 15 Feed Story Instagram"
            data-category="sosmed social media"
            data-seller="Sasi Creative"
            data-status="terlaris"
            data-rating="4.7"
            data-price="120000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Social Media
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Paket 15 Feed & Story Instagram</h6>

                <div class="product-price">
                    Rp120.000
                    <small>Rp160.000</small>
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.7</span>
                    <span>Terjual 176</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Sasi+Creative">

                    Sasi Creative

                </div>

                <button class="btn-add-cart" data-product="4">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 5 --}}
        <div
            class="product-card"
            data-name="Desain UI Aplikasi Mobile"
            data-category="uiux"
            data-seller="Nadia UX"
            data-status="terbaru"
            data-rating="4.9"
            data-price="650000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    UI/UX
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Desain UI Aplikasi Mobile Lengkap</h6>

                <div class="product-price">
                    Rp650.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 84</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Nadia+UX">

                    Nadia UX

                </div>

                <button class="btn-add-cart" data-product="5">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 6 --}}
        <div
            class="product-card"
            data-name="Ilustrasi Vektor Karakter Custom"
            data-category="ilustrasi"
            data-seller="Ilma Art"
            data-status="terlaris"
            data-rating="4.8"
            data-price="95000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Ilustrasi
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Ilustrasi Vektor Karakter Custom</h6>

                <div class="product-price">
                    Rp95.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 260</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Ilma+Art">

                    Ilma.art

                </div>

                <button class="btn-add-cart" data-product="6">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 7 --}}
        <div
            class="product-card"
            data-name="Desain Poster Event Webinar"
            data-category="desain poster"
            data-seller="Studio Elang"
            data-status="terbaru"
            data-rating="4.6"
            data-price="65000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Poster
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Desain Poster Event & Webinar</h6>

                <div class="product-price">
                    Rp65.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.6</span>
                    <span>Terjual 142</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Studio+Elang">

                    Studio Elang

                </div>

                <button class="btn-add-cart" data-product="7">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 8 --}}
        <div
            class="product-card"
            data-name="Render Visualisasi Interior Rumah"
            data-category="3d blender"
            data-seller="Vio 3D Studio"
            data-status="terbaru"
            data-rating="5.0"
            data-price="720000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    3D
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Render Visualisasi Interior Rumah</h6>

                <div class="product-price">
                    Rp720.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 5.0</span>
                    <span>Terjual 56</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Vio+3D">

                    Vio 3D Studio

                </div>

                <button class="btn-add-cart" data-product="8">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 9 --}}
        <div
            class="product-card"
            data-name="Jasa Pembuatan Website Laravel"
            data-category="website web laravel"
            data-seller="CodeCraft"
            data-status="terlaris"
            data-rating="4.9"
            data-price="850000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Website
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Pembuatan Website Laravel</h6>

                <div class="product-price">
                    Rp850.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 97</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=CodeCraft">

                    CodeCraft

                </div>

                <button class="btn-add-cart" data-product="9">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 10 --}}
        <div
            class="product-card"
            data-name="Editing Video Reels TikTok"
            data-category="video editing"
            data-seller="Frame Studio"
            data-status="terlaris"
            data-rating="4.8"
            data-price="100000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Video
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Editing Video Reels & TikTok</h6>

                <div class="product-price">
                    Rp100.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 190</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Frame+Studio">

                    Frame Studio

                </div>

                <button class="btn-add-cart" data-product="10">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 11 --}}
        <div
            class="product-card"
            data-name="Template CV Profesional ATS"
            data-category="desain"
            data-seller="ResumePro"
            data-status="terbaru"
            data-rating="4.7"
            data-price="45000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Template
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Template CV Profesional ATS</h6>

                <div class="product-price">
                    Rp45.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.7</span>
                    <span>Terjual 410</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=ResumePro">

                    ResumePro

                </div>

                <button class="btn-add-cart" data-product="11">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 12 --}}
        <div
            class="product-card"
            data-name="Jasa Foto Produk E-Commerce"
            data-category="fotografi"
            data-seller="Pixel House"
            data-status="terlaris"
            data-rating="4.9"
            data-price="200000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Fotografi
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Foto Produk E-Commerce</h6>

                <div class="product-price">
                    Rp200.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 76</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Pixel+House">

                    Pixel House

                </div>

                <button class="btn-add-cart" data-product="12">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 13 --}}
        <div
            class="product-card"
            data-name="Desain Banner Marketplace"
            data-category="desain banner"
            data-seller="VisualPro"
            data-status="terbaru"
            data-rating="4.6"
            data-price="60000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Banner
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1542744094-3a31f272c490?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Desain Banner Marketplace</h6>

                <div class="product-price">
                    Rp60.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.6</span>
                    <span>Terjual 145</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=VisualPro">

                    VisualPro

                </div>

                <button class="btn-add-cart" data-product="13">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 14 --}}
        <div
            class="product-card"
            data-name="Jasa Landing Page Website"
            data-category="website"
            data-seller="WebMaster"
            data-status="terlaris"
            data-rating="4.9"
            data-price="500000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Website
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Landing Page Website</h6>

                <div class="product-price">
                    Rp500.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 88</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=WebMaster">

                    WebMaster

                </div>

                <button class="btn-add-cart" data-product="14">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 15 --}}
        <div
            class="product-card"
            data-name="Paket Icon UI Design"
            data-category="uiux desain"
            data-seller="IconLab"
            data-status="terbaru"
            data-rating="4.8"
            data-price="80000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    UI/UX
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Paket Icon UI Design</h6>

                <div class="product-price">
                    Rp80.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 132</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=IconLab">

                    IconLab

                </div>

                <button class="btn-add-cart" data-product="15">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 16 --}}
        <div
            class="product-card"
            data-name="Jasa Animasi Logo 3D"
            data-category="3d animasi"
            data-seller="MotionX"
            data-status="terlaris"
            data-rating="4.9"
            data-price="250000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Animasi
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Animasi Logo 3D</h6>

                <div class="product-price">
                    Rp250.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 65</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=MotionX">

                    MotionX

                </div>

                <button class="btn-add-cart" data-product="16">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 17 --}}
        <div
            class="product-card"
            data-name="Template Feed Instagram Minimalis"
            data-category="sosmed desain"
            data-seller="Minimal Studio"
            data-status="terbaru"
            data-rating="4.7"
            data-price="70000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Instagram
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1611162617474-5b21e879e113?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Template Feed Instagram Minimalis</h6>

                <div class="product-price">
                    Rp70.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.7</span>
                    <span>Terjual 220</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Minimal+Studio">

                    Minimal Studio

                </div>

                <button class="btn-add-cart" data-product="17">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 18 --}}
        <div
            class="product-card"
            data-name="Jasa Editing Foto Produk"
            data-category="foto editing"
            data-seller="RetouchPro"
            data-status="terlaris"
            data-rating="4.8"
            data-price="50000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Editing
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Editing Foto Produk</h6>

                <div class="product-price">
                    Rp50.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 310</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=RetouchPro">

                    RetouchPro

                </div>

                <button class="btn-add-cart" data-product="18">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 19 --}}
        <div
            class="product-card"
            data-name="Desain Undangan Digital"
            data-category="desain undangan"
            data-seller="Invite Studio"
            data-status="terbaru"
            data-rating="4.7"
            data-price="85000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Undangan
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Desain Undangan Digital Premium</h6>

                <div class="product-price">
                    Rp85.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.7</span>
                    <span>Terjual 155</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Invite+Studio">

                    Invite Studio

                </div>

                <button class="btn-add-cart" data-product="19">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 20 --}}
        <div
            class="product-card"
            data-name="Jasa Pembuatan Logo Profesional"
            data-category="logo branding"
            data-seller="Brandify"
            data-status="terlaris"
            data-rating="5.0"
            data-price="175000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Branding
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1625014618427-fbc980a7d2f7?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Pembuatan Logo Profesional</h6>

                <div class="product-price">
                    Rp175.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 5.0</span>
                    <span>Terjual 199</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Brandify">

                    Brandify

                </div>

                <button class="btn-add-cart" data-product="20">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 21 --}}
        <div
            class="product-card"
            data-name="Jasa SEO Website"
            data-category="website seo"
            data-seller="SEO Master"
            data-status="terbaru"
            data-rating="4.8"
            data-price="350000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    SEO
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Optimasi SEO Website</h6>

                <div class="product-price">
                    Rp350.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 73</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=SEO+Master">

                    SEO Master

                </div>

                <button class="btn-add-cart" data-product="21">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 22 --}}
        <div
            class="product-card"
            data-name="Paket Thumbnail YouTube"
            data-category="desain youtube"
            data-seller="ThumbnailPro"
            data-status="terlaris"
            data-rating="4.9"
            data-price="65000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    YouTube
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Paket Desain Thumbnail YouTube</h6>

                <div class="product-price">
                    Rp65.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.9</span>
                    <span>Terjual 287</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=ThumbnailPro">

                    ThumbnailPro

                </div>

                <button class="btn-add-cart" data-product="22">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 23 --}}
        <div
            class="product-card"
            data-name="Jasa Animasi Motion Graphic"
            data-category="video animasi"
            data-seller="Motion Studio"
            data-status="terbaru"
            data-rating="4.8"
            data-price="400000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Motion
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1536240478700-b869070f9279?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Animasi Motion Graphic</h6>

                <div class="product-price">
                    Rp400.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.8</span>
                    <span>Terjual 91</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=Motion+Studio">

                    Motion Studio

                </div>

                <button class="btn-add-cart" data-product="23">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>


        {{-- 24 --}}
        <div
            class="product-card"
            data-name="Jasa Copywriting Konten Bisnis"
            data-category="copywriting social media"
            data-seller="WriteLab"
            data-status="terbaru"
            data-rating="4.7"
            data-price="90000"
        >

            <div class="product-thumb">

                <span class="cat-badge">
                    Copywriting
                </span>

                <button class="wish-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=80">

            </div>

            <div class="product-body">

                <h6>Jasa Copywriting Konten Bisnis</h6>

                <div class="product-price">
                    Rp90.000
                </div>

                <div class="product-meta">
                    <span class="rating">★ 4.7</span>
                    <span>Terjual 119</span>
                </div>

                <div class="product-seller">

                    <img src="https://ui-avatars.com/api/?name=WriteLab">

                    WriteLab

                </div>

                <button class="btn-add-cart" data-product="24">
                    <i class="bi bi-cart-plus"></i>
                    Tambah Keranjang
                </button>

            </div>

        </div>

    </div>


    {{-- NO RESULT --}}

    <div class="no-result" id="noResult">

        <i class="bi bi-search"></i>

        <h5>Produk tidak ditemukan</h5>

        <p>
            Coba gunakan kata kunci lain seperti
            <b>logo</b>, <b>website</b>,
            <b>poster</b>, atau <b>3D</b>.
        </p>

    </div>

</main>


<script>

    /* =====================================================
       SEARCH PRODUCT
    ===================================================== */

    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');

    const productGrid = document.getElementById('productGrid');
    const products = Array.from(
        document.querySelectorAll('.product-card')
    );

    const noResult = document.getElementById('noResult');
    const resultCount = document.getElementById('resultCount');

    let currentSort = 'all';


    function searchProducts() {

        const keyword =
            searchInput.value
                .toLowerCase()
                .trim();

        const category =
            categoryFilter.value
                .toLowerCase()
                .trim();

        let visibleProducts = [];


        products.forEach(product => {

            const name =
                product.dataset.name
                    .toLowerCase();

            const productCategory =
                product.dataset.category
                    .toLowerCase();

            const seller =
                product.dataset.seller
                    .toLowerCase();


            const keywordMatch =
                keyword === '' ||
                name.includes(keyword) ||
                productCategory.includes(keyword) ||
                seller.includes(keyword);


            const categoryMatch =
                category === '' ||
                productCategory.includes(category);


            if (keywordMatch && categoryMatch) {

                product.style.display = '';

                visibleProducts.push(product);

            } else {

                product.style.display = 'none';

            }

        });


        applySort(visibleProducts);


        resultCount.innerText =
            visibleProducts.length + ' produk';


        noResult.style.display =
            visibleProducts.length === 0
                ? 'block'
                : 'none';


        productGrid.style.display =
            visibleProducts.length === 0
                ? 'none'
                : 'grid';

    }


    /* =====================================================
       SORT / FILTER
    ===================================================== */

    function applySort(list) {

        if (currentSort === 'murah') {

            list.sort((a, b) => {

                return (
                    Number(a.dataset.price) -
                    Number(b.dataset.price)
                );

            });

        }


        if (currentSort === 'rating') {

            list.sort((a, b) => {

                return (
                    Number(b.dataset.rating) -
                    Number(a.dataset.rating)
                );

            });

        }


        if (currentSort === 'terlaris') {

            const order = [
                'terlaris',
                'terbaru'
            ];

            list.sort((a, b) => {

                return (
                    order.indexOf(a.dataset.status) -
                    order.indexOf(b.dataset.status)
                );

            });

        }


        if (currentSort === 'terbaru') {

            list.sort((a, b) => {

                if (
                    a.dataset.status === 'terbaru' &&
                    b.dataset.status !== 'terbaru'
                ) {
                    return -1;
                }

                if (
                    a.dataset.status !== 'terbaru' &&
                    b.dataset.status === 'terbaru'
                ) {
                    return 1;
                }

                return 0;

            });

        }


        list.forEach(product => {

            productGrid.appendChild(product);

        });

    }


    searchForm.addEventListener(
        'submit',
        function(event) {

            event.preventDefault();

            searchProducts();

        }
    );


    searchInput.addEventListener(
        'input',
        searchProducts
    );


    categoryFilter.addEventListener(
        'change',
        searchProducts
    );


    /* =====================================================
       FILTER BUTTON
    ===================================================== */

    document
        .querySelectorAll('.filter-pill')
        .forEach(button => {

            button.addEventListener(
                'click',
                function() {

                    document
                        .querySelectorAll('.filter-pill')
                        .forEach(btn => {

                            btn.classList.remove('active');

                        });


                    this.classList.add('active');


                    currentSort =
                        this.dataset.filter;


                    searchProducts();

                }
            );

        });


    /* =====================================================
       WISHLIST
    ===================================================== */

    document
        .querySelectorAll('.wish-btn')
        .forEach(button => {

            button.addEventListener(
                'click',
                function(event) {

                    event.stopPropagation();

                    this.classList.toggle('active');

                    const icon =
                        this.querySelector('i');

                    if (
                        this.classList.contains('active')
                    ) {

                        icon.classList.remove(
                            'bi-heart'
                        );

                        icon.classList.add(
                            'bi-heart-fill'
                        );

                    } else {

                        icon.classList.remove(
                            'bi-heart-fill'
                        );

                        icon.classList.add(
                            'bi-heart'
                        );

                    }

                }
            );

        });


    /* =====================================================
       ADD TO CART
    ===================================================== */

    document
        .querySelectorAll('.btn-add-cart')
        .forEach(button => {

            button.addEventListener(
                'click',
                function(event) {

                    event.stopPropagation();

                    const productId =
                        this.dataset.product;


                    this.innerHTML =
                        '<i class="bi bi-check2"></i> Ditambahkan';

                    this.style.background =
                        '#2563eb';

                    this.style.color =
                        '#fff';


                    setTimeout(() => {

                        this.innerHTML =
                            '<i class="bi bi-cart-plus"></i> Tambah Keranjang';

                        this.style.background = '';

                        this.style.color = '';

                    }, 1200);

                }
            );

        });


    /* =====================================================
       PRODUCT DETAIL
    ===================================================== */

    document
        .querySelectorAll('.product-card')
        .forEach(card => {

            card.addEventListener(
                'click',
                function(event) {

                    if (
                        event.target.closest('.wish-btn') ||
                        event.target.closest('.btn-add-cart')
                    ) {
                        return;
                    }


                    const id =
                        this.querySelector('.btn-add-cart')
                            .dataset.product;


                    window.location.href =
                        "{{ url('/pembeli/produk') }}/" + id;

                }
            );

        });


    /* =====================================================
       USER DROPDOWN
    ===================================================== */

    const userMenu =
        document.getElementById('userMenu');

    const userChip =
        document.getElementById('userChip');


    userChip.addEventListener(
        'click',
        function(event) {

            event.stopPropagation();

            userMenu.classList.toggle('open');

        }
    );


    document.addEventListener(
        'click',
        function(event) {

            if (
                !userMenu.contains(event.target)
            ) {

                userMenu.classList.remove(
                    'open'
                );

            }

        }
    );


    /* =====================================================
       INITIAL
    ===================================================== */

    searchProducts();

</script>

</body>
</html>

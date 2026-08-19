<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wishlist Saya - Karyaku</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>

        :root {
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

            --shadow:
                0 8px 24px rgba(37, 99, 235, 0.08);

            --shadow-hover:
                0 16px 34px rgba(37, 99, 235, 0.16);

            --navbar-h: 68px;
        }


        * {
            box-sizing: border-box;
        }


        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }


        a {
            text-decoration: none;
        }


        /* =====================================================
           BACKGROUND
        ===================================================== */

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

            background:
                radial-gradient(
                    circle at 30% 30%,
                    var(--primary-soft),
                    transparent 70%
                );

            opacity: .5;

            animation:
                floatBlob 14s ease-in-out infinite;
        }


        .bg-decor span:nth-child(1) {
            width: 380px;
            height: 380px;
            top: -120px;
            right: -100px;
            animation-duration: 16s;
        }


        .bg-decor span:nth-child(2) {
            width: 260px;
            height: 260px;
            bottom: -80px;
            left: -60px;
            animation-duration: 20s;
            animation-delay: 2s;
        }


        @keyframes floatBlob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(20px, -30px) scale(1.08);
            }
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
            z-index: 1030;

            box-shadow:
                0 10px 30px rgba(20, 34, 92, 0.18);
        }


        .navbar-top {
            display: flex;
            align-items: center;
            gap: 18px;

            padding: 12px 28px;

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

            background: var(--white);
            color: var(--primary);

            border-radius: 11px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;
            font-weight: 700;
        }


        .brand-text h5 {
            margin: 0;
            font-weight: 700;
            font-size: 15.5px;
            color: var(--white);
            line-height: 1.1;
        }


        .brand-text small {
            color: rgba(255, 255, 255, .6);
            font-size: 10.5px;
        }


        .mobile-toggle {
            width: 40px;
            height: 40px;

            border-radius: 10px;

            background: rgba(255, 255, 255, .12);

            border: none;

            color: #fff;

            display: none;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        .nav-menu {
            display: flex;
            align-items: center;

            gap: 2px;

            flex: 1;
        }


        .nav-menu .nav-link {
            position: relative;

            display: flex;
            align-items: center;

            gap: 8px;

            color: rgba(255, 255, 255, .78);

            padding: 9px 14px;

            border-radius: 10px;

            font-size: 13.5px;
            font-weight: 500;

            white-space: nowrap;

            transition: all .2s ease;
        }


        .nav-menu .nav-link i {
            font-size: 16px;
        }


        .nav-menu .nav-link:hover {
            background: rgba(255, 255, 255, .1);
            color: var(--white);
        }


        .nav-menu .nav-link.active {
            background: rgba(255, 255, 255, .16);
            color: var(--white);

            font-weight: 600;
        }


        .nav-menu .nav-link.active::after {
            content: "";

            position: absolute;

            left: 14px;
            right: 14px;
            bottom: -1px;

            height: 2.5px;

            background: var(--coral);

            border-radius: 4px;
        }


        .badge-count {
            background: var(--coral);

            color: #fff;

            font-size: 10.5px;

            font-weight: 700;

            min-width: 17px;

            height: 17px;

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0 4px;
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

            background: var(--coral);

            color: #fff;

            border: none;

            padding: 10px 18px;

            border-radius: 10px;

            font-weight: 700;

            font-size: 13px;

            white-space: nowrap;
        }


        .btn-jual:hover {
            background: var(--coral-dark);
            color: #fff;
        }


        .icon-btn-light {
            width: 40px;
            height: 40px;

            border-radius: 12px;

            background: rgba(255, 255, 255, .12);

            border: none;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #fff;

            position: relative;
        }


        .icon-btn-light .dot {
            position: absolute;

            top: 4px;
            right: 4px;

            min-width: 16px;
            height: 16px;

            background: var(--coral);

            border-radius: 20px;

            border: 2px solid var(--primary-dark);

            font-size: 9.5px;

            font-weight: 700;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* =====================================================
           USER
        ===================================================== */

        .user-menu {
            position: relative;
            flex-shrink: 0;
        }


        .user-chip {
            display: flex;
            align-items: center;

            gap: 9px;

            background: rgba(255, 255, 255, .12);

            padding: 5px 12px 5px 5px;

            border-radius: 30px;

            border: none;

            cursor: pointer;
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

            color: #fff;
        }


        .user-chip .role {
            font-size: 10.5px;

            color: rgba(255, 255, 255, .65);
        }


        .user-dropdown {
            position: absolute;

            right: 0;

            top: calc(100% + 10px);

            width: 220px;

            background: #fff;

            border-radius: 14px;

            box-shadow: var(--shadow-hover);

            padding: 8px;

            opacity: 0;

            visibility: hidden;

            transform: translateY(-8px);

            transition: all .18s ease;

            z-index: 1040;
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

            padding: 10px 12px;

            border-radius: 10px;

            font-size: 13.5px;

            font-weight: 500;

            color: var(--text-dark);
        }


        .user-dropdown a:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }


        .dropdown-logout-btn {
            display: flex;
            align-items: center;

            gap: 10px;

            width: 100%;

            padding: 10px 12px;

            border-radius: 10px;

            border: none;

            background: transparent;

            font-size: 13.5px;

            font-weight: 500;

            font-family: 'Poppins', sans-serif;
        }


        .dropdown-logout-btn:hover {
            background: #fef2f2;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        .mobile-menu-panel {
            display: none;

            max-height: 0;

            overflow: hidden;

            background: var(--primary-darker);

            transition: max-height .28s ease;
        }


        .mobile-menu-panel.show {
            max-height: 640px;
        }


        .mobile-menu-panel .nav-link {
            display: flex;
            align-items: center;

            gap: 12px;

            color: rgba(255, 255, 255, .82);

            padding: 13px 22px;

            font-size: 14px;

            border-top: 1px solid rgba(255, 255, 255, .08);
        }


        .mobile-menu-panel .badge-count {
            margin-left: auto;
        }


        @media(max-width:992px) {

            .mobile-toggle {
                display: flex;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-panel {
                display: block;
            }

            .btn-jual span {
                display: none;
            }

        }


        @media(max-width:576px) {

            .navbar-top {
                padding: 10px 16px;
                gap: 10px;
            }

            .btn-jual {
                padding: 10px 12px;
            }

        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .main-content {
            padding-bottom: 50px;
        }


        .page-header-wrap {
            padding: 30px 28px 0;

            max-width: 1440px;

            margin: 0 auto;
        }


        .page-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 12px;

            background:
                linear-gradient(
                    120deg,
                    var(--primary-darker),
                    var(--primary-dark) 60%,
                    var(--primary)
                );

            border-radius: var(--radius);

            padding: 26px 32px;

            color: #fff;

            box-shadow: var(--shadow);

            position: relative;

            overflow: hidden;
        }


        .page-header h2 {
            font-weight: 800;

            font-size: 24px;

            margin: 0 0 6px;

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .page-header p {
            margin: 0;

            font-size: 13px;

            color: rgba(255, 255, 255, .8);
        }


        .count-pill {
            background: rgba(255, 255, 255, .15);

            padding: 10px 20px;

            border-radius: 14px;

            text-align: center;
        }


        .count-pill .num {
            font-size: 22px;

            font-weight: 800;

            line-height: 1;
        }


        .count-pill .lbl {
            font-size: 11px;

            color: rgba(255, 255, 255, .75);

            margin-top: 4px;
        }


        /* =====================================================
           PRODUCTS
        ===================================================== */

        .section-wrap {
            padding: 26px 28px 0;

            max-width: 1440px;

            margin: 0 auto;
        }


        .product-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 18px;
        }


        @media(max-width:1200px) {

            .product-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

        }


        @media(max-width:768px) {

            .product-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media(max-width:480px) {

            .product-grid {
                grid-template-columns:
                    repeat(2, 1fr);

                gap: 12px;
            }

        }


        .product-card {
            background: #fff;

            border-radius: 16px;

            overflow: hidden;

            border: 1px solid var(--border-color);

            box-shadow: var(--shadow);

            transition:
                transform .25s ease,
                box-shadow .25s ease;

            position: relative;

            display: flex;

            flex-direction: column;
        }


        .product-card:hover {
            transform: translateY(-6px);

            box-shadow: var(--shadow-hover);
        }


        .product-thumb {
            position: relative;

            height: 180px;

            overflow: hidden;
        }


        .product-thumb img {
            width: 100%;

            height: 100%;

            object-fit: cover;

            transition: transform .5s ease;
        }


        .product-card:hover
        .product-thumb img {
            transform: scale(1.08);
        }


        .cat-badge {
            position: absolute;

            top: 10px;
            left: 10px;

            background:
                rgba(20, 34, 92, .75);

            color: #fff;

            font-size: 10px;

            font-weight: 700;

            padding: 4px 10px;

            border-radius: 20px;
        }


        /* =====================================================
           LOVE BUTTON
        ===================================================== */

        .wish-remove-btn {

            position: absolute;

            top: 8px;
            right: 8px;

            width: 36px;
            height: 36px;

            border-radius: 50%;

            background:
                rgba(255, 255, 255, .95);

            border: none;

            display: flex;

            align-items: center;
            justify-content: center;

            color: var(--coral);

            font-size: 16px;

            cursor: pointer;

            z-index: 5;

            transition: all .2s ease;
        }


        .wish-remove-btn:hover {

            background: var(--coral);

            color: #fff;

            transform: scale(1.08);
        }


        .product-body {

            padding: 13px;

            display: flex;

            flex-direction: column;

            gap: 6px;

            flex: 1;
        }


        .product-body h6 {

            font-size: 13px;

            font-weight: 600;

            margin: 0;

            line-height: 1.35;

            min-height: 35px;
        }


        .product-price {

            color: var(--coral);

            font-weight: 800;

            font-size: 15px;
        }


        .product-meta {

            display: flex;

            align-items: center;

            justify-content: space-between;

            font-size: 11px;

            color: var(--text-muted);
        }


        .rating {

            color: #f59e0b;

            font-weight: 600;
        }


        .product-seller {

            display: flex;

            align-items: center;

            gap: 6px;

            font-size: 11px;

            color: var(--text-muted);
        }


        .product-seller img {

            width: 20px;

            height: 20px;

            border-radius: 50%;

            object-fit: cover;
        }


        .btn-add-cart {

            margin-top: 6px;

            width: 100%;

            border: none;

            background: var(--primary-light);

            color: var(--primary);

            font-weight: 700;

            font-size: 12px;

            padding: 9px 0;

            border-radius: 9px;

            transition: all .2s ease;
        }


        .btn-add-cart:hover {

            background: var(--primary);

            color: #fff;
        }


        .btn-hapus-wishlist {

            margin-top: 2px;

            width: 100%;

            border: 1px solid #fecaca;

            background: #fff;

            color: #ef4444;

            font-weight: 700;

            font-size: 12px;

            padding: 9px 0;

            border-radius: 9px;

            transition: all .2s ease;
        }


        .btn-hapus-wishlist:hover {

            background: #ef4444;

            color: #fff;

            border-color: #ef4444;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .empty-state {

            max-width: 1440px;

            margin: 30px auto 0;

            padding: 0 28px;
        }


        .empty-state .inner {

            background: #fff;

            border-radius: var(--radius);

            border: 1px solid var(--border-color);

            box-shadow: var(--shadow);

            text-align: center;

            padding: 70px 20px;
        }


        .icon-circle {

            width: 108px;

            height: 108px;

            border-radius: 50%;

            background: var(--primary-light);

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 0 auto 20px;

            font-size: 46px;

            color: var(--coral);

            border: 2px dashed var(--primary-soft);
        }


        .empty-state h4 {

            font-weight: 800;

            font-size: 19px;

            margin-bottom: 8px;
        }


        .empty-state p {

            color: var(--text-muted);

            font-size: 13.5px;

            max-width: 380px;

            margin: 0 auto 22px;
        }


        .btn-belanja {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            background: var(--coral);

            color: #fff;

            padding: 12px 26px;

            border-radius: 12px;

            font-weight: 700;

            font-size: 14px;
        }


        .btn-belanja:hover {

            background: var(--coral-dark);

            color: #fff;
        }


        .reveal {

            animation:
                fadeUp .5s ease both;
        }


        @keyframes fadeUp {

            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

    </style>
</head>


<body>

<div class="bg-decor">
    <span></span>
    <span></span>
</div>


{{-- =========================================================
     NAVBAR
========================================================= --}}

<header class="site-navbar">

    <div class="navbar-top">

        <button
            class="mobile-toggle"
            id="btnToggleMenu"
            type="button"
        >
            <i class="bi bi-list fs-5"></i>
        </button>


        <a
            href="{{ route('pembeli.dashboard') }}"
            class="brand"
        >

            <div class="brand-icon">
                <i class="bi bi-bag-check-fill"></i>
            </div>

            <div class="brand-text d-none d-sm-block">

                <h5>Karyaku</h5>

                <small>
                    Marketplace Pembeli
                </small>

            </div>

        </a>


        <nav class="nav-menu">

            <a
                href="{{ route('pembeli.dashboard') }}"
                class="nav-link"
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
                class="nav-link active"
            >

                <i class="bi bi-heart-fill"></i>

                Wishlist

                <span class="badge-count">
                    {{ $wishlist->count() }}
                </span>

            </a>


            <a
                href="{{ route('pembeli.keranjang') }}"
                class="nav-link"
            >

                <i class="bi bi-cart-fill"></i>

                Keranjang

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


        <div class="navbar-right">

            <a
                href="#"
                class="btn-jual d-none d-md-inline-flex"
            >

                <i class="bi bi-shop-window"></i>

                <span>
                    Daftar Sebagai Penjual
                </span>

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


            <div
                class="user-menu"
                id="userMenu"
            >

                <button
                    class="user-chip"
                    id="btnUserChip"
                    type="button"
                >

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=ffffff&color=1e3a8a"
                        alt="avatar"
                    >


                    <div class="d-none d-lg-block">

                        <div class="name">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>

                        <div class="role">
                            Pembeli
                        </div>

                    </div>


                    <i class="bi bi-chevron-down"></i>

                </button>


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
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-logout-btn text-danger"
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
        class="mobile-menu-panel"
        id="mobileMenuPanel"
    >

        <a
            href="{{ route('pembeli.dashboard') }}"
            class="nav-link"
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
            class="nav-link active"
        >

            <i class="bi bi-heart-fill"></i>

            Wishlist

            <span class="badge-count">
                {{ $wishlist->count() }}
            </span>

        </a>


        <a
            href="{{ route('pembeli.keranjang') }}"
            class="nav-link"
        >
            <i class="bi bi-cart-fill"></i>
            Keranjang
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


        <a
            href="{{ route('pembeli.profile') }}"
            class="nav-link"
        >
            <i class="bi bi-person-fill"></i>
            Profile
        </a>


        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="nav-link mobile-logout-btn logout-link"
            >

                <i class="bi bi-box-arrow-right"></i>

                Keluar

            </button>

        </form>

    </div>

</header>


{{-- =========================================================
     CONTENT
========================================================= --}}

<main class="main-content">


    {{-- HEADER --}}

    <div class="page-header-wrap">

        <div class="page-header">

            <div>

                <h2>

                    <i class="bi bi-heart-fill"></i>

                    Wishlist Saya

                </h2>


                <p>
                    Kumpulan karya digital favorit yang kamu simpan untuk dibeli nanti
                </p>

            </div>


            <div class="count-pill">

                <div
                    class="num"
                    id="wishlistCount"
                >
                    {{ $wishlist->count() }}
                </div>


                <div class="lbl">
                    Item Wishlist
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         WISHLIST
    ====================================================== --}}

    @if($wishlist->count() > 0)

        <div
            class="section-wrap"
            id="wishlistSection"
        >

            <div class="product-grid">

                @foreach($wishlist as $item)

                    @php
                        $product = $item->product;
                    @endphp


                    @if($product)

                        <div
                            class="product-card reveal"
                            data-id="{{ $product->id_product }}"
                        >

                            {{-- IMAGE --}}

                            <div class="product-thumb">

                                <span class="cat-badge">

                                    {{ $product->category->nama_kategori ?? 'Produk' }}

                                </span>


                                {{-- LOVE --}}
                                <form
                                    action="{{ route('pembeli.wishlist.destroy', $product->id_product) }}"
                                    method="POST"
                                    class="wishlist-delete-form"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="wish-remove-btn"
                                        title="Hapus dari Wishlist"
                                    >

                                        <i class="bi bi-heart-fill"></i>

                                    </button>

                                </form>


                                {{-- PRODUCT IMAGE --}}

                                @if($product->gambar)

                                    <img
                                        src="{{ asset('storage/' . $product->gambar) }}"
                                        alt="{{ $product->nama_produk }}"
                                        onerror="this.src='https://via.placeholder.com/500x350?text=No+Image';"
                                    >

                                @else

                                    <img
                                        src="https://via.placeholder.com/500x350?text=No+Image"
                                        alt="No Image"
                                    >

                                @endif

                            </div>


                            {{-- BODY --}}

                            <div class="product-body">

                                <h6>

                                    {{ $product->nama_produk }}

                                </h6>


                                <div class="product-price">

                                    Rp{{ number_format($product->harga, 0, ',', '.') }}

                                </div>


                                <div class="product-meta">

                                    <span class="rating">

                                        <i class="bi bi-star-fill"></i>

                                        {{ $product->rating ?? '0.0' }}

                                    </span>


                                    <span>

                                        Terjual
                                        {{ $product->terjual ?? 0 }}

                                    </span>

                                </div>


                                {{-- SELLER --}}

                                <div class="product-seller">

                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Seller') }}&background=dbeafe&color=1e3a8a"
                                        alt=""
                                    >


                                    {{ $product->seller->name ?? 'Penjual' }}

                                </div>


                                {{-- DETAIL --}}

                                <a
                                    href="{{ route('pembeli.produk.detail', $product->id_product) }}"
                                    class="btn-add-cart"
                                >

                                    <i class="bi bi-eye"></i>

                                    Lihat Produk

                                </a>


                                {{-- DELETE --}}

                                <form
                                    action="{{ route('pembeli.wishlist.destroy', $product->id_product) }}"
                                    method="POST"
                                    class="wishlist-delete-form"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="btn-hapus-wishlist"
                                    >

                                        <i class="bi bi-trash3"></i>

                                        Hapus dari Wishlist

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

        </div>

    @else

        {{-- =================================================
             EMPTY
        ================================================== --}}

        <div class="empty-state">

            <div class="inner">

                <div class="icon-circle">

                    <i class="bi bi-heart"></i>

                </div>


                <h4>
                    Wishlist Kamu Masih Kosong
                </h4>


                <p>

                    Belum ada karya digital yang kamu simpan.
                    Yuk jelajahi Marketplace dan temukan produk favoritmu!

                </p>


                <a
                    href="{{ route('pembeli.marketplace') }}"
                    class="btn-belanja"
                >

                    <i class="bi bi-shop"></i>

                    Belanja Sekarang

                </a>

            </div>

        </div>

    @endif

</main>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const btnToggleMenu =
        document.getElementById('btnToggleMenu');

    const mobileMenuPanel =
        document.getElementById('mobileMenuPanel');


    if (btnToggleMenu && mobileMenuPanel) {

        btnToggleMenu.addEventListener('click', function () {

            const isOpen =
                mobileMenuPanel.classList.toggle('show');


            btnToggleMenu.setAttribute(
                'aria-expanded',
                isOpen
            );


            btnToggleMenu.querySelector('i').className =
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


    if (btnUserChip && userMenu) {

        btnUserChip.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

                userMenu.classList.toggle('open');

            }
        );


        document.addEventListener(
            'click',
            function (event) {

                if (!userMenu.contains(event.target)) {

                    userMenu.classList.remove('open');

                }

            }
        );

    }


    /* =====================================================
       DELETE CONFIRMATION
    ===================================================== */

    document.querySelectorAll(
        '.wishlist-delete-form'
    ).forEach(function (form) {

        form.addEventListener(
            'submit',
            function (event) {

                const yakin = confirm(
                    'Hapus produk ini dari wishlist?'
                );


                if (!yakin) {

                    event.preventDefault();

                }

            }
        );

    });


</script>


</body>
</html>
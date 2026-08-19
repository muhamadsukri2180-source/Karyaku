<<<<<<< HEAD
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pesanan - Karyaku</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

            --coral: #ff7a59;
            --coral-dark: #f0623f;

            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;

            --border-color: #e5edff;

            --shadow: 0 8px 24px rgba(37, 99, 235, .08);
            --shadow-hover: 0 16px 34px rgba(37, 99, 235, .15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--primary-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* =========================================
           BACKGROUND
        ========================================= */

        .bg-decor {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-decor span {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(
                circle at 30% 30%,
                var(--primary-soft),
                transparent 70%
            );
            opacity: .5;
        }

        .bg-decor span:nth-child(1) {
            width: 380px;
            height: 380px;
            top: -120px;
            right: -100px;
        }

        .bg-decor span:nth-child(2) {
            width: 280px;
            height: 280px;
            bottom: -100px;
            left: -80px;
        }

        /* =========================================
           NAVBAR
        ========================================= */

        .site-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: linear-gradient(
                120deg,
                var(--primary-darker),
                var(--primary-dark) 60%,
                var(--primary)
            );
            box-shadow: 0 10px 30px rgba(20, 34, 92, .18);
        }

        .navbar-top {
            max-width: 1440px;
            margin: auto;
            padding: 10px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* BRAND */

        .brand {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-shrink: 0;
            color: #fff;
        }

        .brand:hover {
            color: #fff;
        }

        .brand-icon {
            width: 39px;
            height: 39px;
            border-radius: 10px;
            background: #fff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .brand-text h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
        }

        .brand-text small {
            color: rgba(255,255,255,.6);
            font-size: 10px;
        }

        /* NAV MENU */

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
            gap: 7px;
            padding: 8px 12px;
            border-radius: 9px;

            color: rgba(255,255,255,.75);
            font-size: 12.5px;
            font-weight: 500;

            white-space: nowrap;
            transition: .2s ease;
        }

        .nav-menu .nav-link i {
            font-size: 15px;
        }

        .nav-menu .nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }

        .nav-menu .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
            font-weight: 600;
        }

        .nav-menu .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 12px;
            right: 12px;
            height: 2px;
            border-radius: 10px;
            background: var(--coral);
        }

        .badge-count {
            min-width: 17px;
            height: 17px;
            padding: 0 4px;

            border-radius: 20px;
            background: var(--coral);

            color: #fff;
            font-size: 9px;
            font-weight: 700;

            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* RIGHT */

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-jual {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 8px 13px;
            border-radius: 9px;

            background: var(--coral);
            color: #fff;

            font-size: 11.5px;
            font-weight: 700;

            transition: .2s ease;
        }

        .btn-jual:hover {
            background: var(--coral-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .icon-btn-light {
            position: relative;

            width: 37px;
            height: 37px;

            border: 0;
            border-radius: 10px;

            background: rgba(255,255,255,.12);
            color: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 16px;
        }

        .icon-btn-light:hover {
            background: rgba(255,255,255,.2);
        }

        .icon-btn-light .dot {
            position: absolute;
            top: 3px;
            right: 3px;

            min-width: 15px;
            height: 15px;

            border-radius: 20px;
            border: 2px solid var(--primary-dark);

            background: var(--coral);

            color: #fff;
            font-size: 8px;
            font-weight: 700;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* USER */

        .user-menu {
            position: relative;
        }

        .user-chip {
            border: 0;
            display: flex;
            align-items: center;
            gap: 8px;

            padding: 4px 10px 4px 4px;
            border-radius: 30px;

            background: rgba(255,255,255,.12);
            cursor: pointer;
        }

        .user-chip:hover {
            background: rgba(255,255,255,.2);
        }

        .user-chip img {
            width: 29px;
            height: 29px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-name {
            color: #fff;
            font-size: 11.5px;
            font-weight: 600;
            line-height: 1.2;
        }

        .user-role {
            color: rgba(255,255,255,.6);
            font-size: 9.5px;
        }

        .user-chip i {
            color: rgba(255,255,255,.7);
            font-size: 10px;
        }

        /* DROPDOWN */

        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 9px);

            width: 215px;

            padding: 7px;

            background: #fff;
            border-radius: 13px;

            box-shadow: var(--shadow-hover);

            opacity: 0;
            visibility: hidden;
            transform: translateY(-7px);

            transition: .18s ease;

            z-index: 1050;
        }

        .user-menu.open .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown a,
        .dropdown-logout {
            width: 100%;

            display: flex;
            align-items: center;
            gap: 9px;

            padding: 9px 11px;

            border: 0;
            border-radius: 9px;

            background: transparent;

            color: var(--text-dark);

            font-family: inherit;
            font-size: 12.5px;
            font-weight: 500;

            text-align: left;
        }

        .user-dropdown a:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .dropdown-logout {
            color: #dc2626;
        }

        .dropdown-logout:hover {
            background: #fef2f2;
        }

        .user-dropdown hr {
            margin: 5px 4px;
            border-color: var(--border-color);
        }

        /* MOBILE */

        .mobile-toggle {
            display: none;

            width: 38px;
            height: 38px;

            border: 0;
            border-radius: 9px;

            background: rgba(255,255,255,.12);
            color: #fff;
        }

        .mobile-menu-panel {
            display: none;
            max-height: 0;
            overflow: hidden;

            background: var(--primary-darker);

            transition: max-height .25s ease;
        }

        .mobile-menu-panel.show {
            max-height: 600px;
        }

        .mobile-menu-panel .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;

            padding: 12px 20px;

            color: rgba(255,255,255,.8);

            font-size: 13px;

            border-top: 1px solid rgba(255,255,255,.08);
        }

        .mobile-menu-panel .nav-link.active {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        /* =========================================
           PAGE
        ========================================= */

        .page-container {
            max-width: 1180px;
            margin: auto;
            padding: 28px 24px 60px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            margin-bottom: 18px;

            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 600;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .page-title {
            margin-bottom: 20px;
        }

        .page-title h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }

        .page-title p {
            margin: 5px 0 0;
            color: var(--text-muted);
            font-size: 12.5px;
        }

        /* =========================================
           ORDER STATUS
        ========================================= */

        .status-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 17px;
            box-shadow: var(--shadow);

            padding: 20px 22px;
            margin-bottom: 18px;
        }

        .status-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            margin-bottom: 20px;
        }

        .status-title {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .status-icon {
            width: 42px;
            height: 42px;

            border-radius: 12px;

            background: var(--primary-light);
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
        }

        .status-title h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .status-title span {
            display: block;
            margin-top: 2px;
            color: var(--text-muted);
            font-size: 11px;
        }

        .status-badge {
            padding: 7px 12px;
            border-radius: 20px;

            background: #fff7ed;
            color: #ea580c;

            font-size: 11px;
            font-weight: 700;
        }

        /* TIMELINE */

        .timeline {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }

        .timeline-step {
            position: relative;
            text-align: center;
        }

        .timeline-step::after {
            content: "";
            position: absolute;

            top: 14px;
            left: 50%;
            width: 100%;
            height: 2px;

            background: #e2e8f0;

            z-index: 0;
        }

        .timeline-step:last-child::after {
            display: none;
        }

        .timeline-dot {
            position: relative;
            z-index: 1;

            width: 29px;
            height: 29px;

            margin: 0 auto 7px;

            border-radius: 50%;

            background: #e2e8f0;
            color: #94a3b8;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
        }

        .timeline-step.done .timeline-dot,
        .timeline-step.active .timeline-dot {
            background: var(--primary);
            color: #fff;
        }

        .timeline-step.done::after {
            background: var(--primary);
        }

        .timeline-step span {
            color: var(--text-muted);
            font-size: 10.5px;
            font-weight: 600;
        }

        .timeline-step.active span {
            color: var(--primary);
        }

        /* =========================================
           GRID
        ========================================= */

        .detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, .8fr);
            gap: 18px;
            align-items: start;
        }

        .card-box {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 17px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border-color);

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-custom h5 {
            margin: 0;

            font-size: 14px;
            font-weight: 700;
        }

        .card-header-custom span {
            color: var(--text-muted);
            font-size: 11px;
        }

        /* =========================================
           PRODUCT
        ========================================= */

        .product-item {
            padding: 18px 20px;

            display: flex;
            gap: 15px;
        }

        .product-image {
            width: 100px;
            height: 85px;

            flex-shrink: 0;

            border-radius: 12px;

            overflow: hidden;

            background: var(--primary-light);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-info h6 {
            margin: 2px 0 6px;

            font-size: 13.5px;
            font-weight: 700;
        }

        .seller {
            display: flex;
            align-items: center;
            gap: 5px;

            color: var(--text-muted);
            font-size: 11px;
        }

        .seller i {
            color: var(--primary);
        }

        .product-qty {
            margin-top: 7px;
            color: var(--text-muted);
            font-size: 11px;
        }

        .product-price {
            min-width: 120px;
            text-align: right;
        }

        .product-price small {
            display: block;
            color: var(--text-muted);
            font-size: 10px;
        }

        .product-price strong {
            display: block;
            margin-top: 3px;

            color: var(--coral);
            font-size: 14px;
            font-weight: 800;
        }

        /* =========================================
           INFO
        ========================================= */

        .info-body {
            padding: 18px 20px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;

            padding: 10px 0;

            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .info-icon {
            width: 31px;
            height: 31px;

            flex-shrink: 0;

            border-radius: 9px;

            background: var(--primary-light);
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
        }

        .info-content small {
            display: block;

            margin-bottom: 2px;

            color: var(--text-muted);
            font-size: 9.5px;
        }

        .info-content strong {
            display: block;

            color: var(--text-dark);
            font-size: 11.5px;
            font-weight: 600;
        }

        /* =========================================
           PAYMENT
        ========================================= */

        .payment-body {
            padding: 18px 20px;
        }

        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 10px;

            color: var(--text-muted);
            font-size: 11.5px;
        }

        .price-row.total {
            margin-top: 13px;
            padding-top: 14px;

            border-top: 1px dashed #cbd5e1;

            color: var(--text-dark);
            font-size: 13px;
            font-weight: 700;
        }

        .price-row.total strong {
            color: var(--coral);
            font-size: 16px;
        }

        /* =========================================
           ACTION
        ========================================= */

        .action-box {
            margin-top: 18px;

            display: flex;
            gap: 9px;
            flex-wrap: wrap;
        }

        .btn-primary-custom,
        .btn-outline-custom {
            border: 0;
            border-radius: 10px;

            padding: 10px 16px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            font-family: inherit;
            font-size: 11.5px;
            font-weight: 700;

            transition: .2s ease;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-dark);
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-soft);
        }

        /* =========================================
           NOTE
        ========================================= */

        .note-box {
            margin-top: 18px;

            padding: 14px 16px;

            border-radius: 12px;

            background: #fffbeb;
            border: 1px solid #fde68a;

            color: #92400e;

            font-size: 10.5px;
            line-height: 1.6;
        }

        .note-box i {
            margin-right: 5px;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1100px) {
            .nav-menu .nav-link {
                padding-left: 8px;
                padding-right: 8px;
                font-size: 11.5px;
            }

            .btn-jual span {
                display: none;
            }

            .btn-jual {
                padding: 9px 11px;
            }
        }

        @media (max-width: 900px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-panel {
                display: block;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .navbar-top {
                padding: 9px 15px;
                gap: 8px;
            }

            .brand-text {
                display: none;
            }

            .navbar-right {
                margin-left: auto;
            }

            .page-container {
                padding: 20px 14px 45px;
            }

            .page-title h2 {
                font-size: 20px;
            }

            .status-card {
                padding: 16px;
            }

            .timeline-step span {
                font-size: 9px;
            }

            .product-item {
                padding: 15px;
                gap: 11px;
            }

            .product-image {
                width: 75px;
                height: 70px;
            }

            .product-info h6 {
                font-size: 12px;
            }

            .product-price {
                min-width: 85px;
            }

            .product-price strong {
                font-size: 12px;
            }

            .status-head {
                align-items: flex-start;
                flex-direction: column;
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
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <i class="bi bi-list"></i>
        </button>


        {{-- BRAND --}}

        <a href="{{ route('pembeli.dashboard') }}" class="brand">

            <div class="brand-icon">
                <i class="bi bi-bag-check-fill"></i>
            </div>

            <div class="brand-text">
                <h5>Karyaku</h5>
                <small>Marketplace Pembeli</small>
            </div>

        </a>


        {{-- NAVIGATION --}}

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
                class="nav-link"
            >
                <i class="bi bi-heart-fill"></i>
                Wishlist
                <span class="badge-count">5</span>
            </a>

            <a
                href="{{ route('pembeli.keranjang') }}"
                class="nav-link"
            >
                <i class="bi bi-cart-fill"></i>
                Keranjang
                <span class="badge-count">3</span>
            </a>

            <a
                href="{{ route('pembeli.pesanan') }}"
                class="nav-link active"
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


        {{-- RIGHT NAV --}}

        <div class="navbar-right">

            <a
                href="#"
                class="btn-jual d-none d-md-inline-flex"
            >
                <i class="bi bi-shop-window"></i>
                <span>Daftar Sebagai Penjual</span>
            </a>


            <button
                type="button"
                class="icon-btn-light"
                title="Notifikasi"
            >
                <i class="bi bi-bell"></i>
                <span class="dot">2</span>
            </button>


            {{-- USER --}}

            <div class="user-menu" id="userMenu">

                <button
                    type="button"
                    class="user-chip"
                    id="btnUserChip"
                >

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
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="dropdown-logout"
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
            class="nav-link"
        >
            <i class="bi bi-heart-fill"></i>
            Wishlist
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
            class="nav-link active"
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

    </div>

</header>


{{-- =========================================================
     MAIN
========================================================= --}}

<main class="page-container">


    {{-- BACK --}}

    <a
        href="{{ route('pembeli.pesanan') }}"
        class="back-link"
    >
        <i class="bi bi-arrow-left"></i>
        Kembali ke Pesanan
    </a>


    {{-- TITLE --}}

    <div class="page-title">

        <h2>Detail Pesanan</h2>

        <p>
            Informasi lengkap mengenai pesanan kamu.
        </p>

    </div>


    {{-- =====================================================
         STATUS PESANAN
    ====================================================== --}}

    <div class="status-card">

        <div class="status-head">

            <div class="status-title">

                <div class="status-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>

                    <h5>
                        Pesanan #ORD-20260801-001
                    </h5>

                    <span>
                        Dibuat pada 1 Agustus 2026, 10:24
                    </span>

                </div>

            </div>


            <div class="status-badge">
                <i class="bi bi-hourglass-split"></i>
                Menunggu Pembayaran
            </div>

        </div>


        {{-- TIMELINE --}}

        <div class="timeline">

            <div class="timeline-step done">

                <div class="timeline-dot">
                    <i class="bi bi-check"></i>
                </div>

                <span>
                    Pesanan Dibuat
                </span>

            </div>


            <div class="timeline-step active">

                <div class="timeline-dot">
                    <i class="bi bi-wallet2"></i>
                </div>

                <span>
                    Pembayaran
                </span>

            </div>


            <div class="timeline-step">

                <div class="timeline-dot">
                    <i class="bi bi-box"></i>
                </div>

                <span>
                    Diproses
                </span>

            </div>


            <div class="timeline-step">

                <div class="timeline-dot">
                    <i class="bi bi-check-lg"></i>
                </div>

                <span>
                    Selesai
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DETAIL GRID
    ====================================================== --}}

    <div class="detail-grid">


        {{-- LEFT --}}

        <div>


            {{-- PRODUK --}}

            <div class="card-box">

                <div class="card-header-custom">

                    <h5>
                        <i class="bi bi-bag-check me-1"></i>
                        Produk Pesanan
                    </h5>

                    <span>
                        1 item
                    </span>

                </div>


                <div class="product-item">

                    <div class="product-image">

                        <img
                            src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=500&q=80"
                            alt="Desain Poster Promosi"
                        >

                    </div>


                    <div class="product-info">

                        <h6>
                            Desain Poster Promosi Kafe & Resto
                        </h6>

                        <div class="seller">
                            <i class="bi bi-shop"></i>
                            Dinda Studio
                        </div>

                        <div class="product-qty">
                            Jumlah: 1 paket
                        </div>

                    </div>


                    <div class="product-price">

                        <small>
                            Harga
                        </small>

                        <strong>
                            Rp75.000
                        </strong>

                    </div>

                </div>

            </div>


            {{-- ALAMAT --}}

            <div class="card-box mt-3">

                <div class="card-header-custom">

                    <h5>
                        <i class="bi bi-person-vcard me-1"></i>
                        Informasi Pembeli
                    </h5>

                </div>


                <div class="info-body">

                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-person"></i>
                        </div>

                        <div class="info-content">

                            <small>
                                Nama Pembeli
                            </small>

                            <strong>
                                {{ Auth::user()->name ?? 'Pembeli' }}
                            </strong>

                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>

                        <div class="info-content">

                            <small>
                                Email
                            </small>

                            <strong>
                                {{ Auth::user()->email ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>

                        <div class="info-content">

                            <small>
                                Nomor Telepon
                            </small>

                            <strong>
                                {{ Auth::user()->phone ?? 'Belum diatur' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- CATATAN --}}

            <div class="note-box">

                <i class="bi bi-info-circle-fill"></i>

                Pastikan pembayaran dilakukan sesuai nominal yang tertera.
                Setelah pembayaran dikonfirmasi, pesanan akan diteruskan
                kepada kreator untuk diproses.

            </div>

        </div>


        {{-- RIGHT --}}

        <div>


            {{-- RINGKASAN PEMBAYARAN --}}

            <div class="card-box">

                <div class="card-header-custom">

                    <h5>
                        Ringkasan Pembayaran
                    </h5>

                </div>


                <div class="payment-body">

                    <div class="price-row">

                        <span>
                            Harga Produk
                        </span>

                        <span>
                            Rp75.000
                        </span>

                    </div>


                    <div class="price-row">

                        <span>
                            Biaya Layanan
                        </span>

                        <span>
                            Rp5.000
                        </span>

                    </div>


                    <div class="price-row">

                        <span>
                            Diskon
                        </span>

                        <span>
                            - Rp0
                        </span>

                    </div>


                    <div class="price-row total">

                        <span>
                            Total Pembayaran
                        </span>

                        <strong>
                            Rp80.000
                        </strong>

                    </div>

                </div>

            </div>


            {{-- METODE --}}

            <div class="card-box mt-3">

                <div class="card-header-custom">

                    <h5>
                        Metode Pembayaran
                    </h5>

                </div>


                <div class="info-body">

                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>

                        <div class="info-content">

                            <small>
                                Metode
                            </small>

                            <strong>
                                Transfer Bank
                            </strong>

                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div class="info-content">

                            <small>
                                Batas Pembayaran
                            </small>

                            <strong>
                                1 Agustus 2026, 12:24
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ACTION --}}

            <div class="action-box">

                <button
                    type="button"
                    class="btn-primary-custom"
                    id="btnPay"
                >
                    <i class="bi bi-credit-card"></i>
                    Bayar Sekarang
                </button>

                <a
                    href="{{ route('pembeli.pesanan') }}"
                    class="btn-outline-custom"
                >
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

            </div>

        </div>

    </div>

</main>


<script>

    /* =========================================
       USER DROPDOWN
    ========================================= */

    const userMenu = document.getElementById('userMenu');
    const btnUserChip = document.getElementById('btnUserChip');

    if (userMenu && btnUserChip) {

        btnUserChip.addEventListener('click', function (event) {

            event.stopPropagation();

            userMenu.classList.toggle('open');

        });

        document.addEventListener('click', function (event) {

            if (!userMenu.contains(event.target)) {

                userMenu.classList.remove('open');

            }

        });

        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                userMenu.classList.remove('open');

            }

        });

    }


    /* =========================================
       MOBILE MENU
    ========================================= */

    const btnToggleMenu =
        document.getElementById('btnToggleMenu');

    const mobileMenuPanel =
        document.getElementById('mobileMenuPanel');

    if (btnToggleMenu && mobileMenuPanel) {

        btnToggleMenu.addEventListener('click', function () {

            const open =
                mobileMenuPanel.classList.toggle('show');

            btnToggleMenu.setAttribute(
                'aria-expanded',
                open ? 'true' : 'false'
            );

            const icon =
                btnToggleMenu.querySelector('i');

            if (icon) {

                icon.className =
                    open
                        ? 'bi bi-x-lg'
                        : 'bi bi-list';

            }

        });

    }


    /* =========================================
       BUTTON BAYAR
    ========================================= */

    const btnPay =
        document.getElementById('btnPay');

    if (btnPay) {

        btnPay.addEventListener('click', function () {

            const original =
                btnPay.innerHTML;

            btnPay.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Memproses...';

            btnPay.disabled = true;

            setTimeout(function () {

                btnPay.innerHTML =
                    '<i class="bi bi-check-circle"></i> Pembayaran Diproses';

            }, 1000);

        });

    }

</script>

</body>
</html>
```
=======
@extends('layouts.pembeli')
@section('title', 'Detail Pesanan')

@section('content')

<a href="{{ route('pembeli.pesanan') }}" class="text-decoration-none small text-muted d-inline-block mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Pesanan</a>

@php
    $statusColor = match($order->status) {
        'selesai' => 'bg-success-subtle text-success',
        'dibatalkan' => 'bg-danger-subtle text-danger',
        default => 'bg-warning-subtle text-warning',
    };
    $payColor = $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
@endphp

<div class="card-box p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h5 class="fw-bold mb-1">{{ $order->kode_order }}</h5>
            <div class="text-muted small">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge-status {{ $statusColor }}">{{ ucfirst($order->status) }}</span>
            <span class="badge-status {{ $payColor }}">{{ ucfirst($order->payment_status) }}</span>
        </div>
    </div>
</div>

<div class="card-box p-3 mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kreator</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td class="small fw-semibold">{{ $item->product->title ?? 'Produk telah dihapus' }}</td>
                    <td class="small">{{ $item->product->seller->name ?? '-' }}</td>
                    <td class="small">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="small">{{ $item->quantity }}</td>
                    <td class="fw-bold small" style="color:var(--coral);">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="fw-bold" style="color:var(--coral);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@if ($order->payment_status === 'paid')
<div class="card-box p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="text-success small"><i class="bi bi-check-circle-fill"></i> Pesanan ini sudah dibayar. Karya bisa diunduh di halaman Download.</div>
    <a href="{{ route('pembeli.download') }}" class="btn btn-sm btn-primary">Ke Halaman Download</a>
</div>
@else
<div class="card-box p-4 text-warning small">
    <i class="bi bi-exclamation-circle-fill"></i> Pesanan ini belum dibayar. Silakan selesaikan pembayaran sesuai instruksi dari admin.
</div>
@endif

@endsection
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895

<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Keranjang Saya - Karyaku</title>

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

            --border: #e5edff;

            --shadow: 0 8px 24px rgba(37, 99, 235, .08);
            --shadow-hover: 0 14px 30px rgba(37, 99, 235, .14);
        }


        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
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

            background: radial-gradient(
                circle at 30% 30%,
                var(--primary-soft),
                transparent 70%
            );

            opacity: .5;
        }


        .bg-decor span:nth-child(1) {
            width: 350px;
            height: 350px;

            right: -120px;
            top: -120px;
        }


        .bg-decor span:nth-child(2) {
            width: 260px;
            height: 260px;

            left: -100px;
            bottom: -100px;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .site-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;

            background: linear-gradient(
                120deg,
                var(--primary-darker),
                var(--primary-dark) 65%,
                var(--primary)
            );

            box-shadow: 0 8px 24px rgba(20, 34, 92, .18);
        }


        .navbar-inner {
            width: 100%;
            max-width: 1320px;

            min-height: 72px;

            margin: auto;

            display: flex;
            align-items: center;

            gap: 14px;

            padding: 10px 24px;
        }


        /* =====================================================
           MOBILE TOGGLE
        ===================================================== */

        .mobile-toggle {
            display: none;

            width: 40px;
            height: 40px;

            border: 0;
            border-radius: 10px;

            background: rgba(255,255,255,.12);

            color: #fff;

            align-items: center;
            justify-content: center;

            cursor: pointer;
        }


        .mobile-toggle:hover {
            background: rgba(255,255,255,.2);
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .brand {
            display: flex;
            align-items: center;

            gap: 10px;

            flex-shrink: 0;
        }


        .brand-icon {
            width: 42px;
            height: 42px;

            border-radius: 11px;

            background: #fff;
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;

            box-shadow: 0 4px 10px rgba(0,0,0,.08);
        }


        .brand-text h5 {
            margin: 0;

            color: #fff;

            font-size: 16px;
            font-weight: 700;

            line-height: 1.1;
        }


        .brand-text small {
            color: rgba(255,255,255,.65);

            font-size: 9.5px;
        }


        /* =====================================================
           NAV MENU
        ===================================================== */

        .nav-menu {
            display: flex;
            align-items: center;

            gap: 4px;

            flex: 1;

            min-width: 0;
        }


        .nav-menu .nav-link {
            position: relative;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 10px 12px;

            border-radius: 10px;

            color: rgba(255,255,255,.78);

            font-size: 12px;
            font-weight: 500;

            white-space: nowrap;

            transition: .2s;
        }


        .nav-menu .nav-link i {
            font-size: 15px;
        }


        .nav-menu .nav-link:hover {
            background: rgba(255,255,255,.10);
            color: #fff;
        }


        .nav-menu .nav-link.active {
            background: rgba(255,255,255,.16);

            color: #fff;

            font-weight: 600;
        }


        .nav-menu .nav-link.active::after {
            content: "";

            position: absolute;

            left: 12px;
            right: 12px;

            bottom: 2px;

            height: 2px;

            background: var(--coral);

            border-radius: 5px;
        }


        /* =====================================================
           BADGE
        ===================================================== */

        .badge-count {
            min-width: 18px;
            height: 18px;

            padding: 0 5px;

            border-radius: 20px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            background: var(--coral);

            color: #fff;

            font-size: 9px;
            font-weight: 700;
        }


        /* =====================================================
           NAVBAR RIGHT
        ===================================================== */

        .navbar-right {
            display: flex;
            align-items: center;

            gap: 8px;

            flex-shrink: 0;
        }


        /* =====================================================
           SELLER BUTTON
        ===================================================== */

        .btn-jual {
            display: inline-flex;
            align-items: center;

            gap: 7px;

            padding: 10px 14px;

            background: var(--coral);
            color: #fff;

            border-radius: 10px;

            font-size: 11px;
            font-weight: 700;

            white-space: nowrap;

            transition: .2s;
        }


        .btn-jual:hover {
            background: var(--coral-dark);
            color: #fff;

            transform: translateY(-1px);
        }


        /* =====================================================
           ICON BUTTON
        ===================================================== */

        .icon-btn {
            position: relative;

            width: 40px;
            height: 40px;

            border: 0;
            border-radius: 10px;

            background: rgba(255,255,255,.12);

            color: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: .2s;
        }


        .icon-btn:hover {
            background: rgba(255,255,255,.20);
        }


        .icon-btn i {
            font-size: 16px;
        }


        .icon-btn .dot {
            position: absolute;

            top: 3px;
            right: 3px;

            min-width: 16px;
            height: 16px;

            padding: 0 4px;

            border-radius: 20px;

            background: var(--coral);

            color: #fff;

            border: 1px solid var(--primary-dark);

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
            border: 0;

            display: flex;
            align-items: center;

            gap: 8px;

            background: rgba(255,255,255,.10);

            padding: 5px 10px 5px 5px;

            border-radius: 30px;

            cursor: pointer;

            transition: .2s;
        }


        .user-chip:hover {
            background: rgba(255,255,255,.18);
        }


        .user-chip img {
            width: 34px;
            height: 34px;

            border-radius: 50%;

            object-fit: cover;

            background: #fff;
        }


        .user-info {
            text-align: left;
        }


        .user-info .name {
            color: #fff;

            font-size: 11px;
            font-weight: 600;

            line-height: 1.2;

            max-width: 120px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        .user-info .role {
            color: rgba(255,255,255,.62);

            font-size: 9px;
        }


        .user-chip > i {
            color: rgba(255,255,255,.75);

            font-size: 10px;
        }


        /* =====================================================
           USER DROPDOWN
        ===================================================== */

        .user-dropdown {
            position: absolute;

            right: 0;
            top: calc(100% + 10px);

            width: 220px;

            padding: 8px;

            background: #fff;

            border: 1px solid var(--border);

            border-radius: 13px;

            box-shadow: var(--shadow-hover);

            opacity: 0;
            visibility: hidden;

            transform: translateY(-8px);

            transition: .18s;
        }


        .user-menu.open .user-dropdown {
            opacity: 1;
            visibility: visible;

            transform: translateY(0);
        }


        .user-dropdown a,
        .logout-btn {
            width: 100%;

            display: flex;
            align-items: center;

            gap: 10px;

            padding: 10px 11px;

            border: 0;

            background: transparent;

            border-radius: 9px;

            color: var(--text-dark);

            font-family: 'Poppins', sans-serif;

            font-size: 12px;

            text-align: left;

            cursor: pointer;
        }


        .user-dropdown a:hover {
            background: var(--primary-light);

            color: var(--primary-dark);
        }


        .logout-btn {
            color: #dc2626;
        }


        .logout-btn:hover {
            background: #fef2f2;
        }


        .user-dropdown hr {
            margin: 5px 3px;

            border-color: var(--border);
        }


        /* =====================================================
           MOBILE MENU
        ===================================================== */

        .mobile-menu {
            display: none;

            max-height: 0;

            overflow: hidden;

            background: var(--primary-darker);

            transition: max-height .25s ease;
        }


        .mobile-menu.show {
            max-height: 700px;
        }


        .mobile-menu a,
        .mobile-menu button {
            width: 100%;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 13px 20px;

            color: rgba(255,255,255,.82);

            background: transparent;

            border: 0;
            border-top: 1px solid rgba(255,255,255,.08);

            font-family: 'Poppins', sans-serif;

            font-size: 13px;

            text-align: left;

            cursor: pointer;
        }


        .mobile-menu a:hover,
        .mobile-menu a.active {
            background: rgba(255,255,255,.08);

            color: #fff;
        }


        .mobile-menu .mobile-logout {
            color: #fecaca;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .page-container {
            width: 100%;

            max-width: 1200px;

            margin: 0 auto;

            padding: 30px 20px 60px;
        }


        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }


        .page-header h2 {
            margin: 0;

            font-size: 24px;
            font-weight: 800;

            color: var(--text-dark);
        }


        .page-header p {
            margin: 5px 0 0;

            color: var(--text-muted);

            font-size: 12px;
        }


        .back-market {
            display: inline-flex;
            align-items: center;

            gap: 7px;

            padding: 9px 13px;

            background: #fff;

            border: 1px solid var(--border);

            border-radius: 10px;

            color: var(--primary);

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;

            transition: .2s;
        }


        .back-market:hover {
            background: var(--primary);
            color: #fff;
        }


        /* =====================================================
           CART LAYOUT
        ===================================================== */

        .cart-layout {
            display: grid;

            grid-template-columns: minmax(0,1fr) 330px;

            gap: 20px;

            align-items: start;
        }


        /* =====================================================
           CART BOX
        ===================================================== */

        .cart-box {
            background: #fff;

            border: 1px solid var(--border);

            border-radius: 16px;

            box-shadow: var(--shadow);

            overflow: hidden;
        }


        .cart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 17px 19px;

            border-bottom: 1px solid var(--border);
        }


        .cart-header h5 {
            margin: 0;

            font-size: 14px;
            font-weight: 700;
        }


        .cart-header h5 i {
            color: var(--primary);
        }


        .select-all {
            display: flex;
            align-items: center;

            gap: 7px;

            color: var(--text-muted);

            font-size: 11px;

            cursor: pointer;
        }


        .select-all input {
            accent-color: var(--primary);

            cursor: pointer;
        }


        /* =====================================================
           CART ITEM
        ===================================================== */

        .cart-item {
            display: grid;

            grid-template-columns:
                22px
                86px
                minmax(0,1fr)
                auto
                34px;

            gap: 13px;

            align-items: center;

            padding: 15px 19px;

            border-bottom: 1px solid var(--border);

            transition: .2s;
        }


        .cart-item:last-child {
            border-bottom: 0;
        }


        .cart-item:hover {
            background: #fafcff;
        }


        .cart-check {
            width: 16px;
            height: 16px;

            accent-color: var(--primary);

            cursor: pointer;
        }


        .cart-image {
            width: 86px;
            height: 74px;

            border-radius: 10px;

            overflow: hidden;

            background: var(--primary-light);
        }


        .cart-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .cart-info {
            min-width: 0;
        }


        .cart-info h6 {
            margin: 0 0 5px;

            font-size: 12.5px;
            font-weight: 700;

            line-height: 1.4;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .seller {
            display: flex;
            align-items: center;

            gap: 5px;

            color: var(--text-muted);

            font-size: 10px;
        }


        .seller i {
            color: var(--primary);
        }


        .cart-price {
            margin-top: 7px;

            color: var(--coral);

            font-size: 13px;
            font-weight: 800;
        }


        /* =====================================================
           QUANTITY
        ===================================================== */

        .qty-form {
            display: flex;
            align-items: center;

            border: 1px solid var(--border);

            border-radius: 8px;

            overflow: hidden;

            background: #fff;
        }


        .qty-form button {
            width: 29px;
            height: 30px;

            border: 0;

            background: #f8faff;

            color: var(--primary);

            font-weight: 700;

            cursor: pointer;

            transition: .2s;
        }


        .qty-form button:hover:not(:disabled) {
            background: var(--primary-soft);
        }


        .qty-form button:disabled {
            opacity: .4;

            cursor: not-allowed;
        }


        .qty-form span {
            min-width: 31px;

            text-align: center;

            font-size: 11px;
            font-weight: 600;
        }


        /* =====================================================
           DELETE
        ===================================================== */

        .delete-btn {
            width: 32px;
            height: 32px;

            border: 0;

            border-radius: 8px;

            background: #fef2f2;

            color: #ef4444;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: .2s;
        }


        .delete-btn:hover {
            background: #fee2e2;

            transform: scale(1.05);
        }


        /* =====================================================
           EMPTY CART
        ===================================================== */

        .empty-cart {
            text-align: center;

            padding: 70px 25px;
        }


        .empty-cart > i {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 75px;
            height: 75px;

            margin: 0 auto 16px;

            border-radius: 50%;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 32px;
        }


        .empty-cart h4 {
            margin: 0 0 7px;

            font-size: 18px;
            font-weight: 700;
        }


        .empty-cart p {
            max-width: 430px;

            margin: 0 auto 18px;

            color: var(--text-muted);

            font-size: 11.5px;
        }


        .btn-marketplace {
            display: inline-flex;
            align-items: center;

            gap: 7px;

            padding: 10px 16px;

            background: var(--primary);

            color: #fff;

            border-radius: 9px;

            font-size: 11.5px;
            font-weight: 700;
        }


        .btn-marketplace:hover {
            background: var(--primary-dark);

            color: #fff;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary-box {
            background: #fff;

            border: 1px solid var(--border);

            border-radius: 16px;

            box-shadow: var(--shadow);

            padding: 19px;

            position: sticky;

            top: 90px;
        }


        .summary-box h5 {
            margin: 0 0 17px;

            font-size: 14px;
            font-weight: 700;
        }


        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 11px;

            color: var(--text-muted);

            font-size: 11.5px;
        }


        .summary-row strong {
            color: var(--text-dark);
        }


        .summary-divider {
            border: 0;

            border-top: 1px solid var(--border);

            margin: 14px 0;
        }


        .summary-total {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 16px;
        }


        .summary-total span {
            font-size: 12px;
            font-weight: 600;
        }


        .summary-total strong {
            color: var(--coral);

            font-size: 18px;
            font-weight: 800;
        }


        /* =====================================================
           CHECKOUT
        ===================================================== */

        .checkout-btn {
            width: 100%;

            border: 0;

            padding: 12px;

            border-radius: 10px;

            background: var(--primary);

            color: #fff;

            font-family: 'Poppins', sans-serif;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;

            transition: .2s;
        }


        .checkout-btn:hover:not(:disabled) {
            background: var(--primary-dark);

            transform: translateY(-1px);
        }


        .checkout-btn:disabled {
            opacity: .5;

            cursor: not-allowed;
        }


        /* =====================================================
           PROMO
        ===================================================== */

        .promo-box {
            margin-top: 15px;

            padding: 14px;

            background: #fff;

            border: 1px solid var(--border);

            border-radius: 13px;

            box-shadow: var(--shadow);
        }


        .promo-title {
            display: flex;
            align-items: center;

            gap: 7px;

            margin-bottom: 9px;

            font-size: 11px;
            font-weight: 700;
        }


        .promo-form {
            display: flex;

            gap: 7px;
        }


        .promo-form input {
            min-width: 0;

            flex: 1;

            border: 1px solid var(--border);

            border-radius: 7px;

            padding: 9px;

            outline: none;

            font-family: 'Poppins', sans-serif;

            font-size: 10px;
        }


        .promo-form input:focus {
            border-color: var(--primary);
        }


        .promo-form button {
            border: 0;

            padding: 8px 11px;

            border-radius: 7px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 10px;
            font-weight: 700;

            cursor: pointer;
        }


        .promo-form button:hover {
            background: var(--primary);

            color: #fff;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1150px) {

            .navbar-inner {
                padding-left: 16px;
                padding-right: 16px;
            }


            .nav-menu {
                gap: 1px;
            }


            .nav-menu .nav-link {
                padding: 9px 8px;

                font-size: 10.5px;
            }


            .nav-menu .nav-link i {
                font-size: 13px;
            }


            .btn-jual {
                padding: 9px 10px;
            }


            .btn-jual span {
                display: none;
            }
        }


        @media (max-width: 900px) {

            .nav-menu {
                display: none;
            }


            .mobile-toggle {
                display: flex;
            }


            .mobile-menu {
                display: block;
            }


            .cart-layout {
                grid-template-columns: 1fr;
            }


            .summary-box {
                position: static;
            }
        }


        @media (max-width: 650px) {

            .navbar-inner {
                min-height: 62px;

                padding: 9px 12px;
            }


            .brand-icon {
                width: 38px;
                height: 38px;

                font-size: 17px;
            }


            .brand-text h5 {
                font-size: 14px;
            }


            .brand-text small {
                display: none;
            }


            .navbar-right {
                margin-left: auto;

                gap: 5px;
            }


            .btn-jual {
                display: none;
            }


            .icon-btn {
                width: 37px;
                height: 37px;
            }


            .user-chip {
                padding: 3px;
            }


            .user-chip img {
                width: 35px;
                height: 35px;
            }


            .user-info {
                display: none;
            }


            .user-chip > i {
                display: none;
            }


            .page-container {
                padding: 22px 12px 45px;
            }


            .page-header {
                align-items: flex-start;
            }


            .page-header h2 {
                font-size: 19px;
            }


            .page-header p {
                font-size: 10px;
            }


            .back-market {
                padding: 8px 9px;

                font-size: 10px;
            }


            .cart-item {
                grid-template-columns:
                    20px
                    68px
                    minmax(0,1fr)
                    32px;

                gap: 8px;

                padding: 13px 12px;
            }


            .cart-image {
                width: 68px;
                height: 62px;
            }


            .cart-info h6 {
                font-size: 11px;
            }


            .seller {
                font-size: 8.5px;
            }


            .cart-price {
                font-size: 11px;
            }


            .qty-form {
                grid-column: 3;

                justify-self: start;

                margin-top: 5px;
            }


            .delete-btn {
                grid-column: 4;

                grid-row: 1;
            }
        }


        @media (max-width: 400px) {

            .brand-text {
                display: none;
            }


            .mobile-toggle {
                width: 36px;
                height: 36px;
            }


            .icon-btn {
                width: 34px;
                height: 34px;
            }


            .user-chip img {
                width: 33px;
                height: 33px;
            }


            .page-header {
                flex-direction: column;
            }


            .back-market {
                align-self: flex-start;
            }


            .cart-header {
                padding: 13px;
            }


            .cart-header h5 {
                font-size: 12px;
            }
        }

    </style>
</head>


<body>


{{-- =====================================================
     BACKGROUND
===================================================== --}}

<div class="bg-decor">
    <span></span>
    <span></span>
</div>



{{-- =====================================================
     NAVBAR
===================================================== --}}

<header class="site-navbar">

    <div class="navbar-inner">


        {{-- MOBILE TOGGLE --}}

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

        <a
            href="{{ route('pembeli.dashboard') }}"
            class="brand"
        >

            <div class="brand-icon">
                <i class="bi bi-bag-check-fill"></i>
            </div>


            <div class="brand-text">

                <h5>
                    Karyaku
                </h5>

                <small>
                    Marketplace Pembeli
                </small>

            </div>

        </a>



        {{-- DESKTOP NAV --}}

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

                <span class="badge-count">
                    {{ $wishlistCount ?? 0 }}
                </span>

            </a>


            <a
                href="{{ route('pembeli.keranjang') }}"
                class="nav-link active"
            >

                <i class="bi bi-cart-fill"></i>

                Keranjang

                <span class="badge-count">
                    {{ $cartCount ?? (isset($carts) ? $carts->count() : 0) }}
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



        {{-- RIGHT NAVBAR --}}

        <div class="navbar-right">


            {{-- JUAL --}}

            <a
                href="#"
                class="btn-jual"
            >

                <i class="bi bi-shop-window"></i>

                <span>
                    Daftar Sebagai Penjual
                </span>

            </a>



            {{-- NOTIFICATION --}}

            <button
                type="button"
                class="icon-btn"
                title="Notifikasi"
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
                    type="button"
                    class="user-chip"
                    id="btnUserChip"
                >

                    @php
                        $currentUser = auth()->user();
                        $userName = $currentUser->name ?? 'Pembeli';

                        $avatarUrl =
                            'https://ui-avatars.com/api/?name=' .
                            urlencode($userName) .
                            '&background=ffffff&color=1e3a8a&bold=true';
                    @endphp


                    <img
                        src="{{ $avatarUrl }}"
                        alt="Avatar {{ $userName }}"
                    >


                    <div class="user-info">

                        <div class="name">
                            {{ $userName }}
                        </div>

                        <div class="role">
                            Pembeli
                        </div>

                    </div>


                    <i class="bi bi-chevron-down"></i>

                </button>



                {{-- DROPDOWN --}}

                <div class="user-dropdown">


                    <a
                        href="{{ route('pembeli.profile') }}"
                    >

                        <i class="bi bi-person-fill"></i>

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
                            class="logout-btn"
                        >

                            <i class="bi bi-box-arrow-right"></i>

                            Keluar

                        </button>

                    </form>


                </div>

            </div>

        </div>

    </div>



    {{-- =================================================
         MOBILE MENU
    ================================================== --}}

    <div
        class="mobile-menu"
        id="mobileMenu"
    >


        <a href="{{ route('pembeli.dashboard') }}">

            <i class="bi bi-grid-1x2-fill"></i>

            Dashboard

        </a>


        <a href="{{ route('pembeli.marketplace') }}">

            <i class="bi bi-shop"></i>

            Marketplace

        </a>


        <a href="{{ route('pembeli.wishlist') }}">

            <i class="bi bi-heart-fill"></i>

            Wishlist

            <span class="badge-count ms-auto">
                {{ $wishlistCount ?? 0 }}
            </span>

        </a>


        <a
            href="{{ route('pembeli.keranjang') }}"
            class="active"
        >

            <i class="bi bi-cart-fill"></i>

            Keranjang

            <span class="badge-count ms-auto">
                {{ $cartCount ?? (isset($carts) ? $carts->count() : 0) }}
            </span>

        </a>


        <a href="{{ route('pembeli.pesanan') }}">

            <i class="bi bi-receipt"></i>

            Pesanan

        </a>


        <a href="{{ route('pembeli.download') }}">

            <i class="bi bi-cloud-arrow-down-fill"></i>

            Download

        </a>


        <a href="{{ route('pembeli.profile') }}">

            <i class="bi bi-person-fill"></i>

            Profile

        </a>


        <a href="#">

            <i class="bi bi-shop-window"></i>

            Daftar Sebagai Penjual

        </a>


        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="mobile-logout"
            >

                <i class="bi bi-box-arrow-right"></i>

                Keluar

            </button>

        </form>


    </div>

</header>



{{-- =====================================================
     MAIN
===================================================== --}}

<main class="page-container">


    {{-- PAGE HEADER --}}

    <div class="page-header">


        <div>

            <h2>

                <i class="bi bi-cart3 text-primary"></i>

                Keranjang Saya

            </h2>


            <p>
                Periksa kembali barang atau jasa yang ingin kamu beli.
            </p>

        </div>


        <a
            href="{{ route('pembeli.marketplace') }}"
            class="back-market"
        >

            <i class="bi bi-shop"></i>

            Lanjut Belanja

        </a>


=======
@extends('layouts.pembeli')
@section('title', 'Keranjang')

@section('content')

<h4 class="fw-bold mb-4">Keranjang Belanja</h4>

@if ($items->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
        Keranjang kamu masih kosong.
        <div class="mt-3"><a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm">Mulai Belanja</a></div>
    </div>
@else
<form action="{{ route('pembeli.checkout') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="card-box p-3 mb-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th style="width:110px;">Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id_cart }}" class="form-check-input cart-check" data-price="{{ $item->product->price ?? 0 }}" data-qty="{{ $item->quantity }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $item->product && $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&name=' . urlencode($item->product->title ?? 'Produk') }}" style="width:50px;height:50px;border-radius:10px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold small">{{ $item->product->title ?? 'Produk telah dihapus' }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $item->product->seller->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">Rp{{ number_format($item->product->price ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('pembeli.keranjang.update', $item->id_cart) }}" method="POST" class="d-flex align-items-center gap-1">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width:60px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Perbarui"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                        </td>
                        <td class="fw-bold small" style="color:var(--coral);">Rp{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            <button type="submit" form="deleteCart{{ $item->id_cart }}" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-box p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="text-muted small">Total ({{ $items->count() }} item)</div>
            <div class="fw-bold" style="font-size:22px; color:var(--coral);" id="cartTotal">Rp0</div>
        </div>
        <button type="submit" class="btn btn-primary fw-semibold px-4 py-2"><i class="bi bi-bag-check-fill"></i> Checkout Item Terpilih</button>
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895
    </div>
</form>

<<<<<<< HEAD


    {{-- =================================================
         CART LAYOUT
    ================================================== --}}

    <div class="cart-layout">


        {{-- =================================================
             LEFT
        ================================================== --}}

        <div>


            <div class="cart-box">


                {{-- HEADER CART --}}

                <div class="cart-header">


                    <h5>

                        <i class="bi bi-cart3"></i>

                        Produk di Keranjang

                    </h5>


                    @if(isset($carts) && $carts->count())

                        <label class="select-all">

                            <input
                                type="checkbox"
                                id="selectAll"
                            >

                            Pilih Semua

                        </label>

                    @endif


                </div>



                {{-- =================================================
                     CART DATA
                ================================================== --}}

                @if(isset($carts) && $carts->count())


                    @foreach($carts as $cart)


                        @php

                            $product = $cart->product ?? null;


                            $productName =
                                $product->nama_produk
                                ?? $product->name
                                ?? 'Produk Karyaku';


                            $productPrice =
                                $cart->harga
                                ?? $cart->price
                                ?? $product->harga
                                ?? $product->price
                                ?? 0;


                            $productImage =
                                $product->gambar
                                ?? $product->image
                                ?? null;


                            $quantity =
                                $cart->jumlah
                                ?? $cart->quantity
                                ?? 1;


                            $cartId =
                                $cart->id_cart
                                ?? $cart->id
                                ?? null;


                            $sellerName = 'Kreator Karyaku';

                            if ($product) {

                                if (
                                    isset($product->seller) &&
                                    $product->seller
                                ) {

                                    $sellerName =
                                        $product->seller->name
                                        ?? 'Kreator Karyaku';

                                }
                                elseif (
                                    isset($product->seller_name)
                                ) {

                                    $sellerName =
                                        $product->seller_name;

                                }

                            }


                            if ($productImage) {

                                if (
                                    str_starts_with(
                                        $productImage,
                                        'http://'
                                    ) ||
                                    str_starts_with(
                                        $productImage,
                                        'https://'
                                    )
                                ) {

                                    $imageUrl =
                                        $productImage;

                                }
                                else {

                                    $imageUrl =
                                        asset(
                                            'storage/' .
                                            $productImage
                                        );

                                }

                            }
                            else {

                                $imageUrl =
                                    'https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=500&q=80';

                            }

                        @endphp



                        <div class="cart-item">


                            {{-- CHECKBOX --}}

                            <input
                                type="checkbox"
                                class="cart-check item-check"
                                name="cart_ids[]"
                                value="{{ $cartId }}"
                                data-price="{{ $productPrice }}"
                                data-quantity="{{ $quantity }}"
                                checked
                            >



                            {{-- IMAGE --}}

                            <div class="cart-image">

                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $productName }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=500&q=80';"
                                >

                            </div>



                            {{-- INFO --}}

                            <div class="cart-info">


                                <h6>
                                    {{ $productName }}
                                </h6>


                                <div class="seller">

                                    <i class="bi bi-shop"></i>

                                    {{ $sellerName }}

                                </div>


                                <div class="cart-price">

                                    Rp{{ number_format(
                                        $productPrice,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </div>


                            </div>



                            {{-- QUANTITY --}}

                            <form
                                action="{{ route(
                                    'pembeli.keranjang.update',
                                    $cartId
                                ) }}"
                                method="POST"
                                class="qty-form"
                            >

                                @csrf

                                @method('PUT')


                                <button
                                    type="submit"
                                    name="quantity"
                                    value="{{ max(
                                        1,
                                        $quantity - 1
                                    ) }}"
                                    {{ $quantity <= 1 ? 'disabled' : '' }}
                                >
                                    −
                                </button>


                                <span>
                                    {{ $quantity }}
                                </span>


                                <button
                                    type="submit"
                                    name="quantity"
                                    value="{{ $quantity + 1 }}"
                                >
                                    +
                                </button>


                            </form>



                            {{-- DELETE --}}

                            <form
                                action="{{ route(
                                    'pembeli.keranjang.destroy',
                                    $cartId
                                ) }}"
                                method="POST"
                                onsubmit="return confirm(
                                    'Hapus produk ini dari keranjang?'
                                )"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="delete-btn"
                                    title="Hapus"
                                >

                                    <i class="bi bi-trash3"></i>

                                </button>

                            </form>


                        </div>


                    @endforeach


                @else


                    {{-- EMPTY --}}

                    <div class="empty-cart">


                        <i class="bi bi-cart-x"></i>


                        <h4>
                            Keranjang Kamu Masih Kosong
                        </h4>


                        <p>
                            Yuk cari barang atau jasa yang kamu
                            butuhkan di marketplace.
                        </p>


                        <a
                            href="{{ route('pembeli.marketplace') }}"
                            class="btn-marketplace"
                        >

                            <i class="bi bi-shop"></i>

                            Belanja Sekarang

                        </a>


                    </div>


                @endif


            </div>



            {{-- PROMO --}}

            @if(isset($carts) && $carts->count())


                <div class="promo-box">


                    <div class="promo-title">

                        <i
                            class="bi bi-tag-fill"
                            style="color:var(--coral);"
                        ></i>

                        Punya kode promo?

                    </div>


                    <div class="promo-form">


                        <input
                            type="text"
                            id="promoCode"
                            placeholder="Masukkan kode promo"
                        >


                        <button
                            type="button"
                            id="btnPromo"
                        >
                            Pakai Kode
                        </button>


                    </div>


                </div>


            @endif


        </div>



        {{-- =================================================
             RIGHT SUMMARY
        ================================================== --}}

        @if(isset($carts) && $carts->count())


            <div class="summary-box">


                <h5>
                    Ringkasan Belanja
                </h5>


                <div class="summary-row">

                    <span>
                        Produk dipilih
                    </span>

                    <strong id="selectedCount">
                        0
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong id="subtotal">
                        Rp0
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Biaya layanan
                    </span>

                    <strong id="serviceFee">
                        Rp0
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Diskon
                    </span>

                    <strong id="discount">
                        Rp0
                    </strong>

                </div>


                <hr class="summary-divider">


                <div class="summary-total">

                    <span>
                        Total Pembayaran
                    </span>

                    <strong id="total">
                        Rp0
                    </strong>

                </div>


                <form
                    action="{{ route('pembeli.checkout') }}"
                    method="POST"
                    id="checkoutForm"
                >

                    @csrf


                    <div id="checkoutInputs"></div>


                    <button
                        type="submit"
                        class="checkout-btn"
                        id="checkoutBtn"
                        disabled
                    >

                        <i class="bi bi-credit-card me-1"></i>

                        Checkout Sekarang

                    </button>


                </form>


            </div>


        @endif


    </div>

</main>



<script>

    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const btnToggleMenu =
        document.getElementById('btnToggleMenu');

    const mobileMenu =
        document.getElementById('mobileMenu');


    if (btnToggleMenu && mobileMenu) {

        btnToggleMenu.addEventListener(
            'click',
            function () {

                const open =
                    mobileMenu.classList.toggle('show');


                this.setAttribute(
                    'aria-expanded',
                    open ? 'true' : 'false'
                );


                const icon =
                    this.querySelector('i');


                if (icon) {

                    icon.className =
                        open
                        ? 'bi bi-x-lg'
                        : 'bi bi-list';

                }

            }
        );

    }



    /* =====================================================
       USER DROPDOWN
    ===================================================== */

    const userMenu =
        document.getElementById('userMenu');

    const btnUserChip =
        document.getElementById('btnUserChip');


    if (userMenu && btnUserChip) {


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


        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    userMenu.classList.remove('open');

                }

            }
        );

    }



    /* =====================================================
       CART CALCULATION
    ===================================================== */

    const checkboxes =
        document.querySelectorAll('.item-check');

    const selectAll =
        document.getElementById('selectAll');

    const selectedCount =
        document.getElementById('selectedCount');

    const subtotalEl =
        document.getElementById('subtotal');

    const serviceFeeEl =
        document.getElementById('serviceFee');

    const discountEl =
        document.getElementById('discount');

    const totalEl =
        document.getElementById('total');

    const checkoutBtn =
        document.getElementById('checkoutBtn');

    const checkoutInputs =
        document.getElementById('checkoutInputs');



    function rupiah(number) {

        return 'Rp' +
            Number(number).toLocaleString(
                'id-ID'
            );

    }



    function calculateCart() {


        let count = 0;

        let subtotal = 0;


        if (checkoutInputs) {

            checkoutInputs.innerHTML = '';

        }


        checkboxes.forEach(
            function (checkbox) {


                if (checkbox.checked) {


                    const price =
                        Number(
                            checkbox.dataset.price || 0
                        );


                    const quantity =
                        Number(
                            checkbox.dataset.quantity || 1
                        );


                    subtotal +=
                        price * quantity;


                    count++;



                    if (checkoutInputs) {


                        const input =
                            document.createElement(
                                'input'
                            );


                        input.type =
                            'hidden';


                        input.name =
                            'cart_ids[]';


                        input.value =
                            checkbox.value;


                        checkoutInputs.appendChild(
                            input
                        );

                    }

                }

            }
        );



        /*
         * BIAYA LAYANAN
         */

        const serviceFee =
            count > 0
            ? 5000
            : 0;


        const discount = 0;


        const total =
            subtotal +
            serviceFee -
            discount;



        if (selectedCount) {

            selectedCount.textContent =
                count;

        }


        if (subtotalEl) {

            subtotalEl.textContent =
                rupiah(subtotal);

        }


        if (serviceFeeEl) {

            serviceFeeEl.textContent =
                rupiah(serviceFee);

        }


        if (discountEl) {

            discountEl.textContent =
                discount > 0
                ? '-' + rupiah(discount)
                : 'Rp0';

        }


        if (totalEl) {

            totalEl.textContent =
                rupiah(total);

        }


        if (checkoutBtn) {

            checkoutBtn.disabled =
                count === 0;

        }

    }



    /* =====================================================
       SELECT ALL
    ===================================================== */

    if (selectAll) {


        selectAll.addEventListener(
            'change',
            function () {


                checkboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );


                calculateCart();

            }
        );

    }



    /* =====================================================
       INDIVIDUAL CHECKBOX
    ===================================================== */

    checkboxes.forEach(
        function (checkbox) {


            checkbox.addEventListener(
                'change',
                function () {


                    if (selectAll) {


                        const allChecked =
                            [...checkboxes]
                            .every(
                                function (cb) {

                                    return cb.checked;

                                }
                            );


                        const anyChecked =
                            [...checkboxes]
                            .some(
                                function (cb) {

                                    return cb.checked;

                                }
                            );


                        selectAll.checked =
                            allChecked;


                        selectAll.indeterminate =
                            anyChecked &&
                            !allChecked;

                    }


                    calculateCart();

                }
            );

        }
    );



    /* =====================================================
       PROMO
    ===================================================== */

    const btnPromo =
        document.getElementById('btnPromo');


    if (btnPromo) {


        btnPromo.addEventListener(
            'click',
            function () {


                const promoInput =
                    document.getElementById(
                        'promoCode'
                    );


                const code =
                    promoInput
                    ? promoInput.value
                        .trim()
                        .toUpperCase()
                    : '';


                if (!code) {

                    alert(
                        'Masukkan kode promo terlebih dahulu.'
                    );

                    return;

                }


                if (code === 'KARYAKU25') {

                    alert(
                        'Kode promo berhasil digunakan!'
                    );

                }
                else {

                    alert(
                        'Kode promo tidak ditemukan.'
                    );

                }

            }
        );

    }



    /* =====================================================
       INITIAL
    ===================================================== */

    calculateCart();

</script>


</body>
</html>
=======
@foreach ($items as $item)
<form id="deleteCart{{ $item->id_cart }}" action="{{ route('pembeli.keranjang.destroy', $item->id_cart) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script>
    const checkAll = document.getElementById('checkAll');
    const cartChecks = document.querySelectorAll('.cart-check');
    const cartTotalEl = document.getElementById('cartTotal');

    function formatRupiah(num) {
        return 'Rp' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function recalcTotal() {
        let total = 0;
        cartChecks.forEach(cb => {
            if (cb.checked) total += parseFloat(cb.dataset.price) * parseInt(cb.dataset.qty);
        });
        cartTotalEl.textContent = formatRupiah(total);
    }

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            cartChecks.forEach(cb => cb.checked = checkAll.checked);
            recalcTotal();
        });
    }
    cartChecks.forEach(cb => cb.addEventListener('change', recalcTotal));

    document.getElementById('checkoutForm').addEventListener('submit', (e) => {
        const anyChecked = Array.from(cartChecks).some(cb => cb.checked);
        if (!anyChecked) {
            e.preventDefault();
            alert('Pilih minimal 1 produk untuk checkout.');
        }
    });

    recalcTotal();
</script>
@endif

@endsection
>>>>>>> 06954879e48d1bd7412da6f15e66525e00bd1895

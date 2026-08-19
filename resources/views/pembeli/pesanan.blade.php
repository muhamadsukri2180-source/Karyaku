<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan - Karyaku</title>

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

        /* =====================================================
           NAVBAR
        ===================================================== */

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

        .user-dropdown hr {
            margin: 5px 0;
            border-color: #e2e8f0;
        }

        /* =====================================================
           SEARCH
        ===================================================== */

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

        /* =====================================================
           MAIN
        ===================================================== */

        .main-content {
            max-width: 1450px;
            margin: auto;

            padding: 24px 28px 60px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h2 {
            margin: 0;

            font-size: 25px;
            font-weight: 800;
        }

        .page-header p {
            margin: 5px 0 0;

            color: var(--text-muted);

            font-size: 13px;
        }

        /* =====================================================
           ORDER FILTER
        ===================================================== */

        .order-tabs {
            display: flex;
            gap: 8px;

            flex-wrap: wrap;

            margin-bottom: 20px;
        }

        .order-tab {
            border: 1px solid var(--border-color);

            background: white;

            color: var(--text-dark);

            padding: 8px 16px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            transition: .2s;
        }

        .order-tab:hover,
        .order-tab.active {
            background: var(--primary);
            color: white;

            border-color: var(--primary);
        }

        /* =====================================================
           ORDER CARD
        ===================================================== */

        .orders-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .order-card {
            background: white;

            border: 1px solid var(--border-color);

            border-radius: 16px;

            box-shadow: var(--shadow);

            overflow: hidden;

            transition: .2s;
        }

        .order-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .order-header {
            padding: 14px 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            border-bottom: 1px solid #edf2ff;
        }

        .order-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .order-icon {
            width: 38px;
            height: 38px;

            border-radius: 10px;

            background: var(--primary-light);

            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 16px;
        }

        .order-number {
            font-size: 11px;
            font-weight: 700;
        }

        .order-date {
            color: var(--text-muted);
            font-size: 9px;
            margin-top: 2px;
        }

        .status {
            padding: 6px 11px;

            border-radius: 20px;

            font-size: 9px;
            font-weight: 700;

            white-space: nowrap;
        }

        .status-menunggu {
            background: #fff7ed;
            color: #ea580c;
        }

        .status-diproses {
            background: #eff6ff;
            color: #2563eb;
        }

        .status-selesai {
            background: #ecfdf5;
            color: #059669;
        }

        .status-dibatalkan {
            background: #fef2f2;
            color: #dc2626;
        }

        /* =====================================================
           ORDER BODY
        ===================================================== */

        .order-body {
            padding: 16px 18px;
        }

        .product-item {
            display: flex;
            align-items: center;

            gap: 13px;
        }

        .product-image {
            width: 68px;
            height: 68px;

            border-radius: 11px;

            overflow: hidden;

            flex-shrink: 0;

            background: #eaf1ff;
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
            margin: 0 0 4px;

            font-size: 12px;
            font-weight: 700;

            line-height: 1.5;
        }

        .product-info p {
            margin: 0;

            color: var(--text-muted);

            font-size: 9px;
        }

        .product-price {
            text-align: right;

            font-size: 13px;

            font-weight: 800;

            color: var(--coral);

            white-space: nowrap;
        }

        .order-summary {
            margin-top: 15px;

            padding-top: 13px;

            border-top: 1px dashed #dbe5f7;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .summary-left {
            color: var(--text-muted);

            font-size: 10px;
        }

        .summary-left strong {
            color: var(--text-dark);
        }

        .summary-right {
            display: flex;
            align-items: center;

            gap: 15px;
        }

        .total-label {
            color: var(--text-muted);

            font-size: 10px;
        }

        .total-price {
            color: var(--coral);

            font-size: 15px;

            font-weight: 800;
        }

        .btn-detail {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            padding: 8px 13px;

            border-radius: 9px;

            background: var(--primary);

            color: white;

            font-size: 10px;

            font-weight: 700;

            border: 0;

            transition: .2s;
        }

        .btn-detail:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-download {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            padding: 8px 13px;

            border-radius: 9px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 10px;

            font-weight: 700;

            border: 0;
        }

        .btn-download:hover {
            background: var(--primary);
            color: white;
        }

        /* =====================================================
           EMPTY
        ===================================================== */

        .empty-order {
            background: white;

            border: 1px solid var(--border-color);

            border-radius: 18px;

            padding: 70px 20px;

            text-align: center;

            box-shadow: var(--shadow);
        }

        .empty-order i {
            font-size: 48px;

            color: #94a3b8;
        }

        .empty-order h5 {
            margin-top: 15px;

            font-size: 17px;

            font-weight: 700;
        }

        .empty-order p {
            color: var(--text-muted);

            font-size: 12px;

            margin-bottom: 20px;
        }

        .btn-marketplace {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            background: var(--primary);

            color: white;

            padding: 10px 16px;

            border-radius: 10px;

            font-size: 11px;

            font-weight: 700;
        }

        .btn-marketplace:hover {
            background: var(--primary-dark);
            color: white;
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media(max-width: 1100px) {

            .nav-menu {
                gap: 0;
            }

            .nav-link {
                padding: 8px 9px;
                font-size: 11px;
            }

            .btn-jual {
                display: none;
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

            .navbar-top {
                gap: 12px;
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

            .order-header {
                align-items: flex-start;
            }

            .order-summary {
                align-items: flex-start;
                flex-direction: column;
            }

            .summary-right {
                width: 100%;

                justify-content: space-between;

                flex-wrap: wrap;
            }
        }

        @media(max-width: 550px) {

            .brand-text {
                display: none;
            }

            .user-name,
            .user-role {
                display: none;
            }

            .product-item {
                align-items: flex-start;
            }

            .product-price {
                font-size: 11px;
            }

            .order-info {
                gap: 8px;
            }

            .order-icon {
                width: 34px;
                height: 34px;
            }

            .order-number {
                font-size: 10px;
            }

            .status {
                font-size: 8px;
            }
        }

        @media(max-width: 430px) {

            .search-combo select {
                width: 90px;
                font-size: 10px;
            }

            .search-combo input {
                font-size: 11px;
            }

            .search-combo button {
                width: 55px;
            }

            .product-image {
                width: 58px;
                height: 58px;
            }

            .product-info h6 {
                font-size: 10px;
            }

            .btn-detail,
            .btn-download {
                width: 100%;
            }

            .summary-right {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

<header class="site-navbar">

    <div class="navbar-top">

        <button class="mobile-toggle" id="mobileToggle" type="button">
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

            <a href="{{ route('pembeli.marketplace') }}" class="nav-link">
                <i class="bi bi-shop"></i>
                Marketplace
            </a>

            <a href="{{ route('pembeli.wishlist') }}" class="nav-link">
                <i class="bi bi-heart-fill"></i>
                Wishlist
                <span class="badge-count">
                    {{ $wishlistCount ?? 0 }}
                </span>
            </a>

            <a href="{{ route('pembeli.keranjang') }}" class="nav-link">
                <i class="bi bi-cart-fill"></i>
                Keranjang
                <span class="badge-count">
                    {{ $cartCount ?? 0 }}
                </span>
            </a>

            <a href="{{ route('pembeli.pesanan') }}" class="nav-link active">
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

            <button class="icon-btn-light" type="button">
                <i class="bi bi-bell"></i>

                <span class="dot">
                    2
                </span>
            </button>

            <div class="user-menu" id="userMenu">

                <button class="user-chip" id="userChip" type="button">

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

        <form class="search-combo" id="searchForm">

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

    <div class="page-header">

        <h2>
            Pesanan Saya
        </h2>

        <p>
            Lihat dan kelola semua pesanan yang pernah kamu lakukan di Karyaku.
        </p>

    </div>


    {{-- FILTER STATUS --}}

    <div class="order-tabs">

        <button
            class="order-tab active"
            data-status="all"
        >
            Semua
        </button>

        <button
            class="order-tab"
            data-status="menunggu"
        >
            Menunggu
        </button>

        <button
            class="order-tab"
            data-status="diproses"
        >
            Diproses
        </button>

        <button
            class="order-tab"
            data-status="selesai"
        >
            Selesai
        </button>

        <button
            class="order-tab"
            data-status="dibatalkan"
        >
            Dibatalkan
        </button>

    </div>


    {{-- =====================================================
         ORDERS
    ===================================================== --}}

    <div class="orders-wrapper" id="ordersWrapper">

        @forelse($orders ?? [] as $order)

            @php

                $status = strtolower($order->status ?? 'menunggu');

                $statusClass = match($status) {

                    'menunggu',
                    'pending',
                    'menunggu pembayaran'
                        => 'status-menunggu',

                    'diproses',
                    'processing'
                        => 'status-diproses',

                    'selesai',
                    'completed'
                        => 'status-selesai',

                    'dibatalkan',
                    'cancelled',
                    'canceled'
                        => 'status-dibatalkan',

                    default
                        => 'status-menunggu',

                };

                $statusText = match($status) {

                    'pending'
                        => 'Menunggu',

                    'processing'
                        => 'Diproses',

                    'completed'
                        => 'Selesai',

                    'cancelled',
                    'canceled'
                        => 'Dibatalkan',

                    default
                        => ucfirst($status),

                };

            @endphp


            <div
                class="order-card"
                data-status="{{ $status }}"
            >

                {{-- ORDER HEADER --}}

                <div class="order-header">

                    <div class="order-info">

                        <div class="order-icon">
                            <i class="bi bi-receipt"></i>
                        </div>

                        <div>

                            <div class="order-number">
                                Pesanan #{{ $order->id_order ?? $order->order_number ?? '-' }}
                            </div>

                            <div class="order-date">

                                @if(isset($order->created_at))

                                    {{ $order->created_at->format('d M Y, H:i') }}

                                @else

                                    Tanggal tidak tersedia

                                @endif

                            </div>

                        </div>

                    </div>


                    <span class="status {{ $statusClass }}">

                        {{ $statusText }}

                    </span>

                </div>


                {{-- ORDER BODY --}}

                <div class="order-body">

                    @php
                        $items = $order->orderItems ?? $order->items ?? collect();
                    @endphp


                    @forelse($items as $item)

                        @php

                            $product = $item->product ?? null;

                            $productName =
                                $product->name_product ??
                                $product->nama_product ??
                                $product->name ??
                                $item->nama_produk ??
                                $item->product_name ??
                                'Produk';

                            $productImage =
                                $product->image ??
                                $product->gambar ??
                                null;

                            $quantity =
                                $item->quantity ??
                                $item->jumlah ??
                                1;

                            $price =
                                $item->price ??
                                $item->harga ??
                                0;

                        @endphp


                        <div class="product-item mb-3">

                            <div class="product-image">

                                @if($productImage)

                                    <img
                                        src="{{ asset('storage/' . $productImage) }}"
                                        alt="{{ $productName }}"
                                    >

                                @else

                                    <img
                                        src="https://via.placeholder.com/150/eaf1ff/2563eb?text=Karyaku"
                                        alt="{{ $productName }}"
                                    >

                                @endif

                            </div>


                            <div class="product-info">

                                <h6>
                                    {{ $productName }}
                                </h6>

                                <p>
                                    Jumlah:
                                    {{ $quantity }}
                                </p>

                            </div>


                            <div class="product-price">

                                Rp{{ number_format(
                                    $price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-3">

                            <small class="text-muted">
                                Detail produk tidak tersedia.
                            </small>

                        </div>

                    @endforelse


                    {{-- SUMMARY --}}

                    <div class="order-summary">

                        <div class="summary-left">

                            <strong>
                                {{ $items->count() }}
                            </strong>

                            produk dalam pesanan

                        </div>


                        <div class="summary-right">

                            <div>

                                <span class="total-label">
                                    Total:
                                </span>

                                <span class="total-price">

                                    Rp{{ number_format(
                                        $order->total_amount ??
                                        $order->total ??
                                        0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>


                            @if(
                                Route::has('pembeli.pesanan.detail') &&
                                isset($order->id_order)
                            )

                                <a
                                    href="{{ route(
                                        'pembeli.pesanan.detail',
                                        $order->id_order
                                    ) }}"
                                    class="btn-detail"
                                >
                                    <i class="bi bi-eye"></i>
                                    Lihat Detail
                                </a>

                            @endif


                            @if(
                                $status === 'selesai' ||
                                $status === 'completed'
                            )

                                <a
                                    href="{{ route('pembeli.download') }}"
                                    class="btn-download"
                                >
                                    <i class="bi bi-download"></i>
                                    Download
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @empty

            {{-- EMPTY ORDER --}}

            <div class="empty-order">

                <i class="bi bi-receipt"></i>

                <h5>
                    Belum Ada Pesanan
                </h5>

                <p>
                    Kamu belum melakukan pembelian.
                    Yuk cari produk menarik di Marketplace.
                </p>

                <a
                    href="{{ route('pembeli.marketplace') }}"
                    class="btn-marketplace"
                >
                    <i class="bi bi-shop"></i>
                    Jelajahi Marketplace
                </a>

            </div>

        @endforelse

    </div>


    {{-- NO FILTER RESULT --}}

    <div
        class="empty-order"
        id="noOrderResult"
        style="display:none; margin-top:16px;"
    >

        <i class="bi bi-search"></i>

        <h5>
            Pesanan Tidak Ditemukan
        </h5>

        <p>
            Tidak ada pesanan dengan status tersebut.
        </p>

    </div>

</main>


<script>

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

                userMenu.classList.remove('open');

            }

        }
    );


    /* =====================================================
       SEARCH
    ===================================================== */

    const searchForm =
        document.getElementById('searchForm');

    const searchInput =
        document.getElementById('searchInput');


    searchForm.addEventListener(
        'submit',
        function(event) {

            event.preventDefault();

            const keyword =
                searchInput.value
                    .toLowerCase()
                    .trim();


            const orders =
                document.querySelectorAll('.order-card');


            orders.forEach(order => {

                const text =
                    order.innerText.toLowerCase();


                if (
                    keyword === '' ||
                    text.includes(keyword)
                ) {

                    order.style.display = '';

                } else {

                    order.style.display = 'none';

                }

            });

        }
    );


    searchInput.addEventListener(
        'input',
        function() {

            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const orders =
                document.querySelectorAll('.order-card');


            orders.forEach(order => {

                const text =
                    order.innerText.toLowerCase();


                if (
                    keyword === '' ||
                    text.includes(keyword)
                ) {

                    order.style.display = '';

                } else {

                    order.style.display = 'none';

                }

            });

        }
    );


    /* =====================================================
       ORDER FILTER
    ===================================================== */

    const orderTabs =
        document.querySelectorAll('.order-tab');

    const orderCards =
        document.querySelectorAll('.order-card');

    const noOrderResult =
        document.getElementById('noOrderResult');


    orderTabs.forEach(tab => {

        tab.addEventListener(
            'click',
            function() {

                orderTabs.forEach(item => {

                    item.classList.remove(
                        'active'
                    );

                });


                this.classList.add('active');


                const selectedStatus =
                    this.dataset.status;


                let visibleCount = 0;


                orderCards.forEach(card => {

                    const cardStatus =
                        card.dataset.status
                            .toLowerCase();


                    let show = false;


                    if (
                        selectedStatus === 'all'
                    ) {

                        show = true;

                    } else {

                        if (
                            selectedStatus ===
                            cardStatus
                        ) {

                            show = true;

                        }


                        /*
                         * Support beberapa
                         * penamaan status Laravel
                         */

                        if (
                            selectedStatus ===
                            'menunggu' &&
                            (
                                cardStatus === 'pending' ||
                                cardStatus === 'menunggu pembayaran'
                            )
                        ) {

                            show = true;

                        }


                        if (
                            selectedStatus ===
                            'diproses' &&
                            cardStatus === 'processing'
                        ) {

                            show = true;

                        }


                        if (
                            selectedStatus ===
                            'selesai' &&
                            cardStatus === 'completed'
                        ) {

                            show = true;

                        }


                        if (
                            selectedStatus ===
                            'dibatalkan' &&
                            (
                                cardStatus === 'cancelled' ||
                                cardStatus === 'canceled'
                            )
                        ) {

                            show = true;

                        }

                    }


                    if (show) {

                        card.style.display = '';

                        visibleCount++;

                    } else {

                        card.style.display = 'none';

                    }

                });


                if (
                    visibleCount === 0 &&
                    orderCards.length > 0
                ) {

                    noOrderResult.style.display =
                        'block';

                } else {

                    noOrderResult.style.display =
                        'none';

                }

            }
        );

    });


    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const mobileToggle =
        document.getElementById('mobileToggle');

    const navMenu =
        document.querySelector('.nav-menu');


    mobileToggle.addEventListener(
        'click',
        function() {

            if (
                navMenu.style.display ===
                'flex'
            ) {

                navMenu.style.display =
                    'none';

            } else {

                navMenu.style.display =
                    'flex';

                navMenu.style.flexDirection =
                    'column';

                navMenu.style.position =
                    'absolute';

                navMenu.style.top =
                    '64px';

                navMenu.style.left =
                    '16px';

                navMenu.style.right =
                    '16px';

                navMenu.style.padding =
                    '10px';

                navMenu.style.borderRadius =
                    '12px';

                navMenu.style.background =
                    '#14225c';

                navMenu.style.boxShadow =
                    '0 15px 30px rgba(0,0,0,.2)';

            }

        }
    );

</script>

</body>
</html>
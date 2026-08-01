<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembeli - Karyaku</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        /* =========================================================
           Tema Dashboard Pembeli - Biru & Putih (Animated)
           ========================================================= */
        :root{
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-darker: #14225c;
            --primary-light: #eff6ff;
            --primary-soft: #dbeafe;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e5edff;
            --radius: 18px;
            --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
            --shadow-hover: 0 16px 34px rgba(37, 99, 235, 0.16);
        }

        *{ box-sizing: border-box; }

        body{
            font-family: 'Poppins', sans-serif;
            background: var(--primary-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ---------------- Background decor animasi ---------------- */
        .bg-decor{
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }
        .bg-decor span{
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, var(--primary-soft), transparent 70%);
            opacity: .5;
            animation: floatBlob 14s ease-in-out infinite;
        }
        .bg-decor span:nth-child(1){ width: 380px; height: 380px; top: -120px; right: -100px; animation-duration: 16s; }
        .bg-decor span:nth-child(2){ width: 260px; height: 260px; bottom: -80px; left: -60px; animation-duration: 20s; animation-delay: 2s; }
        .bg-decor span:nth-child(3){ width: 180px; height: 180px; bottom: 30%; right: 8%; animation-duration: 12s; animation-delay: 1s; }
        @keyframes floatBlob{
            0%,100%{ transform: translate(0,0) scale(1); }
            50%{ transform: translate(20px,-30px) scale(1.08); }
        }

        /* ---------------- Layout ---------------- */
        .app-wrapper{ display: flex; min-height: 100vh; }

        /* ---------------- Sidebar ---------------- */
        .sidebar{
            width: 272px;
            min-width: 272px;
            background: linear-gradient(180deg, var(--primary-darker) 0%, var(--primary-dark) 100%);
            color: var(--white);
            min-height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 24px 18px;
            z-index: 1030;
            animation: slideInLeft .5s ease both;
        }
        @keyframes slideInLeft{
            from{ transform: translateX(-30px); opacity: 0; }
            to{ transform: translateX(0); opacity: 1; }
        }

        .sidebar .brand{
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 10px 26px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 20px;
        }
        .sidebar .brand-icon{
            width: 42px; height: 42px;
            background: var(--white);
            color: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 700;
            transition: transform .4s ease;
        }
        .sidebar .brand:hover .brand-icon{ transform: rotate(-12deg) scale(1.08); }
        .sidebar .brand-text h5{ margin: 0; font-weight: 700; font-size: 16px; color: var(--white); }
        .sidebar .brand-text small{ color: rgba(255,255,255,0.6); font-size: 11px; }

        .sidebar .nav-section-title{
            font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,0.45); padding: 10px 12px 6px;
        }

        .sidebar .nav-link{
            position: relative;
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.78);
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 500;
            margin-bottom: 5px;
            transition: all .25s ease;
            overflow: hidden;
            opacity: 0;
            animation: fadeInItem .4s ease forwards;
        }
        .sidebar .nav-link i{ font-size: 18px; width: 22px; text-align: center; transition: transform .25s ease; }

        @keyframes fadeInItem{
            from{ opacity: 0; transform: translateX(-10px); }
            to{ opacity: 1; transform: translateX(0); }
        }
        .sidebar .nav-link:nth-child(1){ animation-delay: .05s; }
        .sidebar .nav-link:nth-child(2){ animation-delay: .1s; }
        .sidebar .nav-link:nth-child(3){ animation-delay: .15s; }
        .sidebar .nav-link:nth-child(4){ animation-delay: .2s; }
        .sidebar .nav-link:nth-child(5){ animation-delay: .25s; }
        .sidebar .nav-link:nth-child(6){ animation-delay: .3s; }
        .sidebar .nav-link:nth-child(7){ animation-delay: .35s; }

        .sidebar .nav-link::before{
            content: "";
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: var(--white);
            transform: scaleY(0);
            transition: transform .25s ease;
            border-radius: 0 4px 4px 0;
        }
        .sidebar .nav-link:hover{
            background: rgba(255,255,255,0.08);
            color: var(--white);
            transform: translateX(4px);
        }
        .sidebar .nav-link:hover i{ transform: scale(1.15); }

        .sidebar .nav-link.active{
            background: var(--white);
            color: var(--primary-dark);
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0,0,0,0.18);
        }
        .sidebar .nav-link.active::before{ transform: scaleY(1); background: var(--primary); }

        .sidebar .nav-link .badge-count{
            margin-left: auto;
            background: var(--primary);
            color: var(--white);
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
            transition: transform .2s ease;
        }
        .sidebar .nav-link.active .badge-count{ background: var(--primary-soft); color: var(--primary-dark); }
        .sidebar .nav-link:hover .badge-count{ transform: scale(1.12); }

        .sidebar .sidebar-footer{ margin-top: auto; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.12); }
        .sidebar .logout-link{
            display: flex; align-items: center; gap: 10px;
            color: #fecaca; padding: 10px 14px; border-radius: 12px;
            font-size: 14px; font-weight: 500; text-decoration: none;
            transition: .2s;
        }
        .sidebar .logout-link:hover{ background: rgba(255,255,255,0.08); color: #fff; padding-left: 18px; }

        /* Mobile sidebar */
        @media (max-width: 992px){
            .sidebar{ position: fixed; left: -290px; transition: left .3s ease; box-shadow: 10px 0 30px rgba(0,0,0,0.25); }
            .sidebar.show{ left: 0; }
            .sidebar-overlay{
                display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.45);
                z-index: 1020; backdrop-filter: blur(2px);
            }
            .sidebar-overlay.show{ display: block; animation: fadeIn .2s ease; }
        }
        @keyframes fadeIn{ from{opacity:0;} to{opacity:1;} }

        /* ---------------- Main Content ---------------- */
        .main-content{ flex: 1; min-width: 0; padding: 26px 32px 50px; }

        /* Topbar */
        .topbar{
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; margin-bottom: 26px; flex-wrap: wrap;
            animation: fadeSlideDown .5s ease both;
        }
        @keyframes fadeSlideDown{
            from{ opacity: 0; transform: translateY(-14px); }
            to{ opacity: 1; transform: translateY(0); }
        }
        .topbar h2{ font-weight: 700; font-size: 25px; margin: 0; }
        .topbar h2 .wave{ display: inline-block; animation: wave 2s ease-in-out infinite; transform-origin: 70% 70%; }
        @keyframes wave{
            0%,100%{ transform: rotate(0deg); }
            15%{ transform: rotate(16deg); }
            30%{ transform: rotate(-10deg); }
            45%{ transform: rotate(16deg); }
            60%{ transform: rotate(0deg); }
        }
        .topbar p{ margin: 2px 0 0; color: var(--text-muted); font-size: 13.5px; }

        .search-box{ position: relative; min-width: 260px; }
        .search-box input{
            border-radius: 12px; border: 1px solid var(--border-color);
            padding: 10px 16px 10px 40px; background: var(--white);
            width: 100%; font-size: 14px; transition: box-shadow .2s ease, border-color .2s ease;
        }
        .search-box input:focus{
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        .search-box i{ position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

        .topbar-actions{ display: flex; align-items: center; gap: 14px; }
        .icon-btn{
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--white); border: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); position: relative; font-size: 18px;
            transition: all .2s ease;
        }
        .icon-btn:hover{ background: var(--primary); color: #fff; transform: translateY(-3px); box-shadow: var(--shadow-hover); }
        .icon-btn .dot{
            position: absolute; top: 8px; right: 8px; width: 8px; height: 8px;
            background: #ef4444; border-radius: 50%; border: 2px solid var(--white);
            animation: pulseDot 1.6s infinite;
        }
        @keyframes pulseDot{
            0%{ box-shadow: 0 0 0 0 rgba(239,68,68,.55); }
            70%{ box-shadow: 0 0 0 6px rgba(239,68,68,0); }
            100%{ box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }

        .user-chip{
            display: flex; align-items: center; gap: 10px;
            background: var(--white); border: 1px solid var(--border-color);
            padding: 6px 14px 6px 6px; border-radius: 30px;
            transition: box-shadow .2s ease;
        }
        .user-chip:hover{ box-shadow: var(--shadow); }
        .user-chip img{ width: 34px; height: 34px; border-radius: 50%; object-fit: cover; }
        .user-chip .name{ font-size: 13.5px; font-weight: 600; line-height: 1.1; }
        .user-chip .role{ font-size: 11px; color: var(--text-muted); }

        /* ---------------- Stat Cards ---------------- */
        .stat-card{
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(18px);
            animation: cardIn .55s ease forwards;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .stat-card:hover{ transform: translateY(-6px); box-shadow: var(--shadow-hover); }
        .stat-card:nth-child(1){ animation-delay: .05s; }
        .stat-card:nth-child(2){ animation-delay: .15s; }
        .stat-card:nth-child(3){ animation-delay: .25s; }
        .stat-card:nth-child(4){ animation-delay: .35s; }
        @keyframes cardIn{ to{ opacity: 1; transform: translateY(0); } }

        .stat-card::after{
            content: "";
            position: absolute;
            width: 90px; height: 90px;
            background: var(--primary-light);
            border-radius: 50%;
            right: -30px; bottom: -30px;
            z-index: 0;
            transition: transform .4s ease;
        }
        .stat-card:hover::after{ transform: scale(1.5); }
        .stat-card > *{ position: relative; z-index: 1; }

        .stat-icon{
            width: 54px; height: 54px; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 23px; flex-shrink: 0;
            transition: transform .35s ease;
        }
        .stat-card:hover .stat-icon{ transform: rotate(-8deg) scale(1.08); }
        .stat-icon.blue{ background: var(--primary-soft); color: var(--primary); }
        .stat-icon.dark{ background: var(--primary-dark); color: #fff; }
        .stat-icon.green{ background: #dcfce7; color: #16a34a; }
        .stat-icon.orange{ background: #ffedd5; color: #f97316; }

        .stat-card h3{ margin: 0; font-weight: 700; font-size: 23px; }
        .stat-card span.label{ color: var(--text-muted); font-size: 13px; }
        .trend{ font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; }
        .trend.up{ color: #16a34a; }
        .trend.down{ color: #ef4444; }

        /* ---------------- Section titles ---------------- */
        .section-title{ font-weight: 700; font-size: 16.5px; margin-bottom: 2px; }
        .section-sub{ color: var(--text-muted); font-size: 13px; margin-bottom: 18px; }

        /* ---------------- Cards general ---------------- */
        .card-soft{
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            padding: 22px;
            opacity: 0;
            transform: translateY(18px);
            animation: cardIn .6s ease forwards;
        }
        .card-soft.delay1{ animation-delay: .2s; }
        .card-soft.delay2{ animation-delay: .3s; }
        .card-soft.delay3{ animation-delay: .4s; }

        /* ---------------- Buttons ---------------- */
        .btn-primary-soft{
            background: var(--primary); border: none; color: #fff;
            border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 600;
            transition: all .2s ease; position: relative; overflow: hidden;
        }
        .btn-primary-soft:hover{ background: var(--primary-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 10px 18px rgba(37,99,235,.28); }
        .btn-primary-soft:active{ transform: translateY(0) scale(.97); }

        .btn-outline-blue{
            background: var(--white); border: 1px solid var(--primary); color: var(--primary);
            border-radius: 10px; padding: 8px 16px; font-size: 13.5px; font-weight: 600;
            transition: all .2s ease;
        }
        .btn-outline-blue:hover{ background: var(--primary); color: #fff; transform: translateY(-2px); }

        /* ---------------- Badges ---------------- */
        .badge-status{ padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-diproses{ background: #fef3c7; color: #b45309; }
        .badge-dikirim{ background: #dbeafe; color: #1d4ed8; }
        .badge-selesai{ background: #dcfce7; color: #15803d; }
        .badge-dibatalkan{ background: #fee2e2; color: #b91c1c; }

        /* ---------------- Table ---------------- */
        .table-soft{ width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .table-soft thead th{
            font-size: 12px; text-transform: uppercase; color: var(--text-muted);
            font-weight: 600; padding: 0 14px 8px; border: none;
        }
        .table-soft tbody tr{ background: var(--white); transition: transform .2s ease, box-shadow .2s ease; }
        .table-soft tbody tr:hover{ transform: translateX(3px); box-shadow: var(--shadow); }
        .table-soft tbody td{
            padding: 14px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);
            font-size: 13.8px; vertical-align: middle;
        }
        .table-soft tbody td:first-child{ border-left: 1px solid var(--border-color); border-radius: 12px 0 0 12px; }
        .table-soft tbody td:last-child{ border-right: 1px solid var(--border-color); border-radius: 0 12px 12px 0; }

        .order-item-thumb{
            width: 38px; height: 38px; font-size: 16px; border-radius: 10px;
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s ease;
        }
        .table-soft tbody tr:hover .order-item-thumb{ transform: scale(1.1) rotate(-6deg); background: var(--primary-soft); }

        /* ---------------- Progress bars (kategori belanja) ---------------- */
        .progress-item{ margin-bottom: 16px; }
        .progress-item .top{ display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
        .progress-track{ height: 8px; background: var(--primary-light); border-radius: 20px; overflow: hidden; }
        .progress-fill{
            height: 100%; border-radius: 20px;
            background: linear-gradient(90deg, var(--primary), #60a5fa);
            width: 0%;
            transition: width 1.2s cubic-bezier(.22,1,.36,1);
        }

        /* ---------------- Promo card ---------------- */
        .promo-card{
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #fff; border: none; position: relative; overflow: hidden;
        }
        .promo-card::before{
            content: "";
            position: absolute;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
            top: -60px; right: -40px;
            animation: floatBlob 8s ease-in-out infinite;
        }
        .promo-card i.gift{ animation: bounce 2.2s ease-in-out infinite; display: inline-block; }
        @keyframes bounce{
            0%,100%{ transform: translateY(0); }
            50%{ transform: translateY(-6px); }
        }

        /* ---------------- Quick action buttons ---------------- */
        .quick-action{
            display: flex; align-items: center; gap: 12px;
            background: var(--primary-light);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 12px 14px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 13.5px;
            transition: all .25s ease;
        }
        .quick-action i{
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--white); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; transition: transform .25s ease;
        }
        .quick-action:hover{ background: var(--primary); color: #fff; transform: translateX(4px); }
        .quick-action:hover i{ background: rgba(255,255,255,.2); color: #fff; transform: rotate(-10deg) scale(1.1); }

        /* ---------------- Scroll reveal ---------------- */
        .reveal{ opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.active{ opacity: 1; transform: translateY(0); }

        /* ---------------- Chart card ---------------- */
        .chart-wrap{ position: relative; height: 230px; }

        /* scrollbar cantik */
        ::-webkit-scrollbar{ width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }

        @keyframes rippleEffect{ to{ transform: scale(2.6); opacity: 0; } }
    </style>
</head>
<body>

    <div class="bg-decor"><span></span><span></span><span></span></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-wrapper">

        {{-- ===================== SIDEBAR ===================== --}}
        <aside class="sidebar" id="sidebarPembeli">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-bag-check-fill"></i></div>
                <div class="brand-text">
                    <h5>Karyaku</h5>
                    <small>Marketplace Pembeli</small>
                </div>
            </div>

            <div class="nav-section-title">Menu Utama</div>
            <nav class="nav flex-column">
                <a href="#" class="nav-link active">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-shop"></i> Marketplace
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-heart-fill"></i> Wishlist
                    <span class="badge-count">{{ $wishlistCount ?? 5 }}</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-cart-fill"></i> Keranjang
                    <span class="badge-count">{{ $keranjangCount ?? 3 }}</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-receipt"></i> Pesanan Saya
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-cloud-arrow-down-fill"></i> Download Saya
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-person-fill"></i> Profile
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="#">
                    @csrf
                    <a href="#" class="logout-link"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a>
                </form>
            </div>
        </aside>

        {{-- ===================== MAIN CONTENT ===================== --}}
        <main class="main-content">

            {{-- ===================== TOPBAR ===================== --}}
            <div class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn icon-btn d-lg-none" id="btnToggleSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h2>Halo, {{ auth()->user()->name ?? 'Budi Santoso' }} <span class="wave">👋</span></h2>
                        <p>Selamat datang kembali di dashboard belanja Anda</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari produk...">
                    </div>

                    <div class="topbar-actions">
                        <a href="#" class="icon-btn" title="Wishlist">
                            <i class="bi bi-heart"></i><span class="dot"></span>
                        </a>
                        <a href="#" class="icon-btn" title="Keranjang">
                            <i class="bi bi-cart3"></i><span class="dot"></span>
                        </a>
                        <button class="icon-btn" title="Notifikasi">
                            <i class="bi bi-bell"></i><span class="dot"></span>
                        </button>

                        <div class="user-chip">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Budi Santoso') }}&background=2563eb&color=fff" alt="avatar">
                            <div>
                                <div class="name">{{ auth()->user()->name ?? 'Budi Santoso' }}</div>
                                <div class="role">Pembeli</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== STAT CARDS (angka animasi) ===================== --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon dark"><i class="bi bi-receipt"></i></div>
                        <div>
                            <h3 data-count="{{ $totalPesanan ?? 24 }}">0</h3>
                            <span class="label">Total Pesanan</span><br>
                            <span class="trend up"><i class="bi bi-arrow-up-short"></i>12% bulan ini</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-cart-check"></i></div>
                        <div>
                            <h3 data-count="{{ $pesananSelesai ?? 18 }}">0</h3>
                            <span class="label">Pesanan Selesai</span><br>
                            <span class="trend up"><i class="bi bi-arrow-up-short"></i>8% bulan ini</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-heart"></i></div>
                        <div>
                            <h3 data-count="{{ $totalWishlist ?? 9 }}">0</h3>
                            <span class="label">Wishlist</span><br>
                            <span class="trend down"><i class="bi bi-arrow-down-short"></i>2% bulan ini</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
                        <div>
                            <h3 data-count="{{ $totalBelanja ?? 4250 }}" data-prefix="Rp " data-suffix="rb">Rp 0</h3>
                            <span class="label">Total Belanja</span><br>
                            <span class="trend up"><i class="bi bi-arrow-up-short"></i>20% bulan ini</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- ===================== KIRI: TABEL + CHART ===================== --}}
                <div class="col-lg-8">

                    {{-- Pesanan terbaru --}}
                    <div class="card-soft mb-4 delay1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="section-title">Pesanan Terbaru</div>
                                <div class="section-sub">Riwayat transaksi terakhir Anda</div>
                            </div>
                            <a href="#" class="btn-outline-blue">Lihat Semua</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table-soft">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>No. Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $pesananTerbaru = $pesananTerbaru ?? [
                                            ['produk' => 'Kemeja Flanel Pria', 'no' => 'INV-20260728-01', 'tanggal' => '28 Jul 2026', 'total' => 'Rp 189.000', 'status' => 'selesai'],
                                            ['produk' => 'E-Book Belajar Laravel', 'no' => 'INV-20260729-02', 'tanggal' => '29 Jul 2026', 'total' => 'Rp 75.000', 'status' => 'dikirim'],
                                            ['produk' => 'Sepatu Sneakers Putih', 'no' => 'INV-20260730-03', 'tanggal' => '30 Jul 2026', 'total' => 'Rp 349.000', 'status' => 'diproses'],
                                            ['produk' => 'Tas Selempang Kanvas', 'no' => 'INV-20260731-04', 'tanggal' => '31 Jul 2026', 'total' => 'Rp 129.000', 'status' => 'dibatalkan'],
                                        ];
                                    @endphp
                                    @foreach($pesananTerbaru as $p)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="order-item-thumb"><i class="bi bi-box-seam"></i></div>
                                                <span class="fw-semibold">{{ $p['produk'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $p['no'] }}</td>
                                        <td>{{ $p['tanggal'] }}</td>
                                        <td class="fw-semibold">{{ $p['total'] }}</td>
                                        <td><span class="badge-status badge-{{ $p['status'] }}">{{ ucfirst($p['status']) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Chart aktivitas belanja --}}
                    <div class="card-soft delay2 reveal">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="section-title">Aktivitas Belanja</div>
                                <div class="section-sub">Grafik pengeluaran 6 bulan terakhir</div>
                            </div>
                            <select class="form-select form-select-sm" style="width:auto; border-radius:10px; border-color:var(--border-color);">
                                <option>6 Bulan Terakhir</option>
                                <option>1 Tahun Terakhir</option>
                            </select>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="chartBelanja"></canvas>
                        </div>
                    </div>
                </div>

                {{-- ===================== KANAN: AKSI CEPAT, KATEGORI, PROMO ===================== --}}
                <div class="col-lg-4">

                    <div class="card-soft mb-4 delay1">
                        <div class="section-title mb-3">Aksi Cepat</div>
                        <div class="d-grid gap-2">
                            <a href="#" class="quick-action">
                                <i class="bi bi-shop"></i> Belanja Sekarang
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-cart3"></i> Lihat Keranjang
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-receipt"></i> Lacak Pesanan
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-cloud-arrow-down"></i> Download Saya
                            </a>
                        </div>
                    </div>

                    {{-- Kategori belanja (progress animasi) --}}
                    <div class="card-soft mb-4 delay2 reveal">
                        <div class="section-title mb-3">Kategori Favorit</div>

                        <div class="progress-item">
                            <div class="top"><span>Fashion</span><span class="fw-semibold">65%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="65"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="top"><span>Elektronik</span><span class="fw-semibold">40%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="40"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="top"><span>E-Book</span><span class="fw-semibold">78%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="78"></div></div>
                        </div>
                        <div class="progress-item mb-0">
                            <div class="top"><span>Kesehatan</span><span class="fw-semibold">30%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="30"></div></div>
                        </div>
                    </div>

                    {{-- Promo --}}
                    <div class="card-soft promo-card delay3 reveal">
                        <i class="bi bi-gift-fill fs-2 mb-2 gift"></i>
                        <div class="fw-bold fs-6 mb-1">Diskon Spesial 25%!</div>
                        <p class="mb-3" style="font-size:13px; opacity:.9;">
                            Nikmati diskon untuk semua produk kategori Fashion hingga akhir bulan ini.
                        </p>
                        <a href="#" class="btn btn-light btn-sm fw-semibold">
                            Belanja Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ================= Toggle sidebar (mobile) =================
        const btnToggle = document.getElementById('btnToggleSidebar');
        const sidebar   = document.getElementById('sidebarPembeli');
        const overlay   = document.getElementById('sidebarOverlay');
        if (btnToggle) {
            btnToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // ================= Animasi angka statistik (counter) =================
        document.querySelectorAll('[data-count]').forEach(el => {
            const target  = parseInt(el.dataset.count, 10) || 0;
            const prefix  = el.dataset.prefix || '';
            const suffix  = el.dataset.suffix || '';
            const duration = 1400;
            const startTime = performance.now();

            function tick(now){
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                const value = Math.floor(eased * target);
                el.textContent = prefix + value.toLocaleString('id-ID') + (suffix ? ' ' + suffix : '');
                if (progress < 1) requestAnimationFrame(tick);
                else el.textContent = prefix + target.toLocaleString('id-ID') + (suffix ? ' ' + suffix : '');
            }
            requestAnimationFrame(tick);
        });

        // ================= Animasi progress bar kategori =================
        document.querySelectorAll('.progress-fill').forEach(bar => {
            const width = bar.dataset.width;
            setTimeout(() => { bar.style.width = width + '%'; }, 400);
        });

        // ================= Scroll reveal =================
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach(el => observer.observe(el));

        // ================= Chart.js: Aktivitas Belanja =================
        const ctx = document.getElementById('chartBelanja');
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Belanja (Rp ribu)',
                        data: [850, 1200, 950, 1600, 1350, 1850],
                        borderColor: '#2563eb',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.45,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1400, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e3a8a',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (c) => 'Rp ' + c.parsed.y.toLocaleString('id-ID') + ' rb'
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 12 } } },
                        y: {
                            grid: { color: '#eff6ff' },
                            ticks: {
                                color: '#64748b', font: { size: 12 },
                                callback: (v) => v + 'rb'
                            }
                        }
                    }
                }
            });
        }

        // ================= Efek ripple kecil saat tombol diklik =================
        document.querySelectorAll('.btn-primary-soft, .btn-outline-blue, .quick-action').forEach(btn => {
            btn.addEventListener('click', function(e){
                const circle = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                circle.style.cssText = `
                    position:absolute; border-radius:50%; pointer-events:none;
                    width:${size}px; height:${size}px;
                    left:${e.clientX - rect.left - size/2}px; top:${e.clientY - rect.top - size/2}px;
                    background:rgba(255,255,255,0.5); transform:scale(0);
                    animation: rippleEffect .5s ease-out;
                `;
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(circle);
                setTimeout(() => circle.remove(), 500);
            });
        });
    </script>
</body>
</html>
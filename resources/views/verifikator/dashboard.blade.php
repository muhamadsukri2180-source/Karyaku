<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Verifikator - Karyaku</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        /* =========================================================
           Tema Dashboard Verifikator - Biru & Putih (Animated)
           ========================================================= */
        :root{
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-darker: #14225c;
            --primary-light: #eff6ff;
            --primary-soft: #dbeafe;

            /* Sidebar: biru muda, tidak terlalu terang */
            --sidebar-top: #5b7fb0;
            --sidebar-bottom: #6f93c2;
            --sidebar-text: #eef3fb;
            --sidebar-text-muted: rgba(238,243,251,0.68);
            --sidebar-border: rgba(255,255,255,0.14);
            --sidebar-hover: rgba(255,255,255,0.10);

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
            background: linear-gradient(180deg, var(--sidebar-top) 0%, var(--sidebar-bottom) 100%);
            color: var(--sidebar-text);
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
            border-bottom: 1px solid var(--sidebar-border);
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
        .sidebar .brand-text h5{ margin: 0; font-weight: 700; font-size: 16px; color: var(--sidebar-text); }
        .sidebar .brand-text small{ color: var(--sidebar-text-muted); font-size: 11px; }

        .sidebar .nav-section-title{
            font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
            color: var(--sidebar-text-muted); padding: 10px 12px 6px;
        }

        .sidebar .nav-link{
            position: relative;
            display: flex; align-items: center; gap: 12px;
            color: var(--sidebar-text-muted);
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
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
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
        .sidebar .nav-link .badge-count.urgent{ background: #ef4444; }
        .sidebar .nav-link.active .badge-count{ background: var(--primary-soft); color: var(--primary-dark); }
        .sidebar .nav-link:hover .badge-count{ transform: scale(1.12); }

        .sidebar .sidebar-footer{ margin-top: auto; padding-top: 16px; border-top: 1px solid var(--sidebar-border); }
        .sidebar .logout-link{
            display: flex; align-items: center; gap: 10px;
            color: #ffd9d9; padding: 10px 14px; border-radius: 12px;
            font-size: 14px; font-weight: 500; text-decoration: none;
            transition: .2s;
        }
        .sidebar .logout-link:hover{ background: var(--sidebar-hover); color: #fff; padding-left: 18px; }

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
            cursor: pointer;
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
            cursor: pointer;
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
        .stat-icon.red{ background: #fee2e2; color: #dc2626; }

        .stat-card h3{ margin: 0; font-weight: 700; font-size: 23px; }
        .stat-card span.label{ color: var(--text-muted); font-size: 13px; }
        .trend{ font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; }
        .trend.up{ color: #16a34a; }
        .trend.down{ color: #ef4444; }
        .trend.neutral{ color: var(--text-muted); }

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

        /* ---------------- Tabs ---------------- */
        .verif-tabs{
            display: flex; gap: 6px; background: var(--primary-light);
            padding: 5px; border-radius: 12px; margin-bottom: 18px; flex-wrap: wrap;
        }
        .verif-tabs button{
            border: none; background: transparent; padding: 8px 16px;
            border-radius: 9px; font-size: 13.2px; font-weight: 600;
            color: var(--text-muted); transition: all .2s ease; cursor: pointer;
        }
        .verif-tabs button.active{ background: var(--white); color: var(--primary); box-shadow: var(--shadow); }
        .verif-tabs button:hover:not(.active){ color: var(--primary); }

        .verif-pane{ display: none; }
        .verif-pane.active{ display: block; animation: cardIn .4s ease; }

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

        .btn-approve{
            background: #dcfce7; color: #15803d; border: none; border-radius: 8px;
            width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
            transition: all .2s ease;
        }
        .btn-approve:hover{ background: #16a34a; color: #fff; transform: scale(1.08); }
        .btn-reject{
            background: #fee2e2; color: #b91c1c; border: none; border-radius: 8px;
            width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
            transition: all .2s ease;
        }
        .btn-reject:hover{ background: #dc2626; color: #fff; transform: scale(1.08); }
        .btn-view{
            background: var(--primary-light); color: var(--primary); border: none; border-radius: 8px;
            width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
            transition: all .2s ease;
        }
        .btn-view:hover{ background: var(--primary); color: #fff; transform: scale(1.08); }

        /* ---------------- Badges ---------------- */
        .badge-status{ padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
        .badge-menunggu{ background: #fef3c7; color: #b45309; }
        .badge-diproses{ background: #dbeafe; color: #1d4ed8; }
        .badge-disetujui{ background: #dcfce7; color: #15803d; }
        .badge-ditolak{ background: #fee2e2; color: #b91c1c; }

        .prioritas-tinggi{ color: #dc2626; font-weight: 600; }
        .prioritas-sedang{ color: #f97316; font-weight: 600; }
        .prioritas-rendah{ color: #16a34a; font-weight: 600; }

        /* ---------------- Table ---------------- */
        .table-responsive{ overflow-x: auto; }
        .table-soft{ width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 640px; }
        .table-soft thead th{
            font-size: 12px; text-transform: uppercase; color: var(--text-muted);
            font-weight: 600; padding: 0 14px 8px; border: none; white-space: nowrap;
        }
        .table-soft tbody tr{ background: var(--white); transition: transform .2s ease, box-shadow .2s ease; }
        .table-soft tbody tr:hover{ transform: translateX(3px); box-shadow: var(--shadow); }
        .table-soft tbody td{
            padding: 14px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);
            font-size: 13.5px; vertical-align: middle;
        }
        .table-soft tbody td:first-child{ border-left: 1px solid var(--border-color); border-radius: 12px 0 0 12px; }
        .table-soft tbody td:last-child{ border-right: 1px solid var(--border-color); border-radius: 0 12px 12px 0; }

        .entity-thumb{
            width: 38px; height: 38px; font-size: 16px; border-radius: 10px;
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s ease; flex-shrink: 0;
        }
        .table-soft tbody tr:hover .entity-thumb{ transform: scale(1.1) rotate(-6deg); background: var(--primary-soft); }

        /* ---------------- Laporan masuk (list) ---------------- */
        .laporan-item{
            display: flex; gap: 14px; align-items: flex-start;
            padding: 14px; border-radius: 14px; border: 1px solid var(--border-color);
            margin-bottom: 12px; transition: all .2s ease; cursor: pointer;
        }
        .laporan-item:hover{ background: var(--primary-light); transform: translateX(4px); box-shadow: var(--shadow); }
        .laporan-item .icon-wrap{
            width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 18px;
        }
        .laporan-item .icon-wrap.merah{ background: #fee2e2; color: #dc2626; }
        .laporan-item .icon-wrap.kuning{ background: #fef3c7; color: #b45309; }
        .laporan-item .icon-wrap.biru{ background: var(--primary-soft); color: var(--primary); }
        .laporan-item h6{ margin: 0 0 3px; font-size: 13.8px; font-weight: 600; }
        .laporan-item p{ margin: 0; font-size: 12.5px; color: var(--text-muted); }
        .laporan-item .waktu{ font-size: 11px; color: var(--text-muted); white-space: nowrap; }

        /* ---------------- Progress bars ---------------- */
        .progress-item{ margin-bottom: 16px; }
        .progress-item .top{ display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
        .progress-track{ height: 8px; background: var(--primary-light); border-radius: 20px; overflow: hidden; }
        .progress-fill{
            height: 100%; border-radius: 20px;
            background: linear-gradient(90deg, var(--primary), #60a5fa);
            width: 0%;
            transition: width 1.2s cubic-bezier(.22,1,.36,1);
        }

        /* ---------------- Info / alert card ---------------- */
        .info-card{
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #fff; border: none; position: relative; overflow: hidden;
        }
        .info-card::before{
            content: "";
            position: absolute;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
            top: -60px; right: -40px;
            animation: floatBlob 8s ease-in-out infinite;
        }
        .info-card i.badge-icon{ animation: bounce 2.2s ease-in-out infinite; display: inline-block; }
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
        .chart-wrap-sm{ position: relative; height: 190px; }

        /* scrollbar cantik */
        ::-webkit-scrollbar{ width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }

        @keyframes rippleEffect{ to{ transform: scale(2.6); opacity: 0; } }

        /* ---------------- Responsive tweaks ---------------- */
        @media (max-width: 576px){
            .main-content{ padding: 20px 14px 40px; }
            .topbar h2{ font-size: 21px; }
            .card-soft{ padding: 16px; }
        }
    </style>
</head>
<body>

    <div class="bg-decor"><span></span><span></span><span></span></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-wrapper">

        {{-- ===================== SIDEBAR ===================== --}}
        <aside class="sidebar" id="sidebarVerifikator">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-patch-check-fill"></i></div>
                <div class="brand-text">
                    <h5>Karyaku</h5>
                    <small>Panel Verifikator</small>
                </div>
            </div>

            <div class="nav-section-title">Menu Utama</div>
            <nav class="nav flex-column">
                <a href="#" class="nav-link active">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-briefcase-fill"></i> Verifikasi Jasa
                    <span class="badge-count urgent">{{ $antrianJasa ?? 7 }}</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-person-vcard-fill"></i> Verifikasi Identitas
                    <span class="badge-count urgent">{{ $antrianIdentitas ?? 4 }}</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-flag-fill"></i> Laporan Masuk
                    <span class="badge-count">{{ $laporanMasuk ?? 3 }}</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-clock-history"></i> Riwayat Verifikasi
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
                        <h2>Halo, {{ auth()->user()->name ?? 'Dewi Anggraini' }} <span class="wave">👋</span></h2>
                        <p>Berikut ringkasan tugas verifikasi Anda hari ini</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari pengajuan, nama, atau ID...">
                    </div>

                    <div class="topbar-actions">
                        <a href="#" class="icon-btn" title="Laporan">
                            <i class="bi bi-flag"></i><span class="dot"></span>
                        </a>
                        <button class="icon-btn" title="Notifikasi">
                            <i class="bi bi-bell"></i><span class="dot"></span>
                        </button>

                        <div class="user-chip">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Dewi Anggraini') }}&background=2563eb&color=fff" alt="avatar">
                            <div>
                                <div class="name">{{ auth()->user()->name ?? 'Dewi Anggraini' }}</div>
                                <div class="role">Verifikator</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== STAT CARDS ===================== --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-briefcase"></i></div>
                        <div>
                            <h3 data-count="{{ $antrianJasa ?? 7 }}">0</h3>
                            <span class="label">Antrian Verifikasi Jasa</span><br>
                            <span class="trend down"><i class="bi bi-arrow-up-short"></i>Perlu segera diproses</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="bi bi-person-vcard"></i></div>
                        <div>
                            <h3 data-count="{{ $antrianIdentitas ?? 4 }}">0</h3>
                            <span class="label">Antrian Verifikasi Identitas</span><br>
                            <span class="trend down"><i class="bi bi-arrow-up-short"></i>Perlu segera diproses</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-flag"></i></div>
                        <div>
                            <h3 data-count="{{ $laporanMasuk ?? 3 }}">0</h3>
                            <span class="label">Laporan Masuk</span><br>
                            <span class="trend neutral"><i class="bi bi-dot"></i>Menunggu tindak lanjut</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-patch-check"></i></div>
                        <div>
                            <h3 data-count="{{ $totalDiverifikasi ?? 152 }}">0</h3>
                            <span class="label">Terverifikasi Bulan Ini</span><br>
                            <span class="trend up"><i class="bi bi-arrow-up-short"></i>15% dari bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- ===================== KIRI: TABEL VERIFIKASI + CHART ===================== --}}
                <div class="col-lg-8">

                    {{-- Tab Antrian Verifikasi --}}
                    <div class="card-soft mb-4 delay1">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <div>
                                <div class="section-title">Antrian Verifikasi</div>
                                <div class="section-sub">Pengajuan yang menunggu tindakan Anda</div>
                            </div>
                            <a href="#" class="btn-outline-blue">Lihat Semua</a>
                        </div>

                        <div class="verif-tabs">
                            <button class="active" data-tab="jasa">Verifikasi Jasa</button>
                            <button data-tab="identitas">Verifikasi Identitas</button>
                        </div>

                        {{-- Pane: Verifikasi Jasa --}}
                        <div class="verif-pane active" id="pane-jasa">
                            <div class="table-responsive">
                                <table class="table-soft">
                                    <thead>
                                        <tr>
                                            <th>Penyedia Jasa</th>
                                            <th>Jenis Layanan</th>
                                            <th>Tanggal Ajukan</th>
                                            <th>Prioritas</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $antrianVerifikasiJasa = $antrianVerifikasiJasa ?? [
                                                ['nama' => 'Andi Prasetyo', 'jenis' => 'Jasa Renovasi Rumah', 'tanggal' => '31 Jul 2026', 'prioritas' => 'tinggi', 'status' => 'menunggu'],
                                                ['nama' => 'Siti Rahma', 'jenis' => 'Jasa Desain Grafis', 'tanggal' => '30 Jul 2026', 'prioritas' => 'sedang', 'status' => 'diproses'],
                                                ['nama' => 'CV Maju Bersama', 'jenis' => 'Jasa Catering Event', 'tanggal' => '29 Jul 2026', 'prioritas' => 'rendah', 'status' => 'menunggu'],
                                                ['nama' => 'Rizki Ramadhan', 'jenis' => 'Jasa Service AC', 'tanggal' => '28 Jul 2026', 'prioritas' => 'sedang', 'status' => 'menunggu'],
                                            ];
                                        @endphp
                                        @foreach($antrianVerifikasiJasa as $j)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="entity-thumb"><i class="bi bi-briefcase"></i></div>
                                                    <span class="fw-semibold">{{ $j['nama'] }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $j['jenis'] }}</td>
                                            <td>{{ $j['tanggal'] }}</td>
                                            <td><span class="prioritas-{{ $j['prioritas'] }}">{{ ucfirst($j['prioritas']) }}</span></td>
                                            <td><span class="badge-status badge-{{ $j['status'] }}">{{ ucfirst($j['status']) }}</span></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="#" class="btn-view" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                                    <a href="#" class="btn-approve" title="Setujui"><i class="bi bi-check-lg"></i></a>
                                                    <a href="#" class="btn-reject" title="Tolak"><i class="bi bi-x-lg"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pane: Verifikasi Identitas --}}
                        <div class="verif-pane" id="pane-identitas">
                            <div class="table-responsive">
                                <table class="table-soft">
                                    <thead>
                                        <tr>
                                            <th>Nama Pengguna</th>
                                            <th>No. KTP</th>
                                            <th>Tanggal Upload</th>
                                            <th>Prioritas</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $antrianVerifikasiIdentitas = $antrianVerifikasiIdentitas ?? [
                                                ['nama' => 'Budi Santoso', 'ktp' => '3201********0001', 'tanggal' => '31 Jul 2026', 'prioritas' => 'tinggi', 'status' => 'menunggu'],
                                                ['nama' => 'Maria Angelina', 'ktp' => '3273********0045', 'tanggal' => '30 Jul 2026', 'prioritas' => 'sedang', 'status' => 'menunggu'],
                                                ['nama' => 'Fajar Nugroho', 'ktp' => '3374********0132', 'tanggal' => '29 Jul 2026', 'prioritas' => 'rendah', 'status' => 'diproses'],
                                            ];
                                        @endphp
                                        @foreach($antrianVerifikasiIdentitas as $i)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="entity-thumb"><i class="bi bi-person-vcard"></i></div>
                                                    <span class="fw-semibold">{{ $i['nama'] }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $i['ktp'] }}</td>
                                            <td>{{ $i['tanggal'] }}</td>
                                            <td><span class="prioritas-{{ $i['prioritas'] }}">{{ ucfirst($i['prioritas']) }}</span></td>
                                            <td><span class="badge-status badge-{{ $i['status'] }}">{{ ucfirst($i['status']) }}</span></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="#" class="btn-view" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                                    <a href="#" class="btn-approve" title="Setujui"><i class="bi bi-check-lg"></i></a>
                                                    <a href="#" class="btn-reject" title="Tolak"><i class="bi bi-x-lg"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Chart aktivitas verifikasi --}}
                    <div class="card-soft delay2 reveal">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="section-title">Aktivitas Verifikasi</div>
                                <div class="section-sub">Jumlah verifikasi disetujui vs ditolak, 6 bulan terakhir</div>
                            </div>
                            <select class="form-select form-select-sm" style="width:auto; border-radius:10px; border-color:var(--border-color);">
                                <option>6 Bulan Terakhir</option>
                                <option>1 Tahun Terakhir</option>
                            </select>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="chartVerifikasi"></canvas>
                        </div>
                    </div>
                </div>

                {{-- ===================== KANAN: AKSI CEPAT, LAPORAN, RINGKASAN ===================== --}}
                <div class="col-lg-4">

                    <div class="card-soft mb-4 delay1">
                        <div class="section-title mb-3">Aksi Cepat</div>
                        <div class="d-grid gap-2">
                            <a href="#" class="quick-action">
                                <i class="bi bi-briefcase"></i> Proses Verifikasi Jasa
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-person-vcard"></i> Proses Verifikasi Identitas
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-flag"></i> Tinjau Laporan Masuk
                            </a>
                            <a href="#" class="quick-action">
                                <i class="bi bi-clock-history"></i> Lihat Riwayat Verifikasi
                            </a>
                        </div>
                    </div>

                    {{-- Laporan masuk --}}
                    <div class="card-soft mb-4 delay2 reveal">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">Laporan Masuk</div>
                            <a href="#" class="btn-outline-blue" style="padding:6px 12px; font-size:12px;">Lihat Semua</a>
                        </div>

                        @php
                            $laporanTerbaru = $laporanTerbaru ?? [
                                ['judul' => 'Penyedia jasa fiktif', 'deskripsi' => 'Dilaporkan oleh pengguna terkait dugaan jasa tidak sesuai deskripsi.', 'waktu' => '2 jam lalu', 'tipe' => 'merah', 'icon' => 'bi-exclamation-triangle-fill'],
                                ['judul' => 'Identitas mencurigakan', 'deskripsi' => 'Dokumen KTP terindikasi tidak sesuai dengan foto profil.', 'waktu' => '5 jam lalu', 'tipe' => 'kuning', 'icon' => 'bi-person-x-fill'],
                                ['judul' => 'Ulasan tidak wajar', 'deskripsi' => 'Beberapa ulasan pada satu akun terindikasi dibuat otomatis.', 'waktu' => '1 hari lalu', 'tipe' => 'biru', 'icon' => 'bi-chat-square-dots-fill'],
                            ];
                        @endphp

                        @foreach($laporanTerbaru as $l)
                        <div class="laporan-item">
                            <div class="icon-wrap {{ $l['tipe'] }}"><i class="bi {{ $l['icon'] }}"></i></div>
                            <div class="flex-grow-1">
                                <h6>{{ $l['judul'] }}</h6>
                                <p>{{ $l['deskripsi'] }}</p>
                            </div>
                            <span class="waktu">{{ $l['waktu'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan tingkat persetujuan --}}
                    <div class="card-soft mb-4 delay2 reveal">
                        <div class="section-title mb-3">Tingkat Persetujuan</div>

                        <div class="progress-item">
                            <div class="top"><span>Verifikasi Jasa</span><span class="fw-semibold">82%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="82"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="top"><span>Verifikasi Identitas</span><span class="fw-semibold">91%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="91"></div></div>
                        </div>
                        <div class="progress-item mb-0">
                            <div class="top"><span>Laporan Ditindaklanjuti</span><span class="fw-semibold">67%</span></div>
                            <div class="progress-track"><div class="progress-fill" data-width="67"></div></div>
                        </div>
                    </div>

                    {{-- Info card --}}
                    <div class="card-soft info-card delay3 reveal">
                        <i class="bi bi-shield-check badge-icon fs-2 mb-2"></i>
                        <div class="fw-bold fs-6 mb-1">Jaga Kualitas Platform</div>
                        <p class="mb-3" style="font-size:13px; opacity:.9;">
                            Pastikan setiap verifikasi dilakukan teliti demi keamanan dan kepercayaan pengguna Karyaku.
                        </p>
                        <a href="#" class="btn btn-light btn-sm fw-semibold">
                            Lihat Panduan Verifikasi <i class="bi bi-arrow-right"></i>
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
        const sidebar   = document.getElementById('sidebarVerifikator');
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
            const duration = 1400;
            const startTime = performance.now();

            function tick(now){
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                const value = Math.floor(eased * target);
                el.textContent = value.toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(tick);
                else el.textContent = target.toLocaleString('id-ID');
            }
            requestAnimationFrame(tick);
        });

        // ================= Animasi progress bar =================
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

        // ================= Tab Verifikasi Jasa / Identitas =================
        document.querySelectorAll('.verif-tabs button').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.verif-tabs button').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.verif-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('pane-' + btn.dataset.tab).classList.add('active');
            });
        });

        // ================= Chart.js: Aktivitas Verifikasi =================
        const ctx = document.getElementById('chartVerifikasi');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Disetujui',
                            data: [38, 45, 40, 52, 48, 60],
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            maxBarThickness: 22,
                        },
                        {
                            label: 'Ditolak',
                            data: [8, 6, 10, 5, 7, 4],
                            backgroundColor: '#93c5fd',
                            borderRadius: 6,
                            maxBarThickness: 22,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1400, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { boxWidth: 12, font: { size: 11 } } },
                        tooltip: {
                            backgroundColor: '#1e3a8a',
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 12 } } },
                        y: {
                            grid: { color: '#eff6ff' },
                            ticks: { color: '#64748b', font: { size: 12 } }
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
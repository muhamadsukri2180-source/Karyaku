<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Dashboard Admin</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif']
                    },
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
                        skyDeep: '#0B3D62',
                        skyDeeper: '#082C48',
                        skyPale: '#EFF8FF',
                        coral: '#FF7A59',
                        mint: '#10B981',
                        ink: '#0F2A44'
                    }
                }
            }
        }
    </script>
    <style>
        .active-menu {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #ffffff;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(14, 165, 233, 0.5); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) {
            #sidebar.closed { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
        }

        .submenu {
            max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out;
        }
        .submenu.open { max-height: 300px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: default;
        }
        .card-hover:hover {
            transform: scale(1.025) translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(14, 165, 233, 0.3);
            border-color: rgba(14, 165, 233, 0.6);
        }

        @keyframes bluePulseGlow {
            0%   { transform: scale(1) translate(0, 0);      opacity: 0.35; }
            50%  { transform: scale(1.25) translate(-6px, 6px); opacity: 0.55; }
            100% { transform: scale(1) translate(0, 0);      opacity: 0.35; }
        }
        .blob-live {
            animation: bluePulseGlow 3.5s ease-in-out infinite;
        }
        .group:hover .blob-live {
            animation-play-state: paused;
        }

        .dropdown {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            transition: all 300ms;
            display: flex;
            flex-direction: column;
            min-height: 42px;
            background-color: white;
            overflow: visible;
            position: relative;
            width: 160px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .dropdown input:where(:checked) ~ .list {
            opacity: 1;
            transform: translateY(0) scale(1);
            transition: all 400ms ease;
            margin-top: 6px;
            padding-top: 4px;
            margin-bottom: 4px;
            height: auto;
            max-height: 16rem;
            pointer-events: auto;
        }
        .dropdown input:where(:not(:checked)) ~ .list {
            opacity: 0;
            transform: translateY(1rem);
            margin-top: -100%;
            user-select: none;
            height: 0px;
            max-height: 0px;
            min-height: 0px;
            pointer-events: none;
            transition: all 300ms ease-out;
        }
        .trigger {
            cursor: pointer;
            list-style: none;
            user-select: none;
            font-weight: 600;
            color: #334155;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.875rem;
            height: 42px;
            position: relative;
            z-index: 99;
            border-radius: inherit;
            background-color: white;
            font-size: 13px;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        .trigger::after {
            content: "\f078";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 10px;
            color: #64748b;
            transition: transform 350ms ease;
            margin-left: 8px;
        }
        .dropdown input:where(:checked) + .trigger::after {
            transform: rotate(180deg);
        }
        .list {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 16rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            gap: 0.375rem;
            padding: 0.5rem;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            z-index: 100;
            list-style: none;
        }
        .listitem {
            list-style: none;
            width: 100%;
        }
        .article {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            width: 100%;
            border: 1px solid #e2e8f0;
            display: block;
            background-color: #f8fafc;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s;
        }
        .article:hover {
            background-color: #e0f2fe;
            color: #0284c7;
            border-color: #bae6fd;
        }
        .article.selected {
            background-color: #0ea5e9;
            color: #fff;
            border-color: #0ea5e9;
        }

        .webkit-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
        }
        .webkit-scrollbar::-webkit-scrollbar-track {
            background: #0000;
        }
        .webkit-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .webkit-scrollbar:hover::-webkit-scrollbar-thumb {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

    <div class="flex min-h-screen relative">

        <!-- OVERLAY UNTUK MOBILE SAAT SIDEBAR BUKA -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR COMPONENT -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <!-- Brand Logo -->
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg shadow-skyDeep/20">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Profile Widget -->
            @php
                $adminUser = auth()->user();
                $adminName = $adminUser->name ?? 'Admin';
                $initials  = collect(explode(' ', trim($adminName)))
                            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                            ->take(2)
                            ->implode('');
            @endphp
            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">
                    {{ $initials ?: 'AD' }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $adminName }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] text-sky-100 truncate">Online</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-sky-200 w-3 text-center"></i> Akun Verifikator
                        </a>
                        <a href="{{ route('admin.manajemen.akun_service') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-headset text-[10px] text-sky-200 w-3 text-center"></i> Akun Customer Service
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
                            </div>
                            @if($pendingProductsCount > 0)
                                <span class="bg-amber-400 text-slate-900 text-[9px] px-1.5 py-0.5 rounded font-extrabold">{{ $pendingProductsCount }} Baru</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center group-hover:text-amber-300 transition-colors"></i>
                    <span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-server w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Maintenance & Backup</span>
                    </div>
                    @if($isMaintenance)
                        <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                    @endif
                </a>

                <!-- MENU PELANGGARAN YANG DITAMBAHKAN -->
                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/15">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-power-off"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 w-full">

            <!-- TOP NAVBAR -->
            <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 backdrop-blur-xl border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-300 shadow-sm">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Ikhtisar Panel</h2>
                        <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">Pantau statistik penjualan, verifikasi produk, dan aktivitas user.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($isMaintenance)
                        <a href="{{ route('admin.maintenance') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[11px] font-bold shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation"></i> Maintenance Aktif
                        </a>
                    @endif
                    @php $totalNotif = $pendingIdentityCount + $pendingProductsCount + $pendingReportsCount; @endphp
                    <button class="w-10 h-10 rounded-full bg-white hover:bg-sky-50 hover:text-sky text-slate-700 flex items-center justify-center transition border border-sky-300 relative shadow-sm">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if($totalNotif > 0)
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-coral border-2 border-white"></span>
                        @endif
                    </button>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-8 overflow-y-auto no-scrollbar">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- TOP METRICS CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                   <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div>
                                <span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider group-hover:text-sky-600 transition-colors">Total Pesanan</span>
                                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/40">
                                <i class="fa-solid fa-bag-shopping text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 relative z-10">
                            <span class="bg-emerald-100 text-emerald-900 text-[10px] font-extrabold px-2 py-0.5 rounded-md flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($monthlySales, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-slate-600 font-medium">Pesanan bulan ini</span>
                        </div>
                    </div>

                    <!-- Card 2: Komisi Platform -->
                    <div class="bg-gradient-to-br from-emerald-50 via-emerald-100/60 to-teal-200/50 border-l-4 border-emerald-500 border border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-emerald-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div>
                                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Komisi Platform</span>
                                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">Rp {{ number_format($platformCommission, 0, ',', '.') }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/40">
                                <i class="fa-solid fa-wallet text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 relative z-10">
                            <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-emerald-200 shadow-sm px-2 py-0.5 rounded-md">
                                5% dari Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Card 3: Produk -->
                    <div class="bg-gradient-to-br from-amber-50 via-amber-100/60 to-orange-200/50 border-l-4 border-amber-500 border border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-amber-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div>
                                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider group-hover:text-amber-600 transition-colors">Total Produk</span>
                                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/40">
                                <i class="fa-solid fa-swatchbook text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 relative z-10">
                            <span class="bg-amber-200 text-amber-900 border border-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 shadow-sm">
                                <i class="fa-regular fa-clock"></i> {{ $pendingProductsCount }} Antrean
                            </span>
                            <span class="text-[10px] text-slate-600 font-medium">Verifikasi</span>
                        </div>
                    </div>

                    <!-- Card 4: Pengguna -->
                    <div class="bg-gradient-to-br from-purple-50 via-purple-100/60 to-indigo-200/50 border-l-4 border-purple-500 border border-purple-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-purple-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div>
                                <span class="text-[11px] font-bold text-purple-900 uppercase tracking-wider group-hover:text-purple-600 transition-colors">Pengguna Aktif</span>
                                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUsers, 0, ',', '.') }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold shadow-md shadow-purple-500/40">
                                <i class="fa-solid fa-users text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 relative z-10">
                            <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-purple-200 shadow-sm px-2 py-0.5 rounded-md">
                                Penjual & Pembeli
                            </span>
                        </div>
                    </div>

                </div>

                <!-- SECTION 1: GRAFIK & KATEGORI -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <div class="lg:col-span-8 bg-gradient-to-br from-white via-sky-50/70 to-blue-100/50 border border-sky-300/80 p-6 rounded-2xl card-hover shadow-md">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-lg font-display">Statistik Pemesanan Jasa</h3>
                                <p id="chartSubtitle" class="text-[11px] text-slate-600 mt-1">Pertumbuhan transaksi berdasarkan data order (Tahun {{ $year }})</p>
                            </div>

                            <!-- Custom Real-time Dynamic Year Dropdown -->
                            <div class="dropdown">
                                <input type="checkbox" id="yearDropdownToggle" class="sr-only">
                                <label for="yearDropdownToggle" class="trigger">
                                    <span id="selectedYearText">Tahun {{ $year }}</span>
                                </label>
                                <ul id="yearList" class="list webkit-scrollbar">
                                    <!-- Populated dynamically via JS -->
                                </ul>
                            </div>
                        </div>

                        <div class="h-64 w-full">
                            <canvas id="yearlyChart"></canvas>
                        </div>
                    </div>

                    <div class="lg:col-span-4 bg-gradient-to-br from-white via-indigo-50/70 to-purple-100/40 border border-indigo-200/80 p-6 rounded-2xl card-hover shadow-md">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-lg font-display">Top Kategori</h3>
                                <p class="text-[11px] text-slate-600 mt-1">Berdasarkan order item</p>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white hover:bg-indigo-50 text-slate-500 flex items-center justify-center transition border border-indigo-200 shadow-sm">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            @php
                                $catColors = ['sky', 'emerald', 'amber', 'slate'];
                                $catGradients = [
                                    'sky'     => 'from-sky-500 to-sky-700',
                                    'emerald' => 'from-emerald-500 to-teal-600',
                                    'amber'   => 'from-amber-500 to-orange-600',
                                    'slate'   => 'from-slate-400 to-slate-600',
                                ];
                                $catText = [
                                    'sky' => 'text-sky-600', 'emerald' => 'text-emerald-600',
                                    'amber' => 'text-amber-600', 'slate' => 'text-slate-500',
                                ];
                            @endphp

                            @forelse($topCategories as $i => $cat)
                                @php $c = $catColors[$i % 4]; @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1.5">
                                        <span class="font-bold text-slate-800">{{ $cat->name }}</span>
                                        <span class="font-bold {{ $catText[$c] }}">{{ $cat->percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200/70 rounded-full h-2">
                                        <div class="bg-gradient-to-r {{ $catGradients[$c] }} h-2 rounded-full shadow-sm" style="width: {{ $cat->percentage }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-1">{{ number_format($cat->order_count, 0, ',', '.') }} Transaksi</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 text-center py-6">Belum ada data transaksi kategori.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: ANTREAN MODERASI & AKTIVITAS -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <div class="lg:col-span-7 bg-gradient-to-br from-white via-amber-50/50 to-orange-100/50 border border-amber-200/80 p-6 rounded-2xl card-hover shadow-md">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2">
                                    <i class="fa-solid fa-clipboard-list text-amber-600"></i> Antrean Moderasi
                                </h3>
                                <p class="text-[11px] text-slate-600 mt-1">Tugas yang membutuhkan persetujuan/tinjauan Admin.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-xl border border-blue-200 bg-white/90 shadow-sm hover:border-blue-400 hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg group-hover:scale-110 transition-transform shadow-inner">
                                        <i class="fa-solid fa-id-card"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Verifikasi Identitas Kreator</h4>
                                        <p class="text-[11px] text-slate-600 mt-0.5">Terdapat {{ $pendingIdentityCount }} pengajuan KTP/Identitas baru</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.verifikator') }}" class="relative group border-none bg-transparent p-0 outline-none cursor-pointer">
                                    <span class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-15 rounded-lg transform translate-y-0.5 transition duration-300 group-hover:translate-y-1"></span>
                                    <span class="absolute top-0 left-0 w-full h-full rounded-lg bg-gradient-to-l from-slate-200 via-slate-300 to-slate-200"></span>
                                    <div class="relative px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700 hover:bg-blue-600 hover:text-white transform -translate-y-0.5 transition duration-300 group-hover:-translate-y-1 shadow-sm">
                                        Tinjau
                                    </div>
                                </a>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-xl border border-amber-200 bg-white/90 shadow-sm hover:border-amber-400 hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg group-hover:scale-110 transition-transform shadow-inner">
                                        <i class="fa-solid fa-photo-film"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Persetujuan Jasa/Karya Baru</h4>
                                        <p class="text-[11px] text-slate-600 mt-0.5">{{ $pendingProductsCount }} Produk menunggu ditinjau kelayakannya</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.products') }}" class="relative group border-none bg-transparent p-0 outline-none cursor-pointer">
                                    <span class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-15 rounded-lg transform translate-y-0.5 transition duration-300 group-hover:translate-y-1"></span>
                                    <span class="absolute top-0 left-0 w-full h-full rounded-lg bg-gradient-to-l from-slate-200 via-slate-300 to-slate-200"></span>
                                    <div class="relative px-3 py-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white text-xs font-bold transform -translate-y-0.5 transition duration-300 group-hover:-translate-y-1 hover:shadow-md hover:shadow-amber-500/30">
                                        Proses ({{ $pendingProductsCount }})
                                    </div>
                                </a>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-xl border border-red-200 bg-white/90 shadow-sm hover:border-red-400 hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center text-lg group-hover:scale-110 transition-transform shadow-inner">
                                        <i class="fa-solid fa-flag"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors">Laporan Pelanggaran Produk</h4>
                                        <p class="text-[11px] text-slate-600 mt-0.5">Ada {{ $pendingReportsCount }} laporan yang perlu ditinjau</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.pelanggaran') }}" class="relative group border-none bg-transparent p-0 outline-none cursor-pointer">
                                    <span class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-15 rounded-lg transform translate-y-0.5 transition duration-300 group-hover:translate-y-1"></span>
                                    <span class="absolute top-0 left-0 w-full h-full rounded-lg bg-gradient-to-l from-slate-200 via-slate-300 to-slate-200"></span>
                                    <div class="relative px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-xs font-bold text-red-700 hover:bg-red-600 hover:text-white transform -translate-y-0.5 transition duration-300 group-hover:-translate-y-1 shadow-sm">
                                        Periksa
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 bg-gradient-to-br from-white via-emerald-50/50 to-teal-100/50 border border-emerald-200/80 p-6 rounded-2xl card-hover shadow-md">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2">
                                <i class="fa-solid fa-bolt text-emerald-600"></i> Aktivitas Terkini
                            </h3>
                        </div>

                        <div class="relative border-l-2 border-emerald-300 ml-3 space-y-6">
                            @php
                                $dotBg = ['emerald' => 'bg-emerald-200', 'sky' => 'bg-sky-200', 'amber' => 'bg-amber-200'];
                                $dotInner = ['emerald' => 'bg-emerald-600', 'sky' => 'bg-sky-600', 'amber' => 'bg-amber-600'];
                            @endphp
                            @forelse($recentActivities as $activity)
                                <div class="relative pl-5 hover:translate-x-1 transition-transform cursor-default">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $dotBg[$activity['color']] ?? 'bg-slate-200' }} border-2 border-white flex items-center justify-center shadow">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $dotInner[$activity['color']] ?? 'bg-slate-600' }}"></div>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-slate-600 mt-0.5">{{ $activity['desc'] }}</p>
                                    <span class="text-[9px] font-bold text-slate-400 block mt-1">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 pl-5">Belum ada aktivitas terbaru.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- SCRIPT -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }

        sidebarToggleBtn.addEventListener('click', toggleSidebar);
        sidebarCloseBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                submenu.classList.toggle('open');
                chevron.classList.toggle('rotated');
            });
        });

        let yearlyChartInstance;

        const initialYear = {{ (int) $year }};
        const initialChartData = @json($chartData);
        const chartDataUrl = "{{ route('admin.dashboard.chartData') }}";

        document.addEventListener('DOMContentLoaded', function () {
            const startYear = 2020;
            const currentRealYear = new Date().getFullYear();
            const maxYear = Math.max(startYear, currentRealYear, initialYear);

            const yearListEl = document.getElementById('yearList');
            const selectedTextEl = document.getElementById('selectedYearText');
            const checkboxEl = document.getElementById('yearDropdownToggle');
            const subtitleEl = document.getElementById('chartSubtitle');

            selectedTextEl.textContent = `Tahun ${initialYear}`;

            for (let y = maxYear; y >= startYear; y--) {
                const li = document.createElement('li');
                li.className = 'listitem';
                const isSelected = y === initialYear ? 'selected' : '';
                li.innerHTML = `<div class="article ${isSelected}" data-year="${y}">Tahun ${y}</div>`;

                li.querySelector('.article').addEventListener('click', function () {
                    document.querySelectorAll('#yearList .article').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');

                    selectedTextEl.textContent = `Tahun ${y}`;
                    subtitleEl.textContent = `Pertumbuhan transaksi berdasarkan data order (Tahun ${y})`;
                    checkboxEl.checked = false;
                    fetchChartData(y);
                });
                yearListEl.appendChild(li);
            }

            const ctx = document.getElementById('yearlyChart').getContext('2d');

            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.45)');
            gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');

            yearlyChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Jumlah Transaksi (Order)',
                            data: initialChartData,
                            borderColor: '#0EA5E9',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0EA5E9',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F2A44',
                            titleFont: { size: 11, family: 'Plus Jakarta Sans' },
                            bodyFont: { size: 13, weight: 'bold', family: 'Plus Jakarta Sans' },
                            padding: 10,
                            displayColors: false,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(14, 165, 233, 0.1)', drawBorder: false },
                            ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#334155' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#334155' }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });
        });

        function fetchChartData(year) {
            fetch(`${chartDataUrl}?year=${year}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(json => {
                    yearlyChartInstance.data.datasets[0].data = json.data;
                    yearlyChartInstance.update();
                })
                .catch(err => console.error('Gagal memuat data grafik:', err));
        }
    </script>
</body>
</html>
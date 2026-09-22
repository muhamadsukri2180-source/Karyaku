<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Karyaku')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        .submenu.open { max-height: 400px; }
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
            0%   { transform: scale(1) translate(0, 0);    opacity: 0.35; }
            50%  { transform: scale(1.25) translate(-6px, 6px); opacity: 0.55; }
            100% { transform: scale(1) translate(0, 0);    opacity: 0.35; }
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
    @stack('styles')
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
                    <div class="w-9 h-9 rounded-xl bg-white overflow-hidden flex items-center justify-center shadow-lg shadow-skyDeep/20">
                        <img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">KaryaKu</h1>
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

                $isPenggunaActive = request()->routeIs('admin.users*') || request()->routeIs('admin.manajemen.akun_service*');
                $isKatalogActive  = request()->routeIs('admin.products*') || request()->routeIs('admin.categories*');
                $isKeuanganActive = request()->routeIs('admin.transactions*') || request()->routeIs('admin.withdrawals*') || request()->routeIs('admin.laporan.keuangan*');
                $isMaintenanceActive = \App\Models\AllowedIp::where('ip_address', 'MAINTENANCE_MODE')->exists() ?? false;
            @endphp
            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">
                    {{ $initials ?: 'AD' }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $adminName }}</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                    <span>Beranda</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron {{ $isPenggunaActive ? 'rotated' : '' }}" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 {{ $isPenggunaActive ? 'open' : '' }}" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.users.verifikator') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-sky-200 w-3 text-center"></i> Akun Verifikator
                        </a>
                        <a href="{{ route('admin.manajemen.akun_service') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.manajemen.akun_service') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-headset text-[10px] text-sky-200 w-3 text-center"></i> Akun Customer Service
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron {{ $isKatalogActive ? 'rotated' : '' }}" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 {{ $isKatalogActive ? 'open' : '' }}" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.products') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
                            </div>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.categories.index') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron {{ $isKeuanganActive ? 'rotated' : '' }}" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 {{ $isKeuanganActive ? 'open' : '' }}" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.transactions*') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.withdrawals*') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                        <a href="{{ route('admin.laporan.keuangan') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.laporan.keuangan*') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 hover:text-white' }} transition-all text-xs">
                            <i class="fa-solid fa-file-invoice-dollar text-[10px] text-sky-200 w-3 text-center"></i> Laporan Keuangan
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.memberships*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center group-hover:text-amber-300 transition-colors"></i>
                    <span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.maintenance*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-server w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Maintenance & Backup</span>
                    </div>
                    @if($isMaintenanceActive)
                        <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                    @endif
                </a>

                <!-- MENU PELANGGARAN -->
                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.pelanggaran*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
                </a>

                 <a href="{{ route('admin.security.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.security.*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <i class="fa-solid fa-shield-halved w-4 text-center text-white"></i><span>Keamanan System</span>
                </a>

                <!-- MENU NOTIFIKASI -->
                <a href="{{ route('admin.notifications.index') }}"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all group mt-1 {{ request()->routeIs('admin.notifikasi*') || request()->routeIs('admin.notifications*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Notifikasi</span>
                    </div>
                    @php
                        $unreadNotificationsCount = 0;
                        if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                            if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'is_read')) {
                                $unreadNotificationsCount = \App\Models\Notification::where('is_read', false)->count();
                            } else {
                                $unreadNotificationsCount = \App\Models\Notification::count();
                            }
                        }
                    @endphp

                    @if($unreadNotificationsCount > 0)
                        <span class="bg-amber-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">@yield('header_title', 'Ikhtisar Panel')</h2>
                        <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">@yield('header_subtitle', 'Pantau statistik penjualan, verifikasi produk, dan aktivitas user.')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @yield('header_right')
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-8 overflow-y-auto no-scrollbar relative z-10">

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
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>

    @stack('modals')

    <script>
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', function () {
                const menuName = this.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${menuName}"]`);
                const chevron = document.querySelector(`[data-chevron="${menuName}"]`);
                if (submenu) submenu.classList.toggle('open');
                if (chevron) chevron.classList.toggle('rotated');
            });
        });

        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

        function toggleSidebar() {
            if (!sidebar) return;
            if (sidebar.classList.contains('closed')) {
                sidebar.classList.remove('closed');
                sidebar.classList.add('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.remove('open');
                sidebar.classList.add('closed');
                if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
            }
        }

        if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Karyaku - Dashboard Customer Service')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
                colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', skyDeeper: '#082C48', skyPale: '#EFF8FF', coral: '#FF7A59', mint: '#10B981', ink: '#0F2A44' }
            } }
        }
    </script>
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(14, 165, 233, 0.5); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); cursor: default; }
        .card-hover:hover { transform: scale(1.025) translateY(-5px); box-shadow: 0 20px 35px -10px rgba(14, 165, 233, 0.3); border-color: rgba(14, 165, 233, 0.6); }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white overflow-hidden flex items-center justify-center shadow-lg shadow-skyDeep/20"><img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" class="w-full h-full object-contain"></div>
                <div>
                    <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">KaryaKu</h1>
                    <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">CS Panel</span>
                </div>
            </div>
            <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        @php
            $csUser = auth()->user();
            $csName = $csUser->name ?? 'CS';
            $csInitials = collect(explode(' ', trim($csName)))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
        @endphp
        <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
            <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $csInitials ?: 'CS' }}</div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ $csName }}</p>
                <span class="text-[10px] text-sky-200">Customer Service</span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>

            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('cs.dashboard') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all duration-200">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Beranda</span>
            </a>

            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('cs.laporan*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan & Moderasi</span>
            </a>

            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('cs.notifikasi*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i><span>Notifikasi</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/15">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                    <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 backdrop-blur-xl border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div class="flex items-center gap-4">
                <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-300 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">@yield('header_title', 'Dashboard Customer Service')</h2>
                    <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">@yield('header_subtitle', '')</p>
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

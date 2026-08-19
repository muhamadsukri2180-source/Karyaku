<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Dashboard Customer Service</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg shadow-skyDeep/20"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
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

            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all duration-200">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan & Moderasi</span>
            </a>

            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Cek Transaksi</span>
            </a>

            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
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
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Dashboard Customer Service</h2>
                    <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">Pantau laporan masuk, aktivitas moderasi, dan transaksi pengguna.</p>
                </div>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-8 overflow-y-auto no-scrollbar">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm"><i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}</div>
            @endif

            <!-- TOP METRICS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider group-hover:text-sky-600 transition-colors">Laporan Masuk</span>
                            <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalLaporanMasuk, 0, ',', '.') }}</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/40">
                            <i class="fa-solid fa-flag text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="text-[10px] text-slate-600 font-medium">Menunggu ditangani</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 via-emerald-100/60 to-teal-200/50 border-l-4 border-emerald-500 border border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Laporan Selesai</span>
                            <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($laporanSelesai, 0, ',', '.') }}</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/40">
                            <i class="fa-solid fa-check-double text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-emerald-200 shadow-sm px-2 py-0.5 rounded-md">Sudah ditindak</span>
                    </div>
                </div>

            </div>

            <!-- SECTION LAPORAN TERBARU -->
            <div class="bg-gradient-to-br from-white via-sky-50/50 to-blue-100/50 border border-sky-200/80 p-6 rounded-2xl card-hover shadow-md">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2"><i class="fa-solid fa-flag text-sky-600"></i> Laporan Terbaru</h3>
                    <a href="{{ route('cs.laporan') }}" class="text-[11px] font-bold text-sky-700 hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentReports as $report)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-white/70 border border-sky-100 shadow-sm hover:bg-white transition-all">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $report->reporter->name ?? '-' }} melapor {{ $report->reportedUser->name ?? ($report->product->title ?? 'sesuatu') }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $report->reason }}</p>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ optional($report->created_at)->diffForHumans() ?? '-' }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-500 italic py-2">Belum ada laporan masuk.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
    sidebarToggleBtn.addEventListener('click', toggleSidebar);
    sidebarCloseBtn.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Notifikasi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    tailwind.config = { theme: { extend: {
        fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'], display: ['Sora','sans-serif'] },
        colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
    } } }
</script>
<style>
    .active-menu { background: rgba(255,255,255,.2); border-left:4px solid #fff; color:#fff; }
    #sidebar { transition: transform .3s cubic-bezier(.4,0,.2,1); }
    @media (max-width:1023px){ #sidebar.closed{ transform:translateX(-100%);} #sidebar.open{ transform:translateX(0);} }
</style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0 shadow-2xl">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold"><i class="fa-solid fa-layer-group"></i></div>
                <div><h1 class="font-display font-extrabold text-[17px] text-white">Karyaku</h1><span class="text-[9px] text-sky-200 uppercase tracking-widest">CS Panel</span></div>
            </div>
            <button id="sidebarCloseBtn" class="lg:hidden text-white/80"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4 pt-4">
            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span></a>
            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i><span>Laporan & Moderasi</span></a>
            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-receipt w-4 text-center"></i><span>Cek Transaksi</span></a>
            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu"><i class="fa-solid fa-bell w-4 text-center"></i><span>Notifikasi</span></a>
        </nav>
        <div class="p-4 border-t border-white/15">
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white text-xs font-bold"><i class="fa-solid fa-power-off"></i> Keluar Sistem</button></form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-md">
            <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white border border-sky-300 flex items-center justify-center"><i class="fa-solid fa-bars"></i></button>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold font-display text-slate-900">Notifikasi dari Admin</h2>
                <p class="text-[11px] text-slate-700 font-semibold mt-0.5">Pengumuman & instruksi terbaru dari Admin.</p>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-4">
            @forelse($notifications as $notif)
            @php($isNew = $notif->created_at->greaterThan(now()->subDays(3)))
            <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center shadow-md shrink-0"><i class="fa-solid fa-bell"></i></div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-bold text-slate-900 text-sm">{{ $notif->name }}</h4>
                        @if($isNew)<span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-red-50 text-red-600 border border-red-200">Baru</span>@endif
                    </div>
                    <p class="text-xs text-slate-600 mt-1">{{ $notif->description }}</p>
                    <p class="text-[10px] text-slate-400 mt-2">{{ $notif->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="bg-white border border-sky-200 rounded-2xl p-10 text-center text-slate-400 text-xs font-semibold">Belum ada notifikasi dari Admin.</div>
            @endforelse

            @if($notifications->hasPages())<div class="mt-2">{{ $notifications->links() }}</div>@endif
        </div>
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    document.getElementById('sidebarToggleBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarCloseBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
</script>
</body>
</html>

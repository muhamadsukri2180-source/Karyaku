<!SIDEBAR VERIFIKATOR>
<aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
    <div class="p-6 border-b border-white/15 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white overflow-hidden flex items-center justify-center shadow-lg">
                <img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">KaryaKu</h1>
                <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Verifikator Panel</span>
            </div>
        </div>
        <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>

    <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
        <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0 overflow-hidden">
            @if(auth()->check() && auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 2)) }}
            @endif
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Verifikator' }}</p>
            <p class="text-[10px] text-sky-200 uppercase font-bold tracking-wider">Verifikator Team</p>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
        <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-2">Navigasi Utama</p>

        <!1. Dashboard>
        <a href="{{ route('verifikator.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all group {{ request()->routeIs('verifikator.dashboard') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('verifikator.dashboard') ? 'text-white' : 'group-hover:text-white transition-colors' }}"></i><span>Dashboard</span>
        </a>

        <!2. Verifikasi Identitas>
        <a href="{{ route('verifikator.identitas') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all group {{ request()->routeIs(['verifikator.identitas*', 'verifikator.pendaftaran*']) ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-id-card-clip w-4 text-center {{ request()->routeIs(['verifikator.identitas*', 'verifikator.pendaftaran*']) ? 'text-white' : 'group-hover:text-white transition-colors' }}"></i><span>Verifikasi Identitas</span>
            </div>
            @if(($pendingKtp ?? 0) > 0)
                <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">{{ $pendingKtp }}</span>
            @endif
        </a>

        <!3. Verifikasi Produk>
        <a href="{{ route('verifikator.produk') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all group {{ request()->routeIs('verifikator.produk*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-box-open w-4 text-center {{ request()->routeIs('verifikator.produk*') ? 'text-white' : 'group-hover:text-white transition-colors' }}"></i><span>Verifikasi Produk</span>
            </div>
            @if(($pendingProduk ?? 0) > 0)
                <span class="bg-amber-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $pendingProduk }}</span>
            @endif
        </a>

        <!4. Verifikasi Pembayaran>
        <a href="{{ route('verifikator.pembayaran') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all group {{ request()->routeIs(['verifikator.pembayaran*', 'verifikator.transaksi_pembayaran*']) ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs(['verifikator.pembayaran*', 'verifikator.transaksi_pembayaran*']) ? 'text-white' : 'group-hover:text-white transition-colors' }}"></i><span>Verifikasi Pembayaran</span>
            </div>
            @if(($pendingPembayaran ?? 0) > 0)
                <span class="bg-emerald-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $pendingPembayaran }}</span>
            @endif
        </a>

        <!5. Laporan Pelanggaran>
        <a href="{{ route('verifikator.laporan') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all group {{ request()->routeIs('verifikator.laporan*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center {{ request()->routeIs('verifikator.laporan*') ? 'text-white' : 'group-hover:text-white transition-colors' }}"></i><span>Laporan Pelanggaran</span>
            </div>
            @if(($laporanMasuk ?? 0) > 0)
                <span class="bg-rose-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $laporanMasuk }}</span>
            @endif
        </a>


    </nav>

    <div class="p-4 border-t border-white/15">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Daftar Jasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] }, colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', coral: '#FF7A59' } } } }
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
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: scale(1.015) translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
        .modal-overlay { transition: opacity .25s ease; }
        .modal-box { transition: all .25s ease; }
        .tab-btn.active-tab { background: #0EA5E9; color: #fff; box-shadow: 0 8px 15px -5px rgba(14,165,233,0.4); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white overflow-hidden flex items-center justify-center shadow-lg shadow-skyDeep/20"><img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" class="w-full h-full object-contain"></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">KaryaKu</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            @php $admin = auth()->user(); $initials = collect(explode(' ', trim($admin->name ?? 'Admin')))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode(''); @endphp
            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $initials ?: 'AD' }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $admin->name ?? 'Admin' }}</p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Beranda</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i><span>Manajemen Pengguna</span>
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
                            <i class="fa-solid fa-headset text-[10px] text-sky-200 w-3 text-center"></i> Akun & Layanan CS
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center text-white transition-colors"></i><span class="text-white">Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-list-check text-[10px] text-white w-3 text-center"></i> Daftar Jasa</div>
                            @if($pendingCount > 0)
                                <span class="bg-amber-400 text-slate-900 text-[9px] px-1.5 py-0.5 rounded font-extrabold">{{ $pendingCount }} Baru</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                        <a href="{{ route('admin.laporan.keuangan') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-file-invoice-dollar text-[10px] text-sky-200 w-3 text-center"></i> Laporan Keuangan
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center group-hover:text-amber-300 transition-colors"></i><span>Paket Membership</span>
                </a>
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-server w-4 text-center group-hover:text-white transition-colors"></i><span>Maintenance & Backup</span></div>
                </a>

                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
                </a>
                 <a href="{{ route('admin.security.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.security.*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <i class="fa-solid fa-shield-halved w-4 text-center text-white"></i><span>Keamanan System</span>
                </a>
                <!-- MENU NOTIFIKASI -->
                <a href="{{ route('admin.notifications.index') }}"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1 {{ request()->routeIs('admin.notifications.*') ? 'bg-white/20 text-white font-bold' : '' }}">
                <div class="flex items-center gap-3">
                <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i>
                <span>Notifikasi</span>
                </div>
                @php
                $unreadNotificationsCount = 0;
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'is_read')) {
                $unreadNotificationsCount = \App\Models\Notification::where('is_read', false)->count();
                } else {
                    $unreadNotificationsCount = \App\Models\Notification::count();
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
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-gradient-to-r from-white via-sky-50/50 to-blue-50/50 backdrop-blur-xl border-b border-sky-200 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Daftar Jasa (Produk)</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Tinjau, setujui, dan kelola semua layanan/karya yang ditawarkan kreator.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- SUMMARY CARDS (rata penuh - 2 kolom sejajar mengisi lebar halaman) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Menunggu Tinjauan</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($pendingCount, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-clock text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Jasa baru yang perlu disetujui admin</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Jasa Aktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($activeCount, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-box-open text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Sudah tayang di katalog Karyaku</p>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <!-- SUB-TABS NAVIGATION (Gaya Verifikator) -->
                    <div class="flex border-b border-sky-200 gap-4 px-5 pt-4 bg-white/50 backdrop-blur-sm">
                        <a href="{{ route('admin.products', ['tab' => 'pending']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? 'pending') === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('admin.products', ['tab' => 'active']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? '') === 'active' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-box-open mr-1.5"></i> Katalog Aktif ({{ $activeCount }})
                        </a>
                        <a href="{{ route('admin.products', ['tab' => 'all']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? '') === 'all' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-list-check mr-1.5"></i> Semua Jasa ({{ $allCount ?? ($pendingCount + $activeCount) }})
                        </a>
                    </div>

                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <form method="GET" action="{{ route('admin.products') }}" class="relative w-full sm:w-72">
                            <input type="hidden" name="tab" value="{{ $tab ?? 'pending' }}">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jasa..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Informasi Jasa</th>
                                    <th class="py-4 px-6">Kreator</th>
                                    <th class="py-4 px-6">Harga Mulai</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse($products as $product)
                                    @php
                                        $statusColor = match($product->status) {
                                            'pending' => 'text-amber-700 bg-amber-100 border-amber-200',
                                            'active' => 'text-emerald-700 bg-emerald-100 border-emerald-200',
                                            'inactive' => 'text-slate-600 bg-slate-100 border-slate-200',
                                            default => 'text-slate-600 bg-slate-100 border-slate-200',
                                        };
                                        $statusLabel = match($product->status) {
                                            'pending' => 'Menunggu',
                                            'active' => 'Aktif',
                                            'inactive' => 'Nonaktif',
                                            default => ucfirst($product->status),
                                        };
                                    @endphp
                                    <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $thumb = $product->thumbnail ?? (is_array($product->images) ? ($product->images[0] ?? null) : null);
                                                @endphp
                                                <div class="w-12 h-10 rounded-lg bg-slate-200 flex items-center justify-center overflow-hidden border border-slate-300 shrink-0">
                                                    @if(!empty($thumb) && \Illuminate\Support\Facades\Storage::disk('public')->exists($thumb))
                                                        <img src="{{ asset('storage/' . $thumb) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-tr from-sky-400 to-blue-600 text-white flex items-center justify-center text-xs font-bold"><i class="fa-solid fa-box-open"></i></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs line-clamp-1">{{ $product->title }}</p>
                                                    <p class="text-[10px] text-sky-600 font-bold mt-0.5">{{ $product->category->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-xs font-bold text-slate-700">{{ $product->seller->name ?? '-' }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="text-[10px] font-bold {{ $statusColor }} px-2.5 py-1 rounded-md border">{{ $statusLabel }}</span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    onclick="openReviewModal('{{ $product->id_product }}', '{{ addslashes($product->title) }}', '{{ addslashes($product->category->name ?? '-') }}', '{{ addslashes($product->seller->name ?? '-') }}', 'Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $product->description ?? '-')) }}', '{{ !empty($thumb) && \Illuminate\Support\Facades\Storage::disk('public')->exists($thumb) ? asset('storage/' . $thumb) : '' }}', '{{ $product->status }}')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white transition-all text-xs font-bold shadow-sm flex items-center gap-1" title="Tinjau Detail Produk">
                                                    <i class="fa-solid fa-eye text-xs"></i> <span>Tinjau</span>
                                                </button>
                                                @if($product->status === 'pending')
                                                    <button type="button" onclick="confirmApproveProduct('{{ route('admin.products.approve', $product->id_product) }}', '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Setujui"><i class="fa-solid fa-check text-xs"></i></button>
                                                    <button type="button" onclick="confirmTakedown('{{ route('admin.products.takedown', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Tolak / takedown produk ini?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Tolak"><i class="fa-solid fa-xmark text-xs"></i></button>
                                                @elseif($product->status === 'active')
                                                    <button type="button" onclick="confirmTakedown('{{ route('admin.products.takedown', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Takedown produk ini dari katalog?')" class="px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-600 hover:text-white transition-all text-[11px] font-bold shadow-sm"><i class="fa-solid fa-ban"></i> Takedown</button>
                                                @else
                                                    <button type="button" onclick="confirmApproveProduct('{{ route('admin.products.approve', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Aktifkan kembali produk ini?')" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-[11px] font-bold shadow-sm"><i class="fa-solid fa-rotate-left"></i> Aktifkan</button>
                                                @endif
                                                <button type="button" onclick="confirmDeleteProduct('{{ route('admin.products.delete', $product->id_product) }}', '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Hapus Permanen">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data produk/jasa pada tab ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                        <div class="p-5 border-t border-sky-100 bg-white/50">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL: TINJAU PRODUK -->
    <div id="reviewProductModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('reviewProductModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-display font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-sky"></i> Tinjau Jasa / Produk
                </h3>
                <button type="button" onclick="closeModal('reviewProductModal')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Gambar Produk / Jasa</span>
                        <button type="button" id="reviewImgZoomBtn" onclick="const img=document.getElementById('reviewImg'); if(img && img.src) window.open(img.src, '_blank')" class="text-[11px] text-sky-600 hover:text-sky-700 font-semibold hidden flex items-center gap-1">
                            <i class="fa-solid fa-up-right-from-square"></i> Buka Gambar Penuh
                        </button>
                    </div>
                    <div id="reviewImgContainer" class="w-full min-h-[160px] max-h-72 rounded-xl bg-slate-900/5 overflow-hidden border border-slate-200 flex items-center justify-center p-2 relative group">
                        <img id="reviewImg" src="" alt="" class="max-h-64 w-full object-contain hidden cursor-pointer transition-transform hover:scale-[1.01]" onclick="if(this.src) window.open(this.src, '_blank')" title="Klik untuk melihat gambar ukuran penuh">
                        <div id="reviewImgPlaceholder" class="flex flex-col items-center text-slate-400">
                            <i class="fa-solid fa-image text-3xl mb-1"></i>
                            <span class="text-xs font-semibold">Tidak Ada Gambar</span>
                        </div>
                    </div>
                </div>

                <div>
                    <span id="reviewCategory" class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-sky-100 text-sky-700">Kategori</span>
                    <h4 id="reviewTitle" class="text-lg font-bold text-slate-900 mt-1">Judul Jasa</h4>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5" id="reviewPrice">Rp 0</p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-400 font-semibold text-[10px] block">KREATOR</span>
                        <span id="reviewSeller" class="font-bold text-slate-800">Kreator Name</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold text-[10px] block">STATUS</span>
                        <span id="reviewStatus" class="font-bold text-slate-800">Status</span>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Deskripsi</label>
                    <div id="reviewDescription" class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200/80 max-h-36 overflow-y-auto leading-relaxed">
                        -
                    </div>
                </div>
            </div>

            <div id="reviewModalActions" class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" id="reviewApproveBtn" class="hidden px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Setujui & Terbitkan
                </button>
                <button type="button" id="reviewRejectBtn" class="hidden px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> Tolak / Takedown
                </button>
                <button type="button" onclick="closeModal('reviewProductModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
        sidebarToggleBtn.addEventListener('click', toggleSidebar); sidebarCloseBtn.addEventListener('click', toggleSidebar); sidebarOverlay.addEventListener('click', toggleSidebar);

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                submenu.classList.toggle('open');
                chevron.classList.toggle('rotated');
            });
        });

        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        function confirmTakedown(actionUrl, title, customMsg) {
            Swal.fire({
                title: 'Takedown / Tolak Produk?',
                text: customMsg || `Apakah Anda yakin ingin menolak / takedown jasa "${title}" dari katalog?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Takedown!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = actionUrl;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmApproveProduct(actionUrl, title, customMsg) {
            Swal.fire({
                title: 'Setujui Produk?',
                text: customMsg || `Apakah Anda yakin ingin menyetujui dan menerbitkan jasa "${title}" ke katalog?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = actionUrl;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmDeleteProduct(actionUrl, title) {
            Swal.fire({
                title: 'Hapus Produk Permanen?',
                text: `Anda akan menghapus jasa "${title}" secara permanen. Tindakan ini tidak dapat dibatalkan.`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = actionUrl;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openReviewModal(id, title, category, seller, price, description, imgUrl, status) {
            document.getElementById('reviewTitle').textContent = title;
            document.getElementById('reviewCategory').textContent = category;
            document.getElementById('reviewSeller').textContent = seller;
            document.getElementById('reviewPrice').textContent = price;
            document.getElementById('reviewDescription').textContent = description || 'Tidak ada deskripsi.';
            document.getElementById('reviewStatus').textContent = status === 'active' ? 'Aktif' : (status === 'pending' ? 'Menunggu Approval' : 'Nonaktif');

            const approveBtn = document.getElementById('reviewApproveBtn');
            const rejectBtn = document.getElementById('reviewRejectBtn');

            if (status === 'pending') {
                const approveUrl = `{{ url('admin/products/approve') }}/${id}`;
                const rejectUrl = `{{ url('admin/products/takedown') }}/${id}`;
                approveBtn.onclick = function() {
                    closeModal('reviewProductModal');
                    confirmApproveProduct(approveUrl, title);
                };
                rejectBtn.onclick = function() {
                    closeModal('reviewProductModal');
                    confirmTakedown(rejectUrl, title, 'Tolak / takedown produk ini?');
                };
                approveBtn.classList.remove('hidden');
                rejectBtn.classList.remove('hidden');
            } else {
                approveBtn.classList.add('hidden');
                rejectBtn.classList.add('hidden');
            }

            const imgEl = document.getElementById('reviewImg');
            const placeholderEl = document.getElementById('reviewImgPlaceholder');
            const zoomBtn = document.getElementById('reviewImgZoomBtn');
            if (imgUrl && imgUrl.trim() !== '') {
                imgEl.src = imgUrl;
                imgEl.classList.remove('hidden');
                placeholderEl.classList.add('hidden');
                if (zoomBtn) zoomBtn.classList.remove('hidden');
            } else {
                imgEl.src = '';
                imgEl.classList.add('hidden');
                placeholderEl.classList.remove('hidden');
                if (zoomBtn) zoomBtn.classList.add('hidden');
            }

            openModal('reviewProductModal');
        }
    </script>
</body>
</html>
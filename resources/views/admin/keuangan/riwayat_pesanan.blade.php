<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', coral: '#FF7A59' }
                }
            }
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
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: scale(1.015) translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
        .modal-backdrop { background: rgba(15, 23, 42, 0.55); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg shadow-skyDeep/20"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] text-sky-100 truncate">Online</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
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
                            <i class="fa-solid fa-headset text-[10px] text-sky-200 w-3 text-center"></i> Akun Customer Service
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa</div>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center text-white transition-colors"></i><span class="text-white">Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-white w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
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
                <form action="{{ route('logout') }}" method="POST">
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Riwayat Pesanan</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Pantau seluruh siklus transaksi dan status pesanan jasa di platform.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-300 text-red-800 text-xs font-semibold px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100/60 border-l-4 border-blue-500 border-y border-r border-blue-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">Total Transaksi</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalTransaksi, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/30"><i class="fa-solid fa-cart-shopping text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Sedang Diproses</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($sedangDiproses, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-spinner text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Order Selesai</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($orderSelesai, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-check-double text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Dibatalkan</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($dibatalkan, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-ban text-lg"></i></div>
                        </div>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <form action="{{ route('admin.transactions') }}" method="GET" class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Pesanan atau Nama..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </form>

                        <a href="{{ route('admin.transactions.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                            <i class="fa-solid fa-file-excel"></i> Export CSV
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">ID Pesanan & Layanan</th>
                                    <th class="py-4 px-6">Pelanggan & Kreator</th>
                                    <th class="py-4 px-6">Total Nilai</th>
                                    <th class="py-4 px-6">Status Order</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse ($orders as $order)
                                @php
                                    $firstItem = $order->items->first();
                                    $productTitle = $firstItem->product->title ?? '-';
                                    $sellerName = $firstItem->product->seller->name ?? '-';
                                    $extraItems = $order->items->count() > 1 ? ' (+' . ($order->items->count() - 1) . ' lainnya)' : '';
                                @endphp
                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <p class="font-bold text-sky-700 text-xs">#{{ $order->kode_order }}</p>
                                        <p class="text-[11px] text-slate-600 font-medium mt-0.5 w-48 truncate">{{ $productTitle }}{{ $extraItems }}</p>
                                    </td>
                                    <td class="py-3 px-6">
                                        <p class="text-xs font-bold text-slate-800"><i class="fa-solid fa-user text-[10px] text-slate-400 mr-1"></i> {{ $order->buyer->name ?? '-' }} (Pembeli)</p>
                                        <p class="text-[10px] font-semibold text-slate-500 mt-0.5"><i class="fa-solid fa-store text-[10px] text-slate-400 mr-1"></i> {{ $sellerName }} (Kreator)</p>
                                    </td>
                                    <td class="py-3 px-6 text-xs font-bold text-emerald-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-6">
                                        @if ($order->payment_status === 'paid')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai & Dibayar</span>
                                        @elseif ($order->status === 'dibatalkan')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">Dibatalkan</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button type="button" onclick="showOrderDetail({{ $order->id_order ?? $order->id }})" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all text-[10px] font-bold shadow-sm"><i class="fa-solid fa-eye"></i> Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-6 text-center text-xs text-slate-500 font-semibold">Belum ada data pesanan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders->hasPages())
                    <div class="p-5 border-t border-sky-100">
                        {{ $orders->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL DETAIL ORDER -->
    <div id="orderDetailModal" class="fixed inset-0 z-[60] hidden items-center justify-center modal-backdrop p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 font-display">Detail Pesanan</h3>
                <button onclick="closeOrderDetail()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div id="orderDetailContent" class="p-5 text-xs text-slate-700 space-y-2">
                <p class="text-center text-slate-400 py-6">Memuat data...</p>
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

        function showOrderDetail(id) {
            const modal = document.getElementById('orderDetailModal');
            const content = document.getElementById('orderDetailContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.innerHTML = '<p class="text-center text-slate-400 py-6">Memuat data...</p>';

            fetch(`{{ url('admin/transactions') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    let itemsHtml = (data.items || []).map(item => `
                        <div class="flex justify-between border-b border-slate-100 py-2">
                            <span>${item.product?.title ?? '-'}</span>
                            <span class="font-bold">Rp ${Number(item.price ?? 0).toLocaleString('id-ID')}</span>
                        </div>
                    `).join('');
                    content.innerHTML = `
                        <p><span class="font-bold">Kode Order:</span> ${data.kode_order}</p>
                        <p><span class="font-bold">Pembeli:</span> ${data.buyer?.name ?? '-'}</p>
                        <p><span class="font-bold">Status:</span> ${data.status}</p>
                        <p><span class="font-bold">Pembayaran:</span> ${data.payment_status}</p>
                        <p><span class="font-bold">Total:</span> Rp ${Number(data.total_price ?? 0).toLocaleString('id-ID')}</p>
                        <div class="mt-3"><p class="font-bold mb-1">Item Pesanan:</p>${itemsHtml}</div>
                    `;
                })
                .catch(() => {
                    content.innerHTML = '<p class="text-center text-red-500 py-6">Gagal memuat detail pesanan.</p>';
                });
        }

        function closeOrderDetail() {
            const modal = document.getElementById('orderDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
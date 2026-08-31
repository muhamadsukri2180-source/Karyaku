<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Akun Pengguna</title>
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

        /* Menghilangkan ikon bawaan browser (reveal password) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden !important;
            pointer-events: none !important;
            position: absolute !important;
            right: 0 !important;
        }
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

            @php $admin = auth()->user(); $initials = collect(explode(' ', trim($admin->name ?? 'Admin')))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode(''); @endphp
            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $initials ?: 'A' }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $admin->name ?? 'Admin' }}</p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center text-white transition-colors"></i>
                        <span class="text-white">Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-white w-3 text-center"></i> Akun Pengguna
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
                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1 {{ request()->routeIs('admin.notifikasi.*') ? 'bg-white/20 text-white font-bold' : '' }}">
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Akun Pengguna</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola seluruh data kreator dan pembeli di Karyaku (Suspend & Hapus).</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100/60 border-l-4 border-blue-500 border-y border-r border-blue-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">Total Pengguna</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUsers, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/30"><i class="fa-solid fa-users text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-blue-200/50 pt-2 mt-2">Seluruh pengguna terdaftar</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Kreator Aktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($activeCreators, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-user-tie text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Memiliki minimal 1 produk</p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Pengguna Baru</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($newThisMonth, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-user-plus text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Bergabung bulan ini</p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Akun Diblokir</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($blockedUsers, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-user-slash text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-red-200/50 pt-2 mt-2">Melanggar kebijakan</p>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
               <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
        <form method="GET" action="{{ route('admin.users') }}" class="relative w-full sm:flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
        </form>
    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Informasi Akun</th>
                                    <th class="py-4 px-6">Peran (Role)</th>
                                    <th class="py-4 px-6">Tgl Bergabung</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse($users as $user)
                                    @php
                                        $initialsRow = collect(explode(' ', trim($user->name)))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                                        $roleName = $user->role->role_name ?? '-';
                                        $statusColor = match($user->status) {
                                            'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'inactive' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            'blocked' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                                        };
                                        $isBlocked = $user->status === 'blocked';
                                    @endphp
                                    <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">{{ $initialsRow ?: '??' }}</div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $user->name }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="text-[11px] font-bold {{ $roleName === 'penjual' ? 'text-indigo-700 bg-indigo-50 border-indigo-200' : 'text-slate-600 bg-slate-100 border-slate-200' }} px-2 py-1 rounded-md shadow-sm border">
                                                {{ $roleName === 'penjual' ? 'Kreator' : ($roleName === 'pembeli' ? 'Pembeli' : ucfirst($roleName)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6"><p class="text-xs font-semibold text-slate-700">{{ $user->created_at->translatedFormat('d M Y') }}</p></td>
                                        <td class="py-3 px-6">
                                            <span class="text-[10px] font-bold {{ $statusColor }} px-2.5 py-1 rounded-md flex items-center w-max gap-1.5 border">
                                                <i class="fa-solid fa-circle text-[6px]"></i> {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- TOMBOL SUSPEND / AKTIFKAN -->
                                                <button type="button"
                                                    class="btn-suspend-user w-8 h-8 rounded-lg {{ $isBlocked ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-600' : 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-600' }} border hover:text-white transition-all shadow-sm flex items-center justify-center"
                                                    data-id="{{ $user->id_user }}"
                                                    data-name="{{ $user->name }}"
                                                    data-status="{{ $user->status }}"
                                                    title="{{ $isBlocked ? 'Aktifkan Kembali' : 'Suspend Pengguna' }}">
                                                    <i class="fa-solid {{ $isBlocked ? 'fa-lock-open' : 'fa-ban' }} text-xs"></i>
                                                </button>

                                                <!-- TOMBOL HAPUS -->
                                                <button type="button"
                                                    class="btn-delete-user w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center"
                                                    data-id="{{ $user->id_user }}"
                                                    data-name="{{ $user->name }}"
                                                    title="Hapus Pengguna">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="p-5 border-t border-sky-100 bg-white/50">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                if(submenu) submenu.classList.toggle('open');
                if(chevron) chevron.classList.toggle('rotated');
            });
        });

        // === SUSPEND / AKTIFKAN PENGGUNA ===
        document.querySelectorAll('.btn-suspend-user').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const isBlocked = btn.dataset.status === 'blocked';

                if (isBlocked) {
                    Swal.fire({
                        title: 'Aktifkan Pengguna?',
                        text: `Akun "${name}" akan diaktifkan kembali dan bisa login seperti biasa.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Aktifkan!',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                            confirmButton: 'px-6 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md mx-1 cursor-pointer',
                            cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitSuspendForm(id, 0, 0, 0, '');
                        }
                    });
                } else {
                    // Tampilkan form modal durasi (Hari, Jam, Menit) + Alasan
                    Swal.fire({
                        title: `<span class="font-display font-extrabold text-slate-800 text-xl">Suspend Pengguna "${name}"</span>`,
                        html: `
                            <p class="text-xs font-semibold text-slate-500 mb-4 text-left">Tentukan durasi penonaktifan sementara akun serta alasan pemblokiran:</p>
                            
                            <div class="space-y-4 text-left">
                                <div>
                                    <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide block mb-1">Durasi Pemblokiran</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Hari</span>
                                            <input id="swal_suspend_days" type="number" min="0" value="1" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Jam</span>
                                            <input id="swal_suspend_hours" type="number" min="0" max="23" value="0" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Menit</span>
                                            <input id="swal_suspend_minutes" type="number" min="0" max="59" value="0" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">*Kosongkan/set 0 semua jika ingin blokir permanen.</p>
                                </div>

                                <div>
                                    <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide block mb-1">Alasan Pemblokiran <span class="text-red-500">*</span></label>
                                    <textarea id="swal_suspend_reason" rows="3" placeholder="Contoh: Mengunggah karya tanpa izin hak cipta / penipuan transaksi..." class="w-full border-2 border-amber-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-amber-500 focus:outline-none transition"></textarea>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Suspend!',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                            confirmButton: 'px-6 py-2.5 bg-amber-500 text-white font-bold text-sm rounded-xl hover:bg-amber-600 transition-all shadow-md mx-1 cursor-pointer',
                            cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                        },
                        preConfirm: () => {
                            const days = parseInt(document.getElementById('swal_suspend_days').value) || 0;
                            const hours = parseInt(document.getElementById('swal_suspend_hours').value) || 0;
                            const minutes = parseInt(document.getElementById('swal_suspend_minutes').value) || 0;
                            const reason = document.getElementById('swal_suspend_reason').value.trim();

                            if (!reason) {
                                Swal.showValidationMessage('Alasan pemblokiran wajib diisi!');
                                return false;
                            }

                            return { days, hours, minutes, reason };
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            submitSuspendForm(
                                id,
                                result.value.days,
                                result.value.hours,
                                result.value.minutes,
                                result.value.reason
                            );
                        }
                    });
                }
            });
        });

        function submitSuspendForm(id, days, hours, minutes, reason) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('admin/users') }}/${id}/suspend`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            const daysInput = document.createElement('input');
            daysInput.type = 'hidden';
            daysInput.name = 'suspend_days';
            daysInput.value = days;
            form.appendChild(daysInput);

            const hoursInput = document.createElement('input');
            hoursInput.type = 'hidden';
            hoursInput.name = 'suspend_hours';
            hoursInput.value = hours;
            form.appendChild(hoursInput);

            const minutesInput = document.createElement('input');
            minutesInput.type = 'hidden';
            minutesInput.name = 'suspend_minutes';
            minutesInput.value = minutes;
            form.appendChild(minutesInput);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'suspend_reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            document.body.appendChild(form);
            form.submit();
        }

        // === HAPUS PENGGUNA ===
        document.querySelectorAll('.btn-delete-user').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                Swal.fire({
                    title: 'Hapus Pengguna?',
                    text: `Anda akan menghapus "${name}" secara permanen. Tindakan ini tidak dapat dibatalkan!`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('admin/users') }}/${id}`;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
        @if (session('warning'))
            Swal.fire({ icon: 'warning', title: 'Perhatian!', text: "{{ session('warning') }}", confirmButtonColor: '#f59e0b' });
        @endif
        @if ($errors->any())
            Swal.fire({ icon: 'warning', title: 'Perhatian!', html: '<ul class="text-left text-xs space-y-1">@foreach($errors->all() as $err)<li>• {{ $err }}</li>@endforeach</ul>', confirmButtonColor: '#0EA5E9' });
        @endif
    </script>
</body>
</html>
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
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola seluruh data kreator dan pembeli di Karyaku (CRUD).</p>
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
                        <form method="GET" action="{{ route('admin.users') }}" class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </form>
                        
                        <!-- TOMBOL 3D BIRU KOKOH -->
                        <button type="button" onclick="openAddUserModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
                        </button>
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
                                                <button type="button"
                                                    class="btn-edit-user w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center"
                                                    data-id="{{ $user->id_user }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-phone="{{ $user->phone }}"
                                                    data-role="{{ $user->id_role }}"
                                                    data-status="{{ $user->status }}"
                                                    title="Edit Pengguna">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </button>
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

    <!-- MODAL: TAMBAH PENGGUNA -->
    <div id="addUserModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 mx-4 overflow-hidden" id="addUserModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center"><i class="fa-solid fa-user-plus text-sm"></i></div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Tambah Pengguna Baru</h3>
                </div>
                <button type="button" onclick="closeModal('addUserModal')" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4 overflow-y-auto flex-1">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Email</label>
                    <input type="email" name="email" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Password</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="addPassword" required minlength="8" class="w-full border border-sky-200 rounded-xl px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                        <button type="button" onclick="togglePassword('addPassword', 'eyeIconAdd')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none cursor-pointer">
                            <i class="fa-solid fa-eye text-sm" id="eyeIconAdd"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">No. Telepon</label>
                    <input type="text" name="phone" class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Peran (Role)</label>
                    <div class="relative mt-1">
                        <input type="checkbox" id="roleDropdownToggleAdd" class="sr-only peer">
                        <label for="roleDropdownToggleAdd" class="flex items-center justify-between w-full border border-sky-200 rounded-xl px-3 py-2 text-sm bg-white cursor-pointer select-none shadow-sm hover:border-sky-400 transition">
                            <span id="selectedRoleTextAdd" class="font-medium text-slate-800">Kreator (Penjual)</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </label>
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-sky-200 rounded-xl shadow-lg max-h-40 overflow-y-auto z-50 hidden peer-checked:block py-1">
                            @foreach($roles as $role)
                                <li onclick="selectRole('Add', '{{ $role->id_role }}', '{{ $role->role_name === 'penjual' ? 'Kreator (Penjual)' : 'Pembeli' }}')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">
                                    {{ $role->role_name === 'penjual' ? 'Kreator (Penjual)' : 'Pembeli' }}
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="id_role" id="roleInputAdd" value="{{ $roles->first()->id_role ?? 1 }}">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Status</label>
                    <div class="relative mt-1">
                        <input type="checkbox" id="statusDropdownToggleAdd" class="sr-only peer">
                        <label for="statusDropdownToggleAdd" class="flex items-center justify-between w-full border border-sky-200 rounded-xl px-3 py-2 text-sm bg-white cursor-pointer select-none shadow-sm hover:border-sky-400 transition">
                            <span id="selectedStatusTextAdd" class="font-medium text-slate-800">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </label>
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-sky-200 rounded-xl shadow-lg max-h-40 overflow-y-auto z-50 hidden peer-checked:block py-1">
                            <li onclick="selectStatus('Add', 'active', 'Aktif')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Aktif</li>
                            <li onclick="selectStatus('Add', 'inactive', 'Tidak Aktif')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Tidak Aktif</li>
                            <li onclick="selectStatus('Add', 'blocked', 'Diblokir')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Diblokir</li>
                        </ul>
                        <input type="hidden" name="status" id="statusInputAdd" value="active">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 pb-2">
                    <button type="button" onclick="closeModal('addUserModal')" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT PENGGUNA -->
    <div id="editUserModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 mx-4 overflow-hidden" id="editUserModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-pen-to-square text-sm"></i></div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Edit Pengguna</h3>
                </div>
                <button type="button" onclick="closeModal('editUserModal')" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="editUserForm" method="POST" action="" class="p-6 space-y-4 overflow-y-auto flex-1">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" id="editUserName" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Email</label>
                    <input type="email" name="email" id="editUserEmail" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Password Baru (opsional)</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="editPassword" minlength="8" placeholder="Kosongkan jika tidak diubah" class="w-full border border-sky-200 rounded-xl px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                        <button type="button" onclick="togglePassword('editPassword', 'eyeIconEdit')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none cursor-pointer">
                            <i class="fa-solid fa-eye text-sm" id="eyeIconEdit"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">No. Telepon</label>
                    <input type="text" name="phone" id="editUserPhone" class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Peran (Role)</label>
                    <div class="relative mt-1">
                        <input type="checkbox" id="roleDropdownToggleEdit" class="sr-only peer">
                        <label for="roleDropdownToggleEdit" class="flex items-center justify-between w-full border border-sky-200 rounded-xl px-3 py-2 text-sm bg-white cursor-pointer select-none shadow-sm hover:border-sky-400 transition">
                            <span id="selectedRoleTextEdit" class="font-medium text-slate-800">Kreator (Penjual)</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </label>
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-sky-200 rounded-xl shadow-lg max-h-40 overflow-y-auto z-50 hidden peer-checked:block py-1">
                            @foreach($roles as $role)
                                <li onclick="selectRole('Edit', '{{ $role->id_role }}', '{{ $role->role_name === 'penjual' ? 'Kreator (Penjual)' : 'Pembeli' }}')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">
                                    {{ $role->role_name === 'penjual' ? 'Kreator (Penjual)' : 'Pembeli' }}
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="id_role" id="roleInputEdit" value="">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Status</label>
                    <div class="relative mt-1">
                        <input type="checkbox" id="statusDropdownToggleEdit" class="sr-only peer">
                        <label for="statusDropdownToggleEdit" class="flex items-center justify-between w-full border border-sky-200 rounded-xl px-3 py-2 text-sm bg-white cursor-pointer select-none shadow-sm hover:border-sky-400 transition">
                            <span id="selectedStatusTextEdit" class="font-medium text-slate-800">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </label>
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-sky-200 rounded-xl shadow-lg max-h-40 overflow-y-auto z-50 hidden peer-checked:block py-1">
                            <li onclick="selectStatus('Edit', 'active', 'Aktif')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Aktif</li>
                            <li onclick="selectStatus('Edit', 'inactive', 'Tidak Aktif')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Tidak Aktif</li>
                            <li onclick="selectStatus('Edit', 'blocked', 'Diblokir')" class="px-3 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky cursor-pointer transition">Diblokir</li>
                        </ul>
                        <input type="hidden" name="status" id="statusInputEdit" value="active">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 pb-2">
                    <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
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

        function selectRole(modalType, id, text) {
            document.getElementById(`selectedRoleText${modalType}`).textContent = text;
            document.getElementById(`roleInput${modalType}`).value = id;
            document.getElementById(`roleDropdownToggle${modalType}`).checked = false;
        }

        function selectStatus(modalType, val, text) {
            document.getElementById(`selectedStatusText${modalType}`).textContent = text;
            document.getElementById(`statusInput${modalType}`).value = val;
            document.getElementById(`statusDropdownToggle${modalType}`).checked = false;
        }

        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function openAddUserModal() { openModal('addUserModal'); }

        function openEditUserModal(user) {
            const form = document.getElementById('editUserForm');
            form.action = `{{ url('admin/users') }}/${user.id}`;
            document.getElementById('editUserName').value = user.name ?? '';
            document.getElementById('editUserEmail').value = user.email ?? '';
            document.getElementById('editUserPhone').value = user.phone ?? '';

            const roleId = user.role ?? '';
            document.getElementById('roleInputEdit').value = roleId;
            let roleText = 'Kreator (Penjual)';
            if (roleId == '2' || roleId.toString().toLowerCase().includes('pembeli')) {
                roleText = 'Pembeli';
            }
            document.getElementById('selectedRoleTextEdit').textContent = roleText;

            const statusVal = user.status ?? 'active';
            document.getElementById('statusInputEdit').value = statusVal;
            let statusText = 'Aktif';
            if (statusVal === 'inactive') statusText = 'Tidak Aktif';
            if (statusVal === 'blocked') statusText = 'Diblokir';
            document.getElementById('selectedStatusTextEdit').textContent = statusText;

            openModal('editUserModal');
        }

        document.querySelectorAll('.btn-edit-user').forEach(btn => {
            btn.addEventListener('click', () => {
                openEditUserModal({
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    email: btn.dataset.email,
                    phone: btn.dataset.phone,
                    role: btn.dataset.role,
                    status: btn.dataset.status,
                });
            });
        });

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
        @if ($errors->any())
            Swal.fire({ icon: 'warning', title: 'Perhatian!', html: '<ul class="text-left text-xs space-y-1">@foreach($errors->all() as $err)<li>• {{ $err }}</li>@endforeach</ul>', confirmButtonColor: '#0EA5E9' });
        @endif
    </script>
</body>
</html>
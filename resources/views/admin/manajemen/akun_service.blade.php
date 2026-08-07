<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Manajemen Akun & Layanan CS</title>
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
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
                } 
            } 
        }
    </script>
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }

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
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR FULL -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
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

                <!-- KELOMPOK DROPDOWN MANAJEMEN PENGGUNA -->
                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i><span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-sky-200 w-3 text-center"></i> Akun Verifikator
                        </a>
                        <a href="{{ route('admin.manajemen.akun_service') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <i class="fa-solid fa-headset text-[10px] text-white w-3 text-center"></i> Akun & Layanan CS
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
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
                    <i class="fa-solid fa-crown w-4 text-center text-amber-300"></i><span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-server w-4 text-center"></i><span>Maintenance & Backup</span>
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
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Manajemen Akun & Layanan CS</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola akun Customer Service serta tinjau keluhan dan masukan pengguna.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- CARDS STATISTIK KELUHAN -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-circle-check"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Keluhan Selesai</p>
                            <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['selesai'] }}</h3>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-spinner animate-spin"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Sedang Proses</p>
                            <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['proses'] }}</h3>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-clock"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Belum Diproses</p>
                            <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['belum'] }}</h3>
                        </div>
                    </div>
                </div>

                <!-- SECTION 1: TABEL AKUN PETUGAS CS -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Daftar Akun Petugas Customer Service</h3>
                        
                        <!-- TOMBOL 3D BIRU KOKOH -->
                        <button type="button" onclick="openCsModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer">
                            <i class="fa-solid fa-user-plus"></i> Tambah Akun CS
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-3 px-4">Nama Petugas</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100">
                                @forelse($csUsers as $cs)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $cs->name }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $cs->email }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <form action="{{ route('admin.manajemen.akun_service.destroy', $cs->id_user) }}" method="POST" id="del-cs-{{ $cs->id_user }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDeleteCs('del-cs-{{ $cs->id_user }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition flex items-center justify-center mx-auto" title="Hapus Akun"><i class="fa-solid fa-trash text-xs"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-slate-400 font-semibold">Belum ada akun Customer Service terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 2: TABEL KELUHAN & MASUKAN PENGGUNA -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100">
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tinjauan Keluhan & Masukan Pengguna (Customer Tickets)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Pengguna</th>
                                    <th class="py-4 px-6">Subjek & Pesan</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Proses & Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100">
                                @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-6 font-bold text-slate-800">
                                        {{ $ticket->user->name ?? 'Anonim' }}
                                        <span class="block text-[10px] text-slate-400 font-normal">{{ $ticket->user->email ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <p class="font-bold text-slate-900">{{ $ticket->subject }}</p>
                                        <p class="text-slate-600 mt-0.5">{{ $ticket->message }}</p>
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($ticket->status == 'selesai')
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-bold border border-emerald-200">Selesai</span>
                                        @elseif($ticket->status == 'proses')
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg font-bold border border-amber-200">Proses</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg font-bold border border-rose-200">Belum</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <form action="{{ route('admin.manajemen.ticket.update', $ticket->id) }}" method="POST" class="flex flex-wrap items-center gap-2 justify-center">
                                            @csrf @method('PUT')
                                            <select name="status" class="border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold focus:outline-none bg-slate-50">
                                                <option value="belum" {{ $ticket->status == 'belum' ? 'selected' : '' }}>Belum</option>
                                                <option value="proses" {{ $ticket->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                            <input type="text" name="admin_note" value="{{ $ticket->admin_note }}" placeholder="Catatan respon..." class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs w-40 bg-slate-50">
                                            <button type="submit" class="px-3.5 py-1.5 bg-sky hover:bg-skyHover text-white rounded-xl font-bold transition shadow-sm">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-slate-400 font-semibold">Belum ada keluhan pengguna yang masuk ke sistem.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL TAMBAH AKUN CS -->
    <div id="csModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4" id="csModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center"><i class="fa-solid fa-user-plus text-sm"></i></div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Tambah Akun Customer Service</h3>
                </div>
                <button type="button" onclick="closeCsModal()" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('admin.manajemen.akun_service.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Nama Petugas</label>
                    <input type="text" name="name" required placeholder="Nama lengkap petugas..." class="mt-1.5 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-sky transition">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Email Akun</label>
                    <input type="email" name="email" required placeholder="cs@karyaku.com" class="mt-1.5 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-sky transition">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Password</label>
                    <div class="relative mt-1.5">
                        <input type="password" name="password" id="addPassword" required autocomplete="new-password" placeholder="Minimal 6 karakter" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-sky transition pr-10">
                        <button type="button" onclick="togglePassword('addPassword', 'eyeIconAdd')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none cursor-pointer">
                            <i class="fa-solid fa-eye text-sm" id="eyeIconAdd"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 transition flex justify-center items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check"></i> Simpan Akun CS
                    </button>
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
        
        function toggleSidebar() { 
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); 
            sidebarOverlay.classList.toggle('hidden'); 
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        // Skrip untuk dropdown submenu sidebar
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                if(submenu) submenu.classList.toggle('open');
                if(chevron) chevron.classList.toggle('rotated');
            });
        });

        // Fungsi Show/Hide Password
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
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

        const csModal = document.getElementById('csModal');
        const csModalContent = document.getElementById('csModalContent');

        function openCsModal() {
            csModal.classList.remove('hidden');
            setTimeout(() => {
                csModal.classList.remove('opacity-0');
                csModalContent.classList.remove('scale-95');
                csModalContent.classList.add('scale-100');
            }, 10);
        }

        function closeCsModal() {
            csModal.classList.add('opacity-0');
            csModalContent.classList.remove('scale-100');
            csModalContent.classList.add('scale-95');
            setTimeout(() => { csModal.classList.add('hidden'); }, 300);
        }

        function confirmDeleteCs(formId) {
            Swal.fire({
                title: 'Hapus Akun CS?',
                text: "Petugas tidak akan dapat masuk kembali ke sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById(formId).submit(); }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Keamanan System & Monitoring IP</title>
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

        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased h-screen overflow-hidden">

    <!-- MASTER CONTAINER (LOCKED VIEWPORT) -->
    <div class="flex h-screen w-full overflow-hidden relative">
        
        <!-- OVERLAY SIDEBAR MOBILE -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR CONTAINER (STABLE FIXED / STATIC) -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:static inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- PROFILE BOX -->
            <div class="p-4 mx-4 my-4 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner shrink-0">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
            </div>

            <!-- NAVIGATION MENU -->
            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-2">Menu Utama</p>
                
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <!-- MANAJEMEN PENGGUNA -->
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

                <!-- KATALOG & KATEGORI -->
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

                <!-- KEUANGAN -->
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

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.memberships') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center text-amber-300"></i><span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.maintenance') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group">
                    <i class="fa-solid fa-server w-4 text-center"></i><span>Maintenance & Backup</span>
                </a>

                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.pelanggaran') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center"></i>
                    <span>Pelanggaran</span>
                </a>

                <a href="{{ route('admin.security.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.security.*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <i class="fa-solid fa-shield-halved w-4 text-center text-white"></i><span>Keamanan System</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.notifications.*') ? 'active-menu' : 'hover:bg-white/10 hover:text-white' }} transition-all group mt-1">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center"></i>
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

            <div class="p-4 border-t border-white/15 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT CONTAINER (INDEPENDENT SCROLL) -->
        <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
            
            <!-- HEADER -->
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between shrink-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Keamanan System & Monitoring IP</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">IP Anda saat ini: <span class="font-mono font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200">{{ $myIp }}</span></p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.security.verify', ['reset' => 1]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] transition-all cursor-pointer">
                        <i class="fa-solid fa-lock"></i> Kunci Kembali
                    </a>
                </div>
            </header>

            <!-- SCROLLABLE BODY CONTENT -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-8">

                <!-- CARD KELOLA IP WHITELIST -->
                <div class="bg-white border border-sky-200/80 rounded-2xl p-6 shadow-lg shadow-sky-500/5">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-200 shadow-inner">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </div>
                                Kelola IP Whitelist (Izin Akses Menu Keamanan)
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Tambahkan IP perangkat yang diperbolehkan membuka halaman Keamanan System.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.security.allowed_ip.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3.5 mb-6">
                        @csrf
                        <div class="w-full sm:w-1/3">
                            <input type="text" name="ip_address" placeholder="Contoh: 180.252.12.99" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-sm">
                        </div>
                        <div class="w-full sm:w-1/3">
                            <input type="text" name="label" placeholder="Nama Perangkat (Misal: Laptop Admin 2)" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-sm">
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] transition-all cursor-pointer w-full sm:w-auto shrink-0">
                            <i class="fa-solid fa-plus"></i> Tambah IP Whitelist
                        </button>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        @foreach($allowedIps as $allowed)
                            <div class="border rounded-2xl p-4 flex justify-between items-center transition-all {{ $allowed->ip_address === $myIp ? 'bg-gradient-to-r from-sky-50 to-blue-50/60 border-sky-300 shadow-sm' : 'bg-slate-50/80 border-slate-200' }}">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-extrabold text-xs text-slate-800">{{ $allowed->ip_address }}</span>
                                        @if($allowed->ip_address === $myIp)
                                            <span class="text-[9px] bg-sky-600 text-white px-2 py-0.5 rounded-full font-bold shadow-xs">IP Anda</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-1">{{ $allowed->label }} &middot; <span class="text-[10px] text-slate-400 font-semibold">{{ $allowed->added_by }}</span></p>
                                </div>
                                @if($allowed->ip_address !== $myIp)
                                    <form action="{{ route('admin.security.allowed_ip.destroy', $allowed->id) }}" method="POST" id="delete-allowed-{{ $allowed->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAllowed('delete-allowed-{{ $allowed->id }}')" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs cursor-pointer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- TABEL 1: IP ABNORMAL (DETEKSI HACK/JAILBREAK) -->
                <div class="bg-white border border-red-200 rounded-2xl shadow-lg shadow-red-500/5 overflow-hidden">
                    <div class="p-5 border-b border-red-100 bg-gradient-to-r from-red-500/10 via-rose-50 to-white flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center border border-red-200 shadow-inner shrink-0">
                                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-red-950 text-base font-display">Daftar IP Mencurigakan (Abnormal & Attack Attempts)</h3>
                                <p class="text-[11px] text-red-600/80 font-medium">Pengunjung yang mencoba bobol file/jailbreak atau memicu honeypot sistem.</p>
                            </div>
                        </div>
                        <span class="bg-red-600 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">{{ $abnormalIps->count() }} Terdeteksi</span>
                    </div>

                    <!-- TABEL WITH INTERNAL SCROLLBAR -->
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-slate-200 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                                    <th class="py-3.5 px-6">Alamat IP</th>
                                    <th class="py-3.5 px-6">Ancaman / Alasan</th>
                                    <th class="py-3.5 px-6">File & Lokasi Dibobol</th>
                                    <th class="py-3.5 px-6 text-center">Total Request</th>
                                    <th class="py-3.5 px-6">Waktu Terakhir</th>
                                    <th class="py-3.5 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100">
                                @forelse($abnormalIps as $index => $ip)
                                <tr class="hover:bg-red-50/40 transition-colors bg-white odd:bg-slate-50/30 whitespace-nowrap">
                                    <td class="py-4 px-6 font-mono font-bold text-red-600">{{ $ip->ip_address }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold max-w-xs truncate">{{ $ip->reason ?? '-' }}</td>
                                    
                                    <td class="py-4 px-6 font-mono text-[11px] text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <span class="truncate max-w-[220px] bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200 font-bold">{{ $ip->last_activity ?? 'N/A' }}</span>
                                            <!-- ICON MATA UNTUK DETAIL BOBOL -->
                                            <button type="button" onclick="showJailbreakDetail('{{ $ip->ip_address }}', '{{ addslashes($ip->last_activity) }}', '{{ addslashes($ip->reason) }}', '{{ addslashes($ip->user_agent) }}')" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs shrink-0 cursor-pointer" title="Lihat Lokasi File & Payload">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-center font-bold text-slate-700"><span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[11px] border border-red-200">{{ $ip->request_count }}x</span></td>
                                    <td class="py-4 px-6 text-slate-500 font-medium">{{ $ip->last_activity_at?->diffForHumans() }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.security.toggle', $ip->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                                    Normal
                                                </button>
                                            </form>

                                            <form id="delete-log-{{ $index }}" action="{{ route('admin.security.log.destroy', $ip->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDeleteLog('delete-log-{{ $index }}')" class="w-7 h-7 rounded-lg bg-slate-200 hover:bg-red-600 text-slate-700 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs cursor-pointer" title="Hapus Log">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold bg-slate-50/20">
                                        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 rounded-xl">
                                            <i class="fa-solid fa-circle-check text-base"></i>
                                            <span>Sistem Aman! Belum ada aktivitas percobaan bobol/jailbreak terdeteksi.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABEL 2: IP NORMAL (WARNA KUNING AMBER & BEKUKAN TIMER HARI/JAM/DETIK) -->
                <div class="bg-amber-50/90 border border-amber-300 rounded-2xl shadow-lg shadow-amber-500/10 overflow-hidden">
                    <div class="p-5 border-b border-amber-200 bg-gradient-to-r from-amber-500/20 via-amber-100/60 to-amber-50 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center border border-amber-600 shadow-sm shrink-0">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-amber-950 text-base font-display">Daftar Pengunjung Biasa (Aktivitas Pengguna)</h3>
                                <p class="text-[11px] text-amber-900/90 font-semibold">Gunakan tombol kunci untuk membekukan sementara akun/IP yang terindikasi curang (Cheat/Abuse).</p>
                            </div>
                        </div>
                        <span class="bg-amber-600 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">{{ $normalIps->count() }} IP Logged</span>
                    </div>

                    <!-- TABEL WITH INTERNAL SCROLLBAR -->
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-amber-700 text-amber-50 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                                    <th class="py-3.5 px-6">Alamat IP</th>
                                    <th class="py-3.5 px-6">Aktivitas Terakhir</th>
                                    <th class="py-3.5 px-6">User Agent / Browser</th>
                                    <th class="py-3.5 px-6 text-center">Total Request</th>
                                    <th class="py-3.5 px-6">Waktu Terakhir</th>
                                    <th class="py-3.5 px-6 text-center">Aksi / Bekukan</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-amber-200 text-amber-950 font-medium">
                                @forelse($normalIps as $ip)
                                <tr class="hover:bg-amber-100/80 transition-colors bg-white odd:bg-amber-50/50 whitespace-nowrap">
                                    <td class="py-4 px-6 font-mono font-bold text-amber-900">{{ $ip->ip_address }}</td>
                                    <td class="py-4 px-6 font-mono text-[11px] text-amber-900 max-w-xs truncate">{{ $ip->last_activity }}</td>
                                    <td class="py-4 px-6 text-amber-800 max-w-xs truncate">{{ $ip->user_agent }}</td>
                                    <td class="py-4 px-6 text-center font-bold text-amber-900">
                                        <span class="bg-amber-200/80 px-2.5 py-1 rounded-md text-[11px] border border-amber-300 font-extrabold text-amber-950">{{ $ip->request_count }}x</span>
                                    </td>
                                    <td class="py-4 px-6 text-amber-900 font-semibold">{{ $ip->last_activity_at?->diffForHumans() }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <!-- TOMBOL BEKUKAN AKSES -->
                                        <button type="button" onclick="openFreezeTimerModal('{{ $ip->id }}', '{{ $ip->ip_address }}')" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition-all cursor-pointer">
                                            <i class="fa-solid fa-key"></i> Kunci (Bekukan)
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-amber-900 text-xs font-semibold bg-amber-50/20">
                                        <i class="fa-solid fa-inbox text-amber-400 text-xl block mb-2"></i>
                                        Belum ada riwayat aktivitas IP normal yang tercatat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        // TOGGLER ACCORDION DROPDOWN SIDEBAR
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                if(submenu) submenu.classList.toggle('open');
                if(chevron) chevron.classList.toggle('rotated');
            });
        });

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif

        // MODAL DETAIL FILE / LOKASI BOBOL (TABEL 1)
        function showJailbreakDetail(ip, activity, reason, userAgent) {
            Swal.fire({
                title: '🔍 Detail Percobaan Akses/Jailbreak',
                html: `
                    <div class="text-left text-xs space-y-3 font-sans">
                        <div class="bg-red-50 border border-red-200 p-3 rounded-xl">
                            <span class="block text-red-500 font-bold uppercase text-[10px]">Alamat IP:</span>
                            <span class="font-mono font-bold text-red-700 text-sm">${ip}</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                            <span class="block text-slate-500 font-bold uppercase text-[10px]">Target File / URI Lokasi:</span>
                            <code class="block font-mono text-slate-800 bg-white p-2 rounded border border-slate-300 mt-1 break-all">${activity}</code>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                            <span class="block text-slate-500 font-bold uppercase text-[10px]">Alasan Terdeteksi:</span>
                            <p class="font-semibold text-slate-700 mt-0.5">${reason}</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                            <span class="block text-slate-500 font-bold uppercase text-[10px]">User Agent / Perangkat:</span>
                            <p class="font-mono text-[11px] text-slate-600 mt-0.5 break-all">${userAgent}</p>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0ea5e9'
            });
        }

        // MODAL PEMBEKUAN TIMER (HARI, JAM, DETIK) UNTUK CHEATER/ABUSE (TABEL 2)
        function openFreezeTimerModal(id, ip) {
            Swal.fire({
                title: '🔐 Bekukan Akses IP / Akun',
                text: `Tentukan durasi pembekuan sementara untuk IP ${ip} (Akibat pelanggaran/kecurangan):`,
                html: `
                    <form id="freezeForm" action="{{ url('admin/security/toggle') }}/${id}" method="POST" class="mt-4 text-left font-sans">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Hari</label>
                                <input type="number" name="freeze_days" value="0" min="0" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Jam</label>
                                <input type="number" name="freeze_hours" value="1" min="0" max="23" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Detik</label>
                                <input type="number" name="freeze_seconds" value="0" min="0" max="59" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Catatan Pelanggaran (Kecurangan):</label>
                            <textarea name="reason" rows="2" placeholder="Contoh: Menggunakan cheat, indikasi kecurangan transaksi" required class="w-full border border-slate-300 rounded-lg p-2 text-xs font-medium focus:outline-none focus:border-amber-500"></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-lock"></i> Terapkan Pembekuan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#94a3b8',
                preConfirm: () => {
                    document.getElementById('freezeForm').submit();
                }
            });
        }

        function confirmDeleteAllowed(formId) {
            Swal.fire({
                title: 'Hapus IP Whitelist?', text: "IP ini tidak akan bisa mengakses menu Keamanan System lagi!",
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
        }

        function confirmDeleteLog(formId) {
            Swal.fire({
                title: 'Hapus Log IP?', text: "Catatan riwayat IP ini akan dihapus permanen!",
                icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
        }
    </script>
</body>
</html>
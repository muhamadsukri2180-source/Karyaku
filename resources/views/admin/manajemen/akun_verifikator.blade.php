<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Akun Verifikator & Verifikasi Identitas</title>
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
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $initials ?: 'AD' }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $admin->name ?? 'Admin' }}</p>
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
                        <i class="fa-solid fa-users w-4 text-center text-white transition-colors"></i>
                        <span class="text-white">Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-white w-3 text-center"></i> Akun Verifikator
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Akun Verifikator & Verifikasi Identitas</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola staf verifikator dan pantau progres validasi identitas.</p>
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

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-gradient-to-br from-indigo-50 via-white to-blue-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Verifikator</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $totalVerifikator }} Staf</div></div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/30"><i class="fa-solid fa-user-shield text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-indigo-200/50 pt-2 mt-2">Aktif bertugas memeriksa KTP</p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Antrean Masuk</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $antreanMasuk }} Berkas</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-clock-rotate-left text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Menunggu validasi tim</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Selesai Diverifikasi</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $selesaiHariIni }} Hari Ini</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-badge-check text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Akurat dan cepat</p>
                    </div>

                    <div class="bg-gradient-to-br from-sky-50 via-white to-blue-100/60 border-l-4 border-sky-500 border-y border-r border-sky-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider">Akurasi Sistem</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $akurasiSistem }}%</div></div>
                            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/30"><i class="fa-solid fa-chart-line text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-sky-200/50 pt-2 mt-2">Kinerja verifikator</p>
                    </div>
                </div>

                <!-- TAB SWITCH -->
                <div class="flex items-center gap-2 bg-white border border-sky-200 rounded-2xl p-1.5 w-full sm:w-max shadow-sm">
                    <button type="button" onclick="switchTab('staf')" id="tabBtnStaf" class="tab-btn active-tab px-4 py-2 rounded-xl text-xs font-bold text-slate-600 transition-all">
                        <i class="fa-solid fa-user-shield mr-1"></i> Data Verifikator
                    </button>
                    <button type="button" onclick="switchTab('antrean')" id="tabBtnAntrean" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 transition-all">
                        <i class="fa-solid fa-clipboard-list mr-1"></i> Antrean Verifikasi ({{ $antreanMasuk }})
                    </button>
                </div>

                <!-- TAB 1: DATA VERIFIKATOR -->
                <div id="tabStaf" class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <div class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="verifSearch" onkeyup="filterVerifikator()" placeholder="Cari nama verifikator..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </div>
                        <button type="button" onclick="openModal('addVerifierModal')" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-500/30 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                            <i class="fa-solid fa-user-shield"></i> Tambah Verifikator Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Nama Verifikator</th>
                                    <th class="py-4 px-6">Email Staf</th>
                                    <th class="py-4 px-6">Total Diperiksa</th>
                                    <th class="py-4 px-6">Status Tugas</th>
                                    <th class="py-4 px-6 text-center">Aksi (CRUD)</th>
                                </tr>
                            </thead>
                            <tbody id="verifikatorTableBody" class="text-sm divide-y divide-sky-100/70">
                                @forelse($verifikators as $v)
                                    @php
                                        $initialsRow = collect(explode(' ', trim($v->name)))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                                        $isActive = $v->status === 'active';
                                    @endphp
                                    <tr class="verif-row hover:bg-sky-50/50 transition-colors bg-white" data-name="{{ strtolower($v->name) }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">{{ $initialsRow ?: '??' }}</div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $v->name }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">Verifikator</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-xs font-semibold text-slate-700">{{ $v->email }}</td>
                                        <td class="py-3 px-6 text-xs font-bold text-sky-700">{{ number_format($v->total_checked, 0, ',', '.') }} Berkas</td>
                                        <td class="py-3 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold {{ $isActive ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span> {{ $isActive ? 'Bertugas' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                               <button type="button"
    class="btn-edit-verifier px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold shadow-sm"
    data-id="{{ $v->id_user }}"
    data-name="{{ $v->name }}"
    data-email="{{ $v->email }}">
    <i class="fa-solid fa-pen-to-square"></i> Edit
</button>
                                                <button type="button" onclick="openDeleteVerifierModal('{{ $v->id_user }}', '{{ addslashes($v->name) }}')" class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm">
                                                    <i class="fa-solid fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data verifikator.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: ANTREAN VERIFIKASI -->
                <div id="tabAntrean" class="hidden bg-gradient-to-b from-white to-amber-50/30 border border-amber-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-amber-100 bg-white/50 backdrop-blur-sm">
                        <h3 class="font-extrabold text-slate-900 text-sm font-display">Antrean Verifikasi Identitas Kreator</h3>
                        <p class="text-[11px] text-slate-600 mt-0.5">Tinjau dan putuskan status pengajuan identitas kreator baru.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-amber-50/80 border-b border-amber-100 text-amber-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Pemohon</th>
                                    <th class="py-4 px-6">Tgl Pengajuan</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-amber-100/70">
                                @forelse($pendingQueue as $item)
                                    @php
                                        $iInitials = collect(explode(' ', trim($item->user->name ?? '-')))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                                    @endphp
                                    <tr class="hover:bg-amber-50/40 transition-colors bg-white">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">{{ $iInitials ?: '??' }}</div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $item->user->name ?? '-' }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">{{ $item->user->email ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6"><p class="text-xs font-semibold text-slate-700">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</p></td>
                                        <td class="py-3 px-6">
                                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 border border-amber-200 px-2.5 py-1 rounded-md flex items-center w-max gap-1.5">
                                                <i class="fa-regular fa-clock"></i> Menunggu
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <form method="POST" action="{{ route('admin.users.approveSeller', $item->id) }}" onsubmit="return confirm('Setujui pengajuan identitas ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold shadow-sm">
                                                        <i class="fa-solid fa-check"></i> Setujui
                                                    </button>
                                                </form>
                                                <button type="button" onclick="openRejectModal('{{ $item->id }}', '{{ addslashes($item->user->name ?? '-') }}')" class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center text-sm text-slate-500">Tidak ada antrean verifikasi saat ini. 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pendingQueue->hasPages())
                        <div class="p-5 border-t border-amber-100 bg-white/50">
                            {{ $pendingQueue->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL: TAMBAH VERIFIKATOR -->
    <div id="addVerifierModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('addVerifierModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-extrabold text-lg text-slate-900">Tambah Verifikator Baru</h3>
                <button onclick="closeModal('addVerifierModal')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.users.addVerifier') }}" class="space-y-3">
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
                    <input type="password" name="password" required minlength="8" class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('addVerifierModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 shadow-md">Simpan Verifikator</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT VERIFIKATOR -->
    <div id="editVerifierModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('editVerifierModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-extrabold text-lg text-slate-900">Edit Verifikator</h3>
                <button onclick="closeModal('editVerifierModal')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editVerifierForm" method="POST" action="" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" id="editVerifierName" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Email</label>
                    <input type="email" name="email" id="editVerifierEmail" required class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700">Password Baru (opsional)</label>
                    <input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak diubah" class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('editVerifierModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: HAPUS VERIFIKATOR -->
    <div id="deleteVerifierModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('deleteVerifierModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h3 class="font-display font-extrabold text-lg text-slate-900 mb-1">Hapus Verifikator?</h3>
            <p class="text-xs text-slate-600 mb-5">Anda akan menghapus <strong id="deleteVerifierName"></strong> secara permanen.</p>
            <form id="deleteVerifierForm" method="POST" action="" class="flex justify-center gap-2">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('deleteVerifierModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 shadow-md">Ya, Hapus</button>
            </form>
        </div>
    </div>

    <!-- MODAL: TOLAK PENGAJUAN IDENTITAS -->
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('rejectModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-extrabold text-lg text-slate-900">Tolak Pengajuan Identitas</h3>
                <button onclick="closeModal('rejectModal')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <p class="text-xs text-slate-600 mb-4">Anda akan menolak pengajuan dari <strong id="rejectUserName"></strong>. Berikan alasan penolakan (opsional).</p>
            <form id="rejectForm" method="POST" action="" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-700">Catatan Penolakan</label>
                    <textarea name="notes" rows="3" maxlength="500" placeholder="Contoh: Foto KTP buram, mohon unggah ulang." class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('rejectModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 shadow-md">Tolak Pengajuan</button>
                </div>
            </form>
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

        function switchTab(tab) {
            document.getElementById('tabStaf').classList.toggle('hidden', tab !== 'staf');
            document.getElementById('tabAntrean').classList.toggle('hidden', tab !== 'antrean');
            document.getElementById('tabBtnStaf').classList.toggle('active-tab', tab === 'staf');
            document.getElementById('tabBtnAntrean').classList.toggle('active-tab', tab === 'antrean');
        }

        function filterVerifikator() {
            const q = document.getElementById('verifSearch').value.toLowerCase();
            document.querySelectorAll('#verifikatorTableBody .verif-row').forEach(row => {
                row.style.display = row.dataset.name.includes(q) ? '' : 'none';
            });
        }

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

function openEditVerifierModal(v) {
    const form = document.getElementById('editVerifierForm');
    form.action = `{{ url('admin/users/verifier') }}/${v.id}`;
    document.getElementById('editVerifierName').value = v.name ?? '';
    document.getElementById('editVerifierEmail').value = v.email ?? '';
    openModal('editVerifierModal');
}

document.querySelectorAll('.btn-edit-verifier').forEach(btn => {
    btn.addEventListener('click', () => {
        openEditVerifierModal({
            id: btn.dataset.id,
            name: btn.dataset.name,
            email: btn.dataset.email,
        });
    });
});

        function openDeleteVerifierModal(id, name) {
            const form = document.getElementById('deleteVerifierForm');
            form.action = `{{ url('admin/users/verifier') }}/${id}`;
            document.getElementById('deleteVerifierName').textContent = name;
            openModal('deleteVerifierModal');
        }

        function openRejectModal(id, name) {
            const form = document.getElementById('rejectForm');
            form.action = `{{ url('admin/users/reject-seller') }}/${id}`;
            document.getElementById('rejectUserName').textContent = name;
            openModal('rejectModal');
        }
    </script>
</body>
</html>
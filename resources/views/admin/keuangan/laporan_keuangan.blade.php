<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Laporan Keuangan Bulanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .card-hover { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
        .tab-btn.active {
            background-color: #0EA5E9;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35);
        }
        @media print {
            aside, #sidebar, #topNavbar, .no-print, #sidebarOverlay, .filter-box, #mainScreenWrapper {
                display: none !important;
            }
            html, body {
                background: #ffffff !important;
                color: #0f172a !important;
                font-family: 'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif !important;
                font-size: 8pt !important;
                line-height: 1.35 !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm 10mm 12mm 10mm;
            }
            #printDocument {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .print-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-top: 6px !important;
                margin-bottom: 14px !important;
                font-size: 7.5pt !important;
            }
            .print-table th, 
            .print-table td {
                border: 1px solid #94a3b8 !important;
                padding: 4px 6px !important;
                vertical-align: middle !important;
            }
            .print-table thead th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                font-size: 7pt !important;
                letter-spacing: 0.3px !important;
                -webkit-print-color-adjust: exact !important;
            }
            .print-table tfoot td {
                background-color: #f8fafc !important;
                font-weight: 700 !important;
                border-top: 2px solid #334155 !important;
                -webkit-print-color-adjust: exact !important;
            }
            .print-table thead {
                display: table-header-group !important;
            }
            .print-table tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            body.print-scope-orders #printSectionWithdrawals,
            body.print-scope-orders #printSectionDaily {
                display: none !important;
            }
            body.print-scope-withdrawals #printSectionOrders,
            body.print-scope-withdrawals #printSectionDaily {
                display: none !important;
            }
            body.print-scope-daily #printSectionOrders,
            body.print-scope-daily #printSectionWithdrawals {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

    <div id="mainScreenWrapper" class="flex min-h-screen relative">
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
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <span class="text-[10px] text-sky-200 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Administrator</span>
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
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                        <a href="{{ route('admin.laporan.keuangan') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs font-bold">
                            <i class="fa-solid fa-file-invoice-dollar text-[10px] text-white w-3 text-center"></i> Laporan Keuangan
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

                <a href="{{ route('admin.notifications.index') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i>
                        <span>Notifikasi</span>
                    </div>
                </a>
            </nav>
            <div class="p-4 border-t border-white/15">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-red-500/80 text-white font-semibold transition-all duration-200 text-xs">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- TOP NAVBAR -->
            <header id="topNavbar" class="h-16 bg-white/80 backdrop-blur-md border-b border-sky-100 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30 shadow-sm no-print">
                <div class="flex items-center gap-3">
                    <button id="sidebarOpenBtn" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-sky-50"><i class="fa-solid fa-bars text-lg"></i></button>
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-sky">Dashboard</a>
                            <span>/</span>
                            <span>Keuangan</span>
                            <span>/</span>
                            <span class="text-sky font-semibold">Laporan Keuangan Bulanan</span>
                        </div>
                        <h2 class="text-lg font-display font-bold text-slate-800 leading-tight">Laporan Keuangan</h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Month Navigator -->
                    <div class="hidden sm:flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-semibold">
                        @if(!empty($dateRange['has_prev']))
                            <a href="{{ route('admin.laporan.keuangan', ['month' => $dateRange['prev_month'], 'year' => $dateRange['prev_year']]) }}" 
                               class="px-2.5 py-1 rounded-lg text-slate-600 hover:bg-white hover:text-sky transition-all flex items-center gap-1" title="Bulan Sebelumnya">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </a>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-slate-300 cursor-not-allowed opacity-40 flex items-center gap-1" title="Batas Awal Tahun Launching (2026)">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </span>
                        @endif

                        <span class="px-3 py-1 font-bold text-skyDeep">
                            {{ $dateRange['month_name'] }} {{ $dateRange['year'] }}
                        </span>

                        @if(!empty($dateRange['has_next']))
                            <a href="{{ route('admin.laporan.keuangan', ['month' => $dateRange['next_month'], 'year' => $dateRange['next_year']]) }}" 
                               class="px-2.5 py-1 rounded-lg text-slate-600 hover:bg-white hover:text-sky transition-all flex items-center gap-1" title="Bulan Berikutnya">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-slate-300 cursor-not-allowed opacity-40 flex items-center gap-1" title="Bulan Terkini">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Smart Print Button with Dropdown -->
                    <div class="relative inline-block text-left" id="printDropdownContainer">
                        <div class="flex items-center">
                            <button type="button" onclick="triggerPrint('all')" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-l-xl border border-slate-200 shadow-sm transition-all flex items-center gap-2">
                                <i class="fa-solid fa-print text-sky-600"></i>
                                <span>Cetak Laporan</span>
                            </button>
                            <button type="button" onclick="togglePrintDropdown(event)" class="px-2 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-r-xl border-t border-r border-b border-slate-200 shadow-sm transition-all" title="Pilihan Cetak">
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-500"></i>
                            </button>
                        </div>
                        <div id="printDropdownMenu" class="hidden absolute right-0 mt-2 w-60 rounded-2xl bg-white shadow-xl border border-slate-200 py-2 z-50">
                            <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                Opsi Format Cetak
                            </div>
                            <button type="button" onclick="triggerPrint('all')" class="w-full text-left px-3.5 py-2 text-xs text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2.5 transition">
                                <i class="fa-solid fa-file-invoice text-sky-500 w-4"></i>
                                <div>
                                    <span class="font-bold block">Laporan Lengkap</span>
                                    <span class="text-[10px] text-slate-400">Ringkasan & seluruh tabel data</span>
                                </div>
                            </button>
                            <button type="button" onclick="triggerPrint('active')" class="w-full text-left px-3.5 py-2 text-xs text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2.5 transition">
                                <i class="fa-solid fa-table-list text-emerald-500 w-4"></i>
                                <div>
                                    <span class="font-bold block">Tab Aktif Saja</span>
                                    <span class="text-[10px] text-slate-400" id="printActiveTabLabel">Transaksi Penjualan</span>
                                </div>
                            </button>
                            <button type="button" onclick="triggerPrint('orders')" class="w-full text-left px-3.5 py-2 text-xs text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2.5 transition">
                                <i class="fa-solid fa-cart-shopping text-blue-500 w-4"></i>
                                <div>
                                    <span class="font-bold block">Hanya Transaksi Penjualan</span>
                                    <span class="text-[10px] text-slate-400">{{ $orders->count() }} transaksi</span>
                                </div>
                            </button>
                            <button type="button" onclick="triggerPrint('withdrawals')" class="w-full text-left px-3.5 py-2 text-xs text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2.5 transition">
                                <i class="fa-solid fa-wallet text-amber-500 w-4"></i>
                                <div>
                                    <span class="font-bold block">Hanya Penarikan Saldo</span>
                                    <span class="text-[10px] text-slate-400">{{ $withdrawals->count() }} pengajuan</span>
                                </div>
                            </button>
                            <button type="button" onclick="triggerPrint('daily')" class="w-full text-left px-3.5 py-2 text-xs text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2.5 transition">
                                <i class="fa-solid fa-calendar-day text-indigo-500 w-4"></i>
                                <div>
                                    <span class="font-bold block">Hanya Rekap Harian</span>
                                    <span class="text-[10px] text-slate-400">{{ count($dailyBreakdown) }} hari pembukuan</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Export Excel Button -->
                    <a href="{{ route('admin.laporan.keuangan.export', request()->query()) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/25 transition-all flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fa-solid fa-file-excel text-emerald-100 text-sm"></i>
                        <span>Ekspor Excel (.xlsx)</span>
                    </a>
                </div>
            </header>

            <div class="p-4 lg:p-8 space-y-6">

                <!-- FILTER CONTROLS CARD -->
                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-sky-100 shadow-sm filter-box">
                    <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-4">
                        <input type="hidden" name="filter_type" value="bulanan">
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                            <!-- Dropdown Bulan -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Bulan</label>
                                <div class="relative">
                                    <select name="month" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky/30 focus:border-sky appearance-none cursor-pointer">
                                        @foreach($dateRange['month_names'] as $mNum => $mName)
                                            <option value="{{ $mNum }}" {{ $summary['month'] == $mNum ? 'selected' : '' }}>
                                                {{ $mName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Dropdown Tahun -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tahun</label>
                                <div class="relative">
                                    <select name="year" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky/30 focus:border-sky appearance-none cursor-pointer">
                                        @foreach($dateRange['available_years'] as $yr)
                                            <option value="{{ $yr }}" {{ $summary['year'] == $yr ? 'selected' : '' }}>
                                                {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Status Pembayaran Order -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Order</label>
                                <div class="relative">
                                    <select name="status" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky/30 focus:border-sky appearance-none cursor-pointer">
                                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Hanya Lunas (Paid)</option>
                                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal / Batal</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Filter & Reset -->
                        <div class="flex items-center gap-2 w-full lg:w-auto justify-end">
                            <a href="{{ route('admin.laporan.keuangan', ['month' => now()->month, 'year' => now()->year]) }}" 
                               class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all">
                                Bulan Ini
                            </a>
                            <button type="submit" class="px-5 py-2 bg-sky hover:bg-skyHover text-white text-xs font-bold rounded-xl shadow-md shadow-sky/20 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-filter text-[11px]"></i>
                                <span>Terapkan Filter</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- FINANCIAL SUMMARY KPI CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- 1. Total Pemasukan Bruto -->
                    <div class="bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-white rounded-2xl p-5 border border-emerald-500/20 shadow-sm relative overflow-hidden card-hover">
                        <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Total Pemasukan (Bruto)</span>
                            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/30">
                                <i class="fa-solid fa-arrow-trend-up text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-display font-extrabold text-slate-800 mb-1">
                            Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center justify-between text-xs text-slate-500 mt-3 pt-3 border-t border-emerald-500/15">
                            <span class="flex items-center gap-1 font-semibold text-emerald-600">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $summary['total_orders_paid'] }} Transaksi Lunas
                            </span>
                            <span class="text-[11px] text-slate-400">Gross Sales</span>
                        </div>
                    </div>

                    <!-- 2. Komisi Platform (5%) -->
                    <div class="bg-gradient-to-br from-sky-500/10 via-sky-500/5 to-white rounded-2xl p-5 border border-sky-500/20 shadow-sm relative overflow-hidden card-hover">
                        <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-sky-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-sky-700">Komisi Platform (5%)</span>
                            <div class="w-9 h-9 rounded-xl bg-sky text-white flex items-center justify-center shadow-md shadow-sky/30">
                                <i class="fa-solid fa-coins text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-display font-extrabold text-slate-800 mb-1">
                            Rp {{ number_format($summary['total_komisi_platform'], 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center justify-between text-xs text-slate-500 mt-3 pt-3 border-t border-sky-500/15">
                            <span class="font-semibold text-sky-600">Pendapatan Platform</span>
                            <span class="text-[11px] text-slate-400">Rate 5.0%</span>
                        </div>
                    </div>

                    <!-- 3. Total Penarikan Disetujui -->
                    <div class="bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-white rounded-2xl p-5 border border-amber-500/20 shadow-sm relative overflow-hidden card-hover">
                        <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Penarikan Saldo Penjual</span>
                            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30">
                                <i class="fa-solid fa-money-bill-transfer text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-display font-extrabold text-slate-800 mb-1">
                            Rp {{ number_format($summary['total_penarikan_disetujui'], 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center justify-between text-xs text-slate-500 mt-3 pt-3 border-t border-amber-500/15">
                            <span class="flex items-center gap-1 font-semibold text-amber-600">
                                <i class="fa-solid fa-check-double text-[10px]"></i> {{ $summary['count_penarikan_disetujui'] }} Pencairan Selesai
                            </span>
                            <span class="text-[11px] text-slate-400">Total Payout</span>
                        </div>
                    </div>

                    <!-- 4. Saldo Bersih / Arus Kas Net -->
                    <div class="bg-gradient-to-br from-indigo-500/10 via-indigo-500/5 to-white rounded-2xl p-5 border border-indigo-500/20 shadow-sm relative overflow-hidden card-hover">
                        <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-indigo-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Arus Kas Bersih (Net)</span>
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/30">
                                <i class="fa-solid fa-scale-balanced text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-display font-extrabold {{ $summary['saldo_bersih'] >= 0 ? 'text-indigo-700' : 'text-rose-600' }} mb-1">
                            Rp {{ number_format($summary['saldo_bersih'], 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center justify-between text-xs text-slate-500 mt-3 pt-3 border-t border-indigo-500/15">
                            <span class="font-semibold text-indigo-600">Pemasukan - Pencairan</span>
                            <span class="text-[11px] text-slate-400">Net Flow</span>
                        </div>
                    </div>
                </div>

                <!-- SECONDARY STATS BAR -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-white/80 rounded-xl p-3.5 border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0">
                            <i class="fa-solid fa-percentage"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Success Rate</p>
                            <p class="text-sm font-extrabold text-slate-800">{{ $summary['success_rate'] }}% <span class="text-[10px] font-normal text-slate-500">({{ $summary['total_orders_paid'] }}/{{ $summary['total_orders_count'] }})</span></p>
                        </div>
                    </div>

                    <div class="bg-white/80 rounded-xl p-3.5 border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-xs font-bold shrink-0">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Order (AOV)</p>
                            <p class="text-sm font-extrabold text-slate-800">Rp {{ number_format($summary['rata_rata_transaksi'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="bg-white/80 rounded-xl p-3.5 border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold shrink-0">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Penarikan Pending</p>
                            <p class="text-sm font-extrabold text-amber-600">{{ $summary['count_penarikan_pending'] }} <span class="text-[10px] font-normal text-slate-500">(Rp {{ number_format($summary['total_penarikan_pending'], 0, ',', '.') }})</span></p>
                        </div>
                    </div>

                    <div class="bg-white/80 rounded-xl p-3.5 border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold shrink-0">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Order Batal / Gagal</p>
                            <p class="text-sm font-extrabold text-rose-600">{{ $summary['total_orders_failed'] }} <span class="text-[10px] font-normal text-slate-500">Transaksi</span></p>
                        </div>
                    </div>
                </div>

                <!-- CHARTS ROW -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Tren Finansial Harian (Line & Bar Dual Axis Chart) -->
                    <div class="lg:col-span-2 bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-sky-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-display font-bold text-base text-slate-800">Tren Finansial Harian</h3>
                                <p class="text-xs text-slate-500">Perbandingan Pemasukan (Inflow) vs Penarikan Saldo (Outflow) periode {{ $dateRange['month_name'] }} {{ $dateRange['year'] }}</p>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-semibold">
                                <span class="flex items-center gap-1.5 text-emerald-600"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Pemasukan</span>
                                <span class="flex items-center gap-1.5 text-amber-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Penarikan</span>
                            </div>
                        </div>
                        <div class="relative h-[280px] w-full">
                            <canvas id="dailyFinancialChart"></canvas>
                        </div>
                    </div>

                    <!-- Distribusi Status Keuangan (Doughnut Chart) -->
                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-sky-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-display font-bold text-base text-slate-800 mb-1">Status Pembayaran Pesanan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi status transaksi bulan ini</p>
                            <div class="relative h-[210px] w-full flex items-center justify-center">
                                <canvas id="statusDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center pt-4 border-t border-slate-100 text-xs mt-2">
                            <div class="p-2 rounded-xl bg-emerald-50">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase">Lunas</span>
                                <span class="font-extrabold text-slate-800">{{ $summary['total_orders_paid'] }}</span>
                            </div>
                            <div class="p-2 rounded-xl bg-amber-50">
                                <span class="block text-[10px] font-bold text-amber-600 uppercase">Unpaid</span>
                                <span class="font-extrabold text-slate-800">{{ $summary['total_orders_unpaid'] }}</span>
                            </div>
                            <div class="p-2 rounded-xl bg-rose-50">
                                <span class="block text-[10px] font-bold text-rose-600 uppercase">Batal</span>
                                <span class="font-extrabold text-slate-800">{{ $summary['total_orders_failed'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABBED DATA TABLES -->
                <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-sky-100 shadow-sm overflow-hidden">
                    <!-- Tab Navigation Header -->
                    <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-50/50">
                        <div class="flex items-center gap-2 p-1 bg-slate-200/70 rounded-xl text-xs font-bold text-slate-600">
                            <button type="button" onclick="switchTab('orders')" id="tabBtnOrders" class="tab-btn active px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                                <i class="fa-solid fa-cart-shopping text-xs"></i>
                                <span>Transaksi Penjualan</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-white/20 text-[10px]">{{ $orders->count() }}</span>
                            </button>
                            <button type="button" onclick="switchTab('withdrawals')" id="tabBtnWithdrawals" class="tab-btn px-4 py-2 rounded-lg transition-all flex items-center gap-2 hover:text-slate-900">
                                <i class="fa-solid fa-wallet text-xs"></i>
                                <span>Penarikan Saldo</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-slate-300 text-slate-700 text-[10px]">{{ $withdrawals->count() }}</span>
                            </button>
                            <button type="button" onclick="switchTab('daily')" id="tabBtnDaily" class="tab-btn px-4 py-2 rounded-lg transition-all flex items-center gap-2 hover:text-slate-900">
                                <i class="fa-solid fa-calendar-day text-xs"></i>
                                <span>Rekap Harian</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-slate-300 text-slate-700 text-[10px]">{{ count($dailyBreakdown) }} Hari</span>
                            </button>
                        </div>

                        <!-- Realtime Search Input -->
                        <div class="relative w-full sm:w-64 no-print">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-xs text-slate-400"></i>
                            <input type="text" id="tableSearchInput" onkeyup="filterTableRows()" placeholder="Cari dalam tabel..." 
                                   class="w-full pl-9 pr-3.5 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky/30 focus:border-sky shadow-inner">
                        </div>
                    </div>

                    <!-- TAB 1: TRANSAKSI PENJUALAN -->
                    <div id="tabContentOrders" class="tab-pane block">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-700" id="ordersTable">
                                <thead class="bg-slate-100/80 text-[11px] font-bold uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 text-center w-12">No</th>
                                        <th class="py-3 px-4">Kode Order</th>
                                        <th class="py-3 px-4">Tanggal & Waktu</th>
                                        <th class="py-3 px-4">Pembeli</th>
                                        <th class="py-3 px-4">Detail Produk / Item</th>
                                        <th class="py-3 px-4 text-center">Status Bayar</th>
                                        <th class="py-3 px-4 text-center">Status Order</th>
                                        <th class="py-3 px-4 text-right">Total Transaksi</th>
                                        <th class="py-3 px-4 text-right">Komisi 5%</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($orders as $index => $order)
                                    <tr class="hover:bg-sky-50/40 transition-colors">
                                        <td class="py-3 px-4 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-skyDeep">
                                            #{{ $order->id_order }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-slate-800">{{ $order->buyer->name ?? 'User #' . $order->buyer_id }}</span>
                                        </td>
                                        <td class="py-3 px-4 max-w-xs truncate text-slate-600" title="{{ $order->items->map(fn($it) => ($it->product->title ?? 'Item') . ' (' . $it->quantity . 'x)')->implode(', ') }}">
                                            @if($order->items && $order->items->count() > 0)
                                                {{ $order->items->map(fn($it) => ($it->product->title ?? 'Item') . ' (' . $it->quantity . 'x)')->implode(', ') }}
                                            @else
                                                <span class="italic text-slate-400">Tanpa rincian item</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($order->payment_status === 'paid')
                                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Lunas
                                                </span>
                                            @elseif($order->payment_status === 'unpaid')
                                                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Unpaid
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    {{ $order->payment_status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($order->status === 'selesai')
                                                <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Selesai
                                                </span>
                                            @elseif($order->status === 'diproses')
                                                <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Diproses
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    {{ $order->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-800 whitespace-nowrap">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-sky-600 whitespace-nowrap">
                                            Rp {{ number_format($order->total_price * 0.05, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="py-8 text-center text-slate-400">
                                            <i class="fa-solid fa-inbox text-3xl mb-2 block text-slate-300"></i>
                                            Tidak ada data transaksi pesanan pada periode {{ $dateRange['month_name'] }} {{ $dateRange['year'] }}.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($orders->count() > 0)
                                <tfoot class="bg-slate-100 font-extrabold text-slate-800 border-t-2 border-slate-200">
                                    <tr>
                                        <td colspan="7" class="py-3 px-4 text-right">TOTAL TRANSAKSI KESELURUHAN:</td>
                                        <td class="py-3 px-4 text-right text-emerald-700">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-sky-700">Rp {{ number_format($orders->sum('total_price') * 0.05, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: PENARIKAN SALDO -->
                    <div id="tabContentWithdrawals" class="tab-pane hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-700" id="withdrawalsTable">
                                <thead class="bg-slate-100/80 text-[11px] font-bold uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 text-center w-12">No</th>
                                        <th class="py-3 px-4">ID Penarikan</th>
                                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                                        <th class="py-3 px-4">Nama Penjual</th>
                                        <th class="py-3 px-4">Bank & Rekening</th>
                                        <th class="py-3 px-4">Atas Nama</th>
                                        <th class="py-3 px-4 text-right">Nominal</th>
                                        <th class="py-3 px-4">Tanggal Proses</th>
                                        <th class="py-3 px-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($withdrawals as $index => $w)
                                    <tr class="hover:bg-sky-50/40 transition-colors">
                                        <td class="py-3 px-4 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-teal-800">
                                            #WD-{{ $w->id_withdrawal }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                            {{ $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-slate-800">{{ $w->user->name ?? 'Penjual #' . $w->user_id }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-slate-700">{{ strtoupper($w->bank_name ?? '-') }}</span>
                                            <span class="block text-[11px] text-slate-400 font-mono">{{ $w->bank_account_number }}</span>
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-slate-700">
                                            {{ $w->bank_account_name ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-800 whitespace-nowrap">
                                            Rp {{ number_format($w->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                            {{ $w->processed_at ? $w->processed_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @php $wStat = strtolower($w->status); @endphp
                                            @if(in_array($wStat, ['processed', 'approved', 'selesai', 'success']))
                                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Selesai
                                                </span>
                                            @elseif($wStat === 'pending')
                                                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-extrabold uppercase tracking-wider">
                                                    {{ $w->status }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="py-8 text-center text-slate-400">
                                            <i class="fa-solid fa-inbox text-3xl mb-2 block text-slate-300"></i>
                                            Tidak ada data pengajuan penarikan saldo pada periode ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($withdrawals->count() > 0)
                                <tfoot class="bg-slate-100 font-extrabold text-slate-800 border-t-2 border-slate-200">
                                    <tr>
                                        <td colspan="6" class="py-3 px-4 text-right">TOTAL PENARIKAN SALDO:</td>
                                        <td class="py-3 px-4 text-right text-amber-700">Rp {{ number_format($withdrawals->sum('amount'), 0, ',', '.') }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: REKAPITULASI HARIAN -->
                    <div id="tabContentDaily" class="tab-pane hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-700" id="dailyTable">
                                <thead class="bg-slate-100/80 text-[11px] font-bold uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 text-center w-12">No</th>
                                        <th class="py-3 px-4">Tanggal</th>
                                        <th class="py-3 px-4">Hari</th>
                                        <th class="py-3 px-4 text-center">Transaksi Lunas</th>
                                        <th class="py-3 px-4 text-right">Pemasukan Bruto (Inflow)</th>
                                        <th class="py-3 px-4 text-right">Komisi Platform (5%)</th>
                                        <th class="py-3 px-4 text-right">Penarikan Saldo (Outflow)</th>
                                        <th class="py-3 px-4 text-right">Arus Kas Bersih (Net)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($dailyBreakdown as $idx => $day)
                                    <tr class="hover:bg-sky-50/40 transition-colors {{ $day['inflow'] > 0 || $day['outflow'] > 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                        <td class="py-2.5 px-4 text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-800">{{ $day['formatted'] }}</td>
                                        <td class="py-2.5 px-4 text-slate-500">{{ $day['day_name'] }}</td>
                                        <td class="py-2.5 px-4 text-center font-bold {{ $day['order_count'] > 0 ? 'text-sky-600' : 'text-slate-400' }}">
                                            {{ $day['order_count'] }}
                                        </td>
                                        <td class="py-2.5 px-4 text-right font-bold text-emerald-600 whitespace-nowrap">
                                            Rp {{ number_format($day['inflow'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-4 text-right font-semibold text-sky-600 whitespace-nowrap">
                                            Rp {{ number_format($day['commission'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-4 text-right font-bold text-amber-600 whitespace-nowrap">
                                            Rp {{ number_format($day['outflow'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-4 text-right font-extrabold {{ $day['net'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }} whitespace-nowrap">
                                            Rp {{ number_format($day['net'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-100 font-extrabold text-slate-800 border-t-2 border-slate-200">
                                    <tr>
                                        <td colspan="3" class="py-3 px-4 text-right">TOTAL BULANAN:</td>
                                        <td class="py-3 px-4 text-center text-sky-700">{{ $summary['total_orders_paid'] }}</td>
                                        <td class="py-3 px-4 text-right text-emerald-700">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-sky-700">Rp {{ number_format($summary['total_komisi_platform'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-amber-700">Rp {{ number_format($summary['total_penarikan_disetujui'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-indigo-700">Rp {{ number_format($summary['saldo_bersih'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- DEDICATED OFFICIAL PRINT DOCUMENT (Only rendered when printing) -->
    <div id="printDocument" class="hidden">
        <!-- 1. KOP LAPORAN RESMI -->
        <div class="print-kop-wrapper">
            <table style="width: 100%; border-collapse: collapse; border-bottom: 3px double #0f172a; padding-bottom: 8px; margin-bottom: 12px;">
                <tr>
                    <td style="width: 55px; vertical-align: middle; padding-bottom: 8px;">
                        <div style="width: 48px; height: 48px; background-color: #0B3D62; color: #ffffff; border-radius: 8px; font-weight: 800; font-size: 20pt; text-align: center; line-height: 48px; font-family: 'Plus Jakarta Sans', sans-serif;">
                            K
                        </div>
                    </td>
                    <td style="vertical-align: middle; padding-left: 12px; padding-bottom: 8px;">
                        <h1 style="margin: 0; font-size: 16pt; font-weight: 800; color: #0B3D62; letter-spacing: 0.5px; text-transform: uppercase; font-family: 'Plus Jakarta Sans', sans-serif;">
                            KARYAKU INDONESIA
                        </h1>
                        <p style="margin: 2px 0 0; font-size: 8.5pt; color: #334155; font-weight: 600;">
                            Platform Layanan Jasa Kreatif & Marketplace Produk Digital
                        </p>
                        <p style="margin: 1px 0 0; font-size: 7.5pt; color: #64748b;">
                            Website: https://karyaku.id &bull; Email: finance@karyaku.id &bull; Hotline: (021) 8899-7700
                        </p>
                    </td>
                    <td style="width: 220px; vertical-align: middle; text-align: right; padding-bottom: 8px;">
                        <div style="border-left: 2px solid #cbd5e1; padding-left: 10px;">
                            <span style="display: block; font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b;">DOKUMEN KEUANGAN</span>
                            <span style="display: block; font-size: 11pt; font-weight: 800; color: #0f172a;">LAPORAN KEUANGAN</span>
                            <span style="display: block; font-size: 8.5pt; font-weight: 700; color: #0284C7;">Periode: {{ $dateRange['month_name'] }} {{ $dateRange['year'] }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 2. METADATA LAPORAN -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8pt; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px;">
            <tr>
                <td style="padding: 6px 10px; width: 50%; vertical-align: top; border-right: 1px solid #e2e8f0;">
                    <div><span style="font-weight: 700; color: #475569;">Periode Pembukuan:</span> <strong>01 {{ $dateRange['month_name'] }} {{ $dateRange['year'] }} s/d {{ $dateRange['end_date']->format('d') }} {{ $dateRange['month_name'] }} {{ $dateRange['year'] }}</strong></div>
                    <div style="margin-top: 2px;"><span style="font-weight: 700; color: #475569;">Filter Data:</span> {{ request('status') == 'paid' ? 'Hanya Transaksi Lunas (Paid)' : (request('status') == 'unpaid' ? 'Transaksi Belum Bayar (Unpaid)' : (request('status') == 'failed' ? 'Transaksi Gagal / Batal' : 'Semua Transaksi')) }}</div>
                </td>
                <td style="padding: 6px 10px; width: 50%; vertical-align: top; text-align: right;">
                    <div><span style="font-weight: 700; color: #475569;">Tanggal Cetak:</span> {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                    <div style="margin-top: 2px;"><span style="font-weight: 700; color: #475569;">Petugas Cetak:</span> <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong> (Admin Panel)</div>
                </td>
            </tr>
        </table>

        <!-- 3. IKHTISAR / RINGKASAN EKSEKUTIF -->
        <div style="margin-bottom: 14px;">
            <div style="font-size: 8.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a; margin-bottom: 4px; border-bottom: 1.5px solid #0f172a; padding-bottom: 2px;">
                RINGKASAN EKSEKUTIF FINANSIAL
            </div>
            <table class="print-table" style="margin-top: 4px; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="text-align: left; width: 40%;">Indikator Keuangan</th>
                        <th style="text-align: right; width: 25%;">Nilai Moneter (Rp)</th>
                        <th style="text-align: center; width: 15%;">Volume / Rasio</th>
                        <th style="text-align: left; width: 20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1. Total Pemasukan Bruto (Gross Sales)</strong></td>
                        <td style="text-align: right; font-weight: 800; color: #047857;">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $summary['total_orders_paid'] }} Transaksi Lunas</td>
                        <td style="font-size: 7pt; color: #475569;">Total nilai order berstatus lunas</td>
                    </tr>
                    <tr>
                        <td><strong>2. Pendapatan Komisi Platform Karyaku (5%)</strong></td>
                        <td style="text-align: right; font-weight: 800; color: #0284C7;">Rp {{ number_format($summary['total_komisi_platform'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700;">Rate 5.0%</td>
                        <td style="font-size: 7pt; color: #475569;">Net revenue platform Karyaku</td>
                    </tr>
                    <tr>
                        <td><strong>3. Total Pencairan / Penarikan Saldo Disetujui</strong></td>
                        <td style="text-align: right; font-weight: 800; color: #b45309;">Rp {{ number_format($summary['total_penarikan_disetujui'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $summary['count_penarikan_disetujui'] }} Pencairan</td>
                        <td style="font-size: 7pt; color: #475569;">Dana ditransfer ke rekening penjual</td>
                    </tr>
                    <tr style="background-color: #f1f5f9;">
                        <td><strong>4. Arus Kas Bersih (Net Cash Flow)</strong></td>
                        <td style="text-align: right; font-weight: 900; font-size: 8.5pt; {{ $summary['saldo_bersih'] >= 0 ? 'color: #3730a3;' : 'color: #be123c;' }}">
                            Rp {{ number_format($summary['saldo_bersih'], 0, ',', '.') }}
                        </td>
                        <td style="text-align: center; font-weight: 700;">{{ $summary['saldo_bersih'] >= 0 ? 'Surplus' : 'Defisit' }}</td>
                        <td style="font-size: 7pt; color: #475569;">Pemasukan dikurangi Pencairan</td>
                    </tr>
                    <tr>
                        <td><strong>5. Tingkat Keberhasilan & Rata-rata Order</strong></td>
                        <td style="text-align: right; font-weight: 700;">AOV: Rp {{ number_format($summary['rata_rata_transaksi'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $summary['success_rate'] }}% Berhasil</td>
                        <td style="font-size: 7pt; color: #475569;">{{ $summary['total_orders_paid'] }} paid dari {{ $summary['total_orders_count'] }} order</td>
                    </tr>
                    @if($summary['count_penarikan_pending'] > 0 || $summary['total_orders_failed'] > 0)
                    <tr>
                        <td style="color: #64748b;"><em>6. Catatan Pending & Gagal</em></td>
                        <td style="text-align: right; color: #b45309;">WD Pending: Rp {{ number_format($summary['total_penarikan_pending'], 0, ',', '.') }}</td>
                        <td style="text-align: center; color: #64748b;">{{ $summary['count_penarikan_pending'] }} pending</td>
                        <td style="font-size: 7pt; color: #e11d48;">{{ $summary['total_orders_failed'] }} order batal/gagal</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- 4. SECTION 1: TABEL TRANSAKSI PENJUALAN -->
        <div id="printSectionOrders" style="margin-bottom: 18px;">
            <div style="font-size: 8.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a; margin-bottom: 4px; display: flex; justify-content: space-between; border-bottom: 1.5px solid #0f172a; padding-bottom: 2px;">
                <span>I. RINCIAN TRANSAKSI PENJUALAN (INFLOW)</span>
                <span style="font-size: 7.5pt; font-weight: 600; color: #64748b;">Total: {{ $orders->count() }} Data Transaksi</span>
            </div>
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 25px; text-align: center;">No</th>
                        <th style="width: 75px; text-align: left;">Kode Order</th>
                        <th style="width: 90px; text-align: left;">Waktu</th>
                        <th style="width: 105px; text-align: left;">Pembeli</th>
                        <th style="text-align: left;">Item / Jasa</th>
                        <th style="width: 50px; text-align: center;">Bayar</th>
                        <th style="width: 55px; text-align: center;">Status</th>
                        <th style="width: 85px; text-align: right;">Total Nilai</th>
                        <th style="width: 75px; text-align: right;">Komisi 5%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $idx => $order)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700; color: #0B3D62;">#{{ $order->id_order }}</td>
                        <td style="white-space: nowrap; font-size: 7pt;">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-weight: 600;">{{ $order->buyer->name ?? 'User #' . $order->buyer_id }}</td>
                        <td style="font-size: 7pt;">
                            @if($order->items && $order->items->count() > 0)
                                {{ $order->items->map(fn($it) => ($it->product->title ?? 'Item') . ' (' . $it->quantity . 'x)')->implode(', ') }}
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Tanpa rincian item</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700; font-size: 6.5pt;">
                            @if($order->payment_status === 'paid')
                                <span style="color: #047857;">[LUNAS]</span>
                            @elseif($order->payment_status === 'unpaid')
                                <span style="color: #b45309;">[UNPAID]</span>
                            @else
                                <span style="color: #be123c;">[{{ strtoupper($order->payment_status) }}]</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-size: 6.5pt; font-weight: 600;">
                            {{ strtoupper($order->status) }}
                        </td>
                        <td style="text-align: right; font-weight: 700; white-space: nowrap;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 600; color: #0284C7; white-space: nowrap;">
                            Rp {{ number_format($order->total_price * 0.05, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 10px; color: #94a3b8; font-style: italic;">
                            Tidak ada catatan transaksi penjualan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($orders->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align: right; font-weight: 800;">TOTAL TRANSAKSI KESELURUHAN:</td>
                        <td style="text-align: right; font-weight: 800; color: #047857; white-space: nowrap;">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: #0284C7; white-space: nowrap;">Rp {{ number_format($orders->sum('total_price') * 0.05, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- 5. SECTION 2: TABEL PENARIKAN SALDO PENJUAL -->
        <div id="printSectionWithdrawals" style="margin-bottom: 18px;">
            <div style="font-size: 8.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a; margin-bottom: 4px; display: flex; justify-content: space-between; border-bottom: 1.5px solid #0f172a; padding-bottom: 2px;">
                <span>II. DAFTAR PENARIKAN SALDO PENJUAL (OUTFLOW)</span>
                <span style="font-size: 7.5pt; font-weight: 600; color: #64748b;">Total: {{ $withdrawals->count() }} Pengajuan</span>
            </div>
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 25px; text-align: center;">No</th>
                        <th style="width: 75px; text-align: left;">ID Penarikan</th>
                        <th style="width: 90px; text-align: left;">Tgl Pengajuan</th>
                        <th style="width: 110px; text-align: left;">Nama Penjual</th>
                        <th style="text-align: left;">Bank & Rekening</th>
                        <th style="width: 100px; text-align: left;">Atas Nama</th>
                        <th style="width: 90px; text-align: right;">Nominal</th>
                        <th style="width: 85px; text-align: left;">Tgl Proses</th>
                        <th style="width: 55px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $idx => $w)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700; color: #0F766E;">#WD-{{ $w->id_withdrawal }}</td>
                        <td style="white-space: nowrap; font-size: 7pt;">{{ $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-weight: 600;">{{ $w->user->name ?? 'Penjual #' . $w->user_id }}</td>
                        <td>
                            <strong>{{ strtoupper($w->bank_name ?? '-') }}</strong> - {{ $w->bank_account_number }}
                        </td>
                        <td style="font-size: 7pt;">{{ $w->bank_account_name ?? '-' }}</td>
                        <td style="text-align: right; font-weight: 800; white-space: nowrap;">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                        <td style="white-space: nowrap; font-size: 7pt;">{{ $w->processed_at ? $w->processed_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="text-align: center; font-weight: 700; font-size: 6.5pt;">
                            @php $wStat = strtolower($w->status); @endphp
                            @if(in_array($wStat, ['processed', 'approved', 'selesai', 'success']))
                                <span style="color: #047857;">[SELESAI]</span>
                            @elseif($wStat === 'pending')
                                <span style="color: #b45309;">[PENDING]</span>
                            @else
                                <span style="color: #be123c;">[{{ strtoupper($w->status) }}]</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 10px; color: #94a3b8; font-style: italic;">
                            Tidak ada data pengajuan penarikan saldo pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($withdrawals->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right; font-weight: 800;">TOTAL PENARIKAN SALDO:</td>
                        <td style="text-align: right; font-weight: 800; color: #b45309; white-space: nowrap;">Rp {{ number_format($withdrawals->sum('amount'), 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- 6. SECTION 3: TABEL REKAPITULASI ARUS KAS HARIAN -->
        <div id="printSectionDaily" style="margin-bottom: 18px;">
            <div style="font-size: 8.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a; margin-bottom: 4px; display: flex; justify-content: space-between; border-bottom: 1.5px solid #0f172a; padding-bottom: 2px;">
                <span>III. REKAPITULASI ARUS KAS HARIAN</span>
                <span style="font-size: 7.5pt; font-weight: 600; color: #64748b;">Total: {{ count($dailyBreakdown) }} Hari Pembukuan</span>
            </div>
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 25px; text-align: center;">No</th>
                        <th style="width: 80px; text-align: left;">Tanggal</th>
                        <th style="width: 60px; text-align: left;">Hari</th>
                        <th style="width: 55px; text-align: center;">Lunas</th>
                        <th style="width: 95px; text-align: right;">Pemasukan (Inflow)</th>
                        <th style="width: 85px; text-align: right;">Komisi 5%</th>
                        <th style="width: 95px; text-align: right;">Penarikan (Outflow)</th>
                        <th style="width: 100px; text-align: right;">Net Cash Flow</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBreakdown as $idx => $day)
                    <tr style="{{ $day['inflow'] > 0 || $day['outflow'] > 0 ? '' : 'color: #94a3b8;' }}">
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700;">{{ $day['formatted'] }}</td>
                        <td>{{ $day['day_name'] }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $day['order_count'] }}</td>
                        <td style="text-align: right; font-weight: 700; white-space: nowrap;">Rp {{ number_format($day['inflow'], 0, ',', '.') }}</td>
                        <td style="text-align: right; color: #0284C7; white-space: nowrap;">Rp {{ number_format($day['commission'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700; white-space: nowrap;">Rp {{ number_format($day['outflow'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; white-space: nowrap; {{ $day['net'] >= 0 ? 'color: #3730a3;' : 'color: #be123c;' }}">
                            Rp {{ number_format($day['net'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: 800;">TOTAL AKUMULASI BULANAN:</td>
                        <td style="text-align: center; font-weight: 800;">{{ $summary['total_orders_paid'] }}</td>
                        <td style="text-align: right; font-weight: 800; color: #047857; white-space: nowrap;">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: #0284C7; white-space: nowrap;">Rp {{ number_format($summary['total_komisi_platform'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: #b45309; white-space: nowrap;">Rp {{ number_format($summary['total_penarikan_disetujui'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 900; white-space: nowrap; {{ $summary['saldo_bersih'] >= 0 ? 'color: #3730a3;' : 'color: #be123c;' }}">
                            Rp {{ number_format($summary['saldo_bersih'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- 7. LEMBAR PENGESAHAN / TANDA TANGAN -->
        <div style="margin-top: 20px; page-break-inside: avoid; break-inside: avoid;">
            <table style="width: 100%; border-collapse: collapse; border: none;">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top; font-size: 8pt;">
                        <p style="margin: 0 0 3px;">Dibuat dan Diverifikasi Oleh,</p>
                        <p style="font-weight: 700; margin: 0 0 45px;">Administrator Finance</p>
                        <p style="font-weight: 800; text-decoration: underline; margin: 0 0 2px;">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p style="font-size: 7pt; color: #64748b; margin: 0;">Sistem Keuangan Karyaku</p>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top; font-size: 8pt;">
                        <p style="margin: 0 0 3px;">Mengetahui dan Menyetujui,</p>
                        <p style="font-weight: 700; margin: 0 0 45px;">Finance & Operational Manager</p>
                        <p style="font-weight: 800; text-decoration: underline; margin: 0 0 2px;">( .................................................... )</p>
                        <p style="font-size: 7pt; color: #64748b; margin: 0;">Head of Finance Department</p>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 5px; text-align: center; font-size: 6.5pt; color: #94a3b8;">
                Dokumen ini dicetak secara sah dan otomatis melalui Sistem Informasi Karyaku pada {{ now()->translatedFormat('d F Y, H:i:s') }} WIB. Rekam data transaksi tersimpan terenkripsi pada server.
            </div>
        </div>
    </div>

    <!-- SCRIPT CHART & TAB INTERACTIVITY -->
    <script>
        // Sidebar Toggle Scripts
        const sidebar = document.getElementById('sidebar');
        const sidebarOpenBtn = document.getElementById('sidebarOpenBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('closed');
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('hidden');
        }

        if (sidebarOpenBtn) sidebarOpenBtn.addEventListener('click', toggleSidebar);
        if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        // Submenu Accordion
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const menu = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${menu}"]`);
                const chevron = document.querySelector(`[data-chevron="${menu}"]`);
                if (submenu) submenu.classList.toggle('open');
                if (chevron) chevron.classList.toggle('rotated');
            });
        });

        // Tab Switching Logic
        let currentActiveTab = 'orders';

        function switchTab(tabKey) {
            currentActiveTab = tabKey;
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
                el.classList.remove('bg-sky');
                el.classList.remove('text-white');
            });

            const activeTabLabel = document.getElementById('printActiveTabLabel');

            if (tabKey === 'orders') {
                document.getElementById('tabContentOrders').classList.remove('hidden');
                document.getElementById('tabBtnOrders').classList.add('active');
                if (activeTabLabel) activeTabLabel.textContent = 'Transaksi Penjualan';
            } else if (tabKey === 'withdrawals') {
                document.getElementById('tabContentWithdrawals').classList.remove('hidden');
                document.getElementById('tabBtnWithdrawals').classList.add('active');
                if (activeTabLabel) activeTabLabel.textContent = 'Penarikan Saldo';
            } else if (tabKey === 'daily') {
                document.getElementById('tabContentDaily').classList.remove('hidden');
                document.getElementById('tabBtnDaily').classList.add('active');
                if (activeTabLabel) activeTabLabel.textContent = 'Rekapitulasi Harian';
            }
        }

        // Dropdown Print Toggle & Triggers
        function togglePrintDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('printDropdownMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('printDropdownContainer');
            const menu = document.getElementById('printDropdownMenu');
            if (menu && container && !container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        function triggerPrint(scope) {
            const menu = document.getElementById('printDropdownMenu');
            if (menu) menu.classList.add('hidden');

            // Reset scope classes on body
            document.body.classList.remove('print-scope-all', 'print-scope-orders', 'print-scope-withdrawals', 'print-scope-daily');

            if (scope === 'active') {
                scope = currentActiveTab;
            }

            if (scope === 'orders') {
                document.body.classList.add('print-scope-orders');
            } else if (scope === 'withdrawals') {
                document.body.classList.add('print-scope-withdrawals');
            } else if (scope === 'daily') {
                document.body.classList.add('print-scope-daily');
            } else {
                document.body.classList.add('print-scope-all');
            }

            setTimeout(() => {
                window.print();
            }, 50);
        }

        // Live Table Search Filter
        function filterTableRows() {
            const input = document.getElementById('tableSearchInput');
            const filter = input.value.toLowerCase();
            
            // Search in currently visible table
            const activeTable = document.querySelector('.tab-pane:not(.hidden) table tbody');
            if (activeTable) {
                const rows = activeTable.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    const rowText = rows[i].textContent || rows[i].innerText;
                    if (rowText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        // Initialize Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Dual Line/Bar Daily Performance
            const chartLabels = @json($chartData['labels']);
            const chartInflow = @json($chartData['inflow']);
            const chartOutflow = @json($chartData['outflow']);

            const ctxDaily = document.getElementById('dailyFinancialChart').getContext('2d');
            new Chart(ctxDaily, {
                type: 'line',
                data: {
                    labels: chartLabels.map(d => 'Tgl ' + d),
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: chartInflow,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#10B981',
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            borderWidth: 2.5
                        },
                        {
                            label: 'Penarikan (Rp)',
                            data: chartOutflow,
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.08)',
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#F59E0B',
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            borderWidth: 2.5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                font: { size: 10, family: 'Plus Jakarta Sans' },
                                callback: function(val) {
                                    if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                    if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'k';
                                    return 'Rp ' + val;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }, maxTicksLimit: 16 }
                        }
                    }
                }
            });

            // Chart 2: Status Distribution Doughnut Chart
            const ctxStatus = document.getElementById('statusDistributionChart').getContext('2d');
            const totalPaid = {{ $summary['total_orders_paid'] }};
            const totalUnpaid = {{ $summary['total_orders_unpaid'] }};
            const totalFailed = {{ $summary['total_orders_failed'] }};

            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Lunas (Paid)', 'Belum Bayar (Unpaid)', 'Gagal / Batal'],
                    datasets: [{
                        data: [totalPaid, totalUnpaid, totalFailed],
                        backgroundColor: ['#10B981', '#F59E0B', '#F43F5E'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 11, family: 'Plus Jakarta Sans' }, boxWidth: 10 } }
                    }
                }
            });
        });
    </script>
</body>
</html>

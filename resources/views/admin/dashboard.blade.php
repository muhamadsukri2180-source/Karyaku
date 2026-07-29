<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Dashboard Admin</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif']
                    },
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
                        skyDeep: '#0B3D62',
                        skyDeeper: '#082C48',
                        skyPale: '#EFF8FF',
                        coral: '#FF7A59',
                        coralHover: '#F0623F',
                        mint: '#14B8A6',
                        ink: '#0F2A44'
                    }
                }
            }
        }
    </script>
    <style>
        .active-menu {
            background: linear-gradient(90deg, #0EA5E9 0%, #0284C7 100%);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Transisi Mobile Sidebar Drawer */
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }
        @media (max-width: 1023px) {
            #sidebar.closed {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
        }

        /* Submenu accordion */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
        }
        .submenu.open { max-height: 240px; }
        .menu-chevron { transition: transform .25s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-skyPale text-ink font-sans antialiased overflow-x-hidden">

    <div class="flex min-h-screen relative">

        <!-- OVERLAY UNTUK MOBILE SAAT SIDEBAR BUKA -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden transition-opacity"></div>

        <!-- SIDEBAR COMPONENT -->
        <aside id="sidebar" class="w-64 bg-skyDeeper text-white flex flex-col shrink-0 border-r border-skyDeep shadow-xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <!-- Brand Logo -->
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky flex items-center justify-center text-white text-xl font-bold shadow-md">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h1 class="font-display font-extrabold text-lg leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[10px] text-sky-300 font-medium uppercase tracking-wider">Admin Panel</span>
                    </div>
                </div>
                <!-- Tombol Tutup Sidebar (Mobile) -->
                <button id="sidebarCloseBtn" class="lg:hidden text-slate-300 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Profile Widget -->
            <div class="p-4 mx-4 my-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-sky flex items-center justify-center font-bold text-sm text-white border border-white/20 shrink-0">
                    RF
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Rafa Fauzan</p>
                    <p class="text-[10px] text-sky-300 truncate">admin@karyaku.id</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-2 text-xs font-semibold text-slate-300 overflow-y-auto no-scrollbar pb-4">

                <!-- 1. Dashboard -->
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl active-menu text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie text-sm"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Manajemen Pengguna -->
                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                        <i class="fa-solid fa-users text-sm group-hover:text-sky transition-colors"></i>
                        <span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="pengguna">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-user-group text-[11px] w-4"></i> Akun Pembeli/Penjual
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-user-shield text-[11px] w-4"></i> Tim Verifikator
                        </a>
                    </div>
                </div>

                <!-- 3. Katalog & Kategori -->
                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open text-sm group-hover:text-sky transition-colors"></i>
                        <span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-list-check text-[11px] w-4"></i> Daftar Jasa
                            <span class="ml-auto bg-amber-400/20 text-amber-300 text-[9px] px-1.5 py-0.5 rounded-full font-bold">5</span>
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-tags text-[11px] w-4"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <!-- 4. Transaksi & Keuangan -->
                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt text-sm group-hover:text-sky transition-colors"></i>
                        <span>Transaksi & Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="transaksi">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-clock-rotate-left text-[11px] w-4"></i> Riwayat Pesanan
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-wallet text-[11px] w-4"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>

                <!-- 5. Membership -->
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown text-sm group-hover:text-sky transition-colors"></i>
                    <span>Membership</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-coral hover:bg-coral/10 hover:text-coralHover text-xs font-bold transition group">
                    <i class="fa-solid fa-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
                    <span>Keluar Sistem</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 w-full">

            <!-- TOP NAVBAR -->
            <header class="bg-gradient-to-r from-skyDeep via-sky to-skyHover border-b border-sky-900/30 px-4 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md text-white">
                <div class="flex items-center gap-3">
                    <!-- Tombol Toggle Sidebar untuk Mobile -->
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-xl font-extrabold tracking-tight font-display">Dashboard Admin</h2>
                        <p class="text-[11px] sm:text-xs text-sky-100/90 font-medium hidden sm:block">Pantau statistik produk digital, penjualan, moderasi karya, dan aktivitas kreator.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20 shadow-inner relative">
                        <i class="fa-solid fa-bell text-sm"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-coral"></span>
                    </button>
                </div>
            </header>

            <div class="p-4 sm:p-8 space-y-6 sm:space-y-8 overflow-y-auto no-scrollbar">

                <!-- Top Metrics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    <!-- Card 1 -->
                    <div class="bg-sky-50/70 border border-sky-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Penjualan (2026)</span>
                            <div class="w-10 h-10 rounded-xl bg-sky/10 text-sky flex items-center justify-center font-bold">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">2,840</div>
                        <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up"></i> +18% Dari Bulan Lalu
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-emerald-50/70 border border-emerald-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pendapatan Komisi</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-money-bill-wave text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">Rp 14.2M</div>
                        <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-check"></i> Biaya Layanan 10%
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-orange-50/70 border border-orange-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Disetujui</span>
                            <div class="w-10 h-10 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-bold">
                                <i class="fa-solid fa-cubes text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">640 <span class="text-sm font-normal text-slate-400">Karya</span></div>
                        <p class="text-xs text-coral font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-clock-rotate-left"></i> 5 Produk Antrean Moderasi
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-sky-50/70 border border-sky-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kreator Aktif</span>
                            <div class="w-10 h-10 rounded-xl bg-mint/10 text-mint flex items-center justify-center font-bold">
                                <i class="fa-solid fa-user-pen text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">185</div>
                        <p class="text-xs text-mint font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-user-plus"></i> +12 Kreator Baru
                        </p>
                    </div>
                </div>

                <!-- SECTION 1: GRAFIK & REKAPITULASI PRODUK DIGITAL -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Grafik Penjualan & Unduhan -->
                    <div class="lg:col-span-7 bg-sky-50/40 border border-sky-200/70 p-5 sm:p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-base">Grafik Transaksi & Unduhan Produk</h3>
                                    <p class="text-xs text-slate-500">Statistik transaksi marketplace pada tahun <span class="font-bold text-sky">2026</span></p>
                                </div>
                                <span class="text-xs bg-sky-100 text-skyDeep font-bold px-3 py-1 rounded-full border border-sky-200">
                                    Tahun 2026
                                </span>
                            </div>

                            <div class="h-60 sm:h-64 w-full">
                                <canvas id="yearlyChart"></canvas>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 pt-4 border-t border-sky-200/60 mt-4 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded bg-sky inline-block shadow-sm"></span>
                                <span class="font-bold text-slate-700">Jumlah Penjualan Produk</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-0.5 border-b-2 border-dashed border-emerald-500 inline-block"></span>
                                <span class="font-bold text-slate-700">Tingkat Kepuasan Pembeli (%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rekapitulasi Kategori Terpopuler -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-sky-50/80 to-slate-50 border border-sky-200/80 p-5 sm:p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-5 pb-3 border-b border-sky-200/70">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-slate-900 text-base">Kategori Populer</h3>
                                        <span class="bg-sky/10 text-sky text-[10px] font-black px-2 py-0.5 rounded-full uppercase">Karyaku</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">Penjualan berdasarkan jenis produk digital</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-sky/10 text-sky flex items-center justify-center font-bold text-lg">
                                    <i class="fa-solid fa-shapes"></i>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <!-- item 1 -->
                                <div class="p-3.5 rounded-xl bg-white border border-sky-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-file-image"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">Poster & Template Canva</h4>
                                            <p class="text-[10px] text-slate-400">1,120 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-sky bg-sky-50 px-2.5 py-1 rounded-lg">40%</span>
                                </div>

                                <!-- item 2 -->
                                <div class="p-3.5 rounded-xl bg-white border border-sky-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-cube"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">Desain 3D Blender</h4>
                                            <p class="text-[10px] text-slate-400">850 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-sky bg-sky-50 px-2.5 py-1 rounded-lg">30%</span>
                                </div>

                                <!-- item 3 -->
                                <div class="p-3.5 rounded-xl bg-white border border-sky-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-vector-square"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">UI/UX Kit & Vektor</h4>
                                            <p class="text-[10px] text-slate-400">870 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-sky bg-sky-50 px-2.5 py-1 rounded-lg">30%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-3.5 bg-sky-100/60 rounded-xl border border-sky-200/70 flex items-start gap-2 text-xs text-slate-600">
                            <i class="fa-solid fa-circle-info text-sky text-sm mt-0.5 shrink-0"></i>
                            <p class="text-[11px] leading-relaxed">
                                <strong class="text-sky">Informasi:</strong> Data kategori diperbarui secara otomatis setiap ada transaksi baru di platform Karyaku.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: AKSES CEPAT & LOG MODERASI -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Akses Cepat Admin -->
                    <div class="lg:col-span-7 bg-sky-50/40 border border-sky-200/70 p-5 sm:p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                <i class="fa-solid fa-bolt text-amber-500"></i> Aksi Cepat Admin
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <a href="#" class="bg-white p-4 rounded-xl border border-sky-100 hover:border-sky/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Verifikasi Produk</h4>
                                <p class="text-[10px] text-slate-500">Tinjau karya digital baru dari kreator</p>
                            </a>

                            <a href="#" class="bg-white p-4 rounded-xl border border-sky-100 hover:border-sky/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Persetujuan Payout</h4>
                                <p class="text-[10px] text-slate-500">Proses klaim pencairan dana kreator</p>
                            </a>

                            <a href="#" class="bg-white p-4 rounded-xl border border-sky-100 hover:border-sky/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Atur Promo & Diskon</h4>
                                <p class="text-[10px] text-slate-500">Buat voucher promo event marketplace</p>
                            </a>
                        </div>
                    </div>

                    <!-- Log Aktivitas Marketplace -->
                    <div class="lg:col-span-5 bg-sky-50/40 border border-sky-200/70 p-5 sm:p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Aktivitas Terakhir</h3>
                            <a href="#" class="text-xs font-bold text-sky hover:underline">Lihat Semua</a>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-xl border border-sky-100 shadow-sm flex items-start gap-3">
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] font-black px-2 py-1 rounded shrink-0 uppercase">KARYA</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Poster Canva "Banner UMKM" telah diverifikasi</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">5 Menit yang lalu</p>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-sky-100 shadow-sm flex items-start gap-3">
                                <span class="bg-sky-100 text-sky text-[9px] font-black px-2 py-1 rounded shrink-0 uppercase">PAYOUT</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Penarikan Rp 500.000 disetujui untuk Kreator #82</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">35 Menit yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- SCRIPT CHART.JS & SIDEBAR MOBILE DRAWER -->
    <script>
        // Logika Toggle Sidebar untuk Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }

        sidebarToggleBtn.addEventListener('click', toggleSidebar);
        sidebarCloseBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Logika Accordion Submenu Sidebar
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                submenu.classList.toggle('open');
                chevron.classList.toggle('rotated');
            });
        });

        // Chart Init
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('yearlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'Mei 26', 'Jun 26', 'Jul 26'],
                    datasets: [
                        {
                            label: 'Jumlah Penjualan Produk',
                            data: [210, 280, 320, 410, 390, 450, 480],
                            borderColor: '#0EA5E9',
                            backgroundColor: 'rgba(14, 165, 233, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#0EA5E9',
                            pointRadius: 4,
                        },
                        {
                            label: 'Tingkat Kepuasan (%)',
                            data: [94, 95, 96, 95, 97, 98, 99],
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(226, 232, 240, 0.8)' }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</body>
</html>

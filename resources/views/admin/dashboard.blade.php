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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#6366F1', // Indigo untuk nuansa platform kreatif
                            dark: '#4338CA',
                            light: '#818CF8',
                            soft: '#EEF2FF',
                            accent: '#06B6D4',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .active-menu {
            background: linear-gradient(90deg, #6366F1 0%, #4F46E5 100%);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
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
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-x-hidden">

    <div class="flex min-h-screen relative">

        <!-- OVERLAY UNTUK MOBILE SAAT SIDEBAR BUKA -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden transition-opacity"></div>

        <!-- SIDEBAR COMPONENT -->
        <aside id="sidebar" class="w-64 bg-slate-900 text-white flex flex-col shrink-0 border-r border-slate-800 shadow-xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <!-- Brand Logo -->
            <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center text-white text-xl font-bold shadow-md">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-lg leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[10px] text-indigo-400 font-medium uppercase tracking-wider">Admin Panel</span>
                    </div>
                </div>
                <!-- Tombol Tutup Sidebar (Mobile) -->
                <button id="sidebarCloseBtn" class="lg:hidden text-slate-400 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Profile Widget -->
            <div class="p-4 mx-4 my-4 rounded-xl bg-slate-800/60 border border-slate-700/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-brand-light flex items-center justify-center font-bold text-sm text-white border border-white/20 shrink-0">
                    SV
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Rafa Fauzan</p>
                    <p class="text-[10px] text-indigo-300 truncate">Admin@karyaku.id</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-6 text-xs font-semibold text-slate-400 overflow-y-auto no-scrollbar">
                <div>
                    <p class="px-3 mb-2 text-[10px] uppercase font-bold tracking-widest text-slate-500">Menu Utama</p>
                    <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl active-menu text-white transition-all duration-200">
                        <i class="fa-solid fa-chart-pie text-sm"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[10px] uppercase font-bold tracking-widest text-slate-500">Manajemen Produk Digital</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-box-open text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Semua Produk Digital</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-square-check text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Moderasi & Verifikasi</span>
                            <span class="ml-auto bg-amber-500/20 text-amber-300 text-[10px] px-2 py-0.5 rounded-full font-bold">5</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-layer-group text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Kategori & Tag</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[10px] uppercase font-bold tracking-widest text-slate-500">Pengguna & Transaksi</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-users text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Daftar Kreator & Pembeli</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-receipt text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                            <i class="fa-solid fa-wallet text-sm group-hover:text-brand-accent transition-colors"></i>
                            <span>Penarikan Dana (Payout)</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[10px] uppercase font-bold tracking-widest text-slate-500">Pengaturan</p>
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                        <i class="fa-solid fa-sliders text-sm group-hover:text-brand-accent transition-colors"></i>
                        <span>Konfigurasi Marketplace</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 text-xs font-bold transition group">
                    <i class="fa-solid fa-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
                    <span>Keluar Sistem</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 w-full">

            <!-- TOP NAVBAR -->
            <header class="bg-gradient-to-r from-brand-dark via-brand to-brand-light border-b border-indigo-900/30 px-4 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md text-white">
                <div class="flex items-center gap-3">
                    <!-- Tombol Toggle Sidebar untuk Mobile -->
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-xl font-extrabold tracking-tight">Dashboard Admin</h2>
                        <p class="text-[11px] sm:text-xs text-indigo-100/90 font-medium hidden sm:block">Pantau statistik produk digital, penjualan, moderasi karya, dan aktivitas kreator.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20 shadow-inner relative">
                        <i class="fa-solid fa-bell text-sm"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-400"></span>
                    </button>
                </div>
            </header>

            <div class="p-4 sm:p-8 space-y-6 sm:space-y-8 overflow-y-auto no-scrollbar">

                <!-- Top Metrics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    <!-- Card 1 -->
                    <div class="bg-indigo-50/70 border border-indigo-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Penjualan (2026)</span>
                            <div class="w-10 h-10 rounded-xl bg-indigo-600/10 text-brand flex items-center justify-center font-bold">
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
                    <div class="bg-amber-50/70 border border-amber-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Disetujui</span>
                            <div class="w-10 h-10 rounded-xl bg-amber-600/10 text-amber-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-cubes text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">640 <span class="text-sm font-normal text-slate-400">Karya</span></div>
                        <p class="text-xs text-amber-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-clock-rotate-left"></i> 5 Produk Antrean Moderasi
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-sky-50/70 border border-sky-200/80 p-5 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kreator Aktif</span>
                            <div class="w-10 h-10 rounded-xl bg-sky-600/10 text-sky-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-user-pen text-lg"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900">185</div>
                        <p class="text-xs text-sky-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-user-plus"></i> +12 Kreator Baru
                        </p>
                    </div>
                </div>

                <!-- SECTION 1: GRAFIK & REKAPITULASI PRODUK DIGITAL -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Grafik Penjualan & Unduhan -->
                    <div class="lg:col-span-7 bg-indigo-50/40 border border-indigo-200/70 p-5 sm:p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-base">Grafik Transaksi & Unduhan Produk</h3>
                                    <p class="text-xs text-slate-500">Statistik transaksi marketplace pada tahun <span class="font-bold text-brand">2026</span></p>
                                </div>
                                <span class="text-xs bg-indigo-100 text-brand font-bold px-3 py-1 rounded-full border border-indigo-200">
                                    Tahun 2026
                                </span>
                            </div>

                            <div class="h-60 sm:h-64 w-full">
                                <canvas id="yearlyChart"></canvas>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 pt-4 border-t border-indigo-200/60 mt-4 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded bg-brand inline-block shadow-sm"></span>
                                <span class="font-bold text-slate-700">Jumlah Penjualan Produk</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-0.5 border-b-2 border-dashed border-emerald-500 inline-block"></span>
                                <span class="font-bold text-slate-700">Tingkat Kepuasan Pembeli (%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rekapitulasi Kategori Terpopuler -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-indigo-50/80 to-slate-50 border border-indigo-200/80 p-5 sm:p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-5 pb-3 border-b border-indigo-200/70">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-slate-900 text-base">Kategori Populer</h3>
                                        <span class="bg-brand/10 text-brand text-[10px] font-black px-2 py-0.5 rounded-full uppercase">Karyaku</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">Penjualan berdasarkan jenis produk digital</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center font-bold text-lg">
                                    <i class="fa-solid fa-shapes"></i>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <!-- item 1 -->
                                <div class="p-3.5 rounded-xl bg-white border border-indigo-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-file-image"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">Poster & Template Canva</h4>
                                            <p class="text-[10px] text-slate-400">1,120 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">40%</span>
                                </div>

                                <!-- item 2 -->
                                <div class="p-3.5 rounded-xl bg-white border border-indigo-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-cube"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">Desain 3D Blender</h4>
                                            <p class="text-[10px] text-slate-400">850 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">30%</span>
                                </div>

                                <!-- item 3 -->
                                <div class="p-3.5 rounded-xl bg-white border border-indigo-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                            <i class="fa-solid fa-vector-square"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800">UI/UX Kit & Vektor</h4>
                                            <p class="text-[10px] text-slate-400">870 Terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">30%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-3.5 bg-indigo-100/60 rounded-xl border border-indigo-200/70 flex items-start gap-2 text-xs text-slate-600">
                            <i class="fa-solid fa-circle-info text-brand text-sm mt-0.5 shrink-0"></i>
                            <p class="text-[11px] leading-relaxed">
                                <strong class="text-brand">Informasi:</strong> Data kategori diperbarui secara otomatis setiap ada transaksi baru di platform Karyaku.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: AKSES CEPAT & LOG MODERASI -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Akses Cepat Admin -->
                    <div class="lg:col-span-7 bg-indigo-50/40 border border-indigo-200/70 p-5 sm:p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                <i class="fa-solid fa-bolt text-amber-500"></i> Aksi Cepat Admin
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <a href="#" class="bg-white p-4 rounded-xl border border-indigo-100 hover:border-brand/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Verifikasi Produk</h4>
                                <p class="text-[10px] text-slate-500">Tinjau karya digital baru dari kreator</p>
                            </a>

                            <a href="#" class="bg-white p-4 rounded-xl border border-indigo-100 hover:border-brand/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Persetujuan Payout</h4>
                                <p class="text-[10px] text-slate-500">Proses klaim pencadangan dana kreator</p>
                            </a>

                            <a href="#" class="bg-white p-4 rounded-xl border border-indigo-100 hover:border-brand/40 shadow-sm transition group">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-brand flex items-center justify-center font-bold text-lg mb-3">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 text-xs mb-1">Atur Promo & Diskon</h4>
                                <p class="text-[10px] text-slate-500">Buat voucher promo event marketplace</p>
                            </a>
                        </div>
                    </div>

                    <!-- Log Aktivitas Marketplace -->
                    <div class="lg:col-span-5 bg-indigo-50/40 border border-indigo-200/70 p-5 sm:p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Aktivitas Terakhir</h3>
                            <a href="#" class="text-xs font-bold text-brand hover:underline">Lihat Semua</a>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 shadow-sm flex items-start gap-3">
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] font-black px-2 py-1 rounded shrink-0 uppercase">KARYA</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Poster Canva "Banner UMKM" telah diverifikasi</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">5 Menit yang lalu</p>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 shadow-sm flex items-start gap-3">
                                <span class="bg-indigo-100 text-brand text-[9px] font-black px-2 py-1 rounded shrink-0 uppercase">PAYOUT</span>
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
                            borderColor: '#6366F1',
                            backgroundColor: 'rgba(99, 102, 241, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#6366F1',
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

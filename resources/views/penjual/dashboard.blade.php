<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Dashboard Penjual</title>
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

        /* Animation Drawer Mobile Sidebar */
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

        /* Submenu Accordion */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .submenu.open { max-height: 240px; }
        .menu-chevron { transition: transform 0.25s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-skyPale text-ink font-sans antialiased overflow-x-hidden">

    <div class="flex min-h-screen relative">

        <!-- OVERLAY DESIGNS FOR MOBILE SIDEBAR -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden transition-opacity"></div>

        <!-- SIDEBAR COMPONENT (ROLE PENJUAL) -->
        <aside id="sidebar" class="w-64 bg-skyDeeper text-white flex flex-col shrink-0 border-r border-skyDeep shadow-xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <!-- Brand Logo Header -->
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky flex items-center justify-center text-white text-xl font-bold shadow-md">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h1 class="font-display font-extrabold text-lg leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[10px] text-sky-300 font-medium uppercase tracking-wider">Seller Center</span>
                    </div>
                </div>
                <!-- Button Close Sidebar (Mobile View) -->
                <button id="sidebarCloseBtn" class="lg:hidden text-slate-300 hover:text-white p-2 focus:outline-none">
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
                    <p class="text-[10px] text-sky-300 truncate">Kreator Digital</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-2 text-xs font-semibold text-slate-300 overflow-y-auto no-scrollbar pb-4">

                <!-- 1. Dashboard -->
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl active-menu text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie text-sm w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Kelola Produk -->
                <div>
                    <button type="button" data-menu="produk" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open text-sm w-4 text-center group-hover:text-sky transition-colors"></i>
                        <span>Produk Saya</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="produk"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="produk">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-list text-[10px] w-4 text-center"></i> Daftar Produk
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-plus text-[10px] w-4 text-center"></i> Tambah Karya
                        </a>
                    </div>
                </div>

                <!-- 3. Pesanan & Penjualan -->
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                    <i class="fa-solid fa-cart-shopping text-sm w-4 text-center group-hover:text-sky transition-colors"></i>
                    <span>Riwayat Pesanan</span>
                    <span class="ml-auto bg-amber-400/20 text-amber-300 text-[9px] px-2 py-0.5 rounded-full font-bold">3 Baru</span>
                </a>

                <!-- 4. Keuangan & Pendapatan -->
                <div>
                    <button type="button" data-menu="keuangan" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                        <i class="fa-solid fa-wallet text-sm w-4 text-center group-hover:text-sky transition-colors"></i>
                        <span>Saldo & Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="keuangan"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="keuangan">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-money-bill-wave text-[10px] w-4 text-center"></i> Pendapatan
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/5 hover:text-white transition-all text-[11px]">
                            <i class="fa-solid fa-hand-holding-dollar text-[10px] w-4 text-center"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>

                <!-- 5. Pengaturan Toko -->
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                    <i class="fa-solid fa-sliders text-sm w-4 text-center group-hover:text-sky transition-colors"></i>
                    <span>Pengaturan Toko</span>
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
                    <!-- Hamburger Toggle Button for Mobile/Tablet -->
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20 focus:outline-none">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-xl font-extrabold tracking-tight font-display">Dashboard Penjual</h2>
                        <p class="text-[11px] sm:text-xs text-sky-100/90 font-medium hidden sm:block">Ringkasan statistik karya digital, transaksi, dan performa toko Anda.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/20 shadow-inner relative">
                        <i class="fa-solid fa-bell text-sm"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-coral"></span>
                    </button>
                </div>
            </header>

            <!-- MAIN CONTAINER CONTENT -->
            <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 overflow-y-auto no-scrollbar">

                <!-- 1. TOP METRICS CARDS (RESPONSIVE GRID) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    <!-- Metric Card 1 -->
                    <div class="bg-sky-50/70 border border-sky-200/80 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Terjual</span>
                            <div class="w-10 h-10 rounded-xl bg-sky/10 text-sky flex items-center justify-center font-bold">
                                <i class="fa-solid fa-bag-shopping text-lg"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">142</div>
                        <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up"></i> +12 Bulan Ini
                        </p>
                    </div>

                    <!-- Metric Card 2 -->
                    <div class="bg-emerald-50/70 border border-emerald-200/80 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendapatan</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-wallet text-lg"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">Rp 4.850K</div>
                        <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check"></i> Siap Ditarik: Rp 1.200.000
                        </p>
                    </div>

                    <!-- Metric Card 3 -->
                    <div class="bg-orange-50/70 border border-orange-200/80 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Karya</span>
                            <div class="w-10 h-10 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-bold">
                                <i class="fa-solid fa-cubes text-lg"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">18 <span class="text-sm font-normal text-slate-400">Item</span></div>
                        <p class="text-xs text-coral font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i> 1 Dalam Moderasi
                        </p>
                    </div>

                    <!-- Metric Card 4 -->
                    <div class="bg-sky-50/70 border border-sky-200/80 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rating Toko</span>
                            <div class="w-10 h-10 rounded-xl bg-amber-400/10 text-amber-500 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-star text-lg"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">4.9 <span class="text-sm font-normal text-slate-400">/ 5.0</span></div>
                        <p class="text-xs text-sky font-bold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-thumbs-up"></i> Dari 85 Ulasan
                        </p>
                    </div>
                </div>

                <!-- 2. ANALYTICS CHART SECTION -->
                <div class="bg-white/80 border border-sky-200/70 p-4 sm:p-6 rounded-2xl shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Grafik Penjualan Saya</h3>
                            <p class="text-xs text-slate-500">Statistik karya terunduh dan penjualan tahun <span class="font-bold text-sky">2026</span></p>
                        </div>
                        <span class="text-xs bg-sky-50 text-sky font-bold px-3 py-1.5 rounded-full border border-sky-200 self-start sm:self-auto">
                            Tahun 2026
                        </span>
                    </div>

                    <!-- Chart Container Responsif -->
                    <div class="h-60 sm:h-72 w-full">
                        <canvas id="sellerChart"></canvas>
                    </div>
                </div>

                <!-- 3. TABEL TRANSAKSI TERAKHIR (RESPONSIVE TABLE CONTAINER) -->
                <div class="bg-white/80 border border-sky-200/70 p-4 sm:p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-slate-900 text-base">Transaksi Terbaru</h3>
                        <a href="#" class="text-xs text-sky hover:text-skyHover font-bold">Lihat Semua</a>
                    </div>
                    
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left text-xs text-slate-600 min-w-[600px]">
                            <thead class="bg-skyPale text-slate-700 font-bold uppercase text-[10px] border-b border-sky-200">
                                <tr>
                                    <th class="py-3 px-4">Nama Karya</th>
                                    <th class="py-3 px-4">Pembeli</th>
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Harga</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-sky-50/50 transition">
                                    <td class="py-3 px-4 font-bold text-slate-800">UI Kit E-Commerce App</td>
                                    <td class="py-3 px-4">Andi Wijaya</td>
                                    <td class="py-3 px-4">28 Jul 2026</td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">Rp 150.000</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-sky-50/50 transition">
                                    <td class="py-3 px-4 font-bold text-slate-800">Template POS Laravel</td>
                                    <td class="py-3 px-4">Budi Santoso</td>
                                    <td class="py-3 px-4">27 Jul 2026</td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">Rp 250.000</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-sky-50/50 transition">
                                    <td class="py-3 px-4 font-bold text-slate-800">Source Code SiParkir</td>
                                    <td class="py-3 px-4">Citra Lestari</td>
                                    <td class="py-3 px-4">25 Jul 2026</td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">Rp 180.000</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-amber-100 text-amber-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Pending</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- SCRIPT CHART.JS & SIDEBAR MOBILE DRAWER TOGGLE -->
    <script>
        // Logika Responsive Mobile Sidebar Toggle
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

        // Accordion Submenu Navigation
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                submenu.classList.toggle('open');
                chevron.classList.toggle('rotated');
            });
        });

        // Inisialisasi Chart.js
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('sellerChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'Mei 26', 'Jun 26', 'Jul 26'],
                    datasets: [
                        {
                            label: 'Jumlah Penjual (Transaksi)',
                            data: [12, 18, 15, 25, 22, 30, 35],
                            borderColor: '#0EA5E9',
                            backgroundColor: 'rgba(14, 165, 233, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#0EA5E9',
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            grid: { color: 'rgba(226, 232, 240, 0.8)' }, 
                            ticks: { font: { size: 10 } } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { font: { size: 10 } } 
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

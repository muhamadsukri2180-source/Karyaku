<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Google Fonts & Lucide Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-slate-100 gap-2">
                <div class="bg-sky-500 text-white p-2 rounded-lg">
                    <i data-lucide="store" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-lg text-slate-800">SellerCenter</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-sky-50 text-sky-600 font-medium transition-colors">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    Produk Saya
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    Pesanan
                    <span class="ml-auto bg-sky-100 text-sky-700 text-xs px-2 py-0.5 rounded-full font-semibold">5</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    Laporan Penjualan
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Pengaturan
                </a>
            </nav>
        </div>

        <!-- User Info / Logout -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-50">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-slate-800 truncate">Toko Maju Jaya</p>
                    <p class="text-xs text-slate-500 truncate">penjual@domain.com</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- TOPBAR / HEADER -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-500 hover:text-slate-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">Overview</h1>
            </div>

            <!-- Search & Actions -->
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block w-64">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari pesanan atau produk..." class="w-full pl-9 pr-4 py-1.5 text-sm rounded-lg border border-slate-200 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                </div>
                <button class="relative p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                </button>
            </div>
        </header>

        <!-- DASHBOARD BODY (Scrollable) -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">
            
            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1 -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500">Total Pendapatan</span>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 mt-2">Rp 14.850.000</p>
                    <span class="text-xs text-emerald-600 font-medium inline-flex items-center gap-1 mt-1">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +12% dari bulan lalu
                    </span>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500">Pesanan Selesai</span>
                        <div class="p-2 bg-sky-50 text-sky-600 rounded-lg">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 mt-2">157</p>
                    <span class="text-xs text-sky-600 font-medium inline-flex items-center gap-1 mt-1">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +8% dari bulan lalu
                    </span>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500">Perlu Dikirim</span>
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 mt-2">5 Pesanan</p>
                    <span class="text-xs text-amber-600 font-medium mt-1 inline-block">Proses sebelum 17:00</span>
                </div>

                <!-- Card 4 -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500">Rating Toko</span>
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <i data-lucide="star" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 mt-2">4.8 / 5.0</p>
                    <span class="text-xs text-slate-500 mt-1 inline-block">Dari 320 ulasan</span>
                </div>
            </div>

            <!-- GRAFIK PENJUALAN -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Tren Transaksi Penjual</h2>
                        <p class="text-xs text-slate-500">Grafik performa transaksi dalam 7 bulan terakhir</p>
                    </div>
                    <select class="text-xs border border-slate-200 rounded-lg px-2.5 py-1.5 bg-slate-50 text-slate-600 focus:outline-none">
                        <option>Tahun 2026</option>
                        <option>Tahun 2025</option>
                    </select>
                </div>
                
                <!-- Chart Container -->
                <div class="relative h-72 w-full">
                    <canvas id="sellerChart"></canvas>
                </div>
            </div>

            <!-- TABEL PESANAN TERBARU -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-800">Pesanan Terbaru</h2>
                    <a href="#" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="p-4 pl-6">ID Pesanan</th>
                                <th class="p-4">Pembeli</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Total</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 font-semibold text-sky-600">#ORD-9021</td>
                                <td class="p-4">Budi Santoso</td>
                                <td class="p-4 text-xs text-slate-500">10 Aug 2026</td>
                                <td class="p-4 font-medium">Rp 250.000</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        Perlu Dikirim
                                    </span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button class="text-sky-600 hover:text-sky-800 font-medium text-xs">Detail</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 font-semibold text-sky-600">#ORD-9020</td>
                                <td class="p-4">Siti Nurhaliza</td>
                                <td class="p-4 text-xs text-slate-500">09 Aug 2026</td>
                                <td class="p-4 font-medium">Rp 1.200.000</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Selesai
                                    </span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button class="text-sky-600 hover:text-sky-800 font-medium text-xs">Detail</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 font-semibold text-sky-600">#ORD-9019</td>
                                <td class="p-4">Andi Wijaya</td>
                                <td class="p-4 text-xs text-slate-500">09 Aug 2026</td>
                                <td class="p-4 font-medium">Rp 450.000</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        Dikirim
                                    </span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button class="text-sky-600 hover:text-sky-800 font-medium text-xs">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- SCRIPT INITIALIZATION -->
    <script>
        // Inisialisasi Icon Lucide
        lucide.createIcons();

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

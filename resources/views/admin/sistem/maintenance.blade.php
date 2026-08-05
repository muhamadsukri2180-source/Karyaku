<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Maintenance & Backup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] }, colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', coral: '#FF7A59' } } } }
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
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: scale(1.015) translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-sky/20 selection:text-skyDeep min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

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

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pt-4 pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2">Menu Utama</p>
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>
                
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <!-- Menu Maintenance Aktif -->
                <a href="#" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-server w-4 text-center text-white transition-colors"></i><span>Maintenance & Backup</span></div>
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-gradient-to-r from-white via-sky-50/50 to-blue-50/50 backdrop-blur-xl border-b border-sky-200 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Maintenance & Backup</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola status server, mode perbaikan, dan cadangan data (CRUD).</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">
                
                <!-- Status Server Card -->
                <div class="bg-gradient-to-br from-indigo-50 via-white to-blue-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center border-4 border-white shadow-sm">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">Sistem Berjalan Normal (Online)</h3>
                            <p class="text-[11px] text-slate-600 mt-0.5">Server aktif dan dapat diakses oleh semua pengguna.</p>
                        </div>
                    </div>
                    <button class="px-5 py-2.5 bg-red-100 text-red-700 hover:bg-red-600 hover:text-white border border-red-300 text-xs font-bold rounded-xl transition-all shadow-sm">
                        <i class="fa-solid fa-power-off"></i> Aktifkan Mode Maintenance
                    </button>
                </div>

                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg font-display">Riwayat Backup Database</h3>
                        </div>
                        
                        <!-- Tombol Tambah/Buat Backup Baru -->
                        <button onclick="alert('Memulai proses Backup Database...')" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-500/30 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                            <i class="fa-solid fa-database"></i> Buat Backup Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Nama File</th>
                                    <th class="py-4 px-6">Tanggal & Waktu</th>
                                    <th class="py-4 px-6">Ukuran</th>
                                    <th class="py-4 px-6">Tipe</th>
                                    <th class="py-4 px-6 text-center">Aksi (Download/Hapus)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                
                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-sm border border-sky-200"><i class="fa-solid fa-file-zipper"></i></div>
                                            <p class="font-bold text-slate-800 text-xs">backup-2026-08-04.zip</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6"><p class="text-xs font-semibold text-slate-700">04 Ags 2026 - 02:00 WIB</p></td>
                                    <td class="py-3 px-6 text-xs font-bold text-slate-600">45.2 MB</td>
                                    <td class="py-3 px-6"><span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Otomatis</span></td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="alert('Download Backup')" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Download"><i class="fa-solid fa-download"></i></button>
                                            <button onclick="alert('Hapus Backup')" class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
        sidebarToggleBtn.addEventListener('click', toggleSidebar); sidebarCloseBtn.addEventListener('click', toggleSidebar); sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Akun Verifikator & Verifikasi Identitas</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN (Diperbarui agar ikon muncul stabil) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
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

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">RF</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">Rafa Fauzan</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] text-sky-100 truncate">Online</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center text-white transition-colors"></i>
                        <span class="text-white">Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="pengguna">
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
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
                        <a href="#" class="flex items-center justify-between px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa</div>
                            <span class="bg-amber-400 text-slate-900 text-[9px] px-1.5 py-0.5 rounded font-extrabold">5 Baru</span>
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
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
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="#" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>
                <a href="#" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center group-hover:text-amber-300 transition-colors"></i><span>Paket Membership</span>
                </a>
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="#" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-server w-4 text-center group-hover:text-white transition-colors"></i><span>Maintenance & Backup</span></div>
                </a>
            </nav>
            <div class="p-4 border-t border-white/15">
                <a href="#" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                    <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-gradient-to-r from-white via-sky-50/50 to-blue-50/50 backdrop-blur-xl border-b border-sky-200 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Akun Verifikator & Verifikasi Identitas</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola staf verifikator tugas KTP dan pantau progres validasi (CRUD).</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">
                
                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-gradient-to-br from-indigo-50 via-white to-blue-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Verifikator</span><div class="text-3xl font-black text-slate-900 mt-1">8 Staf</div></div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/30"><i class="fa-solid fa-user-shield text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-indigo-200/50 pt-2 mt-2">Aktif bertugas memeriksa KTP</p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Antrean Masuk</span><div class="text-3xl font-black text-slate-900 mt-1">12 Berkas</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-clock-rotate-left text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Menunggu validasi tim</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Selesai Diverifikasi</span><div class="text-3xl font-black text-slate-900 mt-1">24 Hari Ini</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-badge-check text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Akurat dan cepat</p>
                    </div>

                    <div class="bg-gradient-to-br from-sky-50 via-white to-blue-100/60 border-l-4 border-sky-500 border-y border-r border-sky-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider">Akurasi Sistem</span><div class="text-3xl font-black text-slate-900 mt-1">99.4%</div></div>
                            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/30"><i class="fa-solid fa-chart-line text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-sky-200/50 pt-2 mt-2">Kinerja verifikator</p>
                    </div>
                </div>

                <!-- MAIN TABLE AREA (CRUD with Add button at top right) -->
                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <div class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" placeholder="Cari nama verifikator..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </div>
                        <!-- Tombol Tambah di Kanan Atas -->
                        <button onclick="alert('Buka Modal Tambah Verifikator')" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-500/30 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
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
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                
                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">RD</div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs">Rian Darmawan</p>
                                                <p class="text-[10px] text-slate-500 font-medium">Verifikator Utama</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-700">rian.verif@karyaku.com</td>
                                    <td class="py-3 px-6 text-xs font-bold text-sky-700">340 Berkas</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Bertugas
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="alert('Edit Verifikator')" class="px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Edit"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                            <button onclick="alert('Hapus Verifikator')" class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Hapus"><i class="fa-solid fa-trash"></i> Hapus</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">DP</div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs">Dinda Permata</p>
                                                <p class="text-[10px] text-slate-500 font-medium">Verifikator Senior</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-700">dinda.verif@karyaku.com</td>
                                    <td class="py-3 px-6 text-xs font-bold text-sky-700">285 Berkas</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Bertugas
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="alert('Edit Verifikator')" class="px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Edit"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                            <button onclick="alert('Hapus Verifikator')" class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm" title="Hapus"><i class="fa-solid fa-trash"></i> Hapus</button>
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

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                submenu.classList.toggle('open');
                chevron.classList.toggle('rotated');
            });
        });
    </script>
</body>
</html>
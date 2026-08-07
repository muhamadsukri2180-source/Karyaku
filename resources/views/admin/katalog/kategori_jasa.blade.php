<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Kategori Jasa</title>
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
        
        /* CUSTOM DROPDOWN ANIMATION CSS */
        .custom-dropdown { position: relative; width: 100%; }
        .dropdown-toggle:checked ~ .trigger i { transform: rotate(180deg); }
        .dropdown-toggle:checked ~ .trigger { border-color: #0ea5e9; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }
        .dropdown-toggle:checked ~ .list { max-height: 150px; opacity: 1; padding: 0.5rem 0; border-width: 1px; visibility: visible; }
        .dropdown-toggle:not(:checked) ~ .list { max-height: 0; opacity: 0; border-width: 0; padding: 0; visibility: hidden; }
        .list { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
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
                        <i class="fa-solid fa-box-open w-4 text-center text-white transition-colors"></i><span class="text-white">Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron rotated" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1 open" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa</div>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg active-menu transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-white w-3 text-center"></i> Kategori Jasa
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

                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
                </a>

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
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Kategori Jasa</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola struktur kategori untuk mengelompokkan layanan/karya kreator.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                @if (session('success'))
                    <script>Swal.fire({icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false});</script>
                @endif
                @if (session('error'))
                    <script>Swal.fire({icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444'});</script>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Kategori</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $totalKategori ?? 0 }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-tags text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Kategori Terpopuler</span><div class="text-xl font-black text-slate-900 mt-2">{{ $kategoriPopuler->name ?? '-' }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-star text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Kategori Nonaktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $kategoriNonaktif ?? 0 }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-eye-slash text-lg"></i></div>
                        </div>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="categorySearch" onkeyup="filterCategories()" placeholder="Cari nama kategori..." class="pl-8 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 focus:bg-white transition-all">
                        </div>
                        
                        <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                            <i class="fa-solid fa-plus"></i> Tambah Kategori Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Nama Kategori</th>
                                    <th class="py-4 px-6">Deskripsi Singkat</th>
                                    <th class="py-4 px-6">Total Produk/Jasa</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi (CRUD)</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody" class="text-sm divide-y divide-slate-100">
                                @forelse($categories as $category)
                                    @php $isActive = ($category->status ?? 'aktif') === 'aktif'; @endphp
                                    <tr class="category-row hover:bg-slate-50 transition-colors bg-white" data-name="{{ strtolower($category->name) }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg border border-sky-200 shadow-sm"><i class="fa-solid fa-tag"></i></div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $category->name }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">Slug: {{ \Illuminate\Support\Str::slug($category->name) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6"><p class="text-[11px] text-slate-600 w-48 truncate">{{ $category->description ?: '-' }}</p></td>
                                        <td class="py-3 px-6"><p class="text-xs font-bold text-sky-700">{{ number_format($category->products_count ?? 0, 0, ',', '.') }} Layanan</p></td>
                                        <td class="py-3 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border">
                                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" onclick='openEditModal(@json($category))' class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ url('admin/categories/'.$category->id_category) }}" method="POST" class="inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm cursor-pointer" title="Hapus">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500 font-semibold">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL 1: TAMBAH KATEGORI -->
    <div id="addModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-emerald-500" id="addModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50 rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-tags text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Kategori Baru</h3>
                        <p class="text-[10px] font-semibold text-emerald-600">Buat kategori layanan baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="addForm" method="POST" action="{{ route('admin.categories.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Kategori</label>
                    <input type="text" name="name" id="add_name" placeholder="Masukkan nama kategori" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Deskripsi</label>
                    <textarea name="description" id="add_description" rows="3" placeholder="Deskripsi singkat..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all"></textarea>
                </div>
                
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Status</label>
                    <div class="custom-dropdown mt-1">
                        <input type="hidden" name="status" id="add_status_hidden" value="aktif">
                        <input type="checkbox" id="addDropdownToggle" class="sr-only dropdown-toggle">
                        <label for="addDropdownToggle" class="trigger w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all flex justify-between items-center cursor-pointer">
                            <span id="addSelectedStatusText">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300"></i>
                        </label>
                        <ul class="list absolute z-50 left-0 right-0 top-full mt-2 bg-white border-slate-200 rounded-xl shadow-xl">
                            <li onclick="selectStatus('add', 'aktif', 'Aktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors border-b border-slate-100">Aktif</li>
                            <li onclick="selectStatus('add', 'nonaktif', 'Nonaktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors">Nonaktif</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#065f46] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#065f46] transition-all cursor-pointer">
                        Simpan Kategori Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT KATEGORI -->
    <div id="editModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-500" id="editModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-pen-to-square text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Kategori</h3>
                        <p class="text-[10px] font-semibold text-blue-600">Ubah detail data kategori.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="editForm" method="POST" action="" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Kategori</label>
                    <input type="text" name="name" id="edit_name" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Deskripsi</label>
                    <textarea name="description" id="edit_description" rows="3" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition-all"></textarea>
                </div>
                
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Status</label>
                    <div class="custom-dropdown mt-1">
                        <input type="hidden" name="status" id="edit_status_hidden" value="aktif">
                        <input type="checkbox" id="editDropdownToggle" class="sr-only dropdown-toggle">
                        <label for="editDropdownToggle" class="trigger w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all flex justify-between items-center cursor-pointer">
                            <span id="editSelectedStatusText">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300"></i>
                        </label>
                        <ul class="list absolute z-50 left-0 right-0 top-full mt-2 bg-white border-slate-200 rounded-xl shadow-xl">
                            <li onclick="selectStatus('edit', 'aktif', 'Aktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors border-b border-slate-100">Aktif</li>
                            <li onclick="selectStatus('edit', 'nonaktif', 'Nonaktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors">Nonaktif</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitEdit()" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#1e40af] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#1e40af] transition-all cursor-pointer">
                        Update Perubahan
                    </button>
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
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                if(submenu) submenu.classList.toggle('open');
                if(chevron) chevron.classList.toggle('rotated');
            });
        });

        // Close custom dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                const toggles = document.querySelectorAll('.dropdown-toggle');
                toggles.forEach(t => t.checked = false);
            }
        });

        function filterCategories() {
            const q = document.getElementById('categorySearch').value.toLowerCase();
            document.querySelectorAll('#categoryTableBody .category-row').forEach(row => {
                row.style.display = row.dataset.name.includes(q) ? '' : 'none';
            });
        }

        function confirmDelete(button) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) button.closest('form').submit();
            });
        }

        function selectStatus(type, value, text) {
            document.getElementById(`${type}_status_hidden`).value = value;
            document.getElementById(`${type}SelectedStatusText`).textContent = text;
            document.getElementById(`${type}DropdownToggle`).checked = false;
        }

        const addModal = document.getElementById('addModal');
        const addModalContent = document.getElementById('addModalContent');

        function openAddModal() {
            document.getElementById('addForm').reset();
            selectStatus('add', 'aktif', 'Aktif');
            addModal.classList.remove('hidden');
            setTimeout(() => {
                addModal.classList.remove('opacity-0');
                addModalContent.classList.remove('scale-95');
                addModalContent.classList.add('scale-100');
            }, 10);
        }

        function closeAddModal() {
            addModal.classList.add('opacity-0');
            addModalContent.classList.remove('scale-100');
            addModalContent.classList.add('scale-95');
            setTimeout(() => { addModal.classList.add('hidden'); }, 300);
        }

        function submitAdd() {
            const name = document.getElementById('add_name').value.trim();
            if (!name) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan isi nama kategori', confirmButtonColor: '#0EA5E9' });
                return;
            }
            document.getElementById('addForm').submit();
        }

        const editModal = document.getElementById('editModal');
        const editModalContent = document.getElementById('editModalContent');
        let originalEditData = {};

        function openEditModal(category) {
            if(category) {
                originalEditData = {
                    name: String(category.name || ''),
                    description: String(category.description || ''),
                    status: String(category.status || 'aktif')
                };

                document.getElementById('edit_name').value = originalEditData.name;
                document.getElementById('edit_description').value = originalEditData.description;
                let statusText = originalEditData.status === 'aktif' ? 'Aktif' : 'Nonaktif';
                selectStatus('edit', originalEditData.status, statusText);

                document.getElementById('editForm').action = "/admin/categories/" + category.id_category;
            }
            
            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.classList.remove('opacity-0');
                editModalContent.classList.remove('scale-95');
                editModalContent.classList.add('scale-100');
            }, 10);
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editModalContent.classList.remove('scale-100');
            editModalContent.classList.add('scale-95');
            setTimeout(() => { editModal.classList.add('hidden'); }, 300);
        }

        function submitEdit() {
            const currentData = {
                name: document.getElementById('edit_name').value.trim(),
                description: document.getElementById('edit_description').value.trim(),
                status: document.getElementById('edit_status_hidden').value.trim()
            };

            if (!currentData.name) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan isi nama kategori', confirmButtonColor: '#0EA5E9' });
                return;
            }
            if (currentData.name === originalEditData.name && currentData.description === originalEditData.description && currentData.status === originalEditData.status) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan ubah input yang diperlukan', confirmButtonColor: '#0EA5E9' });
                return;
            }
            document.getElementById('editForm').submit();
        }
    </script>
</body>
</html>
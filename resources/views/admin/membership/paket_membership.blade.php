<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Paket Membership</title>
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
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
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
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
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

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center text-amber-300"></i><span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-server w-4 text-center text-white transition-colors"></i><span>Maintenance & Backup</span></div>
                </a>

                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
                </a>
                <a href="{{ route('admin.security.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-shield-halved w-4 text-center text-white"></i><span>Keamanan System</span>
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
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Paket Membership</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola paket langganan premium untuk kreator.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">
                
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            timer: 2500,
                            showConfirmButton: false
                        });
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#ef4444'
                        });
                    </script>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200 p-4 rounded-2xl shadow-sm">
                        <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-widest">Total Pelanggan Aktif</span>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $totalPelangganAktif ?? 0 }}</div>
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold"><i class="fa-solid fa-users text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 p-4 rounded-2xl shadow-sm">
                        <span class="text-[10px] font-extrabold text-blue-800 uppercase tracking-widest">Diamond Plan</span>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $diamondCount ?? 0 }}</div>
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold"><i class="fa-regular fa-gem text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-100 to-white border border-slate-300 p-4 rounded-2xl shadow-sm">
                        <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest">Silver Plan</span>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $silverCount ?? 0 }}</div>
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold"><i class="fa-solid fa-medal text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-white border border-orange-200 p-4 rounded-2xl shadow-sm">
                        <span class="text-[10px] font-extrabold text-orange-800 uppercase tracking-widest">Bronze Plan</span>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $bronzeCount ?? 0 }}</div>
                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold"><i class="fa-solid fa-award text-sm"></i></div>
                        </div>
                    </div>
                </div>

                <!-- TABEL PAKET -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Paket Membership</h3>
                        
                        <button type="button" onclick="triggerAddNewCardAlert()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer">
                            <i class="fa-solid fa-plus"></i> Tambah Kartu / Paket Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6 w-1/4">Nama Paket & Ikon</th>
                                    <th class="py-4 px-6 w-1/6">Harga / Siklus</th>
                                    <th class="py-4 px-6 w-1/4">Fitur / Benefit Kartu</th>
                                    <th class="py-4 px-6 w-1/6">Maksimal Upload</th>
                                    <th class="py-4 px-6 w-1/6">Pelanggan</th>
                                    <th class="py-4 px-6 text-center w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($memberships ?? [] as $membership)
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm"><i class="fa-regular fa-gem"></i></div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs">{{ $membership->name }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">Durasi {{ $membership->duration_days }} hari</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6"><p class="text-xs font-bold text-sky-700">Rp {{ number_format($membership->price, 0, ',', '.') }}</p></td>
                                    <td class="py-3 px-6">
                                        <div class="text-[11px] text-slate-600 space-y-1">
                                            @foreach(explode(' | ', $membership->benefit) as $benefitItem)
                                                <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i> <span>{{ $benefitItem }}</span></div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 px-6"><span class="text-xs font-bold text-slate-700">{{ $membership->max_upload ?? '-' }} karya</span></td>
                                    <td class="py-3 px-6"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $membership->users_count ?? 0 }} pengguna</span></td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick='openEditModal(@json($membership))' class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Edit Kartu"><i class="fa-solid fa-pen-to-square"></i></button>
                                            
                                            <form action="{{ route('admin.memberships.delete', $membership->id_membership) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus Kartu"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400 text-xs font-semibold">Belum ada data paket membership.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL 1: TAMBAH PAKET / KARTU -->
    <div id="addModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4 my-6 overflow-hidden max-h-[90vh] flex flex-col" id="addModalContent">
            
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm"><i class="fa-solid fa-plus text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Kartu Baru</h3>
                        <p class="text-[10px] font-semibold text-slate-500">Konfigurasi detail & pilihan fitur kartu membership.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="addForm" method="POST" action="{{ route('admin.memberships.store') }}" class="p-5 space-y-4 overflow-y-auto">
                @csrf
                
                <!-- DROPDOWN NAMA PAKET -->
                <div class="relative custom-dropdown" id="dropdown_add_name">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Paket / Kartu</label>
                    <div class="relative mt-1">
                        <input type="text" id="add_name" name="name" readonly onclick="toggleDropdown('dropdown_add_name')" placeholder="Pilih Nama Paket" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Diamond Plan')">
                                <span>Diamond Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Silver Plan')">
                                <span>Silver Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Bronze Plan')">
                                <span>Bronze Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('add_name', 'Nama Paket', 'Nama paket...', 'Gold Plan')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition">+ Tambah Nama Paket Baru...</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- DROPDOWN HARGA -->
                    <div class="relative custom-dropdown" id="dropdown_add_price">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Harga (Rp)</label>
                        <div class="relative mt-1">
                            <input type="text" id="add_price" name="price" readonly onclick="toggleDropdown('dropdown_add_price')" placeholder="Pilih Harga" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all">
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                            <div class="options-list max-h-36 overflow-y-auto">
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_price', '150.000', true)">
                                    <span>150.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_price', '50.000', true)">
                                    <span>50.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_price', '25.000', true)">
                                    <span>25.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <div class="p-1.5 bg-slate-50">
                                <button type="button" onclick="promptAddNewOption('add_price', 'Harga', '100.000', '100.000', true)" class="w-full text-left px-2 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition">+ Tambah Harga Baru...</button>
                            </div>
                        </div>
                    </div>

                    <!-- DROPDOWN DURASI -->
                    <div class="relative custom-dropdown" id="dropdown_add_duration">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Durasi (Hari)</label>
                        <div class="relative mt-1">
                            <input type="text" id="add_duration" name="duration_days" readonly onclick="toggleDropdown('dropdown_add_duration')" placeholder="Pilih Durasi" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all">
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                            <div class="options-list max-h-36 overflow-y-auto">
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_duration', '30')">
                                    <span>30 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_duration', '60')">
                                    <span>60 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_duration', '365')">
                                    <span>365 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <div class="p-1.5 bg-slate-50">
                                <button type="button" onclick="promptAddNewOption('add_duration', 'Durasi Hari', '90', '90')" class="w-full text-left px-2 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition">+ Tambah Durasi Hari Baru...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DROPDOWN MAKSIMAL UPLOAD UTAMA -->
                <div class="relative custom-dropdown" id="dropdown_add_max_upload">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Maksimal Upload Karya</label>
                    <div class="relative mt-1">
                        <input type="text" id="add_max_upload" name="max_upload" readonly onclick="toggleDropdown('dropdown_add_max_upload')" placeholder="Pilih Limit Upload" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_max_upload', '999')">
                                <span>999 (Tanpa Batas)</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_max_upload', '50')">
                                <span>50 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_max_upload', '20')">
                                <span>20 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_max_upload', '5')">
                                <span>5 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('add_max_upload', 'Jumlah Upload', '100', '100')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition">+ Tambah Limit Upload Baru...</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION CHECKBOX FITUR INTERAKTIF -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Pilihan Fitur / Benefit Kartu</label>
                    </div>
                    
                    <!-- FITUR 1: JUMLAH JASA / BARANG -->
                    <div class="flex items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none flex-1">
                            <input type="checkbox" name="feat_max_products" value="1" onchange="toggleFeatureInput(this, 'add_val_max_products')" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span>Batas Jumlah Jasa / Barang</span>
                        </label>
                        <div class="w-32 shrink-0">
                            <input type="number" id="add_val_max_products" name="val_max_products" placeholder="Misal: 50" disabled class="w-full text-xs font-bold border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-40 transition">
                        </div>
                    </div>

                    <!-- FITUR 2: SLOT IKLAN PROMOSI -->
                    <div class="flex items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none flex-1">
                            <input type="checkbox" name="feat_max_ads" value="1" onchange="toggleFeatureInput(this, 'add_val_max_ads')" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span>Batas Jumlah Iklan Promosi</span>
                        </label>
                        <div class="w-32 shrink-0">
                            <input type="number" id="add_val_max_ads" name="val_max_ads" placeholder="Misal: 5" disabled class="w-full text-xs font-bold border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-40 transition">
                        </div>
                    </div>

                    <!-- FITUR 3: LENCANA VERIFIKASI -->
                    <div class="flex items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none w-full">
                            <input type="checkbox" name="feat_verified_badge" value="1" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span>Lencana Kreator Terverifikasi</span>
                        </label>
                    </div>

                    <!-- FITUR 4: PRIORITAS CUSTOMER SERVICE -->
                    <div class="flex items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none w-full">
                            <input type="checkbox" name="feat_priority_cs" value="1" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span>Dukungan Prioritas CS 24/7</span>
                        </label>
                    </div>

                    <!-- CONTAINER CHECKBOX CUSTOM TAMBAHAN -->
                    <div id="custom_features_container_add" class="space-y-3"></div>

                    <!-- TOMBOL TAMBAH CHECKBOX DINAMIS -->
                    <div>
                        <button type="button" onclick="promptAddNewCheckbox('add')" class="w-full py-2.5 px-3 border-2 border-dashed border-emerald-300 hover:border-emerald-500 rounded-xl text-emerald-700 bg-emerald-50/50 hover:bg-emerald-100/70 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus-circle text-sm"></i>
                            <span>+ Tambah Fitur / Checkbox Baru</span>
                        </button>
                    </div>

                    <!-- BENEFIT TAMBAHAN MANUAL -->
                    <div class="pt-1">
                        <label class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wide">Benefit Tambahan Lainnya (Opsional)</label>
                        <input type="text" name="custom_benefit" placeholder="Misal: Bebas biaya penarikan saldo" class="mt-1 w-full text-xs font-semibold border border-slate-200 bg-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#065f46] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#065f46] transition-all cursor-pointer">
                        Simpan Kartu / Paket Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT PAKET -->
    <div id="editModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4 my-6 overflow-hidden max-h-[90vh] flex flex-col border-t-4 border-blue-500" id="editModalContent">
            
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><i class="fa-solid fa-pen-to-square text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Kartu Membership</h3>
                        <p class="text-[10px] font-semibold text-blue-600">Perbarui informasi dan daftar fitur kartu.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="editForm" method="POST" action="" class="p-5 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')
                
                <!-- EDIT NAMA -->
                <div class="relative custom-dropdown" id="dropdown_edit_name">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Paket / Kartu</label>
                    <div class="relative mt-1">
                        <input type="text" id="edit_name" name="name" readonly onclick="toggleDropdown('dropdown_edit_name')" placeholder="Pilih Nama Paket" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Diamond Plan')">
                                <span>Diamond Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Silver Plan')">
                                <span>Silver Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Bronze Plan')">
                                <span>Bronze Plan</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('edit_name', 'Nama Paket', 'Nama paket...', 'VIP Plan')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition">+ Tambah Nama Paket Baru...</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- EDIT HARGA -->
                    <div class="relative custom-dropdown" id="dropdown_edit_price">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Harga (Rp)</label>
                        <div class="relative mt-1">
                            <input type="text" id="edit_price" name="price" readonly onclick="toggleDropdown('dropdown_edit_price')" placeholder="Pilih Harga" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all">
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                            <div class="options-list max-h-36 overflow-y-auto">
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_price', '150.000', true)">
                                    <span>150.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_price', '50.000', true)">
                                    <span>50.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_price', '25.000', true)">
                                    <span>25.000</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <div class="p-1.5 bg-slate-50">
                                <button type="button" onclick="promptAddNewOption('edit_price', 'Harga', '200.000', '200.000', true)" class="w-full text-left px-2 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition">+ Tambah Harga Baru...</button>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT DURASI -->
                    <div class="relative custom-dropdown" id="dropdown_edit_duration">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Durasi (Hari)</label>
                        <div class="relative mt-1">
                            <input type="text" id="edit_duration" name="duration_days" readonly onclick="toggleDropdown('dropdown_edit_duration')" placeholder="Pilih Durasi" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all">
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                            <div class="options-list max-h-36 overflow-y-auto">
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_duration', '30')">
                                    <span>30 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_duration', '60')">
                                    <span>60 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_duration', '365')">
                                    <span>365 Hari</span>
                                    <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <div class="p-1.5 bg-slate-50">
                                <button type="button" onclick="promptAddNewOption('edit_duration', 'Durasi Hari', '180', '180')" class="w-full text-left px-2 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition">+ Tambah Durasi Hari Baru...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT MAKSIMAL UPLOAD -->
                <div class="relative custom-dropdown" id="dropdown_edit_max_upload">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Maksimal Upload Karya</label>
                    <div class="relative mt-1">
                        <input type="text" id="edit_max_upload" name="max_upload" readonly onclick="toggleDropdown('dropdown_edit_max_upload')" placeholder="Pilih Limit Upload" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_max_upload', '999')">
                                <span>999 (Tanpa Batas)</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_max_upload', '50')">
                                <span>50 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_max_upload', '20')">
                                <span>20 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_max_upload', '5')">
                                <span>5 Karya</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('edit_max_upload', 'Jumlah Upload', '150', '150')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition">+ Tambah Limit Upload Baru...</button>
                        </div>
                    </div>
                </div>

                <!-- EDIT SECTION CHECKBOX FITUR INTERAKTIF -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Pilihan Fitur / Benefit Kartu</label>
                    </div>
                    
                    <div class="flex items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none flex-1">
                            <input type="checkbox" id="edit_feat_max_products" name="feat_max_products" value="1" onchange="toggleFeatureInput(this, 'edit_val_max_products')" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span>Batas Jumlah Jasa / Barang</span>
                        </label>
                        <div class="w-32 shrink-0">
                            <input type="number" id="edit_val_max_products" name="val_max_products" placeholder="Misal: 50" disabled class="w-full text-xs font-bold border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-40 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none flex-1">
                            <input type="checkbox" id="edit_feat_max_ads" name="feat_max_ads" value="1" onchange="toggleFeatureInput(this, 'edit_val_max_ads')" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span>Batas Jumlah Iklan Promosi</span>
                        </label>
                        <div class="w-32 shrink-0">
                            <input type="number" id="edit_val_max_ads" name="val_max_ads" placeholder="Misal: 5" disabled class="w-full text-xs font-bold border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-40 transition">
                        </div>
                    </div>

                    <div class="flex items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none w-full">
                            <input type="checkbox" id="edit_feat_verified_badge" name="feat_verified_badge" value="1" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span>Lencana Kreator Terverifikasi</span>
                        </label>
                    </div>

                    <div class="flex items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none w-full">
                            <input type="checkbox" id="edit_feat_priority_cs" name="feat_priority_cs" value="1" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span>Dukungan Prioritas CS 24/7</span>
                        </label>
                    </div>

                    <!-- CONTAINER CHECKBOX CUSTOM TAMBAHAN (EDIT) -->
                    <div id="custom_features_container_edit" class="space-y-3"></div>

                    <!-- TOMBOL TAMBAH CHECKBOX DINAMIS (EDIT) -->
                    <div>
                        <button type="button" onclick="promptAddNewCheckbox('edit')" class="w-full py-2.5 px-3 border-2 border-dashed border-blue-300 hover:border-blue-500 rounded-xl text-blue-700 bg-blue-50/50 hover:bg-blue-100/70 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus-circle text-sm"></i>
                            <span>+ Tambah Fitur / Checkbox Baru</span>
                        </button>
                    </div>

                    <div class="pt-1">
                        <label class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wide">Benefit Tambahan Lainnya</label>
                        <input type="text" id="edit_custom_benefit" name="custom_benefit" placeholder="Benefit khusus..." class="mt-1 w-full text-xs font-semibold border border-slate-200 bg-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitEdit()" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#1e40af] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#1e40af] transition-all cursor-pointer">
                        Update Perubahan Kartu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT & HANDLING MODAL -->
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

        function confirmDelete(button) {
            Swal.fire({
                title: 'Hapus Kartu / Paket?',
                text: "Data kartu ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }

        function formatRibuan(val) {
            if (!val) return '';
            let number_string = val.toString().replace(/[^,\d]/g, '');
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const menu = dropdown.querySelector('.dropdown-menu');
            const isHidden = menu.classList.contains('hidden');
            
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            if (isHidden) {
                menu.classList.remove('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        function selectOption(inputId, value, isPrice = false) {
            const input = document.getElementById(inputId);
            input.value = isPrice ? formatRibuan(value) : value;
            input.setAttribute('readonly', 'readonly');
            
            const dropdown = input.closest('.custom-dropdown');
            if (dropdown) {
                dropdown.querySelector('.dropdown-menu').classList.add('hidden');
            }
        }

        /* FITUR 1: PROMPT INPUT PRESET BARU UNTUK NAMA, HARGA, HARI, DAN UPLOAD */
        function promptAddNewOption(inputId, labelName, placeholderText, exampleText, isPrice = false) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));

            Swal.fire({
                title: `<span class="font-display font-extrabold text-slate-800 text-xl">Tambah ${labelName} Baru</span>`,
                html: `<p class="text-xs font-semibold text-slate-500 mb-4">Masukkan ${labelName.toLowerCase()} baru ${exampleText ? '(contoh: ' + exampleText + ')' : ''}:</p>`,
                input: 'text',
                inputPlaceholder: placeholderText || `Masukkan ${labelName.toLowerCase()}...`,
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                    input: 'w-full border-2 border-sky-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:outline-none transition-all my-2 text-center',
                    confirmButton: 'px-6 py-2.5 bg-emerald-500 text-white font-bold text-sm rounded-xl hover:bg-emerald-600 transition-all shadow-md mx-1 cursor-pointer',
                    cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                },
                preConfirm: (value) => {
                    if (!value || !value.trim()) {
                        Swal.showValidationMessage(`Harap isi ${labelName.toLowerCase()} terlebih dahulu!`);
                        return false;
                    }
                    return value.trim();
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let newValue = result.value;
                    let rawValue = newValue;
                    if (isPrice) {
                        newValue = formatRibuan(newValue);
                        rawValue = newValue.replace(/\./g, '');
                    }

                    // Tambahkan item baru ke dalam options-list dropdown secara dinamis
                    const dropdown = document.getElementById(inputId).closest('.custom-dropdown');
                    if (dropdown) {
                        const optionsList = dropdown.querySelector('.options-list');
                        if (optionsList) {
                            const hoverBg = inputId.startsWith('edit_') ? 'hover:bg-blue-50' : 'hover:bg-emerald-50';
                            
                            const newItem = document.createElement('div');
                            newItem.className = `dropdown-item flex items-center justify-between px-3.5 py-2 ${hoverBg} cursor-pointer text-xs font-semibold text-slate-700`;
                            
                            let displayVal = newValue;
                            if (inputId.includes('duration') && !displayVal.toLowerCase().includes('hari')) {
                                displayVal += ' Hari';
                            }
                            if (inputId.includes('max_upload') && !displayVal.toLowerCase().includes('karya') && displayVal !== '999') {
                                displayVal += ' Karya';
                            }

                            newItem.setAttribute('onclick', `selectOption('${inputId}', '${rawValue}', ${isPrice})`);
                            newItem.innerHTML = `
                                <span>${displayVal}</span>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            `;
                            optionsList.appendChild(newItem);
                        }
                    }

                    // Set pilihan aktif
                    selectOption(inputId, rawValue, isPrice);

                    // Notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `${labelName} "${newValue}" berhasil ditambahkan dan dipilih.`,
                        timer: 1600,
                        showConfirmButton: false
                    });
                }
            });
        }

        function removeDropdownItem(btnEl) {
            const item = btnEl.closest('.dropdown-item');
            if (item) {
                item.remove();
            }
        }

        function toggleFeatureInput(checkboxEl, targetInputId) {
            const inputEl = document.getElementById(targetInputId);
            if (checkboxEl.checked) {
                inputEl.removeAttribute('disabled');
                inputEl.focus();
            } else {
                inputEl.value = '';
                inputEl.setAttribute('disabled', 'disabled');
            }
        }

        /* FITUR 2: MODAL SWEETALERT UNTUK MENAMBAH CHECKBOX FITUR / BENEFIT KUSTOM */
        let customFeatureIndex = 100;
        function promptAddNewCheckbox(mode = 'add') {
            const themeColor = mode === 'edit' ? 'blue' : 'emerald';

            Swal.fire({
                title: `<span class="font-display font-extrabold text-slate-800 text-xl">Tambah Fitur / Checkbox Baru</span>`,
                html: `
                    <p class="text-xs font-semibold text-slate-500 mb-3 text-left">Masukkan detail nama fitur benefit baru yang ingin Anda cantumkan pada kartu:</p>
                    <div class="space-y-3 text-left">
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 block mb-1">Nama Fitur / Benefit</label>
                            <input id="swal_feat_name" type="text" placeholder="Misal: Bebas Biaya Layanan" class="w-full border-2 border-sky-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 focus:border-${themeColor}-500 focus:outline-none transition">
                        </div>
                        <div class="flex items-center gap-2 pt-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <input id="swal_feat_has_value" type="checkbox" class="w-4 h-4 text-${themeColor}-600 rounded border-slate-300 focus:ring-${themeColor}-500">
                            <label for="swal_feat_has_value" class="text-xs font-bold text-slate-700 cursor-pointer select-none">Sertakan kolom input jumlah/nilai (Opsional)</label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Tambah Checkbox',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                    confirmButton: `px-6 py-2.5 bg-${themeColor}-600 text-white font-bold text-sm rounded-xl hover:bg-${themeColor}-700 transition-all shadow-md mx-1 cursor-pointer`,
                    cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                },
                preConfirm: () => {
                    const name = document.getElementById('swal_feat_name').value.trim();
                    const hasValue = document.getElementById('swal_feat_has_value').checked;
                    if (!name) {
                        Swal.showValidationMessage('Nama fitur benefit wajib diisi!');
                        return false;
                    }
                    return { name, hasValue };
                }
            }).then((res) => {
                if (res.isConfirmed && res.value) {
                    addCustomCheckboxToContainer(mode, res.value.name, res.value.hasValue, '', true);
                    Swal.fire({
                        icon: 'success',
                        title: 'Checkbox Ditambahkan!',
                        text: `Fitur "${res.value.name}" siap digunakan pada kartu.`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function addCustomCheckboxToContainer(mode, name, hasValue = false, defaultVal = '', isChecked = true) {
            customFeatureIndex++;
            const containerId = mode === 'edit' ? 'custom_features_container_edit' : 'custom_features_container_add';
            const container = document.getElementById(containerId);
            if (!container) return;

            const themeColor = mode === 'edit' ? 'blue' : 'emerald';
            const itemDiv = document.createElement('div');
            itemDiv.className = `custom-feat-item flex items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm transition-all`;

            const checkAttr = isChecked ? 'checked' : '';
            const disableAttr = (!isChecked && hasValue) ? 'disabled' : '';

            let valueHtml = '';
            if (hasValue) {
                valueHtml = `
                    <div class="flex items-center gap-1.5 shrink-0">
                        <input type="text" name="custom_features[${customFeatureIndex}][val]" value="${defaultVal}" placeholder="Nilai/Jml" ${disableAttr} class="custom-val-input w-28 text-xs font-bold border border-slate-200 rounded-lg px-2.5 py-1.5 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-${themeColor}-500 transition">
                        <button type="button" onclick="this.closest('.custom-feat-item').remove()" class="w-7 h-7 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition" title="Hapus Fitur"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                `;
            } else {
                valueHtml = `
                    <button type="button" onclick="this.closest('.custom-feat-item').remove()" class="w-7 h-7 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition shrink-0" title="Hapus Fitur"><i class="fa-solid fa-trash text-xs"></i></button>
                `;
            }

            itemDiv.innerHTML = `
                <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none flex-1">
                    <input type="checkbox" name="custom_features[${customFeatureIndex}][checked]" value="1" ${checkAttr} onchange="toggleCustomFeatVal(this)" class="w-4 h-4 text-${themeColor}-600 rounded border-slate-300 focus:ring-${themeColor}-500">
                    <input type="hidden" name="custom_features[${customFeatureIndex}][name]" value="${name}">
                    <span class="truncate">${name}</span>
                </label>
                ${valueHtml}
            `;

            container.appendChild(itemDiv);
        }

        function toggleCustomFeatVal(checkboxEl) {
            const row = checkboxEl.closest('.custom-feat-item');
            if (row) {
                const valInput = row.querySelector('.custom-val-input');
                if (valInput) {
                    if (checkboxEl.checked) {
                        valInput.removeAttribute('disabled');
                        valInput.focus();
                    } else {
                        valInput.setAttribute('disabled', 'disabled');
                    }
                }
            }
        }

        function triggerAddNewCardAlert() {
            Swal.fire({
                title: 'Tambah Kartu Paket Baru?',
                text: 'Silakan isi konfigurasi kartu membership pada form berikut.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0EA5E9',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    openAddModal();
                }
            });
        }

        const addModal = document.getElementById('addModal');
        const addModalContent = document.getElementById('addModalContent');

        function openAddModal() {
            document.getElementById('addForm').reset();
            document.getElementById('custom_features_container_add').innerHTML = '';
            ['add_name', 'add_price', 'add_duration', 'add_max_upload'].forEach(id => {
                document.getElementById(id).setAttribute('readonly', 'readonly');
            });
            ['add_val_max_products', 'add_val_max_ads'].forEach(id => {
                document.getElementById(id).setAttribute('disabled', 'disabled');
            });
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
            const price = document.getElementById('add_price').value.trim();
            const duration = document.getElementById('add_duration').value.trim();
            const maxUpload = document.getElementById('add_max_upload').value.trim();

            if (!name || !price || !duration || !maxUpload) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silahkan isi bidang Nama, Harga, Durasi, dan Maksimal Upload.',
                    confirmButtonColor: '#0EA5E9'
                });
                return;
            }

            document.getElementById('addForm').submit();
        }

        const editModal = document.getElementById('editModal');
        const editModalContent = document.getElementById('editModalContent');

        function openEditModal(membership) {
            if(membership) {
                document.getElementById('editForm').reset();
                document.getElementById('custom_features_container_edit').innerHTML = '';

                document.getElementById('edit_name').value = membership.name || '';
                document.getElementById('edit_price').value = formatRibuan(membership.price || '');
                document.getElementById('edit_duration').value = membership.duration_days || '';
                document.getElementById('edit_max_upload').value = membership.max_upload || '';
                document.getElementById('editForm').action = "/admin/memberships/" + membership.id_membership;

                // Reset standard checkboxes
                ['edit_feat_max_products', 'edit_feat_max_ads', 'edit_feat_verified_badge', 'edit_feat_priority_cs'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.checked = false;
                });
                ['edit_val_max_products', 'edit_val_max_ads'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) { el.value = ''; el.setAttribute('disabled', 'disabled'); }
                });
                document.getElementById('edit_custom_benefit').value = '';

                // PARSE BENEFIT STRING
                if (membership.benefit) {
                    const benefitItems = membership.benefit.split(' | ');
                    benefitItems.forEach(item => {
                        const trimmed = item.trim();
                        if (trimmed.startsWith('Maksimal Upload:')) {
                            // Already handled by max_upload
                        } else if (trimmed.startsWith('Batas Jasa/Barang:') || trimmed.startsWith('Batas Jumlah Jasa:')) {
                            const valMatch = trimmed.match(/\d+/);
                            const checkbox = document.getElementById('edit_feat_max_products');
                            const inputVal = document.getElementById('edit_val_max_products');
                            if (checkbox && inputVal) {
                                checkbox.checked = true;
                                inputVal.removeAttribute('disabled');
                                inputVal.value = valMatch ? valMatch[0] : '';
                            }
                        } else if (trimmed.startsWith('Iklan Promosi:') || trimmed.startsWith('Batas Jumlah Iklan:')) {
                            const valMatch = trimmed.match(/\d+/);
                            const checkbox = document.getElementById('edit_feat_max_ads');
                            const inputVal = document.getElementById('edit_val_max_ads');
                            if (checkbox && inputVal) {
                                checkbox.checked = true;
                                inputVal.removeAttribute('disabled');
                                inputVal.value = valMatch ? valMatch[0] : '';
                            }
                        } else if (trimmed === 'Lencana Kreator Terverifikasi') {
                            const checkbox = document.getElementById('edit_feat_verified_badge');
                            if (checkbox) checkbox.checked = true;
                        } else if (trimmed === 'Dukungan CS Prioritas 24/7' || trimmed === 'Dukungan Prioritas CS 24/7') {
                            const checkbox = document.getElementById('edit_feat_priority_cs');
                            if (checkbox) checkbox.checked = true;
                        } else if (trimmed !== 'Fitur standar keanggotaan') {
                            // Check if it has key-value pair
                            if (trimmed.includes(':')) {
                                const parts = trimmed.split(':');
                                const fName = parts[0].trim();
                                const fVal = parts.slice(1).join(':').trim();
                                addCustomCheckboxToContainer('edit', fName, true, fVal, true);
                            } else {
                                addCustomCheckboxToContainer('edit', trimmed, false, '', true);
                            }
                        }
                    });
                }
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
            const name = document.getElementById('edit_name').value.trim();
            const price = document.getElementById('edit_price').value.trim();
            const duration = document.getElementById('edit_duration').value.trim();
            const maxUpload = document.getElementById('edit_max_upload').value.trim();

            if (!name || !price || !duration || !maxUpload) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silahkan isi bidang yang diperlukan.',
                    confirmButtonColor: '#0EA5E9'
                });
                return;
            }

            document.getElementById('editForm').submit();
        }
    </script>
</body>
</html>
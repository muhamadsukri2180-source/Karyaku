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
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
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
        
        /* Memastikan kotak input dan area ketik tetap tegak normal */
        input:not([type="checkbox"]):not([type="radio"]), select, textarea { font-style: normal !important; }
        
        /* Efek sorot kartu saat tombol lihat diklik */
        .highlight-card { animation: pulseHighlight 1.5s ease-in-out; }
        @keyframes pulseHighlight {
            0% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7); transform: scale(1); }
            50% { box-shadow: 0 0 0 15px rgba(14, 165, 233, 0); transform: scale(1.02); }
            100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); transform: scale(1); }
        }
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

                <!-- PHP CALCULATIONS UNTUK STATISTIK CARD OTOMATIS -->
                @php
                    $allMemberships = $memberships ?? collect();
                    $totalPelangganAktif = $allMemberships->sum('users_count');
                    
                    // Menghitung otomatis berdasarkan nama paket yang tersimpan di database
                    $diamondCount = $allMemberships->filter(function($item) {
                        return stripos($item->name, 'Diamond') !== false;
                    })->sum('users_count');

                    $silverCount = $allMemberships->filter(function($item) {
                        return stripos($item->name, 'Silver') !== false;
                    })->sum('users_count');

                    $bronzeCount = $allMemberships->filter(function($item) {
                        return stripos($item->name, 'Bronze') !== false;
                    })->sum('users_count');
                @endphp

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200 p-4 rounded-2xl shadow-sm relative group">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-widest italic">Total Pelanggan</span>
                            <button type="button" onclick="scrollToCardSection()" class="text-[9px] font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-100/70 hover:bg-emerald-200 px-2 py-1 rounded transition-colors cursor-pointer" title="Lihat Daftar Kartu Paket">
                                <i class="fa-solid fa-eye"></i> Lihat
                            </button>
                        </div>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $totalPelangganAktif }}</div>
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold"><i class="fa-solid fa-users text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 p-4 rounded-2xl shadow-sm relative group">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-extrabold text-blue-800 uppercase tracking-widest italic">Diamond Plan</span>
                            <button type="button" onclick="filterCardByName('Diamond')" class="text-[9px] font-bold text-blue-600 hover:text-blue-800 bg-blue-100/70 hover:bg-blue-200 px-2 py-1 rounded transition-colors cursor-pointer" title="Lihat Kartu Diamond">
                                <i class="fa-solid fa-eye"></i> Lihat
                            </button>
                        </div>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $diamondCount }}</div>
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold"><i class="fa-regular fa-gem text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-100 to-white border border-slate-300 p-4 rounded-2xl shadow-sm relative group">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest italic">Silver Plan</span>
                            <button type="button" onclick="filterCardByName('Silver')" class="text-[9px] font-bold text-slate-600 hover:text-slate-800 bg-slate-200/70 hover:bg-slate-300 px-2 py-1 rounded transition-colors cursor-pointer" title="Lihat Kartu Silver">
                                <i class="fa-solid fa-eye"></i> Lihat
                            </button>
                        </div>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $silverCount }}</div>
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold"><i class="fa-solid fa-medal text-sm"></i></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-white border border-orange-200 p-4 rounded-2xl shadow-sm relative group">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-extrabold text-orange-800 uppercase tracking-widest italic">Bronze Plan</span>
                            <button type="button" onclick="filterCardByName('Bronze')" class="text-[9px] font-bold text-orange-600 hover:text-orange-800 bg-orange-100/70 hover:bg-orange-200 px-2 py-1 rounded transition-colors cursor-pointer" title="Lihat Kartu Bronze">
                                <i class="fa-solid fa-eye"></i> Lihat
                            </button>
                        </div>
                        <div class="flex items-end justify-between mt-2">
                            <div class="text-3xl font-black text-slate-900">{{ $bronzeCount }}</div>
                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold"><i class="fa-solid fa-award text-sm"></i></div>
                        </div>
                    </div>
                </div>

                <!-- TABEL PAKET -->
                <div id="membershipCardSection" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Paket Membership</h3>
                            <button type="button" id="resetFilterBtn" onclick="resetCardFilter()" class="hidden text-[10px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 px-2.5 py-1 rounded-lg transition">Tampilkan Semua</button>
                        </div>
                        
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
                            <tbody class="text-sm divide-y divide-slate-100" id="membershipTableBody">
                                @forelse ($memberships ?? [] as $membership)
                                <tr class="membership-row hover:bg-slate-50 transition-colors bg-white" data-name="{{ strtolower($membership->name) }}">
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
                                <tr id="emptyMembershipRow">
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
                        <p class="text-[10px] font-semibold text-slate-500 italic">Konfigurasi detail & pilihan fitur kartu membership.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="addForm" method="POST" action="{{ route('admin.memberships.store') }}" class="p-5 space-y-4 overflow-y-auto">
                @csrf
                
                <!-- DROPDOWN NAMA PAKET -->
                <div class="relative custom-dropdown" id="dropdown_add_name">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Nama Paket / Kartu</label>
                    <div class="relative mt-1">
                        <input type="text" id="add_name" name="name" readonly onclick="toggleDropdown('dropdown_add_name')" placeholder="Pilih Nama Paket" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all not-italic">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Diamond Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-gem text-blue-500"></i>
                                    <span>Diamond Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Silver Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-medal text-slate-400"></i>
                                    <span>Silver Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-emerald-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('add_name', 'Bronze Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-award text-amber-600"></i>
                                    <span>Bronze Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('add_name', 'Nama Paket', 'Nama paket...', 'Gold Plan')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition italic">+ Tambah Nama Paket Baru...</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- DROPDOWN HARGA -->
                    <div class="relative custom-dropdown" id="dropdown_add_price">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Harga (Rp)</label>
                        <div class="relative mt-1">
                            <input type="text" id="add_price" name="price" readonly onclick="toggleDropdown('dropdown_add_price')" placeholder="Pilih Harga" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all not-italic">
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
                                <button type="button" onclick="promptAddNewOption('add_price', 'Harga', '100.000', '100.000', true)" class="w-full text-left px-2 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition italic">+ Tambah Harga Baru...</button>
                            </div>
                        </div>
                    </div>

                    <!-- DROPDOWN DURASI -->
                    <div class="relative custom-dropdown" id="dropdown_add_duration">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Durasi (Hari)</label>
                        <div class="relative mt-1">
                            <input type="text" id="add_duration" name="duration_days" readonly onclick="toggleDropdown('dropdown_add_duration')" placeholder="Pilih Durasi" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all not-italic">
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
                                <button type="button" onclick="promptAddNewOption('add_duration', 'Durasi Hari', '90', '90')" class="w-full text-left px-2 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition italic">+ Tambah Durasi Hari Baru...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DROPDOWN MAKSIMAL UPLOAD -->
                <div class="relative custom-dropdown" id="dropdown_add_max_upload">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Maksimal Upload Karya</label>
                    <div class="relative mt-1">
                        <input type="text" id="add_max_upload" name="max_upload" readonly onclick="toggleDropdown('dropdown_add_max_upload')" placeholder="Pilih Limit Upload" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white transition-all not-italic">
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
                            <button type="button" onclick="promptAddNewOption('add_max_upload', 'Jumlah Upload', '100', '100')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-600 hover:bg-emerald-100/60 rounded-lg transition italic">+ Tambah Limit Upload Baru...</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION FITUR SEDERHANA (ADD) -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Fitur / Benefit Kartu</label>
                        <p class="text-[10px] text-slate-500 mt-1">Masukkan angka atau benefit secara langsung. Tidak perlu checkbox.</p>
                    </div>

                    <input type="hidden" name="feat_max_products" value="1">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="add_val_max_products" class="text-xs font-bold text-slate-700 block mb-1.5">Batas Jumlah Jasa / Barang</label>
                        <input type="number" id="add_val_max_products" name="val_max_products" min="1" required placeholder="Misal: 15" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-400 transition not-italic">
                    </div>

                    <input type="hidden" name="feat_max_ads" value="1">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="add_val_max_ads" class="text-xs font-bold text-slate-700 block mb-1.5">Batas Jumlah Iklan Promosi</label>
                        <input type="number" id="add_val_max_ads" name="val_max_ads" min="0" required placeholder="Misal: 1" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-400 transition not-italic">
                    </div>

                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="add_verified_badge" class="text-xs font-bold text-slate-700 block mb-1.5">Lencana Kreator Terverifikasi</label>
                        <select id="add_verified_badge" name="feat_verified_badge" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-400 transition not-italic">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="add_priority_cs" class="text-xs font-bold text-slate-700 block mb-1.5">Dukungan Prioritas CS 24/7</label>
                        <select id="add_priority_cs" name="feat_priority_cs" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-400 transition not-italic">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div id="custom_features_container_add" class="space-y-2"></div>

                    <div>
                        <button type="button" onclick="promptAddNewCheckbox('add')" class="w-full py-2.5 px-3 border-2 border-dashed border-emerald-300 hover:border-emerald-500 rounded-xl text-emerald-700 bg-emerald-50/50 hover:bg-emerald-100/70 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus-circle text-sm"></i>
                            <span>+ Tambah Benefit Lain</span>
                        </button>
                    </div>

                    <div class="pt-1">
                        <label class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wide">Benefit Tambahan Lainnya</label>
                        <input type="text" id="add_custom_benefit" name="custom_benefit" placeholder="Contoh: Prioritas tampil di halaman utama" class="mt-1 w-full text-xs font-semibold border border-slate-200 bg-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm not-italic">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#059669] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#059669] transition-all cursor-pointer">
                        Simpan Kartu
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
                        <p class="text-[10px] font-semibold text-blue-600 italic">Perbarui informasi dan daftar fitur kartu.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="editForm" method="POST" action="" class="p-5 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')
                
                <!-- EDIT NAMA -->
                <div class="relative custom-dropdown" id="dropdown_edit_name">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Nama Paket / Kartu</label>
                    <div class="relative mt-1">
                        <input type="text" id="edit_name" name="name" readonly onclick="toggleDropdown('dropdown_edit_name')" placeholder="Pilih Nama Paket" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all not-italic">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100">
                        <div class="options-list max-h-36 overflow-y-auto">
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Diamond Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-gem text-blue-500"></i>
                                    <span>Diamond Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Silver Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-medal text-slate-400"></i>
                                    <span>Silver Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="dropdown-item flex items-center justify-between px-3.5 py-2 hover:bg-blue-50 cursor-pointer text-xs font-semibold text-slate-700" onclick="selectOption('edit_name', 'Bronze Plan')">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-award text-amber-600"></i>
                                    <span>Bronze Plan</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="p-1.5 bg-slate-50">
                            <button type="button" onclick="promptAddNewOption('edit_name', 'Nama Paket', 'Nama paket...', 'VIP Plan')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition italic">+ Tambah Nama Paket Baru...</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- EDIT HARGA -->
                    <div class="relative custom-dropdown" id="dropdown_edit_price">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Harga (Rp)</label>
                        <div class="relative mt-1">
                            <input type="text" id="edit_price" name="price" readonly onclick="toggleDropdown('dropdown_edit_price')" placeholder="Pilih Harga" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all not-italic">
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
                                <button type="button" onclick="promptAddNewOption('edit_price', 'Harga', '200.000', '200.000', true)" class="w-full text-left px-2 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition italic">+ Tambah Harga Baru...</button>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT DURASI -->
                    <div class="relative custom-dropdown" id="dropdown_edit_duration">
                        <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Durasi (Hari)</label>
                        <div class="relative mt-1">
                            <input type="text" id="edit_duration" name="duration_days" readonly onclick="toggleDropdown('dropdown_edit_duration')" placeholder="Pilih Durasi" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-8 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all not-italic">
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
                                <button type="button" onclick="promptAddNewOption('edit_duration', 'Durasi Hari', '180', '180')" class="w-full text-left px-2 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition italic">+ Tambah Durasi Hari Baru...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT MAKSIMAL UPLOAD -->
                <div class="relative custom-dropdown" id="dropdown_edit_max_upload">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide italic">Maksimal Upload Karya</label>
                    <div class="relative mt-1">
                        <input type="text" id="edit_max_upload" name="max_upload" readonly onclick="toggleDropdown('dropdown_edit_max_upload')" placeholder="Pilih Limit Upload" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all not-italic">
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
                            <button type="button" onclick="promptAddNewOption('edit_max_upload', 'Jumlah Upload', '150', '150')" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100/60 rounded-lg transition italic">+ Tambah Limit Upload Baru...</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION FITUR SEDERHANA (EDIT) -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Fitur / Benefit Kartu</label>
                        <p class="text-[10px] text-slate-500 mt-1">Masukkan angka atau benefit secara langsung. Tidak perlu checkbox.</p>
                    </div>

                    <input type="hidden" name="feat_max_products" value="1">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="edit_val_max_products" class="text-xs font-bold text-slate-700 block mb-1.5">Batas Jumlah Jasa / Barang</label>
                        <input type="number" id="edit_val_max_products" name="val_max_products" min="1" placeholder="Misal: 50" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400 transition not-italic">
                    </div>

                    <input type="hidden" name="feat_max_ads" value="1">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="edit_val_max_ads" class="text-xs font-bold text-slate-700 block mb-1.5">Batas Jumlah Iklan Promosi</label>
                        <input type="number" id="edit_val_max_ads" name="val_max_ads" min="0" placeholder="Misal: 5" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400 transition not-italic">
                    </div>

                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="edit_verified_badge" class="text-xs font-bold text-slate-700 block mb-1.5">Lencana Kreator Terverifikasi</label>
                        <select id="edit_verified_badge" name="feat_verified_badge" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400 transition not-italic">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                        <label for="edit_priority_cs" class="text-xs font-bold text-slate-700 block mb-1.5">Dukungan Prioritas CS 24/7</label>
                        <select id="edit_priority_cs" name="feat_priority_cs" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400 transition not-italic">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div id="custom_features_container_edit" class="space-y-2"></div>

                    <div>
                        <button type="button" onclick="promptAddNewCheckbox('edit')" class="w-full py-2.5 px-3 border-2 border-dashed border-blue-300 hover:border-blue-500 rounded-xl text-blue-700 bg-blue-50/50 hover:bg-blue-100/70 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus-circle text-sm"></i>
                            <span>+ Tambah Benefit Lain</span>
                        </button>
                    </div>

                    <div class="pt-1">
                        <label class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wide">Benefit Tambahan Lainnya</label>
                        <input type="text" id="edit_custom_benefit" name="custom_benefit" placeholder="Benefit khusus..." class="mt-1 w-full text-xs font-semibold border border-slate-200 bg-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm not-italic">
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

        // Fungsi navigasi tombol "Lihat" langsung ke bagian Tabel Kartu Membership
        function scrollToCardSection() {
            const section = document.getElementById('membershipCardSection');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                section.classList.add('highlight-card');
                setTimeout(() => {
                    section.classList.remove('highlight-card');
                }, 1500);
            }
        }

        // Fungsi filter baris tabel kartu berdasarkan nama paket (Diamond / Silver / Bronze)
        function filterCardByName(keyword) {
            scrollToCardSection();
            const rows = document.querySelectorAll('.membership-row');
            let found = false;
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                if (name.includes(keyword.toLowerCase())) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const resetBtn = document.getElementById('resetFilterBtn');
            if (resetBtn) resetBtn.classList.remove('hidden');
        }

        function resetCardFilter() {
            const rows = document.querySelectorAll('.membership-row');
            rows.forEach(row => {
                row.style.display = '';
            });
            const resetBtn = document.getElementById('resetFilterBtn');
            if (resetBtn) resetBtn.classList.add('hidden');
            scrollToCardSection();
        }

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
            document.querySelectorAll('.custom-dropdown').forEach(d => d.style.zIndex = 'auto');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                dropdown.style.zIndex = '50';
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('.custom-dropdown').forEach(d => d.style.zIndex = 'auto');
            }
        });

        function selectOption(inputId, value, isPrice = false) {
            const input = document.getElementById(inputId);
            input.value = isPrice ? formatRibuan(value) : value;
            input.setAttribute('readonly', 'readonly');
            
            const dropdown = input.closest('.custom-dropdown');
            if (dropdown) {
                dropdown.querySelector('.dropdown-menu').classList.add('hidden');
                dropdown.style.zIndex = 'auto';
            }
        }

        function promptAddNewOption(inputId, labelName, placeholderText, exampleText, isPrice = false) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.custom-dropdown').forEach(d => d.style.zIndex = 'auto');

            // Dibuat dengan wrapper sendiri agar input benar-benar berada di tengah
            // dan ukurannya mengikuti card/modal, sama seperti input fitur di bawahnya.
            Swal.fire({
                title: `<span class="font-display font-extrabold text-slate-800 text-xl">Tambah ${labelName} Baru</span>`,
                html: `
                    <div class="w-full text-left">
                        <p class="text-xs font-semibold text-slate-500 mb-4">
                            Masukkan ${labelName.toLowerCase()} baru ${exampleText ? '(contoh: ' + exampleText + ')' : ''}:
                        </p>

                        <div class="w-full px-0">
                            <input
                                id="swal_new_option"
                                type="text"
                                autocomplete="off"
                                autocapitalize="off"
                                autocorrect="off"
                                placeholder="${placeholderText || `Masukkan ${labelName.toLowerCase()}...`}"
                                class="!box-border !block !w-full !m-0 border-2 border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 text-center bg-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all"
                            >
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                    htmlContainer: '!w-full !m-0 !p-0',
                    confirmButton: 'px-6 py-2.5 bg-emerald-500 text-white font-bold text-sm rounded-xl hover:bg-emerald-600 transition-all shadow-md mx-1 cursor-pointer',
                    cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                },
                didOpen: () => {
                    const input = document.getElementById('swal_new_option');
                    if (input) {
                        input.focus();
                        input.addEventListener('keydown', (event) => {
                            if (event.key === 'Enter') {
                                event.preventDefault();
                                Swal.clickConfirm();
                            }
                        });
                    }
                },
                preConfirm: () => {
                    const input = document.getElementById('swal_new_option');
                    const value = input ? input.value.trim() : '';

                    if (!value) {
                        Swal.showValidationMessage(`Harap isi ${labelName.toLowerCase()} terlebih dahulu!`);
                        return false;
                    }

                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let newValue = result.value;
                    let rawValue = newValue;
                    if (isPrice) {
                        newValue = formatRibuan(newValue);
                        rawValue = newValue.replace(/\./g, '');
                    }

                    const dropdown = document.getElementById(inputId).closest('.custom-dropdown');
                    if (dropdown) {
                        const optionsList = dropdown.querySelector('.options-list');
                        if (optionsList) {
                            const hoverBg = inputId.startsWith('edit_') ? 'hover:bg-blue-50' : 'hover:bg-emerald-50';
                            
                            const newItem = document.createElement('div');
                            newItem.className = `dropdown-item flex items-center justify-between px-3.5 py-2 ${hoverBg} cursor-pointer text-xs font-semibold text-slate-700`;
                            
                            let displayVal = newValue;
                            let iconHtml = '';

                            if (inputId.includes('name')) {
                                iconHtml = '<i class="fa-solid fa-star text-sky-500"></i> ';
                            }
                            if (inputId.includes('duration') && !displayVal.toLowerCase().includes('hari')) {
                                displayVal += ' Hari';
                            }
                            if (inputId.includes('max_upload') && !displayVal.toLowerCase().includes('karya') && displayVal !== '999') {
                                displayVal += ' Karya';
                            }

                            newItem.setAttribute('onclick', `selectOption('${inputId}', '${rawValue}', ${isPrice})`);
                            newItem.innerHTML = `
                                <div class="flex items-center gap-2">
                                    ${iconHtml}<span>${displayVal}</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); removeDropdownItem(this)" class="text-slate-300 hover:text-red-500 p-1"><i class="fa-solid fa-xmark"></i></button>
                            `;
                            optionsList.appendChild(newItem);
                        }
                    }

                    selectOption(inputId, rawValue, isPrice);

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

        // Fitur benefit sekarang memakai input sederhana agar tidak miring / tidak bergeser dari card.
        let customFeatureIndex = 100;

        function promptAddNewCheckbox(mode = 'add') {
            const themeColor = mode === 'edit' ? 'blue' : 'emerald';

            Swal.fire({
                title: `<span class="font-display font-extrabold text-slate-800 text-xl">Tambah Benefit Baru</span>`,
                html: `
                    <div class="text-left">
                        <label class="text-[11px] font-bold text-slate-700 block mb-1.5">Nama Benefit</label>
                        <input id="swal_feat_name" type="text" placeholder="Misal: Bebas Biaya Layanan" class="w-full border-2 border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:border-${themeColor}-500 focus:outline-none transition not-italic">
                        <label class="text-[11px] font-bold text-slate-700 block mt-3 mb-1.5">Nilai / Keterangan (opsional)</label>
                        <input id="swal_feat_value" type="text" placeholder="Misal: 10 kali / Aktif" class="w-full border-2 border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:border-${themeColor}-500 focus:outline-none transition not-italic">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Tambah Benefit',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                    confirmButton: `px-6 py-2.5 bg-${themeColor}-600 text-white font-bold text-sm rounded-xl hover:bg-${themeColor}-700 transition-all shadow-md mx-1 cursor-pointer`,
                    cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                },
                preConfirm: () => {
                    const name = document.getElementById('swal_feat_name').value.trim();
                    const value = document.getElementById('swal_feat_value').value.trim();
                    if (!name) {
                        Swal.showValidationMessage('Nama benefit wajib diisi!');
                        return false;
                    }
                    return { name, value };
                }
            }).then((res) => {
                if (res.isConfirmed && res.value) {
                    addCustomBenefitToContainer(mode, res.value.name, res.value.value);
                }
            });
        }

        function addCustomBenefitToContainer(mode, name, value = '') {
            customFeatureIndex++;
            const containerId = mode === 'edit' ? 'custom_features_container_edit' : 'custom_features_container_add';
            const container = document.getElementById(containerId);
            if (!container) return;

            const itemDiv = document.createElement('div');
            itemDiv.className = 'custom-feat-item grid grid-cols-1 sm:grid-cols-[1fr_160px_32px] gap-2 items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm';
            const safeName = name.replace(/"/g, '&quot;');
            const safeValue = value.replace(/"/g, '&quot;');
            itemDiv.innerHTML = `
                <input type="hidden" name="custom_features[${customFeatureIndex}][checked]" value="1">
                <input type="text" name="custom_features[${customFeatureIndex}][name]" value="${safeName}" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-${mode === 'edit' ? 'blue' : 'emerald'}-500/40 not-italic" placeholder="Nama benefit">
                <input type="text" name="custom_features[${customFeatureIndex}][val]" value="${safeValue}" class="w-full text-xs font-semibold border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-${mode === 'edit' ? 'blue' : 'emerald'}-500/40 not-italic" placeholder="Nilai / keterangan">
                <button type="button" onclick="this.closest('.custom-feat-item').remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition" title="Hapus Benefit"><i class="fa-solid fa-trash text-xs"></i></button>
            `;
            container.appendChild(itemDiv);
        }

        // Kompatibilitas jika fungsi lama masih dipanggil dari data benefit.
        function addCustomCheckboxToContainer(mode, name, hasValue = false, defaultVal = '', isChecked = true) {
            addCustomBenefitToContainer(mode, name, hasValue ? defaultVal : '');
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
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('add_verified_badge').value = '0';
            document.getElementById('add_priority_cs').value = '0';
            document.getElementById('add_custom_benefit').value = '';
            
            document.querySelectorAll('.custom-dropdown').forEach(d => d.style.zIndex = 'auto');

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
            const maxProducts = document.getElementById('add_val_max_products').value.trim();
            const maxAds = document.getElementById('add_val_max_ads').value.trim();

            if (!name || !price || !duration || !maxUpload || !maxProducts || maxAds === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan isi Nama, Harga, Durasi, Maksimal Upload, jumlah Jasa/Barang, dan jumlah Iklan.',
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

                ['edit_val_max_products', 'edit_val_max_ads'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
                document.getElementById('edit_verified_badge').value = '0';
                document.getElementById('edit_priority_cs').value = '0';
                const customBenefit = document.getElementById('edit_custom_benefit');
                if (customBenefit) customBenefit.value = '';

                if (membership.benefit) {
                    const benefitItems = membership.benefit.split(' | ');
                    benefitItems.forEach(item => {
                        const trimmed = item.trim();
                        if (trimmed.startsWith('Maksimal Upload:')) {
                            // Handled
                        } else if (trimmed.startsWith('Batas Jasa/Barang:') || trimmed.startsWith('Batas Jumlah Jasa:')) {
                            const valMatch = trimmed.match(/\d+/);
                            const inputVal = document.getElementById('edit_val_max_products');
                            if (inputVal) inputVal.value = valMatch ? valMatch[0] : '';
                        } else if (trimmed.startsWith('Iklan Promosi:') || trimmed.startsWith('Batas Jumlah Iklan:')) {
                            const valMatch = trimmed.match(/\d+/);
                            const inputVal = document.getElementById('edit_val_max_ads');
                            if (inputVal) inputVal.value = valMatch ? valMatch[0] : '';
                        } else if (trimmed === 'Lencana Kreator Terverifikasi') {
                            document.getElementById('edit_verified_badge').value = '1';
                        } else if (trimmed === 'Dukungan CS Prioritas 24/7' || trimmed === 'Dukungan Prioritas CS 24/7') {
                            document.getElementById('edit_priority_cs').value = '1';
                        } else if (trimmed !== 'Fitur standar keanggotaan') {
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
            
            document.querySelectorAll('.custom-dropdown').forEach(d => d.style.zIndex = 'auto');
            
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
            const maxProducts = document.getElementById('edit_val_max_products').value.trim();
            const maxAds = document.getElementById('edit_val_max_ads').value.trim();

            if (!name || !price || !duration || !maxUpload || !maxProducts || maxAds === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan isi bidang Nama, Harga, Durasi, Maksimal Upload, jumlah Jasa/Barang, dan jumlah Iklan.',
                    confirmButtonColor: '#0EA5E9'
                });
                return;
            }

            document.getElementById('editForm').submit();
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Manajemen Notifikasi</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
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
        .target-radio:checked + span { border-color: #10b981; background-color: rgba(16,185,129,0.08); color: #065f46; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR FULL -->
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

            @php $admin = auth()->user(); $initials = collect(explode(' ', trim($admin->name ?? 'Admin')))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode(''); @endphp
            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $initials ?: 'A' }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $admin->name ?? 'Admin' }}</p>
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
                            <i class="fa-solid fa-headset text-[10px] text-sky-200 w-3 text-center"></i> Akun & Layanan CS
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
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

                <a href="{{ route('admin.security.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-shield-halved w-4 text-center text-white"></i><span>Keamanan System</span>
                </a>

                <!-- MENU NOTIFIKASI (AKTIF DI HALAMAN INI) -->
                <a href="{{ route('admin.notifikasi') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl active-menu transition-all group mt-1">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center text-white"></i>
                        <span>Notifikasi</span>
                    </div>
                    @if($notifications->total() > 0)
                        <span class="bg-amber-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">
                            {{ $notifications->total() }}
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

        <!-- KONTEN UTAMA -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Manajemen Notifikasi</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kirim pengumuman ke semua pengguna atau ke pengguna tertentu.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- SWEETALERT FLASH NOTIFICATION -->
                @if (session('success'))
                    <script>Swal.fire({icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false});</script>
                @endif
                @if (session('error'))
                    <script>Swal.fire({icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444'});</script>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
                    <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Notifikasi Terkirim</span>
                                <div class="text-3xl font-black text-slate-900 mt-1">{{ $notifications->total() }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md">
                                <i class="fa-solid fa-bell text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Total Pengguna Terdaftar</span>
                                <div class="text-3xl font-black text-slate-900 mt-1">{{ ($allUsers ?? collect())->count() }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md">
                                <i class="fa-solid fa-users text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800">Daftar Notifikasi Terkirim</h3>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Riwayat pengumuman yang sudah dikirim ke pengguna.</p>
                        </div>

                        <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Notifikasi
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Judul Notifikasi</th>
                                    <th class="py-4 px-6">Target</th>
                                    <th class="py-4 px-6">Deskripsi</th>
                                    <th class="py-4 px-6">Tanggal Dibuat</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($notifications as $notification)
                                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                                        <td class="py-3 px-6 font-bold text-slate-800 text-xs">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-sm border border-sky-200 shadow-sm shrink-0">
                                                    <i class="fa-solid fa-bullhorn"></i>
                                                </div>
                                                <span>{{ $notification->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-xs">
                                            @if(is_null($notification->user_id))
                                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-600 border border-sky-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                                    <i class="fa-solid fa-users"></i> Semua Pengguna
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 border border-purple-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                                    <i class="fa-solid fa-user"></i> {{ $notification->targetUser->name ?? 'Pengguna Dihapus' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $notification->description }}</td>
                                        <td class="py-3 px-6 text-xs font-semibold text-slate-500">
                                            {{ $notification->created_at ? $notification->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- TOMBOL DETAIL (READ-ONLY, tidak ada endpoint update) -->
                                                <button type="button"
                                                        onclick='openDetailModal(@json($notification))'
                                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center"
                                                        title="Lihat Detail Notifikasi">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <!-- TOMBOL HAPUS -->
                                                <form action="{{ route('admin.notifikasi.delete', $notification->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this, '{{ $notification->name }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center" title="Hapus Notifikasi">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-semibold">Belum ada data notifikasi yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($notifications->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL KIRIM NOTIFIKASI -->
    <div id="addNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-emerald-500 max-h-[90vh] overflow-y-auto" id="addModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50 rounded-t-xl sticky top-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-paper-plane text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Kirim Notifikasi Baru</h3>
                        <p class="text-[10px] font-semibold text-emerald-600">Buat pengumuman baru untuk pengguna.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="addForm" action="{{ route('admin.notifikasi.send') }}" method="POST" class="p-5 space-y-4">
                @csrf

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Target Penerima</label>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="target_type" value="semua" checked onchange="toggleTargetUser()" class="target-radio sr-only peer">
                            <span class="flex items-center justify-center gap-2 border-2 border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                                <i class="fa-solid fa-users"></i> Semua Pengguna
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="target_type" value="tertentu" onchange="toggleTargetUser()" class="target-radio sr-only peer">
                            <span class="flex items-center justify-center gap-2 border-2 border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                                <i class="fa-solid fa-user"></i> Pengguna Tertentu
                            </span>
                        </label>
                    </div>
                </div>

                <div id="userSelectWrapper" class="hidden">
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Pilih Pengguna</label>
                    <select name="user_id" id="add_user_id" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach(($allUsers ?? collect()) as $u)
                            <option value="{{ $u->id_user }}">{{ $u->name }} ({{ $u->role->role_name ?? '-' }}) — {{ $u->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Judul Notifikasi</label>
                    <input type="text" name="title" id="add_title" placeholder="Contoh: Pemeliharaan Sistem..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Deskripsi</label>
                    <textarea name="description" id="add_description" rows="4" placeholder="Tuliskan isi pengumuman notifikasi di sini..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all"></textarea>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#065f46] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#065f46] transition-all cursor-pointer">
                        Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL NOTIFIKASI (READ-ONLY) -->
    <div id="detailNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-600" id="detailModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-bell text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Detail Notifikasi</h3>
                        <p class="text-[10px] font-semibold text-blue-600">Informasi lengkap notifikasi terkirim.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Target Penerima</label>
                    <p id="detailTarget" class="mt-1 text-sm font-bold text-slate-800">-</p>
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Judul Notifikasi</label>
                    <p id="detailTitle" class="mt-1 text-sm font-bold text-slate-800">-</p>
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Deskripsi</label>
                    <p id="detailDescription" class="mt-1 text-sm text-slate-600 whitespace-pre-line">-</p>
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Tanggal Dibuat</label>
                    <p id="detailDate" class="mt-1 text-xs font-semibold text-slate-500">-</p>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="closeDetailModal()" class="w-full py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIC JAVASCRIPT -->
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

        // --- KIRIM NOTIFIKASI MODAL LOGIC ---
        const addModal = document.getElementById('addNotificationModal');
        const addModalContent = document.getElementById('addModalContent');

        function toggleTargetUser() {
            const isTertentu = document.querySelector('input[name="target_type"]:checked').value === 'tertentu';
            document.getElementById('userSelectWrapper').classList.toggle('hidden', !isTertentu);
            if (!isTertentu) document.getElementById('add_user_id').value = '';
        }

        function openAddModal() {
            document.getElementById('addForm').reset();
            document.getElementById('userSelectWrapper').classList.add('hidden');
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
            const targetType = document.querySelector('input[name="target_type"]:checked').value;
            const userId = document.getElementById('add_user_id').value;
            const title = document.getElementById('add_title').value.trim();
            const desc = document.getElementById('add_description').value.trim();

            if (!title || !desc) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan isi judul dan deskripsi notifikasi.', confirmButtonColor: '#0EA5E9' });
                return;
            }
            if (targetType === 'tertentu' && !userId) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih pengguna tujuan notifikasi.', confirmButtonColor: '#0EA5E9' });
                return;
            }
            document.getElementById('addForm').submit();
        }

        // --- DETAIL MODAL LOGIC ---
        const detailModal = document.getElementById('detailNotificationModal');
        const detailModalContent = document.getElementById('detailModalContent');

        function openDetailModal(notification) {
            if (notification) {
                document.getElementById('detailTitle').textContent = notification.name || '-';
                document.getElementById('detailDescription').textContent = notification.description || '-';

                if (notification.user_id) {
                    document.getElementById('detailTarget').textContent = notification.target_user
                        ? notification.target_user.name
                        : 'Pengguna Dihapus';
                } else {
                    document.getElementById('detailTarget').textContent = 'Semua Pengguna (Broadcast)';
                }

                document.getElementById('detailDate').textContent = notification.created_at
                    ? new Date(notification.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })
                    : '-';
            }

            detailModal.classList.remove('hidden');
            setTimeout(() => {
                detailModal.classList.remove('opacity-0');
                detailModalContent.classList.remove('scale-95');
                detailModalContent.classList.add('scale-100');
            }, 10);
        }

        function closeDetailModal() {
            detailModal.classList.add('opacity-0');
            detailModalContent.classList.remove('scale-100');
            detailModalContent.classList.add('scale-95');
            setTimeout(() => { detailModal.classList.add('hidden'); }, 300);
        }

        // --- CONFIRM DELETE ---
        function confirmDelete(button, name) {
            Swal.fire({
                title: 'Hapus Notifikasi?',
                text: `Notifikasi "${name}" akan dihapus permanen.`,
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
    </script>
</body>
</html>
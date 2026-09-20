<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Notifikasi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
            colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', skyDeeper: '#082C48', skyPale: '#EFF8FF', coral: '#FF7A59', mint: '#10B981', ink: '#0F2A44' }
        } }
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
    .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .card-hover:hover { transform: scale(1.015) translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
</style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white overflow-hidden flex items-center justify-center shadow-lg shadow-skyDeep/20"><img src="{{ asset('image/logo.png') }}" alt="KaryaKu Logo" class="w-full h-full object-contain"></div>
                <div>
                    <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">KaryaKu</h1>
                    <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">CS Panel</span>
                </div>
            </div>
            <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        @php
            $csUser = auth()->user();
            $csName = $csUser->name ?? 'CS';
            $csInitials = collect(explode(' ', trim($csName)))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
        @endphp
        <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
            <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $csInitials ?: 'CS' }}</div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ $csName }}</p>
                <span class="text-[10px] text-sky-200">Customer Service</span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>

            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-chart-pie w-4 text-center group-hover:text-white transition-colors"></i><span>Beranda</span>
            </a>

            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan & Moderasi</span>
            </a>

            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Cek Transaksi</span>
            </a>

            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl active-menu transition-all duration-200">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bell w-4 text-center"></i><span>Notifikasi</span>
                </div>
                @if($unreadCount > 0)
                    <span class="bg-amber-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </nav>

        <div class="p-4 border-t border-white/15">
            <form method="POST" action="{{ route('logout') }}">
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
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Notifikasi dari Admin</h2>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Pengumuman & instruksi terbaru yang dikirim oleh Admin.</p>
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
                            <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Notifikasi Diterima</span>
                            <div class="text-3xl font-black text-slate-900 mt-1">{{ $notifications->total() }}</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md">
                            <i class="fa-solid fa-bell text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Belum Dibaca</span>
                            <div class="text-3xl font-black text-slate-900 mt-1">{{ $unreadCount }}</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md">
                            <i class="fa-solid fa-envelope text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN TABLE AREA -->
            <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Daftar Notifikasi</h3>
                        <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Pengumuman broadcast dan notifikasi khusus untuk akun Anda.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                <th class="py-4 px-6">Judul Notifikasi</th>
                                <th class="py-4 px-6">Sumber</th>
                                <th class="py-4 px-6">Deskripsi</th>
                                <th class="py-4 px-6">Tanggal Diterima</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse ($notifications as $notification)
                                @php($isNew = $notification->created_at && $notification->created_at->greaterThan(now()->subDays(3)))
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3 px-6 font-bold text-slate-800 text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-sm border border-sky-200 shadow-sm shrink-0">
                                                <i class="fa-solid fa-bullhorn"></i>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span>{{ $notification->name }}</span>
                                                @if($isNew)<span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-red-50 text-red-600 border border-red-200">Baru</span>@endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-xs">
                                        @if(is_null($notification->user_id))
                                            <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-600 border border-sky-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                                <i class="fa-solid fa-users"></i> Broadcast Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 border border-purple-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                                <i class="fa-solid fa-user"></i> Khusus Akun Anda
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $notification->description }}</td>
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-500">
                                        {{ $notification->created_at ? $notification->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button"
                                                    onclick='openDetailModal(@json($notification))'
                                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center"
                                                    title="Lihat Detail Notifikasi">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-semibold">Belum ada notifikasi dari Admin.</td>
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

<!-- MODAL DETAIL NOTIFIKASI (READ-ONLY) -->
<div id="detailNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-600" id="detailModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-bell text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Detail Notifikasi</h3>
                    <p class="text-[10px] font-semibold text-blue-600">Informasi lengkap notifikasi yang diterima.</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div class="p-5 space-y-4">
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Sumber</label>
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
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Tanggal Diterima</label>
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

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
    sidebarToggleBtn?.addEventListener('click', toggleSidebar);
    sidebarCloseBtn?.addEventListener('click', toggleSidebar);
    sidebarOverlay?.addEventListener('click', toggleSidebar);

    // --- DETAIL MODAL LOGIC ---
    const detailModal = document.getElementById('detailNotificationModal');
    const detailModalContent = document.getElementById('detailModalContent');

    function openDetailModal(notification) {
        if (notification) {
            document.getElementById('detailTitle').textContent = notification.name || '-';
            document.getElementById('detailDescription').textContent = notification.description || '-';
            document.getElementById('detailTarget').textContent = notification.user_id
                ? 'Khusus Akun Anda'
                : 'Broadcast Admin (Semua Pengguna)';
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
</script>
</body>
</html>

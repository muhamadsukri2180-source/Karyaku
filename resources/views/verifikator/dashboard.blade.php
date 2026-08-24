<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Dashboard Verifikator</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR VERIFIKATOR (PAS 5 MENU UTAMA) -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Verifikator Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Verifikator' }}</p>
                    <p class="text-[10px] text-sky-200 uppercase font-bold tracking-wider">Verifikator Team</p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-2">Navigasi Utama</p>

                <!-- 1. Dashboard -->
                <a href="{{ route('verifikator.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                    <i class="fa-solid fa-chart-pie w-4 text-center text-white"></i><span>Dashboard</span>
                </a>

                <!-- 2. Verifikasi Identitas -->
                <a href="{{ route('verifikator.identitas') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-id-card-clip w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Identitas</span>
                    </div>
                    @if(($pendingKtp ?? 0) > 0)
                        <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">{{ $pendingKtp }}</span>
                    @endif
                </a>

                <!-- 3. Verifikasi Produk -->
                <a href="{{ route('verifikator.produk') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Produk</span>
                    </div>
                    @if(($pendingProduk ?? 0) > 0)
                        <span class="bg-amber-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $pendingProduk }}</span>
                    @endif
                </a>

                <!-- 4. Verifikasi Pembayaran -->
                <a href="{{ route('verifikator.pembayaran') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Pembayaran</span>
                    </div>
                    @if(($pendingPembayaran ?? 0) > 0)
                        <span class="bg-emerald-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $pendingPembayaran }}</span>
                    @endif
                </a>

                <!-- 5. Laporan Pelanggaran -->
                <a href="{{ route('verifikator.laporan') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan Pelanggaran</span>
                    </div>
                    @if(($laporanMasuk ?? 0) > 0)
                        <span class="bg-rose-400 text-slate-900 text-[10px] px-2 py-0.5 rounded-full font-extrabold shadow-sm">{{ $laporanMasuk }}</span>
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Dashboard Verifikator</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Ringkasan statistik antrean & verifikasi pendaftaran penjual.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- 4 KPI RINGKASAN ANTREAN -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- Pending KTP -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending KTP</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingKtp ?? 0 }}</h3>
                            <a href="{{ route('verifikator.identitas') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Proses Identitas &rarr;</a>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 border border-red-200 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-id-card-clip"></i>
                        </div>
                    </div>

                    <!-- Pending Produk -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending Produk</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingProduk ?? 0 }}</h3>
                            <a href="{{ route('verifikator.produk') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Cek Produk Baru &rarr;</a>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                    </div>

                    <!-- Pending Pembayaran -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pembayaran</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingPembayaran ?? 0 }}</h3>
                            <a href="{{ route('verifikator.pembayaran') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Cek Resi Transfer &rarr;</a>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>

                    <!-- Laporan Masuk -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan Masuk</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $laporanMasuk ?? 0 }}</h3>
                            <a href="{{ route('verifikator.laporan') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Tinjau Pengaduan &rarr;</a>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 border border-rose-200 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>

                </div>

                <!-- STATISTIK & TABEL ANTREAN PENDAFTARAN PENJUAL -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Kartu Performa Keputusan -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base font-display">Statistik Verifikasi</h3>
                            <p class="text-xs text-slate-500 mt-1">Total keputusan tindakan pengajuan yang telah diproses.</p>

                            <div class="space-y-4 mt-6">
                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Disetujui</span>
                                        <span class="text-slate-800 font-extrabold">{{ $approvedCount ?? 0 }}</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ (($approvedCount ?? 0) + ($rejectedCount ?? 0)) > 0 ? (($approvedCount ?? 0) / (($approvedCount ?? 0) + ($rejectedCount ?? 0))) * 100 : 0 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-rose-500"><i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak</span>
                                        <span class="text-slate-800 font-extrabold">{{ $rejectedCount ?? 0 }}</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ (($approvedCount ?? 0) + ($rejectedCount ?? 0)) > 0 ? (($rejectedCount ?? 0) / (($approvedCount ?? 0) + ($rejectedCount ?? 0))) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 mt-6 text-[11px] text-slate-500 font-medium">
                            <i class="fa-solid fa-shield-halved text-sky-500 mr-1"></i> Tim Verifikator Karyaku
                        </div>
                    </div>

                    <!-- Tabel Antrean Pendaftaran Penjual -->
                    <div class="lg:col-span-2 bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                                <h3 class="font-extrabold text-slate-900 text-base font-display">Verifikasi Pendaftaran Penjual</h3>
                                <span class="text-xs font-bold text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">
                                    {{ $pending->total() ?? count($pending) }} Menunggu
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                            <th class="py-4 px-6">Nama & Email</th>
                                            <th class="py-4 px-6">Paket</th>
                                            <th class="py-4 px-6">Pembayaran</th>
                                            <th class="py-4 px-6">Status</th>
                                            <th class="py-4 px-6 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs divide-y divide-slate-100">
                                        @forelse ($pending as $registration)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-3.5 px-6">
                                                <p class="font-bold text-slate-800 text-xs">{{ $registration->user->name ?? '-' }}</p>
                                                <p class="text-[11px] text-slate-400 font-medium">{{ $registration->user->email ?? '-' }}</p>
                                            </td>
                                            <td class="py-3.5 px-6 font-semibold text-slate-700">
                                                <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2.5 py-0.5 rounded-lg font-bold text-[10px]">
                                                    {{ $registration->membership->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-6 font-bold text-slate-800">
                                                Rp {{ number_format($registration->payment_amount ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3.5 px-6">
                                                <span class="bg-amber-50 text-amber-800 border border-amber-200/60 px-2.5 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-clock text-[9px]"></i> {{ ucfirst($registration->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-6 text-center">
                                                <a href="{{ route('verifikator.pendaftaran.show', $registration->id_identity_verification) }}"
                                                   class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[11px] shadow-sm transition-all">
                                                    <i class="fa-solid fa-[magnifying-glass] text-[10px]"></i> Periksa
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-10 text-slate-400 font-semibold text-xs">
                                                <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                                                Belum ada pendaftaran penjual yang perlu diverifikasi.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- PAGINASI -->
                        @if(method_exists($pending, 'hasPages') && $pending->hasPages())
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $pending->links() }}
                        </div>
                        @endif

                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
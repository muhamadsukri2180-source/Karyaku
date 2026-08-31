<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Karyaku - Laporan & Moderasi CS</title>
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
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'], 
                        display: ['Sora', 'sans-serif'] 
                    },
                    colors: { 
                        sky: '#0EA5E9', 
                        skyHover: '#0284C7', 
                        skyDeep: '#0B3D62', 
                        skyPale: '#EFF8FF' 
                    }
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
        @media (max-width: 1023px) { 
            #sidebar.closed { transform: translateX(-100%); } 
            #sidebar.open { transform: translateX(0); } 
        }
        .tab-btn.active-tab { 
            background: #0EA5E9; 
            color: #ffffff; 
            box-shadow: 0 4px 12px rgba(14,165,233,0.3); 
            border-color: transparent; 
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR CS PANEL -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">CS Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            @php
                $csUser = auth()->user();
                $csName = $csUser->name ?? 'CS Staff';
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

                <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <a href="{{ route('cs.tiket') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all">
                    <i class="fa-solid fa-headset w-4 text-center"></i><span>Tiket Bantuan</span>
                </a>

                <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center"></i><span>Laporan & Moderasi</span>
                </a>

                <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all">
                    <i class="fa-solid fa-receipt w-4 text-center"></i><span>Cek Transaksi</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

                <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all">
                    <i class="fa-solid fa-bell w-4 text-center"></i><span>Notifikasi</span>
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

        <!-- KONTEN UTAMA CS -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Laporan & Moderasi CS</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Tinjau laporan pengaduan, pengajuan banding pemblokiran, serta riwayat penanganan.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- TAB SWITCHER -->
                <div class="flex flex-wrap items-center gap-2 bg-white border border-sky-200 rounded-2xl p-1.5 w-full sm:w-max shadow-sm">
                    <button type="button" onclick="switchTab('pengguna')" id="tabBtnPengguna" class="tab-btn active-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        <i class="fa-solid fa-user-xmark mr-1"></i> Pelanggaran Pengguna
                    </button>
                    <button type="button" onclick="switchTab('penjual')" id="tabBtnPenjual" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer">
                        <i class="fa-solid fa-shop-slash mr-1"></i> Pelanggaran Produk
                    </button>
                    <button type="button" onclick="switchTab('banding')" id="tabBtnBanding" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-amber-500"></i>
                        <span>Laporan Banding Pemblokiran</span>
                        @if(!empty($pendingAppealCount) && $pendingAppealCount > 0)
                            <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black shadow-xs">
                                {{ $pendingAppealCount }}
                            </span>
                        @endif
                    </button>
                    <button type="button" onclick="switchTab('riwayat')" id="tabBtnRiwayat" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Moderasi
                    </button>
                </div>

                <!-- TAB 1: LAPORAN PENGGUNA -->
                <div id="tabPengguna" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden block">
                    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                        <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Laporan Pengguna</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Pelapor</th>
                                    <th class="py-4 px-6">Dilaporkan</th>
                                    <th class="py-4 px-6">Alasan</th>
                                    <th class="py-4 px-6">Deskripsi</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6">Tanggal</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($reportsUser as $report)
                                @php
                                $statusColor = match($report->status) {
                                    'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'escalated' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-amber-50 text-amber-700 border-amber-200',
                                };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-700">{{ $report->reporter->name ?? 'User #'.$report->user_id }}</td>
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-700">
                                        {{ $report->reportedUser->name ?? 'Laporan Umum' }}
                                    </td>
                                    <td class="py-3 px-6"><p class="text-xs text-slate-700 font-medium">{{ $report->reason }}</p></td>
                                    <td class="py-3 px-6"><p class="text-xs text-slate-600 w-48 truncate">{{ $report->description ?? '-' }}</p></td>
                                    <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                                    <td class="py-3 px-6 text-xs text-slate-600">{{ optional($report->created_at)->format('d M Y - H:i') }}</td>
                                    <td class="py-3 px-6">
                                        <div class="flex justify-center">
                                            @if(in_array($report->status, ['pending', 'escalated']))
                                            <button type="button" onclick="openTindakModal('user', '{{ $report->id_report }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                                                <i class="fa-solid fa-gavel"></i> Tindak Lanjut
                                            </button>
                                            @else
                                            <span class="text-[10px] text-slate-400 font-semibold">Sudah ditindak</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data laporan pengguna.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($reportsUser->hasPages())
                        <div class="p-4 border-t border-slate-100">{{ $reportsUser->appends(request()->query())->links() }}</div>
                    @endif
                </div>

                <!-- TAB 2: LAPORAN PRODUK -->
                <div id="tabPenjual" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
                    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                        <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Laporan Produk / Penjual</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Pelapor</th>
                                    <th class="py-4 px-6">Produk & Penjual</th>
                                    <th class="py-4 px-6">Alasan</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6">Tanggal</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($reportsProduk as $report)
                                @php
                                    $statusColor = match($report->status) {
                                        'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3 px-6 text-xs font-semibold text-slate-700">{{ $report->reporter->name ?? 'User #'.$report->user_id }}</td>
                                    <td class="py-3 px-6">
                                        <p class="text-xs font-semibold text-slate-700">{{ $report->product->title ?? 'Produk dihapus' }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $report->product->seller->name ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 px-6"><p class="text-xs text-slate-600 w-56 truncate">{{ $report->reason }}</p></td>
                                    <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                                    <td class="py-3 px-6 text-xs text-slate-600">{{ optional($report->created_at)->format('d M Y - H:i') }}</td>
                                    <td class="py-3 px-6">
                                        <div class="flex justify-center">
                                            @if($report->status === 'pending')
                                            <button type="button" onclick="openTindakModal('produk', '{{ $report->id_report }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                                                <i class="fa-solid fa-shield-halved"></i> Tindak Lanjut
                                            </button>
                                            @else
                                            <span class="text-[10px] text-slate-400 font-semibold">Sudah ditindak</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data laporan produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($reportsProduk->hasPages())
                        <div class="p-4 border-t border-slate-100">{{ $reportsProduk->appends(request()->query())->links() }}</div>
                    @endif
                </div>

                <!-- TAB 3: LAPORAN BANDING PEMBLOKIRAN AKUN -->
                <div id="tabBanding" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
                    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg font-display">Laporan Banding Pemblokiran Akun</h3>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Permohonan pembukaan blokir akun dari pengguna yang disuspend.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Pengguna</th>
                                    <th class="py-4 px-6">Alasan Suspend</th>
                                    <th class="py-4 px-6">Pembelaan User</th>
                                    <th class="py-4 px-6 text-center">Bukti Gambar</th>
                                    <th class="py-4 px-6">Status Banding</th>
                                    <th class="py-4 px-6">Tgl Pengajuan</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($reportsAppeal ?? [] as $appeal)
                                @php
                                    $appealStatusColor = match($appeal->status) {
                                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        default => 'bg-amber-100 text-amber-800 border-amber-200',
                                    };
                                    $appealStatusLabel = match($appeal->status) {
                                        'approved' => 'Disetujui (Aktif)',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu Review',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3.5 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                                {{ strtoupper(substr($appeal->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs">{{ $appeal->user->name ?? 'User #'.$appeal->user_id }}</p>
                                                <p class="text-[10px] text-slate-500">{{ $appeal->user->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <p class="text-xs text-slate-700 font-medium">{{ $appeal->user->suspend_reason ?? 'Pelanggaran ketentuan' }}</p>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium max-w-xs leading-relaxed">
                                            "{{ $appeal->reason }}"
                                        </p>
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        @if($appeal->proof_image)
                                            <button type="button" onclick="previewImage('{{ asset('storage/' . $appeal->proof_image) }}')" class="group relative inline-block rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition">
                                                <img src="{{ asset('storage/' . $appeal->proof_image) }}" alt="Bukti" class="w-14 h-14 object-cover group-hover:scale-110 transition duration-300">
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $appealStatusColor }}">
                                            {{ $appealStatusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-slate-600 font-medium">
                                        {{ optional($appeal->created_at)->format('d M Y - H:i') }}
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        @if($appeal->status === 'pending')
                                        <button type="button" onclick='openAppealModal(@json($appeal))' class="px-3.5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 mx-auto cursor-pointer">
                                            <i class="fa-solid fa-gavel"></i> Tindak Banding
                                        </button>
                                        @else
                                        <span class="text-[10px] text-slate-400 font-semibold">Telah Diproses</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada pengajuan banding pemblokiran akun.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(isset($reportsAppeal) && method_exists($reportsAppeal, 'hasPages') && $reportsAppeal->hasPages())
                        <div class="p-4 border-t border-slate-100">{{ $reportsAppeal->appends(request()->query())->links() }}</div>
                    @endif
                </div>

                <!-- TAB 4: RIWAYAT MODERASI -->
                <div id="tabRiwayat" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
                    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg font-display">Riwayat Moderasi Laporan</h3>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Daftar laporan pengaduan yang telah selesai ditindaklanjuti.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Target / Item</th>
                                    <th class="py-4 px-6">Alasan Laporan</th>
                                    <th class="py-4 px-6">Status Akhir</th>
                                    <th class="py-4 px-6">Catatan Petugas</th>
                                    <th class="py-4 px-6">Tanggal Penanganan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($riwayat ?? [] as $report)
                                @php
                                    $riwayatStatusColor = match($report->status) {
                                        'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3.5 px-6 text-xs font-semibold text-slate-800">
                                        {{ $report->product->title ?? ($report->reportedUser->name ?? 'Laporan Umum') }}
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-slate-600 font-medium">{{ $report->reason }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $riwayatStatusColor }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $report->admin_note ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-xs text-slate-500 font-medium">
                                        {{ optional($report->reviewed_at ?? $report->updated_at)->format('d M Y - H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada riwayat laporan yang ditindaklanjuti.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(isset($riwayat) && method_exists($riwayat, 'hasPages') && $riwayat->hasPages())
                        <div class="p-4 border-t border-slate-100">{{ $riwayat->appends(request()->query())->links() }}</div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL TINDAK LAPORAN (PENGGUNA / PRODUK) -->
    <div id="tindakModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4" id="tindakModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 font-display text-base" id="modalTitle">Tindak Lanjut Laporan</h3>
                <button type="button" onclick="closeTindakModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="#" method="POST" id="formTindak" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase">Pilih Aksi</label>
                    <select name="action" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700">
                        <option value="peringatan">Kirim Peringatan</option>
                        <option value="suspend">Suspend Akun / Takedown</option>
                        <option value="eskalasi">Eskalasi ke Admin</option>
                        <option value="abaikan">Abaikan Laporan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase">Catatan CS</label>
                    <textarea name="admin_notes" rows="3" required placeholder="Berikan catatan penanganan..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition">Simpan Tindakan</button>
            </form>
        </div>
    </div>

    <!-- MODAL TINDAK BANDING -->
    <div id="appealModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 font-display text-base">Tindak Lanjut Banding Akun</h3>
                <button type="button" onclick="closeAppealModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="#" method="POST" id="formAppeal" class="p-6 space-y-4">
                @csrf
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                    <p class="text-xs font-bold text-slate-800" id="appealUserName">-</p>
                    <p class="text-[11px] text-slate-600" id="appealUserReason">-</p>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase">Keputusan Banding <span class="text-red-500">*</span></label>
                    <select name="action" id="appealActionSelect" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800">
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="setujui" class="text-emerald-600 font-bold">✓ Setujui Banding (Buka Blokir & Aktifkan Akun)</option>
                        <option value="tolak" class="text-red-600 font-bold">✕ Tolak Banding (Tetap Suspend)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase">Catatan Keputusan (Opsional)</label>
                    <textarea name="admin_notes" rows="3" placeholder="Penjelasan keputusan untuk pengguna..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-700"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-md transition">Simpan Keputusan</button>
            </form>
        </div>
    </div>

    <!-- MODAL PREVIEW BUKTI GAMBAR -->
    <div id="imagePreviewModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4" onclick="closeImagePreview()">
        <div class="relative max-w-3xl max-h-[90vh] p-2" onclick="event.stopPropagation()">
            <button type="button" onclick="closeImagePreview()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-800 flex items-center justify-center font-bold shadow-lg"><i class="fa-solid fa-xmark"></i></button>
            <img id="modalPreviewImg" src="" alt="Bukti Gambar" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain bg-white">
        </div>
    </div>

    <!-- SCRIPTS INTEGRASI & LOGIKA TAB -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() { 
            sidebar.classList.toggle('open'); 
            sidebar.classList.toggle('closed'); 
            sidebarOverlay.classList.toggle('hidden'); 
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        function switchTab(tab) {
            document.getElementById('tabPengguna').style.display = tab === 'pengguna' ? 'block' : 'none';
            document.getElementById('tabPenjual').style.display = tab === 'penjual' ? 'block' : 'none';
            document.getElementById('tabBanding').style.display = tab === 'banding' ? 'block' : 'none';
            document.getElementById('tabRiwayat').style.display = tab === 'riwayat' ? 'block' : 'none';

            document.getElementById('tabBtnPengguna').classList.toggle('active-tab', tab === 'pengguna');
            document.getElementById('tabBtnPenjual').classList.toggle('active-tab', tab === 'penjual');
            document.getElementById('tabBtnBanding').classList.toggle('active-tab', tab === 'banding');
            document.getElementById('tabBtnRiwayat').classList.toggle('active-tab', tab === 'riwayat');
        }

        const tindakModal = document.getElementById('tindakModal');
        const formTindak = document.getElementById('formTindak');
        const modalTitle = document.getElementById('modalTitle');

        function openTindakModal(type, id) {
            formTindak.action = type === 'produk' ? "{{ url('cs/laporan/produk') }}/" + id : "{{ url('cs/laporan/user') }}/" + id;
            modalTitle.textContent = type === 'produk' ? "Tindak Lanjut Laporan Produk" : "Tindak Lanjut Laporan Pengguna";
            tindakModal.classList.remove('hidden');
        }
        function closeTindakModal() { tindakModal.classList.add('hidden'); }

        const appealModal = document.getElementById('appealModal');
        const formAppeal = document.getElementById('formAppeal');

        function openAppealModal(appeal) {
            formAppeal.action = "{{ url('cs/laporan/appeal') }}/" + appeal.id_appeal;
            document.getElementById('appealUserName').textContent = "Pemohon: " + (appeal.user ? appeal.user.name : 'User');
            document.getElementById('appealUserReason').textContent = "Alasan: \"" + appeal.reason + "\"";
            appealModal.classList.remove('hidden');
        }
        function closeAppealModal() { appealModal.classList.add('hidden'); }

        function previewImage(url) {
            document.getElementById('modalPreviewImg').src = url;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
        }
        function closeImagePreview() { document.getElementById('imagePreviewModal').classList.add('hidden'); }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
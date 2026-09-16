<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Cek Transaksi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    .modal-backdrop-custom { background: rgba(15, 23, 42, 0.55); }
    .tab-btn.active { background: #0EA5E9; color: #fff; box-shadow: 0 4px 10px rgba(14,165,233,0.35); }
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
                <i class="fa-solid fa-chart-pie w-4 text-center group-hover:text-white transition-colors"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan & Moderasi</span>
            </a>

            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all duration-200 relative">
                <i class="fa-solid fa-receipt w-4 text-center"></i><span>Cek Transaksi</span>
                @if($pendingSellerCount > 0)
                    <span class="ml-auto bg-coral text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingSellerCount }}</span>
                @endif
            </a>

            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i><span>Notifikasi</span>
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

    <main class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 backdrop-blur-xl border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div class="flex items-center gap-4">
                <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-300 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Cek Transaksi</h2>
                    <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">Pantau transaksi pesanan &amp; setujui transaksi pendaftaran penjual dari pembeli.</p>
                </div>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-semibold px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Tab switcher --}}
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="switchSection('pesanan')" id="tabBtnPesanan" class="tab-btn active px-4 py-2 rounded-xl text-xs font-bold bg-white border border-sky-200 text-slate-600 transition-all">
                    <i class="fa-solid fa-bag-shopping mr-1"></i> Transaksi Pesanan
                </button>
                <button type="button" onclick="switchSection('pendaftaran')" id="tabBtnPendaftaran" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold bg-white border border-sky-200 text-slate-600 transition-all relative">
                    <i class="fa-solid fa-user-tie mr-1"></i> Pendaftaran Penjual
                    @if($pendingSellerCount > 0)
                        <span class="ml-1 bg-coral text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingSellerCount }}</span>
                    @endif
                </button>
            </div>

            {{-- ======================= SECTION: TRANSAKSI PESANAN ======================= --}}
            <section id="section-pesanan" class="space-y-6">
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-800">Riwayat Transaksi Pesanan</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Mode baca saja — untuk verifikasi aduan &amp; sengketa pengguna.</p>
                        </div>
                        <form action="{{ route('cs.transaksi') }}" method="GET" class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pembeli..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                                <th class="py-3 px-5">Kode Order</th><th class="py-3 px-5">Pembeli</th><th class="py-3 px-5">Total</th><th class="py-3 px-5">Pembayaran</th><th class="py-3 px-5">Status</th><th class="py-3 px-5 text-center">Detail</th>
                            </tr></thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($orders as $order)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-5 text-xs font-semibold text-sky-700">{{ $order->kode_order }}</td>
                                    <td class="py-3 px-5 text-xs">{{ $order->buyer->name ?? '-' }}</td>
                                    <td class="py-3 px-5 text-xs font-bold" style="color:#FF7A59;">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-5"><span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">{{ ucfirst($order->payment_status) }}</span></td>
                                    <td class="py-3 px-5"><span class="text-[10px] font-bold px-2 py-1 rounded-md bg-sky-50 text-sky-700 border border-sky-200">{{ ucfirst($order->status) }}</span></td>
                                    <td class="py-3 px-5 text-center">
                                        <button type="button" onclick="showDetail('{{ $order->getKey() }}')" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white text-xs font-bold"><i class="fa-solid fa-eye"></i> Lihat</button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($orders->hasPages())<div class="p-4 border-t">{{ $orders->onEachSide(1)->links() }}</div>@endif
                </div>
            </section>

            {{-- ======================= SECTION: PENDAFTARAN PENJUAL ======================= --}}
            <section id="section-pendaftaran" class="space-y-6 hidden">
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-800">Transaksi Pendaftaran Penjual</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Setujui atau tolak transaksi pembayaran dari pembeli yang ingin menjadi penjual.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex bg-slate-100 rounded-xl p-1 text-xs font-bold">
                                <a href="{{ route('cs.transaksi', array_merge(request()->except('tab_pendaftaran'), ['tab_pendaftaran' => 'pending'])) }}"
                                class="px-3 py-1.5 rounded-lg {{ $tabPendaftaran === 'pending' ? 'bg-sky-600 text-white shadow' : 'text-slate-500 hover:text-sky-600' }}">Menunggu</a>
                                <a href="{{ route('cs.transaksi', array_merge(request()->except('tab_pendaftaran'), ['tab_pendaftaran' => 'history'])) }}"
                                class="px-3 py-1.5 rounded-lg {{ $tabPendaftaran === 'history' ? 'bg-sky-600 text-white shadow' : 'text-slate-500 hover:text-sky-600' }}">Riwayat</a>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                                <th class="py-3 px-5">Calon Penjual</th><th class="py-3 px-5">Paket</th><th class="py-3 px-5">Nominal</th><th class="py-3 px-5">Metode</th><th class="py-3 px-5">Status</th><th class="py-3 px-5 text-center">Aksi</th>
                            </tr></thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($sellerTransactions as $trx)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-5 text-xs">
                                        <p class="font-semibold text-slate-800">{{ $trx->user->name ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400">@safeEmail($trx->user->email ?? '-')</p>
                                    </td>
                                    <td class="py-3 px-5 text-xs">{{ $trx->membership->name ?? '-' }}</td>
                                    <td class="py-3 px-5 text-xs font-bold" style="color:#FF7A59;">Rp{{ number_format($trx->payment_amount ?? ($trx->membership->price ?? 0), 0, ',', '.') }}</td>
                                    <td class="py-3 px-5 text-xs">{{ $trx->payment_method ?? '-' }}</td>
                                    <td class="py-3 px-5">
                                        @php
                                            $statusColor = match($trx->status) {
                                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                                            };
                                        @endphp
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($trx->status) }}</span>
                                    </td>
                                    <td class="py-3 px-5 text-center">
                                        @if($trx->status === 'pending')
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('cs.transaksi.pendaftaran.approve', $trx->id_identity_verification) }}" method="POST" onsubmit="return confirm('Setujui transaksi pendaftaran penjual ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white text-xs font-bold"><i class="fa-solid fa-check"></i> Setujui</button>
                                                </form>
                                                <button type="button" onclick="openRejectModal({{ $trx->id_identity_verification }})" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white text-xs font-bold"><i class="fa-solid fa-xmark"></i> Tolak</button>
                                            </div>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-semibold">{{ $trx->verified_at ? \Carbon\Carbon::parse($trx->verified_at)->translatedFormat('d M Y H:i') : '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada transaksi pendaftaran penjual.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($sellerTransactions->hasPages())<div class="p-4 border-t">{{ $sellerTransactions->onEachSide(1)->links() }}</div>@endif
                </div>
            </section>
        </div>
    </main>
</div>

{{-- Modal detail pesanan --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop-custom p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900">Detail Pesanan</h3>
            <button type="button" onclick="closeDetail()" class="text-slate-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div id="detailContent" class="p-5 text-xs text-slate-700 space-y-2">
            <p class="text-center text-slate-400 py-6">Memuat data...</p>
        </div>
    </div>
</div>

{{-- Modal tolak pendaftaran penjual --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop-custom p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="p-5 border-b flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900">Tolak Transaksi Pendaftaran</h3>
                <button type="button" onclick="closeRejectModal()" class="text-slate-400"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <label class="text-xs font-bold text-slate-600">Alasan Penolakan</label>
                <textarea name="notes" required maxlength="500" rows="4" placeholder="Tulis alasan penolakan transaksi ini..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/30"></textarea>
            </div>
            <div class="p-5 border-t flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Tolak Transaksi</button>
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
    sidebarToggleBtn?.addEventListener('click', toggleSidebar);
    sidebarCloseBtn?.addEventListener('click', toggleSidebar);
    sidebarOverlay?.addEventListener('click', toggleSidebar);

    // ---- Tabs ----
    function switchSection(name) {
        document.getElementById('section-pesanan').classList.toggle('hidden', name !== 'pesanan');
        document.getElementById('section-pendaftaran').classList.toggle('hidden', name !== 'pendaftaran');
        document.getElementById('tabBtnPesanan').classList.toggle('active', name === 'pesanan');
        document.getElementById('tabBtnPendaftaran').classList.toggle('active', name === 'pendaftaran');
        localStorage.setItem('csTransaksiTab', name);
    }
    (function initTab() {
        const saved = localStorage.getItem('csTransaksiTab');
        if (saved === 'pendaftaran') switchSection('pendaftaran');
    })();

    // ---- Detail pesanan modal ----
    const baseUrl = "{{ url('cs/transaksi') }}";
    function showDetail(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        content.innerHTML = '<p class="text-center text-slate-400 py-6">Memuat data...</p>';

        fetch(`${baseUrl}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                let itemsHtml = (data.items || []).map(item => `
                    <div class="flex justify-between border-b border-slate-100 py-2">
                        <span>${item.product?.title ?? '-'}</span>
                        <span class="font-bold">Rp ${Number(item.subtotal ?? 0).toLocaleString('id-ID')}</span>
                    </div>
                `).join('');
                content.innerHTML = `
                    <p><span class="font-bold">Kode Order:</span> ${data.kode_order}</p>
                    <p><span class="font-bold">Pembeli:</span> ${data.buyer?.name ?? '-'}</p>
                    <p><span class="font-bold">Status:</span> ${data.status}</p>
                    <p><span class="font-bold">Pembayaran:</span> ${data.payment_status}</p>
                    <p><span class="font-bold">Total:</span> Rp ${Number(data.total_price ?? 0).toLocaleString('id-ID')}</p>
                    <div class="mt-3"><p class="font-bold mb-1">Item Pesanan:</p>${itemsHtml}</div>
                `;
            })
            .catch(() => { content.innerHTML = '<p class="text-center text-red-500 py-6">Gagal memuat detail.</p>'; });
    }
    function closeDetail() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }

    // ---- Reject seller-registration transaction modal ----
    const rejectBaseUrl = "{{ url('cs/transaksi/pendaftaran') }}";
    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `${rejectBaseUrl}/${id}/reject`;
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
        document.getElementById('rejectForm').reset();
    }
</script>
</body>
</html>

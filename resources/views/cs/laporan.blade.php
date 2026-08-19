<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Laporan & Moderasi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    tailwind.config = { theme: { extend: {
        fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'], display: ['Sora','sans-serif'] },
        colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
    } } }
</script>
<style>
    .active-menu { background: rgba(255,255,255,.2); border-left:4px solid #fff; color:#fff; }
    #sidebar { transition: transform .3s cubic-bezier(.4,0,.2,1); }
    @media (max-width:1023px){ #sidebar.closed{ transform:translateX(-100%);} #sidebar.open{ transform:translateX(0);} }
    .modal-backdrop-custom{ background: rgba(15,23,42,.55); }
</style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0 shadow-2xl">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold"><i class="fa-solid fa-layer-group"></i></div>
                <div><h1 class="font-display font-extrabold text-[17px] text-white">Karyaku</h1><span class="text-[9px] text-sky-200 uppercase tracking-widest">CS Panel</span></div>
            </div>
            <button id="sidebarCloseBtn" class="lg:hidden text-white/80"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4 pt-4">
            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span></a>
            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i><span>Laporan & Moderasi</span></a>
            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-receipt w-4 text-center"></i><span>Cek Transaksi</span></a>
            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-bell w-4 text-center"></i><span>Notifikasi</span></a>
        </nav>
        <div class="p-4 border-t border-white/15">
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white text-xs font-bold"><i class="fa-solid fa-power-off"></i> Keluar Sistem</button></form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-md">
            <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white border border-sky-300 flex items-center justify-center"><i class="fa-solid fa-bars"></i></button>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold font-display text-slate-900">Laporan & Moderasi</h2>
                <p class="text-[11px] text-slate-700 font-semibold mt-0.5">Tinjau pengaduan pengguna dan lakukan tindakan moderasi.</p>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-6">
            @if(session('success'))<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</div>@endif

            <div class="flex gap-2">
                <button type="button" onclick="showTab('tabPengguna')" id="btnTabPengguna" class="px-4 py-2 rounded-xl text-xs font-bold bg-sky-600 text-white shadow-sm">Laporan Pengguna</button>
                <button type="button" onclick="showTab('tabProduk')" id="btnTabProduk" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 border border-slate-200">Laporan Produk</button>
                <button type="button" onclick="showTab('tabRiwayat')" id="btnTabRiwayat" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 border border-slate-200">Riwayat</button>
            </div>

            <!-- TAB PENGGUNA -->
            <div id="tabPengguna" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                            <th class="py-3 px-5">Pelapor</th><th class="py-3 px-5">Dilaporkan</th><th class="py-3 px-5">Alasan</th><th class="py-3 px-5">Deskripsi</th><th class="py-3 px-5">Status</th><th class="py-3 px-5 text-center">Aksi</th>
                        </tr></thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($reportsUser as $report)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-5 text-xs font-semibold">{{ $report->reporter->name ?? '-' }}</td>
                                <td class="py-3 px-5 text-xs">{{ $report->reportedUser->name ?? 'Umum' }}</td>
                                <td class="py-3 px-5 text-xs">{{ $report->reason }}</td>
                                <td class="py-3 px-5 text-xs text-slate-600 w-48 truncate">{{ $report->description ?? '-' }}</td>
                                <td class="py-3 px-5"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $report->status === 'escalated' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">{{ $report->status === 'escalated' ? 'Dieskalasi' : 'Menunggu' }}</span></td>
                                <td class="py-3 px-5 text-center">
                                    <button type="button" onclick="openModal('user', '{{ $report->id_report }}')" class="px-3 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold"><i class="fa-solid fa-gavel"></i> Tindak</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Tidak ada laporan pengguna yang perlu ditangani.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reportsUser->hasPages())<div class="p-4 border-t">{{ $reportsUser->appends(request()->query())->links() }}</div>@endif
            </div>

            <!-- TAB PRODUK -->
            <div id="tabProduk" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                            <th class="py-3 px-5">Pelapor</th><th class="py-3 px-5">Produk & Penjual</th><th class="py-3 px-5">Alasan</th><th class="py-3 px-5">Status</th><th class="py-3 px-5 text-center">Aksi</th>
                        </tr></thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($reportsProduk as $report)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-5 text-xs font-semibold">{{ $report->reporter->name ?? '-' }}</td>
                                <td class="py-3 px-5 text-xs">
                                    <div class="font-semibold">{{ $report->product->title ?? 'Produk dihapus' }}</div>
                                    <div class="text-slate-500 text-[10px]">{{ $report->product->seller->name ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-5 text-xs">{{ $report->reason }}</td>
                                <td class="py-3 px-5"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $report->status === 'escalated' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">{{ $report->status === 'escalated' ? 'Dieskalasi' : 'Menunggu' }}</span></td>
                                <td class="py-3 px-5 text-center">
                                    <button type="button" onclick="openModal('produk', '{{ $report->id_report }}')" class="px-3 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold"><i class="fa-solid fa-shield-halved"></i> Tindak</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-slate-400 text-xs font-semibold">Tidak ada laporan produk yang perlu ditangani.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reportsProduk->hasPages())<div class="p-4 border-t">{{ $reportsProduk->appends(request()->query())->links() }}</div>@endif
            </div>

            <!-- TAB RIWAYAT -->
            <div id="tabRiwayat" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                            <th class="py-3 px-5">Target</th><th class="py-3 px-5">Alasan</th><th class="py-3 px-5">Status</th><th class="py-3 px-5">Catatan</th><th class="py-3 px-5">Tanggal</th>
                        </tr></thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($riwayat as $report)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-5 text-xs font-semibold">{{ $report->product->title ?? ($report->reportedUser->name ?? 'Umum') }}</td>
                                <td class="py-3 px-5 text-xs">{{ $report->reason }}</td>
                                <td class="py-3 px-5"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $report->status === 'dismissed' ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">{{ ucfirst($report->status) }}</span></td>
                                <td class="py-3 px-5 text-xs text-slate-600 w-48 truncate">{{ $report->admin_note ?? '-' }}</td>
                                <td class="py-3 px-5 text-xs text-slate-500">{{ optional($report->reviewed_at)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($riwayat->hasPages())<div class="p-4 border-t">{{ $riwayat->appends(request()->query())->links() }}</div>@endif
            </div>
        </div>
    </main>
</div>

<!-- MODAL TINDAK LANJUT -->
<div id="tindakModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop-custom p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900">Tindak Lanjut Laporan</h3>
            <button type="button" onclick="closeModal()" class="text-slate-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="tindakForm" method="POST" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-600 uppercase">Pilih Tindakan</label>
                <select name="action" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                    <option value="abaikan">Abaikan Laporan (tidak valid)</option>
                    <option value="teguran">Beri Teguran ke Pengguna</option>
                    <option value="sembunyikan" id="opsiSembunyikan">Sembunyikan Produk</option>
                    <option value="eskalasi">Eskalasi ke Admin (kasus berat)</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 uppercase">Catatan</label>
                <textarea name="admin_notes" rows="4" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm" placeholder="Jelaskan alasan/tindakan yang diambil..."></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl">Simpan Tindakan</button>
        </form>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    document.getElementById('sidebarToggleBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarCloseBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });

    function showTab(id) {
        ['tabPengguna','tabProduk','tabRiwayat'].forEach(t => document.getElementById(t).classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');
        ['btnTabPengguna','btnTabProduk','btnTabRiwayat'].forEach(b => {
            document.getElementById(b).className = 'px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 border border-slate-200';
        });
        const map = { tabPengguna: 'btnTabPengguna', tabProduk: 'btnTabProduk', tabRiwayat: 'btnTabRiwayat' };
        document.getElementById(map[id]).className = 'px-4 py-2 rounded-xl text-xs font-bold bg-sky-600 text-white shadow-sm';
    }

    const baseUrl = "{{ url('cs/laporan') }}";
    function openModal(type, id) {
        const modal = document.getElementById('tindakModal');
        const form = document.getElementById('tindakForm');
        form.action = `${baseUrl}/${id}/tindak`;
        document.getElementById('opsiSembunyikan').style.display = type === 'produk' ? 'block' : 'none';
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function closeModal() {
        const modal = document.getElementById('tindakModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }
</script>
</body>
</html>

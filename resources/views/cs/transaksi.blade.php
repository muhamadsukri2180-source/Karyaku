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
            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i><span>Laporan & Moderasi</span></a>
            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu"><i class="fa-solid fa-receipt w-4 text-center"></i><span>Cek Transaksi</span></a>
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
                <h2 class="text-xl sm:text-2xl font-extrabold font-display text-slate-900">Cek Transaksi</h2>
                <p class="text-[11px] text-slate-700 font-semibold mt-0.5">Mode baca saja — untuk verifikasi aduan & sengketa pengguna.</p>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-6">
            <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b">
                    <form action="{{ route('cs.transaksi') }}" method="GET" class="relative w-full sm:w-72">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Order / Nama Pembeli..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                            <th class="py-3 px-5">ID Order</th><th class="py-3 px-5">Pembeli</th><th class="py-3 px-5">Total</th><th class="py-3 px-5">Pembayaran</th><th class="py-3 px-5">Status</th><th class="py-3 px-5 text-center">Detail</th>
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
                                    <button type="button" onclick="showDetail({{ $order->id_order }})" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white text-xs font-bold"><i class="fa-solid fa-eye"></i> Lihat</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())<div class="p-4 border-t">{{ $orders->appends(request()->query())->links() }}</div>@endif
            </div>
        </div>
    </main>
</div>

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

<script>
    const sidebar = document.getElementById('sidebar');
    document.getElementById('sidebarToggleBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarCloseBtn')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); });

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
</script>
</body>
</html>

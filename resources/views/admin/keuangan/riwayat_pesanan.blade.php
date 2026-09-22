@extends('layouts.admin')

@section('title', 'Karyaku - Riwayat Pesanan')

@section('header_title', 'Riwayat Pesanan')
@section('header_subtitle', 'Pantau seluruh siklus transaksi dan status pesanan jasa di platform.')

@section('content')

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-300 text-red-800 text-xs font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-600"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100/60 border-l-4 border-blue-500 border-y border-r border-blue-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">Total Transaksi</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalTransaksi, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/30"><i class="fa-solid fa-cart-shopping text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Sedang Diproses</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($sedangDiproses, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-spinner text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Pesanan Selesai</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($orderSelesai, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-check-double text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Dibatalkan</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($dibatalkan, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-ban text-lg"></i></div>
                        </div>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        
                        <!-- Form Filter & Pencarian -->
                        <form action="{{ route('admin.transactions') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                            <div class="relative w-full sm:w-64">
                                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pembeli, Karya, Kreator..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                            </div>
                            
                            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-sky-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 shadow-sm w-full sm:w-auto">
                                <option value="">Semua Status</option>
                                <option value="pending_verif" {{ request('status') === 'pending_verif' ? 'selected' : '' }}>Verifikasi Bayar</option>
                                <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai & Paid</option>
                                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan / Ditolak</option>
                            </select>

                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.transactions') }}" class="px-3 py-2 text-xs text-red-600 hover:text-red-800 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-rotate-left"></i> Reset
                                </a>
                            @endif
                        </form>

                        <a href="{{ route('admin.transactions.export', request()->all()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 w-full md:w-auto shrink-0">
                            <i class="fa-solid fa-file-excel"></i> Export CSV
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Tanggal Pesanan</th>
                                    <th class="py-4 px-6">Pesanan / Karya</th>
                                    <th class="py-4 px-6">Pembeli & Kreator</th>
                                    <th class="py-4 px-6">Total Nilai</th>
                                    <th class="py-4 px-6">Status Order & Bayar</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse ($orders as $order)
                                @php
                                    $firstItem = $order->items->first();
                                    $productTitle = $firstItem->product->title ?? 'Produk Jasa';
                                    $sellerName = $firstItem->product->seller->name ?? '-';
                                    $extraItems = $order->items->count() > 1 ? ' (+' . ($order->items->count() - 1) . ' lainnya)' : '';
                                @endphp
                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3.5 px-6">
                                        <p class="text-xs text-slate-800 font-bold"><i class="fa-regular fa-clock text-sky-500 mr-1"></i> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</p>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <p class="text-xs text-slate-800 font-bold max-w-xs truncate" title="{{ $productTitle }}">{{ $productTitle }}{{ $extraItems }}</p>
                                        <p class="text-[10px] text-slate-500 font-semibold mt-0.5"><i class="fa-solid fa-credit-card text-slate-400 mr-1"></i> {{ strtoupper($order->payment_method ?? 'TRANSFER') }}</p>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <p class="text-xs font-bold text-slate-800"><i class="fa-solid fa-user text-[10px] text-sky-500 mr-1"></i> {{ $order->buyer->name ?? '-' }} <span class="text-[10px] text-slate-400 font-normal">(Pembeli)</span></p>
                                        <p class="text-[10px] font-semibold text-slate-600 mt-0.5"><i class="fa-solid fa-store text-[10px] text-amber-500 mr-1"></i> {{ $sellerName }} <span class="text-[10px] text-slate-400 font-normal">(Kreator)</span></p>
                                    </td>
                                    <td class="py-3.5 px-6 text-xs font-extrabold text-emerald-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-6 space-y-1">
                                        <div>
                                            @if ($order->payment_status === 'paid')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300"><i class="fa-solid fa-circle-check text-[9px]"></i> Lunas</span>
                                            @elseif ($order->payment_status === 'pending_verification')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300"><i class="fa-solid fa-hourglass-half text-[9px]"></i> Verifikasi Bayar</span>
                                            @elseif ($order->payment_status === 'rejected')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800 border border-red-300"><i class="fa-solid fa-xmark text-[9px]"></i> Bayar Ditolak</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-300">{{ ucfirst($order->payment_status ?? 'unpaid') }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($order->status === 'selesai')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Pesanan Selesai</span>
                                            @elseif ($order->status === 'dibatalkan')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">Dibatalkan</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200">{{ ucfirst($order->status ?? 'diproses') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <button type="button" onclick="showOrderDetail({{ $order->id_order }})" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white transition-all text-[11px] font-bold shadow-sm flex items-center gap-1.5 mx-auto"><i class="fa-solid fa-eye text-xs"></i> Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-12 px-6 text-center text-xs text-slate-500 font-semibold">
                                        <div class="max-w-xs mx-auto space-y-2">
                                            <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                                            <p>Tidak ada data pesanan yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders->hasPages())
                    <div class="p-5 border-t border-sky-100">
                        {{ $orders->links() }}
                    </div>
                    @endif
                </div>

            </div>
@endsection

@push('modals')
    <!-- MODAL DETAIL ORDER -->
    <div id="orderDetailModal" class="fixed inset-0 z-[60] hidden items-center justify-center modal-backdrop p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between bg-sky-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-500 text-white flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-receipt"></i></div>
                    <h3 class="font-extrabold text-slate-900 font-display">Detail Transaksi Pesanan</h3>
                </div>
                <button onclick="closeOrderDetail()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            
            <div id="orderDetailContent" class="p-6 text-xs text-slate-700 space-y-4 overflow-y-auto">
                <div class="py-12 text-center text-slate-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2 text-sky-500"></i>
                    <p class="text-xs font-semibold">Memuat detail transaksi...</p>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>
    function showOrderDetail(id) {
        const modal = document.getElementById('orderDetailModal');
        const content = document.getElementById('orderDetailContent');
        if(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        if(content) {
            content.innerHTML = `
                <div class="py-12 text-center text-slate-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2 text-sky-500"></i>
                    <p class="text-xs font-semibold">Memuat detail transaksi...</p>
                </div>
            `;
        }

        fetch(`{{ url('admin/transactions') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                let itemsHtml = (data.items || []).map(item => {
                    const sellerEmail = (item.product?.seller?.email && !item.product.seller.email.startsWith('$') && item.product.seller.email.includes('@')) ? ` (${item.product.seller.email})` : '';
                    return `
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 mb-2">
                        <div class="pr-2">
                            <p class="font-bold text-slate-900 text-xs">${item.product?.title ?? 'Produk'}</p>
                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">Kreator: <span class="font-bold text-slate-700">${item.product?.seller?.name ?? '-'}</span>${sellerEmail}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-extrabold text-emerald-600 text-xs">Rp ${Number(item.price ?? 0).toLocaleString('id-ID')}</p>
                            <p class="text-[10px] text-slate-400">Qty: ${item.quantity ?? 1}</p>
                        </div>
                    </div>
                `}).join('');

                let proofHtml = '';
                if (data.payment_proof_url) {
                    proofHtml = `
                        <div class="mt-4 p-3.5 rounded-xl bg-sky-50/80 border border-sky-200">
                            <p class="font-bold text-slate-900 text-xs mb-2 flex items-center gap-1.5"><i class="fa-solid fa-file-invoice-dollar text-sky-600"></i> Bukti Transfer Pembayaran:</p>
                            <a href="${data.payment_proof_url}" target="_blank" class="block group relative rounded-lg overflow-hidden border border-sky-300 max-h-48 bg-slate-900/5">
                                <img src="${data.payment_proof_url}" alt="Bukti Transfer" class="w-full object-contain max-h-48 group-hover:scale-105 transition-all">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center text-white text-xs font-bold gap-1">
                                    <i class="fa-solid fa-up-right-from-square"></i> Lihat Bukti Penuh
                                </div>
                            </a>
                        </div>
                    `;
                }

                const buyerEmail = (data.buyer?.email && !data.buyer.email.startsWith('$') && data.buyer.email.includes('@')) ? data.buyer.email : (data.buyer?.phone || '-');

                if(content) {
                    content.innerHTML = `
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <p class="text-xs text-slate-700 font-bold"><i class="fa-regular fa-calendar text-sky-500 mr-1"></i> ${data.created_at_formatted}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Total Transaksi</p>
                                    <p class="text-base font-black text-emerald-600">Rp ${Number(data.total_price ?? 0).toLocaleString('id-ID')}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200 text-[11px]">
                                <div>
                                    <p class="text-slate-400 font-bold text-[10px] uppercase">Pembeli</p>
                                    <p class="font-extrabold text-slate-800">${data.buyer?.name ?? '-'}</p>
                                    <p class="text-slate-500 text-[10px]">${buyerEmail}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400 font-bold text-[10px] uppercase">Status Pembayaran</p>
                                    <p class="font-bold text-slate-800 uppercase">${data.payment_status ?? '-'}</p>
                                    <p class="text-slate-500 text-[10px]">Status Order: <span class="font-bold">${data.status ?? '-'}</span></p>
                                </div>
                            </div>

                            <div>
                                <p class="font-bold text-slate-900 text-xs mb-2">Item Pesanan (${data.items?.length || 0}):</p>
                                ${itemsHtml}
                            </div>

                            ${proofHtml}

                            ${data.rejection_note ? `
                                <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
                                    <p class="font-bold mb-0.5"><i class="fa-solid fa-circle-exclamation mr-1"></i> Catatan Penolakan:</p>
                                    <p>${data.rejection_note}</p>
                                </div>
                            ` : ''}
                        </div>
                    `;
                }
            })
            .catch(err => {
                console.error(err);
                if(content) {
                    content.innerHTML = `
                        <div class="py-8 text-center text-red-500 space-y-2">
                            <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                            <p class="text-xs font-bold">Gagal memuat detail pesanan.</p>
                        </div>
                    `;
                }
            });
    }

    function closeOrderDetail() {
        const modal = document.getElementById('orderDetailModal');
        if(modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
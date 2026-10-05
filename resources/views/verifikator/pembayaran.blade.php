@extends('layouts.verifikator')

@section('title', 'Verifikasi Pembayaran')
@section('header_title', 'Verifikasi Pembayaran')
@section('header_subtitle', 'Konfirmasi & periksa bukti transfer transaksi pembelian produk dan paket membership.')

@section('content')
<!-- MAIN KATEGORI SUB-TAB: TRANSAKSI PRODUK VS MEMBERSHIP PENJUAL -->
<div class="flex flex-wrap border-b border-sky-200 gap-2">
    <a href="{{ route('verifikator.pembayaran', ['sub' => 'transaksi', 'tab' => $tab]) }}" class="px-4 py-2.5 rounded-t-xl font-bold text-xs flex items-center gap-2 transition-all {{ ($sub ?? 'transaksi') === 'transaksi' ? 'bg-white text-sky-600 border-t-2 border-x border-sky-200 shadow-sm' : 'bg-slate-200/60 text-slate-600 hover:bg-slate-200' }}">
        <i class="fa-solid fa-cart-shopping"></i> Transaksi Pembelian Produk
        @if(($pendingOrderCount ?? 0) > 0)
            <span class="bg-emerald-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $pendingOrderCount }}</span>
        @endif
    </a>
    <a href="{{ route('verifikator.pembayaran', ['sub' => 'membership', 'tab' => $tab]) }}" class="px-4 py-2.5 rounded-t-xl font-bold text-xs flex items-center gap-2 transition-all {{ ($sub ?? '') === 'membership' ? 'bg-white text-sky-600 border-t-2 border-x border-sky-200 shadow-sm' : 'bg-slate-200/60 text-slate-600 hover:bg-slate-200' }}">
        <i class="fa-solid fa-gem"></i> Pembayaran Membership Penjual
        @if(($pendingMembershipCount ?? 0) > 0)
            <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $pendingMembershipCount }}</span>
        @endif
    </a>
</div>

<!-- SUB-TABS PENDING VS RIWAYAT -->
<div class="flex border-b border-sky-200 gap-4 pt-1">
    <a href="{{ route('verifikator.pembayaran', ['sub' => $sub ?? 'transaksi', 'tab' => 'pending']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending Transfer
    </a>
    <a href="{{ route('verifikator.pembayaran', ['sub' => $sub ?? 'transaksi', 'tab' => 'history']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'history' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Verifikasi
    </a>
</div>

<!-- TABEL DATA -->
<div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
    <div>
        <div class="overflow-x-auto">
            @if(($sub ?? 'transaksi') === 'transaksi')
            <!-- TABEL TRANSAKSI PEMBELIAN PRODUK PEMBELI -->
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Kode Order & Pembeli</th>
                        <th class="py-4 px-6">Item Produk</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6">Status Pembayaran</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($payments as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-6">
                            <span class="font-extrabold text-sky-600 text-xs block">#{{ $order->kode_order }}</span>
                            <span class="font-bold text-slate-800 text-xs block">{{ $order->buyer->name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400 block">@safeEmail($order->buyer->email ?? '-')</span>
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-slate-700">
                            @php $firstItem = $order->items->first(); @endphp
                            <span class="font-bold text-slate-800 block truncate max-w-[200px]">{{ $firstItem->product->title ?? 'Produk Digital' }}</span>
                            @if($order->items->count() > 1)
                                <span class="text-[10px] text-sky-600 font-bold">+{{ $order->items->count() - 1 }} produk lainnya</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 font-extrabold text-emerald-600 text-xs">
                            Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6 text-xs text-slate-600 font-semibold">
                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded font-bold text-[10px]">
                                {{ $order->payment_method ?? 'Transfer' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6">
                            @if($order->payment_status === 'paid')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Lunas</span>
                            @elseif(in_array($order->payment_status, ['failed', 'rejected']))
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending Verifikasi</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <a href="{{ route('verifikator.transaksi_pembayaran.show', $order->id_order) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                <i class="fa-solid fa-receipt"></i> Periksa Resi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 font-semibold text-xs">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                            Belum ada transaksi pembayaran produk pada tab ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @else
            <!-- TABEL PEMBAYARAN MEMBERSHIP PENJUAL -->
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pengirim (Penjual)</th>
                        <th class="py-4 px-6">Paket Membership</th>
                        <th class="py-4 px-6">Nominal Transfer</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-6 font-bold text-slate-800">{{ $payment->user->name ?? '-' }}</td>
                        <td class="py-3.5 px-6">
                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2.5 py-0.5 rounded-md font-bold text-[10px]">
                                {{ $payment->membership->name ?? 'Membership' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 font-extrabold text-emerald-600 text-xs">
                            Rp {{ number_format($payment->payment_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6 text-xs text-slate-600 font-semibold">{{ $payment->payment_method ?? 'Transfer Bank' }}</td>
                        <td class="py-3.5 px-6">
                            @if($payment->status === 'approved')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Lunas</span>
                            @elseif($payment->status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <a href="{{ route('verifikator.pembayaran.show', $payment->id_identity_verification) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                <i class="fa-solid fa-receipt"></i> Periksa Resi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 font-semibold text-xs">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                            Belum ada pembayaran paket membership pada tab ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @endif
        </div>
    </div>

    @if(method_exists($payments, 'hasPages') && $payments->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
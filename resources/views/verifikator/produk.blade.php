@extends('layouts.verifikator')

@section('title', 'Verifikasi Produk & Jasa')
@section('header_title', 'Verifikasi Produk & Jasa')
@section('header_subtitle', 'Filter kelayakan produk/jasa yang baru diunggah oleh penjual.')

@section('content')
<!-- SUB-TABS NAVIGATION -->
<div class="flex border-b border-sky-200 gap-4">
    <a href="{{ route('verifikator.produk', ['tab' => 'pending']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending
    </a>
    <a href="{{ route('verifikator.produk', ['tab' => 'history']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'history' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Produk
    </a>
</div>

<!-- TABEL DATA -->
<div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
    <div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Nama Produk / Jasa</th>
                        <th class="py-4 px-6">Penjual</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Harga</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-6 font-bold text-slate-800">{{ $product->title ?? $product->name ?? '-' }}</td>
                        <td class="py-3.5 px-6 text-xs text-slate-600 font-semibold">{{ $product->seller->name ?? $product->user->name ?? '-' }}</td>
                        <td class="py-3.5 px-6">
                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded-md font-bold text-[10px]">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 font-extrabold text-slate-800 text-xs">
                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6">
                            @if(in_array($product->status, ['approved', 'active']))
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Aktif</span>
                            @elseif($product->status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <a href="{{ route('verifikator.produk.show', $product->id_product ?? $product->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                <i class="fa-solid fa-eye"></i> Pratinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 font-semibold text-xs">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                            Tidak ada data produk pada tab ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($products, 'hasPages') && $products->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
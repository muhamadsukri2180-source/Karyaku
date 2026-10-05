@extends('layouts.verifikator')

@section('title', 'Detail Verifikasi Produk')
@section('header_title', 'Detail Verifikasi Produk')
@section('header_subtitle', 'Tinjau deskripsi, varian harga, dan sampel media karya penjual.')

@section('header_right')
<a href="{{ route('verifikator.produk') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-5">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">
                {{ $product->category->name ?? 'Kategori Umum' }}
            </span>
            <h2 class="text-xl font-extrabold text-slate-900 font-display mt-2">{{ $product->title ?? $product->name ?? '-' }}</h2>
        </div>
        <div class="text-left md:text-right">
            <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Harga Ditentukan</span>
            <span class="text-2xl font-extrabold text-emerald-600 font-display">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
            <span class="text-slate-400 font-bold block uppercase text-[10px]">Nama Penjual</span>
            <span class="font-extrabold text-slate-800 text-sm block mt-0.5">{{ $product->seller->name ?? $product->user->name ?? '-' }}</span>
        </div>
        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
            <span class="text-slate-400 font-bold block uppercase text-[10px]">Email Penjual</span>
            <span class="font-bold text-slate-800 text-sm block mt-0.5">@safeEmail($product->seller->email ?? $product->user->email ?? '-')</span>
        </div>
    </div>

    <div>
        <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-2">Deskripsi Produk & Jasa</h4>
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed text-slate-700">
            {!! nl2br(e($product->description ?? 'Tidak ada rincian deskripsi.')) !!}
        </div>
    </div>

    @if($product->thumbnail || $product->file)
    <div>
        <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-2">Pratinjau Gambar Sampel</h4>
        <div class="bg-slate-100 border border-slate-200 rounded-xl p-2 max-w-md">
            <img src="{{ $product->thumbnail_url }}" class="w-full h-auto rounded-lg shadow-sm">
        </div>
    </div>
    @endif
</div>

@if($product->status === 'pending')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Form Approve -->
    <div class="bg-white border-l-4 border-l-emerald-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> Publikasikan Produk
        </h3>
        <p class="text-xs text-slate-500 font-medium mb-4">Produk langsung berstatus aktif dan dapat dibeli publik.</p>

        <form id="approveProductForm" action="{{ route('verifikator.produk.approve', $product->id_product ?? $product->id) }}" method="POST">
            @csrf
            <button type="button" onclick="confirmApproveProduct()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-check"></i> ✅ Disetujui & Terbitkan
            </button>
        </form>
    </div>

    <!-- Form Reject -->
    <div class="bg-white border-l-4 border-l-red-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark text-red-500"></i> Tolak Produk
        </h3>
        <p class="text-xs text-slate-500 font-medium mb-4">Kirimkan pesan penolakan / catatan revisi ke penjual.</p>

        <form id="rejectProductForm" action="{{ route('verifikator.produk.reject', $product->id_product ?? $product->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Catatan Penolakan <span class="text-red-500">*</span></label>
                <textarea id="rejectionNoteInput" name="rejection_note" required placeholder="Tuliskan catatan revisi/penolakan..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-red-400 bg-slate-50 min-h-[80px]"></textarea>
            </div>

            <button type="button" onclick="confirmRejectProduct()" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-xmark"></i> ✕ Tolak / Minta Revisi
            </button>
        </form>
    </div>

</div>
@else
<div class="bg-slate-100 text-slate-700 border border-slate-200 rounded-2xl p-4 font-bold text-xs text-center">
    Status Produk: {{ strtoupper($product->status) }}
</div>
@endif
@endsection

@push('scripts')
<script>
    function confirmApproveProduct() {
        Swal.fire({
            title: 'Setujui Produk?',
            text: "Produk akan dapat dilihat dan dibeli oleh seluruh pembeli.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Terbitkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approveProductForm').submit();
            }
        });
    }

    function confirmRejectProduct() {
        const note = document.getElementById('rejectionNoteInput').value;
        if (!note.trim()) {
            Swal.fire({ icon: 'warning', title: 'Catatan Wajib Diisi', text: 'Tuliskan alasan penolakan produk.', confirmButtonColor: '#0EA5E9' });
            return;
        }
        Swal.fire({
            title: 'Tolak Produk Ini?',
            text: "Catatan penolakan akan dikirimkan ke notifikasi penjual.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rejectProductForm').submit();
            }
        });
    }
</script>
@endpush
@extends('layouts.pembeli')
@section('title', 'Marketplace')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Marketplace</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Karya digital dari kreator terverifikasi, siap kamu pesan sekarang.</p>
    </div>
    <form action="{{ route('pembeli.marketplace') }}" method="GET" class="d-flex gap-2">
        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        <select name="sort" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
            <option value="terlaris" {{ request('sort', 'terlaris') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
        </select>
    </form>
</div>

@if (request('q') || request('category'))
<div class="mb-3 small text-muted">
    Menampilkan hasil untuk
    @if(request('q')) pencarian "<strong>{{ request('q') }}</strong>" @endif
    @if(request('category')) di kategori "<strong>{{ $categories->firstWhere('id_category', request('category'))->name ?? '-' }}</strong>" @endif
    &mdash; <a href="{{ route('pembeli.marketplace') }}">Reset filter</a>
</div>
@endif

@if ($products->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-search fs-1 d-block mb-3"></i>
        Tidak ada produk yang ditemukan. Coba kata kunci atau kategori lain.
    </div>
@else
    <div class="product-grid">
        @foreach ($products as $product)
            @include('pembeli.partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds])
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endif

@endsection

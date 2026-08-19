@extends('layouts.pembeli')
@section('title', 'Marketplace - Cari Produk & Jasa Kreatif')

@section('content')

{{-- MARKETPLACE HEADER --}}
<div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h4 class="fw-bold mb-1">Marketplace Karyaku</h4>
        <p class="text-muted mb-0 small">Temukan berbagai jasa kreatif, desain, dan karya digital terbaik dari para kreator Indonesia.</p>
    </div>

    {{-- SORTING DROPDOWN --}}
    <form action="{{ route('pembeli.marketplace') }}" method="GET" class="d-flex align-items-center gap-2">
        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif

        <label class="small text-muted fw-semibold flex-shrink-0">Urutkan:</label>
        <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm border-light-subtle shadow-sm" style="width: auto;">
            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Termahal</option>
        </select>
    </form>
</div>

{{-- KATEGORI CHIPS --}}
<div class="d-flex gap-2 overflow-x-auto pb-3 mb-4 no-scrollbar">
    <a href="{{ route('pembeli.marketplace', array_merge(request()->except('category', 'page'), [])) }}" 
       class="btn btn-sm {{ !request('category') ? 'btn-primary font-weight-bold shadow-sm' : 'btn-outline-secondary bg-white' }} rounded-pill px-3 py-1.5 whitespace-nowrap">
        Semua Kategori
    </a>
    @foreach ($categories as $cat)
        <a href="{{ route('pembeli.marketplace', array_merge(request()->except('page'), ['category' => $cat->id_category])) }}" 
           class="btn btn-sm {{ request('category') == $cat->id_category ? 'btn-primary font-weight-bold shadow-sm' : 'btn-outline-secondary bg-white' }} rounded-pill px-3 py-1.5 whitespace-nowrap">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

{{-- PRODUCT GRID --}}
<div class="product-grid mb-4">
    @forelse ($products as $product)
        @php
            $isWishlisted = in_array($product->id_product, $wishlistIds);
        @endphp
        <div class="product-card">
            <div class="product-thumb">
                <img src="{{ $product->image_url ?? asset('storage/' . $product->image) }}" 
                     alt="{{ $product->title }}"
                     onerror="this.src='https://placehold.co/400x300?text=Produk+Karyaku'">
                <span class="cat-badge">{{ $product->category->name ?? 'Jasa' }}</span>
                <button type="button" class="wish-btn {{ $isWishlisted ? 'active' : '' }}" 
                        data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" 
                        title="Tambah ke Wishlist">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>
            </div>
            <div class="product-body">
                <h6>
                    <a href="{{ route('pembeli.produk.detail', $product->id_product) }}">{{ $product->title }}</a>
                </h6>
                <div class="product-price">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                <div class="product-meta">
                    <span><i class="bi bi-bag-check me-1"></i>{{ $product->sold_count ?? 0 }} Terjual</span>
                    <span><i class="bi bi-eye me-1"></i>{{ $product->view_count ?? 0 }}</span>
                </div>
                <div class="product-seller">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Penjual') }}&background=eff6ff&color=1e3a8a" alt="seller">
                    <span class="text-truncate">{{ $product->seller->name ?? 'Kreator Karyaku' }}</span>
                </div>

                <form action="{{ route('pembeli.keranjang.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                    <button type="submit" class="btn-add-cart">
                        <i class="bi bi-cart-plus-fill"></i> Tambah Keranjang
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center card-box" style="grid-column: 1 / -1;">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width:60px;height:60px;">
                <i class="bi bi-search fs-3"></i>
            </div>
            <h5 class="fw-bold">Produk Tidak Ditemukan</h5>
            <p class="text-muted small mb-3">Tidak ada produk yang sesuai dengan pencarian atau filter Anda.</p>
            <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-bold">
                Reset Filter Pencarian
            </a>
        </div>
    @endforelse
</div>

{{-- PAGINATION --}}
<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>

@endsection

@php
    $isWish = in_array($product->id_product, $wishlistIds ?? []);
@endphp
<div class="product-card">
    <div class="product-thumb">
        <span class="cat-badge">{{ $product->category->name ?? 'Karya Digital' }}</span>
        <button type="button" class="wish-btn {{ $isWish ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" title="Favoritkan">
            <i class="bi {{ $isWish ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
        <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="thumb-link">
            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" loading="lazy" onerror="this.src='https://placehold.co/600x400/eaf1ff/2563eb?text=Produk+Karyaku'">
        </a>
    </div>
    
    <div class="product-body">
        {{-- DI BEWAH GAMBAR: INFORMASI KREATOR & FITUR WISHLIST LOVE --}}
        <div class="product-body-top d-flex align-items-center justify-content-between mb-2">
            <div class="product-seller d-flex align-items-center gap-1.5 text-truncate">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=eff6ff&color=2563eb" alt="Kreator" class="rounded-circle" style="width:20px;height:20px;object-fit:cover;">
                <span class="seller-name text-muted small text-truncate" style="font-size: 11px;">{{ $product->seller->name ?? 'Kreator Karyaku' }}</span>
            </div>
            
            {{-- FITUR WISHLIST DENGAN GAMBAR LOVE DI BAWAH GAMBAR --}}
            <button type="button" class="wish-icon-btn {{ $isWish ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" title="Simpan ke Wishlist">
                <i class="bi {{ $isWish ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        </div>

        {{-- JUDUL PRODUK --}}
        <h6 class="product-title mb-1">
            <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="text-dark fw-semibold text-decoration-none" title="{{ $product->title }}">
                {{ $product->title }}
            </a>
        </h6>

        {{-- RATING & TERJUAL --}}
        <div class="product-meta d-flex align-items-center justify-content-between mb-2 text-muted" style="font-size: 11px;">
            <span class="rating fw-semibold text-warning">
                <i class="bi bi-star-fill"></i> {{ number_format($product->avg_rating, 1) }}
            </span>
            <span class="sold">
                <i class="bi bi-bag-check me-1"></i> {{ $product->sold_count ?? 0 }} Terjual
            </span>
        </div>

        {{-- HARGA PRODUK --}}
        <div class="product-price fw-bold text-primary fs-6 mb-3">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </div>

        {{-- TOMBOL PERSEGI PANJANG BERTULISAN BELI --}}
        <div class="product-actions mt-auto">
            <form action="{{ route('pembeli.keranjang.store') }}" method="POST" class="w-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-buy-rectangular">
                    <i class="bi bi-bag-check-fill me-1"></i> Beli
                </button>
            </form>
        </div>
    </div>
</div>

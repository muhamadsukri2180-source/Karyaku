@php
    $isWish = in_array($product->id_product, $wishlistIds ?? []);
    $imgUrl = $product->thumbnail ? asset('storage/' . $product->thumbnail) : ($product->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=400&name=' . urlencode($product->title));
@endphp
<div class="product-card">
    <div class="product-thumb">
        <span class="cat-badge">{{ $product->category->name ?? 'Karya Digital' }}</span>
        <button type="button" class="wish-btn {{ $isWish ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" title="Favoritkan" aria-label="Favoritkan">
            <i class="bi {{ $isWish ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
        </button>
        <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="d-block w-100 h-100">
            <img src="{{ $imgUrl }}" alt="{{ $product->title }}" loading="lazy" onerror="this.src='https://placehold.co/600x400/eaf1ff/2563eb?text=Produk+Karyaku'">
        </a>
    </div>
    
    <div class="product-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: 80%;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=eff6ff&color=2563eb&bold=true" alt="Kreator" class="rounded-circle flex-shrink-0" style="width:20px;height:20px;object-fit:cover;">
                <span class="text-muted small text-truncate fw-medium" style="font-size: 11px;">{{ $product->seller->name ?? 'Kreator Karyaku' }}</span>
            </div>
            
            <button type="button" class="wish-icon-btn {{ $isWish ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" title="Simpan ke Wishlist" aria-label="Simpan ke Wishlist">
                <i class="bi {{ $isWish ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        </div>

        <h6 class="mb-1 text-truncate" style="font-size: 13.5px; font-weight: 700; line-height: 1.35;">
            <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="text-dark text-decoration-none" title="{{ $product->title }}">
                {{ $product->title }}
            </a>
        </h6>

        <div class="d-flex align-items-center justify-content-between mb-2 text-muted" style="font-size: 11px;">
            <span class="text-muted">
                <i class="bi bi-bag-check me-1"></i> {{ $product->sold_count ?? 0 }} Terjual
            </span>
        </div>

        <div class="fw-extrabold text-primary mb-3" style="font-size: 15px; letter-spacing: -0.2px;">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </div>

        <div class="mt-auto">
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

@php
    $isWish = in_array($product->id_product, $wishlistIds ?? []);
@endphp
<div class="product-card">
    <div class="product-thumb">
        <span class="cat-badge">{{ $product->category->name ?? 'Umum' }}</span>
        <button type="button" class="wish-btn {{ $isWish ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" title="Wishlist">
            <i class="bi {{ $isWish ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
        <a href="{{ route('pembeli.produk.detail', $product->id_product) }}">
            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=256&name=' . urlencode($product->title) }}" alt="{{ $product->title }}">
        </a>
    </div>
    <div class="product-body">
        <h6><a href="{{ route('pembeli.produk.detail', $product->id_product) }}">{{ $product->title }}</a></h6>
        <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="product-meta">
            <span class="rating text-warning fw-semibold"><i class="bi bi-star-fill text-warning"></i> {{ $product->avg_rating }}</span>
            <span>Terjual {{ $product->sold_count }}</span>
        </div>
        <div class="product-seller">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=dbeafe&color=1e3a8a" alt="">
            {{ $product->seller->name ?? 'Kreator' }}
        </div>
        <form action="{{ route('pembeli.keranjang.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id_product }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
        </form>
    </div>
</div>

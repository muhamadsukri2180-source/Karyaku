@extends('layouts.pembeli')
@section('title', 'Wishlist')

@section('content')

<h4 class="fw-bold mb-4">Wishlist Saya</h4>

@if ($items->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-heart fs-1 d-block mb-3"></i>
        Belum ada produk di wishlist kamu.
        <div class="mt-3"><a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm">Jelajahi Marketplace</a></div>
    </div>
@else
<div class="product-grid">
    @foreach ($items as $wish)
        @continue(!$wish->product)
        <div data-wishlist-row>
            @php($product = $wish->product)
            <div class="product-card">
                <div class="product-thumb">
                    <span class="cat-badge">{{ $product->category->name ?? 'Umum' }}</span>
                    <button type="button" class="wish-btn active" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" data-remove-on-unwish="1" title="Hapus dari wishlist">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                    <a href="{{ route('pembeli.produk.detail', $product->id_product) }}">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=256&name=' . urlencode($product->title) }}" alt="{{ $product->title }}">
                    </a>
                </div>
                <div class="product-body">
                    <h6><a href="{{ route('pembeli.produk.detail', $product->id_product) }}">{{ $product->title }}</a></h6>
                    <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
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
        </div>
    @endforeach
</div>
@endif

@endsection

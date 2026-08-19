<!-- @extends('layouts.pembeli')
@section('title', $product->title)

@section('content')

<nav class="small text-muted mb-3">
    <a href="{{ route('pembeli.marketplace') }}" class="text-muted">Marketplace</a> /
    <a href="{{ route('pembeli.marketplace', ['category' => $product->category_id]) }}" class="text-muted">{{ $product->category->name ?? 'Kategori' }}</a> /
    <span>{{ $product->title }}</span>
</nav>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-box overflow-hidden">
            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=512&name=' . urlencode($product->title) }}"
                 alt="{{ $product->title }}" class="w-100" style="max-height: 420px; object-fit: cover;">
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-box p-4">
            <span class="cat-badge" style="position:static; display:inline-block;">{{ $product->category->name ?? 'Umum' }}</span>
            <h3 class="fw-bold mt-2 mb-1">{{ $product->title }}</h3>

            <div class="d-flex align-items-center gap-3 text-muted mb-3" style="font-size:12.5px;">
                <span><i class="bi bi-eye"></i> {{ $product->view_count }} dilihat</span>
                <span><i class="bi bi-bag-check"></i> Terjual {{ $product->sold_count }}</span>
            </div>

            <div class="fw-bold mb-3" style="font-size: 26px; color: var(--coral);">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </div>

            <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background: var(--primary-light);">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=dbeafe&color=1e3a8a" style="width:38px;height:38px;border-radius:50%;">
                <div>
                    <div class="fw-semibold small">{{ $product->seller->name ?? 'Kreator' }}</div>
                    <div class="text-muted" style="font-size:11px;">Kreator Karyaku</div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <form action="{{ route('pembeli.keranjang.store') }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                    <div class="d-flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width:90px;">
                        <button type="submit" class="btn btn-primary flex-fill fw-semibold"><i class="bi bi-cart-plus"></i> Tambah ke Keranjang</button>
                    </div>
                </form>
                <button type="button" class="wish-btn {{ $isWishlisted ? 'active' : '' }}" data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" style="position:static; width:48px; height:48px; border:1px solid var(--border-color);">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card-box p-4 mt-4">
    <h6 class="fw-bold mb-2">Deskripsi Produk</h6>
    <p class="text-muted mb-0" style="white-space: pre-line;">{{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>
</div>

@if ($produkLain->isNotEmpty())
<div class="mt-5">
    <h6 class="fw-bold mb-3">Produk Lain dari {{ $product->seller->name ?? 'Kreator Ini' }}</h6>
    <div class="product-grid">
        @foreach ($produkLain as $item)
            @include('pembeli.partials.product-card', ['product' => $item, 'wishlistIds' => []])
        @endforeach
    </div>
</div>
@endif

@endsection -->

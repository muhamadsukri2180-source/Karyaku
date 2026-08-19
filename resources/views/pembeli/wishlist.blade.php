@extends('layouts.pembeli')
@section('title', 'Wishlist Saya')

@section('content')

<div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-heart-fill text-danger me-2"></i>Wishlist Saya</h4>
        <p class="text-muted mb-0 small">Daftar produk dan karya kreatif favorit yang Anda simpan.</p>
    </div>
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-bold">
        <i class="bi bi-shop me-1"></i> Jelajahi Lebih Banyak
    </a>
</div>

@if ($wishlists->isEmpty())
    <div class="card-box p-5 text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3" style="width:70px;height:70px;">
            <i class="bi bi-heart fs-2"></i>
        </div>
        <h5 class="fw-bold">Wishlist Anda Masih Kosong</h5>
        <p class="text-muted small mb-4">Temukan berbagai desain dan produk favorit Anda di marketplace Karyaku.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-3">
            <i class="bi bi-shop me-1"></i> Cari Produk Sekarang
        </a>
    </div>
@else
    <div class="product-grid mb-4">
        @foreach ($wishlists as $wish)
            @php $product = $wish->product; @endphp
            @if ($product)
                <div class="product-card">
                    <div class="product-thumb">
                        <img src="{{ $product->image_url ?? asset('storage/' . ($product->image ?? '')) }}" 
                             alt="{{ $product->title }}"
                             onerror="this.src='https://placehold.co/400x300?text=Produk+Karyaku'">
                        <span class="cat-badge">{{ $product->category->name ?? 'Jasa' }}</span>
                        <button type="button" class="wish-btn active" 
                                data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}" 
                                title="Hapus dari Wishlist"
                                onclick="toggleWishlist(this)">
                            <i class="bi bi-heart-fill"></i>
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
            @endif
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $wishlists->links() }}
    </div>
@endif

@push('scripts')
<script>
    function toggleWishlist(btn) {
        const url = btn.dataset.url;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'removed') {
                window.location.reload();
            }
        })
        .catch(err => window.location.reload());
    }
</script>
@endpush

@endsection

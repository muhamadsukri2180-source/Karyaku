@extends('layouts.pembeli')
@section('title', $product->title)

@section('content')

{{-- BREADCRUMB --}}
<nav class="small text-muted mb-3">
    <a href="{{ route('pembeli.marketplace') }}" class="text-muted">Marketplace</a> / 
    <a href="{{ route('pembeli.marketplace', ['category' => $product->category_id]) }}" class="text-muted">{{ $product->category->name ?? 'Kategori' }}</a> / 
    <span class="text-dark fw-bold">{{ $product->title }}</span>
</nav>

<div class="row g-4">
    {{-- FOTO PRODUK --}}
    <div class="col-lg-6">
        <div class="card-box overflow-hidden p-2 rounded-4">
            <img src="{{ $product->image_url ?? asset('storage/' . ($product->image ?? '')) }}"
                 alt="{{ $product->title }}" 
                 class="w-100 rounded-3 object-fit-cover" 
                 style="max-height: 420px;"
                 onerror="this.src='https://placehold.co/600x400?text=Karyaku+Produk'">
        </div>
    </div>

    {{-- INFORMATION & PURCHASING --}}
    <div class="col-lg-6">
        <div class="card-box p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill font-weight-bold mb-2" style="font-size: 11px;">
                    {{ $product->category->name ?? 'Umum' }}
                </span>
                <h3 class="fw-bold text-dark mt-1 mb-2">{{ $product->title }}</h3>

                <div class="d-flex align-items-center gap-3 text-muted mb-3" style="font-size: 13px;">
                    <span><i class="bi bi-eye text-primary me-1"></i> {{ number_format($product->view_count ?? 0) }} dilihat</span>
                    <span><i class="bi bi-bag-check text-success me-1"></i> Terjual {{ number_format($product->sold_count ?? 0) }}</span>
                </div>

                <div class="fw-bold mb-4 text-primary" style="font-size: 28px;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                {{-- KREATOR CHIP --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3 border bg-light-subtle">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=2563eb&color=fff" 
                         style="width: 42px; height: 42px; border-radius: 50%;" alt="seller">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $product->seller->name ?? 'Kreator Karyaku' }}</div>
                        <div class="text-muted small">Penjual Terverifikasi</div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="d-flex gap-2">
                <form action="{{ route('pembeli.keranjang.store') }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                    <div class="d-flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="form-control text-center" style="max-width: 80px;">
                        <button type="submit" class="btn btn-primary flex-fill fw-bold py-2.5">
                            <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>
                
                <button type="button" class="btn btn-outline-danger px-3 rounded-3 wish-btn {{ $isWishlisted ? 'active' : '' }}" 
                        data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}"
                        onclick="toggleWishlistDetail(this)"
                        title="Favorit">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- DESKRIPSI PRODUK --}}
<div class="card-box p-4 mt-4">
    <h6 class="fw-bold mb-3 border-bottom pb-2">Deskripsi Produk</h6>
    <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.7;">
        {{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk ini.' }}
    </p>
</div>

{{-- PRODUK LAIN DARI KREATOR INI --}}
@if ($produkLain->isNotEmpty())
<div class="mt-5">
    <h5 class="fw-bold mb-3">Produk Lain dari {{ $product->seller->name ?? 'Kreator Ini' }}</h5>
    <div class="product-grid">
        @foreach ($produkLain as $item)
            <div class="product-card">
                <div class="product-thumb">
                    <img src="{{ $item->image_url ?? asset('storage/' . ($item->image ?? '')) }}" 
                         alt="{{ $item->title }}"
                         onerror="this.src='https://placehold.co/400x300?text=Produk+Karyaku'">
                    <span class="cat-badge">{{ $item->category->name ?? 'Jasa' }}</span>
                </div>
                <div class="product-body">
                    <h6>
                        <a href="{{ route('pembeli.produk.detail', $item->id_product) }}">{{ $item->title }}</a>
                    </h6>
                    <div class="product-price">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </div>
                    <form action="{{ route('pembeli.keranjang.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id_product }}">
                        <button type="submit" class="btn-add-cart mt-2">
                            <i class="bi bi-cart-plus-fill"></i> Tambah Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
    function toggleWishlistDetail(btn) {
        const url = btn.dataset.url;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            window.location.reload();
        })
        .catch(err => window.location.reload());
    }
</script>
@endpush

@endsection

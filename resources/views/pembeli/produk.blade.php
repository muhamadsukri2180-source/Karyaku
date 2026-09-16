@extends('layouts.pembeli')
@section('title', $product->title . ' - Karyaku')

@push('styles')
<style>
    .gallery-main {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 18px;
        background: #f1f5f9;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }
    .thumb-nav-img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: var(--transition);
    }
    .thumb-nav-img:hover, .thumb-nav-img.active {
        border-color: var(--primary);
        transform: scale(1.05);
    }
    .price-tag-lg {
        font-size: 30px;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -0.5px;
    }
    .seller-box {
        background: var(--primary-light);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
    }
    .rating-stars {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 4px;
    }
    .rating-stars input {
        display: none;
    }
    .rating-stars label {
        color: #cbd5e1;
        font-size: 24px;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .rating-stars label:hover,
    .rating-stars label:hover ~ label,
    .rating-stars input:checked ~ label {
        color: #f59e0b;
    }
</style>
@endpush

@section('content')

{{-- BREADCRUMB & LAPORKAN --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <nav class="small text-muted">
        <a href="{{ route('pembeli.marketplace') }}" class="text-muted text-decoration-none">Marketplace</a> / 
        <a href="{{ route('pembeli.marketplace', ['category' => $product->category_id]) }}" class="text-muted text-decoration-none">{{ $product->category->name ?? 'Kategori' }}</a> / 
        <span class="text-dark fw-bold text-truncate d-inline-block" style="max-width: 250px; vertical-align: bottom;">{{ $product->title }}</span>
    </nav>
    <a href="{{ route('reports.create', ['product_id' => $product->id_product]) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1" title="Laporkan produk mencurigakan / melanggar">
        <i class="bi bi-flag me-1"></i> Laporkan Produk
    </a>
</div>

<div class="row g-4 mb-4">
    {{-- FOTO & GALLERY PRODUK --}}
    <div class="col-lg-6">
        <div class="card-box p-3 rounded-4">
            @php
                $imagesList = $product->images_list;
                $mainImg = $product->thumbnail ? asset('storage/' . $product->thumbnail) : ($product->image_url ?? 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=512&name=' . urlencode($product->title));
            @endphp
            <img id="mainProductImg" src="{{ $mainImg }}"
                 alt="{{ $product->title }}" 
                 class="gallery-main mb-2" 
                 onerror="this.src='https://placehold.co/600x400/eaf1ff/2563eb?text=Produk+Karyaku'">
            
            @if(count($imagesList) > 1)
                <div class="d-flex gap-2 mt-2 overflow-x-auto pb-1">
                    @foreach($imagesList as $img)
                        <img src="{{ asset('storage/' . $img) }}" 
                             class="thumb-nav-img" 
                             alt="Thumbnail"
                             onclick="document.getElementById('mainProductImg').src=this.src;">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- INFORMASI & PEMBELIAN --}}
    <div class="col-lg-6">
        <div class="card-box p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fw-bold mb-2" style="font-size: 11px;">
                    {{ $product->category->name ?? 'Aset Digital' }}
                </span>
                <h3 class="fw-extrabold text-dark mt-1 mb-2">{{ $product->title }}</h3>

                <div class="d-flex align-items-center gap-3 text-muted mb-3 flex-wrap" style="font-size: 13px;">
                    <span><i class="bi bi-eye text-primary me-1"></i> {{ number_format($product->view_count ?? 0) }} dilihat</span>
                    <span><i class="bi bi-bag-check text-success me-1"></i> Terjual {{ number_format($product->sold_count ?? 0) }}</span>
                </div>

                <div class="price-tag-lg mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                {{-- KREATOR CHIP --}}
                <div class="seller-box d-flex align-items-center gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Kreator') }}&background=2563eb&color=fff&bold=true" 
                         style="width: 44px; height: 44px; border-radius: 50%;" alt="seller">
                    <div class="overflow-hidden">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 14px;">{{ $product->seller->name ?? 'Kreator Karyaku' }}</div>
                        <div class="text-primary small fw-semibold"><i class="bi bi-patch-check-fill me-1"></i> Penjual Terverifikasi Karyaku</div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI PEMBELIAN --}}
            <div class="d-flex gap-2">
                <form action="{{ route('pembeli.keranjang.store') }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                    <div class="d-flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="form-control text-center fw-bold" style="max-width: 75px; border-radius: 12px;" aria-label="Jumlah">
                        <button type="submit" class="btn btn-primary flex-fill fw-bold py-2.5 rounded-3 shadow-sm">
                            <i class="bi bi-cart-plus me-1"></i> Masukkan Keranjang
                        </button>
                    </div>
                </form>
                
                <button type="button" class="btn btn-outline-danger px-3 rounded-3 wish-btn {{ $isWishlisted ? 'active' : '' }}" 
                        data-url="{{ route('pembeli.wishlist.toggle', $product->id_product) }}"
                        style="position: static; width: 44px; height: 44px;"
                        title="Favoritkan" aria-label="Favoritkan">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }} fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- VIDEO PREVIEW / DEMO (JIKA ADA) --}}
@if($product->video || $product->video_url)
    <div class="card-box p-4 mb-4">
        <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-play-btn-fill text-danger fs-5"></i> Video Demo / Preview Karya
        </h6>
        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm bg-black" style="max-height: 440px;">
            <video controls class="w-100 h-100 object-fit-contain">
                <source src="{{ $product->video_url ?: asset('storage/' . $product->video) }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
    </div>
@endif

{{-- DESKRIPSI PRODUK --}}
<div class="card-box p-4 mb-4">
    <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark d-flex align-items-center gap-2">
        <i class="bi bi-file-text-fill text-primary"></i> Deskripsi & Informasi Karya
    </h6>
    <div class="text-secondary" style="white-space: pre-line; line-height: 1.8; font-size: 14px;">
        {{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk digital ini.' }}
    </div>
</div>



{{-- PRODUK LAIN DARI PENJUAL --}}
@if(isset($produkLain) && $produkLain->count() > 0)
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-3">Karya Lain dari {{ $product->seller->name ?? 'Penjual Ini' }}</h5>
        <div class="product-grid">
            @foreach($produkLain as $pLain)
                @include('pembeli.partials.product-card', ['product' => $pLain])
            @endforeach
        </div>
    </div>
@endif

@endsection

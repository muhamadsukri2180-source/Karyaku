@extends('layouts.pembeli')
@section('title', $product->title)

@section('content')

{{-- BREADCRUMB & AKSI LAPOR --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <nav class="small text-muted">
        <a href="{{ route('pembeli.marketplace') }}" class="text-muted text-decoration-none">Marketplace</a> / 
        <a href="{{ route('pembeli.marketplace', ['category' => $product->category_id]) }}" class="text-muted text-decoration-none">{{ $product->category->name ?? 'Kategori' }}</a> / 
        <span class="text-dark fw-bold">{{ $product->title }}</span>
    </nav>
    <a href="{{ route('reports.create', ['product_id' => $product->id_product]) }}" class="btn btn-outline-secondary btn-sm text-danger border-danger-subtle bg-danger-subtle bg-opacity-25" title="Laporkan produk mencurigakan / melanggar">
        <i class="bi bi-flag-fill me-1"></i> Laporkan Produk
    </a>
</div>

<div class="row g-4">
    {{-- FOTO & GALLERY PRODUK --}}
    <div class="col-lg-6">
        <div class="card-box overflow-hidden p-3 rounded-4">
            @php
                $imagesList = $product->images_list;
            @endphp
            <img id="mainProductImg" src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : ($product->image_url ?? 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=512&name=' . urlencode($product->title)) }}"
                 alt="{{ $product->title }}" 
                 class="w-100 rounded-3 object-fit-cover shadow-sm mb-2" 
                 style="max-height: 420px; object-fit: cover;"
                 onerror="this.src='https://placehold.co/600x400?text=Karyaku+Produk'">
            
            @if(count($imagesList) > 1)
                <div class="d-flex gap-2 mt-2 overflow-x-auto pb-1">
                    @foreach($imagesList as $imgKey => $img)
                        <img src="{{ asset('storage/' . $img) }}" 
                             class="rounded border shadow-sm cursor-pointer" 
                             style="width: 70px; height: 70px; object-fit: cover; cursor: pointer; transition: transform 0.2s;" 
                             onclick="document.getElementById('mainProductImg').src=this.src;">
                    @endforeach
                </div>
            @endif
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

                <div class="d-flex align-items-center gap-3 text-muted mb-3 flex-wrap" style="font-size: 13px;">
                    <span class="text-warning fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> {{ $avgRating }} ({{ $totalReviews }} Ulasan)</span>
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
                        <div class="text-muted small"><i class="bi bi-patch-check-fill text-primary"></i> Penjual Terverifikasi</div>
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

{{-- VIDIO PREVIEW PRODUK (OPSIONAL) --}}
@if($product->video)
    <div class="card-box p-4 mt-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-play-btn-fill text-danger me-2"></i>Vidio Preview / Demo Karya</h6>
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-dark">
            <video controls class="w-100 h-100 rounded-3">
                <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
    </div>
@endif

{{-- DESKRIPSI PRODUK --}}
<div class="card-box p-4 mt-4">
    <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-info-circle text-primary me-2"></i>Deskripsi Produk</h6>
    <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.7;">
        {{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk ini.' }}
    </p>
</div>

{{-- ULASAN & RATING SECTION --}}
<div class="card-box p-4 mt-4">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
        <div>
            <h6 class="fw-bold mb-1"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan & Penilaian Pembeli</h6>
            <span class="text-muted small">Rata-rata penilaian dari pembeli terverifikasi</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4 fw-bold text-dark">{{ $avgRating }}</span>
            <div class="text-warning">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                @endfor
            </div>
            <span class="text-muted small">({{ $totalReviews }} ulasan)</span>
        </div>
    </div>

    {{-- FORM BERI ULASAN (Hanya bagi pembeli yang sudah beli) --}}
    @if($hasBought)
        <div class="p-3 mb-4 rounded-3 border bg-light-subtle">
            <h6 class="fw-bold mb-2">{{ $userReview ? 'Ubah Ulasan Anda' : 'Beri Ulasan untuk Produk Ini' }}</h6>
            <form action="{{ route('pembeli.produk.review', $product->id_product) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Pilih Rating Bintang:</label>
                    <select name="rating" class="form-select form-select-sm" style="max-width: 180px;" required>
                        <option value="5" {{ (old('rating', $userReview->rating ?? 5) == 5) ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                        <option value="4" {{ (old('rating', $userReview->rating ?? 5) == 4) ? 'selected' : '' }}>⭐⭐⭐⭐ (4 - Puas)</option>
                        <option value="3" {{ (old('rating', $userReview->rating ?? 5) == 3) ? 'selected' : '' }}>⭐⭐⭐ (3 - Cukup)</option>
                        <option value="2" {{ (old('rating', $userReview->rating ?? 5) == 2) ? 'selected' : '' }}>⭐⭐ (2 - Kurang)</option>
                        <option value="1" {{ (old('rating', $userReview->rating ?? 5) == 1) ? 'selected' : '' }}>⭐ (1 - Kecewa)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tulis Komentar / Ulasan:</label>
                    <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan kepuasan atau pengalaman Anda menggunakan karya ini..." maxlength="1000">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">
                    <i class="bi bi-send-fill me-1"></i> {{ $userReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                </button>
            </form>
        </div>
    @endif

    {{-- DAFTAR ULASAN --}}
    @if($reviews->isNotEmpty())
        <div class="d-flex flex-column gap-3">
            @foreach($reviews as $rev)
                <div class="p-3 border rounded-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rev->user->name ?? 'User') }}&background=e0e7ff&color=4338ca" 
                                 style="width: 32px; height: 32px; border-radius: 50%;" alt="user">
                            <div>
                                <span class="fw-bold small d-block">{{ $rev->user->name ?? 'Pembeli' }}</span>
                                <span class="text-muted" style="font-size: 11px;">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="text-warning small">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">{{ $rev->comment ?: 'Pembeli tidak meninggalkan komentar teks.' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-chat-square-dots fs-2 d-block mb-2 text-secondary opacity-50"></i>
            <span class="small">Belum ada ulasan untuk produk ini. Jadilah pembeli pertama yang memberikan ulasan!</span>
        </div>
    @endif
</div>

{{-- PRODUK LAIN DARI KREATOR INI --}}
@if ($produkLain->isNotEmpty())
<div class="mt-5">
    <h5 class="fw-bold mb-3">Produk Lain dari {{ $product->seller->name ?? 'Kreator Ini' }}</h5>
    <div class="product-grid">
        @foreach ($produkLain as $item)
            <div class="product-card">
                <div class="product-thumb">
                    <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : ($item->image_url ?? 'https://placehold.co/400x300?text=Produk+Karyaku') }}" 
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

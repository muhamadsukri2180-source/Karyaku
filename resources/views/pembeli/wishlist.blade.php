@extends('layouts.pembeli')

@section('title', 'Disukai - Karyaku')

@section('content')

    <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="fw-extrabold text-dark mb-1">
                <i class="bi bi-heart-fill text-danger me-2"></i>Disukai
            </h4>
            <p class="text-muted small mb-0">Daftar produk karya digital favorit yang Anda simpan untuk dibeli nanti.</p>
        </div>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-3">
            <i class="bi bi-shop me-1"></i> Jelajahi Marketplace
        </a>
    </div>

    @if ($wishlists->isEmpty())
        <div class="card-box p-5 text-center text-muted">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-heartbreak fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Produk yang Disukai</h5>
            <p class="small text-muted mb-4">Simpan produk-produk favorit yang Anda sukai dengan menekan ikon hati di marketplace.</p>
            <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-semibold rounded-3 shadow-sm">
                <i class="bi bi-shop me-1"></i> Cari Produk Favorit
            </a>
        </div>
    @else
        <div class="product-grid">
            @foreach ($wishlists as $item)
                @if($item->product)
                    <div data-wishlist-row="true">
                        @include('pembeli.partials.product-card', [
                            'product' => $item->product,
                            'wishlistIds' => [$item->product->id_product]
                        ])
                    </div>
                @endif
            @endforeach
        </div>

        @if ($wishlists->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $wishlists->links() }}
            </div>
        @endif
    @endif

@endsection
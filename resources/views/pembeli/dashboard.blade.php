@extends('layouts.pembeli')
@section('title', 'Dashboard')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Halo, {{ $navUser->name ?? 'Pembeli' }} 👋</h4>
    <p class="text-muted mb-0" style="font-size: 13px;">Berikut ringkasan aktivitas belanja kamu di Karyaku.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalPesanan }}</div>
                <div class="label">Total Pesanan</div>
            </div>
            <div class="icon" style="background:#2563eb;"><i class="bi bi-receipt"></i></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalSelesai }}</div>
                <div class="label">Pesanan Selesai</div>
            </div>
            <div class="icon" style="background:#10b981;"><i class="bi bi-check2-circle"></i></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value" style="font-size:17px;">Rp{{ number_format($totalBelanja, 0, ',', '.') }}</div>
                <div class="label">Total Belanja</div>
            </div>
            <div class="icon" style="background:#FF7A59;"><i class="bi bi-wallet2"></i></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex justify-content-between align-items-start">
            <div>
                <div class="value">{{ $totalWishlist }}</div>
                <div class="label">Wishlist</div>
            </div>
            <div class="icon" style="background:#e11d48;"><i class="bi bi-heart-fill"></i></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-box p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
                <a href="{{ route('pembeli.pesanan') }}" class="small fw-semibold" style="color: var(--primary);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>

            @forelse ($recentOrders as $order)
                <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="d-flex align-items-center justify-content-between py-3 border-bottom text-decoration-none text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width:42px;height:42px;background:var(--primary-light);color:var(--primary);">
                            <i class="bi bi-bag-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $order->kode_order }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $order->items->first()->product->title ?? '-' }}@if($order->items->count() > 1) (+{{ $order->items->count() - 1 }} lainnya)@endif</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold small" style="color: var(--coral);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</div>
                        @php
                            $statusColor = match($order->status) {
                                'selesai' => 'bg-success-subtle text-success',
                                'dibatalkan' => 'bg-danger-subtle text-danger',
                                default => 'bg-warning-subtle text-warning',
                            };
                        @endphp
                        <span class="badge-status {{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Belum ada pesanan. Yuk mulai belanja di <a href="{{ route('pembeli.marketplace') }}">Marketplace</a>.
                </div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-box p-4 h-100">
            <h6 class="fw-bold mb-3">Akses Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('pembeli.marketplace') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-shop"></i> Jelajahi Marketplace</a>
                <a href="{{ route('pembeli.keranjang') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-cart-fill"></i> Lihat Keranjang ({{ $totalKeranjang }})</a>
                <a href="{{ route('pembeli.wishlist') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-heart-fill"></i> Wishlist Saya ({{ $totalWishlist }})</a>
                <a href="{{ route('pembeli.download') }}" class="btn-add-cart" style="justify-content:flex-start; padding:12px 14px;"><i class="bi bi-cloud-arrow-down-fill"></i> Karya yang Sudah Dibeli</a>
            </div>
        </div>
    </div>
</div>

@if ($rekomendasi->isNotEmpty())
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Rekomendasi Untuk Kamu</h6>
        <a href="{{ route('pembeli.marketplace') }}" class="small fw-semibold" style="color: var(--primary);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="product-grid">
        @foreach ($rekomendasi as $product)
            @include('pembeli.partials.product-card', ['product' => $product, 'wishlistIds' => []])
        @endforeach
    </div>
</div>
@endif

@endsection

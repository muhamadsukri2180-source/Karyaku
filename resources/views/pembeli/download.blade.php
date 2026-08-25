@extends('layouts.pembeli')
@section('title', 'Download Saya')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-cloud-arrow-down-fill text-primary me-2"></i>Karya Digital yang Telah Dibeli</h4>
        <p class="text-muted small mb-0">Unduh berkas asli produk dan aset digital yang sudah Anda bayar.</p>
    </div>
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Belanja Lagi
    </a>
</div>

@if ($orderItems->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-cloud-arrow-down fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Berkas yang Dapat Diunduh</h5>
        <p class="small text-muted mb-4">Karya digital Anda akan otomatis muncul dan dapat diunduh tanpa batas setelah pesanan terkonfirmasi lunas.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
            <i class="bi bi-shop me-1"></i> Jelajahi Marketplace
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach ($orderItems as $item)
            @php
                $prod = $item->product;
                $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&size=256&name=' . urlencode($prod->title ?? 'Karyaku'));
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card-box p-3 h-100 d-flex flex-column justify-content-between border rounded-4 hover-shadow transition-all">
                    <div>
                        <div class="d-flex gap-3 align-items-center mb-3">
                            <img src="{{ $thumb }}" 
                                 alt="{{ $prod->title ?? 'Produk' }}" 
                                 class="rounded-3 object-fit-cover flex-shrink-0 border" 
                                 style="width: 70px; height: 70px;"
                                 onerror="this.src='https://placehold.co/100x100?text=Digital+File'">
                            <div class="overflow-hidden">
                                <span class="badge bg-primary-subtle text-primary mb-1 font-weight-bold" style="font-size: 10px;">
                                    {{ $prod->category->name ?? 'Aset Digital' }}
                                </span>
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14px;">
                                    <a href="{{ $prod ? route('pembeli.produk.detail', $prod->id_product) : '#' }}" class="text-dark text-decoration-none">
                                        {{ $prod->title ?? 'Produk telah dihapus' }}
                                    </a>
                                </h6>
                                <span class="text-muted small d-block" style="font-size: 11px;">
                                    <i class="bi bi-person me-1"></i> {{ $prod->seller->name ?? 'Kreator Karyaku' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-2 bg-light rounded-3 small text-muted mb-3" style="font-size: 11px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Order ID:</span>
                                <strong class="text-dark">#{{ $item->order->kode_order ?? $item->order->id_order }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tanggal Beli:</span>
                                <span class="text-dark">{{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        @if ($prod && $prod->file)
                            <a href="{{ route('pembeli.download.file', $item->id_order_item) }}" class="btn btn-primary btn-sm flex-fill fw-semibold py-2">
                                <i class="bi bi-download me-1"></i> Unduh File
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm flex-fill py-2" disabled>
                                <i class="bi bi-slash-circle me-1"></i> File Tidak Tersedia
                            </button>
                        @endif
                        @if($prod)
                            <a href="{{ route('pembeli.produk.detail', $prod->id_product) }}" class="btn btn-outline-secondary btn-sm px-2.5 py-2" title="Lihat Detail & Ulas">
                                <i class="bi bi-star"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($orderItems->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orderItems->links() }}
        </div>
    @endif
@endif

@endsection

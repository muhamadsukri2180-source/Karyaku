@extends('layouts.pembeli')
@section('title', 'Pesanan Saya')

@section('content')

<div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h4 class="fw-bold mb-1">Daftar Pesanan Saya</h4>
        <p class="text-muted mb-0 small">Pantau status transaksi, riwayat belanja, dan unduh berkas digital pesanan Anda.</p>
    </div>
    <a href="{{ route('pembeli.download') }}" class="btn btn-outline-primary btn-sm fw-bold">
        <i class="bi bi-cloud-arrow-down-fill me-1"></i> File Download Saya
    </a>
</div>

@forelse ($orders as $order)
    <div class="card-box p-4 mb-3 border hover-shadow transition-all" style="border-radius: 16px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-primary-light text-primary rounded-3" style="width: 42px; height: 42px;">
                    <i class="bi bi-bag-check fs-5"></i>
                </div>
                <div>
                    <strong class="text-dark d-block" style="font-size: 14px;">
                        Order #{{ $order->kode_order ?? $order->id_order }}
                    </strong>
                    <span class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if ($order->payment_status === 'paid')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill font-weight-bold" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill me-1"></i> Pembayaran Lunas
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill font-weight-bold" style="font-size: 11px;">
                        <i class="bi bi-clock me-1"></i> Belum Lunas
                    </span>
                @endif

                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill text-capitalize font-weight-bold" style="font-size: 11px;">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        {{-- ITEMS LIST --}}
        <div class="d-flex flex-column gap-3 mb-3">
            @foreach ($order->items as $item)
                @php $product = $item->product; @endphp
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $product->image_url ?? asset('storage/' . ($product->image ?? '')) }}" 
                         alt="{{ $product->title ?? 'Produk' }}" 
                         class="rounded-3 object-fit-cover flex-shrink-0"
                         style="width: 55px; height: 55px;"
                         onerror="this.src='https://placehold.co/100x100?text=Produk'">

                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fw-bold mb-1 text-truncate" style="font-size: 13.5px;">
                            {{ $product->title ?? 'Produk Digital' }}
                        </h6>
                        <div class="text-muted small">
                            {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="fw-bold text-dark flex-shrink-0" style="font-size: 14px;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FOOTER PESANAN --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border-top pt-3">
            <div>
                <span class="text-muted small">Total Pembayaran:</span>
                <strong class="text-primary fs-6 ms-1">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </strong>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if ($order->payment_status === 'paid')
                    <a href="{{ route('pembeli.download') }}" class="btn btn-sm btn-outline-success fw-bold">
                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Unduh Berkas
                    </a>
                @endif
                <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="btn btn-sm btn-primary fw-bold px-3">
                    Detail Pesanan <i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="card-box p-5 text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width:70px;height:70px;">
            <i class="bi bi-journal-x fs-2"></i>
        </div>
        <h5 class="fw-bold">Belum Ada Pesanan</h5>
        <p class="text-muted small mb-4">Anda belum melakukan transaksi apapun di Karyaku.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-3">
            <i class="bi bi-shop me-1"></i> Mulai Belanja Now
        </a>
    </div>
@endforelse

<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>

@endsection

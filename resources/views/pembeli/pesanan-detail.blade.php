@extends('layouts.pembeli')
@section('title', 'Detail Pesanan #' . ($order->kode_order ?? $order->id_order))

@section('content')

{{-- BACK & TITLE --}}
<div class="mb-4">
    <a href="{{ route('pembeli.pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
    </a>
    <h4 class="fw-bold mb-1">Rincian Detail Pesanan</h4>
    <p class="text-muted mb-0 small">Kode Transaksi: <strong>#{{ $order->kode_order ?? $order->id_order }}</strong> &middot; {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
</div>

<div class="row g-4 mb-4">
    
    {{-- KOLOM KIRI: RINCIAN ITEM & DOWNLOAD --}}
    <div class="col-lg-8">
        {{-- STATUS BANNER --}}
        <div class="card-box p-4 mb-4 border shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                        <i class="bi bi-bag-check-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Status Pesanan: <span class="text-capitalize text-primary">{{ $order->status }}</span></h6>
                        <p class="mb-0 small text-muted">Status Pembayaran: 
                            @if ($order->payment_status === 'paid')
                                <strong class="text-success"><i class="bi bi-check-circle-fill"></i> LUNAS</strong>
                            @else
                                <strong class="text-danger"><i class="bi bi-clock-fill"></i> BELUM LUNAS</strong>
                            @endif
                        </p>
                    </div>
                </div>

                @if ($order->payment_status === 'paid')
                    <a href="{{ route('pembeli.download') }}" class="btn btn-success fw-bold px-3 py-2 rounded-3 text-white">
                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download Berkas
                    </a>
                @endif
            </div>
        </div>

        {{-- DAFTAR PRODUK YANG DIBELI --}}
        <div class="card-box p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-3">Produk yang Dibeli</h6>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Produk</th>
                            <th>Penjual</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            @php $product = $item->product; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product->image_url ?? asset('storage/' . ($product->image ?? '')) }}" 
                                             alt="{{ $product->title ?? 'Produk' }}" 
                                             class="rounded-3 object-fit-cover" 
                                             style="width: 50px; height: 50px;"
                                             onerror="this.src='https://placehold.co/100x100?text=Produk'">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 13.5px;">
                                                <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="text-dark">{{ $product->title ?? 'Produk Digital' }}</a>
                                            </h6>
                                            <span class="text-muted small">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-secondary">
                                        {{ $product->seller->name ?? 'Kreator Karyaku' }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-primary">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: RINGKASAN PEMBAYARAN --}}
    <div class="col-lg-4">
        <div class="card-box p-4 position-sticky" style="top: 90px;">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Rincian Pembayaran</h6>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Metode Pembayaran</span>
                <strong class="small text-dark">{{ $order->payment_method ?? 'Transfer / Online' }}</strong>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Total Harga Item</span>
                <span class="small font-weight-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            <hr class="my-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="text-dark">Total Pembayaran</strong>
                <strong class="text-primary fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </div>

            @if ($order->payment_status === 'paid')
                <div class="alert alert-success small mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i> Transaksi telah berhasil diverifikasi oleh sistem.
                </div>
            @else
                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Menunggu konfirmasi pembayaran dari sistem.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

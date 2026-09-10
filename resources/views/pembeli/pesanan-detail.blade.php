@extends('layouts.pembeli')
@section('title', 'Detail Pesanan #' . ($order->kode_order ?? $order->id_order))

@section('content')

{{-- BACK & TITLE --}}
<div class="mb-4">
    <a href="{{ route('pembeli.pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
    </a>
    <h4 class="fw-extrabold text-dark mb-1">Rincian Detail Pesanan</h4>
    <p class="text-muted mb-0 small">Kode Transaksi: <strong>#{{ $order->kode_order ?? $order->id_order }}</strong> &middot; {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
</div>

<div class="row g-4 mb-4">
    {{-- KOLOM KIRI: RINCIAN ITEM & DOWNLOAD --}}
    <div class="col-lg-8">
        {{-- STATUS BANNER --}}
        <div class="card-box p-4 mb-4 border shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                        <i class="bi bi-bag-check-fill fs-4"></i>
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
            <h6 class="fw-bold mb-3 border-bottom pb-3 text-dark">Daftar Produk yang Dibeli</h6>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Produk</th>
                            <th>Penjual</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            @if($order->payment_status === 'paid')
                                <th class="text-center">Aksi Berkas</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            @php $product = $item->product; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product && $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/100x100?text=Produk' }}" 
                                             alt="{{ $product->title ?? 'Produk' }}" 
                                             class="rounded-3 object-fit-cover border flex-shrink-0" 
                                             style="width: 50px; height: 50px;"
                                             onerror="this.src='https://placehold.co/100x100?text=Produk'">
                                        <div class="overflow-hidden">
                                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 13.5px; max-width: 220px;">
                                                <a href="{{ $product ? route('pembeli.produk.detail', $product->id_product) : '#' }}" class="text-dark text-decoration-none">{{ $product->title ?? 'Produk Digital' }}</a>
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
                                @if($order->payment_status === 'paid')
                                    <td class="text-center">
                                        @if($product && $product->file)
                                            <a href="{{ route('pembeli.download.file', $item->id_order_item) }}" class="btn btn-success btn-sm fw-bold px-2 py-1 rounded-2">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">TIDAK ADA FILE</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: RINGKASAN PEMBAYARAN --}}
    <div class="col-lg-4">
        <div class="card-box p-4">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Ringkasan Tagihan</h6>
            
            <div class="d-flex justify-content-between mb-2 small text-muted">
                <span>Subtotal Produk:</span>
                <span class="text-dark fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 small text-muted">
                <span>Biaya Layanan:</span>
                <span class="text-success fw-semibold">Rp 0</span>
            </div>
            
            <hr class="my-3">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="text-dark">Total Pembayaran:</strong>
                <span class="h4 fw-extrabold text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            <div class="p-3 bg-light rounded-3 small text-muted mt-3">
                <div class="d-flex align-items-center gap-2 mb-1 text-dark fw-bold">
                    <i class="bi bi-shield-check text-success"></i> Jaminan Akses File
                </div>
                File produk yang telah dibayar dapat diakses selamanya di menu <strong>Download Saya</strong>.
            </div>
        </div>
    </div>
</div>

@endsection

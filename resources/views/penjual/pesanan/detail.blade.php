@extends('layouts.penjual')
@section('title', 'Rincian Pesanan #' . ($orderItem->order->id_order ?? $orderItem->order_id))

@section('content')

<div class="mb-4">
    <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan
    </a>
    <h4 class="fw-bold text-dark mb-1">Rincian Transaksi Pembelian</h4>
    <p class="text-muted small mb-0">Informasi detail pesanan karya digital dari pelanggan.</p>
</div>

@php
    $order = $orderItem->order;
    $buyer = $order->buyer ?? null;
    $product = $orderItem->product;
    $isPaid = ($order->payment_status ?? '') === 'paid';
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-box p-4 border mb-4">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Informasi Produk Dibeli</h6>
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/100x100?text=Karya' }}" 
                     alt="{{ $product->title ?? 'Produk' }}" class="rounded-3 object-fit-cover border" style="width: 80px; height: 80px;">
                <div>
                    <span class="badge bg-primary-subtle text-primary font-weight-bold mb-1" style="font-size: 10.5px;">
                        {{ $product->category->name ?? 'Kategori' }}
                    </span>
                    <h5 class="fw-bold text-dark mb-1">{{ $product->title ?? 'Produk' }}</h5>
                    <div class="text-muted small">
                        Harga Satuan: <strong class="text-dark">Rp {{ number_format($orderItem->price, 0, ',', '.') }}</strong> &bull; Qty: <strong>{{ $orderItem->quantity }}x</strong>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark">Subtotal Penghasilan Penjual:</span>
                <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="card-box p-4 border">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Data Pelanggan (Pembeli)</h6>
            <div class="row g-3 small">
                <div class="col-sm-6">
                    <span class="text-muted d-block">Nama Pembeli:</span>
                    <strong class="text-dark fs-6">{{ $buyer->name ?? 'Pengguna' }}</strong>
                </div>
                <div class="col-sm-6">
                    <span class="text-muted d-block">Email:</span>
                    <strong class="text-dark">{{ $buyer->email ?? '-' }}</strong>
                </div>
                <div class="col-sm-6">
                    <span class="text-muted d-block">No. Telepon / WhatsApp:</span>
                    <strong class="text-dark">{{ $buyer->phone ?? '-' }}</strong>
                </div>
                <div class="col-sm-6">
                    <span class="text-muted d-block">Waktu Transaksi:</span>
                    <strong class="text-dark">{{ $orderItem->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-box p-4 border">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Status Pembayaran</h6>
            <div class="mb-3 text-center">
                @if($isPaid)
                    <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle text-success">
                        <i class="bi bi-check-circle-fill fs-2 d-block mb-1"></i>
                        <h6 class="fw-bold mb-0">LUNAS</h6>
                        <small>Pembeli telah membayar & dapat mengunduh berkas.</small>
                    </div>
                @else
                    <div class="p-3 bg-warning-subtle rounded-3 border border-warning-subtle text-warning">
                        <i class="bi bi-clock-history fs-2 d-block mb-1"></i>
                        <h6 class="fw-bold mb-0">MENUNGGU PEMBAYARAN</h6>
                        <small>Menunggu verifikasi pembayaran oleh sistem.</small>
                    </div>
                @endif
            </div>

            <div class="small text-muted mb-3">
                Saldo hasil penjualan dari pesanan lunas akan otomatis masuk ke <strong>Saldo Tersedia</strong> Anda dan siap ditarik kapan saja.
            </div>

            <a href="{{ route('penjual.keuangan.index') }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                <i class="bi bi-wallet2 me-1"></i> Cek Saldo & Penarikan
            </a>
        </div>
    </div>
</div>

@endsection

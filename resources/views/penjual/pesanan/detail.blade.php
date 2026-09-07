@extends('layouts.penjual')
@section('title', 'Rincian Pesanan #' . ($orderItem->order->id_order ?? $orderItem->order_id))

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); }
    .info-label { font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 3px; }
</style>

<div class="seller-page-head mb-4">
    <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold mb-2 rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan</a>
    <h4 class="mb-1">Rincian Transaksi Pembelian</h4>
    <p class="small mb-0">Informasi detail pesanan karya digital dari pelanggan.</p>
</div>

@php
    $order = $orderItem->order;
    $buyer = $order->buyer ?? null;
    $product = $orderItem->product;
    $isPaid = ($order->payment_status ?? '') === 'paid';
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="kk-card p-4 mb-4">
            <h6 class="fw-bold mb-3 pb-2" style="color:var(--text-dark); border-bottom:1px solid var(--border-color);"><i class="bi bi-box-seam me-2" style="color:var(--primary);"></i>Informasi Produk Dibeli</h6>
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/100x100?text=Karya' }}"
                     alt="{{ $product->title ?? 'Produk' }}" class="rounded-3 object-fit-cover border" style="width: 80px; height: 80px;">
                <div>
                    <span class="badge fw-bold mb-1" style="font-size: 10.5px; background:var(--primary-light); color:var(--primary);">{{ $product->category->name ?? 'Kategori' }}</span>
                    <h5 class="fw-bold mb-1" style="color:var(--text-dark);">{{ $product->title ?? 'Produk' }}</h5>
                    <div class="small" style="color:var(--text-muted);">Harga Satuan: <strong style="color:var(--text-dark);">Rp {{ number_format($orderItem->price, 0, ',', '.') }}</strong> &bull; Qty: <strong>{{ $orderItem->quantity }}x</strong></div>
                </div>
            </div>
            <div class="mt-4 pt-3 d-flex justify-content-between align-items-center gap-3" style="border-top:1px solid var(--border-color);">
                <span class="fw-bold" style="color:var(--text-dark);">Subtotal Penghasilan Penjual:</span>
                <h4 class="fw-bold mb-0" style="color:var(--primary);">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="kk-card p-4">
            <h6 class="fw-bold mb-3 pb-2" style="color:var(--text-dark); border-bottom:1px solid var(--border-color);"><i class="bi bi-person-vcard me-2" style="color:var(--primary);"></i>Data Pelanggan (Pembeli)</h6>
            <div class="row g-3 small">
                <div class="col-sm-6"><span class="info-label">Nama Pembeli:</span><strong class="fs-6" style="color:var(--text-dark);">{{ $buyer->name ?? 'Pengguna' }}</strong></div>
                <div class="col-sm-6"><span class="info-label">Email:</span><strong style="color:var(--text-dark);">{{ $buyer->email ?? '-' }}</strong></div>
                <div class="col-sm-6"><span class="info-label">No. Telepon / WhatsApp:</span><strong style="color:var(--text-dark);">{{ $buyer->phone ?? '-' }}</strong></div>
                <div class="col-sm-6"><span class="info-label">Waktu Transaksi:</span><strong style="color:var(--text-dark);">{{ $orderItem->created_at->translatedFormat('d F Y, H:i') }} WIB</strong></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="kk-card p-4">
            <h6 class="fw-bold mb-3 pb-2" style="color:var(--text-dark); border-bottom:1px solid var(--border-color);"><i class="bi bi-credit-card me-2" style="color:var(--primary);"></i>Status Pembayaran</h6>
            <div class="mb-3 text-center">
                @if($isPaid)
                    <div class="p-3 rounded-3" style="background:#ecfdf5; border:1px solid #bbf7d0; color:#16a34a;">
                        <i class="bi bi-check-circle-fill fs-2 d-block mb-1"></i>
                        <h6 class="fw-bold mb-0">LUNAS</h6>
                        <small>Pembeli telah membayar & dapat mengunduh berkas.</small>
                    </div>
                @else
                    <div class="p-3 rounded-3 mb-3" style="background:#fff7ed; border:1px solid #fed7aa; color:#f59e0b;">
                        <i class="bi bi-clock-history fs-2 d-block mb-1"></i>
                        <h6 class="fw-bold mb-0">MENUNGGU KONFIRMASI</h6>
                        <small>Silakan konfirmasi pesanan jika pembayaran pembeli telah Anda terima.</small>
                    </div>
                    <form action="{{ route('penjual.pesanan.konfirmasi', $orderItem->id_order_item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi pesanan ini? Pembeli akan langsung dapat mengunduh berkas digital.');">
                        @csrf
                        <button type="submit" class="btn btn-sm w-100 fw-bold py-2 shadow-sm mb-2 rounded-3" style="background:#16a34a; color:#fff;"><i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Pembelian</button>
                    </form>
                @endif
            </div>
            <div class="small mb-3" style="color:var(--text-muted);">Saldo hasil penjualan dari pesanan lunas akan otomatis masuk ke <strong>Saldo Tersedia</strong> Anda dan siap ditarik kapan saja.</div>
            <a href="{{ route('penjual.keuangan.index') }}" class="btn btn-sm w-100 fw-semibold rounded-3" style="border:1px solid var(--primary); color:var(--primary);"><i class="bi bi-wallet2 me-1"></i> Cek Saldo & Penarikan</a>
        </div>
    </div>
</div>

@endsection
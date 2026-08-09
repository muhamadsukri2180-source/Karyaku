@extends('layouts.pembeli')
@section('title', 'Detail Pesanan')

@section('content')

<a href="{{ route('pembeli.pesanan') }}" class="text-decoration-none small text-muted d-inline-block mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Pesanan</a>

@php
    $statusColor = match($order->status) {
        'selesai' => 'bg-success-subtle text-success',
        'dibatalkan' => 'bg-danger-subtle text-danger',
        default => 'bg-warning-subtle text-warning',
    };
    $payColor = $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
@endphp

<div class="card-box p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h5 class="fw-bold mb-1">{{ $order->kode_order }}</h5>
            <div class="text-muted small">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge-status {{ $statusColor }}">{{ ucfirst($order->status) }}</span>
            <span class="badge-status {{ $payColor }}">{{ ucfirst($order->payment_status) }}</span>
        </div>
    </div>
</div>

<div class="card-box p-3 mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kreator</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td class="small fw-semibold">{{ $item->product->title ?? 'Produk telah dihapus' }}</td>
                    <td class="small">{{ $item->product->seller->name ?? '-' }}</td>
                    <td class="small">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="small">{{ $item->quantity }}</td>
                    <td class="fw-bold small" style="color:var(--coral);">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="fw-bold" style="color:var(--coral);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@if ($order->payment_status === 'paid')
<div class="card-box p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="text-success small"><i class="bi bi-check-circle-fill"></i> Pesanan ini sudah dibayar. Karya bisa diunduh di halaman Download.</div>
    <a href="{{ route('pembeli.download') }}" class="btn btn-sm btn-primary">Ke Halaman Download</a>
</div>
@else
<div class="card-box p-4 text-warning small">
    <i class="bi bi-exclamation-circle-fill"></i> Pesanan ini belum dibayar. Silakan selesaikan pembayaran sesuai instruksi dari admin.
</div>
@endif

@endsection

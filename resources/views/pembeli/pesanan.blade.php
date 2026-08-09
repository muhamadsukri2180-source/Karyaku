@extends('layouts.pembeli')
@section('title', 'Pesanan Saya')

@section('content')

<h4 class="fw-bold mb-4">Pesanan Saya</h4>

@if ($orders->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-receipt fs-1 d-block mb-3"></i>
        Kamu belum memiliki pesanan.
        <div class="mt-3"><a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm">Mulai Belanja</a></div>
    </div>
@else
<div class="card-box p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                @php
                    $statusColor = match($order->status) {
                        'selesai' => 'bg-success-subtle text-success',
                        'dibatalkan' => 'bg-danger-subtle text-danger',
                        default => 'bg-warning-subtle text-warning',
                    };
                    $payColor = $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
                @endphp
                <tr>
                    <td class="fw-semibold small">{{ $order->kode_order }}</td>
                    <td class="small">
                        {{ $order->items->first()->product->title ?? 'Produk telah dihapus' }}
                        @if($order->items->count() > 1) <span class="text-muted">(+{{ $order->items->count() - 1 }} lainnya)</span> @endif
                    </td>
                    <td class="fw-bold small" style="color:var(--coral);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><span class="badge-status {{ $payColor }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td><span class="badge-status {{ $statusColor }}">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $orders->links() }}
</div>
@endif

@endsection

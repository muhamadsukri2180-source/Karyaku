@extends('layouts.penjual')
@section('title', 'Pesanan Masuk')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-receipt-cutoff text-primary me-2"></i>Pesanan Masuk</h4>
    <p class="text-muted small mb-0">Daftar transaksi pembelian karya digital Anda dari para pembeli.</p>
</div>

{{-- TAB FILTER PESANAN --}}
<div class="card-box p-3 mb-4">
    <ul class="nav nav-pills gap-2">
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : 'text-secondary' }}" 
               href="{{ route('penjual.pesanan.index', ['tab' => 'semua']) }}">
                Semua Pesanan <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? '') === 'diproses' ? 'active fw-bold' : 'text-secondary' }}" 
               href="{{ route('penjual.pesanan.index', ['tab' => 'diproses']) }}">
                <i class="bi bi-hourglass-split me-1"></i> Perlu Diproses / Pending <span class="badge {{ ($tab ?? '') === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['diproses'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? '') === 'selesai' ? 'active fw-bold' : 'text-secondary' }}" 
               href="{{ route('penjual.pesanan.index', ['tab' => 'selesai']) }}">
                <i class="bi bi-check-circle me-1"></i> Selesai <span class="badge {{ ($tab ?? '') === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['selesai'] }}</span>
            </a>
        </li>
    </ul>
</div>

{{-- DAFTAR PESANAN --}}
@if($orderItems->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-cart-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan</h5>
        <p class="small text-muted mb-0">Belum ada transaksi pembelian produk pada tab filter ini.</p>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($orderItems as $item)
            @php
                $order = $item->order;
                $buyer = $order->buyer ?? null;
                $isPaid = ($order->payment_status ?? '') === 'paid';
            @endphp
            <div class="card-box p-3 border hover-shadow">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}" 
                             alt="{{ $item->product->title ?? 'Produk' }}" class="rounded-3 object-fit-cover flex-shrink-0 border" style="width: 65px; height: 65px;">
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">
                                    ORDER #{{ $order->id_order ?? $item->order_id }}
                                </span>
                                @if($isPaid)
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i> Pembayaran Lunas</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning"><i class="bi bi-clock-fill me-1"></i> Menunggu Pembayaran</span>
                                @endif
                                <span class="text-muted" style="font-size: 11px;">&bull; {{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>

                            <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14.5px;">{{ $item->product->title ?? 'Produk Karya Digital' }}</h6>
                            <div class="small text-muted" style="font-size: 12px;">
                                Pembeli: <strong class="text-dark">{{ $buyer->name ?? 'Pengguna' }}</strong> ({{ $buyer->email ?? '-' }}) &bull; Qty: <strong>{{ $item->quantity }}x</strong>
                            </div>
                        </div>
                    </div>

                    <div class="text-md-end flex-shrink-0 d-flex flex-md-column justify-content-between align-items-end gap-2">
                        <div>
                            <div class="text-muted small" style="font-size: 11px;">Total Pendapatan:</div>
                            <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</h5>
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$isPaid)
                                <form action="{{ route('penjual.pesanan.konfirmasi', $item->id_order_item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi pembelian ini? Pembeli akan mendapatkan akses unduh berkas digital.');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Pembelian
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('penjual.pesanan.detail', $item->id_order_item) }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orderItems->links() }}
    </div>
@endif

@endsection

@extends('layouts.penjual')
@section('title', 'Pesanan Masuk')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.10);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); }
    .kk-card-hover { transition: .2s ease; }
    .kk-card-hover:hover { box-shadow: var(--shadow-hover); transform: translateY(-1px); }
    .kk-thumb { width: 65px; height: 65px; object-fit: cover; border-radius: 14px; flex-shrink: 0; }
    .kk-tabs .nav-link { border-radius: 10px; font-size: 12px; font-weight: 600; padding: 9px 13px; color: var(--text-muted); }
    .kk-tabs .nav-link.active { background: var(--primary); color: #fff !important; box-shadow: 0 5px 14px rgba(37,99,235,.18); }
</style>

<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-receipt-cutoff me-2" style="color:var(--primary);"></i>Pesanan Masuk</h4>
    <p class="small mb-0">Daftar transaksi pembelian karya digital Anda dari para pembeli.</p>
</div>

{{-- TAB FILTER PESANAN --}}
<div class="kk-card p-3 mb-4">
    <ul class="nav nav-pills gap-2 kk-tabs flex-wrap">
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'semua']) }}">
                Semua Pesanan <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? '') === 'diproses' ? 'active fw-bold' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'diproses']) }}">
                <i class="bi bi-hourglass-split me-1"></i> Perlu Diproses / Pending <span class="badge {{ ($tab ?? '') === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['diproses'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($tab ?? '') === 'selesai' ? 'active fw-bold' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'selesai']) }}">
                <i class="bi bi-check-circle me-1"></i> Selesai <span class="badge {{ ($tab ?? '') === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['selesai'] }}</span>
            </a>
        </li>
    </ul>
</div>

{{-- DAFTAR PESANAN --}}
@if($orderItems->isEmpty())
    <div class="kk-card p-5 text-center" style="color:var(--text-muted);">
        <i class="bi bi-cart-x fs-1 d-block mb-3 opacity-50"></i>
        <h5 class="fw-bold mb-1" style="color:var(--text-dark);">Belum Ada Pesanan</h5>
        <p class="small mb-0">Belum ada transaksi pembelian produk pada tab filter ini.</p>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($orderItems as $item)
            @php
                $order = $item->order;
                $buyer = $order->buyer ?? null;
                $isPaid = ($order->payment_status ?? '') === 'paid';
            @endphp
            <div class="kk-card kk-card-hover p-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}"
                             alt="{{ $item->product->title ?? 'Produk' }}" class="kk-thumb border">
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge" style="font-size: 10px; background:#f1f5f9; color:#64748b;">ORDER #{{ $order->id_order ?? $item->order_id }}</span>
                                @if($isPaid)
                                    <span class="badge" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-shield-check me-1"></i> Lunas (Terverifikasi)</span>
                                @elseif(($order->payment_status ?? '') === 'pending')
                                    <span class="badge" style="background:#fef3c7; color:#d97706;"><i class="bi bi-hourglass-split me-1"></i> Sedang Diverifikasi Verifikator</span>
                                @elseif(in_array(($order->payment_status ?? ''), ['failed', 'rejected']))
                                    <span class="badge" style="background:#ffe4e6; color:#e11d48;"><i class="bi bi-x-circle me-1"></i> Pembayaran Ditolak</span>
                                @else
                                    <span class="badge" style="background:#f1f5f9; color:#64748b;"><i class="bi bi-clock-fill me-1"></i> Menunggu Pembayaran Pembeli</span>
                                @endif
                                <span style="font-size: 11px; color:var(--text-muted);">&bull; {{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14.5px;">{{ $item->product->title ?? 'Produk Karya Digital' }}</h6>
                            <div class="small" style="font-size: 12px; color:var(--text-muted);">Pembeli: <strong style="color:var(--text-dark);">{{ $buyer->name ?? 'Pengguna' }}</strong> (@safeEmail($buyer->email ?? '-')) &bull; Qty: <strong>{{ $item->quantity }}x</strong></div>
                        </div>
                    </div>

                    <div class="text-md-end flex-shrink-0 d-flex flex-md-column justify-content-between align-items-end gap-2">
                        <div>
                            <div class="small" style="font-size: 11px; color:var(--text-muted);">Total Pendapatan:</div>
                            <h5 class="fw-bold mb-0" style="color:var(--primary);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('penjual.pesanan.detail', $item->id_order_item) }}" class="btn btn-sm fw-semibold rounded-3" style="border:1px solid var(--primary); color:var(--primary);"><i class="bi bi-eye me-1"></i> Detail Pesanan</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">{{ $orderItems->links() }}</div>
@endif

@endsection
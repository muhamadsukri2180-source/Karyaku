@extends('layouts.pembeli')

@section('title', 'Pesanan Saya - Karyaku')

@push('styles')
<style>
    .order-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        transition: all 0.25s ease;
        overflow: hidden;
    }
    .order-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }
    .order-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        padding: 16px 20px;
    }
    .order-body {
        padding: 20px;
    }
    .order-footer {
        background: #f8fafc;
        border-top: 1px solid var(--border-color);
        padding: 16px 20px;
    }
    .nav-pills .nav-link {
        color: var(--text-muted);
        background: #ffffff;
        border: 1px solid var(--border-color);
        font-weight: 600;
        font-size: 13px;
        padding: 10px 18px;
        transition: all 0.2s ease;
    }
    .nav-pills .nav-link:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: #dbeafe;
    }
    .nav-pills .nav-link.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    @media(max-width: 576px) {
        .order-card { border-radius: 16px; }
        .order-header { padding: 12px 14px; }
        .order-body { padding: 12px 14px; }
        .order-footer { padding: 12px 14px; }
        .nav-pills .nav-link { padding: 7px 13px; font-size: 12px; }
    }
</style>
@endpush

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h4 class="fw-extrabold text-dark mb-1">
                <i class="bi bi-receipt me-2 text-primary"></i>Daftar Pesanan Saya
            </h4>
            <p class="text-muted mb-0 small">
                Pantau status pembayaran, riwayat transaksi, dan unduh berkas digital pesanan Anda.
            </p>
        </div>

        <a href="{{ route('pembeli.download') }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);"> File Download Saya
        </a>
    </div>

    {{-- TAB FILTER STATUS --}}
    <div class="card-box p-2 mb-4 rounded-4 shadow-sm border">
        <ul class="nav nav-pills gap-2 flex-nowrap overflow-x-auto pb-1" style="scrollbar-width: none;">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active' : '' }} rounded-pill d-inline-flex align-items-center gap-2" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'semua']) }}">
                    <i class="bi bi-collection"></i> Semua
                    <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} rounded-pill px-2">{{ $counts['semua'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'diproses' ? 'active' : '' }} rounded-pill d-inline-flex align-items-center gap-2" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'diproses']) }}">
                    <i class="bi bi-hourglass-split"></i> Diproses
                    <span class="badge {{ ($tab ?? '') === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }} rounded-pill px-2">{{ $counts['diproses'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'selesai' ? 'active' : '' }} rounded-pill d-inline-flex align-items-center gap-2" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'selesai']) }}">
                    <i class="bi bi-check2-circle"></i> Selesai
                    <span class="badge {{ ($tab ?? '') === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }} rounded-pill px-2">{{ $counts['selesai'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'dibatalkan' ? 'active' : '' }} rounded-pill d-inline-flex align-items-center gap-2" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'dibatalkan']) }}">
                    <i class="bi bi-x-circle"></i> Dibatalkan
                    <span class="badge {{ ($tab ?? '') === 'dibatalkan' ? 'bg-white text-primary' : 'bg-light text-dark border' }} rounded-pill px-2">{{ $counts['dibatalkan'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- DAFTAR PESANAN --}}
    <div class="d-flex flex-column gap-3">
        @forelse ($orders as $order)
            @php
                $isPaid = $order->payment_status === 'paid';
                $statusBadgeClass = match($order->status) {
                    'selesai' => 'bg-success-subtle text-success border border-success-subtle',
                    'dibatalkan' => 'bg-danger-subtle text-danger border border-danger-subtle',
                    default => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                };
            @endphp
            <div class="order-card">
                {{-- Header Pesanan (Tanpa Order ID) --}}
                <div class="order-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3 flex-shrink-0" style="width: 42px; height: 42px;">
                            <i class="bi bi-bag-check fs-5"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block" style="font-size: 14.5px;">
                                Transaksi Belanja
                            </strong>
                            <span class="text-muted small">
                                <i class="bi bi-calendar-event me-1"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge {{ $statusBadgeClass }} px-3 py-1.5 rounded-pill text-capitalize fw-bold" style="font-size: 11px;">
                            {{ $order->status }}
                        </span>
                        @if ($isPaid)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas
                            </span>
                        @elseif($order->payment_status === 'pending')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                <i class="bi bi-clock-fill me-1"></i> Menunggu Verifikasi
                            </span>
                        @elseif(in_array($order->payment_status, ['failed', 'rejected']))
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Belum Bayar
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Item Produk dalam Pesanan --}}
                <div class="order-body">
                    <div class="d-flex flex-column gap-2">
                        @foreach ($order->items as $item)
                            @php
                                $prod = $item->product;
                                $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=200&name=' . urlencode($prod->title ?? 'Produk'));
                            @endphp
                            <div class="d-flex align-items-center justify-content-between gap-3 py-2 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                    <img src="{{ $thumb }}" alt="{{ $prod->title ?? 'Produk' }}" class="rounded-3 object-fit-cover border flex-shrink-0" style="width: 52px; height: 52px;" onerror="this.src='https://placehold.co/100x100?text=Karyaku'">
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold text-dark text-truncate mb-0" style="font-size: 13.5px;">
                                            {{ $prod->title ?? 'Karya Digital' }}
                                        </h6>
                                        <span class="text-muted small">
                                            Jumlah: {{ $item->quantity }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold text-dark" style="font-size: 14px;">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Pesanan: Total & Aksi --}}
                <div class="order-footer d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <span class="text-muted small d-block">Total Tagihan:</span>
                        <span class="fw-extrabold text-primary h5 mb-0">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
                            <i class="bi bi-info-circle me-1"></i> Rincian
                        </a>
                        @if ($isPaid)
                            <a href="{{ route('pembeli.download') }}" class="btn btn-success btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm text-white d-inline-flex align-items-center gap-1">
                                <i class="bi bi-cloud-arrow-down-fill"></i> Unduh Berkas
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="order-card p-5 text-center text-muted">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-receipt-cutoff fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan</h5>
                <p class="small text-muted mb-4">Tidak ada riwayat transaksi pada filter status yang Anda pilih.</p>
                <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-shop"></i> Mulai Berbelanja
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

@endsection
@extends('layouts.pembeli')

@section('title', 'Pesanan Saya - Karyaku')

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

        <a href="{{ route('pembeli.download') }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3">
            <i class="bi bi-cloud-arrow-down-fill me-1"></i> File Download Saya
        </a>
    </div>

    {{-- TAB FILTER STATUS --}}
    <div class="card-box p-2 mb-4">
        <ul class="nav nav-pills gap-2 flex-nowrap overflow-x-auto pb-1" style="scrollbar-width: none;">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : 'text-secondary' }} rounded-3" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'semua']) }}">
                    <i class="bi bi-collection me-1"></i> Semua
                    <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'diproses' ? 'active fw-bold' : 'text-secondary' }} rounded-3" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'diproses']) }}">
                    <i class="bi bi-hourglass-split me-1"></i> Diproses
                    <span class="badge {{ ($tab ?? '') === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['diproses'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'selesai' ? 'active fw-bold' : 'text-secondary' }} rounded-3" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'selesai']) }}">
                    <i class="bi bi-check2-circle me-1"></i> Selesai
                    <span class="badge {{ ($tab ?? '') === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['selesai'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'dibatalkan' ? 'active fw-bold' : 'text-secondary' }} rounded-3" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'dibatalkan']) }}">
                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                    <span class="badge {{ ($tab ?? '') === 'dibatalkan' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['dibatalkan'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- DAFTAR PESANAN --}}
    @forelse ($orders as $order)
        @php
            $isPaid = $order->payment_status === 'paid';
            $statusBadgeClass = match($order->status) {
                'selesai' => 'bg-success text-white',
                'dibatalkan' => 'bg-danger text-white',
                default => 'bg-warning text-dark',
            };
        @endphp
        <div class="card-box p-4 mb-3">
            {{-- Header Pesanan --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-primary-light text-primary rounded-3 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-bag-check fs-5"></i>
                    </div>
                    <div>
                        <strong class="text-dark d-block" style="font-size: 14.5px;">
                            Order #{{ $order->kode_order ?? $order->id_order }}
                        </strong>
                        <span class="text-muted small">
                            <i class="bi bi-calendar-event me-1"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $statusBadgeClass }} px-3 py-1.5 rounded-pill text-capitalize fw-bold" style="font-size: 11px;">
                        {{ $order->status }}
                    </span>
                    @if ($isPaid)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                            <i class="bi bi-check-circle-fill me-1"></i> Lunas (Terverifikasi)
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                            <i class="bi bi-clock-fill me-1"></i> Menunggu Verifikasi
                        </span>
                    @elseif(in_array($order->payment_status, ['failed', 'rejected']))
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                            <i class="bi bi-x-circle-fill me-1"></i> Transfer Ditolak
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Belum Bayar
                        </span>
                    @endif
                </div>
            </div>

            {{-- Item Produk dalam Pesanan --}}
            <div class="mb-3">
                @foreach ($order->items as $item)
                    @php
                        $prod = $item->product;
                        $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=200&name=' . urlencode($prod->title ?? 'Produk'));
                    @endphp
                    <div class="d-flex align-items-center justify-content-between gap-3 py-2">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="{{ $thumb }}" alt="{{ $prod->title ?? 'Produk' }}" class="rounded-3 object-fit-cover border flex-shrink-0" style="width: 52px; height: 52px;" onerror="this.src='https://placehold.co/100x100?text=Karyaku'">
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-dark text-truncate mb-0" style="font-size: 13.5px;">
                                    {{ $prod->title ?? 'Karya Digital' }}
                                </h6>
                                <span class="text-muted small">
                                    {{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}
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

            {{-- Footer Pesanan: Total & Aksi --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top bg-light p-3 rounded-3">
                <div>
                    <span class="text-muted small d-block">Total Tagihan:</span>
                    <span class="fw-extrabold text-primary h5 mb-0">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}" class="btn btn-outline-primary btn-sm fw-bold px-3 py-2 rounded-3">
                        <i class="bi bi-info-circle me-1"></i> Rincian Pesanan
                    </a>
                    @if ($isPaid)
                        <a href="{{ route('pembeli.download') }}" class="btn btn-success btn-sm fw-bold px-3 py-2 rounded-3 text-white">
                            <i class="bi bi-cloud-arrow-down-fill me-1"></i> Unduh Berkas
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card-box p-5 text-center text-muted">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-receipt-cutoff fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan</h5>
            <p class="small text-muted mb-4">Tidak ada riwayat transaksi pada filter status yang Anda pilih.</p>
            <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
                <i class="bi bi-shop me-1"></i> Mulai Berbelanja
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

@endsection
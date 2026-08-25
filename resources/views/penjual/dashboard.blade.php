@extends('layouts.penjual')
@section('title', 'Dashboard Penjual')

@section('content')

{{-- BANNER KEDALUWARSA / PERINGATAN MEMBERSHIP --}}
@if($isExpired)
    <div class="alert alert-danger card-box p-3 border-0 border-start border-4 border-danger d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-clock-history fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-danger">Masa Aktif Paket Membership Anda Telah Habis!</h6>
                <small class="text-muted">Produk Anda tetap tersimpan, namun Anda tidak dapat mengunggah produk baru hingga memperpanjang paket.</small>
            </div>
        </div>
        <a href="{{ route('penjual.membership.index') }}" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-3">
            <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Sekarang
        </a>
    </div>
@elseif($remainingDays <= 3 && $remainingDays > 0)
    <div class="alert alert-warning card-box p-3 border-0 border-start border-4 border-warning d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-warning-emphasis">Masa Aktif Membership Anda Tersisa {{ $remainingDays }} Hari Lagi</h6>
                <small class="text-muted">Segera perpanjang paket untuk menikmati kuota unggah dan fitur promosi tanpa jeda.</small>
            </div>
        </div>
        <a href="{{ route('penjual.membership.index') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-3">
            <i class="bi bi-gem me-1"></i> Perpanjang Paket
        </a>
    </div>
@endif

{{-- KARTU STATUS MEMBERSHIP --}}
<div class="card-box p-4 mb-4 border bg-white shadow-sm rounded-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 p-2.5 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-gem fs-3"></i>
                </div>
                <div>
                    <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                        PAKET AKTIF
                    </span>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $membershipName }}</h4>
                </div>
            </div>
            <p class="text-muted small mb-3">
                @if($user->membership_expires_at)
                    Masa aktif berlaku sampai <strong class="text-dark">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong> ({{ $remainingDays }} hari tersisa).
                @else
                    Masa aktif paket aktif permanen atau belum ditentukan.
                @endif
            </p>

            {{-- PROGRESS KUOTA UPLOAD --}}
            <div>
                @php
                    $percentage = $maxProducts > 0 ? min(100, round(($totalProduk / $maxProducts) * 100)) : 0;
                @endphp
                <div class="d-flex justify-content-between small mb-1 fw-medium">
                    <span class="text-muted">Kuota Upload Produk Terpakai:</span>
                    <strong class="{{ $batasTercapai ? 'text-danger' : 'text-primary' }}">{{ $totalProduk }} / {{ $maxProducts }} Produk ({{ $percentage }}%)</strong>
                </div>
                <div class="progress" style="height: 8px; border-radius: 20px;">
                    <div class="progress-bar {{ $batasTercapai ? 'bg-danger' : 'bg-primary' }}" style="width: {{ $percentage }}%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1 text-muted" style="font-size: 11px;">
                    <span>Sisa kuota: <strong>{{ $quotaSisa }} slot</strong></span>
                    <span>Fitur Iklan: <strong class="{{ $bisaIklan ? 'text-success' : 'text-secondary' }}">{{ $bisaIklan ? 'Tersedia' : 'Khusus Gold/Diamond' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-lg-end d-flex flex-column flex-sm-row justify-content-lg-end gap-2">
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-outline-primary fw-bold py-2.5 px-3 rounded-3">
                <i class="bi bi-arrow-up-circle me-1"></i> Upgrade / Perpanjang
            </a>
            <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary fw-bold py-2.5 px-3 rounded-3 {{ $batasTercapai || $isExpired ? 'disabled' : '' }}">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
            </a>
        </div>
    </div>
</div>

{{-- STATISTIK UTAMA --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-box p-3 h-100 border hover-shadow">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Total Pendapatan</span>
                <div class="rounded-3 p-2 bg-success-subtle text-success"><i class="bi bi-wallet2 fs-5"></i></div>
            </div>
            <h4 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
            <a href="{{ route('penjual.keuangan.index') }}" class="small text-success text-decoration-none fw-semibold">
                Lihat Saldo & Tarik <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-box p-3 h-100 border hover-shadow">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Pesanan Masuk</span>
                <div class="rounded-3 p-2 bg-primary-subtle text-primary"><i class="bi bi-bag-check fs-5"></i></div>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ number_format($totalPesanan) }}</h4>
            <a href="{{ route('penjual.pesanan.index') }}" class="small text-primary text-decoration-none fw-semibold">
                Kelola Pesanan <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-box p-3 h-100 border hover-shadow">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Produk Aktif</span>
                <div class="rounded-3 p-2 bg-info-subtle text-info"><i class="bi bi-box-seam fs-5"></i></div>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ number_format($produkAktif) }}</h4>
            <div class="small text-muted">Dari total {{ $totalProduk }} karya terunggah</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-box p-3 h-100 border hover-shadow">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Status Moderasi</span>
                <div class="rounded-3 p-2 bg-warning-subtle text-warning"><i class="bi bi-shield-exclamation fs-5"></i></div>
            </div>
            <div class="d-flex gap-3 mb-1">
                <div><span class="badge bg-warning-subtle text-warning">{{ $produkPending }} Pending</span></div>
                <div><span class="badge bg-danger-subtle text-danger">{{ $produkBuked }} Ditolak/Blokir</span></div>
            </div>
            <a href="{{ route('penjual.produk.index', ['tab' => 'diblokir']) }}" class="small text-danger text-decoration-none fw-semibold">
                Cek Produk Diblokir <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- PRODUK SAYA TERBARU --}}
    <div class="col-lg-7">
        <div class="card-box p-4 h-100 border">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>Produk Terbaru Anda</h6>
                <a href="{{ route('penjual.produk.index') }}" class="small text-primary fw-semibold">Lihat Semua</a>
            </div>

            @if($recentProducts->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-3">Anda belum mengunggah produk karya digital.</p>
                    <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary btn-sm fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i> Mulai Jual Produk
                    </a>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($recentProducts as $prod)
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-light-subtle">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Produk' }}" 
                                     alt="{{ $prod->title }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 50px; height: 50px;">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate" style="font-size: 13.5px;">{{ $prod->title }}</h6>
                                    <div class="text-muted small" style="font-size: 11px;">
                                        Rp {{ number_format($prod->price, 0, ',', '.') }} &bull; Stok: {{ $prod->stock }} &bull; Terjual: {{ $prod->sold_count }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                @if($prod->status === 'active')
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @elseif($prod->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Ditolak / Blokir</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- PESANAN MASUK TERBARU --}}
    <div class="col-lg-5">
        <div class="card-box p-4 h-100 border">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i>Pesanan Masuk Terbaru</h6>
                <a href="{{ route('penjual.pesanan.index') }}" class="small text-primary fw-semibold">Lihat Semua</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-0">Belum ada pesanan masuk dari pembeli.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($recentOrders as $orderItem)
                        <div class="p-2.5 rounded-3 border bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-bold small text-dark">{{ $orderItem->order->buyer->name ?? 'Pembeli' }}</span>
                                <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-muted small text-truncate" style="font-size: 11px;">
                                {{ $orderItem->product->title ?? 'Produk' }} ({{ $orderItem->quantity }}x)
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2" style="font-size: 10px;">
                                <span class="text-muted">{{ $orderItem->created_at->diffForHumans() }}</span>
                                @if($orderItem->order->payment_status === 'paid')
                                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                @else
                                    <span class="text-warning fw-bold"><i class="bi bi-clock-fill"></i> Belum Bayar</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

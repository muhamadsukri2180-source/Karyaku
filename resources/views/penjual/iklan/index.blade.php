@extends('layouts.penjual')
@section('title', 'Iklan & Promosi Produk')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-megaphone-fill text-warning me-2"></i>Iklan & Promosi Produk</h4>
    <p class="text-muted small">Tingkatkan penjualan dengan mempromosikan karya terbaik Anda di posisi teratas marketplace.</p>
</div>

{{-- STATUS TIER IKLAN --}}
@if(!$bisaIklan)
    <div class="card-box p-4 border mb-4 bg-warning-subtle bg-opacity-25 border-warning-subtle">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                    <i class="bi bi-gem fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-1">Fitur Iklan Eksklusif untuk Paket Gold & Diamond</h6>
                    <p class="text-muted small mb-0">Paket membership Anda saat ini (<strong>{{ $user->membership->name ?? 'Standar' }}</strong>) belum mendukung fitur promosi iklan. Upgrade paket Anda sekarang untuk menikmati fitur ini.</p>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-warning fw-bold px-4 py-2 flex-shrink-0">
                <i class="bi bi-arrow-up-circle me-1"></i> Upgrade ke Gold / Diamond
            </a>
        </div>
    </div>
@else
    <div class="card-box p-3 border mb-4 bg-success-subtle bg-opacity-25 border-success-subtle d-flex align-items-center gap-3">
        <i class="bi bi-check-circle-fill text-success fs-4"></i>
        <div class="small">
            <strong class="text-success">Fitur Iklan Aktif!</strong> Sebagai member <strong>{{ $user->membership->name }}</strong>, Anda dapat mengiklankan produk aktif Anda secara gratis untuk mendapatkan lencana khusus dan sorotan prioritas.
        </div>
    </div>
@endif

<div class="row g-4">
    {{-- PRODUK SEDANG DIIKLANKAN --}}
    <div class="col-lg-6">
        <div class="card-box p-4 border h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-fire text-danger me-2"></i>Produk Sedang Beriklan ({{ $promotedProducts->count() }})</h6>

            @if($promotedProducts->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-megaphone fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-0">Belum ada produk yang sedang dipromosikan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($promotedProducts as $prod)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-warning-subtle bg-opacity-25 border-warning-subtle">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}" 
                                     alt="{{ $prod->title }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 50px; height: 50px;">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate small">{{ $prod->title }}</h6>
                                    <span class="badge bg-warning text-dark" style="font-size: 10px;">
                                        Aktif s/d {{ $prod->promoted_until ? $prod->promoted_until->translatedFormat('d M Y') : 'Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('penjual.iklan.cancel', $prod->id_product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                                    Hentikan
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- PILIH PRODUK UNTUK DIIKLANKAN --}}
    <div class="col-lg-6">
        <div class="card-box p-4 border h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>Pilih Produk Aktif untuk Dipromosikan</h6>

            @if($activeProducts->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-0">Belum ada produk aktif yang siap diiklankan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($activeProducts as $prod)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}" 
                                     alt="{{ $prod->title }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 50px; height: 50px;">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate small">{{ $prod->title }}</h6>
                                    <div class="text-muted small" style="font-size: 11px;">Rp {{ number_format($prod->price, 0, ',', '.') }} &bull; Terjual: {{ $prod->sold_count }}</div>
                                </div>
                            </div>

                            @if($prod->is_promoted)
                                <span class="badge bg-warning text-dark px-3 py-2">Sedang Beriklan</span>
                            @else
                                <form action="{{ route('penjual.iklan.promote', $prod->id_product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-3" {{ !$bisaIklan ? 'disabled' : '' }}>
                                        <i class="bi bi-megaphone me-1"></i> Iklankan
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

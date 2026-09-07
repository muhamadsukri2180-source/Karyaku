@extends('layouts.penjual')

@section('title', 'Iklan & Promosi Produk')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --primary-soft:#dbeafe;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.10);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { max-width: 720px; color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); transition: .2s ease; }
    .kk-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
    .kk-section-title { font-size: 14px; font-weight: 800; color: var(--text-dark); }
    .kk-row { border: 1px solid var(--border-color); border-radius: 14px; transition: .2s ease; }
    .kk-row:hover { border-color: #cbd5e1; box-shadow: 0 5px 16px rgba(15,23,42,.06); }
    .kk-thumb { width: 54px; height: 54px; object-fit: cover; border-radius: 14px; flex-shrink: 0; }
    .status-box { border-radius: 18px; }
    .kk-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .icon-gold { background: #fff7ed; color: #f59e0b; }
</style>

<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-megaphone-fill text-warning me-2"></i>Iklan & Promosi Produk</h4>
    <p class="small mb-0">Tingkatkan penjualan dengan mempromosikan karya terbaik Anda di posisi teratas marketplace.</p>
</div>

{{-- STATUS TIER IKLAN --}}
@if(!$bisaIklan)
    <div class="kk-card status-box p-4 mb-4" style="background:#fff7ed;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kk-icon icon-gold shadow-sm">
                    <i class="bi bi-gem fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color:var(--text-dark);">Fitur Iklan Eksklusif untuk Paket Gold & Diamond</h6>
                    <p class="small mb-0" style="color:var(--text-muted);">Paket membership Anda saat ini (<strong>{{ $user->membership->name ?? 'Standar' }}</strong>) belum mendukung fitur promosi iklan. Upgrade paket Anda sekarang untuk menikmati fitur ini.</p>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn fw-bold px-4 py-2 flex-shrink-0 rounded-3" style="background:var(--primary); color:#fff;">
                <i class="bi bi-arrow-up-circle me-1"></i> Upgrade ke Gold / Diamond
            </a>
        </div>
    </div>
@else
    <div class="kk-card status-box p-3 mb-4 d-flex align-items-center gap-3" style="background:#ecfdf5;">
        <i class="bi bi-check-circle-fill fs-4" style="color:#16a34a;"></i>
        <div class="small">
            <strong style="color:#16a34a;">Fitur Iklan Aktif!</strong> Sebagai member <strong>{{ $user->membership->name }}</strong>, Anda dapat mengiklankan produk aktif Anda secara gratis untuk mendapatkan lencana khusus dan sorotan prioritas.
        </div>
    </div>
@endif

<div class="row g-4">
    {{-- PRODUK SEDANG DIIKLANKAN --}}
    <div class="col-lg-6">
        <div class="kk-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="kk-section-title mb-0"><i class="bi bi-fire text-danger me-2"></i>Produk Sedang Beriklan</h6>
                <span class="badge rounded-pill px-3 py-2" style="background:#fff7ed; color:#f59e0b;">{{ $promotedProducts->count() }}</span>
            </div>

            @if($promotedProducts->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada produk yang sedang dipromosikan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($promotedProducts as $prod)
                        <div class="kk-row d-flex align-items-center justify-content-between p-3 gap-3" style="background:#fffaf0;">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}"
                                     alt="{{ $prod->title }}" class="kk-thumb border">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-1 text-truncate small">{{ $prod->title }}</h6>
                                    <span class="badge" style="font-size: 10px; background:#f59e0b; color:#1e293b;">
                                        Aktif s/d {{ $prod->promoted_until ? $prod->promoted_until->translatedFormat('d M Y') : 'Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('penjual.iklan.cancel', $prod->id_product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold rounded-3 px-3">Hentikan</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- PILIH PRODUK UNTUK DIIKLANKAN --}}
    <div class="col-lg-6">
        <div class="kk-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="kk-section-title mb-0"><i class="bi bi-box-seam me-2" style="color:var(--primary);"></i>Pilih Produk Aktif untuk Dipromosikan</h6>
                <span class="badge rounded-pill px-3 py-2" style="background:var(--primary-light); color:var(--primary);">{{ $activeProducts->count() }}</span>
            </div>

            @if($activeProducts->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-box fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada produk aktif yang siap diiklankan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($activeProducts as $prod)
                        <div class="kk-row d-flex align-items-center justify-content-between p-3 gap-3" style="background:#f8fafc;">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}"
                                     alt="{{ $prod->title }}" class="kk-thumb border">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-1 text-truncate small">{{ $prod->title }}</h6>
                                    <div class="small" style="font-size: 11px; color:var(--text-muted);">Rp {{ number_format($prod->price, 0, ',', '.') }} &bull; Terjual: {{ $prod->sold_count }}</div>
                                </div>
                            </div>
                            @if($prod->is_promoted)
                                <span class="badge px-3 py-2" style="background:#f59e0b; color:#1e293b;">Sedang Beriklan</span>
                            @else
                                <form action="{{ route('penjual.iklan.promote', $prod->id_product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm fw-bold px-3 rounded-3" style="background:var(--primary); color:#fff;" {{ !$bisaIklan ? 'disabled' : '' }}>
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
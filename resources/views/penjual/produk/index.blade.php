@extends('layouts.penjual')
@section('title', 'Manajemen Produk Saya')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-box-seam-fill text-primary me-2"></i>Produk & Karya Saya</h4>
        <p class="text-muted small mb-0">Kelola seluruh karya digital yang Anda jual di marketplace Karyaku.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm {{ !$canUpload ? 'disabled' : '' }}">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

{{-- STATUS KUOTA UPLOAD --}}
@if(!$canUpload)
    <div class="alert alert-warning card-box p-3 border-0 border-start border-4 border-warning d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
            <span class="small fw-medium">Kuota upload produk Anda telah penuh ({{ $counts['semua'] }}/{{ $maxUpload }} produk). Tingkatkan paket membership Anda untuk menambah kuota upload.</span>
        </div>
        <a href="{{ route('penjual.membership.index') }}" class="btn btn-warning btn-sm fw-bold px-3 py-1.5">
            Upgrade Paket
        </a>
    </div>
@endif

{{-- TAB FILTER STATUS & PENCARIAN --}}
<div class="card-box p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <ul class="nav nav-pills gap-2">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'semua']) }}">
                    Semua <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'aktif' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'aktif']) }}">
                    <i class="bi bi-check-circle me-1"></i> Aktif <span class="badge {{ ($tab ?? '') === 'aktif' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['aktif'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'pending' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'pending']) }}">
                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi <span class="badge {{ ($tab ?? '') === 'pending' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'diblokir' ? 'active fw-bold bg-danger' : 'text-danger' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'diblokir']) }}">
                    <i class="bi bi-x-circle me-1"></i> Ditolak / Diblokir <span class="badge {{ ($tab ?? '') === 'diblokir' ? 'bg-white text-danger' : 'bg-danger-subtle text-danger border' }} ms-1">{{ $counts['diblokir'] }}</span>
                </a>
            </li>
        </ul>

        <form action="{{ route('penjual.produk.index') }}" method="GET" class="d-flex gap-2">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nama produk..." style="min-width: 200px;">
            <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

{{-- DAFTAR PRODUK --}}
@if($products->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-box fs-1 d-block mb-3 text-secondary opacity-50"></i>
        <h5 class="fw-bold text-dark mb-1">Tidak Ada Produk</h5>
        <p class="small text-muted mb-3">Tidak ditemukan produk pada kategori/filter ini.</p>
        @if($canUpload)
            <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary btn-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk Sekarang
            </a>
        @endif
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($products as $prod)
            @php
                $isBlocked = in_array($prod->status, ['rejected', 'inactive', 'blocked']);
            @endphp
            <div class="card-box p-3 border {{ $isBlocked ? 'border-danger-subtle bg-danger-subtle bg-opacity-10' : '' }} hover-shadow">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/100x100?text=Karyaku' }}" 
                             alt="{{ $prod->title }}" class="rounded-3 object-fit-cover flex-shrink-0 border" style="width: 75px; height: 75px;">
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge bg-primary-subtle text-primary font-weight-bold" style="font-size: 10px;">
                                    {{ $prod->category->name ?? 'Kategori' }}
                                </span>
                                @if($prod->status === 'active')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i> Aktif di Marketplace</span>
                                @elseif($prod->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-exclamation-octagon-fill me-1"></i> Ditolak / Dinonaktifkan</span>
                                @endif

                                @if($prod->is_promoted)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-megaphone-fill me-1"></i> Sedang Diiklankan</span>
                                @endif
                            </div>

                            <h6 class="fw-bold mb-1 text-truncate" style="font-size: 15px;">
                                <a href="{{ route('pembeli.produk.detail', $prod->id_product) }}" target="_blank" class="text-dark text-decoration-none">
                                    {{ $prod->title }}
                                </a>
                            </h6>

                            <div class="d-flex align-items-center gap-3 text-muted small" style="font-size: 12px;">
                                <strong class="text-primary font-weight-bold">Rp {{ number_format($prod->price, 0, ',', '.') }}</strong>
                                <span><i class="bi bi-boxes me-1"></i> Stok: <strong>{{ $prod->stock }}</strong></span>
                                <span><i class="bi bi-eye me-1"></i> {{ $prod->view_count }}</span>
                                <span><i class="bi bi-bag-check me-1"></i> Terjual {{ $prod->sold_count }}</span>
                                <span><i class="bi bi-star-fill text-warning me-1"></i> {{ $prod->avg_rating }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- AKSI --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        @if($prod->status === 'active')
                            @if($prod->is_promoted)
                                <form action="{{ route('penjual.iklan.cancel', $prod->id_product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-warning btn-sm fw-semibold" title="Hentikan Iklan">
                                        <i class="bi bi-megaphone-fill me-1"></i> Iklan Aktif
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('penjual.iklan.promote', $prod->id_product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold" title="Pasang Iklan untuk Produk Ini">
                                        <i class="bi bi-megaphone me-1"></i> Iklankan
                                    </button>
                                </form>
                            @endif
                        @endif

                        <a href="{{ route('penjual.produk.edit', $prod->id_product) }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>

                        <form action="{{ route('penjual.produk.destroy', $prod->id_product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CATATAN PENOLAKAN / PEMBLOKIRAN JIKA ADA --}}
                @if($isBlocked && $prod->rejection_note)
                    <div class="mt-3 p-3 bg-danger-subtle bg-opacity-50 border border-danger-subtle rounded-3 text-danger small">
                        <div class="d-flex align-items-center gap-2 fw-bold mb-1">
                            <i class="bi bi-exclamation-triangle-fill"></i> Catatan Penolakan / Pemblokiran oleh Petugas:
                        </div>
                        <p class="mb-2 text-dark">{{ $prod->rejection_note }}</p>
                        <a href="{{ route('penjual.produk.edit', $prod->id_product) }}" class="btn btn-danger btn-sm fw-semibold py-1 px-3">
                            <i class="bi bi-pencil me-1"></i> Perbaiki & Ajukan Verifikasi Ulang
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif

@endsection

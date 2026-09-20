@extends('layouts.penjual')
@section('title', 'Manajemen Produk Saya')

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
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); }
    .kk-card-hover { transition: .2s ease; }
    .kk-card-hover:hover { box-shadow: var(--shadow-hover); transform: translateY(-1px); }
    .kk-tabs .nav-link { border-radius: 10px; font-size: 12px; font-weight: 600; padding: 9px 13px; color: var(--text-muted); }
    .kk-tabs .nav-link.active { background: var(--primary); color: #fff !important; box-shadow: 0 5px 14px rgba(37,99,235,.18); }
    .kk-tabs .nav-link.danger-tab.active { background: #ef4444; }
    .kk-tabs .nav-link.danger-tab { color: #ef4444; }

    /* CUSTOM TOMBOL AKSI */
    .btn-action {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    
    .btn-action-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .btn-action-blue:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }
    .btn-action-blue-active {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .btn-action-blue-active:hover {
        background: #f59e0b;
        color: #fff;
    }

    .btn-action-yellow {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .btn-action-yellow:hover {
        background: #f59e0b;
        color: #fff;
        border-color: #f59e0b;
    }

    .btn-action-red {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 8px 12px;
    }
    .btn-action-red:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--text-dark);"><i class="bi bi-box-seam-fill me-2" style="color:var(--primary);"></i>Produk & Karya Saya</h4>
        <p class="small mb-0" style="color:var(--text-muted);">Kelola seluruh karya digital yang Anda jual di marketplace Karyaku.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('penjual.produk.create') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm {{ !$canUpload ? 'disabled' : '' }}" style="background:var(--primary); color:#fff;">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

{{-- STATUS KUOTA UPLOAD --}}
@if(!$canUpload)
    <div class="kk-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4" style="background:#fff7ed; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5" style="color:#f59e0b;"></i>
            <span class="small fw-medium">Kuota upload produk Anda telah penuh ({{ $counts['semua'] }}/{{ $maxUpload }} produk). Tingkatkan paket membership Anda untuk menambah kuota upload.</span>
        </div>
        <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm fw-bold px-3 py-1.5" style="background:#f59e0b; color:#1e293b;">
            Upgrade Paket
        </a>
    </div>
@endif

{{-- TAB FILTER STATUS & PENCARIAN --}}
<div class="kk-card p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <ul class="nav nav-pills gap-2 kk-tabs">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : '' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'semua']) }}">
                    Semua <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'aktif' ? 'active fw-bold' : '' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'aktif']) }}">
                    <i class="bi bi-check-circle me-1"></i> Aktif <span class="badge {{ ($tab ?? '') === 'aktif' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['aktif'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'pending' ? 'active fw-bold' : '' }}" 
                   href="{{ route('penjual.produk.index', ['tab' => 'pending']) }}">
                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi <span class="badge {{ ($tab ?? '') === 'pending' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link danger-tab {{ ($tab ?? '') === 'diblokir' ? 'active fw-bold' : '' }}" 
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
    <div class="kk-card p-5 text-center" style="color:var(--text-muted);">
        <i class="bi bi-box fs-1 d-block mb-3 opacity-50"></i>
        <h5 class="fw-bold mb-1" style="color:var(--text-dark);">Tidak Ada Produk</h5>
        <p class="small mb-3">Tidak ditemukan produk pada kategori/filter ini.</p>
        @if($canUpload)
            <a href="{{ route('penjual.produk.create') }}" class="btn btn-sm fw-semibold" style="background:var(--primary); color:#fff;">
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
            <div class="kk-card kk-card-hover p-4" style="{{ $isBlocked ? 'border-color:#fecaca !important; background:#fef2f2;' : '' }}">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    {{-- DI SINI DITAMBAHKAN GAP (JARAK) YANG LEBIH LUAS ANTARA GAMBAR DAN TEKS --}}
                    <div class="d-flex align-items-center gap-4 overflow-hidden">
                        <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/100x100?text=Karyaku' }}" 
                             alt="{{ $prod->title }}" class="rounded-3 object-fit-cover flex-shrink-0 border shadow-sm" style="width: 85px; height: 85px;">
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                                <span class="badge fw-bold px-2 py-1" style="font-size: 10.5px; background:var(--primary-light); color:var(--primary);">
                                    {{ $prod->category->name ?? 'Kategori' }}
                                </span>
                                @if($prod->status === 'active')
                                    <span class="badge px-2 py-1" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle-fill me-1"></i> Aktif di Toko</span>
                                @elseif($prod->status === 'pending')
                                    <span class="badge px-2 py-1" style="background:#fff7ed; color:#f59e0b;"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                                @else
                                    <span class="badge px-2 py-1" style="background:#fef2f2; color:#ef4444;"><i class="bi bi-exclamation-octagon-fill me-1"></i> Ditolak / Dinonaktifkan</span>
                                @endif

                                @if($prod->is_promoted)
                                    <span class="badge px-2 py-1" style="background:#f59e0b; color:#fff;"><i class="bi bi-megaphone-fill me-1"></i> Sedang Diiklankan</span>
                                @endif
                            </div>

                            <h6 class="fw-bold mb-1.5 text-truncate" style="font-size: 16px;">
                                <a href="{{ route('pembeli.produk.detail', $prod->id_product) }}" target="_blank" class="text-decoration-none" style="color:var(--text-dark);">
                                    {{ $prod->title }}
                                </a>
                            </h6>

                            <div class="d-flex align-items-center gap-3 small flex-wrap" style="font-size: 12.5px; color:var(--text-muted);">
                                <strong style="color:var(--primary); font-size: 13.5px;">Rp {{ number_format($prod->price, 0, ',', '.') }}</strong>
                                <span>&bull;</span>
                                <span><i class="bi bi-boxes me-1"></i> Stok: <strong>{{ $prod->stock }}</strong></span>
                                <span>&bull;</span>
                                <span><i class="bi bi-eye me-1"></i> Dilihat {{ $prod->view_count }}</span>
                                <span>&bull;</span>
                                <span><i class="bi bi-bag-check me-1"></i> Terjual {{ $prod->sold_count }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        @if($prod->status === 'active')
                            @if($prod->is_promoted)
                                <form action="{{ route('penjual.iklan.cancel', $prod->id_product) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-blue-active shadow-sm" title="Hentikan Iklan">
                                        <i class="bi bi-megaphone-fill"></i> Iklan Aktif
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('penjual.iklan.promote', $prod->id_product) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-action btn-action-blue shadow-sm" title="Pasang Iklan untuk Produk Ini">
                                        <i class="bi bi-megaphone"></i> Iklankan
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Tombol Edit (Kuning) --}}
                        <a href="{{ route('penjual.produk.edit', $prod->id_product) }}" class="btn btn-action btn-action-yellow shadow-sm" title="Edit Produk">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        {{-- Tombol Hapus (Merah) --}}
                        <form action="{{ route('penjual.produk.destroy', $prod->id_product) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-action-red shadow-sm" title="Hapus Produk">
                                <i class="bi bi-trash fs-6"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CATATAN PENOLAKAN / PEMBLOKIRAN JIKA ADA --}}
                @if($isBlocked && $prod->rejection_note)
                    <div class="mt-3.5 p-3 rounded-3 small" style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c;">
                        <div class="d-flex align-items-center gap-2 fw-bold mb-1">
                            <i class="bi bi-exclamation-triangle-fill"></i> Catatan Penolakan / Pemblokiran oleh Petugas:
                        </div>
                        <p class="mb-2" style="color:var(--text-dark);">{{ $prod->rejection_note }}</p>
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
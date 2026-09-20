@extends('layouts.pembeli')
@section('title', 'Download Saya - Karyaku')

@push('styles')
<style>
    .download-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        transition: all 0.25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .download-card:hover {
        transform: translateY(-4px);
        border-color: #cbd5e1;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }
    .download-body {
        padding: 20px;
    }
    .download-footer {
        background: #f8fafc;
        border-top: 1px solid var(--border-color);
        padding: 14px 20px;
    }
    .download-thumb {
        width: 72px;
        height: 72px;
        border-radius: 14px;
        object-fit: cover;
        background: #f1f5f9;
        border: 1px solid var(--border-color);
    }
    .download-meta-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 12px 14px;
    }
</style>
@endpush

@section('content')

<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-cloud-arrow-down-fill text-primary me-2"></i>Pusat Unduhan Berkas Digital
        </h4>
        <p class="text-muted small mb-0">Unduh berkas asli produk dan aset digital yang telah berhasil Anda beli.</p>
    </div>
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
        Belanja Lagi
    </a>
</div>

@if ($orderItems->isEmpty())
    <div class="card-box p-4 p-md-5 text-center my-2">
        <div class="mx-auto d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3.5 shadow-sm" style="width: 84px; height: 84px; background: linear-gradient(135deg, rgba(37,99,235,0.1) 0%, rgba(59,130,246,0.18) 100%); border: 1px solid rgba(37,99,235,0.15);">
            <i class="bi bi-cloud-arrow-down-fill text-primary" style="font-size: 2.5rem;"></i>
        </div>
        <div style="max-width: 500px;" class="mx-auto">
            <h5 class="fw-bold text-dark mb-2">Belum Ada Berkas yang Dapat Diunduh</h5>
            <p class="small text-muted mb-4 leading-relaxed">
                Karya digital dan aset yang Anda beli akan otomatis muncul di sini dan dapat diunduh tanpa batas setelah pesanan terkonfirmasi lunas.
            </p>
            <div class="p-3 bg-light rounded-3 text-start mb-4 border d-flex align-items-center gap-2.5 text-muted small">
                <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
                <div>
                    Baru saja melakukan pembayaran? Cek status transaksi Anda di 
                    <a href="{{ route('pembeli.pesanan') }}" class="fw-bold text-primary text-decoration-underline">Daftar Pesanan Saya</a>.
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2">
                <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-shop"></i> Jelajahi Marketplace
                </a>
                <a href="{{ route('pembeli.pesanan') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-bold rounded-pill d-inline-flex align-items-center gap-2">
                    <i class="bi bi-receipt"></i> Pesanan Saya
                </a>
            </div>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach ($orderItems as $item)
            @php
                $prod = $item->product;
                $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=256&name=' . urlencode($prod->title ?? 'Karyaku'));
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="download-card">
                    <div class="download-body">
                        <div class="d-flex gap-3 align-items-center mb-3">
                            <img src="{{ $thumb }}" 
                                 alt="{{ $prod->title ?? 'Produk' }}" 
                                 class="download-thumb flex-shrink-0" 
                                 onerror="this.src='https://placehold.co/100x100?text=Digital+File'">
                            <div class="overflow-hidden">
                                <span class="badge bg-primary-subtle text-primary mb-1 fw-bold" style="font-size: 10px;">
                                    {{ $prod->category->name ?? 'Aset Digital' }}
                                </span>
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14px;">
                                    <a href="{{ $prod ? route('pembeli.produk.detail', $prod->id_product) : '#' }}" class="text-dark text-decoration-none">
                                        {{ $prod->title ?? 'Produk telah dihapus' }}
                                    </a>
                                </h6>
                                <span class="text-muted small d-block" style="font-size: 11px;">
                                    <i class="bi bi-person me-1"></i> {{ $prod->seller->name ?? 'Kreator Karyaku' }}
                                </span>
                            </div>
                        </div>

                        <div class="download-meta-box small text-muted">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Status Berkas:</span>
                                <strong class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Siap Unduh</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tanggal Beli:</span>
                                <span class="text-dark fw-semibold">{{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="download-footer">
                        <div class="d-flex gap-2">
                            @if ($prod && $prod->file)
                                <a href="{{ route('pembeli.download.file', $item->id_order_item) }}" class="btn btn-primary btn-sm flex-fill fw-bold py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-1.5" style="font-size: 13px;">
                                    Unduh File
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm flex-fill py-2 rounded-pill" disabled style="font-size: 13px;">
                                    <i class="bi bi-slash-circle me-1"></i> File Tidak Tersedia
                                </button>
                            @endif
                            @if($prod)
                                <a href="{{ route('pembeli.produk.detail', $prod->id_product) }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-pill d-inline-flex align-items-center justify-content-center" title="Lihat Detail & Ulas">
                                    <i class="bi bi-star"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($orderItems->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $orderItems->links() }}
        </div>
    @endif
@endif

@endsection
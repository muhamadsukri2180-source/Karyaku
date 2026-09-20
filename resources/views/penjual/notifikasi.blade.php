@extends('layouts.penjual')
@section('title', 'Notifikasi Penjual - Karyaku')

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
    .notif-card-item {
        border: 1px solid var(--border-color);
        background: #ffffff;
        border-radius: 14px;
        transition: all .2s ease;
    }
    .notif-card-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(15,23,42,.06);
        transform: translateY(-1px);
    }
    .notif-card-item.unread {
        background: #f8faff;
        border-color: #bfdbfe;
        border-left: 4px solid var(--primary);
    }
    .icon-notif-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }
</style>

{{-- HEADER HALAMAN --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 seller-page-head">
    <div>
        <h4 class="mb-1"><i class="bi bi-bell-fill text-primary me-2"></i>Pusat Notifikasi Toko</h4>
        <p class="small mb-0">Semua informasi penting terkait pesanan, penarikan saldo, verifikasi produk, paket membership, dan peringatan toko Anda.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        @if($unreadCount > 0)
            <form action="{{ route('penjual.notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm rounded-3 fw-bold px-3 py-2 shadow-sm">
                    <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca ({{ $unreadCount }})
                </button>
            </form>
        @endif
        <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold px-3 py-2">
            <i class="bi bi-arrow-left me-1"></i> Dashboard Penjual
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 small mb-4">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 small mb-4">{{ session('error') }}</div>
@endif

{{-- STAT KARTU RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon" style="background:linear-gradient(135deg, #3b82f6, #1d4ed8);">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <div class="value">{{ $totalCount }}</div>
                <div class="label">Total Notifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon" style="background:linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="bi bi-bell"></i>
            </div>
            <div>
                <div class="value text-danger">{{ $unreadCount }}</div>
                <div class="label">Belum Dibaca</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon" style="background:linear-gradient(135deg, #10b981, #059669);">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="value text-success">{{ max(0, $totalCount - $unreadCount) }}</div>
                <div class="label">Sudah Dibaca</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER TABS --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="d-flex gap-2">
        <a href="{{ route('penjual.notifikasi') }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold {{ !request('filter') ? 'btn-primary text-white' : 'btn-light text-dark' }}">
            Semua Notifikasi ({{ $totalCount }})
        </a>
        <a href="{{ route('penjual.notifikasi', ['filter' => 'unread']) }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold {{ request('filter') === 'unread' ? 'btn-danger text-white' : 'btn-light text-dark' }}">
            Belum Dibaca @if($unreadCount > 0)<span class="badge bg-white text-danger ms-1">{{ $unreadCount }}</span>@endif
        </a>
    </div>
</div>

{{-- DAFTAR NOTIFIKASI --}}
@if ($notifications->count() > 0)
    <div class="d-flex flex-column gap-2.5">
        @foreach ($notifications as $notif)
            @php
                $nameLower = strtolower($notif->name ?? '');
                
                // Klasifikasi ikon & warna berdasarkan topik notifikasi
                if (str_contains($nameLower, 'pesanan') || str_contains($nameLower, 'order')) {
                    $icon = 'bi-receipt-cutoff';
                    $iconBg = '#ecfdf5';
                    $iconColor = '#059669';
                } elseif (str_contains($nameLower, 'saldo') || str_contains($nameLower, 'tarik') || str_contains($nameLower, 'keuangan') || str_contains($nameLower, 'pembayaran')) {
                    $icon = 'bi-wallet2';
                    $iconBg = '#eff6ff';
                    $iconColor = '#2563eb';
                } elseif (str_contains($nameLower, 'membership') || str_contains($nameLower, 'paket') || str_contains($nameLower, 'perpanjangan')) {
                    $icon = 'bi-gem';
                    $iconBg = '#fdf4ff';
                    $iconColor = '#c026d3';
                } elseif (str_contains($nameLower, 'peringatan') || str_contains($nameLower, 'teguran') || str_contains($nameLower, 'laporan') || str_contains($nameLower, 'takedown') || str_contains($nameLower, 'tolak') || str_contains($nameLower, 'suspend')) {
                    $icon = 'bi-shield-exclamation';
                    $iconBg = '#fef2f2';
                    $iconColor = '#dc2626';
                } elseif (str_contains($nameLower, 'produk') || str_contains($nameLower, 'karya')) {
                    $icon = 'bi-box-seam-fill';
                    $iconBg = '#f0fdf4';
                    $iconColor = '#16a34a';
                } else {
                    $icon = 'bi-bell-fill';
                    $iconBg = '#f1f5f9';
                    $iconColor = '#475569';
                }

                $isUnread = !$notif->is_read;
            @endphp
            <div class="notif-card-item p-3.5 {{ $isUnread ? 'unread' : '' }}">
                <div class="d-flex gap-3 align-items-start">
                    <div class="icon-notif-box" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                        <i class="bi {{ $icon }}"></i>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 14.5px;">{{ $notif->name }}</h6>
                                @if($isUnread)
                                    <span class="badge bg-danger rounded-pill" style="font-size: 9px; padding: 3px 7px;">Baru</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small" style="font-size: 11px;">
                                    <i class="bi bi-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                                </span>
                                <form action="{{ route('penjual.notifikasi.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-muted" title="Hapus Notifikasi" style="font-size: 13px; line-height:1;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="text-secondary small mb-1" style="line-height: 1.55; font-size: 13px;">
                            {{ $notif->description }}
                        </p>
                        <div class="text-muted" style="font-size: 10.5px;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $notif->created_at ? $notif->created_at->translatedFormat('l, d F Y - H:i') . ' WIB' : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($notifications->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
@else
    <div class="kk-card p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-bell-slash fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Tidak Ada Notifikasi</h5>
        <p class="small text-muted mb-0">
            @if(request('filter') === 'unread')
                Bagus! Semua notifikasi telah Anda baca.
            @else
                Belum ada pemberitahuan baru untuk akun penjual Anda saat ini.
            @endif
        </p>
    </div>
@endif

@endsection

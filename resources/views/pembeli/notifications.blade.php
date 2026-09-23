@extends('layouts.pembeli')

@section('title', 'Semua Notifikasi - Karyaku')

@push('styles')
<style>
    .notification-card-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .notification-item {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background: #fafafa;
    }
    .notification-item.unread {
        background: rgba(37, 99, 235, 0.02);
    }
    .notification-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    @media(max-width: 576px) {
        .notification-card-box { border-radius: 16px; }
        .notification-item { padding: 14px 16px; }
        .notification-icon { width: 38px; height: 38px; font-size: 16px; border-radius: 10px; }
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-bell-fill text-primary me-2"></i>Semua Notifikasi Akun
        </h4>
        <p class="text-muted mb-0 small">
            Pantau seluruh pemberitahuan sistem, konfirmasi transaksi, dan informasi akun Anda.
        </p>
    </div>

    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
        Kembali ke Dashboard
    </a>
</div>

@if ($notifications->count() > 0)
    <div class="notification-card-box mb-4">
        @foreach ($notifications as $notif)
            @php
                $isNew = $notif->created_at ? $notif->created_at->greaterThan(now()->subDays(3)) : false;
            @endphp
            <div class="notification-item d-flex gap-3 align-items-start {{ $isNew ? 'unread' : '' }}">
                <div class="notification-icon">
                    <i class="bi bi-bell-fill fs-5"></i>
                </div>

                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                        <strong class="text-dark" style="font-size: 14.5px;">{{ $notif->name ?? 'Pemberitahuan Sistem' }}</strong>
                        <div class="d-flex align-items-center gap-2">
                            @if($isNew)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 10px;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>Baru
                                </span>
                            @endif
                            <span class="text-muted small" style="font-size: 11.5px;">
                                <i class="bi bi-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                            </span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        {{ $notif->description ?? 'Tidak ada rincian tambahan.' }}
                    </p>
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
    <div class="notification-card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
            <i class="bi bi-bell-slash fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Tidak Ada Notifikasi</h5>
        <p class="small text-muted mb-0">Belum ada pemberitahuan baru untuk akun Anda saat ini.</p>
    </div>
@endif

@endsection
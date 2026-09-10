@extends('layouts.pembeli')

@section('title', 'Semua Notifikasi - Karyaku')

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

    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

@if ($notifications->count() > 0)
    <div class="card-box overflow-hidden">
        @foreach ($notifications as $notif)
            @php
                $isNew = $notif->created_at ? $notif->created_at->greaterThan(now()->subDays(3)) : false;
            @endphp
            <div class="d-flex gap-3 p-3.5 {{ !$loop->last ? 'border-bottom' : '' }} align-items-start {{ $isNew ? 'bg-primary-subtle bg-opacity-25' : '' }}">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:44px; height:44px; background: var(--primary-light); color: var(--primary);">
                    <i class="bi bi-bell-fill fs-5"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                        <strong class="text-dark" style="font-size: 14px;">{{ $notif->name ?? 'Pemberitahuan Sistem' }}</strong>
                        <div class="d-flex align-items-center gap-2">
                            @if($isNew)
                                <span class="badge bg-danger rounded-pill" style="font-size: 9.5px;">Baru</span>
                            @endif
                            <span class="text-muted small" style="font-size: 11px;">
                                <i class="bi bi-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                            </span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
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
    <div class="card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-bell-slash fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Tidak Ada Notifikasi</h5>
        <p class="small text-muted mb-0">Belum ada pemberitahuan baru untuk akun Anda saat ini.</p>
    </div>
@endif

@endsection

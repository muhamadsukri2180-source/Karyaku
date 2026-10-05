@extends('layouts.pembeli')
@section('title', 'Paket Membership Penjual - Karyaku')

@push('styles')
<style>
    @media(max-width: 576px) {
        .card-box { padding: 18px 16px !important; border-radius: 16px !important; }
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white shadow-sm" style="width:52px;height:52px;">
            <i class="bi bi-crown fs-3"></i>
        </div>
        <div>
            <h4 class="fw-extrabold text-dark mb-0">Paket Membership Penjual</h4>
            <p class="text-muted mb-0 small">Pilih paket terbaik untuk mulai berjualan dan mengunggah produk digital di platform Karyaku.</p>
        </div>
    </div>
</div>

@if ($isPenjual)
    <div class="card-box p-4 mb-4 border-success bg-success-subtle text-success-emphasis rounded-4">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🎉 Akun Anda Sudah Aktif Sebagai Penjual</h6>
                    <p class="mb-0 small">Paket Membership Aktif: <strong>{{ $user->membership->name ?? 'Penjual' }}</strong></p>
                </div>
            </div>
            <a href="{{ route('penjual.dashboard') }}" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white flex-shrink-0">
                <i class="bi bi-speedometer2 me-1"></i> Masuk Dashboard Penjual
            </a>
        </div>
    </div>
@elseif ($pending)
    <div class="card-box p-4 mb-4 border-warning bg-warning-subtle text-warning-emphasis rounded-4">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-warning text-dark rounded-circle flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Pendaftaran Penjual Sedang Diverifikasi</h6>
                    <p class="mb-0 small">Paket yang dipilih: <strong>{{ $pending->membership->name ?? '-' }}</strong> &middot; Dikirim: {{ optional($pending->submitted_at)->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <a href="{{ route('pembeli.seller.registration.status') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark flex-shrink-0">
                <i class="bi bi-eye me-1"></i> Lihat Status Pendaftaran
            </a>
        </div>
    </div>
@endif

{{-- LIST DAFTAR PAKET MEMBERSHIP --}}
<div class="row g-4">
    @forelse ($memberships as $membership)
        <div class="col-md-6 col-lg-4">
            <div class="card-box h-100 p-4 d-flex flex-column justify-content-between border rounded-4 hover-shadow position-relative">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size:11px;">
                            {{ $membership->name }}
                        </span>
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ $membership->duration_days }} Hari
                        </div>
                    </div>

                    <div class="mb-3">
                        <h3 class="fw-extrabold text-primary mb-0">
                            Rp {{ number_format($membership->price, 0, ',', '.') }}
                        </h3>
                        <span class="text-muted small">Masa aktif paket {{ $membership->duration_days }} hari</span>
                    </div>

                    <ul class="list-unstyled text-secondary small mb-4">
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Batas Unggah: <strong>{{ $membership->max_upload }} Produk</strong></span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Akses Dashboard Penjual & Analytics</span>
                        </li>
                        @if ($membership->benefit)
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-star-fill text-warning mt-1"></i>
                                <span>{{ $membership->benefit }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div>
                    @if ($pending)
                        <a href="{{ route('pembeli.seller.registration.status') }}" class="btn btn-outline-warning w-100 py-2.5 fw-bold rounded-3">
                            Pendaftaran Sedang Diverifikasi
                        </a>
                    @elseif ($isPenjual)
                        <button type="button" class="btn btn-outline-secondary w-100 py-2.5 fw-bold rounded-3" disabled>
                            Sudah Menjadi Penjual
                        </button>
                    @else
                        <a href="{{ route('pembeli.seller.registration.create', ['membership' => $membership->id_membership]) }}" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-arrow-right-circle me-1"></i> Pilih Paket Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada paket membership yang tersedia saat ini.</p>
        </div>
    @endforelse
</div>

@endsection
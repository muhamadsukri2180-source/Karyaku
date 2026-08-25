@extends('layouts.penjual')
@section('title', 'Paket Membership Penjual')

@section('content')

<div class="mb-4 text-center text-md-start">
    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-gem text-primary me-2"></i>Pilihan Paket Membership</h4>
    <p class="text-muted small mb-0">Pilih paket terbaik untuk menambah kuota unggahan produk, masa aktif, dan fitur iklan promosi.</p>
</div>

{{-- PAKET SAAT INI --}}
<div class="card-box p-4 border mb-5 bg-white shadow-sm rounded-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-person-badge-fill fs-2"></i>
            </div>
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 mb-1">STATUS MEMBERSHIP ANDA</span>
                <h5 class="fw-bold text-dark mb-1">{{ $currentMembership->name ?? 'Paket Standar' }}</h5>
                <div class="small text-muted">
                    @if($user->membership_expires_at)
                        Masa aktif hingga <strong class="text-dark">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong> ({{ $remainingDays }} hari lagi).
                    @else
                        Masa aktif permanen / belum diatur.
                    @endif
                    &bull; Kuota: <strong>{{ $totalUploaded }} / {{ $maxUpload }} Produk</strong>
                </div>
            </div>
        </div>

        @if($isExpired)
            <span class="badge bg-danger p-2 fs-6"><i class="bi bi-x-circle me-1"></i> Paket Telah Kedaluwarsa</span>
        @else
            <span class="badge bg-success-subtle text-success p-2 fs-6"><i class="bi bi-check-circle me-1"></i> Paket Aktif</span>
        @endif
    </div>
</div>

{{-- DAFTAR PAKET MEMBERSHIP DARI ADMIN --}}
<h5 class="fw-bold text-dark mb-3"><i class="bi bi-stars text-warning me-2"></i>Katalog Paket Tersedia</h5>
<div class="row g-4">
    @forelse($memberships as $m)
        @php
            $isCurrent = $user->id_membership == $m->id_membership && !$isExpired;
            $lower = strtolower($m->name);
            $isDiamond = str_contains($lower, 'diamond');
            $isGold = str_contains($lower, 'gold');
        @endphp
        <div class="col-md-6 col-lg-3">
            <div class="card-box p-4 h-100 border {{ $isDiamond ? 'border-primary shadow' : '' }} d-flex flex-column justify-content-between position-relative hover-shadow">
                @if($isDiamond)
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-primary px-3 py-1.5 shadow-sm fw-bold" style="font-size: 11px;">
                        REKOMENDASI KREATOR
                    </span>
                @endif

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 {{ $isDiamond ? 'text-primary' : 'text-dark' }}">{{ $m->name }}</h5>
                        <div class="rounded-circle p-2 {{ $isDiamond ? 'bg-primary-subtle text-primary' : ($isGold ? 'bg-warning-subtle text-warning' : 'bg-light text-secondary') }}">
                            <i class="bi bi-gem fs-5"></i>
                        </div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($m->price, 0, ',', '.') }}</h3>
                        <small class="text-muted">Durasi aktif {{ $m->duration_days }} hari</small>
                    </div>

                    <ul class="list-unstyled small mb-4 d-flex flex-column gap-2 text-secondary">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Batas Upload: <strong>{{ $m->max_upload }} Produk</strong></span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Durasi Aktif: <strong>{{ $m->duration_days }} Hari</strong></span>
                        </li>
                        @if($isDiamond || $isGold)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Fitur Iklan & Promosi: <strong>Tersedia</strong></span>
                            </li>
                        @endif
                        @if($m->benefit)
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <span>{{ $m->benefit }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div>
                    <form action="{{ route('penjual.membership.purchase', $m->id_membership) }}" method="POST" onsubmit="return confirm('Konfirmasi aktivasi paket {{ $m->name }} seharga Rp {{ number_format($m->price, 0, ',', '.') }}?');">
                        @csrf
                        @if($isCurrent)
                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold py-2">
                                <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Ini
                            </button>
                        @else
                            <button type="submit" class="btn {{ $isDiamond ? 'btn-primary' : 'btn-outline-dark' }} w-100 fw-bold py-2">
                                Pilih & Aktifkan Paket
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-box p-5 text-center text-muted">
                <p class="mb-0">Belum ada paket membership yang dikonfigurasi oleh admin.</p>
            </div>
        </div>
    @endforelse
</div>

@endsection

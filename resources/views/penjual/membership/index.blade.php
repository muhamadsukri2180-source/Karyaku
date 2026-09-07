@extends('layouts.penjual')
@section('title', 'Paket Membership Penjual')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.11);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); transition: .2s ease; }
    .kk-card:hover { box-shadow: var(--shadow-hover); }
    .kk-membership { position: relative; transition: .2s ease; }
    .kk-membership:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
</style>

<div class="seller-page-head mb-4 text-center text-md-start">
    <h4 class="mb-1"><i class="bi bi-gem me-2" style="color:var(--primary);"></i>Pilihan Paket Membership</h4>
    <p class="small mb-0">Pilih paket terbaik untuk menambah kuota unggahan produk, masa aktif, dan fitur iklan promosi.</p>
</div>

{{-- PAKET SAAT INI --}}
<div class="kk-card p-4 mb-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background:var(--primary-light); color:var(--primary);">
                <i class="bi bi-person-badge-fill fs-2"></i>
            </div>
            <div>
                <span class="badge fw-bold px-2 py-1 mb-1" style="background:var(--primary-light); color:var(--primary);">STATUS MEMBERSHIP ANDA</span>
                <h5 class="fw-bold mb-1" style="color:var(--text-dark);">{{ $currentMembership->name ?? 'Paket Standar' }}</h5>
                <div class="small" style="color:var(--text-muted);">
                    @if($user->membership_expires_at)
                        Masa aktif hingga <strong style="color:var(--text-dark);">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong> ({{ $remainingDays }} hari lagi).
                    @else
                        Masa aktif permanen / belum diatur.
                    @endif
                    &bull; Kuota: <strong>{{ $totalUploaded }} / {{ $maxUpload }} Produk</strong>
                </div>
            </div>
        </div>

        @if($isExpired)
            <span class="badge p-2 fs-6 rounded-3" style="background:#ef4444; color:#fff;"><i class="bi bi-x-circle me-1"></i> Paket Telah Kedaluwarsa</span>
        @else
            <span class="badge p-2 fs-6 rounded-3" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle me-1"></i> Paket Aktif</span>
        @endif
    </div>
</div>

{{-- DAFTAR PAKET MEMBERSHIP DARI ADMIN --}}
<h5 class="fw-bold mb-3" style="color:var(--text-dark);"><i class="bi bi-stars text-warning me-2"></i>Katalog Paket Tersedia</h5>
<div class="row g-4">
    @forelse($memberships as $m)
        @php
            $isCurrent = $user->id_membership == $m->id_membership && !$isExpired;
            $lower = strtolower($m->name);
            $isDiamond = str_contains($lower, 'diamond');
            $isGold = str_contains($lower, 'gold');
        @endphp
        <div class="col-md-6 col-lg-3">
            <div class="kk-card kk-membership p-4 h-100 {{ $isDiamond ? 'shadow' : '' }} d-flex flex-column justify-content-between" style="{{ $isDiamond ? 'border-color: var(--primary) !important;' : '' }}">
                @if($isDiamond)
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill px-3 py-2 shadow-sm fw-bold" style="font-size: 11px; background:var(--primary); color:#fff;">REKOMENDASI KREATOR</span>
                @endif

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0" style="color: {{ $isDiamond ? 'var(--primary)' : 'var(--text-dark)' }};">{{ $m->name }}</h5>
                        <div class="rounded-circle p-2" style="background: {{ $isDiamond ? 'var(--primary-light)' : ($isGold ? '#fff7ed' : '#f8fafc') }}; color: {{ $isDiamond ? 'var(--primary)' : ($isGold ? '#f59e0b' : '#64748b') }};">
                            <i class="bi bi-gem fs-5"></i>
                        </div>
                    </div>

                    <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        <h3 class="fw-bold mb-0" style="color:var(--text-dark);">Rp {{ number_format($m->price, 0, ',', '.') }}</h3>
                        <small style="color:var(--text-muted);">Durasi aktif {{ $m->duration_days }} hari</small>
                    </div>

                    <ul class="list-unstyled small mb-4 d-flex flex-column gap-2" style="color:var(--text-muted);">
                        <li class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i><span>Batas Upload: <strong>{{ $m->max_upload }} Produk</strong></span></li>
                        <li class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i><span>Durasi Aktif: <strong>{{ $m->duration_days }} Hari</strong></span></li>
                        @if($isDiamond || $isGold)
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i><span>Fitur Iklan & Promosi: <strong>Tersedia</strong></span></li>
                        @endif
                        @if($m->benefit)
                            <li class="d-flex align-items-start gap-2"><i class="bi bi-check-circle-fill mt-1" style="color:#16a34a;"></i><span>{{ $m->benefit }}</span></li>
                        @endif
                    </ul>
                </div>

                <div>
                    <form action="{{ route('penjual.membership.purchase', $m->id_membership) }}" method="POST" onsubmit="return confirm('Konfirmasi aktivasi paket {{ $m->name }} seharga Rp {{ number_format($m->price, 0, ',', '.') }}?');">
                        @csrf
                        @if($isCurrent)
                            <button type="submit" class="btn w-100 fw-bold py-2 rounded-3" style="border:1px solid var(--primary); color:var(--primary); background:#fff;"><i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Ini</button>
                        @else
                            <button type="submit" class="btn w-100 fw-bold py-2 rounded-3" style="{{ $isDiamond ? 'background:var(--primary); color:#fff;' : 'border:1px solid var(--text-dark); color:var(--text-dark); background:#fff;' }}">Pilih & Aktifkan Paket</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="kk-card p-5 text-center" style="color:var(--text-muted);"><p class="mb-0">Belum ada paket membership yang dikonfigurasi oleh admin.</p></div>
        </div>
    @endforelse
</div>

@endsection
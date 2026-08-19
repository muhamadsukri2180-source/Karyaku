@extends('layouts.pembeli')
@section('title', 'Jadi Penjual')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Jadi Penjual di Karyaku</h4>
    <p class="text-muted mb-0" style="font-size:13px;">Pilih paket membership untuk mulai menjual karya digital kamu.</p>
</div>

@if ($isPenjual)
<div class="card-box p-4 mb-4 d-flex align-items-center gap-3" style="background:#ecfdf5; border-color:#a7f3d0;">
    <i class="bi bi-check-circle-fill text-success fs-3"></i>
    <div>
        <div class="fw-bold text-success">Akun kamu sudah menjadi Penjual</div>
        <div class="small text-muted">Paket aktif: {{ $user->membership->name ?? '-' }}</div>
    </div>
</div>
@endif

@if ($memberships->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-crown fs-1 d-block mb-3"></i>
        Belum ada paket membership yang tersedia. Silakan hubungi admin.
    </div>
@else
<div class="row g-4">
    @foreach ($memberships as $membership)
    <div class="col-md-6 col-lg-4">
        <div class="card-box p-4 h-100 d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $membership->name }}</div>
                    <div class="text-muted small">{{ $membership->duration_days }} hari aktif</div>
                </div>
            </div>

            <div class="fw-bold mb-3" style="font-size:24px; color: var(--coral);">
                Rp{{ number_format($membership->price, 0, ',', '.') }}
            </div>

            <div class="text-muted small mb-2"><i class="bi bi-cloud-upload"></i> Maks. {{ $membership->max_upload }} produk diunggah</div>
            <p class="text-muted small flex-fill" style="white-space:pre-line;">{{ $membership->benefit }}</p>

            @if (! $isPenjual)
            <form action="{{ route('pembeli.membership.purchase', $membership->id_membership) }}" method="POST" onsubmit="return confirm('Pilih paket {{ $membership->name }} dan upgrade akun kamu menjadi Penjual?');">
                @csrf
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Pilih Paket Ini</button>
            </form>
            @else
            <button type="button" class="btn btn-outline-secondary w-100 fw-semibold" disabled>Sudah Penjual</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection


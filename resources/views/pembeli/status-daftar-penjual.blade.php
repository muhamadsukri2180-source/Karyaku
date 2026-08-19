@extends('layouts.pembeli')
@section('title', 'Status Pendaftaran Penjual')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Status Pendaftaran Penjual</h4>
    <p class="text-muted mb-0 small">Pantau progres dan hasil verifikasi pendaftaran akun penjual Anda.</p>
</div>

@php
    $isSeller = ($user->role?->role_name ?? null) === 'penjual';
    $status = $registration ? strtolower($registration->status) : null;
@endphp

@if ($isSeller || $status === 'approved')

    {{-- STATUS DISETUJUI --}}
    <div class="card-box p-4 mb-4 border-success bg-success-subtle text-success-emphasis">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">🎉 Selamat! Akun Anda Telah Aktif Sebagai Penjual!</h5>
                    <p class="mb-0 small">Akun Anda resmi terverifikasi sebagai Penjual di Karyaku. Paket Aktif: <strong>{{ $user->membership->name ?? ($registration->membership->name ?? 'Penjual') }}</strong>.</p>
                </div>
            </div>
            <a href="{{ route('penjual.dashboard') }}" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white flex-shrink-0">
                <i class="bi bi-speedometer2 me-1"></i> Masuk Dashboard Penjual
            </a>
        </div>
    </div>

@elseif (! $registration)

    {{-- BELUM ADA PENDAFTARAN --}}
    <div class="card-box p-5 text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width:70px;height:70px;">
            <i class="bi bi-person-badge fs-2"></i>
        </div>
        <h5 class="fw-bold">Belum Ada Pendaftaran</h5>
        <p class="text-muted small mb-4">Anda belum pernah mengajukan pendaftaran sebagai penjual. Mulai jual karya dan produk digital Anda sekarang!</p>
        <a href="{{ route('pembeli.seller.registration.create') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-3">
            <i class="bi bi-plus-circle me-1"></i> Daftar Sebagai Penjual
        </a>
    </div>

@elseif ($status === 'pending')

    {{-- STATUS PENDING / MENUNGGU --}}
    <div class="card-box p-4 mb-4 border-warning bg-warning-subtle text-warning-emphasis">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-warning text-dark rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Sedang Diverifikasi Admin</h5>
                    <p class="mb-0 small">Pendaftaran Anda sedang ditinjau oleh tim verifikator. Mohon tunggu beberapa saat.</p>
                </div>
            </div>
            <form action="{{ route('pembeli.seller.registration.cancel') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                    <i class="bi bi-x-circle me-1"></i> Batalkan Pengajuan
                </button>
            </form>
        </div>
    </div>

@elseif ($status === 'rejected')

    {{-- STATUS DITOLAK --}}
    <div class="card-box p-4 mb-4 border-danger bg-danger-subtle text-danger-emphasis">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                    <i class="bi bi-x-lg fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Pendaftaran Ditolak</h5>
                    <p class="mb-0 small">
                        <strong>Alasan Penolakan:</strong> {{ $registration->notes ?? 'Data atau bukti pembayaran tidak sesuai.' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('pembeli.seller.registration.create') }}" class="btn btn-danger fw-bold px-4 py-2 rounded-3 text-white flex-shrink-0">
                <i class="bi bi-arrow-repeat me-1"></i> Daftar Kembali
            </a>
        </div>
    </div>

@endif

@if ($registration)
    {{-- RINCIAN PENGAJUAN --}}
    <div class="card-box p-4">
        <h6 class="fw-bold mb-4 border-bottom pb-3">Rincian Berkas Pengajuan</h6>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <span class="text-muted small d-block">Nama Pemohon</span>
                    <strong class="text-dark">{{ $registration->user->name ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">NIK</span>
                    <strong class="text-dark">{{ $registration->nik ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Alamat</span>
                    <span class="text-dark">{{ $registration->address ?? '-' }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Rekening Pencairan</span>
                    <strong class="text-dark">{{ $registration->bank_name ?? '-' }} - {{ $registration->account_number ?? '-' }} (a.n {{ $registration->account_name ?? '-' }})</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <span class="text-muted small d-block">Paket Membership</span>
                    <strong class="text-primary fs-6">{{ $registration->membership->name ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Metode Pembayaran</span>
                    <strong class="text-dark">{{ $registration->payment_method ?? 'Transfer Bank' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Nominal Pembayaran</span>
                    <strong class="text-danger fs-6">Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Tanggal Pengajuan</span>
                    <span class="text-dark">{{ optional($registration->submitted_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4 border-top pt-4 mt-2">
            <div class="col-md-6">
                <span class="text-muted small d-block mb-2 font-weight-semibold">Foto KTP</span>
                @if ($registration->identity_document)
                    <a href="{{ asset('storage/' . $registration->identity_document) }}" target="_blank">
                        <img src="{{ asset('storage/' . $registration->identity_document) }}" alt="Foto KTP" class="img-fluid rounded-3 border shadow-sm" style="max-height: 200px; object-fit: cover;">
                    </a>
                @else
                    <span class="text-muted italic">Tidak ada foto KTP.</span>
                @endif
            </div>

            <div class="col-md-6">
                <span class="text-muted small d-block mb-2 font-weight-semibold">Bukti Pembayaran</span>
                @if ($registration->payment_proof)
                    <a href="{{ asset('storage/' . $registration->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $registration->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded-3 border shadow-sm" style="max-height: 200px; object-fit: cover;">
                    </a>
                @else
                    <span class="text-muted italic">Tidak ada bukti pembayaran.</span>
                @endif
            </div>
        </div>
    </div>
@endif

@endsection

@extends('layouts.pembeli')
@section('title', 'Status Pendaftaran Penjual - Karyaku')

@push('styles')
<style>
    .reg-status-card {
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .img-preview-box {
        width: 100%;
        height: 180px;
        border-radius: 14px;
        object-fit: cover;
        border: 1px solid var(--border-color);
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .img-preview-box:hover {
        opacity: 0.9;
        transform: scale(1.01);
    }
</style>
@endpush

@section('content')

<div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-person-check-fill text-primary me-2"></i>Status Pendaftaran Penjual
        </h4>
        <p class="text-muted mb-0 small">Pantau perkembangan dan hasil verifikasi data pendaftaran akun penjual Anda.</p>
    </div>
    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
        Kembali ke Dashboard
    </a>
</div>

@php
    $isSeller = ($user->role?->role_name ?? null) === 'penjual';
    $status = $registration ? strtolower($registration->status) : null;
@endphp

@if ($isSeller || $status === 'approved')
    {{-- STATUS DISETUJUI --}}
    <div class="card-box p-4 mb-4 border-success bg-success-subtle text-success-emphasis reg-status-card">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0 shadow-sm" style="width:56px;height:56px;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Selamat! Akun Anda Telah Aktif Sebagai Penjual</h5>
                    <p class="mb-0 small">Akun Anda resmi terverifikasi. Paket Aktif: <strong>{{ $user->membership->name ?? ($registration->membership->name ?? 'Penjual') }}</strong>.</p>
                </div>
            </div>
            <a href="{{ route('penjual.dashboard') }}" class="btn btn-success fw-bold px-4 py-2.5 rounded-pill shadow-sm text-white flex-shrink-0 d-inline-flex align-items-center gap-1.5" style="font-size: 13.5px;">
                Masuk Beranda Penjual
            </a>
        </div>
    </div>
@elseif (! $registration)
    {{-- BELUM ADA PENDAFTARAN --}}
    <div class="card-box p-5 text-center text-muted reg-status-card">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3 shadow-sm" style="width:80px;height:80px;">
            <i class="bi bi-person-badge fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Pendaftaran Aktif</h5>
        <p class="small text-muted mb-4">Anda belum pernah mengajukan pendaftaran sebagai penjual. Mulai jual karya dan aset digital terbaik Anda di Karyaku sekarang!</p>
        <a href="{{ route('pembeli.seller.registration.create') }}" class="btn btn-primary px-4.5 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="font-size: 13.5px;">
            <i class="bi bi-plus-circle"></i> Daftar Sebagai Penjual
        </a>
    </div>
@elseif ($status === 'pending')
    {{-- STATUS PENDING / MENUNGGU --}}
    <div class="card-box p-4 mb-4 border-warning bg-warning-subtle text-warning-emphasis reg-status-card">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-warning text-dark rounded-circle flex-shrink-0 shadow-sm" style="width:56px;height:56px;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Sedang Diverifikasi Tim Verifikator</h5>
                    <p class="mb-0 small">Pendaftaran Anda sedang ditinjau oleh tim verifikator Karyaku. Mohon menunggu proses verifikasi.</p>
                </div>
            </div>
            <form action="{{ route('pembeli.seller.registration.cancel') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold rounded-pill px-3.5 py-2 shadow-sm d-inline-flex align-items-center gap-1">
                    Batalkan Pengajuan
                </button>
            </form>
        </div>
    </div>
@elseif ($status === 'rejected')
    {{-- STATUS DITOLAK --}}
    <div class="card-box p-4 mb-4 border-danger bg-danger-subtle text-danger-emphasis reg-status-card">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle flex-shrink-0 shadow-sm" style="width:56px;height:56px;">
                    <i class="bi bi-x-lg fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Pengajuan Pendaftaran Ditolak</h5>
                    <p class="mb-0 small">
                        <strong>Catatan Verifikator:</strong> {{ $registration->notes ?? 'Data identitas atau bukti pembayaran tidak valid.' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('pembeli.seller.registration.create') }}" class="btn btn-danger fw-bold px-4 py-2.5 rounded-pill shadow-sm text-white flex-shrink-0 d-inline-flex align-items-center gap-1.5" style="font-size: 13.5px;">
                <i class="bi bi-arrow-repeat"></i> Ajukan Ulang Pendaftaran
            </a>
        </div>
    </div>
@endif

@if ($registration)
    {{-- RINCIAN PENGAJUAN --}}
    <div class="card-box p-4 p-md-4 reg-status-card">
        <h6 class="fw-bold mb-4 border-bottom pb-3 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
            <i class="bi bi-file-earmark-text-fill text-primary fs-5"></i> Rincian Berkas Pengajuan
        </h6>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Nama Pemohon:</span>
                    <strong class="text-dark">{{ $registration->user->name ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">NIK:</span>
                    <strong class="text-dark">{{ $registration->nik ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Alamat Lengkap:</span>
                    <span class="text-dark">{{ $registration->address ?? '-' }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Rekening Pencairan:</span>
                    <strong class="text-dark">{{ $registration->bank_name ?? '-' }} - {{ $registration->account_number ?? '-' }} (a.n {{ $registration->account_name ?? '-' }})</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Paket Membership:</span>
                    <strong class="text-primary fs-6">{{ $registration->membership->name ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Metode Pembayaran:</span>
                    <strong class="text-dark">{{ $registration->payment_method ?? 'Transfer Bank' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Nominal Biaya:</span>
                    <strong class="text-danger fs-6">Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Tanggal Pengajuan:</span>
                    <span class="text-dark">{{ optional($registration->submitted_at)->format('d M Y, H:i') ?? '-' }} WIB</span>
                </div>
            </div>
        </div>

        <div class="row g-4 border-top pt-4 mt-2">
            <div class="col-md-6">
                <span class="text-muted small d-block mb-2 fw-bold text-dark">Foto KTP:</span>
                @if ($registration->identity_document)
                    <a href="{{ asset('storage/' . $registration->identity_document) }}" target="_blank" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $registration->identity_document) }}" alt="Foto KTP" class="img-preview-box shadow-sm">
                    </a>
                @else
                    <div class="p-4 bg-light rounded-3 text-muted small text-center border">Tidak ada foto KTP.</div>
                @endif
            </div>

            <div class="col-md-6">
                <span class="text-muted small d-block mb-2 fw-bold text-dark">Bukti Pembayaran:</span>
                @if ($registration->payment_proof)
                    <a href="{{ asset('storage/' . $registration->payment_proof) }}" target="_blank" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $registration->payment_proof) }}" alt="Bukti Pembayaran" class="img-preview-box shadow-sm">
                    </a>
                @else
                    <div class="p-4 bg-light rounded-3 text-muted small text-center border">Tidak ada bukti transfer.</div>
                @endif
            </div>
        </div>
    </div>
@endif

@endsection
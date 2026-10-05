@extends('layouts.pembeli')
@section('title', 'Profil Saya - Karyaku')

@push('styles')
<style>
    .profile-card-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .profile-sidebar {
        background: #f8fafc;
        border-right: 1px solid var(--border-color);
        padding: 32px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .profile-form-area {
        padding: 32px;
    }
    /* Memberikan warna latar belakang abu-abu terang pada kolom isian agar jelas dan tidak nyaru */
    .profile-form-area .form-control {
        background-color: #f1f5f9 !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b;
        font-weight: 500;
    }
    .profile-form-area .form-control:focus {
        background-color: #ffffff !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    @media(max-width: 991.98px) {
        .profile-sidebar {
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            padding: 24px;
        }
    }
    @media(max-width: 576px) {
        .profile-card-box { border-radius: 16px; }
        .profile-sidebar { padding: 20px 16px; }
        .profile-form-area { padding: 18px 16px; }
        .btn-simpan-profil { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <h4 class="fw-extrabold text-dark mb-1">
        <i class="bi bi-person-circle text-primary me-2"></i>Pengaturan Profil Akun
    </h4>
    <p class="text-muted small mb-0">Kelola informasi data diri, nomor kontak, dan keamanan kata sandi akun Anda dalam satu tempat.</p>
</div>

<form action="{{ route('pembeli.profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="profile-card-box mb-4">
        <div class="row g-0">
            {{-- SISI KIRI: RINGKASAN & AVATAR AKUN --}}
            <div class="col-lg-4 profile-sidebar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff&size=128&bold=true" 
                     alt="Avatar" 
                     class="rounded-circle mb-3 shadow-sm border"
                     style="width: 96px; height: 96px;">

                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">@safeEmail($user->email)</p>

                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill text-capitalize fw-bold mb-4" style="font-size: 11px;">
                    <i class="bi bi-person-fill me-1"></i> Peran: {{ $user->role->role_name ?? 'Pembeli' }}
                </span>

                <div class="w-100 text-start small text-muted border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Terdaftar Sejak:</span>
                        <strong class="text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>No. Telepon:</span>
                        <strong class="text-dark">{{ $user->phone ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            {{-- SISI KANAN: FORM EDIT PROFIL & PASSWORD --}}
            <div class="col-lg-8 profile-form-area">
                <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary d-flex align-items-center gap-2" style="font-size: 15px;">
                    <i class="bi bi-pencil-square fs-5"></i> Ubah Informasi Akun
                </h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-3 py-2.5 @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3 py-2.5 @error('email') is-invalid @enderror" 
                               value="{{ old('email', (!empty($user->email) && !str_starts_with($user->email, '$') && str_contains($user->email, '@')) ? $user->email : '') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control rounded-3 py-2.5 @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h6 class="fw-bold mb-3 border-bottom pb-2 text-secondary d-flex align-items-center gap-2 pt-2" style="font-size: 15px;">
                    <i class="bi bi-shield-lock fs-5"></i> Ubah Password (Opsional)
                </h6>
                <p class="text-muted small mb-3">Kosongkan bagian ini jika Anda tidak ingin melakukan pergantian kata sandi.</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2.5 @error('password') is-invalid @enderror" 
                               placeholder="Minimal 6 karakter">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3 py-2.5" 
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5 btn-simpan-profil" style="font-size: 13.5px;">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
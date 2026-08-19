@extends('layouts.pembeli')
@section('title', 'Profil Saya')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Pengaturan Profil</h4>
    <p class="text-muted mb-0 small">Kelola informasi diri, nomor telepon, dan kata sandi akun Anda.</p>
</div>

<div class="row g-4 mb-4">
    
    {{-- RINGKASAN AKUN --}}
    <div class="col-lg-4">
        <div class="card-box p-4 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff&size=128" 
                 alt="Avatar" 
                 class="rounded-circle mb-3 shadow-sm border"
                 style="width: 100px; height: 100px;">

            <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-2">{{ $user->email }}</p>

            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill text-capitalize font-weight-bold" style="font-size: 11px;">
                <i class="bi bi-person-fill me-1"></i> Peran: {{ $user->role->role_name ?? 'Pembeli' }}
            </span>

            <hr class="my-4">

            <div class="text-start small text-muted space-y-2">
                <div class="d-flex justify-content-between mb-2">
                    <span>Terdaftar Sejak:</span>
                    <strong class="text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>No. Telepon:</span>
                    <strong class="text-dark">{{ $user->phone ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM EDIT PROFIL --}}
    <div class="col-lg-8">
        <div class="card-box p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square fs-5"></i> Ubah Informasi Akun
            </h6>

            <form action="{{ route('pembeli.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock fs-5"></i> Ubah Password (Opsional)
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Biarkan kosong jika tidak diubah">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5 rounded-3 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

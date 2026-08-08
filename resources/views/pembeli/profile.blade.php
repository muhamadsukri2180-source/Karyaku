@extends('layouts.pembeli')
@section('title', 'Profile')

@section('content')

<h4 class="fw-bold mb-4">Profil Saya</h4>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-box p-4 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dbeafe&color=1e3a8a&size=128" class="rounded-circle mb-3" style="width:96px;height:96px;">
            <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
            <div class="text-muted small mb-3">{{ $user->email }}</div>
            <span class="badge-status bg-primary-subtle text-primary">Pembeli</span>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-box p-4">
            <h6 class="fw-bold mb-3">Edit Informasi Akun</h6>
            <form action="{{ route('pembeli.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
                <button type="submit" class="btn btn-primary fw-semibold px-4">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

@endsection

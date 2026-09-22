@extends('layouts.verifikator')

@section('title', 'Profil Verifikator')
@section('header_title', 'Profil Saya')
@section('header_subtitle', 'Kelola informasi akun verifikator Anda.')

@section('header_right')
<a href="{{ route('verifikator.dashboard') }}" class="hidden sm:flex items-center gap-2 text-xs font-bold text-sky-600 hover:text-skyHover transition-colors bg-sky-50 border border-sky-200 px-4 py-2 rounded-xl">
    <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali
</a>
@endsection

@section('content')
<div class="space-y-6 max-w-3xl mx-auto w-full">

    {{-- Alert sukses/error --}}
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold p-4 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold p-4 flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-red-500 text-lg"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm p-4">
            <p class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM UPDATE PROFIL -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm font-display">Informasi Profil</h3>
                <p class="text-[11px] text-slate-500 font-medium">Perbarui nama, email, dan no. telepon Anda.</p>
            </div>
        </div>

        <form action="{{ route('verifikator.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- AVATAR -->
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-sky-50 border-2 border-sky-200 flex items-center justify-center text-2xl font-bold text-sky-600 shadow-sm shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover" id="avatarPreview">
                    @else
                        <span id="avatarInitial">{{ strtoupper(substr($user->name ?? 'V', 0, 2)) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Profil</label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/jpg,image/jpeg,image/png,image/webp"
                        class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition-all cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
                </div>
            </div>

            <!-- NAMA -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-slate-400 text-sm"></i>
                    </div>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:bg-white transition-all">
                </div>
            </div>

            <!-- EMAIL -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-slate-400 text-sm"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email', (!empty($user->email) && !str_starts_with($user->email, '$') && str_contains($user->email, '@')) ? $user->email : '') }}" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:bg-white transition-all">
                </div>
            </div>

            <!-- NO. TELEPON -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">No. Telepon</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-slate-400 text-sm"></i>
                    </div>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="08xxxxxxxxxx atau +62xxxxxxxxxx"
                        pattern="^(\+62|08)[0-9]{8,13}$"
                        title="No. telepon harus diawali 08 atau +62"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:bg-white transition-all">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Opsional. Format: 08xxx atau +62xxx</p>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- FORM GANTI PASSWORD -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm font-display">Ganti Password</h3>
                <p class="text-[11px] text-slate-500 font-medium">Kosongkan jika tidak ingin mengganti password.</p>
            </div>
        </div>

        <form action="{{ route('verifikator.profile.update') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            {{-- Kirim ulang data wajib agar tidak null saat update password saja --}}
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="phone" value="{{ $user->phone }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Min. 6 karakter"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:bg-white transition-all">
                        <button type="button" onclick="togglePw('password', 'eye1')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky-600">
                            <i class="fa-solid fa-eye text-sm" id="eye1"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:bg-white transition-all">
                        <button type="button" onclick="togglePw('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky-600">
                            <i class="fa-solid fa-eye text-sm" id="eye2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-key"></i> Ganti Password
                </button>
            </div>
        </form>
    </div>

    <!-- INFO AKUN (READ-ONLY) -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm p-6">
        <h3 class="font-extrabold text-slate-900 text-sm font-display mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-sky-500"></i> Informasi Akun
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Role</p>
                <p class="font-bold text-slate-800">{{ ucfirst($user->role->role_name ?? '-') }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Status Akun</p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold
                    {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                    <i class="fa-solid fa-circle text-[8px]"></i>
                    {{ $user->status === 'active' ? 'Aktif' : ucfirst($user->status) }}
                </span>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Bergabung Sejak</p>
                <p class="font-bold text-slate-800">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">ID Pengguna</p>
                <p class="font-bold text-slate-800 font-mono">#{{ $user->id_user }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Preview avatar sebelum upload
    const avatarInput = document.getElementById('avatarInput');
    if(avatarInput) {
        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('avatarPreview');
                    const initial = document.getElementById('avatarInitial');
                    if (preview) {
                        preview.src = e.target.result;
                    } else if (initial) {
                        // Buat elemen img baru
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        initial.parentNode.replaceChild(img, initial);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush

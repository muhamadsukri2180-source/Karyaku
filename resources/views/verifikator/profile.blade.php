<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Profil Verifikator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
                }
            }
        }
    </script>
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Verifikator Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full overflow-hidden bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name ?? 'V', 0, 2)) }}
                    @endif
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ $user->name ?? 'Verifikator' }}</p>
                    <p class="text-[10px] text-sky-200 uppercase font-bold tracking-wider">Verifikator Team</p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-2">Navigasi Utama</p>

                <a href="{{ route('verifikator.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-chart-pie w-4 text-center group-hover:text-white transition-colors"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('verifikator.identitas') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-id-card-clip w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Identitas</span>
                </a>
                <a href="{{ route('verifikator.produk') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Produk</span>
                </a>
                <a href="{{ route('verifikator.pembayaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Pembayaran</span>
                </a>
                <a href="{{ route('verifikator.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan Pelanggaran</span>
                </a>

                <div class="border-t border-white/10 mt-3 pt-3">
                    <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2">Akun</p>
                    <a href="{{ route('verifikator.profile') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                        <i class="fa-solid fa-user-gear w-4 text-center text-white"></i><span>Profil Saya</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-white/15">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Profil Saya</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola informasi akun verifikator Anda.</p>
                    </div>
                </div>
                <a href="{{ route('verifikator.dashboard') }}" class="hidden sm:flex items-center gap-2 text-xs font-bold text-sky-600 hover:text-skyHover transition-colors bg-sky-50 border border-sky-200 px-4 py-2 rounded-xl">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali
                </a>
            </header>

            <div class="p-6 sm:p-8 space-y-6 max-w-3xl mx-auto w-full">

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
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
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
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

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

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>

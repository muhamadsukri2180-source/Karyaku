<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Karyaku</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: '#0EA5E9',
                        skyDeep: '#0B3D62',
                        skyPale: '#EFF8FF',
                        ink: '#0F2A44'
                    },
                    fontFamily: {
                        display: ['"Sora"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 10px 40px -10px rgba(11,61,98,0.35)'
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards'
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }

        /* Sembunyikan icon mata bawaan browser Edge */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        .grain-overlay {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            opacity: 0.05; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }

        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Custom Button & Animation */
        .btn-premium {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #0400ff, #4ce3f7);
            border-radius: 12px;
            background-size: 100% auto;
            font-family: inherit;
            font-size: 13px;
            padding: 0.7em 1.5em;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            background-position: right center;
            background-size: 200% auto;
            -webkit-animation: pulse 2s infinite;
            animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% { box-shadow: 0 0 0 0 #05bada66; }
            70% { box-shadow: 0 0 0 10px rgb(218 103 68 / 0%); }
            100% { box-shadow: 0 0 0 0 rgb(218 103 68 / 0%); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full flex items-center justify-center relative p-4 overflow-hidden">

    <!-- Background Animasi -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="grain-overlay"></div>
        <div class="absolute top-0 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-blue-300/50 rounded-full blur-[80px] animate-blob animation-delay-4000"></div>
    </div>

    <!-- Register Card (Compact & Kecil) -->
    <div class="w-full max-w-[360px] bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-[1.5rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <div class="text-center mb-4">
            <div class="w-10 h-10 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-xl flex items-center justify-center mb-2.5 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">
                <i class="fa-solid fa-layer-group text-white text-base"></i>
            </div>
            <h1 class="font-display text-lg font-extrabold text-slate-900 leading-tight tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 text-[12px] mt-0.5 font-medium">Bergabung dengan Karyaku sekarang</p>
        </div>

        {{-- Tampilkan pesan error validasi --}}
        @if ($errors->any())
            <div class="mb-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[11px] font-medium p-2.5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auth.register.submit') }}" method="POST" class="space-y-3">
            @csrf

            <!-- Username -->
            <div class="group">
                <label for="username" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username" placeholder="Pilih username" autocomplete="off" required
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- Email (Wajib @gmail.com) -->
            <div class="group">
                <label for="email" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Email (Harus @gmail.com)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="email" id="email" name="email" placeholder="nama@gmail.com" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Email wajib berakhiran @gmail.com" autocomplete="off" required
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- Password Grid (Min 6 Karakter & Mengandung Angka) -->
            <div class="grid grid-cols-2 gap-2.5">
                <!-- Password -->
                <div class="group">
                    <label for="password" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-[11px] group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Min 6 & angka" minlength="6" pattern="(?=.*\d).{6,}" title="Password minimal 6 karakter dan harus mengandung angka" autocomplete="new-password" required
                            class="w-full pl-7 pr-7 py-2 rounded-xl bg-skyPale border border-slate-200 text-[11px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                        <button type="button" onclick="togglePassword('password', 'eye-icon-reg-pass')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-xs" id="eye-icon-reg-pass"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="group">
                    <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Konfirmasi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-double text-slate-400 text-[11px] group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" minlength="6" autocomplete="new-password" required
                            class="w-full pl-7 pr-7 py-2 rounded-xl bg-skyPale border border-slate-200 text-[11px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-reg-conf')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-xs" id="eye-icon-reg-conf"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Centang Persetujuan -->
            <div class="flex items-start pt-1">
                <div class="flex items-center h-4 mt-0.5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="w-3.5 h-3.5 border-2 border-slate-300 rounded bg-skyPale focus:ring-2 focus:ring-sky/50 checked:bg-sky checked:border-sky transition-colors cursor-pointer">
                </div>
                <label for="terms" class="ml-2 text-[10.5px] text-slate-600 leading-tight font-medium cursor-pointer">
                    Saya menyetujui <a href="#" class="text-sky font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-sky font-bold hover:underline">Kebijakan Privasi</a>.
                </label>
            </div>

            <button type="submit" class="btn-premium group w-full flex items-center justify-center gap-2 mt-2 font-bold shadow-md">
                <span>Daftar Sekarang</span>
                <i class="fa-solid fa-arrow-right text-[11px] opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300"></i>
            </button>
        </form>

        <div class="mt-4 pt-3.5 border-t border-slate-100 text-center">
            <p class="text-[11.5px] text-slate-500 font-medium">
                Sudah punya akun? <a href="{{ route('auth.login') }}" class="font-bold text-sky hover:text-skyDeep transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>

    <!-- Script Tampil/Sembunyi Password -->
    <script>
        function togglePassword(inputId, iconId) {
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
    </script>
</body>
</html>
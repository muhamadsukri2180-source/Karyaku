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
                        'fade-in-up': 'fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards'
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(15px)' },
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

        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }

        .grain-overlay {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            opacity: 0.05; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }

        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        .btn-premium {
            border: none; color: #fff;
            background-image: linear-gradient(30deg, #0400ff, #4ce3f7);
            border-radius: 10px;
            background-size: 100% auto;
            font-family: inherit; font-size: 11.5px;
            padding: 0.6em 1.2em;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            background-position: right center; background-size: 200% auto;
            -webkit-animation: pulse 2s infinite; animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% { box-shadow: 0 0 0 0 #05bada66; }
            70% { box-shadow: 0 0 0 8px rgb(218 103 68 / 0%); }
            100% { box-shadow: 0 0 0 0 rgb(218 103 68 / 0%); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full flex items-center justify-center relative p-2 sm:p-4 overflow-hidden">

    <!-- BACKGROUND ANIMASI -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="grain-overlay"></div>
        <div class="absolute top-0 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-blue-300/50 rounded-full blur-[80px] animate-blob animation-delay-4000"></div>
    </div>

    <!-- REGISTER CARD (UKURAN SANGAT KOMPAK UNTUK MOBILE & DESKTOP) -->
    <!-- w-[92%] memastikan di layar HP terkecil pun tidak nabrak tepi layar, max-w-[310px] membatasi lebar maksimal di desktop -->
    <div class="w-[92%] max-w-[310px] bg-white/95 backdrop-blur-xl p-4 rounded-2xl shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <!-- HEADER -->
        <div class="text-center mb-3">
            <div class="w-8 h-8 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-lg flex items-center justify-center mb-1.5 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">
                <i class="fa-solid fa-layer-group text-white text-[13px]"></i>
            </div>
            <h1 class="font-display text-[14px] font-extrabold text-slate-900 leading-tight tracking-tight">
                Buat Akun Baru
            </h1>
            <p class="text-slate-500 text-[9.5px] mt-0.5 font-medium">
                Bergabung dengan Karyaku sekarang
            </p>
        </div>

        <!-- ERROR VALIDATION -->
        @if ($errors->any())
            <div class="mb-2 rounded-lg bg-red-50 border border-red-200 text-red-600 text-[9px] font-medium p-1.5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- REGISTER FORM (SPASI DIPERKECIL) -->
        <form action="{{ route('auth.register.submit') }}" method="POST" id="registerForm" class="space-y-2">
            @csrf

            <!-- USERNAME -->
            <div class="group">
                <label for="username" class="block text-[9.5px] font-bold text-slate-700 mb-0.5 ml-1 transition-colors group-focus-within:text-sky">
                    Username
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-slate-400 text-[10px] group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Pilih username" autocomplete="off" required
                        class="w-full pl-6 pr-2.5 py-1.5 rounded-lg bg-skyPale border border-slate-200 text-[10px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- EMAIL -->
            <div class="group">
                <label for="email" class="block text-[9.5px] font-bold text-slate-700 mb-0.5 ml-1 transition-colors group-focus-within:text-sky">
                    Email (Harus @gmail.com)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-slate-400 text-[10px] group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Email wajib berakhiran @gmail.com" autocomplete="off" required
                        class="w-full pl-6 pr-2.5 py-1.5 rounded-lg bg-skyPale border border-slate-200 text-[10px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- NO. TELEPON -->
            <div class="group">
                <label for="phone" class="block text-[9.5px] font-bold text-slate-700 mb-0.5 ml-1 transition-colors group-focus-within:text-sky">
                    No. Telepon
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-slate-400 text-[10px] group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="08xxxxxxxxxx atau +62xxxxxxxxxx"
                        pattern="^(\+62|08)[0-9]{8,13}$"
                        title="No. telepon harus diawali 08 atau +62"
                        autocomplete="off" required
                        class="w-full pl-6 pr-2.5 py-1.5 rounded-lg bg-skyPale border border-slate-200 text-[10px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
                @error('phone')
                    <p class="text-[8px] text-red-500 mt-0.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD GRID -->
            <div class="grid grid-cols-2 gap-1.5">
                <!-- PASSWORD -->
                <div class="group">
                    <label for="password" class="block text-[9.5px] font-bold text-slate-700 mb-0.5 ml-1 transition-colors group-focus-within:text-sky">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-1.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-[9px] group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Min 8 char" minlength="8" autocomplete="new-password" required
                            class="w-full pl-5 pr-5 py-1.5 rounded-lg bg-skyPale border border-slate-200 text-[9px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                        <button type="button" onclick="togglePassword('password', 'eye-icon-reg-pass')" class="absolute inset-y-0 right-0 pr-1.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-[9.5px]" id="eye-icon-reg-pass"></i>
                        </button>
                    </div>
                    <div class="mt-1">
                        <div class="flex gap-0.5">
                            <div id="strength-1" class="h-0.5 flex-1 rounded-full bg-slate-200"></div>
                            <div id="strength-2" class="h-0.5 flex-1 rounded-full bg-slate-200"></div>
                            <div id="strength-3" class="h-0.5 flex-1 rounded-full bg-slate-200"></div>
                        </div>
                        <p id="password-strength" class="text-[7.5px] font-bold text-slate-400 mt-0.5">Masukkan password</p>
                    </div>
                </div>

                <!-- KONFIRMASI -->
                <div class="group">
                    <label for="password_confirmation" class="block text-[9.5px] font-bold text-slate-700 mb-0.5 ml-1 transition-colors group-focus-within:text-sky">
                        Konfirmasi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-1.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-double text-slate-400 text-[9px] group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi" minlength="8" autocomplete="new-password" required
                            class="w-full pl-5 pr-5 py-1.5 rounded-lg bg-skyPale border border-slate-200 text-[9px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-reg-conf')" class="absolute inset-y-0 right-0 pr-1.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-[9.5px]" id="eye-icon-reg-conf"></i>
                        </button>
                    </div>
                    <p id="password-match" class="text-[7.5px] font-bold mt-0.5"></p>
                </div>
            </div>

            <!-- PASSWORD REQUIREMENTS -->
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-1.5">
                <p class="text-[8px] font-bold text-slate-600 mb-0.5">Password aman harus memiliki:</p>
                <div class="grid grid-cols-2 gap-y-0">
                    <span id="req-length" class="text-[7.5px] text-slate-400">○ Min 8 karakter</span>
                    <span id="req-lower" class="text-[7.5px] text-slate-400">○ Huruf kecil</span>
                    <span id="req-upper" class="text-[7.5px] text-slate-400">○ Huruf besar</span>
                    <span id="req-number" class="text-[7.5px] text-slate-400">○ Angka</span>
                    <span id="req-symbol" class="text-[7.5px] text-slate-400">○ Simbol</span>
                </div>
            </div>

            <!-- TERMS -->
            <div class="flex items-start pt-0.5">
                <div class="flex items-center h-3 mt-0.5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="w-2.5 h-2.5 border border-slate-300 rounded bg-skyPale focus:ring-1 focus:ring-sky/50 checked:bg-sky checked:border-sky transition-colors cursor-pointer">
                </div>
                <label for="terms" class="ml-1.5 text-[8.5px] text-slate-600 leading-[1.2] font-medium cursor-pointer">
                    Saya menyetujui <a href="#" class="text-sky font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-sky font-bold hover:underline">Kebijakan Privasi</a>.
                </label>
            </div>

            <!-- SUBMIT -->
            <button type="submit" id="submitButton" class="btn-premium group w-full flex items-center justify-center gap-1.5 mt-1 font-bold shadow-sm">
                <span>Daftar Sekarang</span>
                <i class="fa-solid fa-arrow-right text-[9.5px] opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300"></i>
            </button>
        </form>

        <!-- LOGIN LINK -->
        <div class="mt-2.5 pt-2 border-t border-slate-100/60 text-center">
            <p class="text-[9.5px] text-slate-500 font-medium">
                Sudah punya akun? <a href="{{ route('auth.login') }}" class="font-bold text-sky hover:text-skyDeep transition-colors">Masuk di sini</a>
            </p>
        </div>

    </div>

    <!-- JAVASCRIPT -->
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

        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('password_confirmation');

        function updateRequirement(id, condition) {
            const element = document.getElementById(id);
            if (condition) {
                element.classList.remove('text-slate-400');
                element.classList.add('text-green-600');
                element.textContent = '✓ ' + element.textContent.substring(2);
            } else {
                element.classList.remove('text-green-600');
                element.classList.add('text-slate-400');
                element.textContent = '○ ' + element.textContent.substring(2);
            }
        }

        passwordInput.addEventListener('input', function () {
            const password = this.value;
            const strengthText = document.getElementById('password-strength');
            const bar1 = document.getElementById('strength-1');
            const bar2 = document.getElementById('strength-2');
            const bar3 = document.getElementById('strength-3');

            bar1.className = 'h-0.5 flex-1 rounded-full bg-slate-200';
            bar2.className = 'h-0.5 flex-1 rounded-full bg-slate-200';
            bar3.className = 'h-0.5 flex-1 rounded-full bg-slate-200';

            const hasLength = password.length >= 8;
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[^A-Za-z0-9]/.test(password);

            updateRequirement('req-length', hasLength);
            updateRequirement('req-lower', hasLower);
            updateRequirement('req-upper', hasUpper);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-symbol', hasSymbol);

            if (password.length === 0) {
                strengthText.textContent = 'Masukkan password';
                strengthText.className = 'text-[7.5px] font-bold text-slate-400 mt-0.5';
                return;
            }

            let score = 0;
            if (hasLength) score++;
            if (hasLower) score++;
            if (hasUpper) score++;
            if (hasNumber) score++;
            if (hasSymbol) score++;

            if (score <= 2) {
                bar1.className = 'h-0.5 flex-1 rounded-full bg-red-500';
                strengthText.textContent = '🔴 Password Lemah';
                strengthText.className = 'text-[7.5px] font-bold text-red-500 mt-0.5';
            } else if (score <= 4) {
                bar1.className = 'h-0.5 flex-1 rounded-full bg-yellow-400';
                bar2.className = 'h-0.5 flex-1 rounded-full bg-yellow-400';
                strengthText.textContent = '🟡 Password Sedang';
                strengthText.className = 'text-[7.5px] font-bold text-yellow-600 mt-0.5';
            } else {
                bar1.className = 'h-0.5 flex-1 rounded-full bg-green-500';
                bar2.className = 'h-0.5 flex-1 rounded-full bg-green-500';
                bar3.className = 'h-0.5 flex-1 rounded-full bg-green-500';
                strengthText.textContent = '🟢 Password Aman';
                strengthText.className = 'text-[7.5px] font-bold text-green-600 mt-0.5';
            }

            checkPasswordMatch();
        });

        confirmationInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmation = confirmationInput.value;
            const matchText = document.getElementById('password-match');

            if (confirmation.length === 0) {
                matchText.textContent = '';
                return;
            }

            if (password === confirmation) {
                matchText.textContent = '✓ Password cocok';
                matchText.className = 'text-[7.5px] font-bold text-green-600 mt-0.5';
            } else {
                matchText.textContent = '✕ Password tidak cocok';
                matchText.className = 'text-[7.5px] font-bold text-red-500 mt-0.5';
            }
        }

        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', function (event) {
            const password = passwordInput.value;
            const confirmation = confirmationInput.value;

            const isStrong = password.length >= 8 &&
                /[a-z]/.test(password) &&
                /[A-Z]/.test(password) &&
                /[0-9]/.test(password) &&
                /[^A-Za-z0-9]/.test(password);

            if (!isStrong) {
                event.preventDefault();
                alert('Password belum aman. Gunakan minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan simbol.');
                passwordInput.focus();
                return;
            }

            if (password !== confirmation) {
                event.preventDefault();
                alert('Konfirmasi password tidak cocok.');
                confirmationInput.focus();
                return;
            }
        });
    </script>
</body>
</html>
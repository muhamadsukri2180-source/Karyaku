```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar - Karyaku</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome CDN -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

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
                            '0%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },

                            '33%': {
                                transform: 'translate(30px, -50px) scale(1.1)'
                            },

                            '66%': {
                                transform: 'translate(-20px, 20px) scale(0.9)'
                            },

                            '100%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            }
                        },

                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },

                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-display {
            font-family: 'Sora', sans-serif;
        }

        /* Sembunyikan icon mata bawaan browser Edge */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        .grain-overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.05;
            mix-blend-mode: overlay;

            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Button */
        .btn-premium {
            border: none;
            color: #fff;

            background-image:
                linear-gradient(30deg, #0400ff, #4ce3f7);

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
            0% {
                box-shadow: 0 0 0 0 #05bada66;
            }

            70% {
                box-shadow: 0 0 0 10px rgb(218 103 68 / 0%);
            }

            100% {
                box-shadow: 0 0 0 0 rgb(218 103 68 / 0%);
            }
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full flex items-center justify-center relative p-4 overflow-hidden">

    <!-- ============================= -->
    <!-- BACKGROUND ANIMASI -->
    <!-- ============================= -->

    <div
        class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">

        <div class="grain-overlay"></div>

        <div
            class="absolute top-0 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob">
        </div>

        <div
            class="absolute bottom-10 left-10 w-80 h-80 bg-blue-300/50 rounded-full blur-[80px] animate-blob animation-delay-4000">
        </div>

    </div>


    <!-- ============================= -->
    <!-- REGISTER CARD -->
    <!-- ============================= -->

    <div
        class="w-full max-w-[360px] bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-[1.5rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <!-- HEADER -->

        <div class="text-center mb-4">

            <div
                class="w-10 h-10 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-xl flex items-center justify-center mb-2.5 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">

                <i class="fa-solid fa-layer-group text-white text-base"></i>

            </div>

            <h1
                class="font-display text-lg font-extrabold text-slate-900 leading-tight tracking-tight">

                Buat Akun Baru

            </h1>

            <p class="text-slate-500 text-[12px] mt-0.5 font-medium">

                Bergabung dengan Karyaku sekarang

            </p>

        </div>


        <!-- ============================= -->
        <!-- ERROR VALIDATION -->
        <!-- ============================= -->

        @if ($errors->any())

            <div
                class="mb-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[11px] font-medium p-2.5">

                <ul class="list-disc list-inside space-y-0.5">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- ============================= -->
        <!-- REGISTER FORM -->
        <!-- ============================= -->

        <form
            action="{{ route('auth.register.submit') }}"
            method="POST"
            id="registerForm"
            class="space-y-3">

            @csrf


            <!-- ============================= -->
            <!-- USERNAME -->
            <!-- ============================= -->

            <div class="group">

                <label
                    for="username"
                    class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">

                    Username

                </label>

                <div class="relative">

                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                        <i
                            class="fa-solid fa-user text-slate-400 text-xs group-focus-within:text-sky transition-colors">
                        </i>

                    </div>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Pilih username"
                        autocomplete="off"
                        required

                        class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                </div>

            </div>


            <!-- ============================= -->
            <!-- EMAIL -->
            <!-- ============================= -->

            <div class="group">

                <label
                    for="email"
                    class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">

                    Email (Harus @gmail.com)

                </label>

                <div class="relative">

                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                        <i
                            class="fa-solid fa-envelope text-slate-400 text-xs group-focus-within:text-sky transition-colors">
                        </i>

                    </div>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com"

                        pattern="[a-zA-Z0-9._%+-]+@gmail\.com"

                        title="Email wajib berakhiran @gmail.com"

                        autocomplete="off"
                        required

                        class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                </div>

            </div>


            <!-- ============================= -->
            <!-- PASSWORD GRID -->
            <!-- ============================= -->

            <div class="grid grid-cols-2 gap-2.5">


                <!-- ============================= -->
                <!-- PASSWORD -->
                <!-- ============================= -->

                <div class="group">

                    <label
                        for="password"
                        class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">

                        Password

                    </label>


                    <div class="relative">

                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">

                            <i
                                class="fa-solid fa-lock text-slate-400 text-[11px] group-focus-within:text-sky transition-colors">
                            </i>

                        </div>


                        <input
                            type="password"
                            id="password"
                            name="password"

                            placeholder="Minimal 8 karakter"

                            minlength="8"

                            autocomplete="new-password"

                            required

                            class="w-full pl-7 pr-7 py-2 rounded-xl bg-skyPale border border-slate-200 text-[11px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">


                        <!-- Toggle Password -->

                        <button
                            type="button"

                            onclick="togglePassword('password', 'eye-icon-reg-pass')"

                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">

                            <i
                                class="fa-solid fa-eye text-xs"
                                id="eye-icon-reg-pass">
                            </i>

                        </button>

                    </div>


                    <!-- ============================= -->
                    <!-- PASSWORD STRENGTH -->
                    <!-- ============================= -->

                    <div class="mt-1.5">

                        <div class="flex gap-1">

                            <div
                                id="strength-1"
                                class="h-1 flex-1 rounded-full bg-slate-200">
                            </div>

                            <div
                                id="strength-2"
                                class="h-1 flex-1 rounded-full bg-slate-200">
                            </div>

                            <div
                                id="strength-3"
                                class="h-1 flex-1 rounded-full bg-slate-200">
                            </div>

                        </div>


                        <p
                            id="password-strength"
                            class="text-[9px] font-bold text-slate-400 mt-1">

                            Masukkan password

                        </p>

                    </div>

                </div>


                <!-- ============================= -->
                <!-- KONFIRMASI PASSWORD -->
                <!-- ============================= -->

                <div class="group">

                    <label
                        for="password_confirmation"
                        class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">

                        Konfirmasi

                    </label>


                    <div class="relative">

                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">

                            <i
                                class="fa-solid fa-check-double text-slate-400 text-[11px] group-focus-within:text-sky transition-colors">
                            </i>

                        </div>


                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"

                            placeholder="Ulangi password"

                            minlength="8"

                            autocomplete="new-password"

                            required

                            class="w-full pl-7 pr-7 py-2 rounded-xl bg-skyPale border border-slate-200 text-[11px] font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">


                        <!-- Toggle Confirmation -->

                        <button
                            type="button"

                            onclick="togglePassword('password_confirmation', 'eye-icon-reg-conf')"

                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">

                            <i
                                class="fa-solid fa-eye text-xs"
                                id="eye-icon-reg-conf">
                            </i>

                        </button>

                    </div>


                    <!-- Password Match -->

                    <p
                        id="password-match"
                        class="text-[9px] font-bold mt-1">

                    </p>

                </div>

            </div>


            <!-- ============================= -->
            <!-- PASSWORD REQUIREMENTS -->
            <!-- ============================= -->

            <div
                class="bg-slate-50 border border-slate-200 rounded-xl p-2.5">

                <p
                    class="text-[9px] font-bold text-slate-600 mb-1.5">

                    Password aman harus memiliki:

                </p>

                <div class="grid grid-cols-2 gap-y-1">

                    <span
                        id="req-length"
                        class="text-[9px] text-slate-400">

                        ○ Minimal 8 karakter

                    </span>

                    <span
                        id="req-lower"
                        class="text-[9px] text-slate-400">

                        ○ Huruf kecil

                    </span>

                    <span
                        id="req-upper"
                        class="text-[9px] text-slate-400">

                        ○ Huruf besar

                    </span>

                    <span
                        id="req-number"
                        class="text-[9px] text-slate-400">

                        ○ Angka

                    </span>

                    <span
                        id="req-symbol"
                        class="text-[9px] text-slate-400">

                        ○ Simbol

                    </span>

                </div>

            </div>


            <!-- ============================= -->
            <!-- TERMS -->
            <!-- ============================= -->

            <div class="flex items-start pt-1">

                <div class="flex items-center h-4 mt-0.5">

                    <input
                        id="terms"
                        name="terms"
                        type="checkbox"
                        required

                        class="w-3.5 h-3.5 border-2 border-slate-300 rounded bg-skyPale focus:ring-2 focus:ring-sky/50 checked:bg-sky checked:border-sky transition-colors cursor-pointer">

                </div>


                <label
                    for="terms"
                    class="ml-2 text-[10.5px] text-slate-600 leading-tight font-medium cursor-pointer">

                    Saya menyetujui

                    <a
                        href="#"
                        class="text-sky font-bold hover:underline">

                        Syarat & Ketentuan

                    </a>

                    serta

                    <a
                        href="#"
                        class="text-sky font-bold hover:underline">

                        Kebijakan Privasi

                    </a>.

                </label>

            </div>


            <!-- ============================= -->
            <!-- SUBMIT -->
            <!-- ============================= -->

            <button
                type="submit"
                id="submitButton"

                class="btn-premium group w-full flex items-center justify-center gap-2 mt-2 font-bold shadow-md">

                <span>
                    Daftar Sekarang
                </span>

                <i
                    class="fa-solid fa-arrow-right text-[11px] opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300">
                </i>

            </button>

        </form>


        <!-- ============================= -->
        <!-- LOGIN -->
        <!-- ============================= -->

        <div
            class="mt-4 pt-3.5 border-t border-slate-100 text-center">

            <p
                class="text-[11.5px] text-slate-500 font-medium">

                Sudah punya akun?

                <a
                    href="{{ route('auth.login') }}"
                    class="font-bold text-sky hover:text-skyDeep transition-colors">

                    Masuk di sini

                </a>

            </p>

        </div>

    </div>


    <!-- ============================= -->
    <!-- JAVASCRIPT -->
    <!-- ============================= -->

    <script>

        // ==========================================
        // TOGGLE PASSWORD
        // ==========================================

        function togglePassword(inputId, iconId) {

            const input =
                document.getElementById(inputId);

            const icon =
                document.getElementById(iconId);


            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.replace(
                    'fa-eye',
                    'fa-eye-slash'
                );

            } else {

                input.type = 'password';

                icon.classList.replace(
                    'fa-eye-slash',
                    'fa-eye'
                );

            }

        }


        // ==========================================
        // ELEMENT PASSWORD
        // ==========================================

        const passwordInput =
            document.getElementById('password');

        const confirmationInput =
            document.getElementById('password_confirmation');


        // ==========================================
        // UPDATE REQUIREMENTS
        // ==========================================

        function updateRequirement(
            id,
            condition
        ) {

            const element =
                document.getElementById(id);


            if (condition) {

                element.classList.remove(
                    'text-slate-400'
                );

                element.classList.add(
                    'text-green-600'
                );

                element.textContent =
                    '✓ ' +
                    element.textContent.substring(2);

            } else {

                element.classList.remove(
                    'text-green-600'
                );

                element.classList.add(
                    'text-slate-400'
                );

                element.textContent =
                    '○ ' +
                    element.textContent.substring(2);

            }

        }


        // ==========================================
        // PASSWORD STRENGTH
        // ==========================================

        passwordInput.addEventListener(
            'input',
            function () {

                const password =
                    this.value;


                const strengthText =
                    document.getElementById(
                        'password-strength'
                    );


                const bar1 =
                    document.getElementById(
                        'strength-1'
                    );

                const bar2 =
                    document.getElementById(
                        'strength-2'
                    );

                const bar3 =
                    document.getElementById(
                        'strength-3'
                    );


                // Reset bar

                bar1.className =
                    'h-1 flex-1 rounded-full bg-slate-200';

                bar2.className =
                    'h-1 flex-1 rounded-full bg-slate-200';

                bar3.className =
                    'h-1 flex-1 rounded-full bg-slate-200';


                // Requirement

                const hasLength =
                    password.length >= 8;

                const hasLower =
                    /[a-z]/.test(password);

                const hasUpper =
                    /[A-Z]/.test(password);

                const hasNumber =
                    /[0-9]/.test(password);

                const hasSymbol =
                    /[^A-Za-z0-9]/.test(password);


                updateRequirement(
                    'req-length',
                    hasLength
                );

                updateRequirement(
                    'req-lower',
                    hasLower
                );

                updateRequirement(
                    'req-upper',
                    hasUpper
                );

                updateRequirement(
                    'req-number',
                    hasNumber
                );

                updateRequirement(
                    'req-symbol',
                    hasSymbol
                );


                // Empty

                if (password.length === 0) {

                    strengthText.textContent =
                        'Masukkan password';

                    strengthText.className =
                        'text-[9px] font-bold text-slate-400 mt-1';

                    return;

                }


                // Score

                let score = 0;


                if (hasLength) {
                    score++;
                }

                if (hasLower) {
                    score++;
                }

                if (hasUpper) {
                    score++;
                }

                if (hasNumber) {
                    score++;
                }

                if (hasSymbol) {
                    score++;
                }


                // ==================================
                // LEMAH
                // ==================================

                if (score <= 2) {

                    bar1.className =
                        'h-1 flex-1 rounded-full bg-red-500';

                    strengthText.textContent =
                        '🔴 Password Lemah';

                    strengthText.className =
                        'text-[9px] font-bold text-red-500 mt-1';

                }


                // ==================================
                // SEDANG
                // ==================================

                else if (score <= 4) {

                    bar1.className =
                        'h-1 flex-1 rounded-full bg-yellow-400';

                    bar2.className =
                        'h-1 flex-1 rounded-full bg-yellow-400';

                    strengthText.textContent =
                        '🟡 Password Sedang';

                    strengthText.className =
                        'text-[9px] font-bold text-yellow-600 mt-1';

                }


                // ==================================
                // AMAN
                // ==================================

                else {

                    bar1.className =
                        'h-1 flex-1 rounded-full bg-green-500';

                    bar2.className =
                        'h-1 flex-1 rounded-full bg-green-500';

                    bar3.className =
                        'h-1 flex-1 rounded-full bg-green-500';

                    strengthText.textContent =
                        '🟢 Password Aman';

                    strengthText.className =
                        'text-[9px] font-bold text-green-600 mt-1';

                }


                // Update password confirmation

                checkPasswordMatch();

            }
        );


        // ==========================================
        // PASSWORD CONFIRMATION
        // ==========================================

        confirmationInput.addEventListener(
            'input',
            checkPasswordMatch
        );


        function checkPasswordMatch() {

            const password =
                passwordInput.value;

            const confirmation =
                confirmationInput.value;

            const matchText =
                document.getElementById(
                    'password-match'
                );


            if (confirmation.length === 0) {

                matchText.textContent = '';

                return;

            }


            if (password === confirmation) {

                matchText.textContent =
                    '✓ Password cocok';

                matchText.className =
                    'text-[9px] font-bold text-green-600 mt-1';

            } else {

                matchText.textContent =
                    '✕ Password tidak cocok';

                matchText.className =
                    'text-[9px] font-bold text-red-500 mt-1';

            }

        }


        // ==========================================
        // CEGAH SUBMIT JIKA PASSWORD BELUM AMAN
        // ==========================================

        const registerForm =
            document.getElementById('registerForm');


        registerForm.addEventListener(
            'submit',
            function (event) {

                const password =
                    passwordInput.value;

                const confirmation =
                    confirmationInput.value;


                const isStrong =
                    password.length >= 8 &&
                    /[a-z]/.test(password) &&
                    /[A-Z]/.test(password) &&
                    /[0-9]/.test(password) &&
                    /[^A-Za-z0-9]/.test(password);


                // Password belum aman

                if (!isStrong) {

                    event.preventDefault();

                    alert(
                        'Password belum aman. Gunakan minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan simbol.'
                    );

                    passwordInput.focus();

                    return;

                }


                // Password tidak sama

                if (password !== confirmation) {

                    event.preventDefault();

                    alert(
                        'Konfirmasi password tidak cocok.'
                    );

                    confirmationInput.focus();

                    return;

                }

            }
        );

    </script>

</body>
</html>
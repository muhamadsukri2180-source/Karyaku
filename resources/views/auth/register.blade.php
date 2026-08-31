<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar - Karyaku</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --accent: #F97316;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --bg: #F8FAFC;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .12), transparent 30%),
                radial-gradient(circle at bottom right, rgba(249, 115, 22, .10), transparent 30%),
                var(--bg);
            min-height: 100vh;
            color: var(--text-main);
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .auth-container {
            width: 100%;
            max-width: 470px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 22px;
            transition: .2s;
        }

        .back-link:hover {
            color: var(--primary);
            transform: translateX(-3px);
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 36px;
            box-shadow:
                0 20px 60px rgba(15, 23, 42, .08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: white;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, .25);
        }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 21px;
            font-weight: 800;
        }

        .brand-name span {
            color: var(--accent);
        }

        .auth-title {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 17px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            height: 50px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0 16px 0 45px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-main);
            transition: .2s;
            background: #FFFFFF;
        }

        .form-control::placeholder {
            color: #94A3B8;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #94A3B8;
            cursor: pointer;
            font-size: 17px;
        }

        .password-input {
            padding-right: 45px;
        }

        .btn-register {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            transition: .25s;
            margin-top: 6px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, .20);
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {

            .auth-page {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .auth-card {
                padding: 25px 20px;
                border-radius: 20px;
            }

            .auth-title {
                font-size: 24px;
            }

        }
    </style>
</head>

<body>

    <div class="auth-page">

        <div class="auth-container">

            <!-- KEMBALI KE LANDING -->
            <a href="{{ url('/') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Landing</span>
            </a>


            <div class="auth-card">

                <!-- LOGO -->
                <div class="brand">
                    <div class="brand-icon">
                        <i class="bi bi-layers"></i>
                    </div>

                    <div class="brand-name">
                        Karyaku<span>.</span>
                    </div>
                </div>

<<<<<<< HEAD

                <!-- JUDUL -->
                <h1 class="auth-title">
                    Buat Akun Baru
                </h1>

                <p class="auth-subtitle">
                    Bergabung dengan Karyaku dan mulai jelajahi berbagai karya digital terbaik.
                </p>


                <!-- FORM REGISTER -->
                <form action="{{ route('register') }}" method="POST">

                    @csrf


                    <!-- NAMA -->
                    <div class="form-group">

                        <label for="name" class="form-label">
                            Nama Lengkap
                        </label>

                        <div class="input-group">

                            <i class="bi bi-person input-icon"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}"
                                required
                            >


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
                        placeholder="08xxxxxxxxxx/+62xxxxxxxxxx"
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

                        @error('name')
                            <small style="color: #DC2626; font-size: 12px;">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <!-- EMAIL -->
                    <div class="form-group">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <div class="input-group">

                            <i class="bi bi-envelope input-icon"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Masukkan email"
                                value="{{ old('email') }}"
                                required
                            >

                        </div>

                        @error('email')
                            <small style="color: #DC2626; font-size: 12px;">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <!-- NOMOR TELEPON -->
                    <div class="form-group">

                        <label for="phone" class="form-label">
                            Nomor Telepon
                        </label>

                        <div class="input-group">

                            <i class="bi bi-telephone input-icon"></i>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                placeholder="Contoh: 081234567890"
                                value="{{ old('phone') }}"
                                required
                            >

                        </div>

                        @error('phone')
                            <small style="color: #DC2626; font-size: 12px;">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <!-- PASSWORD -->
                    <div class="form-group">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-group">

                            <i class="bi bi-lock input-icon"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control password-input"
                                placeholder="Minimal 8 karakter"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password', this)"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                        @error('password')
                            <small style="color: #DC2626; font-size: 12px;">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <!-- KONFIRMASI PASSWORD -->
                    <div class="form-group">

                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password
                        </label>

                        <div class="input-group">

                            <i class="bi bi-shield-lock input-icon"></i>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control password-input"
                                placeholder="Masukkan ulang password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password_confirmation', this)"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>


                    <!-- TOMBOL DAFTAR -->
                    <button type="submit" class="btn-register">

                        <span>
                            Daftar Sekarang
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                <!-- SUDAH PUNYA AKUN -->
                <div class="auth-footer">

                    Sudah punya akun?

                    <a href="{{ route('login') }}">
                        Masuk
                    </a>

                </div>

            </div>

        </div>

    </div>


    <script>

        function togglePassword(inputId, button) {

            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

            } else {

                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

            }

        }

    </script>

</body>
</html>
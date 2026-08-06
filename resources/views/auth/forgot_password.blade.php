<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Karyaku</title>
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

        .grain-overlay {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            opacity: 0.05; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }

        .animation-delay-2000 { animation-delay: 2s; }

        /* Custom Button & Animation */
        .btn-premium {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #0400ff, #4ce3f7);
            border-radius: 12px;
            background-size: 100% auto;
            font-family: inherit;
            font-size: 14px;
            padding: 0.75em 1.5em;
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
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob animation-delay-2000"></div>
    </div>

    <!-- Forgot Password Card -->
    <div class="w-full max-w-[360px] bg-white/95 backdrop-blur-xl p-6 sm:p-7 rounded-[1.5rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <div class="text-center mb-6">
            <div class="w-12 h-12 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-xl flex items-center justify-center mb-3 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">
                <i class="fa-solid fa-key text-white text-xl"></i>
            </div>
            <h1 class="font-display text-xl font-extrabold text-slate-900 tracking-tight">Lupa Password?</h1>
            <p class="text-slate-500 text-[13px] mt-1 font-medium">Masukkan email terdaftar Anda</p>
        </div>

        {{-- Pesan Status Berhasil Kirim Tautan --}}
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-600 text-[11px] font-medium p-2.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[11px] font-medium p-2.5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div class="group">
                <label for="email" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Email Terdaftar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-slate-400 text-sm group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com" autocomplete="off" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <button type="submit" class="btn-premium group w-full flex items-center justify-center gap-2 mt-3 font-bold shadow-md">
                <span>Kirim Tautan Reset</span>
                <i class="fa-solid fa-paper-plane text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300"></i>
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-[12px] text-slate-500 font-medium">
                <a href="{{ route('auth.login') }}" class="font-bold text-sky hover:text-skyDeep transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Halaman Login
                </a>
            </p>
        </div>
    </div>

</body>
</html>
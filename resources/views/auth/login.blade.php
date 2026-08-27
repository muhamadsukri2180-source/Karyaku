<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Karyaku</title>
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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full flex items-center justify-center relative p-4 overflow-hidden">

    <!-- Background Animasi -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="grain-overlay"></div>
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob animation-delay-2000"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-[360px] bg-white/95 backdrop-blur-xl p-6 sm:p-7 rounded-[1.5rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-xl flex items-center justify-center mb-3 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">
                <i class="fa-solid fa-layer-group text-white text-xl"></i>
            </div>
            <h1 class="font-display text-xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-slate-500 text-[13px] mt-1 font-medium">Masuk ke akun Karyaku kamu</p>
        </div>

        {{-- Pesan sukses setelah registrasi / banding --}}
        @if (session('success'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-600 text-[11px] font-medium p-2.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('success_appeal'))
            <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-medium p-2.5 flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0"></i>
                <span>{{ session('success_appeal') }}</span>
            </div>
        @endif

        {{-- Pesan error validasi (login gagal, dll) --}}
        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[11px] font-medium p-2.5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auth.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div class="group">
                <label for="username" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-at text-slate-400 text-sm group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username"
                        value="{{ old('username', session('registered_username')) }}"
                        placeholder="Masukkan username" autocomplete="off" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <div class="group">
                <label for="password" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-slate-400 text-sm group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="new-password" required
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                    <!-- Hanya 1 Icon Mata di Kanan -->
                    <button type="button" onclick="togglePassword('password', 'eye-icon-login')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                        <i class="fa-solid fa-eye text-sm" id="eye-icon-login"></i>
                    </button>
                </div>
            </div>

           <div class="flex justify-end -mt-1">
            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-sky hover:text-skyDeep transition-colors">Lupa password?</a>
           </div>

            <button type="submit" class="btn-premium group w-full flex items-center justify-center gap-2 mt-3 font-bold shadow-md">
                <span>Masuk Sekarang</span>
                <i class="fa-solid fa-arrow-right text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300"></i>
            </button>

            <!-- Tombol Kembali ke Landing Page -->
            <div class="text-center mb-2">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-sky transition-colors group">
                    <i class="fa-solid fa-arrow-left text-[11px] group-hover:-translate-x-1 transition-transform"></i>
                    <span>Kembali ke Landing Page</span>
                </a>
            </div>
        </form>

        <div class="mt-2 pt-5 border-t border-slate-100 text-center">
            <p class="text-[12px] text-slate-500 font-medium">
                Belum punya akun? <a href="{{ route('auth.register') }}" class="font-bold text-sky hover:text-skyDeep transition-colors">Daftar di sini</a>
            </p>
        </div>
    </div>

    <!-- MODAL POPUP USER TERBLOKIR / DISUSPEND & FORM BANDING -->
    @if (session('suspended_info'))
    @php $info = session('suspended_info'); @endphp
    <div id="suspendedModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-md p-4 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all duration-300 scale-100 my-4 max-h-[92vh] flex flex-col">
            
            <!-- VIEW 1: INFORMASI SUSPEND -->
            <div id="suspendInfoView" class="p-6 sm:p-7 overflow-y-auto space-y-5">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-3xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-3 shadow-inner border border-amber-200">
                        <i class="fa-solid fa-user-lock animate-bounce"></i>
                    </div>
                    <h2 class="font-display text-xl sm:text-2xl font-black text-slate-900">Akun Anda Dinonaktifkan</h2>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Halo <span class="text-slate-800 font-bold">{{ $info['username'] }}</span>, akun Anda telah dinonaktifkan sementara oleh Admin.</p>
                </div>

                <!-- BOX DURASI WAKTU -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50/50 border border-amber-200/80 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 text-amber-900 font-bold text-xs uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-clock text-amber-500"></i>
                        <span>Masa Penangguhan (Sanksi)</span>
                    </div>
                    <p class="text-sm font-black text-slate-800" id="suspendDurationText">{{ $info['duration_text'] }}</p>
                    
                    @if (!empty($info['target_timestamp']))
                    <div class="mt-2.5 pt-2.5 border-t border-amber-200/60 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-semibold text-[11px]">Hitung Mundur:</span>
                        <div id="countdownBadge" class="font-black text-amber-700 font-mono bg-white px-2.5 py-1 rounded-lg border border-amber-200 shadow-xs">
                            Memuat hitungan...
                        </div>
                    </div>
                    @endif
                </div>

                <!-- BOX ALASAN PEMBLOKIRAN -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        <span>Alasan Pemblokiran</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-relaxed italic bg-white p-3 rounded-xl border border-slate-200">
                        "{{ $info['reason'] }}"
                    </p>
                </div>

                <!-- STATUS BANDING SEBELUMNYA JIKA ADA -->
                @if (!empty($info['appeal_status']))
                    @if ($info['appeal_status'] === 'pending')
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-3.5 flex items-start gap-3 text-xs text-blue-800">
                        <i class="fa-solid fa-hourglass-half text-blue-500 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <p class="font-bold">Banding Sedang Ditinjau</p>
                            <p class="text-[11px] text-blue-600 mt-0.5">Anda telah mengirim banding pada {{ $info['appeal_date'] }}. Mohon menunggu keputusan Admin.</p>
                        </div>
                    </div>
                    @elseif ($info['appeal_status'] === 'rejected')
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-3.5 flex items-start gap-3 text-xs text-red-800">
                        <i class="fa-solid fa-circle-xmark text-red-500 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <p class="font-bold">Banding Sebelumnya Ditolak</p>
                            <p class="text-[11px] text-red-600 mt-0.5">{{ $info['appeal_admin_note'] ?? 'Alasan dan bukti tidak mencukupi.' }} Anda dapat mengajukan banding baru dengan bukti tambahan.</p>
                        </div>
                    </div>
                    @endif
                @endif

                <!-- TOMBOL AKSI -->
                <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button type="button" onclick="closeSuspendedModal()" class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
                    </button>
                    
                    <button type="button" onclick="showAppealView()" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-shield-halved"></i> Ajukan Banding
                    </button>
                </div>
            </div>

            <!-- VIEW 2: FORMULIR PENGAJUAN BANDING -->
            <div id="appealFormView" class="p-6 sm:p-7 overflow-y-auto space-y-5 hidden">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <button type="button" onclick="showSuspendInfoView()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition"><i class="fa-solid fa-arrow-left text-xs"></i></button>
                    <div>
                        <h3 class="font-display font-extrabold text-slate-900 text-base">Formulir Pengajuan Banding</h3>
                        <p class="text-[10px] text-slate-500 font-semibold">Jelaskan mengapa Anda tidak bersalah beserta bukti pendukung.</p>
                    </div>
                </div>

                <form action="{{ route('appeal.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $info['user_id'] }}">

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1">Alasan Mengapa Anda Tidak Bersalah <span class="text-red-500">*</span></label>
                        <textarea name="reason" rows="4" required placeholder="Jelaskan secara jelas kronologi, pembelaan, atau sanggahan Anda..." class="w-full border-2 border-slate-200 rounded-2xl p-3.5 text-xs font-semibold text-slate-800 focus:border-amber-500 focus:outline-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1">Unggah Bukti Gambar / Screenshot (Opsional)</label>
                        <div class="border-2 border-dashed border-slate-300 hover:border-amber-500 rounded-2xl p-4 text-center transition bg-slate-50 relative group cursor-pointer">
                            <input type="file" name="proof_image" id="proof_image_input" accept="image/*" onchange="previewProofImage(event)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                            <div id="uploadPlaceholder">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 group-hover:text-amber-500 transition mb-1.5 block"></i>
                                <span class="text-xs font-bold text-slate-700 block">Klik untuk pilih gambar bukti</span>
                                <span class="text-[10px] text-slate-400">Format: JPG, PNG, WEBP (Maks 5MB)</span>
                            </div>
                            <div id="imagePreviewContainer" class="hidden mt-2">
                                <img id="imagePreview" src="" alt="Preview Bukti" class="max-h-36 mx-auto rounded-xl shadow-md object-contain">
                                <span class="text-[10px] text-emerald-600 font-bold mt-1 block">Gambar dipilih ✓</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 grid grid-cols-2 gap-3">
                        <button type="button" onclick="showSuspendInfoView()" class="w-full py-3 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="w-full py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Banding
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @if (!empty($info['target_timestamp']))
    <script>
        (function() {
            const targetTime = {{ $info['target_timestamp'] }};
            const badge = document.getElementById('countdownBadge');
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetTime - now;

                if (distance <= 0) {
                    if (badge) badge.innerHTML = "Waktu suspend telah berakhir. Silakan login kembali!";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let text = "";
                if (days > 0) text += days + "h ";
                if (hours > 0 || days > 0) text += hours + "j ";
                text += minutes + "m " + seconds + "d tersisa";

                if (badge) badge.innerHTML = text;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
    </script>
    @endif

    @endif

    <!-- Script Tampil/Sembunyi Password & Modal Handling -->
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

        function closeSuspendedModal() {
            const modal = document.getElementById('suspendedModal');
            if (modal) modal.remove();
        }

        function showAppealView() {
            document.getElementById('suspendInfoView').classList.add('hidden');
            document.getElementById('appealFormView').classList.remove('hidden');
        }

        function showSuspendInfoView() {
            document.getElementById('appealFormView').classList.add('hidden');
            document.getElementById('suspendInfoView').classList.remove('hidden');
        }

        function previewProofImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
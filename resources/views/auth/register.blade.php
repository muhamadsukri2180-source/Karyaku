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
    <!-- Alpine.js untuk interaksi pilih role -> paket -> pembayaran -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
                        skyDeep: '#0B3D62',
                        skyDeeper: '#082C48',
                        skyPale: '#EFF8FF',
                        coral: '#FF7A59',
                        mint: '#14B8A6',
                        ink: '#0F2A44',
                        bronze: '#B45309',
                        silverc: '#64748B',
                        diamond: '#0891B2'
                    },
                    fontFamily: {
                        display: ['"Sora"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 10px 40px -10px rgba(11,61,98,0.35)',
                        glowSky: '0 8px 30px -6px rgba(14,165,233,0.5)'
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
        .animation-delay-4000 { animation-delay: 4s; }

        .btn-premium {
            position: relative; overflow: hidden; isolation: isolate;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium::before {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,0.4) 50%, transparent 80%);
            transform: translateX(-150%) skewX(-15deg); transition: transform 0.7s ease; z-index: 1; pointer-events: none;
        }
        .btn-premium:hover::before { transform: translateX(150%) skewX(-15deg); }
        .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -6px rgba(14,165,233,0.7); }
        .btn-premium:active { transform: translateY(0) scale(0.98); }
        .btn-premium > * { position: relative; z-index: 2; }
        .btn-premium:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
        .btn-premium:disabled::before { display: none; }

        [x-cloak] { display: none !important; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-skyDeeper text-ink antialiased min-h-screen w-full flex items-center justify-center relative p-4 overflow-hidden">

    <!-- Background Animasi Premium -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="grain-overlay"></div>
        <div class="absolute top-0 right-10 w-80 h-80 bg-coral/20 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-sky/25 rounded-full blur-[80px] animate-blob animation-delay-4000"></div>
    </div>

    <!-- Register Card -->
    <div
        x-data="{
            role: '{{ old('role', 'pembeli') }}',
            paket: '{{ old('paket_toko', '') }}',
            pembayaran: '{{ old('metode_pembayaran', '') }}',
            sudahBayar: false,
            hargaPaket: { bronze: 30000, silver: 50000, diamond: 100000 },
            rupiah(n){ return 'Rp ' + n.toLocaleString('id-ID'); },
            bisaSubmit(){
                if (this.role === 'pembeli') return true;
                if (this.role === 'kreator') return this.paket !== '' && this.pembayaran !== '' && this.sudahBayar;
                return false;
            }
        }"
        x-init="$watch('paket', () => sudahBayar = false); $watch('pembayaran', () => sudahBayar = false)"
        class="w-full max-w-[420px] bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-[1.5rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up max-h-[95vh] overflow-y-auto no-scrollbar"
    >

        <div class="text-center mb-5">
            <div class="w-10 h-10 mx-auto bg-gradient-to-br from-skyDeep to-sky rounded-xl flex items-center justify-center mb-3 shadow-md shadow-skyDeep/20 transform transition hover:scale-105 duration-300">
                <i class="fa-solid fa-layer-group text-white text-lg"></i>
            </div>

            <h1 class="font-display text-lg font-extrabold text-slate-900 leading-tight tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 text-[12px] mt-1 font-medium">Bergabung dengan Karyaku, jual atau beli karya digital</p>
        </div>

        {{-- Tampilkan pesan error validasi --}}
        @if ($errors->any())
            <div class="mb-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-medium p-3">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tampilkan pesan sukses (kalau ada) --}}
        @if (session('success'))
            <div class="mb-3 rounded-xl bg-green-50 border border-green-200 text-green-600 text-xs font-medium p-3">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('auth.register.submit') }}" method="POST" class="space-y-3" @submit="if(!bisaSubmit()){ $event.preventDefault(); alert('Lengkapi dulu paket toko & pembayaran sebelum daftar ya, bre.'); }">
            @csrf

            <!-- 1. Username -->
            <div class="group">
                <label for="username" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Pilih username" required
                        class="w-full pl-9 pr-3 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- 2. Email -->
            <div class="group">
                <label for="email" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                        class="w-full pl-9 pr-3 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">
                </div>
            </div>

            <!-- 3. Daftar Sebagai (Pembeli / Kreator) -->
            <div class="group">
                <label class="block text-[11px] font-bold text-slate-700 mb-1 ml-1">Daftar Sebagai</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-1.5 py-2 rounded-xl border border-slate-200 bg-skyPale text-[11px] font-bold text-slate-600 cursor-pointer transition-all has-[:checked]:bg-sky has-[:checked]:text-white has-[:checked]:border-sky">
                        <input type="radio" name="role" value="pembeli" x-model="role" class="hidden">
                        <i class="fa-solid fa-bag-shopping text-[11px]"></i> Pembeli
                    </label>
                    <label class="flex items-center justify-center gap-1.5 py-2 rounded-xl border border-slate-200 bg-skyPale text-[11px] font-bold text-slate-600 cursor-pointer transition-all has-[:checked]:bg-sky has-[:checked]:text-white has-[:checked]:border-sky">
                        <input type="radio" name="role" value="kreator" x-model="role" class="hidden">
                        <i class="fa-solid fa-store text-[11px]"></i> Kreator
                    </label>
                </div>
            </div>

            <!-- 3B. PAKET TOKO (khusus Kreator) -->
            <div x-show="role === 'kreator'" x-cloak x-transition class="group rounded-2xl bg-gradient-to-br from-skyPale to-white border border-sky-200 p-3 space-y-2">
                <label class="block text-[11px] font-bold text-slate-700 ml-1">Pilih Paket Toko Kreator</label>

                <!-- Bronze -->
                <label class="flex items-start gap-3 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all has-[:checked]:border-bronze has-[:checked]:bg-orange-50/70 has-[:checked]:shadow-sm">
                    <input type="radio" name="paket_toko" value="bronze" x-model="paket" class="hidden">
                    <div class="w-9 h-9 shrink-0 rounded-lg bg-orange-100 text-bronze flex items-center justify-center">
                        <i class="fa-solid fa-medal text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-extrabold text-slate-800">Bronze</p>
                            <p class="text-xs font-extrabold text-bronze">Rp 30.000</p>
                        </div>
                        <p class="text-[10.5px] text-slate-500 leading-snug mt-0.5">Cocok buat pemula. Bisa menambahkan <span class="font-bold">maksimal 5 produk</span> di toko kamu.</p>
                    </div>
                </label>

                <!-- Silver -->
                <label class="flex items-start gap-3 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all has-[:checked]:border-silverc has-[:checked]:bg-slate-50 has-[:checked]:shadow-sm">
                    <input type="radio" name="paket_toko" value="silver" x-model="paket" class="hidden">
                    <div class="w-9 h-9 shrink-0 rounded-lg bg-slate-200 text-silverc flex items-center justify-center">
                        <i class="fa-solid fa-award text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-extrabold text-slate-800">Silver</p>
                            <p class="text-xs font-extrabold text-silverc">Rp 50.000</p>
                        </div>
                        <p class="text-[10.5px] text-slate-500 leading-snug mt-0.5">Buat yang serius jualan. Bisa menambahkan <span class="font-bold">lebih banyak produk (hingga 20 produk)</span>.</p>
                    </div>
                </label>

                <!-- Diamond -->
                <label class="flex items-start gap-3 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all has-[:checked]:border-sky has-[:checked]:bg-sky-50 has-[:checked]:shadow-sm relative overflow-hidden">
                    <span class="absolute top-0 right-0 bg-sky text-white text-[8px] font-bold px-2 py-0.5 rounded-bl-lg">PALING LARIS</span>
                    <input type="radio" name="paket_toko" value="diamond" x-model="paket" class="hidden">
                    <div class="w-9 h-9 shrink-0 rounded-lg bg-sky-100 text-sky flex items-center justify-center">
                        <i class="fa-solid fa-gem text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-extrabold text-slate-800">Diamond</p>
                            <p class="text-xs font-extrabold text-sky">Rp 100.000</p>
                        </div>
                        <p class="text-[10.5px] text-slate-500 leading-snug mt-0.5">Paling maksimal. Bisa menambahkan <span class="font-bold">produk tanpa batas</span> + <span class="font-bold">pasang iklan promosi</span> di halaman utama Karyaku.</p>
                    </div>
                </label>
            </div>

            <!-- 3C. METODE PEMBAYARAN (muncul setelah paket dipilih) -->
            <div x-show="role === 'kreator' && paket !== ''" x-cloak x-transition class="rounded-2xl bg-white border border-sky-200 p-3 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-bold text-slate-700 ml-1">Metode Pembayaran</label>
                    <p class="text-[11px] font-extrabold text-sky" x-text="paket ? rupiah(hargaPaket[paket]) : ''"></p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-slate-200 bg-skyPale text-[11px] font-bold text-slate-600 cursor-pointer transition-all has-[:checked]:bg-slate-900 has-[:checked]:text-white has-[:checked]:border-slate-900">
                        <input type="radio" name="metode_pembayaran" value="qris" x-model="pembayaran" class="hidden">
                        <i class="fa-solid fa-qrcode text-xs"></i> QRIS
                    </label>
                    <label class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-slate-200 bg-skyPale text-[11px] font-bold text-slate-600 cursor-pointer transition-all has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600">
                        <input type="radio" name="metode_pembayaran" value="dana" x-model="pembayaran" class="hidden">
                        <i class="fa-solid fa-wallet text-xs"></i> DANA
                    </label>
                </div>

                <!-- Panel QRIS -->
                <div x-show="pembayaran === 'qris'" x-cloak x-transition class="flex flex-col items-center gap-2 bg-slate-50 border border-dashed border-slate-300 rounded-xl p-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=KARYAKU-QRIS-PAYMENT"
                         alt="QRIS Karyaku" class="w-32 h-32 rounded-lg border border-slate-200 bg-white p-1">
                    <p class="text-[10.5px] text-slate-500 text-center leading-snug">Scan QRIS di atas pakai aplikasi e-wallet / m-banking apapun untuk membayar <span class="font-bold" x-text="paket ? rupiah(hargaPaket[paket]) : ''"></span>.</p>
                </div>

                <!-- Panel DANA -->
                <div x-show="pembayaran === 'dana'" x-cloak x-transition class="bg-blue-50 border border-dashed border-blue-200 rounded-xl p-3 space-y-1.5">
                    <p class="text-[10.5px] text-slate-600">Transfer ke nomor DANA berikut:</p>
                    <div class="flex items-center justify-between bg-white rounded-lg border border-blue-200 px-3 py-2">
                        <span class="text-xs font-extrabold text-blue-700 tracking-wide">0812-3456-7890</span>
                        <button type="button" onclick="navigator.clipboard.writeText('081234567890'); this.innerText='Tersalin!'; setTimeout(()=>this.innerText='Salin', 1500)"
                            class="text-[10px] font-bold text-blue-600 hover:underline">Salin</button>
                    </div>
                    <p class="text-[10px] text-slate-500">a.n. Karyaku Digital Store — nominal <span class="font-bold" x-text="paket ? rupiah(hargaPaket[paket]) : ''"></span></p>
                </div>

                <!-- Konfirmasi Sudah Bayar -->
                <div x-show="pembayaran !== ''" x-cloak>
                    <button type="button" @click="sudahBayar = !sudahBayar"
                        :class="sudahBayar ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-300 text-slate-600'"
                        class="w-full flex items-center justify-center gap-2 py-2 rounded-xl border-2 text-[11px] font-bold transition-all">
                        <i :class="sudahBayar ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle'" class="text-sm"></i>
                        <span x-text="sudahBayar ? 'Pembayaran Dikonfirmasi' : 'Saya Sudah Membayar'"></span>
                    </button>
                </div>

                <!-- Hidden fields dikirim ke backend -->
                <input type="hidden" name="status_pembayaran" :value="sudahBayar ? 'menunggu_verifikasi' : 'belum_bayar'">
            </div>

            <!-- Bagian Grid untuk 2 Password -->
            <div class="grid grid-cols-2 gap-3">
                <!-- 4. Password -->
                <div class="group">
                    <label for="password" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="w-full pl-8 pr-8 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                        <button type="button" onclick="togglePassword('password', 'eye-icon-reg-pass')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-xs" id="eye-icon-reg-pass"></i>
                        </button>
                    </div>
                </div>

                <!-- 5. Konfirmasi Password -->
                <div class="group">
                    <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-sky">Konfirmasi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-double text-slate-400 text-xs group-focus-within:text-sky transition-colors"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required
                            class="w-full pl-8 pr-8 py-2 rounded-xl bg-skyPale border border-slate-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition-all duration-300">

                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-reg-conf')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none">
                            <i class="fa-solid fa-eye text-xs" id="eye-icon-reg-conf"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 6. Centang Persetujuan -->
            <div class="flex items-start pt-1 pb-0.5">
                <div class="flex items-center h-4 mt-0.5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="w-3.5 h-3.5 border-2 border-slate-300 rounded bg-skyPale focus:ring-2 focus:ring-sky/50 checked:bg-sky checked:border-sky transition-colors cursor-pointer">
                </div>
                <label for="terms" class="ml-2.5 text-[10px] text-slate-600 leading-tight font-medium cursor-pointer">
                    Saya menyetujui <a href="#" class="text-sky font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-sky font-bold hover:underline">Kebijakan Privasi</a>.
                </label>
            </div>

            <button type="submit" :disabled="!bisaSubmit()" class="btn-premium group w-full flex items-center justify-center gap-2 bg-gradient-to-r from-sky to-skyDeep text-white py-2.5 rounded-xl text-[13px] font-bold shadow-glowSky mt-2">
                <span x-text="role === 'kreator' ? 'Bayar & Daftar Sekarang' : 'Daftar Sekarang'"></span>
                <i class="fa-solid fa-arrow-right text-[11px] opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all duration-300"></i>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-500 font-medium">
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
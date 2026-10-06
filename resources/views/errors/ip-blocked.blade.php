@php
    $displayIp  = $ip ?? request()->ip();
    $rawReason  = $reason ?? null;
    $rawCat     = $category ?? null;
    $reasonData = \App\Support\BanReason::present($rawReason, $rawCat);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - IP Diblokir | Karyaku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-attachment: fixed;
        }
        .font-display { font-family: 'Sora', sans-serif; }
        .animation-delay-2000 { animation-delay: 2s; }
        .grain-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            opacity: 0.05; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full py-10 px-4">

    <!-- Background Animasi -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="grain-overlay"></div>
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob animation-delay-2000"></div>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-lg mx-auto bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-[1.8rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <!-- Top accent bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-sky-400 to-blue-600 rounded-t-[1.8rem]"></div>

        <!-- Header -->
        <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center shadow-md border border-slate-100 bg-white shrink-0">
                    <img src="{{ asset('image/logo.png') }}" alt="Karyaku" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-lg text-slate-900 leading-tight">Akses Diblokir</h1>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Karyaku Security Protection</p>
                </div>
            </div>
            <span class="px-3 py-1.5 bg-red-50 text-red-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider border border-red-100 shrink-0">
                <i class="fa-solid fa-shield-halved mr-1"></i> Diblokir
            </span>
        </div>

        <!-- Icon & Title -->
        <div class="text-center mb-5">
            <div class="flex flex-col items-center justify-center mb-4">
                <!-- Logo Box dengan aksen ban -->
                <div class="relative mb-3.5">
                    <div class="w-20 h-20 rounded-2xl bg-white border border-red-200/80 shadow-md flex items-center justify-center p-3 relative overflow-hidden">
                        <span class="absolute inset-0 bg-red-500/10 animate-pulse pointer-events-none"></span>
                        <img src="{{ asset('image/logo.png') }}" alt="Logo Karyaku" class="w-12 h-12 object-contain relative z-10 drop-shadow-sm">
                    </div>
                    <!-- Badge status ban kecil di sudut -->
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px] shadow-md border-2 border-white z-20">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>

                <!-- Label Kategori Pelanggaran (Diberi jarak lega agar tidak rapat) -->
                <div class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-600 px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider shadow-2xs">
                    <i class="{{ $reasonData['icon'] }} text-xs"></i>
                    <span>{{ $reasonData['label'] }}</span>
                </div>
            </div>

            <h2 class="font-display text-xl font-extrabold text-slate-900 tracking-tight mt-1">
                {{ $reasonData['title'] }}
            </h2>

            <div class="mt-3 mb-4 p-3.5 rounded-2xl bg-red-50 border border-red-100 text-left">
                <p class="text-xs font-semibold text-red-700 leading-relaxed flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-sm mt-0.5 shrink-0"></i>
                    <span>{{ $reasonData['headline'] }}</span>
                </p>
            </div>

            <p class="text-slate-500 text-[12px] font-medium leading-relaxed max-w-sm mx-auto">
                Alamat IP Anda diblokir oleh <span class="text-blue-600 font-bold">Administrator Karyaku</span>. Anda tidak dapat mengakses landing page, login, maupun menggunakan fitur platform.
            </p>
        </div>

        <!-- Diagnostic Box -->
        <div class="bg-skyPale/70 border border-sky-100 rounded-2xl p-4 mb-5 space-y-3">

            @if(!empty($username))
            <!-- Akun Terkait -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-sky-200/50">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-user-tag text-sky text-xs"></i> Akun Terkait
                </span>
                <span class="text-xs font-bold text-slate-800 bg-white px-3 py-1.5 rounded-xl border border-sky-100 flex items-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-circle-user text-sky"></i>
                    <span>{{ $username }}</span>
                    @if(!empty($email))
                        <span class="text-slate-400 font-normal text-[11px]">&lt;{{ $email }}&gt;</span>
                    @endif
                </span>
            </div>
            @endif

            <!-- IP Terdeteksi -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-sky-200/50">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-network-wired text-sky text-xs"></i> Alamat IP
                </span>
                <span class="font-mono text-xs font-bold text-blue-700 bg-white px-3 py-1.5 rounded-xl border border-blue-200 shadow-2xs">
                    {{ $displayIp }}
                </span>
            </div>

            <!-- Kategori -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-sky-200/50">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-sky text-xs"></i> Kategori Pelanggaran
                </span>
                <span class="text-xs font-bold text-amber-800 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200 shadow-2xs">
                    {{ $reasonData['label'] }}
                </span>
            </div>

            <!-- Rincian Pelanggaran (Tata letak rapi & lega) -->
            <div class="pb-3 border-b border-sky-200/50 space-y-1.5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-sky text-xs"></i> Rincian Pelanggaran
                </span>
                <div class="bg-white p-3 rounded-xl border border-sky-100 text-xs font-medium text-slate-700 leading-relaxed shadow-2xs flex items-start gap-2">
                    <i class="fa-solid fa-caret-right text-sky text-xs mt-0.5 shrink-0"></i>
                    <span class="break-words">{{ $reasonData['detail'] }}</span>
                </div>
            </div>

            <!-- Waktu -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-sky-200/50">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-slate-400 text-xs"></i> Waktu Pemblokiran
                </span>
                <span class="font-mono text-xs text-slate-600 bg-white px-2.5 py-1 rounded-lg border border-sky-100">
                    {{ $blocked_at ?? now()->translatedFormat('d M Y, H:i') . ' WIB' }}
                </span>
            </div>

            <!-- Incident Reference -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-fingerprint text-slate-400 text-xs"></i> Ref. Insiden
                </span>
                <span class="font-mono text-[11px] text-slate-400 bg-white px-2 py-0.5 rounded-lg border border-sky-100">
                    SEC-{{ strtoupper($reasonData['category']) }}-{{ strtoupper(substr(md5($displayIp . ($username ?? '') . config('app.key', 'karyaku-secret')), 0, 8)) }}
                </span>
            </div>
        </div>

        <!-- Notice -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 mb-5 flex items-start gap-3">
            <i class="fa-solid fa-shield-halved text-blue-400 text-base mt-0.5 shrink-0"></i>
            <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                <strong class="font-bold text-blue-700">Keamanan Sistem Aktif:</strong> Segala akses dari alamat IP ini ke seluruh halaman website ditolak otomatis. Untuk permohonan pembukaan blokir, hubungi admin melalui email resmi di bawah.
            </p>
        </div>

        <!-- Action Buttons — Email Only -->
        <div class="space-y-2.5">
            <a href="mailto:karyakuustore@gmail.com?subject={{ urlencode('Banding Pemblokiran IP: ' . $displayIp) }}&body={{ urlencode('Halo Tim Support Karyaku,' . "\n\n" . 'Saya ingin mengajukan permohonan pembukaan blokir:' . "\n" . '- IP: ' . $displayIp . "\n" . '- Akun: ' . ($username ?? 'N/A') . "\n" . '- Pelanggaran: ' . $reasonData['label'] . "\n" . '- Rincian: ' . $reasonData['headline'] . "\n\n" . 'Mohon ditinjau kembali. Terima kasih.') }}"
               class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-sky-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md shadow-blue-900/20 transition-all cursor-pointer">
                <i class="fa-solid fa-envelope text-sm"></i> Kirim Email Banding ke Admin (karyakuustore@gmail.com)
            </a>

            <button type="button" onclick="window.location.reload()"
                    class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 hover:text-slate-900 font-semibold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i> Periksa Status Ulang
            </button>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center pt-5 border-t border-slate-100">
            <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 font-semibold">
                <i class="fa-solid fa-shield-halved text-slate-300"></i>
                <span>Karyaku Security System &bull; {{ date('Y') }}</span>
            </div>
        </div>

    </div>

</body>
</html>

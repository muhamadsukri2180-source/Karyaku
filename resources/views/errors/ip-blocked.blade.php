<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Akun & IP Diblokir | Karyaku Security</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        cyber: {
                            900: '#070b14',
                            800: '#0d1527',
                            700: '#14213d',
                            red: '#ef4444',
                            crimson: '#dc2626',
                            rose: '#f43f5e',
                            amber: '#f59e0b',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #060911;
            background-image: 
                radial-gradient(at 0% 0%, rgba(220, 38, 38, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(244, 63, 94, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(13, 21, 39, 0.7) 0px, transparent 100%);
            background-attachment: fixed;
        }
        .cyber-grid {
            background-size: 36px 36px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 cyber-grid flex items-center justify-center p-4 antialiased selection:bg-rose-500 selection:text-white">

    <div class="max-w-xl w-full my-8 relative">
        <!-- Ambient Glowing Aura -->
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-rose-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Main Security Card -->
        <div class="relative bg-slate-900/95 backdrop-blur-xl border border-red-500/30 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-red-950/50 overflow-hidden">
            
            <!-- Top Alert Banner Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-rose-500 to-amber-500"></div>

            <!-- Shield Icon & Badge -->
            <div class="text-center mb-7">
                <div class="relative inline-flex items-center justify-center mb-5">
                    <div class="w-24 h-24 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center shadow-inner relative group">
                        <span class="absolute inset-0 rounded-2xl bg-red-500/20 animate-ping opacity-30"></span>
                        <i class="fa-solid fa-user-lock text-4xl sm:text-5xl text-red-500 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]"></i>
                    </div>
                    <div class="absolute -bottom-2 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-0.5 rounded-full shadow-lg border border-red-400">
                        AKUN &amp; IP DIBLOKIR
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-2">
                    Akses Sistem Ditolak
                </h1>
                <p class="text-slate-400 text-xs sm:text-sm font-medium leading-relaxed max-w-md mx-auto">
                    Akun pengguna dan alamat IP Anda telah diblokir oleh <span class="text-red-400 font-bold">Administrator Karyaku</span>. Anda tidak dapat melakukan login maupun mengakses fitur platform.
                </p>
            </div>

            <!-- Security Diagnostic Box -->
            <div class="bg-slate-950/80 rounded-2xl p-5 border border-red-500/20 mb-6 space-y-3.5 shadow-inner">
                
                @if(!empty($username))
                <!-- Target User Account -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-user-tag text-rose-400 text-xs"></i> Akun Pengguna
                    </span>
                    <span class="font-sans text-xs sm:text-sm font-bold text-white bg-slate-900 px-2.5 py-1 rounded-lg border border-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-user text-red-400"></i>
                        <span>{{ $username }}</span>
                        @if(!empty($email))
                            <span class="text-slate-400 font-normal text-xs">&lt;{{ $email }}&gt;</span>
                        @endif
                    </span>
                </div>
                @endif

                <!-- Client IP Display -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-network-wired text-red-400 text-xs"></i> Alamat IP Terdeteksi
                    </span>
                    <span class="font-mono text-xs sm:text-sm font-bold text-red-400 bg-red-950/60 px-2.5 py-1 rounded-lg border border-red-900/60">
                        {{ $ip ?? request()->ip() }}
                    </span>
                </div>

                <!-- Block Reason -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5 shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 text-xs"></i> Alasan Pemblokiran
                    </span>
                    <span class="text-xs font-semibold text-slate-200 text-left sm:text-right bg-slate-900/80 px-2.5 py-1 rounded-lg border border-slate-800">
                        {{ $reason ?? 'Akun dan Alamat IP Anda diblokir oleh Administrator sistem.' }}
                    </span>
                </div>

                <!-- Timestamp -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-clock text-slate-400 text-xs"></i> Waktu Pemblokiran
                    </span>
                    <span class="font-mono text-xs text-slate-300">
                        {{ $blocked_at ?? now()->translatedFormat('d M Y, H:i') . ' WIB' }}
                    </span>
                </div>

                <!-- Reference Code -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-fingerprint text-slate-400 text-xs"></i> Security Incident Code
                    </span>
                    <span class="font-mono text-[11px] text-slate-400">
                        SEC-BLOCK-{{ strtoupper(substr(md5(($ip ?? request()->ip()) . ($username ?? '') . config('app.key')), 0, 10)) }}
                    </span>
                </div>

            </div>

            <!-- Notice & Advisory -->
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3.5 mb-6 flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-400 text-sm mt-0.5 shrink-0"></i>
                <div class="text-[11px] text-red-200/90 leading-relaxed font-normal">
                    <strong class="font-bold text-red-300">Peringatan Keamanan:</strong> Sesi akun telah dinonaktifkan oleh Administrator. Segala percobaan login atau akses fitur akan otomatis diblokir sampai blokir dibuka oleh Admin.
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin Karyaku, akun/IP saya (' . ($username ?? $ip ?? request()->ip()) . ') diblokir oleh Admin dengan alasan: ' . ($reason ?? '-') . '. Mohon bantuan peninjauan/pembukaan blokir.') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg shadow-red-900/30 transition-all cursor-pointer">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Administrator (WhatsApp Banding)
                </a>

                <div class="flex flex-col sm:flex-row gap-2.5">
                    <a href="mailto:support@karyaku.com?subject={{ urlencode('Permohonan Pembukaan Blokir: ' . ($username ?? $ip ?? request()->ip())) }}&body={{ urlencode('Halo Tim Support Karyaku,\n\nSaya ingin mengajukan permohonan pembukaan blokir untuk akun: ' . ($username ?? 'N/A') . ' / IP: ' . ($ip ?? request()->ip()) . '\nAlasan terblokir: ' . ($reason ?? 'N/A') . '\n\nTerima kasih.') }}" 
                       class="flex-1 py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-envelope"></i> Kirim Email Support
                    </a>
                    <button type="button" onclick="window.location.reload()" 
                            class="flex-1 py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 text-slate-300 hover:text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <i class="fa-solid fa-rotate-right"></i> Periksa Status Ulang
                    </button>
                </div>
            </div>

            <!-- Footer Branding -->
            <div class="mt-8 text-center pt-5 border-t border-slate-800/80">
                <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 font-semibold">
                    <i class="fa-solid fa-shield-virus text-slate-600"></i>
                    <span>Karyaku Cyber Shield Protection System &bull; 2026</span>
                </div>
            </div>

        </div>
    </div>

</body>
</html>

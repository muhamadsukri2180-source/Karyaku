@php
    $clientIp = request()->ip();
    $rawReason = $exception->getMessage() ?: 'Akses Anda dibatasi oleh Sistem Keamanan Karyaku.';
    $reasonData = \App\Support\BanReason::present($rawReason);
@endphp
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Karyaku Security</title>
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
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-rose-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative bg-slate-900/90 backdrop-blur-xl border border-red-500/30 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-red-950/40 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-rose-500 to-amber-500"></div>

            <div class="text-center mb-6">
                <div class="relative inline-flex items-center justify-center mb-5">
                    <div class="w-24 h-24 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center shadow-inner relative group">
                        <span class="absolute inset-0 rounded-2xl bg-red-500/20 animate-ping opacity-30"></span>
                        <i class="{{ $reasonData['icon'] }} text-4xl sm:text-5xl text-red-500 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]"></i>
                    </div>
                    <div class="absolute -bottom-2 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-0.5 rounded-full shadow-lg border border-red-400">
                        {{ $reasonData['label'] }}
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-2">
                    {{ $reasonData['title'] }}
                </h1>

                <div class="mt-3 mb-3 p-4 rounded-2xl bg-red-950/60 border border-red-500/30 shadow-inner">
                    <p class="text-xs sm:text-sm font-bold text-red-200 leading-relaxed text-center">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1.5"></i>
                        {{ $reasonData['headline'] }}
                    </p>
                </div>

                <p class="text-slate-400 text-xs sm:text-sm font-medium leading-relaxed max-w-md mx-auto">
                    Akses Anda ke halaman ini ditolak oleh sistem keamanan platform Karyaku.
                </p>
            </div>

            <div class="bg-slate-950/80 rounded-2xl p-5 border border-red-500/20 mb-6 space-y-3.5 shadow-inner">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-network-wired text-red-400 text-xs"></i> Alamat IP Anda
                    </span>
                    <span class="font-mono text-xs sm:text-sm font-bold text-red-400 bg-red-950/60 px-2.5 py-1 rounded-lg border border-red-900/60">
                        {{ $clientIp }}
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-slate-800/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-amber-400 text-xs"></i> Kategori
                    </span>
                    <span class="text-xs font-bold text-amber-300 bg-amber-950/50 px-2.5 py-1 rounded-lg border border-amber-900/60">
                        {{ $reasonData['label'] }}
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-fingerprint text-slate-400 text-xs"></i> Incident Reference
                    </span>
                    <span class="font-mono text-[11px] text-slate-400">
                        SEC-403-{{ strtoupper(substr(md5($clientIp . config('app.key')), 0, 10)) }}
                    </span>
                </div>
            </div>

            <div class="space-y-3">
                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Tim Support Karyaku, akses saya diblokir pada IP: ' . $clientIp . '. Keterangan: ' . $reasonData['headline'] . '. Mohon bantuan peninjauan.') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg shadow-red-900/30 transition-all cursor-pointer">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Customer Service (Banding)
                </a>

                <div class="flex flex-col sm:flex-row gap-2.5">
                    <a href="mailto:support@karyaku.com?subject={{ urlencode('Banding 403: ' . $clientIp) }}&body={{ urlencode('Halo Tim Support Karyaku,\n\nSaya ingin mengajukan permohonan pembukaan blokir untuk IP: ' . $clientIp . '\nAlasan: ' . $reasonData['headline'] . '\n\nTerima kasih.') }}" 
                       class="flex-1 py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-envelope"></i> Kirim Email Support
                    </a>
                    <button type="button" onclick="window.location.reload()" 
                            class="flex-1 py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 text-slate-300 hover:text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <i class="fa-solid fa-rotate-right"></i> Periksa Status Ulang
                    </button>
                </div>
            </div>

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

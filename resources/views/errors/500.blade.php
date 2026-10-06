<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Gangguan Server Sementara | Karyaku</title>
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
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
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
<body class="min-h-screen text-slate-100 cyber-grid flex items-center justify-center p-4 antialiased selection:bg-blue-500 selection:text-white">

    <div class="max-w-xl w-full my-8 relative">
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative bg-slate-900/90 backdrop-blur-xl border border-blue-500/30 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-blue-950/40 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 via-sky-500 to-indigo-500"></div>

            <div class="text-center mb-6">
                <div class="relative inline-flex items-center justify-center mb-5">
                    <div class="w-24 h-24 rounded-2xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center shadow-inner relative group">
                        <i class="fa-solid fa-server text-4xl sm:text-5xl text-blue-400 drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]"></i>
                    </div>
                    <div class="absolute -bottom-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-blue-400/30">
                        Error 500
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Gangguan Server Sementara
                </h1>
                <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto leading-relaxed">
                    Sistem sedang memproses pembaruan atau mengalami kendala internal sementara. Silakan segarkan halaman dalam beberapa saat.
                </p>
            </div>

            <div class="space-y-3">
                <a href="{{ url('/') }}"
                   class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 transition-all cursor-pointer">
                    <i class="fa-solid fa-house text-sm"></i> Kembali ke Beranda
                </a>

                <button type="button" onclick="window.location.reload()"
                        class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <i class="fa-solid fa-rotate-right"></i> Segarkan Halaman
                </button>
            </div>

            <div class="mt-6 text-center pt-5 border-t border-slate-800/80">
                <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-500 font-medium">
                    <i class="fa-solid fa-shield-halved text-slate-600"></i>
                    <span>Karyaku Server Protection &bull; {{ date('Y') }}</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

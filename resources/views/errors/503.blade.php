<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Dalam Perbaikan - Karyaku</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-skyDeep via-skyHover to-sky text-slate-800 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- POPUP CARD MAINTENANCE -->
    <div class="relative w-full max-w-[270px] bg-white border border-sky-100 rounded-xl p-3.5 shadow-2xl shadow-skyDeep/30 text-center z-10">

        <!-- Icon Badge -->
        <div class="mx-auto w-9 h-9 rounded-xl bg-gradient-to-tr from-sky to-skyHover flex items-center justify-center text-white text-sm shadow-md shadow-sky-500/30 mb-2">
            <i class="fa-solid fa-wrench"></i>
        </div>

        <!-- Header Status -->
        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-600 text-[9px] font-bold uppercase tracking-wider mb-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Mode Pemeliharaan Aktif
        </span>

        <h1 class="text-sm font-extrabold font-display text-slate-900 tracking-tight leading-tight">
            Server Sedang Maintenance
        </h1>

        <p class="text-slate-500 text-[10px] mt-1 leading-snug">
            Sistem kami sedang melakukan perbaikan rutin. Kami akan segera kembali!
        </p>

        @php
            $endAtCarbon = $maintenance_end_at ? \Carbon\Carbon::parse($maintenance_end_at, 'Asia/Jakarta') : null;
            $targetTimestampMs = $endAtCarbon ? ($endAtCarbon->timestamp * 1000) : 0;
        @endphp

        <!-- KARTU ESTIMASI SELESAI -->
        <div class="mt-2.5 p-2 rounded-lg bg-sky-50 border border-sky-100 flex flex-col items-center justify-center gap-0.5">
            <p class="text-[8px] font-bold text-sky-700/70 uppercase tracking-widest">
                <i class="fa-solid fa-calendar-check text-sky mr-1"></i> Estimasi Selesai
            </p>
            <p class="text-[11px] font-extrabold text-skyDeep">
                @if($endAtCarbon)
                    {{ $endAtCarbon->setTimezone('Asia/Jakarta')->translatedFormat('d F Y - H:i') }} WIB
                @else
                    Akan Diumumkan Segera
                @endif
            </p>
        </div>

        <!-- COUNTDOWN TIMER -->
        <div class="mt-2.5">
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Waktu Mundur Berakhir</p>

            <div id="countdownBox" class="grid grid-cols-4 gap-1 {{ $endAtCarbon ? '' : 'opacity-40 pointer-events-none' }}">
                <div class="bg-sky-50 border border-sky-100 rounded-lg py-1.5">
                    <span id="days" class="block text-xs font-black text-skyHover font-display">00</span>
                    <span class="text-[7px] font-bold text-slate-400 uppercase">Hari</span>
                </div>
                <div class="bg-sky-50 border border-sky-100 rounded-lg py-1.5">
                    <span id="hours" class="block text-xs font-black text-skyHover font-display">00</span>
                    <span class="text-[7px] font-bold text-slate-400 uppercase">Jam</span>
                </div>
                <div class="bg-sky-50 border border-sky-100 rounded-lg py-1.5">
                    <span id="minutes" class="block text-xs font-black text-skyHover font-display">00</span>
                    <span class="text-[7px] font-bold text-slate-400 uppercase">Menit</span>
                </div>
                <div class="bg-sky-50 border border-sky-100 rounded-lg py-1.5">
                    <span id="seconds" class="block text-xs font-black text-amber-500 font-display">00</span>
                    <span class="text-[7px] font-bold text-slate-400 uppercase">Detik</span>
                </div>
            </div>

            <p id="timeFinishedText" class="hidden text-[10px] font-bold text-emerald-600 mt-2">
                <i class="fa-solid fa-circle-check mr-1"></i> Perbaikan selesai! Memuat ulang halaman...
            </p>

            <p id="autoCheckText" class="text-[8px] text-slate-400 mt-2 flex items-center justify-center gap-1">
                <span class="w-1 h-1 rounded-full bg-sky-400 animate-pulse"></span>
                Memeriksa status server secara otomatis...
            </p>
        </div>

        <!-- Footer Action -->
        <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-col items-center justify-center gap-1.5 text-[8px] text-slate-400">
            <button onclick="forceRefresh()" class="w-full px-3 py-1.5 bg-sky text-white hover:bg-skyHover rounded-lg font-bold text-[10px] transition-all flex items-center justify-center gap-1.5 shadow-sm shadow-sky-500/20">
                <i class="fa-solid fa-rotate-right"></i> Cek Status
            </button>

            <a href="{{ route('auth.login') }}" class="w-full px-3 py-1.5 bg-white text-skyHover border border-sky-200 hover:bg-sky-50 rounded-lg font-bold text-[10px] transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Kembali ke Login
            </a>

            <span class="mt-0.5">&copy; {{ date('Y') }} Karyaku. All rights reserved.</span>
        </div>
    </div>

    <!-- SCRIPT COUNTDOWN + AUTO REFRESH -->
    <script>
        const targetTimeMs = Number("{{ $targetTimestampMs }}");
        let isRedirecting = false;

        function forceRefresh() {
            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
        }

        // === 1. COUNTDOWN TIMER MENGGUNAKAN MILIDETIK UNIK ===
        if (targetTimeMs > 0) {
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const diff = targetTimeMs - now;

                if (diff <= 0) {
                    clearInterval(timer);
                    document.getElementById('days').innerText = '00';
                    document.getElementById('hours').innerText = '00';
                    document.getElementById('minutes').innerText = '00';
                    document.getElementById('seconds').innerText = '00';
                    document.getElementById('timeFinishedText').classList.remove('hidden');
                    document.getElementById('autoCheckText').classList.add('hidden');

                    if (!isRedirecting) {
                        isRedirecting = true;
                        setTimeout(forceRefresh, 1000);
                    }
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('days').innerText = String(d).padStart(2, '0');
                document.getElementById('hours').innerText = String(h).padStart(2, '0');
                document.getElementById('minutes').innerText = String(m).padStart(2, '0');
                document.getElementById('seconds').innerText = String(s).padStart(2, '0');
            }, 1000);
        }

        // === 2. AUTO-CHECK SERVER (Polling 5 Detik) ===
        const checkInterval = setInterval(() => {
            if (isRedirecting) return;

            fetch(window.location.pathname + '?check_status=' + Date.now(), { 
                method: 'GET', 
                cache: 'no-store',
                headers: { 'Cache-Control': 'no-cache' }
            })
            .then((res) => {
                if (res.status !== 503) {
                    clearInterval(checkInterval);
                    isRedirecting = true;
                    forceRefresh();
                }
            })
            .catch(() => {});
        }, 5000);
    </script>
</body>
</html>
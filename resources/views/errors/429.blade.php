<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terlalu Banyak Permintaan - Karyaku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-orange-100 overflow-hidden text-center relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-400 to-amber-500"></div>
        <div class="p-8">
            <div class="w-20 h-20 mx-auto bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-4xl mb-6 shadow-inner border border-orange-100">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Wow, Santai Dulu!</h1>
            <p class="text-slate-600 text-sm mb-6 leading-relaxed font-medium">
                Sistem keamanan kami mendeteksi terlalu banyak permintaan masuk dari Anda dalam waktu singkat. Hal ini wajar terjadi jika Anda mencoba login berkali-kali.
            </p>
            <div class="bg-orange-50 rounded-xl p-4 mb-6 border border-orange-100 text-left flex items-center justify-between">
                <div>
                    <p class="text-xs text-orange-600 font-bold uppercase mb-1">Status Keamanan:</p>
                    <p class="font-mono text-sm text-orange-700 font-bold">Rate Limit Aktif (429)</p>
                </div>
                <div class="text-orange-400 text-2xl">
                    <i class="fa-solid fa-shield-cat"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-medium mb-5">
                <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Silakan tunggu sekitar <strong>1 Menit</strong> sebelum mencoba kembali.
            </p>
            <button onclick="window.location.reload()" class="inline-block w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-md transition-colors text-sm mb-3">
                <i class="fa-solid fa-rotate-right mr-2"></i> Coba Refresh Halaman
            </button>
            <a href="/" class="text-xs text-slate-500 hover:text-slate-800 font-semibold underline">
                Atau kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>

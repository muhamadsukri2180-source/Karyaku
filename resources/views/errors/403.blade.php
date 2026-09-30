<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Diblokir - Keamanan Sistem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-red-100 overflow-hidden text-center relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
        <div class="p-8">
            <div class="w-20 h-20 mx-auto bg-red-50 text-red-600 rounded-full flex items-center justify-center text-4xl mb-6 shadow-inner border border-red-100">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Akses Ditolak</h1>
            <p class="text-slate-600 text-sm mb-6 leading-relaxed font-medium">
                {{ $exception->getMessage() ?: 'IP Anda sedang dibekukan karena adanya aktivitas yang terdeteksi mencurigakan oleh sistem keamanan kami.' }}
            </p>
            <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-100 text-left flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase mb-1">Status IP Anda:</p>
                    <p class="font-mono text-sm text-red-600 font-bold">{{ request()->ip() }} (DIBEKUKAN)</p>
                </div>
                <div class="text-red-500 text-2xl">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <button onclick="window.history.back()" class="inline-block w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-md transition-colors text-sm mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Halaman Sebelumnya
            </button>
            <a href="mailto:support@karyaku.com" class="text-xs text-slate-500 hover:text-slate-800 font-semibold underline">
                Hubungi Support jika ini adalah kesalahan
            </a>
        </div>
    </div>
</body>
</html>

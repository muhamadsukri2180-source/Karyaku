<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Karyaku - Verifikator Panel')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        @include('verifikator.partials.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">@yield('header_title', 'Dashboard Verifikator')</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">@yield('header_subtitle', '')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @yield('header_right')
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

        function toggleSidebar() {
            if (!sidebar) return;
            if (sidebar.classList.contains('closed')) {
                sidebar.classList.remove('closed');
                sidebar.classList.add('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.remove('open');
                sidebar.classList.add('closed');
                if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
                if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
            }
        }

        if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>

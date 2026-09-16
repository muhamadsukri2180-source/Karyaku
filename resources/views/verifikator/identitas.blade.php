<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Verifikasi Identitas (KTP)</title>
    
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

        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        @include('verifikator.partials.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Verifikasi Identitas (KTP)</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Validasi pendaftaran pendaftar penjual baru di platform.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- SUB-TABS NAVIGATION -->
                <div class="flex border-b border-sky-200 gap-4">
                    <a href="{{ route('verifikator.identitas', ['tab' => 'pending']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                        <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending
                    </a>
                    <a href="{{ route('verifikator.identitas', ['tab' => 'history']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'history' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Verifikasi
                    </a>
                </div>

                <!-- TABEL DATA -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                        <th class="py-4 px-6">Nama Pemohon</th>
                                        <th class="py-4 px-6">NIK</th>
                                        <th class="py-4 px-6">Paket / Bank</th>
                                        <th class="py-4 px-6">Status</th>
                                        <th class="py-4 px-6 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs divide-y divide-slate-100">
                                    @forelse($verifications as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-3.5 px-6">
                                            <p class="font-bold text-slate-800 text-xs">{{ $item->user->name ?? 'User N/A' }}</p>
                                            <p class="text-[11px] text-slate-400 font-medium">@safeEmail($item->user->email ?? '-')</p>
                                        </td>
                                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-600">{{ $item->nik ?? '-' }}</td>
                                        <td class="py-3.5 px-6 text-xs">
                                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded-md font-bold text-[10px]">
                                                {{ $item->membership->name ?? 'Bronze' }}
                                            </span>
                                            <span class="block text-[10px] font-semibold text-slate-400 mt-0.5">
                                                {{ $item->bank_name ?? '-' }} ({{ $item->account_number ?? '-' }})
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-6">
                                            @if($item->status === 'approved')
                                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Disetujui</span>
                                            @elseif($item->status === 'rejected')
                                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                                            @else
                                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 text-center">
                                            <a href="{{ route('verifikator.pendaftaran.show', $item->id_identity_verification) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                                <i class="fa-solid fa-eye"></i> Tinjau Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10 text-slate-400 font-semibold text-xs">
                                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                                            Belum ada data pendaftaran pada tab ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(method_exists($verifications, 'hasPages') && $verifications->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $verifications->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
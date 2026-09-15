<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Detail Tinjauan Laporan</title>
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Tinjauan Laporan Pelanggaran</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Pemeriksaan detail aduan dan eksekusi tindakan disiplin moderator.</p>
                    </div>
                </div>
                <a href="{{ route('verifikator.laporan') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </header>

            <div class="p-6 sm:p-8 space-y-6 max-w-4xl">

                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-slate-900 font-display text-base pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Detail Kasus Pengaduan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px]">Pengirim Laporan (Pelapor)</span>
                            <strong class="text-slate-800 text-sm block mt-0.5">{{ $report->reporter->name ?? '-' }}</strong>
                        </div>
                        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px]">Entitas Dilaporkan</span>
                            <strong class="text-rose-600 text-sm block mt-0.5">{{ $report->product->title ?? $report->reportedUser->name ?? '-' }}</strong>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] mb-1">Rincian Deskripsi Laporan</span>
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed text-slate-700">
                            {{ $report->description ?? $report->reason ?? 'Tidak ada rincian aduan.' }}
                        </div>
                    </div>
                </div>

                @if($report->status === 'pending')
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h4 class="font-extrabold text-slate-900 font-display text-sm">Eksekusi Disiplin Platform</h4>

                    <form id="actionReportForm" action="{{ route('verifikator.laporan.action', $report->id_report ?? $report->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Keputusan Disiplin <span class="text-red-500">*</span></label>
                            <select name="action" required class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold bg-slate-50 focus:outline-none focus:border-sky-400">
                                <option value="warning">⚠️ Kirim Teguran Resmi (Warning Notification)</option>
                                <option value="takedown">🚫 Takedown Produk / Suspend Sementara</option>
                                <option value="dismiss">🟢 Abaikan Laporan (Fitnah / Tidak Terbukti)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Catatan Admin / Moderator</label>
                            <textarea name="note" placeholder="Tuliskan catatan pertimbangan tindakan..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-sky-400 bg-slate-50 min-h-[80px]"></textarea>
                        </div>

                        <button type="button" onclick="confirmActionReport()" class="w-full py-3 bg-skyHover hover:bg-skyDeep text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-gavel"></i> Eksekusi Keputusan Kasus
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-slate-100 text-slate-700 border border-slate-200 rounded-2xl p-4 font-bold text-xs text-center">
                    Kasus Laporan Berstatus: {{ strtoupper($report->status) }}
                </div>
                @endif

            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

        function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);

        function confirmActionReport() {
            Swal.fire({
                title: 'Eksekusi Keputusan?',
                text: "Tindakan disiplin akan diproses dan notifikasi dikirimkan ke pihak terkait.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0EA5E9',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Eksekusi!',
                cancelButtonText: 'Batal'
            }).then((result) => { if (result.isConfirmed) document.getElementById('actionReportForm').submit(); });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
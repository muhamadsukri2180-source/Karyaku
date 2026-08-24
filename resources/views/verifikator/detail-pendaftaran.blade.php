<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Detail Pendaftaran Penjual</title>
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

        <!-- SIDEBAR VERIFIKATOR -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Verifikator Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto mt-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-2">Navigasi Utama</p>

                <a href="{{ route('verifikator.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-chart-pie w-4 text-center group-hover:text-white transition-colors"></i><span>Dashboard</span>
                </a>

                <a href="{{ route('verifikator.identitas') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-id-card-clip w-4 text-center text-white"></i><span>Verifikasi Identitas</span>
                    </div>
                </a>

                <a href="{{ route('verifikator.produk') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Produk</span>
                    </div>
                </a>

                <a href="{{ route('verifikator.pembayaran') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Verifikasi Pembayaran</span>
                    </div>
                </a>

                <a href="{{ route('verifikator.laporan') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan Pelanggaran</span>
                    </div>
                </a>
            </nav>

            <div class="p-4 border-t border-white/15">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Detail Pendaftaran Penjual</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Pemeriksaan identitas, data bank, dan bukti pembayaran pendaftar.</p>
                    </div>
                </div>
                <a href="{{ route('verifikator.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- RINCIAN DATA PEMOHON -->
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-base font-display pb-4 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-sky"></i> Data Profil & Identitas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Nama Lengkap</span>
                            <span class="font-extrabold text-slate-800 text-sm mt-0.5 block">{{ $registration->user->name ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Email</span>
                            <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $registration->user->email ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">No. Telepon / WA</span>
                            <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $registration->user->phone ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">NIK</span>
                            <span class="font-mono font-extrabold text-slate-800 text-sm mt-0.5 block">{{ $registration->nik ?? '-' }}</span>
                        </div>

                        <div class="md:col-span-2 p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Alamat Lengkap</span>
                            <span class="font-semibold text-slate-700 text-xs mt-0.5 block leading-relaxed">{{ $registration->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- RINCIAN BANK & PEMBAYARAN MEMBERSHIP -->
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-base font-display pb-4 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-sky"></i> Data Bank & Pembayaran Membership
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Nama Bank</span>
                            <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $registration->bank_name ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Nama Rekening</span>
                            <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $registration->account_name ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Nomor Rekening</span>
                            <span class="font-mono font-extrabold text-slate-800 text-sm mt-0.5 block">{{ $registration->account_number ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Paket Membership</span>
                            <span class="font-bold text-sky-600 text-sm mt-0.5 block">{{ $registration->membership->name ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Metode Pembayaran</span>
                            <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $registration->payment_method ?? 'Transfer Bank' }}</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 border border-slate-100 rounded-xl">
                            <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wider">Nominal Pembayaran</span>
                            <span class="font-extrabold text-emerald-600 text-sm mt-0.5 block">Rp {{ number_format($registration->payment_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- BUKTI FOTO KTP & RESI PEMBAYARAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Foto KTP -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm font-display mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-id-card text-sky"></i> Foto KTP
                            </h4>
                            <div class="bg-slate-100 border border-slate-200 rounded-xl overflow-hidden min-h-[220px] flex items-center justify-center p-2">
                                @if ($registration->identity_document)
                                    <img src="{{ asset('storage/' . $registration->identity_document) }}" class="max-h-[320px] w-auto object-contain rounded-lg shadow-sm" alt="Foto KTP">
                                @else
                                    <p class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-image-slash mr-1"></i> Tidak ada foto KTP.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm font-display mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-sky"></i> Bukti Pembayaran
                            </h4>
                            <div class="bg-slate-100 border border-slate-200 rounded-xl overflow-hidden min-h-[220px] flex items-center justify-center p-2">
                                @if ($registration->payment_proof)
                                    <img src="{{ asset('storage/' . $registration->payment_proof) }}" class="max-h-[320px] w-auto object-contain rounded-lg shadow-sm" alt="Bukti Pembayaran">
                                @else
                                    <p class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-image-slash mr-1"></i> Tidak ada bukti pembayaran.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- MODUL VERIFIKASI / KEPUTUSAN -->
                @if (($registration->status ?? 'pending') !== 'approved')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Form Approve -->
                        <div class="bg-white border-l-4 border-l-emerald-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
                            <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Setujui Pendaftaran
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mb-4">Ubah role pengguna menjadi Penjual dan aktifkan paket membership.</p>

                            <form id="approveForm" action="{{ route('verifikator.pendaftaran.approve', $registration->id_identity_verification) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Catatan Verifikator (Opsional)</label>
                                    <textarea name="notes" placeholder="Catatan opsional untuk persetujuan..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-sky-400 bg-slate-50 min-h-[90px]"></textarea>
                                </div>

                                <button type="button" onclick="confirmApprove()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-check"></i> ✓ Setujui Pendaftaran
                                </button>
                            </form>
                        </div>

                        <!-- Form Reject -->
                        <div class="bg-white border-l-4 border-l-red-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
                            <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-circle-xmark text-red-500"></i> Tolak Pendaftaran
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mb-4">Kirimkan catatan alasan penolakan langsung ke notifikasi user.</p>

                            <form id="rejectForm" action="{{ route('verifikator.pendaftaran.reject', $registration->id_identity_verification) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea id="rejectionNotes" name="notes" required placeholder="Masukkan alasan penolakan (misal: Foto KTP buram, Resi transfer palsu)..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-red-400 bg-slate-50 min-h-[90px]"></textarea>
                                </div>

                                <button type="button" onclick="confirmReject()" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-xmark"></i> ✕ Tolak Pendaftaran
                                </button>
                            </form>
                        </div>

                    </div>
                @else
                    <!-- Status Disetujui -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-emerald-900 text-base font-display">✓ Pendaftaran Sudah Disetujui</h3>
                            <p class="text-xs text-emerald-700 font-medium mt-0.5">Pendaftaran ini sudah disetujui dan akun pengguna sudah aktif menjadi penjual.</p>
                        </div>
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
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        function confirmApprove() {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                text: "Pengguna akan diubah hak aksesnya menjadi Penjual.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm').submit();
                }
            });
        }

        function confirmReject() {
            const notes = document.getElementById('rejectionNotes').value;
            if (!notes.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alasan Wajib Diisi',
                    text: 'Silakan isi alasan penolakan sebelum menolak pendaftaran ini.',
                    confirmButtonColor: '#0EA5E9'
                });
                return;
            }

            Swal.fire({
                title: 'Tolak Pendaftaran?',
                text: "Notifikasi penolakan beserta alasan akan dikirimkan ke pengguna.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectForm').submit();
                }
            });
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
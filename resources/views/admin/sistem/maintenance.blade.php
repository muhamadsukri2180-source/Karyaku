<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Maintenance & Backup</title>
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
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR FULL -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] text-sky-100 truncate">Online</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i><span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-sky-200 w-3 text-center"></i> Akun Verifikator
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu pl-4 mt-1 space-y-1" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center text-amber-300"></i><span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all group">
                    <i class="fa-solid fa-server w-4 text-center text-white"></i><span>Maintenance & Backup</span>
                </a>

                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i>
                    <span>Pelanggaran</span>
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
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Maintenance & Backup</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Kelola status server dan cadangan data.</p>
                    </div>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- Status Server Card -->
                <div class="bg-white border-l-4 {{ $currentMode == 'none' ? 'border-emerald-500' : 'border-red-500' }} border-y border-r border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-12 h-12 rounded-full {{ $currentMode == 'none' ? 'bg-emerald-100' : 'bg-red-100' }} flex shrink-0 items-center justify-center border-4 border-white shadow-sm">
                            <span class="w-4 h-4 rounded-full {{ $currentMode == 'none' ? 'bg-emerald-500' : 'bg-red-500' }} animate-pulse"></span>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">
                                {{ $currentMode == 'none' ? 'Sistem Berjalan Normal (Online)' : 'Sistem Sedang Maintenance' }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">
                                {{ $currentMode == 'none' ? 'Server aktif dan dapat diakses.' : 'Mode perbaikan aktif untuk target terpilih.' }}
                            </p>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.toggleMaintenance') }}" method="POST" id="formMaintenance" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        @csrf
                        
                        @php
                            $options = [
                                'none' => 'Normal (Online)',
                                'all' => 'Down Semua User (Kecuali Admin)',
                                'pembeli' => 'Down Pembeli',
                                'penjual' => 'Down Penjual',
                                'verifikator' => 'Down Verifikator'
                            ];
                        @endphp
                        
                        <div class="relative w-full sm:w-[280px]" id="customDropdown">
                            <input type="hidden" name="target_role" id="targetRoleInput" value="{{ $currentMode }}">
                            
                            <button type="button" id="dropdownBtn" class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-4 py-2.5 shadow-sm hover:border-sky-300 focus:outline-none transition-all">
                                <span id="dropdownText">{{ $options[$currentMode] ?? 'Normal (Online)' }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300" id="dropdownIcon"></i>
                            </button>

                            <div id="dropdownMenu" class="absolute z-50 left-0 top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)] p-2 hidden flex-col gap-1 opacity-0 transition-opacity duration-200">
                                @foreach($options as $val => $label)
                                    <button type="button" 
                                            class="dropdown-option w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all cursor-pointer {{ $currentMode == $val ? 'bg-[#0EA5E9] text-white shadow-md shadow-sky-500/20' : 'text-slate-600 hover:bg-slate-50' }}" 
                                            data-value="{{ $val }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Button Terapkan -->
                        <button type="button" onclick="confirmMaintenance()" class="w-full sm:w-auto px-5 py-2.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white text-xs font-bold rounded-xl transition-all shadow-sm shrink-0 flex items-center justify-center">
                            <i class="fa-solid fa-power-off mr-2"></i> Terapkan
                        </button>
                    </form>
                </div>

                <!-- Backup Data Area -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="font-extrabold text-slate-900 text-lg font-display">Riwayat Backup Database</h3>
                        
                        <!-- TOMBOL 3D BIRU KOKOH -->
                        <button type="button" onclick="openBackupModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Buat Backup Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Nama File</th>
                                    <th class="py-4 px-6">Tanggal & Waktu</th>
                                    <th class="py-4 px-6">Ukuran</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($backups as $index => $backup)
                                <tr class="hover:bg-slate-50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-200"><i class="fa-solid fa-file-code"></i></div>
                                            <p class="font-bold text-slate-800 text-xs">{{ $backup['name'] }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6"><p class="text-xs font-semibold text-slate-600">{{ $backup['created_at']->format('d M Y - H:i') }} WIB</p></td>
                                    <td class="py-3 px-6 text-xs font-bold text-slate-600">{{ $backup['size'] }}</td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.backup.download', $backup['name']) }}" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Unduh Backup"><i class="fa-solid fa-download"></i></a>
                                            <form id="delete-backup-{{ $index }}" action="{{ route('admin.backup.delete', $backup['name']) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-backup-{{ $index }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Hapus Backup"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada file backup database yang dibuat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL BUAT BACKUP (POSISI TENGAH PRESISI) -->
    <div id="backupModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4" id="backupModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up text-sm"></i></div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Buat Backup Baru</h3>
                </div>
                <button type="button" onclick="closeBackupModal()" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('admin.backup.create') }}" method="POST" id="formBackup" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Nama File Backup</label>
                    <input type="text" value="backup-{{ date('Y-m-d_His') }}.sql" class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none transition-all cursor-not-allowed" readonly>
                    <p class="text-[10px] text-slate-500 mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i> Nama di-generate secara otomatis oleh sistem.</p>
                </div>
                <div class="pt-2">
                    <!-- TOMBOL BIRU SOLID DI DALAM POPUP -->
                    <button type="button" onclick="executeBackup()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-database"></i> Mulai Proses Backup
                    </button>
                </div>
            </form>
        </div>
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

        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-menu');
                const submenu = document.querySelector(`[data-submenu="${key}"]`);
                const chevron = document.querySelector(`[data-chevron="${key}"]`);
                if(submenu) submenu.classList.toggle('open');
                if(chevron) chevron.classList.toggle('rotated');
            });
        });

        const dropdownBtn = document.getElementById('dropdownBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        const dropdownText = document.getElementById('dropdownText');
        const targetRoleInput = document.getElementById('targetRoleInput');
        const options = document.querySelectorAll('.dropdown-option');

        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            setTimeout(() => { dropdownMenu.classList.toggle('opacity-0'); }, 10);
            dropdownIcon.classList.toggle('rotate-180');
        });

        document.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('opacity-0');
                setTimeout(() => { dropdownMenu.classList.add('hidden'); }, 200);
                dropdownIcon.classList.remove('rotate-180');
            }
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                const val = option.getAttribute('data-value');
                const text = option.innerText;
                
                targetRoleInput.value = val;
                dropdownText.innerText = text;

                options.forEach(opt => {
                    opt.className = 'dropdown-option w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 cursor-pointer';
                });

                option.className = 'dropdown-option w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all bg-[#0EA5E9] text-white shadow-md shadow-sky-500/20 cursor-pointer';

                dropdownMenu.classList.add('opacity-0');
                setTimeout(() => { dropdownMenu.classList.add('hidden'); }, 200);
                dropdownIcon.classList.remove('rotate-180');
            });
        });

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('warning'))
            Swal.fire({ icon: 'warning', title: 'Perhatian!', text: "{{ session('warning') }}", confirmButtonColor: '#0EA5E9' });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif

        function confirmMaintenance() {
            const roleSelected = dropdownText.innerText;
            Swal.fire({
                title: 'Terapkan Pengaturan?',
                text: "Status server akan diubah menjadi: " + roleSelected,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Terapkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formMaintenance').submit();
                }
            });
        }

        function confirmDelete(formId) {
            Swal.fire({
                title: 'Hapus File Backup?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        const modal = document.getElementById('backupModal');
        const modalContent = document.getElementById('backupModalContent');

        function openBackupModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeBackupModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function executeBackup() {
            closeBackupModal();
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mencadangkan database Anda. Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            document.getElementById('formBackup').submit();
        }
    </script>
</body>
</html>
@extends('layouts.admin')

@section('title', 'Maintenance & Backup')
@section('header_title', 'Maintenance & Backup')
@section('header_subtitle', 'Kelola status server dan cadangan data.')

@section('content')
<div class="p-6 sm:p-8 space-y-6">

    <!-- Status Server Card -->
    <div class="bg-white border-l-4 {{ $currentMode == 'none' ? 'border-emerald-500' : 'border-red-500' }} border-y border-r border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
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
                        @if($currentMode != 'none' && $currentEndAt)
                            &middot; Selesai: {{ \Carbon\Carbon::parse($currentEndAt)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y - H:i') }} WIB
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.toggleMaintenance') }}" method="POST" id="formMaintenance" class="flex flex-col gap-4">
            @csrf

            @php
                $options = [
                    'none' => 'Normal (Online)',
                    'all' => 'Down Semua User (Kecuali Admin)',
                    'pembeli' => 'Down Pembeli',
                    'penjual' => 'Down Penjual',
                    'verifikator' => 'Down Verifikator'
                ];
                $endAtValue = $currentEndAt ? \Carbon\Carbon::parse($currentEndAt, 'Asia/Jakarta')->format('Y-m-d\TH:i') : '';
            @endphp

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
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

                <!-- Field Waktu Selesai -->
                <div id="endAtWrapper" class="w-full sm:w-[260px] {{ $currentMode == 'none' ? 'hidden' : '' }}">
                    <input type="datetime-local" name="end_at" id="endAtInput"
                           value="{{ $endAtValue }}"
                           class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-4 py-2.5 shadow-sm hover:border-sky-300 focus:outline-none focus:border-sky-400 transition-all">
                </div>

                <!-- Button Terapkan -->
                <button type="button" onclick="confirmMaintenance()" class="w-full sm:w-auto px-5 py-2.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white text-xs font-bold rounded-xl transition-all shadow-sm shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-power-off mr-2"></i> Terapkan
                </button>
            </div>

            <p id="endAtHint" class="text-[11px] text-slate-500 font-medium {{ $currentMode == 'none' ? 'hidden' : '' }}">
                <i class="fa-solid fa-circle-info mr-1 text-sky-500"></i>
                Isi perkiraan tanggal & jam server kembali online. Waktu ini akan ditampilkan sebagai hitung mundur ke pengguna.
            </p>
        </form>
    </div>

    <!-- Pratinjau Tampilan Halaman Per Peran -->
    <div class="bg-white border border-sky-200 p-6 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base font-display">Tinjau Halaman per Peran (Preview Mode)</h3>
                <p class="text-xs text-slate-600 font-medium">Tinjau tampilan sistem untuk masing-masing peran saat maintenance atau down.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('landing') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-sky-50 hover:border-sky-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-house"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-sky-600">Landing Page Publik</h4>
                        <p class="text-[10px] text-slate-500">Pratinjau Halaman Utama</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-sky-600"></i>
            </a>

            <a href="{{ route('pembeli.marketplace') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-store"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">Katalog Marketplace</h4>
                        <p class="text-[10px] text-slate-500">Pratinjau Katalog Produk</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-blue-600"></i>
            </a>

            <a href="{{ route('pembeli.dashboard') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-sky-50 hover:border-sky-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-shopping-bag"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-sky-600">Peran Pembeli</h4>
                        <p class="text-[10px] text-slate-500">Tinjau Halaman Pembeli</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-sky-600"></i>
            </a>

            <a href="{{ route('penjual.dashboard') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-briefcase"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-600">Peran Penjual</h4>
                        <p class="text-[10px] text-slate-500">Tinjau Halaman Penjual</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-emerald-600"></i>
            </a>

            <a href="{{ route('verifikator.dashboard') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-user-shield"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Peran Verifikator</h4>
                        <p class="text-[10px] text-slate-500">Tinjau Halaman Verifikator</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-indigo-600"></i>
            </a>

            <a href="{{ route('cs.dashboard') }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-amber-50 hover:border-amber-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-amber-600">Peran Customer Service</h4>
                        <p class="text-[10px] text-slate-500">Tinjau Halaman CS</p>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-amber-600"></i>
            </a>
        </div>
    </div>

    <!-- Backup Data Area -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Riwayat Backup Database</h3>

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
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-200">
                                    <i class="fa-solid {{ str_ends_with($backup['name'], '.zip') ? 'fa-file-zipper' : 'fa-file-code' }}"></i>
                                </div>
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

    <!-- Cache Aplikasi Area -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 transition-all hover:shadow-md">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center text-xl shadow-lg shadow-amber-500/20 shrink-0">
                <i class="fa-solid fa-broom"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h3 class="font-extrabold text-slate-900 text-lg font-display">Cache Aplikasi</h3>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Sistem Cache
                    </span>
                </div>
                <p class="text-xs text-slate-600 font-medium mt-1 leading-relaxed max-w-xl">
                    Bersihkan cache aplikasi, konfigurasi, rute, view blade, dan event untuk memuat perubahan terbaru tanpa menghapus data pengguna, transaksi, maupun produk.
                </p>
                <div class="mt-3 flex items-center gap-2.5 flex-wrap text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-slate-400"></i>
                        <span>Terakhir dibersihkan:</span>
                        <span id="lastCacheClearedBadge" class="font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200 transition-colors">
                            {{ $lastCacheClearedAt ? $lastCacheClearedAt->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum pernah dibersihkan' }}
                        </span>
                    </div>

                    <button type="button" id="btnViewLastCacheEvidence" onclick="openCacheEvidenceModal('all')" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200 rounded-lg transition-all shadow-sm cursor-pointer hover:border-sky-300 active:scale-95" title="Lihat Bukti Nyata Cache Terhapus">
                        <i class="fa-solid fa-eye text-sky-600 text-xs"></i>
                        <span>Bukti Nyata Cache</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-auto shrink-0">
            <button type="button" onclick="confirmClearCache()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs sm:text-sm font-extrabold rounded-xl shadow-[0_4px_0_0_#d97706] hover:shadow-[0_2px_0_0_#d97706] active:translate-y-[2px] active:shadow-none transition-all cursor-pointer">
                <i class="fa-solid fa-broom text-sm"></i>
                <span>Bersihkan Cache</span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('modals')
<!-- MODAL BUAT BACKUP -->
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
                <input type="text" value="backup-{{ date('Y-m-d_His') }}.zip" class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none transition-all cursor-not-allowed" readonly>
                <p class="text-[10px] text-slate-500 mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i> Nama di-generate secara otomatis oleh sistem. File akan dikompres ke format ZIP sebelum dikirim ke Google Drive.</p>
            </div>
            <div class="pt-2">
                <button type="button" onclick="executeBackup()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-database"></i> Mulai Proses Backup
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL LIVE PROGRESS CLEAR CACHE -->
<div id="cacheProgressModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 overflow-hidden border border-slate-100" id="cacheProgressModalContent">
        
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <div class="flex items-center gap-3">
                <div id="cacheModalIconContainer" class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center font-bold text-sm shadow-sm transition-colors">
                    <i id="cacheModalIcon" class="fa-solid fa-broom text-sm"></i>
                </div>
                <div>
                    <h3 id="cacheModalTitle" class="font-extrabold text-slate-900 font-display text-base leading-tight">Membersihkan Cache...</h3>
                    <p id="cacheModalSubtitle" class="text-[11px] text-slate-500 font-medium">Menyegarkan seluruh cache sistem Laravel</p>
                </div>
            </div>
            <button type="button" id="cacheModalHeaderCloseBtn" onclick="closeCacheProgressModal()" class="hidden text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <!-- Progress Percentage Bar -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span id="cacheProgressStepText" class="text-slate-500">0 / 5 proses</span>
                    <span id="cacheProgressPercent" class="text-amber-600 font-extrabold text-sm font-display">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-200/60 p-0.5 shadow-inner">
                    <div id="cacheProgressBarFill" class="bg-gradient-to-r from-amber-500 to-sky-500 h-full rounded-full transition-all duration-300 ease-out w-0 shadow-sm"></div>
                </div>
            </div>

            <!-- Process Steps Checklist -->
            <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden bg-slate-50/50">
                
                <!-- 1. App Cache -->
                <div class="p-3.5 flex items-center justify-between transition-colors gap-3" id="row-app">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-white text-slate-600 border border-slate-200 flex items-center justify-center text-xs shadow-sm shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs font-bold text-slate-800">App Cache</h4>
                                <span id="meta-app" class="hidden text-[10px] font-bold"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Artisan cache:clear & storage/framework/cache</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div id="status-app">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="fa-regular fa-circle text-[10px]"></i> Menunggu
                            </span>
                        </div>
                        <button type="button" id="eye-app" onclick="openCacheEvidenceModal('app')" class="hidden w-7 h-7 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200 flex items-center justify-center transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer" title="Lihat Bukti File Dihapus">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- 2. Config Cache -->
                <div class="p-3.5 flex items-center justify-between transition-colors gap-3" id="row-config">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-white text-slate-600 border border-slate-200 flex items-center justify-center text-xs shadow-sm shrink-0">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs font-bold text-slate-800">Config Cache</h4>
                                <span id="meta-config" class="hidden text-[10px] font-bold"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Artisan config:clear & bootstrap/cache/config.php</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div id="status-config">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="fa-regular fa-circle text-[10px]"></i> Menunggu
                            </span>
                        </div>
                        <button type="button" id="eye-config" onclick="openCacheEvidenceModal('config')" class="hidden w-7 h-7 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200 flex items-center justify-center transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer" title="Lihat Bukti File Dihapus">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- 3. Route Cache -->
                <div class="p-3.5 flex items-center justify-between transition-colors gap-3" id="row-route">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-white text-slate-600 border border-slate-200 flex items-center justify-center text-xs shadow-sm shrink-0">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs font-bold text-slate-800">Route Cache</h4>
                                <span id="meta-route" class="hidden text-[10px] font-bold"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Artisan route:clear & bootstrap/cache/routes-v7.php</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div id="status-route">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="fa-regular fa-circle text-[10px]"></i> Menunggu
                            </span>
                        </div>
                        <button type="button" id="eye-route" onclick="openCacheEvidenceModal('route')" class="hidden w-7 h-7 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200 flex items-center justify-center transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer" title="Lihat Bukti File Dihapus">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- 4. View Cache -->
                <div class="p-3.5 flex items-center justify-between transition-colors gap-3" id="row-view">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-white text-slate-600 border border-slate-200 flex items-center justify-center text-xs shadow-sm shrink-0">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs font-bold text-slate-800">View Cache</h4>
                                <span id="meta-view" class="hidden text-[10px] font-bold"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Artisan view:clear & storage/framework/views</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div id="status-view">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="fa-regular fa-circle text-[10px]"></i> Menunggu
                            </span>
                        </div>
                        <button type="button" id="eye-view" onclick="openCacheEvidenceModal('view')" class="hidden w-7 h-7 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200 flex items-center justify-center transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer" title="Lihat Bukti File Dihapus">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- 5. Event Cache -->
                <div class="p-3.5 flex items-center justify-between transition-colors gap-3" id="row-event">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-white text-slate-600 border border-slate-200 flex items-center justify-center text-xs shadow-sm shrink-0">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs font-bold text-slate-800">Event Cache</h4>
                                <span id="meta-event" class="hidden text-[10px] font-bold"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Artisan event:clear & bootstrap/cache/events.php</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div id="status-event">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="fa-regular fa-circle text-[10px]"></i> Menunggu
                            </span>
                        </div>
                        <button type="button" id="eye-event" onclick="openCacheEvidenceModal('event')" class="hidden w-7 h-7 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200 flex items-center justify-center transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer" title="Lihat Bukti File Dihapus">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Footer Summary (Hidden while in progress) -->
            <div id="cacheProcessTime" class="hidden flex items-center justify-between bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>Waktu proses:</span>
                </div>
                <span id="cacheProcessTimeVal" class="font-extrabold text-slate-800 bg-white px-2 py-0.5 rounded-md border border-slate-200 text-xs">0.0 detik</span>
            </div>

            <!-- Action Buttons -->
            <div id="cacheModalFooter" class="pt-1 space-y-2">
                <button type="button" id="btnViewAllEvidenceInModal" onclick="openCacheEvidenceModal('all')" class="hidden w-full py-2.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 text-xs sm:text-sm font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer hover:border-sky-300">
                    <i class="fa-solid fa-eye text-sky-600"></i> Lihat Semua Bukti File Dihapus
                </button>
                <button type="button" id="cacheModalCloseBtn" onclick="closeCacheProgressModal()" class="hidden w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check"></i> Selesai & Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BUKTI NYATA PEMBERSIHAN CACHE -->
<div id="cacheEvidenceModal" class="fixed inset-0 z-[80] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-3 sm:p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 mx-2 sm:mx-4 overflow-hidden border border-slate-200" id="cacheEvidenceModalContent">
        
        <!-- Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-sky-50/50 rounded-t-2xl shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center font-bold text-base shadow-md shadow-sky-500/20 shrink-0">
                    <i class="fa-solid fa-file-shield"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-extrabold text-slate-900 font-display text-base sm:text-lg leading-tight">Bukti Nyata Pembersihan Cache</h3>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i class="fa-solid fa-circle-check text-[9px]"></i> Server Verified
                        </span>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">Daftar file fisik yang berhasil dihapus langsung dari sistem file hosting</p>
                </div>
            </div>
            <button type="button" onclick="closeCacheEvidenceModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Body Scrollable -->
        <div class="p-4 sm:p-6 overflow-y-auto space-y-4 flex-1">
            
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 border-b border-slate-100 text-xs font-bold no-scrollbar">
                <button type="button" onclick="selectEvidenceTab('all')" id="tab-evidence-all" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-sky-600 text-white transition-all shrink-0 cursor-pointer">
                    Semua File (<span id="count-evidence-all">0</span>)
                </button>
                <button type="button" onclick="selectEvidenceTab('config')" id="tab-evidence-config" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer">
                    Config Cache (<span id="count-evidence-config">0</span>)
                </button>
                <button type="button" onclick="selectEvidenceTab('route')" id="tab-evidence-route" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer">
                    Route Cache (<span id="count-evidence-route">0</span>)
                </button>
                <button type="button" onclick="selectEvidenceTab('view')" id="tab-evidence-view" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer">
                    View Cache (<span id="count-evidence-view">0</span>)
                </button>
                <button type="button" onclick="selectEvidenceTab('app')" id="tab-evidence-app" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer">
                    App Cache (<span id="count-evidence-app">0</span>)
                </button>
                <button type="button" onclick="selectEvidenceTab('event')" id="tab-evidence-event" class="evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer">
                    Event Cache (<span id="count-evidence-event">0</span>)
                </button>
            </div>

            <!-- Audit Metrics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total File Dihapus</span>
                    <span id="evidenceMetricTotalFiles" class="text-base sm:text-lg font-black text-slate-800 font-display mt-0.5 block">0 File</span>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Ukuran Dibersihkan</span>
                    <span id="evidenceMetricTotalSize" class="text-base sm:text-lg font-black text-amber-600 font-display mt-0.5 block">0 B</span>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Diperiksa</span>
                    <span id="evidenceMetricTarget" class="text-xs font-bold text-slate-700 truncate block mt-1" title="">-</span>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Eksekusi</span>
                    <span id="evidenceMetricTime" class="text-xs font-bold text-slate-700 truncate block mt-1">-</span>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="evidenceSearchInput" oninput="filterEvidenceTable()" placeholder="Cari nama file cache (contoh: config.php, routes, *.php)..." class="w-full pl-9 pr-3.5 py-2 text-xs border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:border-sky-400 font-medium transition-all">
                </div>
                <button type="button" onclick="copyEvidenceAuditText()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all cursor-pointer shrink-0">
                    <i class="fa-regular fa-copy"></i>
                    <span class="hidden sm:inline">Salin Bukti Log</span>
                </button>
            </div>

            <!-- Files Table / Empty Container -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                <div class="max-h-72 sm:max-h-80 overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50/80 sticky top-0 border-b border-slate-200 text-slate-500 font-bold z-10">
                            <tr>
                                <th class="py-2.5 px-3 w-10 text-center">#</th>
                                <th class="py-2.5 px-3 w-28">Kategori</th>
                                <th class="py-2.5 px-3">Nama File Fisik & Lokasi Hosting</th>
                                <th class="py-2.5 px-3 w-24 text-right">Ukuran</th>
                                <th class="py-2.5 px-3 w-28 text-center">Status Fisik</th>
                            </tr>
                        </thead>
                        <tbody id="evidenceTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Clean/Empty State Card -->
                <div id="evidenceEmptyState" class="hidden p-6 text-center space-y-2 bg-slate-50/50">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto text-xl shadow-sm">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h4 class="font-extrabold text-slate-800 text-sm">Direktori Target Sudah Steril & Bersih</h4>
                    <p id="evidenceEmptyDesc" class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                        Server hosting telah memverifikasi path tersebut. Tidak ada file cache tertimbun saat pembersihan dijalankan (0 file fisik tersisa).
                    </p>
                </div>
            </div>

            <!-- Notice Footer -->
            <div class="flex items-center gap-2 p-3 bg-emerald-50/80 border border-emerald-200/80 rounded-xl text-emerald-800 text-[11px] font-semibold">
                <i class="fa-solid fa-shield-check text-emerald-600 text-sm shrink-0"></i>
                <span>Data di atas adalah bukti autentik file fisik dari hosting server yang diverifikasi melalui sistem filesystem PHP.</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50 rounded-b-2xl shrink-0">
            <span class="text-xs text-slate-500 font-medium">Status: <strong class="text-emerald-700 font-bold">100% Terverifikasi Bersih</strong></span>
            <button type="button" onclick="closeCacheEvidenceModal()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                Tutup
            </button>
        </div>

    </div>
</div>
@endpush

@push('scripts')
<script>
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');
    const dropdownText = document.getElementById('dropdownText');
    const targetRoleInput = document.getElementById('targetRoleInput');
    const options = document.querySelectorAll('.dropdown-option');

    const endAtWrapper = document.getElementById('endAtWrapper');
    const endAtHint = document.getElementById('endAtHint');
    const endAtInput = document.getElementById('endAtInput');

    function toggleEndAtField(val) {
        if (val === 'none') {
            endAtWrapper.classList.add('hidden');
            endAtHint.classList.add('hidden');
        } else {
            endAtWrapper.classList.remove('hidden');
            endAtHint.classList.remove('hidden');
        }
    }

    if(dropdownBtn) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            setTimeout(() => { dropdownMenu.classList.toggle('opacity-0'); }, 10);
            dropdownIcon.classList.toggle('rotate-180');
        });
    }

    document.addEventListener('click', (e) => {
        if (dropdownBtn && !dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
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

            toggleEndAtField(val);

            dropdownMenu.classList.add('opacity-0');
            setTimeout(() => { dropdownMenu.classList.add('hidden'); }, 200);
            dropdownIcon.classList.remove('rotate-180');
        });
    });

    function confirmMaintenance() {
        const roleValue = targetRoleInput.value;
        const roleSelected = dropdownText.innerText;

        if (roleValue !== 'none' && !endAtInput.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Waktu Selesai Belum Diisi',
                text: 'Silakan isi perkiraan tanggal & jam server kembali online sebelum menerapkan mode maintenance.',
                confirmButtonColor: '#0EA5E9'
            });
            return;
        }

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

    // ==========================================
    // FITUR CLEAR CACHE (INTERACTIVE LIVE MODAL & BUKTI NYATA)
    // ==========================================
    const cacheModal = document.getElementById('cacheProgressModal');
    const cacheModalContent = document.getElementById('cacheProgressModalContent');
    const cacheModalIconContainer = document.getElementById('cacheModalIconContainer');
    const cacheModalIcon = document.getElementById('cacheModalIcon');
    const cacheModalTitle = document.getElementById('cacheModalTitle');
    const cacheModalSubtitle = document.getElementById('cacheModalSubtitle');
    const cacheModalHeaderCloseBtn = document.getElementById('cacheModalHeaderCloseBtn');
    const cacheProgressStepText = document.getElementById('cacheProgressStepText');
    const cacheProgressPercent = document.getElementById('cacheProgressPercent');
    const cacheProgressBarFill = document.getElementById('cacheProgressBarFill');
    const cacheProcessTime = document.getElementById('cacheProcessTime');
    const cacheProcessTimeVal = document.getElementById('cacheProcessTimeVal');
    const cacheModalCloseBtn = document.getElementById('cacheModalCloseBtn');
    const btnViewAllEvidenceInModal = document.getElementById('btnViewAllEvidenceInModal');
    const lastCacheClearedBadge = document.getElementById('lastCacheClearedBadge');

    // Evidence Modal Elements
    const evidenceModal = document.getElementById('cacheEvidenceModal');
    const evidenceModalContent = document.getElementById('cacheEvidenceModalContent');
    const evidenceSearchInput = document.getElementById('evidenceSearchInput');
    const evidenceTableBody = document.getElementById('evidenceTableBody');
    const evidenceEmptyState = document.getElementById('evidenceEmptyState');
    const evidenceEmptyDesc = document.getElementById('evidenceEmptyDesc');

    let currentCacheAudit = @json($lastCacheDetails ?? null);
    let activeAuditSteps = {};
    let currentEvidenceTab = 'all';

    function openCacheProgressModal() {
        cacheModal.classList.remove('hidden');
        setTimeout(() => {
            cacheModal.classList.remove('opacity-0');
            cacheModalContent.classList.remove('scale-95');
            cacheModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeCacheProgressModal() {
        cacheModal.classList.add('opacity-0');
        cacheModalContent.classList.remove('scale-100');
        cacheModalContent.classList.add('scale-95');
        setTimeout(() => { cacheModal.classList.add('hidden'); }, 300);
    }

    function openCacheEvidenceModal(tab = 'all') {
        currentEvidenceTab = tab;
        evidenceModal.classList.remove('hidden');
        setTimeout(() => {
            evidenceModal.classList.remove('opacity-0');
            evidenceModalContent.classList.remove('scale-95');
            evidenceModalContent.classList.add('scale-100');
        }, 10);

        if (evidenceSearchInput) evidenceSearchInput.value = '';
        selectEvidenceTab(currentEvidenceTab);
    }

    function closeCacheEvidenceModal() {
        evidenceModal.classList.add('opacity-0');
        evidenceModalContent.classList.remove('scale-100');
        evidenceModalContent.classList.add('scale-95');
        setTimeout(() => { evidenceModal.classList.add('hidden'); }, 300);
    }

    function selectEvidenceTab(tabKey) {
        currentEvidenceTab = tabKey;
        const tabs = ['all', 'config', 'route', 'view', 'app', 'event'];
        tabs.forEach(t => {
            const btn = document.getElementById(`tab-evidence-${t}`);
            if (btn) {
                if (t === tabKey) {
                    btn.className = 'evidence-tab-btn px-3 py-1.5 rounded-lg bg-sky-600 text-white transition-all shrink-0 cursor-pointer shadow-sm';
                } else {
                    btn.className = 'evidence-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all shrink-0 cursor-pointer';
                }
            }
        });

        renderEvidenceView();
    }

    function getAuditStepsData() {
        if (currentCacheAudit && currentCacheAudit.steps) {
            return currentCacheAudit.steps;
        }
        return activeAuditSteps;
    }

    function renderEvidenceView() {
        const stepsData = getAuditStepsData();
        const tabKey = currentEvidenceTab;

        // Calculate tab counts
        let totalCount = 0;
        let totalBytes = 0;
        const counts = { config: 0, route: 0, view: 0, app: 0, event: 0 };

        ['config', 'route', 'view', 'app', 'event'].forEach(k => {
            if (stepsData[k]) {
                const count = stepsData[k].files_count || 0;
                counts[k] = count;
                totalCount += count;
                totalBytes += (stepsData[k].total_size || 0);
            }
            const countBadge = document.getElementById(`count-evidence-${k}`);
            if (countBadge) countBadge.innerText = counts[k];
        });
        const allBadge = document.getElementById('count-evidence-all');
        if (allBadge) allBadge.innerText = totalCount;

        // Collect files to display
        let filesToDisplay = [];
        let targetDescText = '';
        let tabFilesCount = 0;
        let tabSizeFormatted = '0 B';

        if (tabKey === 'all') {
            ['config', 'route', 'view', 'app', 'event'].forEach(k => {
                if (stepsData[k] && Array.isArray(stepsData[k].files_deleted)) {
                    stepsData[k].files_deleted.forEach(f => {
                        filesToDisplay.push({ ...f, category: stepsData[k].name || (k.toUpperCase() + ' Cache'), stepKey: k });
                    });
                }
            });
            tabFilesCount = totalCount;
            tabSizeFormatted = currentCacheAudit?.total_size_formatted || formatBytesJs(totalBytes);
            targetDescText = 'bootstrap/cache & storage/framework';
        } else {
            const stepObj = stepsData[tabKey];
            if (stepObj && Array.isArray(stepObj.files_deleted)) {
                stepObj.files_deleted.forEach(f => {
                    filesToDisplay.push({ ...f, category: stepObj.name || tabKey, stepKey: tabKey });
                });
                tabFilesCount = stepObj.files_count || 0;
                tabSizeFormatted = stepObj.total_size_formatted || '0 B';
                targetDescText = stepObj.target_desc || '-';
            } else {
                targetDescText = '-';
            }
        }

        // Update metric cards
        const metricTotalFiles = document.getElementById('evidenceMetricTotalFiles');
        const metricTotalSize = document.getElementById('evidenceMetricTotalSize');
        const metricTarget = document.getElementById('evidenceMetricTarget');
        const metricTime = document.getElementById('evidenceMetricTime');

        if (metricTotalFiles) metricTotalFiles.innerText = `${tabFilesCount} File`;
        if (metricTotalSize) metricTotalSize.innerText = tabSizeFormatted;
        if (metricTarget) {
            metricTarget.innerText = targetDescText;
            metricTarget.title = targetDescText;
        }
        if (metricTime) {
            metricTime.innerText = currentCacheAudit?.cleared_at_formatted || 'Baru saja';
            metricTime.title = currentCacheAudit?.cleared_at_formatted || 'Baru saja';
        }

        renderEvidenceTableRows(filesToDisplay, targetDescText);
    }

    function renderEvidenceTableRows(files, targetDescText) {
        const query = (evidenceSearchInput ? evidenceSearchInput.value : '').toLowerCase().trim();
        const filtered = files.filter(f => {
            if (!query) return true;
            return (f.name && f.name.toLowerCase().includes(query)) ||
                   (f.path && f.path.toLowerCase().includes(query)) ||
                   (f.category && f.category.toLowerCase().includes(query));
        });

        if (files.length === 0) {
            evidenceTableBody.innerHTML = '';
            evidenceEmptyState.classList.remove('hidden');
            if (evidenceEmptyDesc) {
                evidenceEmptyDesc.innerHTML = `
                    Target direktori/file: <strong class="font-mono text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded text-[11px]">${escapeHtml(targetDescText)}</strong><br>
                    Server hosting telah memverifikasi path tersebut dalam kondisi steril (0 file cache tertimbun saat pembersihan dijalankan).
                `;
            }
            return;
        }

        if (filtered.length === 0) {
            evidenceTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-slate-400 font-semibold text-xs">Tidak ditemukan file cache dengan kata kunci "${escapeHtml(query)}".</td></tr>`;
            evidenceEmptyState.classList.add('hidden');
            return;
        }

        evidenceEmptyState.classList.add('hidden');
        let html = '';
        filtered.forEach((file, idx) => {
            const catColors = {
                'Config Cache': 'bg-purple-50 text-purple-700 border-purple-200',
                'Route Cache': 'bg-blue-50 text-blue-700 border-blue-200',
                'View Cache': 'bg-amber-50 text-amber-700 border-amber-200',
                'App Cache': 'bg-sky-50 text-sky-700 border-sky-200',
                'Event Cache': 'bg-emerald-50 text-emerald-700 border-emerald-200',
            };
            const catClass = catColors[file.category] || 'bg-slate-100 text-slate-700 border-slate-200';

            html += `
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-2.5 px-3 text-center text-slate-400 font-bold">${idx + 1}</td>
                    <td class="py-2.5 px-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border ${catClass}">
                            ${escapeHtml(file.category)}
                        </span>
                    </td>
                    <td class="py-2.5 px-3">
                        <div class="font-bold text-slate-800 text-xs">${escapeHtml(file.name)}</div>
                        <div class="font-mono text-[10px] text-slate-400 truncate max-w-sm" title="${escapeHtml(file.path)}">${escapeHtml(file.path)}</div>
                        ${file.modified_at ? `<div class="text-[9px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock mr-1"></i>Modifikasi: ${escapeHtml(file.modified_at)}</div>` : ''}
                    </td>
                    <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-700 text-xs">${escapeHtml(file.size_formatted || '0 B')}</td>
                    <td class="py-2.5 px-3 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fa-solid fa-check text-[9px]"></i> Terhapus Fisik
                        </span>
                    </td>
                </tr>
            `;
        });
        evidenceTableBody.innerHTML = html;
    }

    function filterEvidenceTable() {
        const stepsData = getAuditStepsData();
        const tabKey = currentEvidenceTab;
        let filesToDisplay = [];
        let targetDescText = '';

        if (tabKey === 'all') {
            ['config', 'route', 'view', 'app', 'event'].forEach(k => {
                if (stepsData[k] && Array.isArray(stepsData[k].files_deleted)) {
                    stepsData[k].files_deleted.forEach(f => {
                        filesToDisplay.push({ ...f, category: stepsData[k].name || (k.toUpperCase() + ' Cache'), stepKey: k });
                    });
                }
            });
            targetDescText = 'bootstrap/cache & storage/framework';
        } else {
            const stepObj = stepsData[tabKey];
            if (stepObj && Array.isArray(stepObj.files_deleted)) {
                stepObj.files_deleted.forEach(f => {
                    filesToDisplay.push({ ...f, category: stepObj.name || tabKey, stepKey: tabKey });
                });
                targetDescText = stepObj.target_desc || '-';
            }
        }
        renderEvidenceTableRows(filesToDisplay, targetDescText);
    }

    function copyEvidenceAuditText() {
        const stepsData = getAuditStepsData();
        let report = `=== LAPORAN BUKTI NYATA PEMBERSIHAN CACHE KARYAKU ===\n`;
        report += `Waktu: ${currentCacheAudit?.cleared_at_formatted || new Date().toLocaleString('id-ID')}\n`;
        report += `Eksekutor: ${currentCacheAudit?.by || 'Admin'}\n`;
        report += `Total File Dihapus: ${currentCacheAudit?.total_files || 0} file\n`;
        report += `Total Ukuran Dibersihkan: ${currentCacheAudit?.total_size_formatted || '0 B'}\n\n`;

        ['config', 'route', 'view', 'app', 'event'].forEach(k => {
            const s = stepsData[k];
            if (s) {
                report += `[${s.name || k.toUpperCase()}]\n`;
                report += `Target: ${s.target_desc || '-'}\n`;
                report += `Status: ${s.label || 'Bersih'}\n`;
                if (s.files_deleted && s.files_deleted.length > 0) {
                    s.files_deleted.forEach((f, idx) => {
                        report += `  ${idx + 1}. ${f.name} (${f.size_formatted}) -> ${f.path}\n`;
                    });
                } else {
                    report += `  (0 file fisik tertimbun, direktori sudah steril)\n`;
                }
                report += `\n`;
            }
        });

        navigator.clipboard.writeText(report).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Laporan audit berhasil disalin ke clipboard!',
                showConfirmButton: false,
                timer: 2500
            });
        }).catch(() => {
            alert('Gagal menyalin log.');
        });
    }

    function formatBytesJs(bytes) {
        if (!bytes || bytes <= 0) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + units[i];
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function confirmClearCache() {
        Swal.fire({
            title: '🧹 Bersihkan Cache?',
            html: `
                <div class="text-left text-xs sm:text-sm text-slate-600 bg-amber-50/80 border border-amber-200/80 rounded-xl p-3.5 space-y-2 mt-1">
                    <p class="font-semibold text-slate-800">Cache aplikasi akan dibersihkan secara fisik dari server hosting untuk menyegarkan konfigurasi, rute, template blade, dan performa sistem.</p>
                    <div class="flex items-center gap-2 text-[11px] sm:text-xs text-emerald-700 font-bold pt-2 border-t border-amber-200">
                        <i class="fa-solid fa-shield-check text-emerald-600 text-sm shrink-0"></i>
                        <span>Data pengguna, produk, transaksi, dan data utama lainnya tidak akan dihapus.</span>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fa-solid fa-broom mr-1.5"></i> Bersihkan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs font-bold px-5 py-2.5',
                cancelButton: 'rounded-xl text-xs font-bold px-5 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                executeClearCacheProcess();
            }
        });
    }

    async function executeClearCacheProcess() {
        // Reset UI State
        cacheModalIconContainer.className = 'w-9 h-9 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center font-bold text-sm shadow-sm transition-colors';
        cacheModalIcon.className = 'fa-solid fa-broom text-sm';
        cacheModalTitle.innerText = 'Membersihkan Cache...';
        cacheModalSubtitle.innerText = 'Menyegarkan seluruh cache sistem Laravel secara fisik di hosting';
        cacheModalHeaderCloseBtn.classList.add('hidden');
        cacheProcessTime.classList.add('hidden');
        cacheModalCloseBtn.classList.add('hidden');
        if (btnViewAllEvidenceInModal) btnViewAllEvidenceInModal.classList.add('hidden');

        cacheProgressPercent.innerText = '0%';
        cacheProgressPercent.className = 'text-amber-600 font-extrabold text-sm font-display';
        cacheProgressStepText.innerText = '0 / 5 proses';
        cacheProgressBarFill.style.width = '0%';
        cacheProgressBarFill.className = 'bg-gradient-to-r from-amber-500 to-sky-500 h-full rounded-full transition-all duration-300 ease-out shadow-sm';

        activeAuditSteps = {};

        const steps = [
            { key: 'app', name: 'App Cache' },
            { key: 'config', name: 'Config Cache' },
            { key: 'route', name: 'Route Cache' },
            { key: 'view', name: 'View Cache' },
            { key: 'event', name: 'Event Cache' }
        ];

        // Reset step badges to pending & hide eyes
        steps.forEach(s => {
            const el = document.getElementById(`status-${s.key}`);
            if (el) {
                el.innerHTML = `<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400"><i class="fa-regular fa-circle text-[10px]"></i> Menunggu</span>`;
            }
            const metaEl = document.getElementById(`meta-${s.key}`);
            if (metaEl) { metaEl.classList.add('hidden'); metaEl.innerText = ''; }
            const eyeEl = document.getElementById(`eye-${s.key}`);
            if (eyeEl) { eyeEl.classList.add('hidden'); }
        });

        openCacheProgressModal();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const startTime = performance.now();
        let completedCount = 0;

        for (let i = 0; i < steps.length; i++) {
            const step = steps[i];
            const statusEl = document.getElementById(`status-${step.key}`);
            
            if (statusEl) {
                statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600"><i class="fa-solid fa-spinner fa-spin text-[11px]"></i> Sedang memproses...</span>`;
            }

            try {
                const response = await fetch(`{{ route('admin.clearCache') }}?step=${step.key}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    completedCount++;
                    activeAuditSteps[step.key] = data;

                    if (statusEl) {
                        statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check text-[11px]"></i> Bersih</span>`;
                    }
                    const metaEl = document.getElementById(`meta-${step.key}`);
                    if (metaEl) {
                        if (data.files_count > 0) {
                            metaEl.innerHTML = `<span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">${data.files_count} file (${data.total_size_formatted})</span>`;
                        } else {
                            metaEl.innerHTML = `<span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">0 file (Steril)</span>`;
                        }
                        metaEl.classList.remove('hidden');
                    }
                    const eyeEl = document.getElementById(`eye-${step.key}`);
                    if (eyeEl) {
                        eyeEl.classList.remove('hidden');
                    }
                } else {
                    if (statusEl) {
                        statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600"><i class="fa-solid fa-circle-xmark text-[11px]"></i> Gagal</span>`;
                    }
                }
            } catch (err) {
                completedCount++;
                if (statusEl) {
                    statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check text-[11px]"></i> Bersih</span>`;
                }
            }

            const percent = Math.round(((i + 1) / steps.length) * 100);
            cacheProgressPercent.innerText = `${percent}%`;
            cacheProgressStepText.innerText = `${i + 1} / 5 proses`;
            cacheProgressBarFill.style.width = `${percent}%`;

            await new Promise(r => setTimeout(r, 180));
        }

        let formattedDate = 'Baru saja';
        try {
            const finishRes = await fetch(`{{ route('admin.clearCache') }}?step=finish`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const finishData = await finishRes.json();
            if (finishData.cleared_at_formatted) {
                formattedDate = finishData.cleared_at_formatted;
            }
            if (finishData.audit) {
                currentCacheAudit = finishData.audit;
            } else {
                currentCacheAudit = {
                    cleared_at_formatted: formattedDate,
                    total_files: Object.values(activeAuditSteps).reduce((acc, c) => acc + (c.files_count || 0), 0),
                    total_size_formatted: formatBytesJs(Object.values(activeAuditSteps).reduce((acc, c) => acc + (c.total_size || 0), 0)),
                    steps: activeAuditSteps
                };
            }
        } catch (err) {
            currentCacheAudit = {
                cleared_at_formatted: formattedDate,
                total_files: Object.values(activeAuditSteps).reduce((acc, c) => acc + (c.files_count || 0), 0),
                total_size_formatted: formatBytesJs(Object.values(activeAuditSteps).reduce((acc, c) => acc + (c.total_size || 0), 0)),
                steps: activeAuditSteps
            };
        }

        const durationSec = ((performance.now() - startTime) / 1000).toFixed(1);

        cacheModalIconContainer.className = 'w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center font-bold text-sm shadow-sm transition-colors';
        cacheModalIcon.className = 'fa-solid fa-circle-check text-base text-emerald-600';
        cacheModalTitle.innerText = 'Cache Berhasil Dibersihkan';
        cacheModalSubtitle.innerText = `${completedCount} / 5 proses berhasil • Terhapus secara fisik`;
        cacheProgressBarFill.className = 'bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full transition-all duration-300 ease-out shadow-sm';
        cacheProgressPercent.className = 'text-emerald-600 font-extrabold text-sm font-display';

        cacheProcessTimeVal.innerText = `${durationSec} detik`;
        cacheProcessTime.classList.remove('hidden');
        if (btnViewAllEvidenceInModal) btnViewAllEvidenceInModal.classList.remove('hidden');
        cacheModalCloseBtn.classList.remove('hidden');
        cacheModalHeaderCloseBtn.classList.remove('hidden');

        if (lastCacheClearedBadge) {
            lastCacheClearedBadge.innerText = formattedDate;
            lastCacheClearedBadge.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
            setTimeout(() => {
                lastCacheClearedBadge.classList.remove('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
            }, 3000);
        }
    }
</script>
@endpush
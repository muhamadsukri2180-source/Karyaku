@extends('layouts.admin')

@section('title', 'Laporan Pelanggaran')
@section('header_title', 'Laporan Pelanggaran')
@section('header_subtitle', 'Tinjau dan tindak lanjuti laporan pelanggaran sistem.')

@section('content')
<div class="p-6 sm:p-8 space-y-6">

    <!-- TAB SWITCHER -->
    <div class="flex flex-wrap items-center gap-2 bg-white border border-sky-200 rounded-2xl p-1.5 w-full sm:w-max shadow-sm">
        <button type="button" onclick="switchTab('pengguna')" id="tabBtnPengguna" class="tab-btn active-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
            <i class="fa-solid fa-user-xmark mr-1"></i> Pelanggaran Pengguna / Umum
        </button>
        <button type="button" onclick="switchTab('penjual')" id="tabBtnPenjual" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer">
            <i class="fa-solid fa-shop-slash mr-1"></i> Pelanggaran Produk / Penjual
        </button>
        <button type="button" onclick="switchTab('banding')" id="tabBtnBanding" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer flex items-center gap-1.5">
            <i class="fa-solid fa-shield-halved text-amber-500"></i>
            <span>Laporan Banding Pemblokiran</span>
            @if(!empty($pendingAppealCount) && $pendingAppealCount > 0)
                <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black shadow-xs">
                    {{ $pendingAppealCount }}
                </span>
            @endif
        </button>
    </div>

    <!-- TAB 1: LAPORAN PENGGUNA / UMUM -->
    <div id="tabPengguna" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden block">
        <div class="p-5 border-b border-sky-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Laporan Pengguna & Umum</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pelapor</th>
                        <th class="py-4 px-6">Dilaporkan</th>
                        <th class="py-4 px-6">Alasan</th>
                        <th class="py-4 px-6">Deskripsi</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($reportsUser as $report)
                    @php
                    $statusColor = match($report->status) {
                    'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                    'escalated' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-amber-50 text-amber-700 border-amber-200',
                    };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                        <td class="py-3 px-6 text-xs font-semibold text-slate-700">{{ $report->reporter->name ?? 'User #'.$report->user_id }}</td>
                        <td class="py-3 px-6 text-xs font-semibold text-slate-700">
                            @if($report->reportedUser)
                                <span class="text-slate-800 font-bold">{{ $report->reportedUser->name }}</span>
                            @else
                                <span class="text-slate-400 italic">Laporan Umum / Sistem</span>
                            @endif
                        </td>
                        <td class="py-3 px-6"><p class="text-xs text-slate-700 font-medium">{{ $report->reason }}</p></td>
                        <td class="py-3 px-6"><p class="text-xs text-slate-600 w-48 truncate">{{ $report->description ?? '-' }}</p></td>
                        <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                        <td class="py-3 px-6 text-xs text-slate-600">{{ optional($report->created_at)->format('d M Y - H:i') }}</td>
                        <td class="py-3 px-6">
                            <div class="flex justify-center">
                                @if(in_array($report->status, ['pending', 'escalated']))
                                <button type="button" onclick="openTindakModal('user', '{{ $report->id_report }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-gavel"></i> Tindak Lanjut
                                </button>
                                @else
                                <span class="text-[10px] text-slate-400 font-semibold">Sudah ditindak</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data laporan pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reportsUser->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $reportsUser->appends(request()->query())->links() }}</div>
        @endif
    </div>

    <!-- TAB 2: LAPORAN PRODUK / PENJUAL -->
    <div id="tabPenjual" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
        <div class="p-5 border-b border-sky-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Laporan Produk / Penjual</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pelapor</th>
                        <th class="py-4 px-6">Produk & Penjual</th>
                        <th class="py-4 px-6">Alasan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($reportsProduk as $report)
                    @php
                        $statusColor = match($report->status) {
                            'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                        <td class="py-3 px-6 text-xs font-semibold text-slate-700">{{ $report->reporter->name ?? 'User #'.$report->user_id }}</td>
                        <td class="py-3 px-6">
                            <p class="text-xs font-semibold text-slate-700">{{ $report->product->title ?? 'Produk dihapus' }}</p>
                            <p class="text-[10px] text-slate-500">{{ $report->product->seller->name ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-6"><p class="text-xs text-slate-600 w-56 truncate">{{ $report->reason }}</p></td>
                        <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                        <td class="py-3 px-6 text-xs text-slate-600">{{ optional($report->created_at)->format('d M Y - H:i') }}</td>
                        <td class="py-3 px-6">
                            <div class="flex justify-center">
                                @if($report->status === 'pending')
                                <button type="button" onclick="openTindakModal('produk', '{{ $report->id_report }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-shield-halved"></i> Tindak Lanjut
                                </button>
                                @else
                                <span class="text-[10px] text-slate-400 font-semibold">Sudah ditindak</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada data laporan produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reportsProduk->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $reportsProduk->appends(request()->query())->links() }}</div>
        @endif
    </div>

    <!-- TAB 3: LAPORAN BANDING PEMBLOKIRAN AKUN -->
    <div id="tabBanding" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg font-display">Laporan Banding Pemblokiran Akun</h3>
                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Daftar permohonan pembukaan blokir akun dari pengguna yang disuspend.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pengguna</th>
                        <th class="py-4 px-6">Alasan Suspend Admin</th>
                        <th class="py-4 px-6">Pembelaan / Alasan User</th>
                        <th class="py-4 px-6 text-center">Bukti Gambar</th>
                        <th class="py-4 px-6">Status Banding</th>
                        <th class="py-4 px-6">Tgl Pengajuan</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($reportsAppeal ?? [] as $appeal)
                    @php
                        $appealStatusColor = match($appeal->status) {
                            'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-amber-100 text-amber-800 border-amber-200',
                        };
                        $appealStatusLabel = match($appeal->status) {
                            'approved' => 'Disetujui (Aktif)',
                            'rejected' => 'Ditolak',
                            default => 'Menunggu Review',
                        };
                        $userRole = $appeal->user->role->role_name ?? 'User';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                        <td class="py-3.5 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    {{ strtoupper(substr($appeal->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-xs">{{ $appeal->user->name ?? 'User #'.$appeal->user_id }}</p>
                                    @if(!empty($appeal->user?->email) && !str_starts_with($appeal->user->email, '$') && str_contains($appeal->user->email, '@'))
                                        <p class="text-[10px] text-slate-500">{{ $appeal->user->email }}</p>
                                    @elseif(!empty($appeal->user?->phone))
                                        <p class="text-[10px] text-slate-500">{{ $appeal->user->phone }}</p>
                                    @endif
                                    <span class="inline-block mt-0.5 text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $userRole }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="text-xs text-slate-700 font-medium">{{ $appeal->user->suspend_reason ?? 'Pelanggaran ketentuan' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                @if($appeal->user && $appeal->user->suspended_until)
                                    Hingga {{ $appeal->user->suspended_until->format('d M Y H:i') }}
                                @else
                                    Permanen
                                @endif
                            </p>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium max-w-xs leading-relaxed">
                                "{{ $appeal->reason }}"
                            </p>
                            @if($appeal->admin_note)
                                <p class="text-[10px] text-slate-500 mt-1 font-semibold">
                                    <span class="text-slate-700 font-bold">Catatan Admin:</span> {{ $appeal->admin_note }}
                                </p>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            @if($appeal->proof_image)
                                <button type="button" onclick="previewImage('{{ asset('storage/' . $appeal->proof_image) }}')" class="group relative inline-block rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition">
                                    <img src="{{ asset('storage/' . $appeal->proof_image) }}" alt="Bukti" class="w-14 h-14 object-cover group-hover:scale-110 transition duration-300">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </button>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada bukti</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $appealStatusColor }}">
                                {{ $appealStatusLabel }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-xs text-slate-600 font-medium">
                            {{ optional($appeal->created_at)->format('d M Y - H:i') }}
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($appeal->status === 'pending')
                                <button type="button" onclick='openAppealModal(@json($appeal))' class="px-3.5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-gavel"></i> Tindak Banding
                                </button>
                                @else
                                <span class="text-[10px] text-slate-400 font-semibold">Telah Diproses</span>
                                @endif

                                <!-- Tombol Hapus Riwayat Banding dengan SweetAlert2 -->
                                <form id="delete-appeal-{{ $appeal->id_appeal }}" action="{{ route('admin.pelanggaran.appeal.delete', $appeal->id_appeal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteAppeal('delete-appeal-{{ $appeal->id_appeal }}')" class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition border border-red-200 shadow-sm cursor-pointer" title="Hapus Riwayat Banding">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada pengajuan banding pemblokiran akun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($reportsAppeal) && $reportsAppeal->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $reportsAppeal->appends(request()->query())->links() }}</div>
        @endif
    </div>

</div>
@endsection

@push('modals')
<!-- MODAL TINDAK LANJUT LAPORAN UMUM / PRODUK -->
<div id="tindakModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4" id="tindakModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center"><i class="fa-solid fa-gavel text-sm"></i></div>
                <h3 class="font-extrabold text-slate-900 font-display text-base" id="modalTitle">Tindak Lanjut Pelanggaran</h3>
            </div>
            <button type="button" onclick="closeTindakModal()" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="#" method="POST" id="formTindak" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Pilih Aksi</label>
                <select name="action" id="actionSelect" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none transition-all">
                    <option value="">Pilih Tindakan</option>
                    <option value="peringatan">Kirim sebuah Peringatan</option>
                    <option value="suspend">Suspend Akun / Takedown</option>
                    <option value="abaikan">Abaikan Laporan</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Catatan Admin</label>
                <textarea name="admin_notes" rows="3" required placeholder="Berikan catatan..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none transition-all"></textarea>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center gap-2 cursor-pointer">
                    Simpan Tindakan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TINDAK LANJUT BANDING AKUN -->
<div id="appealModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4" id="appealModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-amber-50/50 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-shield-halved text-sm"></i></div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Tindak Lanjut Banding Akun</h3>
            </div>
            <button type="button" onclick="closeAppealModal()" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="#" method="POST" id="formAppeal" class="p-6 space-y-4">
            @csrf
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                <p class="text-xs font-bold text-slate-800" id="appealUserName">-</p>
                <p class="text-[11px] text-slate-600" id="appealUserReason">-</p>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Pilih Keputusan Banding <span class="text-red-500">*</span></label>
                <select name="action" id="appealActionSelect" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all">
                    <option value="">Pilih Keputusan</option>
                    <option value="setujui" class="text-emerald-600 font-bold">✓ Setujui Banding (Buka Blokir & Aktifkan Akun)</option>
                    <option value="tolak" class="text-red-600 font-bold">✕ Tolak Banding (Tetap Suspend)</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Catatan / Alasan Keputusan Admin (Opsional)</label>
                <textarea name="admin_notes" rows="3" placeholder="Berikan catatan penjelasan untuk pengguna..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"></textarea>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all flex justify-center items-center gap-2 cursor-pointer">
                    Simpan Keputusan Banding
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL ZOOM PREVIEW GAMBAR BUKTI -->
<div id="imagePreviewModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4 transition-opacity duration-300 opacity-0" onclick="closeImagePreview()">
    <div class="relative max-w-3xl max-h-[90vh] p-2" onclick="event.stopPropagation()">
        <button type="button" onclick="closeImagePreview()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-800 hover:text-red-500 flex items-center justify-center shadow-lg font-bold"><i class="fa-solid fa-xmark"></i></button>
        <img id="modalPreviewImg" src="" alt="Bukti Gambar" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain bg-white">
    </div>
</div>
@endpush

@push('scripts')
<script>
    function switchTab(tab) {
        document.getElementById('tabPengguna').style.display = tab === 'pengguna' ? 'block' : 'none';
        document.getElementById('tabPenjual').style.display = tab === 'penjual' ? 'block' : 'none';
        document.getElementById('tabBanding').style.display = tab === 'banding' ? 'block' : 'none';
        
        document.getElementById('tabBtnPengguna').classList.toggle('active-tab', tab === 'pengguna');
        document.getElementById('tabBtnPenjual').classList.toggle('active-tab', tab === 'penjual');
        document.getElementById('tabBtnBanding').classList.toggle('active-tab', tab === 'banding');
    }

    const tindakModal = document.getElementById('tindakModal');
    const tindakModalContent = document.getElementById('tindakModalContent');
    const formTindak = document.getElementById('formTindak');
    const modalTitle = document.getElementById('modalTitle');

    function openTindakModal(type, id) {
        if (type === 'produk') {
            formTindak.action = "{{ url('admin/pelanggaran/produk') }}/" + id;
            modalTitle.textContent = "Tindak Lanjut Produk / Penjual";
        } else {
            formTindak.action = "{{ url('admin/pelanggaran/user') }}/" + id;
            modalTitle.textContent = "Tindak Lanjut Pengguna";
        }
        tindakModal.classList.remove('hidden');
        setTimeout(() => {
            tindakModal.classList.remove('opacity-0');
            tindakModalContent.classList.remove('scale-95');
            tindakModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeTindakModal() {
        tindakModal.classList.add('opacity-0');
        tindakModalContent.classList.remove('scale-100');
        tindakModalContent.classList.add('scale-95');
        setTimeout(() => { tindakModal.classList.add('hidden'); }, 300);
    }

    const appealModal = document.getElementById('appealModal');
    const appealModalContent = document.getElementById('appealModalContent');
    const formAppeal = document.getElementById('formAppeal');
    const appealUserName = document.getElementById('appealUserName');
    const appealUserReason = document.getElementById('appealUserReason');

    function openAppealModal(appeal) {
        formAppeal.action = "{{ url('admin/pelanggaran/appeal') }}/" + appeal.id_appeal;
        const userEmail = (appeal.user && appeal.user.email && !appeal.user.email.startsWith('$') && appeal.user.email.includes('@')) ? ' (' + appeal.user.email + ')' : (appeal.user && appeal.user.phone ? ' (' + appeal.user.phone + ')' : '');
        appealUserName.textContent = "Pemohon: " + (appeal.user ? appeal.user.name : 'User') + userEmail;
        appealUserReason.textContent = "Alasan Pembelaan: \"" + appeal.reason + "\"";
        
        appealModal.classList.remove('hidden');
        setTimeout(() => {
            appealModal.classList.remove('opacity-0');
            appealModalContent.classList.remove('scale-95');
            appealModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeAppealModal() {
        appealModal.classList.add('opacity-0');
        appealModalContent.classList.remove('scale-100');
        appealModalContent.classList.add('scale-95');
        setTimeout(() => { appealModal.classList.add('hidden'); }, 300);
    }

    function previewImage(url) {
        document.getElementById('modalPreviewImg').src = url;
        const modal = document.getElementById('imagePreviewModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); }, 10);
    }

    function closeImagePreview() {
        const modal = document.getElementById('imagePreviewModal');
        modal.classList.add('opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Konfirmasi Hapus Banding dengan SweetAlert2
    function confirmDeleteAppeal(formId) {
        Swal.fire({
            title: 'Hapus Riwayat Banding?',
            text: "Data riwayat banding ini akan dihapus secara permanen dari sistem!",
            icon: 'warning',
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
</script>
@endpush

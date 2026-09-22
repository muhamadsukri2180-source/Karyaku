@extends('layouts.cs')

@section('title', 'Karyaku - Laporan & Moderasi CS')
@section('header_title', 'Laporan & Moderasi CS')
@section('header_subtitle', 'Tinjau laporan pengaduan, pengajuan banding pemblokiran, serta riwayat penanganan.')

@section('content')
<!-- TAB SWITCHER -->
<div class="flex flex-wrap items-center gap-2 bg-white border border-sky-200 rounded-2xl p-1.5 w-full sm:w-max shadow-sm">
    <button type="button" onclick="switchTab('pengguna')" id="tabBtnPengguna" class="tab-btn active-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
        <i class="fa-solid fa-user-xmark mr-1"></i> Pelanggaran Pengguna
    </button>
    <button type="button" onclick="switchTab('penjual')" id="tabBtnPenjual" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer">
        <i class="fa-solid fa-shop-slash mr-1"></i> Pelanggaran Produk
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
    <button type="button" onclick="switchTab('riwayat')" id="tabBtnRiwayat" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 transition-all border border-transparent cursor-pointer">
        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Moderasi
    </button>
</div>

<!-- TAB 1: LAPORAN PENGGUNA -->
<div id="tabPengguna" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden block">
    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Laporan Pengguna</h3>
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
                            {{ $report->reportedUser->name }}
                        @else
                            <span class="italic text-slate-400">Laporan Umum</span>
                        @endif
                    </td>
                    <td class="py-3 px-6"><span class="inline-block bg-sky-50 border border-sky-200 text-sky-700 text-[10px] font-bold px-2 py-1 rounded-lg">{{ $report->reason }}</span></td>
                    <td class="py-3 px-6"><p class="text-xs text-slate-600 max-w-[180px] break-words" title="{{ $report->description }}">{{ $report->description ? \Illuminate\Support\Str::limit($report->description, 60) : '-' }}</p></td>
                    <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                    <td class="py-3 px-6 text-xs text-slate-500 whitespace-nowrap">{{ optional($report->created_at)->format('d M Y') }}<br><span class="text-[10px]">{{ optional($report->created_at)->format('H:i') }}</span></td>
                    <td class="py-3 px-6">
                        <div class="flex justify-center">
                            @if(in_array($report->status, ['pending', 'escalated']))
                            <button type="button" onclick="openTindakModal('user', '{{ $report->id }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
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

<!-- TAB 2: LAPORAN PRODUK -->
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
                    <td class="py-3 px-6">
                        <span class="inline-block bg-orange-50 border border-orange-200 text-orange-700 text-[10px] font-bold px-2 py-1 rounded-lg">{{ $report->reason }}</span>
                        @if($report->description)
                        <p class="text-[10px] text-slate-500 mt-1 max-w-[140px] break-words" title="{{ $report->description }}">{{ \Illuminate\Support\Str::limit($report->description, 50) }}</p>
                        @endif
                    </td>
                    <td class="py-3 px-6"><span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                    <td class="py-3 px-6 text-xs text-slate-500 whitespace-nowrap">{{ optional($report->created_at)->format('d M Y') }}<br><span class="text-[10px]">{{ optional($report->created_at)->format('H:i') }}</span></td>
                    <td class="py-3 px-6">
                        <div class="flex justify-center">
                            @if(in_array($report->status, ['pending', 'escalated']))
                            <button type="button" onclick="openTindakModal('produk', '{{ $report->id }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
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
    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Laporan Banding Pemblokiran Akun</h3>
            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Permohonan pembukaan blokir akun dari pengguna yang disuspend.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Pengguna</th>
                    <th class="py-4 px-6">Alasan Suspend</th>
                    <th class="py-4 px-6">Pembelaan User</th>
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
                @endphp
                <tr class="hover:bg-slate-50 transition-colors bg-white">
                    <td class="py-3.5 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ strtoupper(substr($appeal->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-xs">{{ $appeal->user->name ?? 'User #'.$appeal->user_id }}</p>
                                <p class="text-[10px] text-slate-500">@safeEmail($appeal->user->email ?? '-')</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-6">
                        <p class="text-xs text-slate-700 font-medium">{{ $appeal->user->suspend_reason ?? 'Pelanggaran ketentuan' }}</p>
                    </td>
                    <td class="py-3.5 px-6">
                        <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium max-w-xs leading-relaxed">
                            "{{ $appeal->reason }}"
                        </p>
                    </td>
                    <td class="py-3.5 px-6 text-center">
                        @if($appeal->proof_image)
                            <button type="button" onclick="previewImage('{{ asset('storage/' . $appeal->proof_image) }}')" class="group relative inline-block rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $appeal->proof_image) }}" alt="Bukti" class="w-14 h-14 object-cover group-hover:scale-110 transition duration-300">
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
                        @if($appeal->status === 'pending')
                        <button type="button" onclick='openAppealModal(@json($appeal))' class="px-3.5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 mx-auto cursor-pointer">
                            <i class="fa-solid fa-gavel"></i> Tindak Banding
                        </button>
                        @else
                        <span class="text-[10px] text-slate-400 font-semibold">Telah Diproses</span>
                        @endif
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
    @if(isset($reportsAppeal) && method_exists($reportsAppeal, 'hasPages') && $reportsAppeal->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $reportsAppeal->appends(request()->query())->links() }}</div>
    @endif
</div>

<!-- TAB 4: RIWAYAT MODERASI -->
<div id="tabRiwayat" class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden hidden">
    <div class="p-5 border-b border-sky-100 flex items-center justify-between">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Riwayat Moderasi Laporan</h3>
            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Daftar laporan pengaduan yang telah selesai ditindaklanjuti.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Target / Item</th>
                    <th class="py-4 px-6">Alasan Laporan</th>
                    <th class="py-4 px-6">Status Akhir</th>
                    <th class="py-4 px-6">Catatan Petugas</th>
                    <th class="py-4 px-6">Tanggal Penanganan</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($riwayat ?? [] as $report)
                @php
                    $riwayatStatusColor = match($report->status) {
                        'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200',
                        'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors bg-white">
                    <td class="py-3.5 px-6 text-xs font-semibold text-slate-800">
                        {{ $report->product->title ?? ($report->reportedUser->name ?? 'Laporan Umum') }}
                    </td>
                    <td class="py-3.5 px-6 text-xs text-slate-600 font-medium">{{ $report->reason }}</td>
                    <td class="py-3.5 px-6">
                        <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $riwayatStatusColor }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $report->admin_note ?? '-' }}</td>
                    <td class="py-3.5 px-6 text-xs text-slate-500 font-medium">
                        {{ optional($report->reviewed_at ?? $report->updated_at)->format('d M Y - H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada riwayat laporan yang ditindaklanjuti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($riwayat) && method_exists($riwayat, 'hasPages') && $riwayat->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $riwayat->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection

@push('modals')
<!-- MODAL TINDAK LAPORAN (PENGGUNA / PRODUK) -->
<div id="tindakModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4" id="tindakModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 font-display text-base" id="modalTitle">Tindak Lanjut Laporan</h3>
            <button type="button" onclick="closeTindakModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="#" method="POST" id="formTindak" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase">Pilih Aksi</label>
                <select name="action" id="selectAksi" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700">
                    <option value="peringatan">⚠️ Kirim Peringatan ke Terlapor</option>
                    <option value="teguran">📢 Kirim Teguran ke Terlapor</option>
                    <option value="suspend">🚫 Suspend Akun Terlapor</option>
                    <option value="sembunyikan" id="optSembunyikan" style="display:none">🙈 Sembunyikan Produk</option>
                    <option value="eskalasi">📤 Eskalasi ke Admin</option>
                    <option value="abaikan">🗑️ Abaikan Laporan</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase">Catatan CS <span class="text-red-500">*</span></label>
                <textarea name="admin_notes" rows="3" required placeholder="Berikan catatan penanganan..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition">Simpan Tindakan</button>
        </form>
    </div>
</div>

<!-- MODAL TINDAK BANDING -->
<div id="appealModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 font-display text-base">Tindak Lanjut Banding Akun</h3>
            <button type="button" onclick="closeAppealModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="#" method="POST" id="formAppeal" class="p-6 space-y-4">
            @csrf
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                <p class="text-xs font-bold text-slate-800" id="appealUserName">-</p>
                <p class="text-[11px] text-slate-600" id="appealUserReason">-</p>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 uppercase">Keputusan Banding <span class="text-red-500">*</span></label>
                <select name="action" id="appealActionSelect" required class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800">
                    <option value="">Pilih Keputusan</option>
                    <option value="setujui" class="text-emerald-600 font-bold">✓ Setujui Banding (Buka Blokir & Aktifkan Akun)</option>
                    <option value="tolak" class="text-red-600 font-bold">✕ Tolak Banding (Tetap Suspend)</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase">Catatan Keputusan (Opsional)</label>
                <textarea name="admin_notes" rows="3" placeholder="Penjelasan keputusan untuk pengguna..." class="mt-2 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-700"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-md transition">Simpan Keputusan</button>
        </form>
    </div>
</div>

<!-- MODAL PREVIEW BUKTI GAMBAR -->
<div id="imagePreviewModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4" onclick="closeImagePreview()">
    <div class="relative max-w-3xl max-h-[90vh] p-2" onclick="event.stopPropagation()">
        <button type="button" onclick="closeImagePreview()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-800 flex items-center justify-center font-bold shadow-lg"><i class="fa-solid fa-xmark"></i></button>
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
        document.getElementById('tabRiwayat').style.display = tab === 'riwayat' ? 'block' : 'none';

        document.getElementById('tabBtnPengguna').classList.toggle('active-tab', tab === 'pengguna');
        document.getElementById('tabBtnPenjual').classList.toggle('active-tab', tab === 'penjual');
        document.getElementById('tabBtnBanding').classList.toggle('active-tab', tab === 'banding');
        document.getElementById('tabBtnRiwayat').classList.toggle('active-tab', tab === 'riwayat');
    }

    const tindakModal = document.getElementById('tindakModal');
    const formTindak = document.getElementById('formTindak');
    const modalTitle = document.getElementById('modalTitle');

    function openTindakModal(type, id) {
        const optSembunyikan = document.getElementById('optSembunyikan');
        if (type === 'produk') {
            formTindak.action = "{{ url('cs/laporan/produk') }}/" + id + "/tindak";
            modalTitle.textContent = "Tindak Lanjut Laporan Produk";
            if (optSembunyikan) optSembunyikan.style.display = '';
        } else {
            formTindak.action = "{{ url('cs/laporan/user') }}/" + id + "/tindak";
            modalTitle.textContent = "Tindak Lanjut Laporan Pengguna";
            if (optSembunyikan) optSembunyikan.style.display = 'none';
        }
        tindakModal.classList.remove('hidden');
    }
    function closeTindakModal() { tindakModal.classList.add('hidden'); formTindak.reset(); }

    const appealModal = document.getElementById('appealModal');
    const formAppeal = document.getElementById('formAppeal');

    function openAppealModal(appeal) {
        formAppeal.action = "{{ url('cs/laporan/appeal') }}/" + appeal.id + "/tindak";
        document.getElementById('appealUserName').textContent = "Pemohon: " + (appeal.user ? appeal.user.name : 'User #' + appeal.user_id);
        document.getElementById('appealUserReason').textContent = "Alasan banding: \"" + (appeal.reason || '-') + "\"";
        appealModal.classList.remove('hidden');
    }
    function closeAppealModal() { appealModal.classList.add('hidden'); }

    function previewImage(url) {
        document.getElementById('modalPreviewImg').src = url;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
    }
    function closeImagePreview() { document.getElementById('imagePreviewModal').classList.add('hidden'); }
</script>
@endpush

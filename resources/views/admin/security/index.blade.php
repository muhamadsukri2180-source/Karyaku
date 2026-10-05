@extends('layouts.admin')

@section('title', 'Keamanan System & Monitoring IP')
@section('header_title', 'Keamanan System & Monitoring IP')
@section('header_subtitle')
    IP Anda saat ini: <span class="font-mono font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200">{{ $myIp }}</span>
@endsection

@section('header_right')
    <a href="{{ route('admin.security.verify', ['reset' => 1]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] transition-all cursor-pointer">
        <i class="fa-solid fa-lock"></i> Kunci Kembali
    </a>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-8 overflow-y-auto no-scrollbar">

    <!-- TABEL 1: IP ABNORMAL (DETEKSI HACK/JAILBREAK) -->
    <div class="bg-white border border-red-200 rounded-2xl shadow-lg shadow-red-500/5 overflow-hidden">
        <div class="p-5 border-b border-red-100 bg-gradient-to-r from-red-500/10 via-rose-50 to-white flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center border border-red-200 shadow-inner shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-red-950 text-base font-display">Daftar IP Mencurigakan (Abnormal & Attack Attempts)</h3>
                    <p class="text-[11px] text-red-600/80 font-medium">Pengunjung yang mencoba bobol file/jailbreak atau memicu honeypot sistem.</p>
                </div>
            </div>
            <span class="bg-red-600 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">{{ $abnormalIps->count() }} Terdeteksi</span>
        </div>

        <!-- TABEL WITH INTERNAL SCROLLBAR -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-slate-200 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                        <th class="py-3.5 px-6">Alamat IP</th>
                        <th class="py-3.5 px-6">Ancaman / Alasan</th>
                        <th class="py-3.5 px-6">File & Lokasi Dibobol</th>
                        <th class="py-3.5 px-6 text-center">Total Permintaan</th>
                        <th class="py-3.5 px-6">Waktu Terakhir</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($abnormalIps as $index => $ip)
                    <tr class="hover:bg-red-50/40 transition-colors bg-white odd:bg-slate-50/30 whitespace-nowrap">
                        <td class="py-4 px-6 font-mono font-bold text-red-600">{{ $ip->ip_address }}</td>
                        <td class="py-4 px-6 text-slate-700 font-semibold max-w-xs truncate">{{ $ip->reason ?? '-' }}</td>
                        
                        <td class="py-4 px-6 font-mono text-[11px] text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="truncate max-w-[220px] bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200 font-bold">{{ $ip->last_activity ?? 'N/A' }}</span>
                                <!-- ICON MATA UNTUK DETAIL BOBOL -->
                                <button type="button" onclick="showJailbreakDetail('{{ $ip->ip_address }}', '{{ addslashes($ip->last_activity) }}', '{{ addslashes($ip->reason) }}', '{{ addslashes($ip->user_agent) }}')" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs shrink-0 cursor-pointer" title="Lihat Lokasi File & Payload">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-center font-bold text-slate-700"><span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[11px] border border-red-200">{{ $ip->request_count }}x</span></td>
                        <td class="py-4 px-6 text-slate-500 font-medium">{{ $ip->last_activity_at?->diffForHumans() }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.security.toggle', $ip->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                        Normal
                                    </button>
                                </form>

                                <form id="delete-log-{{ $index }}" action="{{ route('admin.security.log.destroy', $ip->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteLog('delete-log-{{ $index }}')" class="w-7 h-7 rounded-lg bg-slate-200 hover:bg-red-600 text-slate-700 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs cursor-pointer" title="Hapus Log">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 text-xs font-semibold bg-slate-50/20">
                            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 rounded-xl">
                                <i class="fa-solid fa-circle-check text-base"></i>
                                <span>Sistem Aman! Belum ada aktivitas percobaan bobol/jailbreak terdeteksi.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 2: IP NORMAL (WARNA KUNING AMBER & BEKUKAN TIMER HARI/JAM/DETIK) -->
    <div class="bg-amber-50/90 border border-amber-300 rounded-2xl shadow-lg shadow-amber-500/10 overflow-hidden">
        <div class="p-5 border-b border-amber-200 bg-gradient-to-r from-amber-500/20 via-amber-100/60 to-amber-50 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center border border-amber-600 shadow-sm shrink-0">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-amber-950 text-base font-display">Daftar Pengunjung Biasa (Aktivitas Pengguna)</h3>
                    <p class="text-[11px] text-amber-900/90 font-semibold">Gunakan tombol kunci untuk membekukan sementara akun/IP yang terindikasi curang (Cheat/Abuse).</p>
                </div>
            </div>
            <span class="bg-amber-600 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">{{ $normalIps->count() }} IP Logged</span>
        </div>

        <!-- TABEL WITH INTERNAL SCROLLBAR -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-amber-700 text-amber-50 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                        <th class="py-3.5 px-6">Alamat IP</th>
                        <th class="py-3.5 px-6">Aktivitas Terakhir</th>
                        <th class="py-3.5 px-6">User Agent / Browser</th>
                        <th class="py-3.5 px-6 text-center">Total Permintaan</th>
                        <th class="py-3.5 px-6">Waktu Terakhir</th>
                        <th class="py-3.5 px-6 text-center">Aksi / Bekukan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-amber-200 text-amber-950 font-medium">
                    @forelse($normalIps as $ip)
                    <tr class="hover:bg-amber-100/80 transition-colors bg-white odd:bg-amber-50/50 whitespace-nowrap">
                        <td class="py-4 px-6 font-mono font-bold text-amber-900">{{ $ip->ip_address }}</td>
                        <td class="py-4 px-6 font-mono text-[11px] text-amber-900 max-w-xs truncate">{{ $ip->last_activity }}</td>
                        <td class="py-4 px-6 text-amber-800 max-w-xs truncate">{{ $ip->user_agent }}</td>
                        <td class="py-4 px-6 text-center font-bold text-amber-900">
                            <span class="bg-amber-200/80 px-2.5 py-1 rounded-md text-[11px] border border-amber-300 font-extrabold text-amber-950">{{ $ip->request_count }}x</span>
                        </td>
                        <td class="py-4 px-6 text-amber-900 font-semibold">{{ $ip->last_activity_at?->diffForHumans() }}</td>
                        <td class="py-4 px-6 text-center">
                            <!-- TOMBOL BEKUKAN AKSES -->
                            <button type="button" onclick="openFreezeTimerModal('{{ $ip->id }}', '{{ $ip->ip_address }}')" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition-all cursor-pointer">
                                <i class="fa-solid fa-key"></i> Kunci (Bekukan)
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-amber-900 text-xs font-semibold bg-amber-50/20">
                            <i class="fa-solid fa-inbox text-amber-400 text-xl block mb-2"></i>
                            Belum ada riwayat aktivitas IP normal yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // MODAL DETAIL FILE / LOKASI BOBOL (TABEL 1)
    function showJailbreakDetail(ip, activity, reason, userAgent) {
        Swal.fire({
            title: '🔍 Detail Percobaan Akses/Jailbreak',
            html: `
                <div class="text-left text-xs space-y-3 font-sans">
                    <div class="bg-red-50 border border-red-200 p-3 rounded-xl">
                        <span class="block text-red-500 font-bold uppercase text-[10px]">Alamat IP:</span>
                        <span class="font-mono font-bold text-red-700 text-sm">${ip}</span>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Target File / URI Lokasi:</span>
                        <code class="block font-mono text-slate-800 bg-white p-2 rounded border border-slate-300 mt-1 break-all">${activity}</code>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Alasan Terdeteksi:</span>
                        <p class="font-semibold text-slate-700 mt-0.5">${reason}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">User Agent / Perangkat:</span>
                        <p class="font-mono text-[11px] text-slate-600 mt-0.5 break-all">${userAgent}</p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#0ea5e9'
        });
    }

    // MODAL PEMBEKUAN TIMER (HARI, JAM, DETIK) UNTUK CHEATER/ABUSE (TABEL 2)
    function openFreezeTimerModal(id, ip) {
        Swal.fire({
            title: '🔐 Bekukan Akses IP / Akun',
            text: `Tentukan durasi pembekuan sementara untuk IP ${ip} (Akibat pelanggaran/kecurangan):`,
            html: `
                <form id="freezeForm" action="{{ url('admin/security/toggle') }}/${id}" method="POST" class="mt-4 text-left font-sans">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Hari</label>
                            <input type="number" name="freeze_days" value="0" min="0" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Jam</label>
                            <input type="number" name="freeze_hours" value="1" min="0" max="23" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Detik</label>
                            <input type="number" name="freeze_seconds" value="0" min="0" max="59" class="w-full border border-slate-300 rounded-lg p-2 text-xs font-bold text-center">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Catatan Pelanggaran (Kecurangan):</label>
                        <textarea name="reason" rows="2" placeholder="Contoh: Menggunakan cheat, indikasi kecurangan transaksi" required class="w-full border border-slate-300 rounded-lg p-2 text-xs font-medium focus:outline-none focus:border-amber-500"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-lock"></i> Terapkan Pembekuan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#94a3b8',
            preConfirm: () => {
                document.getElementById('freezeForm').submit();
            }
        });
    }

    function confirmDeleteAllowed(formId) {
        Swal.fire({
            title: 'Hapus IP Whitelist?', text: "IP ini tidak akan bisa mengakses menu Keamanan System lagi!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    }

    function confirmDeleteLog(formId) {
        Swal.fire({
            title: 'Hapus Log IP?', text: "Catatan riwayat IP ini akan dihapus permanen!",
            icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    }
</script>
@endpush
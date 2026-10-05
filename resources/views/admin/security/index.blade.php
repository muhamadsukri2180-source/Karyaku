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
                    @forelse($abnormalIps as $ipAddress => $logs)
                    @php
                        $first = $logs->sortByDesc('last_activity_at')->first();
                        $totalReq = $logs->sum('request_count');
                        $groupId = 'abnormal-'.$loop->index;
                        $userObj = $logs->first(fn($l) => $l->user !== null)?->user;
                        $userLabel = $userObj?->name ?? $userObj?->email;
                        if (!$userLabel) {
                            $loginHist = \App\Models\LoginHistory::where('ip_address', $ipAddress)->whereNotNull('username')->latest()->first();
                            if ($loginHist) { $userLabel = $loginHist->username; }
                        }
                    @endphp
                    <tr class="hover:bg-red-50/40 transition-colors bg-white odd:bg-slate-50/30 whitespace-nowrap">
                        <td class="py-4 px-6 font-mono font-bold text-red-600">
                            <div class="flex items-center gap-2">
                                <span>{{ $ipAddress }}</span>
                                <button type="button" onclick="document.getElementById('{{ $groupId }}').classList.toggle('hidden')" class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] hover:bg-red-200 cursor-pointer">
                                    {{ $logs->count() }} Sesi <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>
                            @if($userLabel)
                                <div class="text-[10px] font-sans font-medium text-red-800 mt-1 flex items-center gap-1"><i class="fa-solid fa-user text-[9px] mr-0.5"></i> <span>{{ $userLabel }}</span></div>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-700 font-semibold max-w-xs truncate">{{ $first->reason ?? '-' }}</td>
                        
                        <td class="py-4 px-6 font-mono text-[11px] text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="truncate max-w-[220px] bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200 font-bold">{{ $first->last_activity ?? 'N/A' }}</span>
                                <button type="button" class="btn-jailbreak-detail w-8 h-8 rounded-lg bg-red-100 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-xs flex items-center justify-center text-xs shrink-0 cursor-pointer"
                                    onclick="showJailbreakDetail('{{ $ipAddress }}', '{{ addslashes($first->last_activity) }}', '{{ addslashes($first->reason) }}', '{{ addslashes($first->user_agent) }}')"
                                    title="Lihat Lokasi File & Payload">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-center font-bold text-slate-700"><span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[11px] border border-red-200">{{ $totalReq }}x</span></td>
                        <td class="py-4 px-6 text-slate-500 font-medium">{{ $first->last_activity_at ? $first->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('admin.security.toggle', $first->id) }}" method="POST" class="inline-block">
                                @csrf
                                @if($first->status === 'abnormal')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                        <i class="fa-solid fa-check"></i> Buka Blokir
                                    </button>
                                @else
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                        <i class="fa-solid fa-ban"></i> Ban
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    
                    <!-- DROPDOWN SESSIONS -->
                    <tr id="{{ $groupId }}" class="hidden bg-slate-50/50">
                        <td colspan="6" class="p-0 border-b border-slate-200">
                            <table class="w-full text-left">
                                @foreach($logs as $log)
                                <tr class="border-t border-slate-200 hover:bg-slate-100 text-[11px]">
                                    <td class="py-3 px-10 text-slate-500 font-mono">Sesi: #{{ !empty($log->session_id) ? substr($log->session_id, 0, 8) : 'Main' }}</td>
                                    <td class="py-3 px-6 text-slate-600 truncate max-w-[150px]">{{ $log->reason }}</td>
                                    <td class="py-3 px-6 text-slate-600 font-mono truncate max-w-[150px]">{{ $log->last_activity }}</td>
                                    <td class="py-3 px-6 text-center text-slate-600 font-bold">{{ $log->request_count }}x</td>
                                    <td class="py-3 px-6 text-slate-500 font-medium">{{ $log->last_activity_at ? $log->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                                    <td class="py-3 px-6 text-center text-slate-400 italic">
                                        -
                                    </td>
                                </tr>
                                @endforeach
                            </table>
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
                    @forelse($normalIps as $ipAddress => $logs)
                    @php
                        $first = $logs->sortByDesc('last_activity_at')->first();
                        $totalReq = $logs->sum('request_count');
                        $groupId = 'normal-'.$loop->index;
                        $userObj = $logs->first(fn($l) => $l->user !== null)?->user;
                        $userLabel = $userObj?->name ?? $userObj?->email;
                        if (!$userLabel) {
                            $loginHist = \App\Models\LoginHistory::where('ip_address', $ipAddress)->whereNotNull('username')->latest()->first();
                            if ($loginHist) { $userLabel = $loginHist->username; }
                        }
                    @endphp
                    <tr class="hover:bg-amber-100/80 transition-colors bg-white odd:bg-amber-50/50 whitespace-nowrap">
                        <td class="py-4 px-6 font-mono font-bold text-amber-900">
                            <div class="flex items-center gap-2">
                                <span>{{ $ipAddress }}</span>
                                <button type="button" onclick="document.getElementById('{{ $groupId }}').classList.toggle('hidden')" class="px-2 py-0.5 rounded bg-amber-200 text-amber-800 text-[10px] hover:bg-amber-300 cursor-pointer">
                                    {{ $logs->count() }} Sesi <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>
                            @if($userLabel)
                                <div class="text-[10px] font-sans font-medium text-amber-800 mt-1 flex items-center gap-1"><i class="fa-solid fa-user text-[9px] mr-0.5"></i> <span>{{ $userLabel }}</span></div>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-mono text-[11px] text-amber-900 max-w-xs truncate">{{ $first->last_activity }}</td>
                        <td class="py-4 px-6 text-amber-800 max-w-xs truncate" title="{{ $first->user_agent }}">{{ $first->user_agent }}</td>
                        <td class="py-4 px-6 text-center font-bold text-amber-900">
                            <span class="bg-amber-200/80 px-2.5 py-1 rounded-md text-[11px] border border-amber-300 font-extrabold text-amber-950">{{ $totalReq }}x</span>
                        </td>
                        <td class="py-4 px-6 text-amber-900 font-semibold">{{ $first->last_activity_at ? $first->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                        <td class="py-4 px-6 text-center">
                            <!-- TOMBOL BEKUKAN AKSES UTAMA -->
                            <button type="button" onclick="openFreezeTimerModal('{{ $first->id }}', '{{ $ipAddress }}')" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold shadow-sm transition-all cursor-pointer">
                                <i class="fa-solid fa-key"></i> Kunci
                            </button>
                        </td>
                    </tr>
                    
                    <!-- DROPDOWN SESSIONS -->
                    <tr id="{{ $groupId }}" class="hidden bg-amber-50/40">
                        <td colspan="6" class="p-0 border-b border-amber-200">
                            <table class="w-full text-left">
                                @foreach($logs as $log)
                                <tr class="border-t border-amber-100 hover:bg-amber-100/80 text-[11px]">
                                    <td class="py-3 px-10 text-amber-700 font-mono">Sesi: #{{ !empty($log->session_id) ? substr($log->session_id, 0, 8) : 'Main' }}</td>
                                    <td class="py-3 px-6 text-amber-800 font-mono truncate max-w-[200px]">{{ $log->last_activity }}</td>
                                    <td class="py-3 px-6 text-amber-800 truncate max-w-[200px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td>
                                    <td class="py-3 px-6 text-center text-amber-800 font-bold">{{ $log->request_count }}x</td>
                                    <td class="py-3 px-6 text-amber-800 font-semibold">{{ $log->last_activity_at ? $log->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                                    <td class="py-3 px-6 text-center text-amber-600/80 italic">
                                        -
                                    </td>
                                </tr>
                                @endforeach
                            </table>
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
    <!-- TABEL ANTI BOT -->
    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl shadow-lg shadow-indigo-500/10 overflow-hidden">
        <div class="p-5 border-b border-indigo-100 bg-gradient-to-r from-indigo-500/10 via-white to-indigo-50 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500 text-white flex items-center justify-center border border-indigo-600 shadow-sm shrink-0">
                    <i class="fa-solid fa-robot text-sm"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-indigo-950 text-base font-display">Tabel Anti Bot & Serangan Otomatis</h3>
                    <p class="text-[11px] text-indigo-800/80 font-medium">Log aktivitas dari Bot atau serangan DDoS. Silakan klik "Blokir Manual" untuk membekukan IP tersebut.</p>
                </div>
            </div>
            <span class="bg-indigo-600 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">{{ $botIps->count() }} Bot Terdeteksi</span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-indigo-700 text-indigo-50 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                        <th class="py-3.5 px-6">Alamat IP Bot</th>
                        <th class="py-3.5 px-6">Tipe Serangan</th>
                        <th class="py-3.5 px-6">User Agent Palsu / Bot Name</th>
                        <th class="py-3.5 px-6 text-center">Spam Request</th>
                        <th class="py-3.5 px-6">Terakhir Menyerang</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-indigo-100 text-indigo-950 font-medium">
                    @forelse($botIps as $ipAddress => $logs)
                    @php
                        $first = $logs->sortByDesc('last_activity_at')->first();
                        $totalReq = $logs->sum('request_count');
                        $groupId = 'bot-'.$loop->index;
                    @endphp
                    <tr class="hover:bg-indigo-100/80 transition-colors bg-white odd:bg-indigo-50/50 whitespace-nowrap">
                        <td class="py-4 px-6 font-mono font-bold text-indigo-900">
                            <div class="flex items-center gap-2">
                                <span>{{ $ipAddress }}</span>
                                <button type="button" onclick="document.getElementById('{{ $groupId }}').classList.toggle('hidden')" class="px-2 py-0.5 rounded bg-indigo-200 text-indigo-800 text-[10px] hover:bg-indigo-300 cursor-pointer">
                                    {{ $logs->count() }} Sesi <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>
                            @if($first->user)
                                <div class="text-[10px] font-sans font-medium text-indigo-700 mt-1"><i class="fa-solid fa-user text-[9px] mr-1"></i> {{ $first->user->name ?? $first->user->email }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-indigo-800 max-w-xs truncate font-semibold"><span class="bg-indigo-100 px-2 py-1 rounded border border-indigo-200">{{ $first->reason }}</span></td>
                        <td class="py-4 px-6 text-indigo-700 max-w-xs truncate" title="{{ $first->user_agent }}">{{ $first->user_agent ?? 'Unknown/Empty' }}</td>
                        <td class="py-4 px-6 text-center font-bold">
                            <span class="bg-red-100 px-2.5 py-1 rounded-md text-[11px] border border-red-300 text-red-700">{{ $totalReq }}x Req</span>
                        </td>
                        <td class="py-4 px-6 text-indigo-600 font-semibold">{{ $first->last_activity_at ? $first->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('admin.security.toggle', $first->id) }}" method="POST" class="inline-block">
                                @csrf
                                @if($first->status === 'abnormal')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                        <i class="fa-solid fa-check"></i> Buka Blokir
                                    </button>
                                @else
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-[11px] font-extrabold shadow-sm transition-all cursor-pointer">
                                        <i class="fa-solid fa-ban"></i> Ban
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    
                    <!-- DROPDOWN SESSIONS -->
                    <tr id="{{ $groupId }}" class="hidden bg-indigo-50/40">
                        <td colspan="6" class="p-0 border-b border-indigo-200">
                            <table class="w-full text-left">
                                @foreach($logs as $log)
                                <tr class="border-t border-indigo-100 hover:bg-indigo-100/50 text-[11px]">
                                    <td class="py-3 px-10 text-indigo-700 font-mono">Sesi: #{{ !empty($log->session_id) ? substr($log->session_id, 0, 8) : 'Main' }}</td>
                                    <td class="py-3 px-6 text-indigo-800 font-semibold truncate max-w-[150px]">{{ $log->reason }}</td>
                                    <td class="py-3 px-6 text-indigo-700 truncate max-w-[150px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td>
                                    <td class="py-3 px-6 text-center text-red-700 font-bold">{{ $log->request_count }}x</td>
                                    <td class="py-3 px-6 text-indigo-600 font-semibold">{{ $log->last_activity_at ? $log->last_activity_at->format('d M Y, H:i:s') : '-' }}</td>
                                    <td class="py-3 px-6 text-center text-indigo-400 italic">
                                        -
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-indigo-900 text-xs font-semibold bg-indigo-50/20">
                            <i class="fa-solid fa-shield-virus text-indigo-400 text-xl block mb-2"></i>
                            Sistem bebas dari serangan Bot / DDoS saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL RIWAYAT LOGIN / LOGOUT -->
    <div class="bg-slate-50 border border-slate-200 rounded-2xl shadow-lg shadow-slate-500/10 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-slate-100 via-white to-slate-50 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-800 text-white flex items-center justify-center border border-slate-900 shadow-sm shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Tabel Riwayat Login & Logout Pengguna</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Memantau sesi akun, lokasi IP Login, dan waktu Logout secara real-time.</p>
                </div>
            </div>
            <span class="bg-slate-800 text-white text-[10px] px-3 py-1 rounded-full font-extrabold shadow-sm">10 Riwayat per Halaman</span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 border-b border-slate-200 text-[11px] uppercase tracking-wider font-bold whitespace-nowrap">
                        <th class="py-3.5 px-6">Username / Akun</th>
                        <th class="py-3.5 px-6">Status Aktivitas</th>
                        <th class="py-3.5 px-6">Alamat IP (Lokasi)</th>
                        <th class="py-3.5 px-6">Perangkat / Browser</th>
                        <th class="py-3.5 px-6">Waktu Kejadian (Tanggal & Waktu)</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($loginHistories as $log)
                    <tr class="hover:bg-slate-50 transition-colors bg-white whitespace-nowrap">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle text-slate-400"></i> {{ $log->username ?? 'Unknown' }}
                        </td>
                        <td class="py-4 px-6">
                            @if($log->type == 'login')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded border border-emerald-200 font-bold text-[10px] uppercase tracking-wider"><i class="fa-solid fa-right-to-bracket mr-1"></i> Sedang Login</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded border border-slate-200 font-bold text-[10px] uppercase tracking-wider"><i class="fa-solid fa-right-from-bracket mr-1"></i> Telah Logout</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-mono font-semibold text-slate-800">{{ $log->ip_address }}</td>
                        <td class="py-4 px-6 text-slate-500 max-w-xs truncate" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td>
                        <td class="py-4 px-6 text-slate-500 font-semibold">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-500 text-xs font-semibold bg-slate-50">
                            <i class="fa-solid fa-ghost text-slate-300 text-xl block mb-2"></i>
                            Belum ada riwayat aktivitas masuk/keluar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-white">
            {{ $loginHistories->links() }}
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



    function confirmDeleteLog(formId) {
        Swal.fire({
            title: 'Hapus Log IP?', text: "Catatan riwayat IP ini akan dihapus permanen!",
            icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    }
</script>
@endpush

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Karyaku - Tiket Bantuan Pengguna</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
            colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', skyDeeper: '#082C48', skyPale: '#EFF8FF', coral: '#FF7A59', mint: '#10B981', ink: '#0F2A44' }
        } }
    }
</script>
<style>
    .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(14, 165, 233, 0.5); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
    .modal-backdrop-custom { background: rgba(15, 23, 42, 0.55); }
</style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/50 to-blue-200/60 text-slate-800 font-sans antialiased min-h-screen">

<div class="flex min-h-screen relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
        <div class="p-6 border-b border-white/15 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg shadow-skyDeep/20"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                    <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">CS Panel</span>
                </div>
            </div>
            <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        @php
            $csUser = auth()->user();
            $csName = $csUser->name ?? 'CS';
            $csInitials = collect(explode(' ', trim($csName)))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
        @endphp
        <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
            <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ $csInitials ?: 'CS' }}</div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ $csName }}</p>
                <span class="text-[10px] text-sky-200">Customer Service</span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>

            <a href="{{ route('cs.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-chart-pie w-4 text-center group-hover:text-white transition-colors"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('cs.tiket') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all duration-200">
                <i class="fa-solid fa-headset w-4 text-center"></i><span>Tiket Bantuan</span>
            </a>

            <a href="{{ route('cs.laporan') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-triangle-exclamation w-4 text-center group-hover:text-white transition-colors"></i><span>Laporan & Moderasi</span>
            </a>

            <a href="{{ route('cs.transaksi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Cek Transaksi</span>
            </a>

            <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>

            <a href="{{ route('cs.notifikasi') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                <i class="fa-solid fa-bell w-4 text-center group-hover:text-white transition-colors"></i><span>Notifikasi</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/15">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                    <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-gradient-to-r from-sky-50 via-sky-100/70 to-blue-200/60 backdrop-blur-xl border-b border-sky-300/80 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div class="flex items-center gap-4">
                <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-300 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Tiket Bantuan Pengguna</h2>
                    <p class="text-[11px] sm:text-xs text-slate-700 font-semibold mt-0.5">Kelola pertanyaan dan bantuan langsung dari pembeli/pengguna.</p>
                </div>
            </div>
        </header>

        <div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</div>
            @endif

            <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b flex flex-wrap items-center justify-between gap-4">
                    <form action="{{ route('cs.tiket') }}" method="GET" class="relative w-full sm:w-72">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Subjek / Pengirim..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium focus:outline-none">
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b text-slate-500 text-[11px] uppercase font-bold">
                                <th class="py-3 px-5">Pengirim</th>
                                <th class="py-3 px-5">Subjek</th>
                                <th class="py-3 px-5">Pesan</th>
                                <th class="py-3 px-5">Status</th>
                                <th class="py-3 px-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-5 text-xs font-semibold">{{ $ticket->user->name ?? '-' }}</td>
                                <td class="py-3 px-5 text-xs font-bold text-slate-800">{{ $ticket->subject }}</td>
                                <td class="py-3 px-5 text-xs text-slate-600 max-w-xs truncate">{{ $ticket->message }}</td>
                                <td class="py-3 px-5">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'closed' => 'bg-slate-100 text-slate-600 border-slate-200'
                                        ];
                                    @endphp
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $statusClasses[$ticket->status] ?? 'bg-slate-50' }}">{{ strtoupper($ticket->status) }}</span>
                                </td>
                                <td class="py-3 px-5 text-center">
                                    <button type="button" onclick="openBalasModal('{{ $ticket->id }}')" class="px-3 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold"><i class="fa-solid fa-reply"></i> Respon</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-slate-400 text-xs font-semibold">Belum ada tiket bantuan masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tickets->hasPages())<div class="p-4 border-t">{{ $tickets->appends(request()->query())->links() }}</div>@endif
            </div>
        </div>
    </main>
</div>

<!-- MODAL BALAS TIKET -->
<div id="balasModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop-custom p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900">Tanggapi Tiket Bantuan</h3>
            <button type="button" onclick="closeBalasModal()" class="text-slate-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="balasForm" method="POST" class="p-5 space-y-4">
            @csrf
            <div id="ticketDetailBody" class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1">
                <p><strong>Pengirim:</strong> <span id="mUser">-</span></p>
                <p><strong>Subjek:</strong> <span id="mSubject">-</span></p>
                <p><strong>Pesan Pengguna:</strong> <span id="mMessage">-</span></p>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 uppercase">Ubah Status Tiket</label>
                <select name="status" id="mStatus" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                    <option value="in_progress">Dalam Proses (In Progress)</option>
                    <option value="resolved">Selesai (Resolved)</option>
                    <option value="closed">Ditutup (Closed)</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 uppercase">Pesan / Balasan CS</label>
                <textarea name="admin_note" id="mAdminNote" rows="4" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm" placeholder="Tuliskan jawaban atau penyelesaian masalah untuk pengguna..."></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl">Kirim Balasan</button>
        </form>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() { sidebar.classList.toggle('open'); sidebar.classList.toggle('closed'); sidebarOverlay.classList.toggle('hidden'); }
    sidebarToggleBtn?.addEventListener('click', toggleSidebar);
    sidebarCloseBtn?.addEventListener('click', toggleSidebar);
    sidebarOverlay?.addEventListener('click', toggleSidebar);

    const baseUrl = "{{ url('cs/tiket') }}";
    function openBalasModal(id) {
        const modal = document.getElementById('balasModal');
        const form = document.getElementById('balasForm');
        form.action = `${baseUrl}/${id}/balas`;

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('mUser').innerText = data.user?.name || '-';
                document.getElementById('mSubject').innerText = data.subject || '-';
                document.getElementById('mMessage').innerText = data.message || '-';
                document.getElementById('mStatus').value = data.status || 'in_progress';
                document.getElementById('mAdminNote').value = data.admin_note || '';
                modal.classList.remove('hidden'); modal.classList.add('flex');
            });
    }
    function closeBalasModal() {
        const modal = document.getElementById('balasModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }
</script>
</body>
</html>
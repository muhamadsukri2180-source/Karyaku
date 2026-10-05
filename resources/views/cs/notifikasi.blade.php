@extends('layouts.cs')

@section('title', 'Karyaku - Notifikasi')
@section('header_title', 'Notifikasi dari Admin')
@section('header_subtitle', 'Pengumuman & instruksi terbaru yang dikirim oleh Admin.')

@section('content')
<!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
    <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Notifikasi Diterima</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $notifications->total() }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md">
                <i class="fa-solid fa-bell text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Belum Dibaca</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $unreadCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md">
                <i class="fa-solid fa-envelope text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- MAIN TABLE AREA -->
<div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-extrabold text-slate-800">Daftar Notifikasi</h3>
            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Pengumuman broadcast dan notifikasi khusus untuk akun Anda.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Judul Notifikasi</th>
                    <th class="py-4 px-6">Sumber</th>
                    <th class="py-4 px-6">Deskripsi</th>
                    <th class="py-4 px-6">Tanggal Diterima</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse ($notifications as $notification)
                    @php($isNew = $notification->created_at && $notification->created_at->greaterThan(now()->subDays(3)))
                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                        <td class="py-3 px-6 font-bold text-slate-800 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-sm border border-sky-200 shadow-sm shrink-0">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>{{ $notification->name }}</span>
                                    @if($isNew)<span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-red-50 text-red-600 border border-red-200">Baru</span>@endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-xs">
                            @if(is_null($notification->user_id))
                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-600 border border-sky-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                    <i class="fa-solid fa-users"></i> Broadcast Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 border border-purple-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                    <i class="fa-solid fa-user"></i> Khusus Akun Anda
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $notification->description }}</td>
                        <td class="py-3 px-6 text-xs font-semibold text-slate-500">
                            {{ $notification->created_at ? $notification->created_at->translatedFormat('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        onclick='openDetailModal(@json($notification))'
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center"
                                        title="Lihat Detail Notifikasi">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-semibold">Belum ada notifikasi dari Admin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notifications->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

@push('modals')
<!-- MODAL DETAIL NOTIFIKASI (READ-ONLY) -->
<div id="detailNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-600" id="detailModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-bell text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Detail Notifikasi</h3>
                    <p class="text-[10px] font-semibold text-blue-600">Informasi lengkap notifikasi yang diterima.</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div class="p-5 space-y-4">
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Sumber</label>
                <p id="detailTarget" class="mt-1 text-sm font-bold text-slate-800">-</p>
            </div>
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Judul Notifikasi</label>
                <p id="detailTitle" class="mt-1 text-sm font-bold text-slate-800">-</p>
            </div>
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Deskripsi</label>
                <p id="detailDescription" class="mt-1 text-sm text-slate-600 whitespace-pre-line">-</p>
            </div>
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Tanggal Diterima</label>
                <p id="detailDate" class="mt-1 text-xs font-semibold text-slate-500">-</p>
            </div>

            <div class="pt-2">
                <button type="button" onclick="closeDetailModal()" class="w-full py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const detailModal = document.getElementById('detailNotificationModal');
    const detailModalContent = document.getElementById('detailModalContent');

    function openDetailModal(notification) {
        if (notification) {
            document.getElementById('detailTitle').textContent = notification.name || '-';
            document.getElementById('detailDescription').textContent = notification.description || '-';
            document.getElementById('detailTarget').textContent = notification.user_id
                ? 'Khusus Akun Anda'
                : 'Broadcast Admin (Semua Pengguna)';
            document.getElementById('detailDate').textContent = notification.created_at
                ? new Date(notification.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })
                : '-';
        }

        detailModal.classList.remove('hidden');
        setTimeout(() => {
            detailModal.classList.remove('opacity-0');
            detailModalContent.classList.remove('scale-95');
            detailModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeDetailModal() {
        detailModal.classList.add('opacity-0');
        detailModalContent.classList.remove('scale-100');
        detailModalContent.classList.add('scale-95');
        setTimeout(() => { detailModal.classList.add('hidden'); }, 300);
    }
</script>
@endpush

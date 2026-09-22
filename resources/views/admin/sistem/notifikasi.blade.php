@extends('layouts.admin')

@section('title', 'Manajemen Notifikasi')
@section('header_title', 'Manajemen Notifikasi')
@section('header_subtitle', 'Kirim pengumuman ke semua pengguna atau ke pengguna tertentu.')

@section('content')
<div class="p-6 sm:p-8 space-y-6">

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
        <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Notifikasi Terkirim</span>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ $notifications->total() }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md">
                    <i class="fa-solid fa-bell text-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Total Pengguna Terdaftar</span>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ ($allUsers ?? collect())->count() }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE AREA -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Daftar Notifikasi Terkirim</h3>
                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Riwayat pengumuman yang sudah dikirim ke pengguna.</p>
            </div>

            <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                <i class="fa-solid fa-paper-plane"></i> Kirim Notifikasi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Judul Notifikasi</th>
                        <th class="py-4 px-6">Target</th>
                        <th class="py-4 px-6">Deskripsi</th>
                        <th class="py-4 px-6">Tanggal Dibuat</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse ($notifications as $notification)
                        <tr class="hover:bg-slate-50 transition-colors bg-white">
                            <td class="py-3 px-6 font-bold text-slate-800 text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-sm border border-sky-200 shadow-sm shrink-0">
                                        <i class="fa-solid fa-bullhorn"></i>
                                    </div>
                                    <span>{{ $notification->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-xs">
                                @if(is_null($notification->user_id))
                                    <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-600 border border-sky-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                        <i class="fa-solid fa-users"></i> Semua Pengguna
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 border border-purple-200 px-2 py-1 rounded-lg font-bold text-[10px]">
                                        <i class="fa-solid fa-user"></i> {{ $notification->targetUser->name ?? 'Pengguna Dihapus' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-xs text-slate-600 max-w-xs truncate">{{ $notification->description }}</td>
                            <td class="py-3 px-6 text-xs font-semibold text-slate-500">
                                {{ $notification->created_at ? $notification->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- TOMBOL DETAIL (READ-ONLY, tidak ada endpoint update) -->
                                    <button type="button"
                                            onclick='openDetailModal(@json($notification))'
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center"
                                            title="Lihat Detail Notifikasi">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <!-- TOMBOL HAPUS -->
                                    <form action="{{ route('admin.notifikasi.delete', $notification->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this, '{{ $notification->name }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center" title="Hapus Notifikasi">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-semibold">Belum ada data notifikasi yang tersedia.</td>
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

</div>

<!-- MODAL KIRIM NOTIFIKASI -->
<div id="addNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-emerald-500 max-h-[90vh] overflow-y-auto" id="addModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50 rounded-t-xl sticky top-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-paper-plane text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Kirim Notifikasi Baru</h3>
                    <p class="text-[10px] font-semibold text-emerald-600">Buat pengumuman baru untuk pengguna.</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="addForm" action="{{ route('admin.notifikasi.send') }}" method="POST" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Target Penerima</label>
                <div class="mt-2 grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="target_type" value="semua" checked onchange="toggleTargetUser()" class="target-radio sr-only peer">
                        <span class="flex items-center justify-center gap-2 border-2 border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                            <i class="fa-solid fa-users"></i> Semua Pengguna
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="target_type" value="tertentu" onchange="toggleTargetUser()" class="target-radio sr-only peer">
                        <span class="flex items-center justify-center gap-2 border-2 border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                            <i class="fa-solid fa-user"></i> Pengguna Tertentu
                        </span>
                    </label>
                </div>
            </div>

            <div id="userSelectWrapper" class="hidden">
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Pilih Pengguna</label>
                <select name="user_id" id="add_user_id" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
                    <option value="">Pilih Pengguna</option>
                    @foreach(($allUsers ?? collect()) as $u)
                        @php
                            $uEmail = (!empty($u->email) && !str_starts_with($u->email, '$') && str_contains($u->email, '@')) ? ' — ' . $u->email : (!empty($u->phone) ? ' — ' . $u->phone : '');
                        @endphp
                        <option value="{{ $u->id_user }}">{{ $u->name }} ({{ $u->role->role_name ?? '-' }}){{ $uEmail }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Judul Notifikasi</label>
                <input type="text" name="title" id="add_title" placeholder="Contoh: Pemeliharaan Sistem..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
            </div>
            <div>
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Deskripsi</label>
                <textarea name="description" id="add_description" rows="4" placeholder="Tuliskan isi pengumuman notifikasi di sini..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all"></textarea>
            </div>

            <div class="pt-2">
                <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#065f46] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#065f46] transition-all cursor-pointer">
                    Kirim Notifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL NOTIFIKASI (READ-ONLY) -->
<div id="detailNotificationModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-600" id="detailModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-bell text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Detail Notifikasi</h3>
                    <p class="text-[10px] font-semibold text-blue-600">Informasi lengkap notifikasi terkirim.</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div class="p-5 space-y-4">
            <div>
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Target Penerima</label>
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
                <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Tanggal Dibuat</label>
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
@endsection

@push('scripts')
<script>
    // --- KIRIM NOTIFIKASI MODAL LOGIC ---
    const addModal = document.getElementById('addNotificationModal');
    const addModalContent = document.getElementById('addModalContent');

    function toggleTargetUser() {
        const isTertentu = document.querySelector('input[name="target_type"]:checked').value === 'tertentu';
        document.getElementById('userSelectWrapper').classList.toggle('hidden', !isTertentu);
        if (!isTertentu) document.getElementById('add_user_id').value = '';
    }

    function openAddModal() {
        document.getElementById('addForm').reset();
        document.getElementById('userSelectWrapper').classList.add('hidden');
        addModal.classList.remove('hidden');
        setTimeout(() => {
            addModal.classList.remove('opacity-0');
            addModalContent.classList.remove('scale-95');
            addModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeAddModal() {
        addModal.classList.add('opacity-0');
        addModalContent.classList.remove('scale-100');
        addModalContent.classList.add('scale-95');
        setTimeout(() => { addModal.classList.add('hidden'); }, 300);
    }

    function submitAdd() {
        const targetType = document.querySelector('input[name="target_type"]:checked').value;
        const userId = document.getElementById('add_user_id').value;
        const title = document.getElementById('add_title').value.trim();
        const desc = document.getElementById('add_description').value.trim();

        if (!title || !desc) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan isi judul dan deskripsi notifikasi.', confirmButtonColor: '#0EA5E9' });
            return;
        }
        if (targetType === 'tertentu' && !userId) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih pengguna tujuan notifikasi.', confirmButtonColor: '#0EA5E9' });
            return;
        }
        document.getElementById('addForm').submit();
    }

    // --- DETAIL MODAL LOGIC ---
    const detailModal = document.getElementById('detailNotificationModal');
    const detailModalContent = document.getElementById('detailModalContent');

    function openDetailModal(notification) {
        if (notification) {
            document.getElementById('detailTitle').textContent = notification.name || '-';
            document.getElementById('detailDescription').textContent = notification.description || '-';

            if (notification.user_id) {
                document.getElementById('detailTarget').textContent = notification.target_user
                    ? notification.target_user.name
                    : 'Pengguna Dihapus';
            } else {
                document.getElementById('detailTarget').textContent = 'Semua Pengguna (Broadcast)';
            }

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

    // --- CONFIRM DELETE ---
    function confirmDelete(button, name) {
        Swal.fire({
            title: 'Hapus Notifikasi?',
            text: `Notifikasi "${name}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush

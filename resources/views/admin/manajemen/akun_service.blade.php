@extends('layouts.admin')

@section('title', 'Manajemen Akun & Layanan CS')
@section('header_title', 'Manajemen Akun & Layanan CS')
@section('header_subtitle', 'Kelola akun Customer Service serta tinjau keluhan dan masukan pengguna.')

@section('content')
<div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Keluhan Selesai</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['selesai'], 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-circle-check text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Tiket telah ditindaklanjuti</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Sedang Proses</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['proses'], 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-spinner text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Sedang ditangani petugas CS</p>
        </div>

        <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Belum Diproses</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['belum'], 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-clock text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-red-200/50 pt-2 mt-2">Menunggu tindak lanjut</p>
        </div>
    </div>

    <!-- SECTION 1: TABEL AKUN PETUGAS CS -->
    <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
            <h3 class="font-extrabold text-slate-900 text-base font-display">Daftar Akun Petugas Customer Service</h3>

            <!-- TOMBOL 3D BIRU KOKOH -->
            <button type="button" onclick="openCsModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                <i class="fa-solid fa-user-plus"></i> Tambah Akun CS
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Informasi Petugas</th>
                        <th class="py-4 px-6">Peran</th>
                        <th class="py-4 px-6">Status Bertugas</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-sky-100/70">
                    @forelse($csUsers as $cs)
                        @php
                            $initialsCs = collect(explode(' ', trim($cs->name)))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                            $isCsActive = $cs->status === 'active';
                        @endphp
                        <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $cs->avatar ? asset('storage/' . $cs->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($cs->name) . '&background=E0F2FE&color=0369A1&bold=true' }}" alt="{{ $cs->name }}" class="w-9 h-9 rounded-full object-cover border border-sky-200 shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">{{ $cs->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-medium">@safeEmail($cs->email)</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 border-indigo-200 px-2 py-1 rounded-md shadow-sm border">
                                    Customer Service
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold {{ $isCsActive ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isCsActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span> {{ $isCsActive ? 'Aktif Bertugas' : 'Off / Tidak Bertugas' }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="openEditCsModal('{{ $cs->id_user }}', '{{ addslashes($cs->name) }}', '{{ addslashes($cs->email) }}', '{{ $cs->status }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Edit CS">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.manajemen.akun_service.destroy', $cs->id_user) }}" method="POST" id="del-cs-{{ $cs->id_user }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDeleteCs('del-cs-{{ $cs->id_user }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Hapus Akun">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm text-slate-500">Belum ada akun Customer Service terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: TABEL KELUHAN & MASUKAN PENGGUNA -->
    <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 bg-white/50 backdrop-blur-sm">
            <h3 class="font-extrabold text-slate-900 text-base font-display">Tinjauan Keluhan & Masukan Pengguna (Customer Tickets)</h3>
            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Pantau dan tindak lanjuti setiap keluhan yang masuk dari pengguna.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pengguna</th>
                        <th class="py-4 px-6">Subjek & Pesan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Proses & Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-sky-100/70">
                    @forelse($tickets as $ticket)
                        @php
                            $ticketStatusColor = match($ticket->status) {
                                'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'proses' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'belum' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                            $ticketStatusLabel = match($ticket->status) {
                                'selesai' => 'Selesai',
                                'proses' => 'Proses',
                                'belum' => 'Belum',
                                default => ucfirst($ticket->status),
                            };
                            $initialsTicket = collect(explode(' ', trim($ticket->user->name ?? 'Anonim')))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                        @endphp
                        <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm shrink-0">{{ $initialsTicket ?: '??' }}</div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">{{ $ticket->user->name ?? 'Anonim' }}</p>
                                        @if(!empty($ticket->user?->email) && !str_starts_with($ticket->user->email, '$') && str_contains($ticket->user->email, '@'))
                                            <p class="text-[10px] text-slate-500 font-medium">{{ $ticket->user->email }}</p>
                                        @elseif(!empty($ticket->user?->phone))
                                            <p class="text-[10px] text-slate-500 font-medium">{{ $ticket->user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6 max-w-xs">
                                <p class="font-bold text-slate-800 text-xs">{{ $ticket->subject }}</p>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $ticket->message }}</p>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-[10px] font-bold {{ $ticketStatusColor }} px-2.5 py-1 rounded-md flex items-center w-max gap-1.5 border">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $ticketStatusLabel }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <form action="{{ route('admin.manajemen.ticket.update', $ticket->id) }}" method="POST" class="flex flex-wrap items-center gap-2 justify-center">
                                    @csrf @method('PUT')
                                    <select name="status" class="border border-sky-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 bg-white">
                                        <option value="belum" {{ $ticket->status == 'belum' ? 'selected' : '' }}>Belum</option>
                                        <option value="proses" {{ $ticket->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    <input type="text" name="admin_note" value="{{ $ticket->admin_note }}" placeholder="Catatan respon..." class="border border-sky-200 rounded-xl px-3 py-1.5 text-xs w-40 text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 bg-white">
                                    <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm text-slate-500">Belum ada keluhan pengguna yang masuk ke sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('modals')
<!-- MODAL: TAMBAH AKUN CS -->
<div id="csModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 mx-4 overflow-hidden" id="csModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center"><i class="fa-solid fa-user-plus text-sm"></i></div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Tambah Akun Customer Service</h3>
            </div>
            <button type="button" onclick="closeCsModal()" class="text-slate-400 hover:text-red-500 transition-colors w-7 h-7 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('admin.manajemen.akun_service.store') }}" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-700">Nama Petugas</label>
                <input type="text" name="name" required placeholder="Nama lengkap petugas..." class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700">Email Akun</label>
                <input type="email" name="email" required placeholder="cs@karyaku.com" class="mt-1 w-full border border-sky-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" name="password" id="addPassword" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full border border-sky-200 rounded-xl px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                    <button type="button" onclick="togglePassword('addPassword', 'eyeIconAdd')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-sky transition focus:outline-none cursor-pointer">
                        <i class="fa-solid fa-eye text-sm" id="eyeIconAdd"></i>
                    </button>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 pb-2">
                <button type="button" onclick="closeCsModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">Simpan Akun CS</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT AKUN CS -->
<div id="editCsModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 w-screen h-screen">
    <div id="editCsModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl transform scale-95 transition-transform duration-300 border border-sky-100">
        <div class="flex items-center justify-between pb-4 border-b border-sky-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm"><i class="fa-solid fa-pen-to-square"></i></div>
                <h4 class="font-extrabold text-slate-900 text-base font-display">Edit Akun Customer Service</h4>
            </div>
            <button type="button" onclick="closeEditCsModal()" class="text-slate-400 hover:text-slate-600 text-sm"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editCsForm" action="" method="POST" class="space-y-4 mt-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Petugas <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="editCsName" required class="w-full px-3.5 py-2.5 rounded-xl border border-sky-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Petugas CS <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="editCsEmail" required class="w-full px-3.5 py-2.5 rounded-xl border border-sky-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin merubah" class="w-full px-3.5 py-2.5 rounded-xl border border-sky-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Bertugas <span class="text-red-500">*</span></label>
                <select name="status" id="editCsStatus" required class="w-full px-3.5 py-2.5 rounded-xl border border-sky-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500">
                    <option value="active">Aktif Bertugas</option>
                    <option value="blocked">Off / Tidak Bertugas</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeEditCsModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Fungsi Show/Hide Password
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    const csModal = document.getElementById('csModal');
    const csModalContent = document.getElementById('csModalContent');

    function openCsModal() {
        csModal.classList.remove('hidden');
        setTimeout(() => {
            csModal.classList.remove('opacity-0');
            csModalContent.classList.remove('scale-95');
            csModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeCsModal() {
        csModal.classList.add('opacity-0');
        csModalContent.classList.remove('scale-100');
        csModalContent.classList.add('scale-95');
        setTimeout(() => { csModal.classList.add('hidden'); }, 300);
    }

    const editCsModal = document.getElementById('editCsModal');
    const editCsModalContent = document.getElementById('editCsModalContent');

    function openEditCsModal(id, name, email, status) {
        document.getElementById('editCsForm').action = '/admin/manajemen/akun-service/' + id;
        document.getElementById('editCsName').value = name;
        document.getElementById('editCsEmail').value = (email && !email.startsWith('$') && email.includes('@')) ? email : '';
        document.getElementById('editCsStatus').value = status;

        editCsModal.classList.remove('hidden');
        setTimeout(() => {
            editCsModal.classList.remove('opacity-0');
            editCsModalContent.classList.remove('scale-95');
            editCsModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeEditCsModal() {
        editCsModal.classList.add('opacity-0');
        editCsModalContent.classList.remove('scale-100');
        editCsModalContent.classList.add('scale-95');
        setTimeout(() => { editCsModal.classList.add('hidden'); }, 300);
    }

    function confirmDeleteCs(formId) {
        Swal.fire({
            title: 'Hapus Akun CS?',
            text: "Petugas tidak akan dapat masuk kembali ke sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById(formId).submit(); }
        });
    }
</script>
@endpush
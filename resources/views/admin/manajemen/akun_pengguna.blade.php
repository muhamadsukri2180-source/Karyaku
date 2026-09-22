@extends('layouts.admin')

@section('title', 'Akun Pengguna')
@section('header_title', 'Akun Pengguna')
@section('header_subtitle', 'Kelola seluruh data kreator dan pembeli di Karyaku (Suspend & Hapus).')

@section('content')
<div class="p-6 sm:p-8 space-y-6 overflow-y-auto no-scrollbar">

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100/60 border-l-4 border-blue-500 border-y border-r border-blue-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">Total Pengguna</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUsers, 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/30"><i class="fa-solid fa-users text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-blue-200/50 pt-2 mt-2">Seluruh pengguna terdaftar</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Kreator Aktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($activeCreators, 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-user-tie text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Memiliki minimal 1 produk</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Pengguna Baru</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($newThisMonth, 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-user-plus text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Bergabung bulan ini</p>
        </div>

        <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Akun Diblokir</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($blockedUsers, 0, ',', '.') }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-user-slash text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-600 font-medium border-t border-red-200/50 pt-2 mt-2">Melanggar kebijakan</p>
        </div>
    </div>

    <!-- MAIN TABLE AREA -->
    <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
            <form method="GET" action="{{ route('admin.users') }}" class="relative w-full sm:flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. telepon pengguna..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Informasi Akun</th>
                        <th class="py-4 px-6">Peran (Role)</th>
                        <th class="py-4 px-6">Tgl Bergabung</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-sky-100/70">
                    @forelse($users as $user)
                        @php
                            $initialsRow = collect(explode(' ', trim($user->name)))->map(fn($w)=>mb_strtoupper(mb_substr($w,0,1)))->take(2)->implode('');
                            $roleName = $user->role->role_name ?? '-';
                            $statusColor = match($user->status) {
                                'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'inactive' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'blocked' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                            $isBlocked = $user->status === 'blocked';
                        @endphp
                        <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=E0F2FE&color=0369A1&bold=true' }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-sky-200 shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">{{ $user->name }}</p>
                                        @if(!empty($user->phone))
                                            <p class="text-[10px] text-slate-500 font-medium"><i class="fa-solid fa-phone text-[9px] mr-1 text-slate-400"></i>{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-[11px] font-bold {{ $roleName === 'penjual' ? 'text-indigo-700 bg-indigo-50 border-indigo-200' : 'text-slate-600 bg-slate-100 border-slate-200' }} px-2 py-1 rounded-md shadow-sm border">
                                    {{ $roleName === 'penjual' ? 'Kreator' : ($roleName === 'pembeli' ? 'Pembeli' : ucfirst($roleName)) }}
                                </span>
                            </td>
                            <td class="py-3 px-6"><p class="text-xs font-semibold text-slate-700">{{ $user->created_at->translatedFormat('d M Y') }}</p></td>
                            <td class="py-3 px-6">
                                <span class="text-[10px] font-bold {{ $statusColor }} px-2.5 py-1 rounded-md flex items-center w-max gap-1.5 border">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $user->status === 'active' ? 'Aktif' : ($user->status === 'blocked' ? 'Ditangguhkan' : 'Nonaktif') }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- TOMBOL SUSPEND / AKTIFKAN -->
                                    <button type="button"
                                        class="btn-suspend-user w-8 h-8 rounded-lg {{ $isBlocked ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-600' : 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-600' }} border hover:text-white transition-all shadow-sm flex items-center justify-center"
                                        data-id="{{ $user->id_user }}"
                                        data-name="{{ $user->name }}"
                                        data-status="{{ $user->status }}"
                                        title="{{ $isBlocked ? 'Aktifkan Kembali' : 'Suspend Pengguna' }}">
                                        <i class="fa-solid {{ $isBlocked ? 'fa-lock-open' : 'fa-ban' }} text-xs"></i>
                                    </button>

                                    <!-- TOMBOL HAPUS -->
                                    <button type="button"
                                        class="btn-delete-user w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center"
                                        data-id="{{ $user->id_user }}"
                                        data-name="{{ $user->name }}"
                                        title="Hapus Pengguna">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-5 border-t border-sky-100 bg-white/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // === SUSPEND / AKTIFKAN PENGGUNA ===
    document.querySelectorAll('.btn-suspend-user').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const isBlocked = btn.dataset.status === 'blocked';

            if (isBlocked) {
                Swal.fire({
                    title: 'Aktifkan Pengguna?',
                    text: `Akun "${name}" akan diaktifkan kembali dan bisa login seperti biasa.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Aktifkan!',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                        confirmButton: 'px-6 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md mx-1 cursor-pointer',
                        cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitSuspendForm(id, 0, 0, 0, '');
                    }
                });
            } else {
                // Tampilkan form modal durasi (Hari, Jam, Menit) + Alasan
                Swal.fire({
                    title: `<span class="font-display font-extrabold text-slate-800 text-xl">Suspend Pengguna "${name}"</span>`,
                    html: `
                        <p class="text-xs font-semibold text-slate-500 mb-4 text-left">Tentukan durasi penonaktifan sementara akun serta alasan pemblokiran:</p>
                        
                        <div class="space-y-4 text-left">
                            <div>
                                <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide block mb-1">Durasi Pemblokiran</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Hari</span>
                                        <input id="swal_suspend_days" type="number" min="0" value="1" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Jam</span>
                                        <input id="swal_suspend_hours" type="number" min="0" max="23" value="0" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-500 block mb-0.5">Menit</span>
                                        <input id="swal_suspend_minutes" type="number" min="0" max="59" value="0" placeholder="0" class="w-full text-center border-2 border-amber-200 rounded-xl px-2 py-2 text-sm font-bold text-slate-800 focus:border-amber-500 focus:outline-none transition">
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 font-medium">*Kosongkan/set 0 semua jika ingin blokir permanen.</p>
                            </div>

                            <div>
                                <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide block mb-1">Alasan Pemblokiran <span class="text-red-500">*</span></label>
                                <textarea id="swal_suspend_reason" rows="3" placeholder="Contoh: Mengunggah karya tanpa izin hak cipta / penipuan transaksi..." class="w-full border-2 border-amber-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-amber-500 focus:outline-none transition"></textarea>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Suspend!',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-md bg-white',
                        confirmButton: 'px-6 py-2.5 bg-amber-500 text-white font-bold text-sm rounded-xl hover:bg-amber-600 transition-all shadow-md mx-1 cursor-pointer',
                        cancelButton: 'px-6 py-2.5 bg-slate-500 text-white font-bold text-sm rounded-xl hover:bg-slate-600 transition-all shadow-md mx-1 cursor-pointer'
                    },
                    preConfirm: () => {
                        const days = parseInt(document.getElementById('swal_suspend_days').value) || 0;
                        const hours = parseInt(document.getElementById('swal_suspend_hours').value) || 0;
                        const minutes = parseInt(document.getElementById('swal_suspend_minutes').value) || 0;
                        const reason = document.getElementById('swal_suspend_reason').value.trim();

                        if (!reason) {
                            Swal.showValidationMessage('Alasan pemblokiran wajib diisi!');
                            return false;
                        }

                        return { days, hours, minutes, reason };
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        submitSuspendForm(
                            id,
                            result.value.days,
                            result.value.hours,
                            result.value.minutes,
                            result.value.reason
                        );
                    }
                });
            }
        });
    });

    function submitSuspendForm(id, days, hours, minutes, reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/users') }}/${id}/suspend`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        const daysInput = document.createElement('input');
        daysInput.type = 'hidden';
        daysInput.name = 'suspend_days';
        daysInput.value = days;
        form.appendChild(daysInput);

        const hoursInput = document.createElement('input');
        hoursInput.type = 'hidden';
        hoursInput.name = 'suspend_hours';
        hoursInput.value = hours;
        form.appendChild(hoursInput);

        const minutesInput = document.createElement('input');
        minutesInput.type = 'hidden';
        minutesInput.name = 'suspend_minutes';
        minutesInput.value = minutes;
        form.appendChild(minutesInput);

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'suspend_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);

        document.body.appendChild(form);
        form.submit();
    }

    // === HAPUS PENGGUNA ===
    document.querySelectorAll('.btn-delete-user').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: `Anda akan menghapus "${name}" secara permanen. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/users') }}/${id}`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@extends('layouts.admin')

@section('title', 'Karyaku - Penarikan Saldo')

@section('header_title', 'Penarikan Saldo (Withdraw)')
@section('header_subtitle', 'Kelola dan setujui permintaan pencairan dana dari kreator.')

@section('content')

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Menunggu Diproses</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $menungguDiproses }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-clock-rotate-left text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Selesai (Bulan Ini)</span><div class="text-3xl font-black text-slate-900 mt-1">Rp {{ number_format($selesaiBulanIni, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-money-bill-transfer text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Gagal / Ditolak</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $gagalDitolak }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md shadow-red-500/30"><i class="fa-solid fa-building-columns text-lg"></i></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <form action="{{ route('admin.withdrawals') }}" method="GET" class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID WD / Nama..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Tanggal</th>
                                    <th class="py-4 px-6">Kreator &amp; Rekening Tujuan</th>
                                    <th class="py-4 px-6">Nominal Penarikan</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi (Setujui/Tolak)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse ($withdrawals as $withdrawal)
                                @php $wdId = $withdrawal->id_withdrawal ?? $withdrawal->id; @endphp
                                <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                    <td class="py-3 px-6">
                                        <p class="text-xs text-slate-600 font-medium">{{ $withdrawal->created_at->translatedFormat('d M Y, H:i') }}</p>
                                    </td>
                                    <td class="py-3 px-6">
                                        <p class="text-xs font-bold text-slate-800">{{ $withdrawal->user->name ?? '-' }}</p>
                                        <p class="text-[10px] font-semibold text-slate-500 mt-0.5"><i class="fa-solid fa-building-columns mr-1"></i> {{ $withdrawal->bank_name ?? '-' }} - {{ $withdrawal->account_number ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 px-6 text-xs font-bold text-emerald-600">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-6">
                                        @if ($withdrawal->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fa-solid fa-clock text-[9px]"></i> Menunggu
                                            </span>
                                        @elseif ($withdrawal->status === 'processed')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-[9px]"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                                <i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        @if ($withdrawal->status === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Form Proses (submit via JS) --}}
                                            <form id="formProcess-{{ $wdId }}" action="{{ route('admin.withdrawals.process', $wdId) }}" method="POST" class="hidden">
                                                @csrf
                                            </form>
                                            <button type="button"
                                                onclick="confirmProcess('{{ $wdId }}', '{{ addslashes($withdrawal->user->name ?? '-') }}', 'Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}')"
                                                class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold shadow-sm">
                                                <i class="fa-solid fa-check mr-1"></i> Proses
                                            </button>
                                            {{-- Tombol Tolak membuka modal --}}
                                            <button type="button"
                                                onclick="openRejectModal('{{ $wdId }}', '{{ addslashes($withdrawal->user->name ?? '-') }}', 'Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}')"
                                                class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm">
                                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                            </button>
                                        </div>
                                        @else
                                            <p class="text-center text-[10px] text-slate-400 font-semibold">Sudah diproses</p>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-6 text-center text-xs text-slate-500 font-semibold">Belum ada data penarikan saldo.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($withdrawals->hasPages())
                    <div class="p-5 border-t border-sky-100">
                        {{ $withdrawals->links() }}
                    </div>
                    @endif
                </div>

@endsection

{{-- ======================= MODAL TOLAK PENARIKAN ======================= --}}
@push('modals')
<div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeRejectModal()"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <button type="button" onclick="closeRejectModal()"
            class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
            </div>
            <div>
                <h3 id="rejectModalTitle" class="text-sm font-extrabold text-slate-800">Tolak Penarikan Saldo</h3>
                <p class="text-[11px] text-slate-500 font-medium" id="rejectModalSubtitle">—</p>
            </div>
        </div>

        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5" for="rejectNotes">
                    Catatan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="notes" id="rejectNotes" rows="3" required
                    placeholder="Jelaskan alasan penolakan kepada penjual..."
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-400/30 focus:border-red-400 resize-none transition-all"></textarea>
                <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> Catatan ini akan dikirimkan sebagai notifikasi kepada penjual.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white text-xs font-bold shadow-md shadow-red-400/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

{{-- ======================= SCRIPTS ======================= --}}
@push('scripts')
<script>
    // Konfirmasi Proses Penarikan dengan SweetAlert2
    function confirmProcess(id, name, amount) {
        Swal.fire({
            title: 'Proses Penarikan Saldo?',
            html: `<div class="text-sm text-slate-600 leading-relaxed">Anda akan menyetujui dan memproses penarikan dari:<br><br><strong class="text-slate-800">${name}</strong><br>Nominal: <strong class="text-emerald-600">${amount}</strong></div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Ya, Proses Sekarang',
            cancelButtonText: '<i class="fa-solid fa-arrow-left mr-1"></i> Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            customClass: {
                popup: 'rounded-2xl font-sans',
                confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                cancelButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                title: 'text-base font-extrabold text-slate-800',
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formProcess-' + id).submit();
            }
        });
    }

    // Buka Modal Tolak Penarikan
    function openRejectModal(id, name, amount) {
        const baseRoute = @json(route('admin.withdrawals.reject', '__WD_ID__'));
        document.getElementById('rejectForm').action = baseRoute.replace('__WD_ID__', id);
        document.getElementById('rejectModalSubtitle').textContent = name + ' — ' + amount;
        document.getElementById('rejectNotes').value = '';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('rejectNotes').focus(), 100);
    }

    // Tutup Modal Tolak
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRejectModal();
    });

    // Toast SweetAlert2 untuk notifikasi setelah aksi
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: @json(session('success')),
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-2xl text-xs font-semibold shadow-lg',
                title: 'text-xs font-bold text-slate-800'
            }
        });
    });
    @endif

    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: @json(session('error')),
            showConfirmButton: false,
            timer: 5500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-2xl text-xs font-semibold shadow-lg',
                title: 'text-xs font-bold text-slate-800'
            }
        });
    });
    @endif
</script>
@endpush
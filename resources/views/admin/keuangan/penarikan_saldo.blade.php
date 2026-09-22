@extends('layouts.admin')

@section('title', 'Karyaku - Penarikan Saldo')

@section('header_title', 'Penarikan Saldo (Withdraw)')
@section('header_subtitle', 'Kelola dan setujui permintaan pencairan dana dari kreator.')

@section('content')

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-300 text-red-800 text-xs font-semibold px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                    </div>
                @endif

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
                                    <th class="py-4 px-6">Kreator & Rekening Tujuan</th>
                                    <th class="py-4 px-6">Nominal Penarikan</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi (Setujui/Tolak)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse ($withdrawals as $withdrawal)
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
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Menunggu</span>
                                        @elseif ($withdrawal->status === 'processed')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        @if ($withdrawal->status === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.withdrawals.process', $withdrawal->id_withdrawal ?? $withdrawal->id) }}" method="POST" onsubmit="return confirm('Proses penarikan ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold shadow-sm"><i class="fa-solid fa-check"></i> Proses</button>
                                            </form>
                                            <form action="{{ route('admin.withdrawals.reject', $withdrawal->id_withdrawal ?? $withdrawal->id) }}" method="POST" onsubmit="return confirm('Tolak penarikan ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all text-xs font-bold shadow-sm"><i class="fa-solid fa-xmark"></i> Tolak</button>
                                            </form>
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
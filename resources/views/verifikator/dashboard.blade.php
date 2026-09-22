@extends('layouts.verifikator')

@section('title', 'Karyaku - Dashboard Verifikator')
@section('header_title', 'Dashboard Verifikator')
@section('header_subtitle', 'Ringkasan statistik antrean & verifikasi pendaftaran penjual.')

@section('content')
<!-- 4 KPI RINGKASAN ANTREAN -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    
    <!-- Pending KTP -->
    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending KTP</p>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingKtp ?? 0 }}</h3>
            <a href="{{ route('verifikator.identitas') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Proses Identitas &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 border border-red-200 flex items-center justify-center text-xl shadow-sm">
            <i class="fa-solid fa-id-card-clip"></i>
        </div>
    </div>

    <!-- Pending Produk -->
    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending Produk</p>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingProduk ?? 0 }}</h3>
            <a href="{{ route('verifikator.produk') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Cek Produk Baru &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center text-xl shadow-sm">
            <i class="fa-solid fa-box-open"></i>
        </div>
    </div>

    <!-- Pending Pembayaran -->
    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pembayaran</p>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $pendingPembayaran ?? 0 }}</h3>
            <a href="{{ route('verifikator.pembayaran') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Cek Resi Transfer &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl shadow-sm">
            <i class="fa-solid fa-receipt"></i>
        </div>
    </div>

    <!-- Laporan Masuk -->
    <div class="bg-white border border-sky-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan Masuk</p>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-display">{{ $laporanMasuk ?? 0 }}</h3>
            <a href="{{ route('verifikator.laporan') }}" class="text-[11px] font-bold text-sky-600 hover:text-skyHover mt-2 inline-block">Tinjau Pengaduan &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 border border-rose-200 flex items-center justify-center text-xl shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
    </div>

</div>

<!-- STATISTIK & TABEL ANTREAN PENDAFTARAN PENJUAL -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kartu Performa Keputusan -->
    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="font-extrabold text-slate-900 text-base font-display">Statistik Verifikasi</h3>
            <p class="text-xs text-slate-500 mt-1">Total keputusan tindakan pengajuan yang telah diproses.</p>

            <div class="space-y-4 mt-6">
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Disetujui</span>
                        <span class="text-slate-800 font-extrabold">{{ $approvedCount ?? 0 }}</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ (($approvedCount ?? 0) + ($rejectedCount ?? 0)) > 0 ? (($approvedCount ?? 0) / (($approvedCount ?? 0) + ($rejectedCount ?? 0))) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-rose-500"><i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak</span>
                        <span class="text-slate-800 font-extrabold">{{ $rejectedCount ?? 0 }}</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ (($approvedCount ?? 0) + ($rejectedCount ?? 0)) > 0 ? (($rejectedCount ?? 0) / (($approvedCount ?? 0) + ($rejectedCount ?? 0))) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 mt-6 text-[11px] text-slate-500 font-medium">
            <i class="fa-solid fa-shield-halved text-sky-500 mr-1"></i> Tim Verifikator Karyaku
        </div>
    </div>

    <!-- Tabel Antrean Pendaftaran Penjual -->
    <div class="lg:col-span-2 bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 text-base font-display">Verifikasi Pendaftaran Penjual</h3>
                <span class="text-xs font-bold text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">
                    {{ $pending->total() ?? count($pending) }} Menunggu
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                            <th class="py-4 px-6">Nama & Email</th>
                            <th class="py-4 px-6">Paket</th>
                            <th class="py-4 px-6">Pembayaran</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-100">
                        @forelse ($pending as $registration)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-6">
                                <p class="font-bold text-slate-800 text-xs">{{ $registration->user->name ?? '-' }}</p>
                                <p class="text-[11px] text-slate-400 font-medium">@safeEmail($registration->user->email ?? '-')</p>
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-slate-700">
                                <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2.5 py-0.5 rounded-lg font-bold text-[10px]">
                                    {{ $registration->membership->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 font-bold text-slate-800">
                                Rp {{ number_format($registration->payment_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="bg-amber-50 text-amber-800 border border-amber-200/60 px-2.5 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[9px]"></i> {{ ucfirst($registration->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <a href="{{ route('verifikator.pendaftaran.show', $registration->id_identity_verification) }}"
                                   class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[11px] shadow-sm transition-all">
                                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Periksa
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400 font-semibold text-xs">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                                Belum ada pendaftaran penjual yang perlu diverifikasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINASI -->
        @if(method_exists($pending, 'hasPages') && $pending->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $pending->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
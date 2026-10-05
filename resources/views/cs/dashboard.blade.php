@extends('layouts.cs')

@section('title', 'Karyaku - Dashboard Customer Service')
@section('header_title', 'Dashboard Customer Service')
@section('header_subtitle', 'Pantau laporan masuk dan transaksi pengguna.')

@section('content')
<!-- TOP METRICS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

    <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider">Laporan Masuk</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalLaporanMasuk, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md">
                <i class="fa-solid fa-flag text-lg"></i>
            </div>
        </div>
        <span class="text-[10px] text-slate-600 font-medium">Menunggu ditangani</span>
    </div>

    <div class="bg-gradient-to-br from-emerald-50 via-emerald-100/60 to-teal-200/50 border-l-4 border-emerald-500 border border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Laporan Selesai</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($laporanSelesai, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md">
                <i class="fa-solid fa-check-double text-lg"></i>
            </div>
        </div>
        <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-emerald-200 px-2 py-0.5 rounded-md">Sudah ditindak</span>
    </div>

</div>

<!-- SECTION LAPORAN TERBARU -->
<div class="bg-gradient-to-br from-white via-sky-50/50 to-blue-100/50 border border-sky-200/80 p-6 rounded-2xl card-hover shadow-md">
    <div class="flex justify-between items-center mb-5">
        <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2"><i class="fa-solid fa-flag text-sky-600"></i> Laporan Terbaru</h3>
        <a href="{{ route('cs.laporan') }}" class="text-[11px] font-bold text-sky-700 hover:underline">Lihat Semua</a>
    </div>
    <div class="space-y-3">
        @forelse($recentReports as $report)
        <div class="flex items-center justify-between p-3.5 rounded-xl bg-white/70 border border-sky-100 shadow-sm hover:bg-white transition-all">
            <div>
                <p class="text-xs font-bold text-slate-800">{{ $report->reporter->name ?? '-' }} melapor {{ $report->reportedUser->name ?? ($report->product->title ?? 'sesuatu') }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $report->reason }}</p>
            </div>
            <span class="text-[9px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ optional($report->created_at)->diffForHumans() ?? '-' }}</span>
        </div>
        @empty
        <p class="text-xs text-slate-500 italic py-2">Belum ada laporan masuk.</p>
        @endforelse
    </div>
</div>
@endsection
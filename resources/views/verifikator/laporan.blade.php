@extends('layouts.verifikator')

@section('title', 'Laporan Pelanggaran & Pengaduan')
@section('header_title', 'Laporan Pelanggaran & Pengaduan')
@section('header_subtitle', 'Penanganan laporan pengaduan pelanggaran dari pengguna platform.')

@section('content')
<!-- SUB-TABS NAVIGATION -->
<div class="flex border-b border-sky-200 gap-4">
    <a href="{{ route('verifikator.laporan', ['tab' => 'pending']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock mr-1.5"></i> Laporan Masuk
    </a>
    <a href="{{ route('verifikator.laporan', ['tab' => 'history']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'history' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Kasus
    </a>
</div>

<!-- TABEL DATA -->
<div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
    <div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Pelapor</th>
                        <th class="py-4 px-6">Terlapor / Produk</th>
                        <th class="py-4 px-6">Kategori Pelanggaran</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-6 font-bold text-slate-800">{{ $report->reporter->name ?? '-' }}</td>
                        <td class="py-3.5 px-6 text-xs text-slate-700 font-semibold">{{ $report->product->title ?? $report->reportedUser->name ?? '-' }}</td>
                        <td class="py-3.5 px-6 font-bold text-rose-600 text-xs">{{ $report->reason ?? $report->category ?? 'Pelanggaran' }}</td>
                        <td class="py-3.5 px-6">
                            @if($report->status === 'resolved')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Selesai Ditindak</span>
                            @elseif($report->status === 'dismissed')
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-minus"></i> Diabaikan</span>
                            @else
                                <span class="bg-rose-100 text-rose-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <a href="{{ route('verifikator.laporan.show', $report->id_report ?? $report->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation"></i> Tinjau Kasus
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 font-semibold text-xs">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                            Tidak ada laporan pengaduan pada tab ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($reports, 'hasPages') && $reports->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection
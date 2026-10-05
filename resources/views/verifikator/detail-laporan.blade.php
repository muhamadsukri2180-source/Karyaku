@extends('layouts.verifikator')

@section('title', 'Tinjauan Laporan Pelanggaran')
@section('header_title', 'Tinjauan Laporan Pelanggaran')
@section('header_subtitle', 'Pemeriksaan detail aduan dan eksekusi tindakan disiplin moderator.')

@section('header_right')
<a href="{{ route('verifikator.laporan') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="space-y-6 max-w-4xl">

    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
        <h3 class="font-extrabold text-slate-900 font-display text-base pb-3 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Detail Kasus Pengaduan
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                <span class="text-slate-400 font-bold block uppercase text-[10px]">Pengirim Laporan (Pelapor)</span>
                <strong class="text-slate-800 text-sm block mt-0.5">{{ $report->reporter->name ?? '-' }}</strong>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                <span class="text-slate-400 font-bold block uppercase text-[10px]">Entitas Dilaporkan</span>
                <strong class="text-rose-600 text-sm block mt-0.5">{{ $report->product->title ?? $report->reportedUser->name ?? '-' }}</strong>
            </div>
        </div>

        <div>
            <span class="text-slate-400 font-bold block uppercase text-[10px] mb-1">Rincian Deskripsi Laporan</span>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed text-slate-700">
                {{ $report->description ?? $report->reason ?? 'Tidak ada rincian aduan.' }}
            </div>
        </div>
    </div>

    @if($report->status === 'pending')
    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
        <h4 class="font-extrabold text-slate-900 font-display text-sm">Eksekusi Disiplin Platform</h4>

        <form id="actionReportForm" action="{{ route('verifikator.laporan.action', $report->id_report ?? $report->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Keputusan Disiplin <span class="text-red-500">*</span></label>
                <select name="action" required class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold bg-slate-50 focus:outline-none focus:border-sky-400">
                    <option value="warning">⚠️ Kirim Teguran Resmi (Warning Notification)</option>
                    <option value="takedown">🚫 Takedown Produk / Suspend Sementara</option>
                    <option value="dismiss">🟢 Abaikan Laporan (Fitnah / Tidak Terbukti)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Catatan Admin / Moderator</label>
                <textarea name="note" placeholder="Tuliskan catatan pertimbangan tindakan..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-sky-400 bg-slate-50 min-h-[80px]"></textarea>
            </div>

            <button type="button" onclick="confirmActionReport()" class="w-full py-3 bg-skyHover hover:bg-skyDeep text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-gavel"></i> Eksekusi Keputusan Kasus
            </button>
        </form>
    </div>
    @else
    <div class="bg-slate-100 text-slate-700 border border-slate-200 rounded-2xl p-4 font-bold text-xs text-center">
        Kasus Laporan Berstatus: {{ strtoupper($report->status) }}
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function confirmActionReport() {
        Swal.fire({
            title: 'Eksekusi Keputusan?',
            text: "Tindakan disiplin akan diproses dan notifikasi dikirimkan ke pihak terkait.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0EA5E9',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Eksekusi!',
            cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) document.getElementById('actionReportForm').submit(); });
    }
</script>
@endpush
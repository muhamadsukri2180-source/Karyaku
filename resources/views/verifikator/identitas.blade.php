@extends('layouts.verifikator')

@section('title', 'Karyaku - Verifikasi Identitas (KTP)')
@section('header_title', 'Verifikasi Identitas (KTP)')
@section('header_subtitle', 'Validasi pendaftaran pendaftar penjual baru di platform.')

@section('content')
<!-- SUB-TABS NAVIGATION -->
<div class="flex border-b border-sky-200 gap-4">
    <a href="{{ route('verifikator.identitas', ['tab' => 'pending']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending
    </a>
    <a href="{{ route('verifikator.identitas', ['tab' => 'history']) }}" class="pb-3 px-2 text-sm font-bold border-b-2 transition-all {{ $tab === 'history' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Verifikasi
    </a>
</div>

<!-- TABEL DATA -->
<div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
    <div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Nama Pemohon</th>
                        <th class="py-4 px-6">NIK</th>
                        <th class="py-4 px-6">Paket / Bank</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    @forelse($verifications as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-slate-800 text-xs">{{ $item->user->name ?? 'User N/A' }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">@safeEmail($item->user->email ?? '-')</p>
                        </td>
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-600">{{ $item->nik ?? '-' }}</td>
                        <td class="py-3.5 px-6 text-xs">
                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded-md font-bold text-[10px]">
                                {{ $item->membership->name ?? 'Bronze' }}
                            </span>
                            <span class="block text-[10px] font-semibold text-slate-400 mt-0.5">
                                {{ $item->bank_name ?? '-' }} ({{ $item->account_number ?? '-' }})
                            </span>
                        </td>
                        <td class="py-3.5 px-6">
                            @if($item->status === 'approved')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-check"></i> Disetujui</span>
                            @elseif($item->status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <a href="{{ route('verifikator.pendaftaran.show', $item->id_identity_verification) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-sm">
                                <i class="fa-solid fa-eye"></i> Tinjau Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 font-semibold text-xs">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                            Belum ada data pendaftaran pada tab ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($verifications, 'hasPages') && $verifications->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $verifications->links() }}
    </div>
    @endif
</div>
@endsection
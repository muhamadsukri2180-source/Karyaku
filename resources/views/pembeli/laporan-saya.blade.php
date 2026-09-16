@extends('layouts.pembeli')
@section('title', 'Riwayat Laporan Saya - Karyaku')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Laporan Saya
        </h4>
        <p class="text-muted small mb-0">Pantau status penanganan laporan pelanggaran yang telah Anda kirimkan.</p>
    </div>
    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3">
        <i class="bi bi-flag-fill me-1"></i> Buat Laporan Baru
    </a>
</div>

@if ($reports->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-flag fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Laporan</h5>
        <p class="small text-muted mb-4">Anda belum pernah mengajukan laporan pelanggaran terhadap produk atau pengguna.</p>
        <a href="{{ route('pembeli.dashboard') }}" class="btn btn-outline-primary btn-sm px-4 py-2 rounded-3">
            Kembali ke Dashboard
        </a>
    </div>
@else
    <div class="card-box p-0 overflow-hidden shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Target Laporan</th>
                        <th>Alasan</th>
                        <th>Status Penanganan</th>
                        <th>Catatan Petugas</th>
                        <th class="pe-4 text-end">Tanggal Lapor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        @php
                            // Status yang berarti laporan SUDAH ditindaklanjuti oleh verifikator/admin.
                            // Controller (VerifikatorController@actionLaporan) menyimpan 'resolved',
                            // bukan 'reviewed', jadi kita cek semua kemungkinan status "sudah ditangani" di sini.
                            $handledStatuses = ['reviewed', 'resolved', 'action_taken', 'escalated'];

                            $statusBadge = match(true) {
                                in_array($report->status, $handledStatuses) => 'bg-success text-white',
                                $report->status === 'dismissed' => 'bg-secondary text-white',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match(true) {
                                in_array($report->status, $handledStatuses) => 'Ditindaklanjuti',
                                $report->status === 'dismissed' => 'Ditolak / Selesai',
                                default => 'Sedang Ditinjau',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4">
                                @if ($report->product)
                                    <div class="fw-bold text-dark">{{ $report->product->title }}</div>
                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Produk Digital</span>
                                @elseif ($report->reportedUser)
                                    <div class="fw-bold text-dark">{{ $report->reportedUser->name }}</div>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Pengguna</span>
                                @else
                                    <span class="text-muted small">Umum / Lainnya</span>
                                @endif
                            </td>
                            <td>
                                <span class="small fw-semibold text-dark">{{ $report->reason }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge }} px-3 py-1.5 rounded-pill text-capitalize" style="font-size: 11px;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $report->admin_note ?: '-' }}
                            </td>
                            <td class="pe-4 text-end small text-muted">
                                {{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($reports->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @endif
@endif

@endsection
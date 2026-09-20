@extends('layouts.pembeli')
@section('title', 'Riwayat Laporan - Karyaku')

@section('content')

<style>
    .nav-tabs-report .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 13.5px;
        border: none;
        border-bottom: 2.5px solid transparent;
        padding: 9px 16px;
        background: transparent;
        transition: all .2s;
    }
    .nav-tabs-report .nav-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: transparent;
        font-weight: 700;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-shield-exclamation text-primary me-2"></i>Pusat Laporan & Pengaduan
        </h4>
        <p class="text-muted small mb-0">Pantau status penanganan laporan pelanggaran yang telah Anda ajukan atau ditujukan ke akun Anda.</p>
    </div>
    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm">
        <i class="bi bi-flag-fill me-1"></i> Buat Laporan Baru
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 small mb-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 small mb-3">{{ session('error') }}</div>
@endif

{{-- NAV TABS --}}
<ul class="nav nav-tabs nav-tabs-report border-bottom mb-4" id="pembeliReportTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="out-tab" data-bs-toggle="tab" data-bs-target="#tab-out" type="button" role="tab" aria-selected="true">
            <i class="bi bi-send me-1"></i> Laporan yang Saya Ajukan
            @if($reports->total() > 0)
                <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size:10px;">
                    {{ $reports->total() }}
                </span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="in-tab" data-bs-toggle="tab" data-bs-target="#tab-in" type="button" role="tab" aria-selected="false">
            <i class="bi bi-inbox-fill me-1"></i> Laporan Terhadap Akun Saya
            @if($incomingReports->total() > 0)
                <span class="badge bg-danger rounded-pill ms-1" style="font-size:10px;">
                    {{ $incomingReports->total() }}
                </span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content" id="pembeliReportTabContent">
    {{-- ================= TAB 1: LAPORAN YANG SAYA AJUKAN ================= --}}
    <div class="tab-pane fade show active" id="tab-out" role="tabpanel">
        @if ($reports->isEmpty())
            <div class="card-box p-5 text-center text-muted">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-flag fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Laporan Terkirim</h5>
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
                                <th>Alasan & Detail</th>
                                <th>Status Penanganan</th>
                                <th>Catatan Petugas</th>
                                <th class="pe-4 text-end">Tanggal Lapor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @php
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
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Pengguna Terlapor</span>
                                        @else
                                            <span class="text-muted small">Umum / Lainnya</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $report->reason }}</div>
                                        @if($report->description)
                                            <div class="text-muted small" style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($report->description, 60) }}</div>
                                        @endif
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
    </div>

    {{-- ================= TAB 2: LAPORAN TERHADAP AKUN SAYA ================= --}}
    <div class="tab-pane fade" id="tab-in" role="tabpanel">
        @if ($incomingReports->isEmpty())
            <div class="card-box p-5 text-center text-muted">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-shield-check fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Akun Anda Bersih</h5>
                <p class="small text-muted mb-0">Tidak ada pengaduan atau laporan pelanggaran yang ditujukan terhadap akun Anda.</p>
            </div>
        @else
            <div class="card-box p-0 overflow-hidden shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Alasan Pengaduan</th>
                                <th>Keterangan Pelapor</th>
                                <th>Status Verifikasi</th>
                                <th>Catatan Petugas</th>
                                <th class="pe-4 text-end">Tanggal Dilaporkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomingReports as $inReport)
                                @php
                                    $handledStatuses = ['reviewed', 'resolved', 'action_taken', 'escalated'];

                                    $statusBadge = match(true) {
                                        in_array($inReport->status, $handledStatuses) => 'bg-success text-white',
                                        $inReport->status === 'dismissed' => 'bg-secondary text-white',
                                        default => 'bg-warning text-dark',
                                    };

                                    $statusLabel = match(true) {
                                        in_array($inReport->status, $handledStatuses) => 'Ditindaklanjuti',
                                        $inReport->status === 'dismissed' => 'Ditolak / Selesai',
                                        default => 'Sedang Ditinjau',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-danger">{{ $inReport->reason }}</div>
                                        <span class="badge bg-danger-subtle text-danger" style="font-size: 10px;">Laporan Terhadap Anda</span>
                                    </td>
                                    <td>
                                        <div class="small text-secondary">{{ $inReport->description ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge }} px-3 py-1.5 rounded-pill text-capitalize" style="font-size: 11px;">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $inReport->admin_note ?: '-' }}
                                    </td>
                                    <td class="pe-4 text-end small text-muted">
                                        {{ $inReport->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($incomingReports->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $incomingReports->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
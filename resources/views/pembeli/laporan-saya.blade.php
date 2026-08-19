@extends('layouts.pembeli')
@section('title', 'Riwayat Laporan Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Riwayat Laporan Saya</h4>
    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-flag-fill"></i> Buat Laporan Baru</a>
</div>

@if ($reports->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-flag fs-1 d-block mb-3"></i>
        Kamu belum pernah membuat laporan.
    </div>
@else
<div class="card-box p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Target</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Catatan Admin</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                @php
                    $statusColor = match($report->status) {
                        'reviewed' => 'bg-primary-subtle text-primary',
                        'dismissed' => 'bg-secondary-subtle text-secondary',
                        default => 'bg-warning-subtle text-warning',
                    };
                @endphp
                <tr>
                    <td class="small">
                        @if ($report->product)
                            <div class="fw-semibold">{{ $report->product->title }}</div>
                            <div class="text-muted" style="font-size:11px;">Produk</div>
                        @elseif ($report->reportedUser)
                            <div class="fw-semibold">{{ $report->reportedUser->name }}</div>
                            <div class="text-muted" style="font-size:11px;">Pengguna</div>
                        @else
                            <span class="text-muted">Umum</span>
                        @endif
                    </td>
                    <td class="small">{{ $report->reason }}</td>
                    <td><span class="badge-status {{ $statusColor }}">{{ ucfirst($report->status) }}</span></td>
                    <td class="small text-muted">{{ $report->admin_note ?? '-' }}</td>
                    <td class="small">{{ $report->created_at->translatedFormat('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $reports->links() }}
</div>
@endif

@endsection

@extends('layouts.pembeli')
@section('title', 'Notifikasi')

@section('content')

<h4 class="fw-bold mb-4">Notifikasi</h4>

@if ($notifications->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-bell-slash fs-1 d-block mb-3"></i>
        Belum ada notifikasi dari admin.
    </div>
@else
<div class="card-box p-3">
    @foreach ($notifications as $notif)
    @php($isNew = $notif->created_at->greaterThan(now()->subDays(3)))
    <div class="d-flex gap-3 p-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width:42px;height:42px;background:var(--primary-light);color:var(--primary);">
            <i class="bi bi-bell-fill"></i>
        </div>
        <div class="flex-fill">
            <div class="d-flex align-items-center gap-2">
                <div class="fw-bold small">{{ $notif->name }}</div>
                @if ($isNew)<span class="badge-status bg-danger-subtle text-danger">Baru</span>@endif
            </div>
            <div class="text-muted small mt-1">{{ $notif->description }}</div>
            <div class="text-muted mt-1" style="font-size:11px;">{{ $notif->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $notifications->links() }}
</div>
@endif

@endsection

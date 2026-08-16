@extends('layouts.pembeli')
@section('title', 'Peringatan Saya')

@section('content')

<h4 class="fw-bold mb-4">Peringatan Diterima</h4>

@if ($peringatan->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-shield-check fs-1 d-block mb-3 text-success"></i>
        Tidak ada peringatan untuk akun kamu. Terus jaga aktivitas yang baik ya!
    </div>
@else
<div class="card-box p-3">
    @foreach ($peringatan as $p)
    <div class="d-flex gap-3 p-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width:42px;height:42px;background:#fef2f2;color:#dc2626;">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="flex-fill">
            <div class="fw-bold small">Terkait laporan: {{ $p->reason }}</div>
            <div class="text-muted small mt-1">{{ $p->admin_note }}</div>
            <div class="text-muted mt-1" style="font-size:11px;">{{ optional($p->reviewed_at)->translatedFormat('d F Y, H:i') }}</div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-4 d-flex justify-content-center">{{ $peringatan->links() }}</div>
@endif

@endsection

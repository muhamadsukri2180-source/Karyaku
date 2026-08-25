@extends('layouts.pembeli')
@section('title', 'Peringatan Saya')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="bi bi-shield-exclamation text-danger me-2"></i>Peringatan & Teguran Akun</h4>
    <p class="text-muted small mb-0">Daftar catatan peringatan resmi dari Tim Verifikator / Admin terkait aktivitas atau laporan pada akun Anda.</p>
</div>

@if ($peringatan->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-shield-check fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Status Akun Anda Bersih</h5>
        <p class="small text-muted mb-0">Tidak ada teguran atau peringatan aktif untuk akun kamu. Terus patuhi syarat dan ketentuan komunitas Karyaku ya!</p>
    </div>
@else
    <div class="card-box p-3">
        @foreach ($peringatan as $p)
        <div class="d-flex gap-3 p-3 {{ ! $loop->last ? 'border-bottom' : '' }} align-items-start">
            <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width:44px;height:44px;background:#fef2f2;color:#dc2626;">
                <i class="bi bi-exclamation-octagon-fill fs-5"></i>
            </div>
            <div class="flex-fill">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                    <div class="fw-bold text-dark" style="font-size: 14px;">
                        Peringatan Pelanggaran: <span class="text-danger">{{ $p->reason }}</span>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small">
                        {{ $p->action_taken ? ucfirst($p->action_taken) : 'Peringatan' }}
                    </span>
                </div>
                <div class="p-2.5 bg-light rounded-3 text-secondary small my-2 border-start border-3 border-danger">
                    <strong>Catatan Petugas:</strong> {{ $p->admin_note }}
                </div>
                <div class="text-muted d-flex align-items-center gap-3" style="font-size:11px;">
                    <span><i class="bi bi-calendar3 me-1"></i> {{ optional($p->reviewed_at ?? $p->updated_at)->translatedFormat('d F Y, H:i') }} WIB</span>
                    @if($p->product)
                        <span><i class="bi bi-box me-1"></i> Terkait: {{ $p->product->title }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $peringatan->links() }}</div>
@endif

@endsection

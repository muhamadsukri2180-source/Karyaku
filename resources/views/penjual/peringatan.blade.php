@extends('layouts.penjual')
@section('title', 'Peringatan Saya - Karyaku')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;

        --danger:#dc2626;
        --danger-light:#fef2f2;
        --danger-soft:#fee2e2;

        --success:#16a34a;
        --success-light:#f0fdf4;

        --border-color:#e5e7eb;
        --border-soft:#eef2f7;

        --text-dark:#1e293b;
        --text-muted:#64748b;
        --text-soft:#94a3b8;

        --shadow:0 5px 20px rgba(15,23,42,.06);
    }

    .seller-page-head { margin-bottom:24px; }
    .seller-page-head h4 { font-size:22px; font-weight:800; letter-spacing:-.3px; color:var(--text-dark); margin-bottom:6px; }
    .seller-page-head p  { color:var(--text-muted); line-height:1.7; max-width:760px; }

    .warning-card { background:#fff; border:1px solid var(--border-color); border-radius:18px; box-shadow:var(--shadow); overflow:hidden; }

    /* Ringkasan status akun */
    .status-strip { display:flex; align-items:center; gap:14px; padding:18px 20px; border-radius:18px; border:1px solid var(--border-color); background:#fff; box-shadow:var(--shadow); margin-bottom:20px; }
    .status-strip .badge-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:21px; flex-shrink:0; }
    .status-strip h6 { font-size:15px; font-weight:800; color:var(--text-dark); margin-bottom:3px; }
    .status-strip p  { font-size:12.5px; color:var(--text-muted); margin:0; line-height:1.6; }
    .status-count { margin-left:auto; text-align:right; flex-shrink:0; }
    .status-count .num { font-size:24px; font-weight:800; color:var(--danger); line-height:1; }
    .status-count .lbl { font-size:11px; color:var(--text-muted); }

    /* Keadaan kosong */
    .empty-warning { padding:55px 25px; text-align:center; }
    .empty-warning-icon { width:76px; height:76px; border-radius:50%; background:var(--success-light); color:var(--success); display:inline-flex; align-items:center; justify-content:center; margin-bottom:18px; border:7px solid #f7fee7; }
    .empty-warning-icon i { font-size:34px; }
    .empty-warning h5 { color:var(--text-dark); font-size:18px; font-weight:800; margin-bottom:7px; }
    .empty-warning p  { color:var(--text-muted); font-size:13px; line-height:1.7; max-width:520px; margin:0 auto; }

    /* Daftar peringatan */
    .warning-list { padding:8px; }
    .warning-item { display:flex; gap:16px; align-items:flex-start; padding:18px; border-radius:14px; transition:background .2s ease; }
    .warning-item:hover { background:#fafcff; }
    .warning-item:not(:last-child) { border-bottom:1px solid var(--border-soft); }

    .warning-icon { width:48px; height:48px; min-width:48px; border-radius:14px; background:var(--danger-light); color:var(--danger); display:flex; align-items:center; justify-content:center; border:1px solid var(--danger-soft); }
    .warning-icon i { font-size:20px; }

    .warning-content { flex:1; min-width:0; }
    .warning-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:8px; }

    .warning-eyebrow { font-size:11px; color:var(--text-soft); display:block; margin-bottom:2px; }
    .warning-title { font-size:15px; font-weight:800; color:var(--danger); line-height:1.45; }

    .warning-badge { display:inline-flex; align-items:center; gap:5px; padding:6px 11px; border-radius:999px; background:var(--danger-light); color:var(--danger); border:1px solid var(--danger-soft); font-size:11px; font-weight:800; white-space:nowrap; }

    .warning-note { background:#f8fafc; border:1px solid var(--border-soft); border-left:4px solid var(--danger); border-radius:10px; padding:12px 14px; margin:10px 0 12px; color:#475569; font-size:12.5px; line-height:1.7; }
    .warning-note .note-label { display:flex; align-items:center; gap:6px; color:var(--text-dark); font-weight:800; font-size:11.5px; margin-bottom:4px; }

    .warning-meta { display:flex; align-items:center; flex-wrap:wrap; gap:8px 18px; color:var(--text-soft); font-size:11.5px; }
    .warning-meta span { display:inline-flex; align-items:center; }
    .warning-meta i { margin-right:5px; }
    .warning-meta strong { color:var(--text-muted); font-weight:700; }

    /* Panduan di bawah daftar */
    .guide-card { margin-top:20px; background:var(--primary-light); border:1px solid #dbeafe; border-radius:16px; padding:18px 20px; }
    .guide-card h6 { font-size:13.5px; font-weight:800; color:var(--primary); display:flex; align-items:center; gap:8px; margin-bottom:10px; }
    .guide-card ul { margin:0; padding-left:18px; font-size:12.5px; color:var(--text-muted); line-height:1.8; }

    .warning-pagination { margin-top:20px; display:flex; justify-content:center; }

    @media(max-width:768px){
        .seller-page-head h4 { font-size:19px; }
        .warning-list { padding:5px; }
        .warning-item { gap:12px; padding:15px 12px; }
        .warning-icon { width:42px; height:42px; min-width:42px; border-radius:12px; }
        .warning-icon i { font-size:18px; }
        .warning-title { font-size:13.5px; }
        .status-count { margin-left:0; width:100%; text-align:left; }
        .status-strip { flex-wrap:wrap; }
    }
    @media (prefers-reduced-motion: reduce){ .warning-item { transition:none; } }
</style>

@php
    $adaPeringatan = !$peringatan->isEmpty();
    $jumlah = method_exists($peringatan, 'total') ? $peringatan->total() : $peringatan->count();
@endphp

{{-- ================= HEADER ================= --}}
<div class="seller-page-head">
    <h4>
        <i class="bi bi-shield-exclamation {{ $adaPeringatan ? 'text-danger' : 'text-success' }} me-2"></i>
        Peringatan dan teguran akun
    </h4>
    <p class="small mb-0">
        Catatan teguran resmi dari tim verifikator atau admin terkait aktivitas dan laporan yang masuk
        terhadap akun penjual Anda.
    </p>
</div>

{{-- ================= RINGKASAN STATUS ================= --}}
<div class="status-strip">
    @if($adaPeringatan)
        <div class="badge-icon" style="background:var(--danger-light); color:var(--danger);">
            <i class="bi bi-exclamation-octagon-fill"></i>
        </div>
        <div>
            <h6>Ada catatan teguran pada akun Anda</h6>
            <p>Baca setiap catatan petugas dan lakukan perbaikan agar akun tidak dibatasi.</p>
        </div>
        <div class="status-count">
            <div class="num">{{ $jumlah }}</div>
            <div class="lbl">Total teguran</div>
        </div>
    @else
        <div class="badge-icon" style="background:var(--success-light); color:var(--success);">
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <h6>Akun Anda dalam status baik</h6>
            <p>Tidak ada teguran aktif. Pertahankan kualitas karya dan layanan Anda.</p>
        </div>
    @endif
</div>

{{-- ================= ISI ================= --}}
@if (!$adaPeringatan)

    <div class="warning-card">
        <div class="empty-warning">
            <div class="empty-warning-icon"><i class="bi bi-shield-check"></i></div>
            <h5>Status akun Anda bersih</h5>
            <p>
                Belum ada teguran atau peringatan yang tercatat. Terus ikuti syarat dan ketentuan
                komunitas Karyaku supaya toko Anda tetap aman.
            </p>
        </div>
    </div>

@else

    <div class="warning-card">
        <div class="warning-list">
            @foreach ($peringatan as $p)
                @php
                    $tanggal = optional($p->reviewed_at ?? $p->updated_at);
                    $tindakan = $p->action_taken ? ucfirst(str_replace('_', ' ', $p->action_taken)) : 'Peringatan';
                @endphp

                <div class="warning-item">

                    <div class="warning-icon flex-shrink-0">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>

                    <div class="warning-content">

                        <div class="warning-top">
                            <div>
                                <span class="warning-eyebrow">Pelanggaran yang dicatat</span>
                                <div class="warning-title">{{ $p->reason }}</div>
                            </div>
                            <span class="warning-badge">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $tindakan }}
                            </span>
                        </div>

                        @if($p->admin_note)
                            <div class="warning-note">
                                <span class="note-label"><i class="bi bi-chat-left-text"></i> Catatan petugas</span>
                                {{ $p->admin_note }}
                            </div>
                        @endif

                        <div class="warning-meta">
                            <span>
                                <i class="bi bi-calendar3"></i>
                                {{ $tanggal ? $tanggal->translatedFormat('d F Y, H:i') . ' WIB' : 'Waktu tidak tercatat' }}
                            </span>

                            @if($p->product)
                                <span>
                                    <i class="bi bi-box"></i> Terkait produk
                                    <strong class="ms-1">{{ $p->product->title }}</strong>
                                </span>
                            @else
                                <span><i class="bi bi-person-badge"></i> Terkait akun penjual</span>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="guide-card">
        <h6><i class="bi bi-info-circle-fill"></i> Yang perlu Anda lakukan</h6>
        <ul>
            <li>Perbaiki produk atau perilaku yang disebut dalam catatan petugas.</li>
            <li>Pastikan setiap karya yang diunggah adalah milik Anda dan sesuai deskripsi.</li>
            <li>Teguran yang menumpuk dapat berujung pada pembatasan atau penonaktifan toko.</li>
        </ul>
    </div>

    @if (method_exists($peringatan, 'hasPages') && $peringatan->hasPages())
        <div class="warning-pagination">{{ $peringatan->links() }}</div>
    @endif

@endif

@endsection
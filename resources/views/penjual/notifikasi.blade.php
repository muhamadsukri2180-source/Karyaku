@extends('layouts.penjual')
@section('title', 'Notifikasi Penjual - Karyaku')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }

    .seller-page-head h4 { font-size:22px; font-weight:800; letter-spacing:-.3px; color:var(--text-dark); }
    .seller-page-head p  { color:var(--text-muted); }

    .kk-card { background:#fff; border:1px solid var(--border-color) !important; border-radius:18px; box-shadow:var(--shadow); }

    /* Ringkasan */
    .kk-stat { display:flex; align-items:center; gap:13px; padding:16px 18px; }
    .kk-stat-icon { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
    .kk-stat-value { font-size:21px; font-weight:800; line-height:1.1; color:var(--text-dark); }
    .kk-stat-label { font-size:11.5px; color:var(--text-muted); }

    /* Filter */
    .kk-filter .btn { border:1px solid var(--border-color); background:#fff; color:var(--text-muted); font-size:12.5px; font-weight:600; border-radius:11px; padding:8px 14px; transition:.18s ease; }
    .kk-filter .btn:hover { border-color:var(--primary); color:var(--primary); }
    .kk-filter .btn.active { background:var(--primary); border-color:var(--primary); color:#fff; box-shadow:0 5px 14px rgba(37,99,235,.18); }
    .kk-filter .btn.active.danger { background:#dc2626; border-color:#dc2626; box-shadow:0 5px 14px rgba(220,38,38,.18); }

    .kk-search { position:relative; min-width:250px; }
    .kk-search input { min-height:42px; width:100%; border-radius:11px; border:1px solid var(--border-color); background:#f8fafc; font-size:13.5px; padding:0 14px 0 38px; color:var(--text-dark); }
    .kk-search input:focus { background:#fff; border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,.12); outline:none; }
    .kk-search i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:14px; }

    /* Pemisah tanggal */
    .kk-group-label { display:flex; align-items:center; gap:10px; font-size:11.5px; font-weight:700; color:var(--text-muted); margin:6px 0 2px; }
    .kk-group-label::after { content:""; flex:1; height:1px; background:var(--border-color); }

    /* Kartu notifikasi */
    .notif-item { border:1px solid var(--border-color); background:#fff; border-radius:14px; padding:15px 16px; transition:box-shadow .2s ease, transform .2s ease, border-color .2s ease; }
    .notif-item:hover { border-color:#cbd5e1; box-shadow:0 4px 14px rgba(15,23,42,.06); transform:translateY(-1px); }
    .notif-item.unread { background:#f8faff; border-color:#bfdbfe; box-shadow:inset 3px 0 0 var(--primary); }

    .icon-notif-box { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:19px; flex-shrink:0; }

    .notif-title { font-size:14.5px; font-weight:700; color:var(--text-dark); line-height:1.4; }
    .notif-desc  { font-size:13px; line-height:1.6; color:#475569; margin:4px 0 6px; }
    .notif-meta  { font-size:11px; color:#94a3b8; display:flex; align-items:center; gap:14px; flex-wrap:wrap; }

    .kk-dot { width:7px; height:7px; border-radius:50%; background:var(--primary); flex-shrink:0; }

    .btn-hapus { border:0; background:transparent; color:#cbd5e1; font-size:14px; line-height:1; padding:5px 7px; border-radius:8px; transition:.18s ease; }
    .btn-hapus:hover { color:#dc2626; background:#fee2e2; }

    @media (max-width: 575px){
        .notif-meta { gap:8px; }
        .kk-search { min-width:100%; }
    }
    @media (prefers-reduced-motion: reduce){
        .notif-item, .kk-filter .btn, .btn-hapus { transition:none; }
        .notif-item:hover { transform:none; }
    }
</style>

@php
    $sudahDibaca = max(0, $totalCount - $unreadCount);
    $filterAktif = request('filter');

    // Pengelompokan waktu agar daftar mudah dipindai
    $grup = ['Hari ini' => [], 'Kemarin' => [], 'Minggu ini' => [], 'Lebih lama' => []];
    foreach ($notifications as $n) {
        $t = $n->created_at;
        if (!$t)                              $grup['Lebih lama'][] = $n;
        elseif ($t->isToday())                $grup['Hari ini'][]   = $n;
        elseif ($t->isYesterday())            $grup['Kemarin'][]    = $n;
        elseif ($t->greaterThan(now()->subWeek())) $grup['Minggu ini'][] = $n;
        else                                  $grup['Lebih lama'][] = $n;
    }
@endphp

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3 seller-page-head">
    <div>
        <h4 class="mb-1"><i class="bi bi-bell-fill text-primary me-2"></i>Pusat notifikasi toko</h4>
        <p class="small mb-0" style="max-width:640px;">
            Informasi pesanan, penarikan saldo, verifikasi produk, membership, dan peringatan akun Anda.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        @if($unreadCount > 0)
            <form action="{{ route('penjual.notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm rounded-3 fw-bold px-3 py-2 shadow-sm">
                    <i class="bi bi-check2-all me-1"></i> Tandai semua dibaca ({{ $unreadCount }})
                </button>
            </form>
        @endif
        <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold px-3 py-2">
            <i class="bi bi-arrow-left me-1"></i> Dashboard penjual
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- ================= RINGKASAN ================= --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:var(--primary-light); color:var(--primary);"><i class="bi bi-bell-fill"></i></div>
            <div>
                <div class="kk-stat-value">{{ $totalCount }}</div>
                <div class="kk-stat-label">Total notifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:#fef2f2; color:#dc2626;"><i class="bi bi-envelope-exclamation-fill"></i></div>
            <div>
                <div class="kk-stat-value" style="color:#dc2626;">{{ $unreadCount }}</div>
                <div class="kk-stat-label">Belum dibaca</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="kk-stat-value" style="color:#16a34a;">{{ $sudahDibaca }}</div>
                <div class="kk-stat-label">Sudah dibaca</div>
            </div>
        </div>
    </div>
</div>

{{-- ================= FILTER & PENCARIAN ================= --}}
<div class="kk-card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div class="d-flex gap-2 kk-filter flex-wrap">
            <a href="{{ route('penjual.notifikasi') }}" class="btn {{ !$filterAktif ? 'active' : '' }}">
                <i class="bi bi-collection me-1"></i> Semua ({{ $totalCount }})
            </a>
            <a href="{{ route('penjual.notifikasi', ['filter' => 'unread']) }}" class="btn danger {{ $filterAktif === 'unread' ? 'active' : '' }}">
                <i class="bi bi-dot"></i> Belum dibaca ({{ $unreadCount }})
            </a>
        </div>

        <div class="kk-search">
            <i class="bi bi-search"></i>
            <input type="text" id="cariNotif" placeholder="Cari judul atau isi notifikasi">
        </div>
    </div>
</div>

{{-- ================= DAFTAR NOTIFIKASI ================= --}}
@if ($notifications->count() > 0)

    <div class="d-flex flex-column gap-3" id="daftarNotif">
        @foreach ($grup as $labelGrup => $isiGrup)
            @if (count($isiGrup) > 0)
                <div class="kk-group-label js-group" data-grup="{{ $labelGrup }}">{{ $labelGrup }}</div>

                @foreach ($isiGrup as $notif)
                    @php
                        $nameLower = strtolower($notif->name ?? '');

                        if (str_contains($nameLower, 'pesanan') || str_contains($nameLower, 'order')) {
                            $icon = 'bi-receipt-cutoff'; $iconBg = '#ecfdf5'; $iconColor = '#059669';
                        } elseif (str_contains($nameLower, 'saldo') || str_contains($nameLower, 'tarik') || str_contains($nameLower, 'keuangan') || str_contains($nameLower, 'pembayaran')) {
                            $icon = 'bi-wallet2'; $iconBg = '#eff6ff'; $iconColor = '#2563eb';
                        } elseif (str_contains($nameLower, 'membership') || str_contains($nameLower, 'paket') || str_contains($nameLower, 'perpanjangan')) {
                            $icon = 'bi-gem'; $iconBg = '#fdf4ff'; $iconColor = '#c026d3';
                        } elseif (str_contains($nameLower, 'peringatan') || str_contains($nameLower, 'teguran') || str_contains($nameLower, 'laporan') || str_contains($nameLower, 'takedown') || str_contains($nameLower, 'tolak') || str_contains($nameLower, 'suspend')) {
                            $icon = 'bi-shield-exclamation'; $iconBg = '#fef2f2'; $iconColor = '#dc2626';
                        } elseif (str_contains($nameLower, 'produk') || str_contains($nameLower, 'karya')) {
                            $icon = 'bi-box-seam-fill'; $iconBg = '#f0fdf4'; $iconColor = '#16a34a';
                        } else {
                            $icon = 'bi-bell-fill'; $iconBg = '#f1f5f9'; $iconColor = '#475569';
                        }

                        $isUnread = !$notif->is_read;
                    @endphp

                    <div class="notif-item js-notif {{ $isUnread ? 'unread' : '' }}"
                         data-cari="{{ \Illuminate\Support\Str::lower(($notif->name ?? '') . ' ' . ($notif->description ?? '')) }}">
                        <div class="d-flex gap-3 align-items-start">

                            <div class="icon-notif-box" style="background:{{ $iconBg }}; color:{{ $iconColor }};">
                                <i class="bi {{ $icon }}"></i>
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($isUnread)<span class="kk-dot"></span>@endif
                                        <span class="notif-title">{{ $notif->name }}</span>
                                    </div>

                                    <button type="button" class="btn-hapus flex-shrink-0" title="Hapus notifikasi" onclick="confirmDeleteNotif('{{ route('penjual.notifikasi.destroy', $notif->id) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <p class="notif-desc mb-0">{{ $notif->description }}</p>

                                <div class="notif-meta">
                                    <span><i class="bi bi-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}</span>
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $notif->created_at ? $notif->created_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>

    <div class="kk-card p-4 text-center mt-3 d-none" id="hasilKosong" style="color:var(--text-muted);">
        <i class="bi bi-search fs-3 d-block mb-2 opacity-50"></i>
        <div class="small mb-0">Tidak ada notifikasi yang cocok di halaman ini.</div>
    </div>

    @if ($notifications->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif

@else
    <div class="kk-card p-5 text-center" style="color:var(--text-muted);">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
             style="width:74px; height:74px; background:var(--primary-light); color:var(--primary);">
            <i class="bi bi-bell-slash fs-1"></i>
        </div>
        <h5 class="fw-bold mb-1" style="color:var(--text-dark);">
            {{ $filterAktif === 'unread' ? 'Semua notifikasi sudah dibaca' : 'Belum ada notifikasi' }}
        </h5>
        <p class="small mb-3">
            {{ $filterAktif === 'unread'
                ? 'Tidak ada pemberitahuan yang menunggu dibaca.'
                : 'Pemberitahuan akan muncul di sini saat ada aktivitas baru di toko Anda.' }}
        </p>
        @if($filterAktif === 'unread')
            <a href="{{ route('penjual.notifikasi') }}" class="btn btn-sm btn-outline-primary rounded-3 fw-semibold">
                Lihat semua notifikasi
            </a>
        @endif
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input  = document.getElementById('cariNotif');
    const kosong = document.getElementById('hasilKosong');
    if (!input) return;

    input.addEventListener('input', function () {
        const kata = input.value.trim().toLowerCase();
        let tampil = 0;

        document.querySelectorAll('.js-notif').forEach(function (row) {
            const cocok = !kata || (row.dataset.cari || '').indexOf(kata) !== -1;
            row.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        // Sembunyikan label tanggal yang isinya kosong setelah disaring
        document.querySelectorAll('.js-group').forEach(function (label) {
            let ada = false;
            let el = label.nextElementSibling;
            while (el && !el.classList.contains('js-group')) {
                if (el.classList.contains('js-notif') && !el.classList.contains('d-none')) { ada = true; break; }
                el = el.nextElementSibling;
            }
            label.classList.toggle('d-none', !ada);
        });

        if (kosong) kosong.classList.toggle('d-none', tampil !== 0);
    });
});

function confirmDeleteNotif(actionUrl) {
    Swal.fire({
        title: 'Hapus Notifikasi?',
        text: 'Notifikasi ini akan dihapus dari riwayat toko Anda.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4 border-0 shadow-lg',
            confirmButton: 'btn btn-danger px-4 py-2 rounded-3 fw-bold',
            cancelButton: 'btn btn-secondary px-4 py-2 rounded-3 fw-semibold ms-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
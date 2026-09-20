@extends('layouts.penjual')
@section('title', 'Pesanan Masuk')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.10);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }

    .seller-page-head h4 { font-size:22px; font-weight:800; letter-spacing:-.3px; color:var(--text-dark); }
    .seller-page-head p  { color:var(--text-muted); }

    .kk-card        { background:#fff; border:1px solid var(--border-color) !important; border-radius:18px; box-shadow:var(--shadow); }
    .kk-card-hover  { transition:box-shadow .2s ease, transform .2s ease, border-color .2s ease; }
    .kk-card-hover:hover { box-shadow:var(--shadow-hover); transform:translateY(-2px); border-color:#c7d2fe !important; }

    /* Kartu ringkasan */
    .kk-stat { display:flex; align-items:center; gap:12px; padding:16px 18px; }
    .kk-stat-icon { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
    .kk-stat-value { font-size:20px; font-weight:800; line-height:1.1; color:var(--text-dark); }
    .kk-stat-label { font-size:11.5px; color:var(--text-muted); }

    /* Tab filter */
    .kk-tabs .nav-link { border:1px solid var(--border-color); border-radius:11px; font-size:12.5px; font-weight:600; padding:9px 14px; color:var(--text-muted); background:#fff; transition:.18s ease; }
    .kk-tabs .nav-link:hover { border-color:var(--primary); color:var(--primary); }
    .kk-tabs .nav-link.active { background:var(--primary); border-color:var(--primary); color:#fff !important; box-shadow:0 5px 14px rgba(37,99,235,.18); }
    .kk-tabs .badge { font-size:10.5px; font-weight:700; }

    /* Baris pesanan */
    .kk-order { padding:16px 18px; }
    .kk-thumb { width:68px; height:68px; object-fit:cover; border-radius:14px; flex-shrink:0; border:1px solid var(--border-color); }
    .kk-order-title { font-size:15px; font-weight:700; color:var(--text-dark); }
    .kk-meta { font-size:12px; color:var(--text-muted); }
    .kk-meta strong { color:var(--text-dark); font-weight:600; }

    .kk-chip { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:4px 9px; border-radius:7px; line-height:1.4; }
    .kk-chip-id { background:#f1f5f9; color:#475569; font-family:inherit; letter-spacing:.2px; }

    .kk-amount-box { border-left:1px dashed var(--border-color); padding-left:18px; }
    .kk-amount { font-size:18px; font-weight:800; color:var(--primary); line-height:1.2; }

    .btn-detail { border:1px solid var(--primary); color:var(--primary); background:#fff; font-size:12.5px; font-weight:600; border-radius:10px; padding:7px 14px; transition:.18s ease; }
    .btn-detail:hover { background:var(--primary); color:#fff; }

    /* Pencarian cepat */
    .kk-search { position:relative; }
    .kk-search input { min-height:44px; border-radius:11px; border:1px solid var(--border-color); background:#f8fafc; font-size:13.5px; padding-left:40px; }
    .kk-search input:focus { background:#fff; border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,.12); outline:none; }
    .kk-search i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:15px; }

    @media (max-width: 991px){
        .kk-amount-box { border-left:0; padding-left:0; border-top:1px dashed var(--border-color); padding-top:14px; width:100%; }
    }
    @media (prefers-reduced-motion: reduce){
        .kk-card-hover, .kk-tabs .nav-link, .btn-detail { transition:none; }
        .kk-card-hover:hover { transform:none; }
    }
</style>

@php
    $tabAktif   = $tab ?? 'semua';
    $pendapatan = $orderItems->sum(function ($i) {
        return (($i->order->payment_status ?? '') === 'paid') ? $i->subtotal : 0;
    });

    $statusMap = [
        'paid'     => ['label' => 'Lunas & terverifikasi', 'icon' => 'bi-shield-check',    'bg' => '#ecfdf5', 'fg' => '#16a34a'],
        'pending'  => ['label' => 'Sedang diverifikasi',   'icon' => 'bi-hourglass-split', 'bg' => '#fef3c7', 'fg' => '#b45309'],
        'failed'   => ['label' => 'Pembayaran ditolak',    'icon' => 'bi-x-circle',        'bg' => '#ffe4e6', 'fg' => '#e11d48'],
        'rejected' => ['label' => 'Pembayaran ditolak',    'icon' => 'bi-x-circle',        'bg' => '#ffe4e6', 'fg' => '#e11d48'],
    ];
@endphp

{{-- ================= HEADER ================= --}}
<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-receipt-cutoff me-2" style="color:var(--primary);"></i>Pesanan masuk</h4>
    <p class="small mb-0">Daftar transaksi pembelian karya digital Anda dari para pembeli.</p>
</div>

{{-- ================= RINGKASAN ================= --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:var(--primary-light); color:var(--primary);"><i class="bi bi-bag-check-fill"></i></div>
            <div>
                <div class="kk-stat-value">{{ $counts['semua'] }}</div>
                <div class="kk-stat-label">Total pesanan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:#fef3c7; color:#b45309;"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="kk-stat-value">{{ $counts['diproses'] }}</div>
                <div class="kk-stat-label">Menunggu verifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="kk-stat-value">{{ $counts['selesai'] }}</div>
                <div class="kk-stat-label">Transaksi selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kk-card kk-stat h-100">
            <div class="kk-stat-icon" style="background:#f1f5f9; color:#0f172a;"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="kk-stat-value">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
                <div class="kk-stat-label">Pendapatan lunas di halaman ini</div>
            </div>
        </div>
    </div>
</div>

{{-- ================= FILTER & PENCARIAN ================= --}}
<div class="kk-card p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">
        <ul class="nav nav-pills gap-2 kk-tabs flex-wrap mb-0">
            <li class="nav-item">
                <a class="nav-link {{ $tabAktif === 'semua' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'semua']) }}">
                    <i class="bi bi-collection me-1"></i> Semua
                    <span class="badge ms-1 {{ $tabAktif === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }}">{{ $counts['semua'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tabAktif === 'diproses' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'diproses']) }}">
                    <i class="bi bi-hourglass-split me-1"></i> Perlu diproses
                    <span class="badge ms-1 {{ $tabAktif === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }}">{{ $counts['diproses'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tabAktif === 'selesai' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['tab' => 'selesai']) }}">
                    <i class="bi bi-check-circle me-1"></i> Selesai
                    <span class="badge ms-1 {{ $tabAktif === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }}">{{ $counts['selesai'] }}</span>
                </a>
            </li>
        </ul>

        <div class="kk-search" style="min-width: 260px;">
            <i class="bi bi-search"></i>
            <input type="text" id="cariPesanan" class="form-control" placeholder="Cari judul produk, pembeli, atau nomor pesanan">
        </div>
    </div>
</div>

{{-- ================= DAFTAR PESANAN ================= --}}
@if($orderItems->isEmpty())
    <div class="kk-card p-5 text-center" style="color:var(--text-muted);">
        <i class="bi bi-cart-x fs-1 d-block mb-3 opacity-50"></i>
        <h5 class="fw-bold mb-1" style="color:var(--text-dark);">Belum ada pesanan di tab ini</h5>
        <p class="small mb-3">Pesanan akan muncul begitu ada pembeli yang memesan karya Anda.</p>
        <a href="{{ route('penjual.produk.index') }}" class="btn btn-detail">
            <i class="bi bi-box-seam me-1"></i> Kelola produk saya
        </a>
    </div>
@else
    <div class="d-flex flex-column gap-3" id="daftarPesanan">
        @foreach($orderItems as $item)
            @php
                $order  = $item->order;
                $buyer  = $order->buyer ?? null;
                $status = $order->payment_status ?? '';
                $s      = $statusMap[$status] ?? ['label' => 'Menunggu pembayaran pembeli', 'icon' => 'bi-clock-fill', 'bg' => '#f1f5f9', 'fg' => '#64748b'];
                $kodeOrder = $order->id_order ?? $item->order_id;
            @endphp

            <div class="kk-card kk-card-hover kk-order js-order"
                 data-cari="{{ Str::lower(($item->product->title ?? '') . ' ' . ($buyer->name ?? '') . ' ' . $kodeOrder) }}">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">

                    <div class="d-flex align-items-start gap-3 overflow-hidden flex-grow-1">
                        <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://placehold.co/80x80?text=Karya' }}"
                             alt="{{ $item->product->title ?? 'Produk' }}" class="kk-thumb">

                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <span class="kk-chip kk-chip-id">#{{ $kodeOrder }}</span>
                                <span class="kk-chip" style="background:{{ $s['bg'] }}; color:{{ $s['fg'] }};">
                                    <i class="bi {{ $s['icon'] }}"></i> {{ $s['label'] }}
                                </span>
                                <span class="kk-meta">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>

                            <div class="kk-order-title text-truncate mb-1">{{ $item->product->title ?? 'Produk karya digital' }}</div>

                            <div class="kk-meta text-truncate">
                                <i class="bi bi-person-circle me-1"></i>
                                <strong>{{ $buyer->name ?? 'Pengguna' }}</strong>
                                <span class="mx-1">&bull;</span>@safeEmail($buyer->email ?? '-')
                                <span class="mx-1">&bull;</span>{{ $item->quantity }} item
                            </div>
                        </div>
                    </div>

                    <div class="kk-amount-box d-flex flex-row flex-lg-column justify-content-between align-items-lg-end gap-2 flex-shrink-0">
                        <div class="text-lg-end">
                            <div class="kk-stat-label">Pendapatan Anda</div>
                            <div class="kk-amount">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                        <a href="{{ route('penjual.pesanan.detail', $item->id_order_item) }}" class="btn btn-detail align-self-center">
                            <i class="bi bi-eye me-1"></i> Lihat detail
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="kk-card p-4 text-center mt-3 d-none" id="hasilKosong" style="color:var(--text-muted);">
        <i class="bi bi-search fs-3 d-block mb-2 opacity-50"></i>
        <div class="small mb-0">Tidak ada pesanan yang cocok di halaman ini. Coba kata kunci lain.</div>
    </div>

    <div class="d-flex justify-content-center mt-4">{{ $orderItems->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input  = document.getElementById('cariPesanan');
    const kosong = document.getElementById('hasilKosong');
    if (!input) return;

    input.addEventListener('input', function () {
        const kata = input.value.trim().toLowerCase();
        let tampil = 0;

        document.querySelectorAll('.js-order').forEach(function (row) {
            const cocok = !kata || (row.dataset.cari || '').indexOf(kata) !== -1;
            row.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        if (kosong) kosong.classList.toggle('d-none', tampil !== 0);
    });
});
</script>
@endpush
@extends('layouts.penjual')
@section('title', 'Rincian Pesanan #' . ($orderItem->order->id_order ?? $orderItem->order_id))

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

    .kk-card-title { display:flex; align-items:center; gap:9px; font-size:14px; font-weight:700; color:var(--text-dark); padding-bottom:12px; margin-bottom:18px; border-bottom:1px solid var(--border-color); }
    .kk-card-title i { color:var(--primary); }

    .info-label { font-size:11px; color:var(--text-muted); display:block; margin-bottom:3px; }
    .info-value { font-size:14px; font-weight:600; color:var(--text-dark); word-break:break-word; }

    .kk-chip { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:4px 9px; border-radius:7px; }

    .kk-product-thumb { width:88px; height:88px; object-fit:cover; border-radius:14px; border:1px solid var(--border-color); flex-shrink:0; }

    .kk-total { background:var(--primary-light); border:1px solid #dbeafe; border-radius:14px; padding:16px 18px; }
    .kk-total-value { font-size:24px; font-weight:800; color:var(--primary); line-height:1.1; }

    .kk-row { display:flex; justify-content:space-between; gap:12px; font-size:13px; padding:7px 0; color:var(--text-muted); }
    .kk-row strong { color:var(--text-dark); font-weight:600; }

    /* Status */
    .kk-status { border-radius:14px; padding:20px 16px; text-align:center; }
    .kk-status i { font-size:1.9rem; display:block; margin-bottom:6px; }
    .kk-status h6 { font-weight:800; font-size:14px; margin-bottom:4px; letter-spacing:-.2px; }
    .kk-status small { font-size:11.5px; line-height:1.5; display:block; }

    /* Linimasa */
    .kk-timeline { list-style:none; padding:0; margin:18px 0 0; }
    .kk-timeline li { position:relative; padding:0 0 16px 28px; font-size:12.5px; color:var(--text-muted); line-height:1.5; }
    .kk-timeline li:not(:last-child)::before { content:""; position:absolute; left:8px; top:18px; bottom:0; width:1px; background:var(--border-color); }
    .kk-timeline .dot { position:absolute; left:0; top:2px; width:17px; height:17px; border-radius:50%; border:2px solid var(--border-color); background:#fff; display:flex; align-items:center; justify-content:center; font-size:8px; color:#fff; }
    .kk-timeline .dot.done { background:var(--primary); border-color:var(--primary); }
    .kk-timeline .dot.fail { background:#e11d48; border-color:#e11d48; }
    .kk-timeline .title { font-weight:700; color:var(--text-dark); display:block; font-size:13px; }

    .btn-outline-kk { border:1px solid var(--primary); color:var(--primary); background:#fff; font-size:13px; font-weight:600; border-radius:10px; padding:9px 14px; transition:.18s ease; }
    .btn-outline-kk:hover { background:var(--primary); color:#fff; }

    .kk-side { position:sticky; top:20px; }

    @media (prefers-reduced-motion: reduce){ .btn-outline-kk { transition:none; } }
</style>

@php
    $order   = $orderItem->order;
    $buyer   = $order->buyer ?? null;
    $product = $orderItem->product;
    $status  = $order->payment_status ?? '';
    $isPaid  = $status === 'paid';
    $isTolak = in_array($status, ['failed', 'rejected']);
    $kodeOrder = $order->id_order ?? $orderItem->order_id;
@endphp

{{-- ================= HEADER ================= --}}
<div class="seller-page-head mb-4">
    <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-sm fw-semibold mb-3 rounded-3 px-3 shadow-sm text-white" style="background-color:var(--primary);">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke pesanan masuk
    </a>

    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2">
        <div>
            <h4 class="mb-1">Rincian transaksi pembelian</h4>
            <p class="small mb-0">
                Nomor pesanan <strong style="color:var(--text-dark);">#{{ $kodeOrder }}</strong>
                &bull; {{ $orderItem->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ================= KOLOM KIRI ================= --}}
    <div class="col-lg-8">

        {{-- PRODUK --}}
        <div class="kk-card p-4 mb-4">
            <div class="kk-card-title"><i class="bi bi-box-seam"></i> Produk yang dibeli</div>

            <div class="d-flex align-items-start gap-3 flex-wrap">
                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/100x100?text=Karya' }}"
                     alt="{{ $product->title ?? 'Produk' }}" class="kk-product-thumb">
                <div class="flex-grow-1" style="min-width:200px;">
                    <span class="kk-chip mb-2" style="background:var(--primary-light); color:var(--primary);">
                        <i class="bi bi-tag-fill"></i> {{ $product->category->name ?? 'Tanpa kategori' }}
                    </span>
                    <h5 class="fw-bold mb-2" style="color:var(--text-dark);">{{ $product->title ?? 'Produk' }}</h5>
                    <div class="kk-row p-0">
                        <span>Harga satuan</span>
                        <strong>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="kk-row p-0 pt-1">
                        <span>Jumlah</span>
                        <strong>{{ $orderItem->quantity }} item</strong>
                    </div>
                </div>
            </div>

            <div class="kk-total d-flex justify-content-between align-items-center gap-3 mt-4">
                <div>
                    <div class="info-label mb-0">Pendapatan dari pesanan ini</div>
                    <div class="small" style="color:var(--text-muted);">
                        {{ $isPaid ? 'Sudah masuk ke saldo Anda.' : 'Masuk ke saldo setelah pembayaran terverifikasi.' }}
                    </div>
                </div>
                <div class="kk-total-value text-end">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- PEMBELI --}}
        <div class="kk-card p-4">
            <div class="kk-card-title"><i class="bi bi-person-vcard"></i> Data pembeli</div>

            <div class="row g-4">
                <div class="col-sm-6">
                    <span class="info-label">Nama pembeli</span>
                    <div class="info-value">{{ $buyer->name ?? 'Pengguna' }}</div>
                </div>
                <div class="col-sm-6">
                    <span class="info-label">Email</span>
                    <div class="info-value">@safeEmail($buyer->email ?? '-')</div>
                </div>
                <div class="col-sm-6">
                    <span class="info-label">Telepon / WhatsApp</span>
                    <div class="info-value">{{ $buyer->phone ?? '-' }}</div>
                </div>
                <div class="col-sm-6">
                    <span class="info-label">Waktu transaksi</span>
                    <div class="info-value">{{ $orderItem->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                </div>
            </div>

            <div class="d-flex align-items-start gap-2 mt-4 p-3 rounded-3" style="background:#f8fafc; border:1px solid var(--border-color);">
                <i class="bi bi-lock-fill" style="color:var(--text-muted);"></i>
                <small style="color:var(--text-muted);">
                    Data pembeli hanya untuk keperluan layanan pesanan ini. Hubungi pembeli lewat kanal resmi platform.
                </small>
            </div>
        </div>

    </div>

    {{-- ================= KOLOM KANAN ================= --}}
    <div class="col-lg-4">
        <div class="kk-side">
            <div class="kk-card p-4">
                <div class="kk-card-title"><i class="bi bi-shield-check"></i> Status pembayaran</div>

                @if($isPaid)
                    <div class="kk-status" style="background:#ecfdf5; border:1px solid #bbf7d0; color:#16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                        <h6>Lunas dan terverifikasi</h6>
                        <small>Diverifikasi tim verifikator. Saldo pendapatan Anda sudah bertambah.</small>
                    </div>
                @elseif($status === 'pending')
                    <div class="kk-status" style="background:#fef3c7; border:1px solid #fde68a; color:#b45309;">
                        <i class="bi bi-hourglass-split"></i>
                        <h6>Sedang diverifikasi</h6>
                        <small>Bukti transfer sudah dikirim pembeli dan menunggu pemeriksaan verifikator.</small>
                    </div>
                @elseif($isTolak)
                    <div class="kk-status" style="background:#ffe4e6; border:1px solid #fecdd3; color:#e11d48;">
                        <i class="bi bi-x-circle-fill"></i>
                        <h6>Pembayaran ditolak</h6>
                        <small>Bukti transfer tidak lolos pemeriksaan verifikator.</small>
                    </div>
                @else
                    <div class="kk-status" style="background:#f1f5f9; border:1px solid #e2e8f0; color:#64748b;">
                        <i class="bi bi-clock-history"></i>
                        <h6>Menunggu pembayaran</h6>
                        <small>Pembeli belum mengirimkan bukti pembayaran.</small>
                    </div>
                @endif

                {{-- LINIMASA --}}
                <ul class="kk-timeline">
                    <li>
                        <span class="dot done"><i class="bi bi-check"></i></span>
                        <span class="title">Pesanan dibuat</span>
                        {{ $orderItem->created_at->translatedFormat('d M Y, H:i') }}
                    </li>
                    <li>
                        <span class="dot {{ ($isPaid || $status === 'pending' || $isTolak) ? 'done' : '' }}"></span>
                        <span class="title">Bukti pembayaran dikirim</span>
                        {{ ($isPaid || $status === 'pending' || $isTolak) ? 'Sudah diterima sistem' : 'Belum dikirim pembeli' }}
                    </li>
                    <li>
                        <span class="dot {{ $isPaid ? 'done' : ($isTolak ? 'fail' : '') }}"></span>
                        <span class="title">Verifikasi verifikator</span>
                        {{ $isPaid ? 'Disetujui' : ($isTolak ? 'Ditolak' : 'Dalam antrean pemeriksaan') }}
                    </li>
                    <li>
                        <span class="dot {{ $isPaid ? 'done' : '' }}"></span>
                        <span class="title">Akses unduhan dan saldo</span>
                        {{ $isPaid ? 'Pembeli dapat mengunduh, saldo Anda bertambah' : 'Aktif setelah verifikasi berhasil' }}
                    </li>
                </ul>

                <div class="d-flex align-items-start gap-2 mt-3 mb-3 p-3 rounded-3" style="background:#f8fafc; border:1px solid var(--border-color);">
                    <i class="bi bi-info-circle-fill" style="color:var(--primary);"></i>
                    <small style="color:var(--text-muted);">
                        Verifikasi bukti transfer sepenuhnya ditangani tim verifikator agar transaksi tetap aman untuk kedua pihak.
                    </small>
                </div>

                <a href="{{ route('penjual.keuangan.index') }}" class="btn btn-outline-kk w-100">
                    <i class="bi bi-wallet2 me-1"></i> Cek saldo dan penarikan
                </a>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('salinKode');
    if (!btn) return;

    btn.addEventListener('click', function () {
        const kode = btn.dataset.kode || '';
        const selesai = function () {
            const asli = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Nomor pesanan disalin';
            setTimeout(function () { btn.innerHTML = asli; }, 1800);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(kode).then(selesai);
        } else {
            const ta = document.createElement('textarea');
            ta.value = kode;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            selesai();
        }
    });
});
</script>
@endpush
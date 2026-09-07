@extends('layouts.penjual')
@section('title', 'Dashboard Penjual')

@push('styles')
<style>
    /* Menggunakan sistem desain yang SAMA dengan pembeli.dashboard */
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --primary-soft:#dbeafe;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.10);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }

    /* WELCOME / STATUS CARD (sama seperti .welcome-card di pembeli) */
    .welcome-card { background: #fff; border: 1px solid var(--border-color); border-radius: 18px; padding: 25px 28px; margin-bottom: 22px; box-shadow: var(--shadow); display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; }
    .welcome-title { margin: 0; font-size: 24px; font-weight: 800; }
    .welcome-title span { color: var(--primary); }
    .welcome-desc { margin: 7px 0 0; color: var(--text-muted); font-size: 12px; max-width: 700px; line-height: 1.7; }
    .welcome-icon { width: 70px; height: 70px; border-radius: 20px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink:0; }

    /* ALERT BANNER (khusus penjual - peringatan membership) */
    .alert-banner { background:#fff; border:1px solid var(--border-color); border-left:4px solid var(--primary); border-radius:16px; padding:16px 20px; box-shadow:var(--shadow); display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom: 22px; }
    .alert-banner.danger { border-left-color:#ef4444; }
    .alert-banner.warning { border-left-color:#f59e0b; }
    .alert-icon { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }

    /* STATISTICS GRID (identik dengan pembeli.dashboard) */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card-dash { background: #fff; border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; box-shadow: var(--shadow); transition: all .2s ease; }
    .stat-card-dash:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .icon-blue { background: var(--primary-light); color: var(--primary); }
    .icon-green { background: #ecfdf5; color: #16a34a; }
    .icon-orange { background: #fff7ed; color: #f59e0b; }
    .icon-red { background: #fef2f2; color: #ef4444; }
    .stat-number { margin-top: 16px; font-size: 24px; font-weight: 800; color: var(--text-dark); }
    .stat-label { margin-top: 2px; color: var(--text-muted); font-size: 10px; }
    .stat-link { display: inline-flex; align-items: center; gap: 4px; margin-top: 10px; color: var(--primary); font-size: 9px; font-weight: 700; text-decoration:none; }

    /* SECTIONS (identik dengan pembeli.dashboard) */
    .section { margin-bottom: 30px; }
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; }
    .section-title { margin: 0; font-size: 19px; font-weight: 800; color: var(--text-dark); }
    .section-subtitle { margin: 4px 0 0; color: var(--text-muted); font-size: 11px; }
    .see-all { color: var(--primary); font-size: 11px; font-weight: 700; text-decoration:none; }

    /* LIST ROW (mengikuti pola .creator milik pembeli.dashboard) */
    .list-row { display:flex; align-items:center; justify-content:space-between; gap: 12px; padding: 10px; border-radius: 12px; background:#f8fafc; border:1px solid var(--border-color); }
    .list-row img { border-radius: 10px; object-fit: cover; flex-shrink:0; }

    /* PROGRESS QUOTA */
    .quota-bar { height: 8px; border-radius: 20px; background: #f1f5f9; overflow:hidden; }
    .quota-bar-fill { height:100%; border-radius:20px; background: var(--primary); }
    .quota-bar-fill.danger { background:#ef4444; }

    /* RESPONSIVE (sama seperti pembeli.dashboard) */
    @media(max-width: 1000px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 700px) { .welcome-card { padding: 20px; } .welcome-icon { display: none; } .welcome-title { font-size: 20px; } }
    @media(max-width: 450px) { .stats-grid { grid-template-columns: 1fr; } .section-title { font-size: 16px; } }
</style>
@endpush

@section('content')

    {{-- BANNER KEDALUWARSA / PERINGATAN MEMBERSHIP --}}
    @if($isExpired)
        <div class="alert-banner danger">
            <div class="d-flex align-items-center gap-3">
                <div class="alert-icon" style="background:#fef2f2; color:#ef4444;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0" style="color:#ef4444;">Masa Aktif Paket Membership Anda Telah Habis!</h6>
                    <small style="color:var(--text-muted);">Produk Anda tetap tersimpan, namun Anda tidak dapat mengunggah produk baru hingga memperpanjang paket.</small>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-3" style="background:#ef4444; color:#fff;">
                <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Sekarang
            </a>
        </div>
    @elseif($remainingDays <= 3 && $remainingDays > 0)
        <div class="alert-banner warning">
            <div class="d-flex align-items-center gap-3">
                <div class="alert-icon" style="background:#fff7ed; color:#f59e0b;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0" style="color:#b45309;">Masa Aktif Membership Anda Tersisa {{ $remainingDays }} Hari Lagi</h6>
                    <small style="color:var(--text-muted);">Segera perpanjang paket untuk menikmati kuota unggah dan fitur promosi tanpa jeda.</small>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-3" style="background:#f59e0b; color:#1e293b;">
                <i class="bi bi-gem me-1"></i> Perpanjang Paket
            </a>
        </div>
    @endif

    {{-- WELCOME / STATUS MEMBERSHIP CARD --}}
    <section class="welcome-card">
        <div>
            <span class="badge px-2 py-1 rounded-pill fw-bold mb-2 d-inline-block" style="font-size: 11px; background:var(--primary-light); color:var(--primary);">
                PAKET AKTIF
            </span>
            <h2 class="welcome-title">{{ $membershipName }}</h2>
            <p class="welcome-desc">
                @if($user->membership_expires_at)
                    Masa aktif berlaku sampai <strong style="color:var(--text-dark);">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong> ({{ $remainingDays }} hari tersisa).
                @else
                    Masa aktif paket aktif permanen atau belum ditentukan.
                @endif
            </p>

            {{-- PROGRESS KUOTA UPLOAD --}}
            @php
                $percentage = $maxProducts > 0 ? min(100, round(($totalProduk / $maxProducts) * 100)) : 0;
            @endphp
            <div style="max-width:420px;">
                <div class="d-flex justify-content-between small mb-1 fw-medium">
                    <span style="color:var(--text-muted);">Kuota Upload Produk Terpakai:</span>
                    <strong style="color: {{ $batasTercapai ? '#ef4444' : 'var(--primary)' }};">{{ $totalProduk }} / {{ $maxProducts }} ({{ $percentage }}%)</strong>
                </div>
                <div class="quota-bar">
                    <div class="quota-bar-fill {{ $batasTercapai ? 'danger' : '' }}" style="width: {{ $percentage }}%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size: 11px; color:var(--text-muted);">
                    <span>Sisa kuota: <strong>{{ $quotaSisa }} slot</strong></span>
                    <span>Fitur Iklan: <strong style="color: {{ $bisaIklan ? '#16a34a' : '#94a3b8' }};">{{ $bisaIklan ? 'Tersedia' : 'Khusus Gold/Diamond' }}</strong></span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('penjual.membership.index') }}" class="btn fw-bold py-2 px-3 rounded-3" style="border:1px solid var(--primary); color:var(--primary); background:#fff; font-size:13px;">
                    <i class="bi bi-arrow-up-circle me-1"></i> Upgrade / Perpanjang
                </a>
                <a href="{{ route('penjual.produk.create') }}" class="btn fw-bold py-2 px-3 rounded-3 {{ $batasTercapai || $isExpired ? 'disabled' : '' }}" style="background:var(--primary); color:#fff; font-size:13px;">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
                </a>
            </div>
        </div>
        <div class="welcome-icon"><i class="bi bi-gem"></i></div>
    </section>

    {{-- STATISTIK UTAMA (identik struktur dengan pembeli.dashboard) --}}
    <section class="stats-grid">
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-green"><i class="bi bi-wallet2"></i></div><i class="bi bi-arrow-up-right" style="color:#16a34a;"></i></div>
            <div class="stat-number">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
            <a href="{{ route('penjual.keuangan.index') }}" class="stat-link">Lihat Saldo & Tarik <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div><i class="bi bi-arrow-up-right" style="color:var(--primary);"></i></div>
            <div class="stat-number">{{ number_format($totalPesanan) }}</div>
            <div class="stat-label">Pesanan Masuk</div>
            <a href="{{ route('penjual.pesanan.index') }}" class="stat-link">Kelola Pesanan <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-orange"><i class="bi bi-box-seam"></i></div><i class="bi bi-check2" style="color:#f59e0b;"></i></div>
            <div class="stat-number">{{ number_format($produkAktif) }}</div>
            <div class="stat-label">Produk Aktif dari {{ $totalProduk }} karya</div>
            <a href="{{ route('penjual.produk.index') }}" class="stat-link">Lihat Produk <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="stat-card-dash">
            <div class="stat-top"><div class="stat-icon icon-red"><i class="bi bi-shield-exclamation"></i></div><i class="bi bi-arrow-up-right" style="color:#ef4444;"></i></div>
            <div class="stat-number">{{ $produkPending }} / {{ $produkBuked }}</div>
            <div class="stat-label">Pending / Ditolak & Blokir</div>
            <a href="{{ route('penjual.produk.index', ['tab' => 'diblokir']) }}" class="stat-link">Cek Produk Diblokir <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>

    {{-- PRODUK TERBARU --}}
    <section class="section">
        <div class="section-header">
            <div>
                <h3 class="section-title">Produk Terbaru Anda</h3>
                <p class="section-subtitle">Karya digital yang baru saja Anda unggah</p>
            </div>
            <a href="{{ route('penjual.produk.index') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        @if($recentProducts->isEmpty())
            <div class="stat-card-dash text-center py-5" style="color:var(--text-muted);">
                <i class="bi bi-box fs-1 d-block mb-2 opacity-50"></i>
                <p class="small mb-3">Anda belum mengunggah produk karya digital.</p>
                <a href="{{ route('penjual.produk.create') }}" class="btn btn-sm fw-semibold" style="background:var(--primary); color:#fff;">
                    <i class="bi bi-plus-lg me-1"></i> Mulai Jual Produk
                </a>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($recentProducts as $prod)
                    <div class="list-row">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Produk' }}"
                                 alt="{{ $prod->title }}" style="width: 50px; height: 50px;">
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-truncate" style="font-size: 13.5px; color:var(--text-dark);">{{ $prod->title }}</h6>
                                <div class="small" style="font-size: 11px; color:var(--text-muted);">
                                    Rp {{ number_format($prod->price, 0, ',', '.') }} &bull; Stok: {{ $prod->stock }} &bull; Terjual: {{ $prod->sold_count }}
                                </div>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @if($prod->status === 'active')
                                <span class="badge" style="background:#ecfdf5; color:#16a34a;">Aktif</span>
                            @elseif($prod->status === 'pending')
                                <span class="badge" style="background:#fff7ed; color:#f59e0b;">Menunggu</span>
                            @else
                                <span class="badge" style="background:#fef2f2; color:#ef4444;">Ditolak / Blokir</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- PESANAN MASUK TERBARU --}}
    <section class="section">
        <div class="section-header">
            <div>
                <h3 class="section-title">Pesanan Masuk Terbaru</h3>
                <p class="section-subtitle">Transaksi pembelian dari pelanggan Anda</p>
            </div>
            <a href="{{ route('penjual.pesanan.index') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="stat-card-dash text-center py-5" style="color:var(--text-muted);">
                <i class="bi bi-cart-x fs-1 d-block mb-2 opacity-50"></i>
                <p class="small mb-0">Belum ada pesanan masuk dari pembeli.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($recentOrders as $orderItem)
                    <div class="col-md-6 col-lg-4">
                        <div class="stat-card-dash h-100">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-bold small" style="color:var(--text-dark);">{{ $orderItem->order->buyer->name ?? 'Pembeli' }}</span>
                                <span class="badge" style="font-size: 10px; background:var(--primary-light); color:var(--primary);">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="small text-truncate" style="font-size: 11px; color:var(--text-muted);">
                                {{ $orderItem->product->title ?? 'Produk' }} ({{ $orderItem->quantity }}x)
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3" style="font-size: 10px;">
                                <span style="color:var(--text-muted);">{{ $orderItem->created_at->diffForHumans() }}</span>
                                @if($orderItem->order->payment_status === 'paid')
                                    <span class="fw-bold" style="color:#16a34a;"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                @else
                                    <span class="fw-bold" style="color:#f59e0b;"><i class="bi bi-clock-fill"></i> Belum Bayar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@endsection
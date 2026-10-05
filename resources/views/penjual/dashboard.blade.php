@extends('layouts.penjual')
@section('title', 'Dashboard Penjual')

@push('styles')
<style>
    :root {
        --primary: #2563eb;
        --primary-light: #eff6ff;
        --border-color: #e5e7eb;
        --shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.08);
        --shadow-hover: 0 12px 30px -4px rgba(15, 23, 42, 0.14);
        --text-muted: #64748b;
        --text-dark: #0f172a;
    }

    /* REDESIGNED MEMBERSHIP CARD (PUTIH KEBIRUAN) */
    .welcome-card { 
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); 
        border: 1px solid #bfdbfe; 
        border-radius: 22px; 
        padding: 32px; 
        margin-bottom: 24px; 
        box-shadow: var(--shadow); 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        gap: 24px; 
        flex-wrap: wrap; 
        position: relative;
        overflow: hidden;
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 5px; height: 100%;
        background: var(--primary);
    }
    .welcome-badge {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .welcome-title { margin: 0; font-size: 26px; font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; }
    .welcome-desc { margin: 8px 0 0; color: var(--text-muted); font-size: 13.5px; max-width: 650px; line-height: 1.6; }
    .welcome-icon { width: 80px; height: 80px; border-radius: 20px; background: #dbeafe; color: var(--primary); border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; font-size: 38px; flex-shrink: 0; }

    /* ALERT BANNER */
    .alert-banner { background: #fff; border: 1px solid var(--border-color); border-left: 4px solid var(--primary); border-radius: 16px; padding: 18px 22px; box-shadow: var(--shadow); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .alert-banner.danger { border-left-color: #ef4444; background: #fff5f5; }
    .alert-banner.warning { border-left-color: #f59e0b; background: #fffbeb; }
    .alert-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }

    /* STATISTICS GRID */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 30px; }
    .stat-card-dash { 
        background: #fff; 
        border-radius: 18px; 
        padding: 22px; 
        box-shadow: var(--shadow); 
        transition: all .25s ease; 
        display: flex; 
        flex-direction: column; 
        justify-content: space-between; 
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }
    
    /* Varian Warna Card Statistik */
    .stat-card-dash.card-green { background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-color: #86efac; }
    .stat-card-dash.card-blue { background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%); border-color: #93c5fd; }
    .stat-card-dash.card-orange { background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%); border-color: #fcd34d; }
    .stat-card-dash.card-red { background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%); border-color: #fca5a5; }

    .stat-card-dash:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-icon { width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.06); }
    
    .icon-green { background: #15803d; color: #fff; }
    .icon-blue { background: #1d4ed8; color: #fff; }
    .icon-orange { background: #b45309; color: #fff; }
    .icon-red { background: #b91c1c; color: #fff; }

    .stat-number { margin-top: 16px; font-size: 22px; font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; }
    .stat-label { margin-top: 4px; color: #475569; font-size: 12px; font-weight: 700; }
    
    .stat-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 16px; font-size: 12px; font-weight: 700; text-decoration: none; transition: gap 0.2s; }
    .card-green .stat-link { color: #15803d; }
    .card-blue .stat-link { color: #1d4ed8; }
    .card-orange .stat-link { color: #b45309; }
    .card-red .stat-link { color: #b91c1c; }
    .stat-link:hover { gap: 10px; text-decoration: underline; }

    /* SECTIONS */
    .section { margin-bottom: 32px; }
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .section-title { margin: 0; font-size: 18px; font-weight: 800; color: var(--text-dark); }
    .section-subtitle { margin: 3px 0 0; color: var(--text-muted); font-size: 12px; }
    .see-all { color: var(--primary); font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .see-all:hover { text-decoration: underline; }

    /* LIST ROW */
    .list-row { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 16px; border-radius: 14px; background: #f8fafc; border: 1px solid var(--border-color); transition: background 0.2s; }
    .list-row:hover { background: #f1f5f9; }
    .list-row img { border-radius: 10px; object-fit: cover; flex-shrink: 0; width: 55px; height: 55px; }

    /* PROGRESS QUOTA (Light Theme) */
    .quota-bar-light { height: 9px; border-radius: 20px; background: #e2e8f0; overflow: hidden; margin-top: 6px; border: 1px solid #cbd5e1; }
    .quota-bar-fill-light { height: 100%; border-radius: 20px; background: var(--primary); transition: width 0.4s ease; }
    .quota-bar-fill-light.danger { background: #ef4444; }
    .quota-bar-fill-light.warning { background: #f59e0b; }

    /* RESPONSIVE */
    @media(max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 768px) { .welcome-card { padding: 22px; } .welcome-icon { display: none; } .welcome-title { font-size: 22px; } }
    @media(max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

    {{-- BANNER KEDALUWARSA / PERINGATAN MEMBERSHIP --}}
    @if($isExpired)
        <div class="alert-banner danger">
            <div class="d-flex align-items-center gap-3">
                <div class="alert-icon" style="background:#fee2e2; color:#ef4444;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color:#b91c1c; font-size: 14px;">Masa Aktif Paket Membership Anda Telah Habis!</h6>
                    <small style="color:var(--text-muted);">Produk Anda aman dan tersimpan, namun Anda tidak dapat mengunggah produk baru hingga memperpanjang paket.</small>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm" style="background:#ef4444; color:#fff;">
                <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Sekarang
            </a>
        </div>
    @elseif($remainingDays <= 3 && $remainingDays > 0)
        <div class="alert-banner warning">
            <div class="d-flex align-items-center gap-3">
                <div class="alert-icon" style="background:#fef3c7; color:#d97706;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color:#b45309; font-size: 14px;">Masa Aktif Membership Anda Tersisa {{ $remainingDays }} Hari Lagi</h6>
                    <small style="color:var(--text-muted);">Segera perpanjang paket untuk menikmati kuota unggah dan fitur promosi tanpa jeda.</small>
                </div>
            </div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm" style="background:#f59e0b; color:#fff;">
                <i class="bi bi-gem me-1"></i> Perpanjang Paket
            </a>
        </div>
    @endif

    {{-- WELCOME / STATUS MEMBERSHIP CARD (PUTIH KEBIRUAN) --}}
    <section class="welcome-card">
        <div style="flex: 1; min-width: 280px; z-index: 1;">
            <span class="badge welcome-badge px-3 py-1.5 rounded-pill fw-bold mb-2 d-inline-flex align-items-center gap-1.5" style="font-size: 11.5px;">
                <i class="bi bi-award-fill"></i> STATUS: {{ strtoupper($membershipName) }}
            </span>
            <h2 class="welcome-title">{{ $membershipName }} Plan</h2>
            <p class="welcome-desc">
                @if($user->membership_expires_at)
                    Masa aktif berlaku sampai dengan <strong class="text-dark">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong> (<span class="text-primary fw-semibold">{{ $remainingDays }} hari tersisa</span>).
                @else
                    Masa aktif paket Anda berlaku permanen atau belum ditentukan.
                @endif
            </p>

            {{-- PROGRESS KUOTA UPLOAD --}}
            @php
                $percentage = $maxProducts > 0 ? min(100, round(($totalProduk / $maxProducts) * 100)) : 0;
                $barClass = $percentage >= 90 ? 'danger' : ($percentage >= 75 ? 'warning' : '');
            @endphp
            <div style="max-width: 480px; margin-top: 18px;">
                <div class="d-flex justify-content-between small mb-1.5 fw-semibold" style="font-size: 12px; color: #475569;">
                    <span>Kuota Upload Produk Terpakai:</span>
                    <span style="color: {{ $percentage >= 90 ? '#ef4444' : 'var(--primary)' }};">
                        <strong>{{ $totalProduk }}</strong> / {{ $maxProducts }} Produk (<strong>{{ $percentage }}%</strong>)
                    </span>
                </div>
                <div class="quota-bar-light">
                    <div class="quota-bar-fill-light {{ $barClass }}" style="width: {{ $percentage }}%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-2" style="font-size: 11.5px; color: #64748b;">
                    <span>Sisa Kuota: <strong class="text-dark">{{ $quotaSisa }} Slot</strong></span>
                    <span>Fitur Iklan: <strong style="color: {{ $bisaIklan ? '#16a34a' : '#64748b' }};"><i class="bi bi-{{ $bisaIklan ? 'check-circle-fill' : 'dash-circle' }}"></i> {{ $bisaIklan ? 'Tersedia' : 'Khusus Gold/Diamond' }}</strong></span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2.5 mt-4">
                <a href="{{ route('penjual.membership.index') }}" class="btn fw-bold py-2 px-3.5 rounded-3 shadow-sm text-primary" style="background: #fff; border: 1px solid #93c5fd; font-size: 13px;">
                    <i class="bi bi-arrow-up-circle me-1"></i> Upgrade / Perpanjang Paket
                </a>
                <a href="{{ route('penjual.produk.create') }}" class="btn fw-bold py-2 px-3.5 rounded-3 shadow-sm {{ $batasTercapai || $isExpired ? 'disabled opacity-50' : '' }}" style="background: var(--primary); color: #fff; font-size: 13px; border: 1px solid var(--primary);">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
                </a>
            </div>
        </div>
        <div class="welcome-icon shadow-sm"><i class="bi bi-gem"></i></div>
    </section>

    {{-- STATISTIK UTAMA --}}
    <section class="stats-grid">
        <!-- Card 1: Pendapatan -->
        <div class="stat-card-dash card-green">
            <div>
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="bi bi-wallet2"></i></div>
                    <span class="badge bg-success text-white fw-bold px-2.5 py-1 shadow-sm" style="font-size: 10px;">Saldo</span>
                </div>
                <div class="stat-number">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan Bersih</div>
            </div>
            <a href="{{ route('penjual.keuangan.index') }}" class="stat-link">Kelola Saldo & Tarik <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Card 2: Pesanan Masuk -->
        <div class="stat-card-dash card-blue">
            <div>
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div>
                    <span class="badge bg-primary text-white fw-bold px-2.5 py-1 shadow-sm" style="font-size: 10px;">Pesanan</span>
                </div>
                <div class="stat-number">{{ number_format($totalPesanan) }}</div>
                <div class="stat-label">Total Pesanan Masuk</div>
            </div>
            <a href="{{ route('penjual.pesanan.index') }}" class="stat-link">Kelola Pesanan <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Card 3: Produk Aktif -->
        <div class="stat-card-dash card-orange">
            <div>
                <div class="stat-top">
                    <div class="stat-icon icon-orange"><i class="bi bi-box-seam"></i></div>
                    <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 shadow-sm" style="font-size: 10px;">Katalog</span>
                </div>
                <div class="stat-number">{{ number_format($produkAktif) }}</div>
                <div class="stat-label">Produk Aktif (dari {{ $totalProduk }} total karya)</div>
            </div>
            <a href="{{ route('penjual.produk.index') }}" class="stat-link">Lihat Semua Produk <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Card 4: Pending / Ditolak -->
        <div class="stat-card-dash card-red">
            <div>
                <div class="stat-top">
                    <div class="stat-icon icon-red"><i class="bi bi-shield-exclamation"></i></div>
                    <span class="badge bg-danger text-white fw-bold px-2.5 py-1 shadow-sm" style="font-size: 10px;">Perhatian</span>
                </div>
                <div class="stat-number">{{ $produkPending ?? 0 }} / {{ $produkBlocked ?? 0 }}</div>
                <div class="stat-label">Pending / Ditolak & Blokir</div>
            </div>
            <a href="{{ route('penjual.produk.index', ['tab' => 'diblokir']) }}" class="stat-link">Cek Status Produk <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>

    {{-- PRODUK TERBARU --}}
    <section class="section">
        <div class="section-header">
            <div>
                <h3 class="section-title">Produk Terbaru Anda</h3>
                <p class="section-subtitle">Daftar karya digital yang baru saja Anda unggah ke etalase</p>
            </div>
            <a href="{{ route('penjual.produk.index') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        @if($recentProducts->isEmpty())
            <div class="stat-card-dash text-center py-5" style="color:var(--text-muted);">
                <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Produk</h6>
                <p class="small mb-3">Anda belum mengunggah produk karya digital apapun saat ini.</p>
                <a href="{{ route('penjual.produk.create') }}" class="btn btn-sm fw-semibold px-3 py-2 shadow-sm" style="background:var(--primary); color:#fff;">
                    <i class="bi bi-plus-lg me-1"></i> Mulai Jual Produk Sekarang
                </a>
            </div>
        @else
            <div class="d-flex flex-column gap-2.5">
                @foreach($recentProducts as $prod)
                    <div class="list-row">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="{{ $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : 'https://placehold.co/80x80?text=Produk' }}"
                                 alt="{{ $prod->title }}">
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14px; color:var(--text-dark);">{{ $prod->title }}</h6>
                                <div class="small text-muted" style="font-size: 11.5px;">
                                    <span class="fw-bold text-dark">Rp {{ number_format($prod->price, 0, ',', '.') }}</span> &bull; Stok: {{ $prod->stock }} &bull; Terjual: {{ $prod->sold_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @if($prod->status === 'active')
                                <span class="badge px-2.5 py-1.5 rounded-pill" style="background:#dcfce7; color:#15803d; font-weight: 700;">Aktif</span>
                            @elseif($prod->status === 'pending')
                                <span class="badge px-2.5 py-1.5 rounded-pill" style="background:#fef3c7; color:#b45309; font-weight: 700;">Menunggu Review</span>
                            @else
                                <span class="badge px-2.5 py-1.5 rounded-pill" style="background:#fee2e2; color:#b91c1c; font-weight: 700;">Ditolak / Blokir</span>
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
                <p class="section-subtitle">Transaksi pembelian langsung dari pelanggan Anda</p>
            </div>
            <a href="{{ route('penjual.pesanan.index') }}" class="see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="stat-card-dash text-center py-5" style="color:var(--text-muted);">
                <i class="bi bi-cart-x fs-1 d-block mb-3 opacity-50"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Pesanan</h6>
                <p class="small mb-0">Pesanan dari pembeli yang masuk akan muncul di bagian ini.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($recentOrders as $orderItem)
                    <div class="col-md-6 col-lg-4">
                        <div class="stat-card-dash h-100" style="background: #fff;">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small text-dark d-flex align-items-center gap-1">
                                        <i class="bi bi-person-circle text-muted"></i> {{ $orderItem->order->buyer->name ?? 'Pembeli' }}
                                    </span>
                                    <span class="badge px-2 py-1" style="font-size: 11px; background:var(--primary-light); color:#1d4ed8; font-weight: 700;">
                                        Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="small text-truncate text-muted mb-2" style="font-size: 12px;" title="{{ $orderItem->product->title ?? 'Produk' }}">
                                    <i class="bi bi-box-seam me-1"></i> {{ $orderItem->product->title ?? 'Produk' }} <span class="fw-semibold text-dark">({{ $orderItem->quantity }}x)</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2" style="font-size: 11.5px;">
                                <span class="text-muted"><i class="bi bi-clock me-1"></i>{{ $orderItem->created_at->diffForHumans() }}</span>
                                @if(optional($orderItem->order)->payment_status === 'paid')
                                    <span class="fw-bold text-success d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                @else
                                    <span class="fw-bold d-flex align-items-center gap-1" style="color: #b45309;"><i class="bi bi-clock-fill"></i> Belum Bayar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@endsection
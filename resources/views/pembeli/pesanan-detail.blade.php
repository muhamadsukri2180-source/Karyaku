@extends('layouts.pembeli')
@section('title', 'Detail Pesanan #' . ($order->kode_order ?? $order->id_order))

@push('styles')
<style>
    @media(max-width: 576px) {
        .card-box { padding: 16px !important; border-radius: 16px !important; }
        .table td, .table th { padding: 8px 6px; font-size: 12px; }
        .btn-kirim-bukti { width: 100% !important; }
    }
</style>
@endpush

@section('content')

{{-- BACK & TITLE --}}
<div class="mb-4">
    <a href="{{ route('pembeli.pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
    </a>
    <h4 class="fw-extrabold text-dark mb-1">Rincian Detail Pesanan</h4>
    <p class="text-muted mb-0 small">Kode Transaksi: <strong>#{{ $order->kode_order ?? $order->id_order }}</strong> &middot; {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
</div>

{{-- ALERT MESSAGE --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    {{-- KOLOM KIRI: RINCIAN ITEM & STATUS --}}
    <div class="col-lg-8">
        {{-- STATUS BANNER --}}
        <div class="card-box p-4 mb-4 border shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle flex-shrink-0" style="width:50px;height:50px;">
                        <i class="bi bi-bag-check-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Status Pesanan: <span class="text-capitalize text-primary">{{ $order->status }}</span></h6>
                        <p class="mb-0 small text-muted">Status Pembayaran: 
                            @if ($order->payment_status === 'paid')
                                <strong class="text-success"><i class="bi bi-check-circle-fill"></i> LUNAS (Terverifikasi Verifikator)</strong>
                            @elseif ($order->payment_status === 'pending')
                                <strong class="text-warning"><i class="bi bi-clock-fill"></i> MENUNGGU VERIFIKASI VERIFIKATOR</strong>
                            @elseif (in_array($order->payment_status, ['failed', 'rejected']))
                                <strong class="text-danger"><i class="bi bi-x-circle-fill"></i> DITOLAK VERIFIKATOR</strong>
                            @else
                                <strong class="text-danger"><i class="bi bi-exclamation-circle-fill"></i> BELUM DIBAYAR</strong>
                            @endif
                        </p>
                    </div>
                </div>

                @if ($order->payment_status === 'paid')
                    <a href="{{ route('pembeli.download') }}" class="btn btn-success fw-bold px-3 py-2 rounded-3 text-white">
                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download Berkas
                    </a>
                @endif
            </div>

            @if($order->payment_status === 'pending')
                <div class="mt-3 p-3 bg-warning-subtle border border-warning rounded-3 small text-dark">
                    <i class="bi bi-info-circle-fill me-1 text-warning"></i>
                    Bukti pembayaran Anda telah dikirim dan sedang diperiksa oleh tim Verifikator platform. Mohon tunggu proses konfirmasi agar berkas dapat diunduh.
                </div>
            @elseif(in_array($order->payment_status, ['failed', 'rejected']))
                <div class="mt-3 p-3 bg-danger-subtle border border-danger rounded-3 small text-danger">
                    <i class="bi bi-exclamation-octagon-fill me-1"></i>
                    Catatan Penolakan Verifikator: <strong>{{ $order->rejection_note ?? 'Bukti transfer tidak dapat diverifikasi.' }}</strong>
                    <br>Silakan unggah kembali foto resi transfer yang valid di bawah ini.
                </div>
            @endif
        </div>

        {{-- FORM UNGGAH BUKTI PEMBAYARAN JIKA BELUM LUNAS --}}
        @if ($order->payment_status !== 'paid')
            <div class="card-box p-4 mb-4 border shadow-sm rounded-4 border-primary">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i> Instruksi & Konfirmasi Pembayaran
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="badge bg-primary mb-2">BCA</span>
                            <h6 class="fw-bold text-dark mb-1">0862398284994</h6>
                            <p class="mb-0 small text-muted">a.n PT Karyaku Digital Kreatif</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="badge bg-danger mb-2">QRIS All Bank & E-Wallet</span>
                            <h6 class="fw-bold text-dark mb-1">NMID: ID102003920192</h6>
                            <p class="mb-0 small text-muted">KARYAKU QRIS RESMI</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('pembeli.pesanan.bayar', $order->id_order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Metode Pembayaran Digunakan <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select form-select-sm rounded-3" required>
                                <option value="">Pilih Metode</option>
                                <option value="Bank BCA" {{ old('payment_method', $order->payment_method) === 'Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA</option>
                                <option value="Bank BNI" {{ old('payment_method', $order->payment_method) === 'Bank BNI' ? 'selected' : '' }}>Transfer Bank BNI</option>
                                <option value="Bank Mandiri" {{ old('payment_method', $order->payment_method) === 'Bank Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                                <option value="Bank BRI" {{ old('payment_method', $order->payment_method) === 'Bank BRI' ? 'selected' : '' }}>Transfer Bank BRI</option>
                                <option value="QRIS" {{ old('payment_method', $order->payment_method) === 'QRIS' ? 'selected' : '' }}>Scan QRIS</option>
                                <option value="GoPay" {{ old('payment_method', $order->payment_method) === 'GoPay' ? 'selected' : '' }}>E-Wallet GoPay</option>
                                <option value="DANA" {{ old('payment_method', $order->payment_method) === 'DANA' ? 'selected' : '' }}>E-Wallet DANA</option>
                                <option value="OVO" {{ old('payment_method', $order->payment_method) === 'OVO' ? 'selected' : '' }}>E-Wallet OVO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Upload Resi / Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" class="form-control form-control-sm rounded-3" accept="image/*" required>
                            <span class="text-muted text-xs">Format JPG, PNG, WEBP (Maks 4 MB).</span>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white btn-kirim-bukti">
                            <i class="bi bi-send-fill me-1"></i> Kirim Bukti Pembayaran ke Verifikator
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- DAFTAR PRODUK YANG DIBELI --}}
        <div class="card-box p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-3 text-dark">Daftar Produk yang Dibeli</h6>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Produk</th>
                            <th>Penjual</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            @if($order->payment_status === 'paid')
                                <th class="text-center">Aksi Berkas</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            @php $product = $item->product; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product && $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/100x100?text=Produk' }}" 
                                             alt="{{ $product->title ?? 'Produk' }}" 
                                             class="rounded-3 object-fit-cover border flex-shrink-0" 
                                             style="width: 50px; height: 50px;"
                                             onerror="this.src='https://placehold.co/100x100?text=Produk'">
                                        <div class="overflow-hidden">
                                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 13.5px; max-width: 220px;">
                                                <a href="{{ $product ? route('pembeli.produk.detail', $product->id_product) : '#' }}" class="text-dark text-decoration-none">{{ $product->title ?? 'Produk Digital' }}</a>
                                            </h6>
                                            <span class="text-muted small">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-secondary">
                                        {{ $product->seller->name ?? 'Kreator Karyaku' }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-primary">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                                @if($order->payment_status === 'paid')
                                    <td class="text-center">
                                        @if($product && $product->file)
                                            <a href="{{ route('pembeli.download.file', $item->id_order_item) }}" class="btn btn-success btn-sm fw-bold px-2 py-1 rounded-2">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">TIDAK ADA FILE</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: RINGKASAN PEMBAYARAN --}}
    <div class="col-lg-4">
        <div class="card-box p-4">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Ringkasan Tagihan</h6>
            
            <div class="d-flex justify-content-between mb-2 small text-muted">
                <span>Subtotal Produk:</span>
                <span class="text-dark fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 small text-muted">
                <span>Biaya Layanan:</span>
                <span class="text-success fw-semibold">Rp 0</span>
            </div>
            
            <hr class="my-3">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="text-dark">Total Pembayaran:</strong>
                <span class="h4 fw-extrabold text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            <div class="p-3 bg-light rounded-3 small text-muted mt-3">
                <div class="d-flex align-items-center gap-2 mb-1 text-dark fw-bold">
                    <i class="bi bi-shield-check text-success"></i> Jaminan Verifikasi Platform
                </div>
                Transaksi Anda diverifikasi langsung oleh <strong>Tim Verifikator</strong>. File dapat diunduh begitu status diverifikasi lunas.
            </div>
        </div>
    </div>
</div>

@endsection

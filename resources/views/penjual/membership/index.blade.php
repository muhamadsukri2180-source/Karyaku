@extends('layouts.penjual')
@section('title', 'Paket Membership Penjual')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e3a8a;
        --primary-light: #eff6ff;
        --primary-soft: #dbeafe;
        --border-color: #e2e8f0;
        --shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        --shadow-hover: 0 14px 30px rgba(15, 23, 42, 0.12);
        --text-muted: #64748b;
        --text-dark: #1e293b;
    }

    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { color: var(--text-muted); }

    .kk-card {
        background: #fff;
        border: 1px solid var(--border-color) !important;
        border-radius: 20px;
        box-shadow: var(--shadow);
        transition: all .25s ease;
    }
    .kk-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .kk-membership {
        position: relative;
        transition: transform .25s ease, box-shadow .25s ease;
        border-radius: 20px;
    }
    .kk-membership:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        background: #f8fafc;
        cursor: pointer;
        transition: all .2s ease;
        text-align: center;
        padding: 24px 16px;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--primary);
        background: #eff6ff;
    }

    .bank-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        transition: all .2s ease;
        cursor: pointer;
    }
    .bank-card:hover {
        border-color: var(--primary);
        background: #f8fafc;
    }
    .bank-card.active {
        border-color: var(--primary);
        background: #eff6ff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    .copy-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-size: 11px;
        font-weight: 700;
        border-radius: 8px;
        padding: 4px 8px;
        transition: all .15s ease;
    }
    .copy-btn:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
</style>

<div class="seller-page-head mb-4 text-center text-md-start">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="bi bi-gem me-2" style="color:var(--primary);"></i>Pilihan Paket Membership</h4>
            <p class="small mb-0">Pilih paket terbaik untuk menambah kuota unggahan produk, masa aktif, dan fitur iklan promosi toko Anda.</p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill shadow-sm" style="background:#e0e7ff; color:#3730a3; font-size:12px;">
                <i class="bi bi-shield-check me-1"></i> Transaksi Aman & Terverifikasi
            </span>
        </div>
    </div>
</div>

{{-- ALERT TRANSAKSI DITOLAK SEBELUMNYA (JIKA ADA) --}}
@if($lastRejectedPayment)
    <div class="alert alert-danger alert-dismissible fade show kk-card p-4 mb-4 border-0 border-start border-4 border-danger shadow-sm" role="alert">
        <div class="d-flex flex-column flex-md-row align-items-start gap-3">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center shrink-0" style="background:#fee2e2; color:#dc2626; width:44px; height:44px;">
                <i class="bi bi-exclamation-octagon-fill fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-danger mb-1">Pembayaran Paket Sebelumnya Ditolak</h6>
                <p class="small text-muted mb-2">
                    Pengajuan pembayaran untuk paket <strong>{{ $lastRejectedPayment->membership->name ?? 'Membership' }}</strong> ditolak oleh verifikator.
                </p>
                @if($lastRejectedPayment->notes)
                    <div class="p-2.5 rounded-3 bg-white border border-danger-subtle text-danger small mb-2">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Catatan Verifikator:</strong> {{ $lastRejectedPayment->notes }}
                    </div>
                @endif
                <p class="small text-muted mb-0">Silakan pilih kembali paket di bawah dan pastikan bukti transfer yang diunggah jelas serta nominal sesuai.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

{{-- BANNER TRANSAKSI SEDANG PENDING VERIFIKASI --}}
@if($pendingPayment)
    <div class="kk-card p-4 mb-4 border-0 border-start border-4 border-warning shadow-sm" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center shrink-0" style="background:#fde68a; color:#b45309; width:50px; height:50px;">
                    <i class="bi bi-hourglass-split fs-3 animate__animated animate__pulse animate__infinite"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge px-2.5 py-1 rounded-pill fw-bold" style="background:#f59e0b; color:#fff; font-size:11px;">
                            <i class="bi bi-clock-history me-1"></i> MENUNGGU VERIFIKASI ADMIN
                        </span>
                        <span class="text-muted small">&bull; Diajukan {{ $pendingPayment->submitted_at ? $pendingPayment->submitted_at->diffForHumans() : 'baru saja' }}</span>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#78350f;">
                        Pembayaran Paket {{ $pendingPayment->membership->name ?? 'Membership' }} Sedang Diproses
                    </h5>
                    <div class="small" style="color:#92400e;">
                        Nominal Tagihan: <strong class="text-dark">Rp {{ number_format($pendingPayment->payment_amount ?? 0, 0, ',', '.') }}</strong>
                        &bull; Metode: <strong>{{ $pendingPayment->payment_method ?? 'Transfer Bank' }}</strong>
                        &bull; Atas Nama: <strong>{{ $pendingPayment->account_name ?? $user->name }}</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if($pendingPayment->payment_proof)
                    <button type="button" class="btn btn-sm btn-light border fw-bold px-3 py-2 rounded-3 shadow-sm" onclick="openProofModal('{{ asset('storage/' . $pendingPayment->payment_proof) }}', '{{ $pendingPayment->membership->name ?? 'Membership' }}')">
                        <i class="bi bi-image me-1 text-primary"></i> Lihat Bukti Transfer
                    </button>
                @endif
                <form action="{{ route('penjual.membership.cancel') }}" method="POST" onsubmit="return confirm('Batalkan pengajuan perpanjangan/pembayaran ini? Bukti transfer yang diunggah akan dihapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3 py-2 rounded-3">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- PAKET SAAT INI --}}
<div class="kk-card p-4 mb-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-4 p-3 d-flex align-items-center justify-content-center shrink-0" style="width: 60px; height: 60px; background:var(--primary-light); color:var(--primary);">
                <i class="bi bi-person-badge-fill fs-2"></i>
            </div>
            <div>
                <span class="badge fw-bold px-2.5 py-1 mb-1" style="background:var(--primary-light); color:var(--primary); font-size:11px;">
                    STATUS MEMBERSHIP TOKO
                </span>
                <h5 class="fw-bold mb-1" style="color:var(--text-dark);">{{ $currentMembership->name ?? 'Paket Standar' }}</h5>
                <div class="small" style="color:var(--text-muted);">
                    @if($user->membership_expires_at)
                        Masa aktif berlaku sampai <strong style="color:var(--text-dark);">{{ $user->membership_expires_at->translatedFormat('d F Y') }}</strong>
                        @if(!$isExpired)
                            <span class="badge bg-light text-dark border ms-1">{{ $remainingDays }} hari lagi</span>
                        @endif
                    @else
                        Masa aktif permanen / paket dasar.
                    @endif
                    &bull; Penggunaan Kuota: <strong class="text-primary">{{ $totalUploaded }} / {{ $maxUpload }} Produk</strong>
                </div>
            </div>
        </div>

        <div>
            @if($isExpired)
                <span class="badge p-2.5 fs-6 rounded-3" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5;">
                    <i class="bi bi-x-circle me-1"></i> Paket Kedaluwarsa
                </span>
            @else
                <span class="badge p-2.5 fs-6 rounded-3" style="background:#ecfdf5; color:#16a34a; border:1px solid #a7f3d0;">
                    <i class="bi bi-check-circle me-1"></i> Paket Aktif
                </span>
            @endif
        </div>
    </div>
</div>

{{-- DAFTAR PAKET MEMBERSHIP DARI ADMIN --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color:var(--text-dark);">
        <i class="bi bi-stars text-warning me-2"></i>Katalog Paket Pilihan
    </h5>
    <span class="small text-muted">Pilih paket untuk melakukan perpanjangan atau upgrade kuota</span>
</div>

<div class="row g-4 mb-5">
    @forelse($memberships as $m)
        @php
            $isCurrent = $user->id_membership == $m->id_membership && !$isExpired;
            $lower = strtolower($m->name);
            $isDiamond = str_contains($lower, 'diamond') || str_contains($lower, 'platinum');
            $isGold = str_contains($lower, 'gold');
        @endphp
        <div class="col-md-6 col-lg-3">
            <div class="kk-card kk-membership p-4 h-100 {{ $isDiamond ? 'shadow' : '' }} d-flex flex-column justify-content-between" style="{{ $isDiamond ? 'border-color: var(--primary) !important; background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);' : '' }}">
                @if($isDiamond)
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill px-3 py-1.5 shadow fw-bold" style="font-size: 10.5px; background:var(--primary); color:#fff; letter-spacing:.3px;">
                        <i class="bi bi-star-fill me-1"></i> REKOMENDASI UTAMA
                    </span>
                @endif

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="fw-bold mb-0" style="color: {{ $isDiamond ? 'var(--primary)' : 'var(--text-dark)' }};">{{ $m->name }}</h5>
                            @if($isCurrent)
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold mt-1" style="font-size:10px;">Paket Anda Saat Ini</span>
                            @endif
                        </div>
                        <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width:42px; height:42px; background: {{ $isDiamond ? 'var(--primary-light)' : ($isGold ? '#fff7ed' : '#f8fafc') }}; color: {{ $isDiamond ? 'var(--primary)' : ($isGold ? '#f59e0b' : '#64748b') }};">
                            <i class="bi {{ $isDiamond ? 'bi-gem' : ($isGold ? 'bi-award' : 'bi-shield-check') }} fs-5"></i>
                        </div>
                    </div>

                    <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="fw-bold mb-0" style="color:var(--text-dark);">Rp {{ number_format($m->price, 0, ',', '.') }}</h3>
                        </div>
                        <small style="color:var(--text-muted);"><i class="bi bi-calendar-check me-1"></i>Masa aktif <strong>{{ $m->duration_days }} hari</strong></small>
                    </div>

                    <ul class="list-unstyled small mb-4 d-flex flex-column gap-2.5" style="color:var(--text-muted);">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Batas Unggah: <strong class="text-dark">{{ $m->max_upload }} Produk</strong></span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Durasi Paket: <strong class="text-dark">{{ $m->duration_days }} Hari</strong></span>
                        </li>
                        @if($isDiamond || $isGold)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Fitur Iklan Video: <strong class="text-primary">Tersedia</strong></span>
                            </li>
                        @endif
                        @if($m->benefit)
                            @php
                                $benefitsList = explode('|', $m->benefit);
                            @endphp
                            @foreach($benefitsList as $b)
                                @if(trim($b))
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                        <span>{{ trim($b) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div>
                    @if($pendingPayment)
                        <button type="button" class="btn w-100 fw-bold py-2.5 rounded-3 btn-secondary" disabled>
                            <i class="bi bi-hourglass me-1"></i> Menunggu Verifikasi
                        </button>
                    @else
                        <button type="button" 
                            class="btn w-100 fw-bold py-2.5 rounded-3 transition shadow-sm {{ $isDiamond ? 'btn-primary' : ($isCurrent ? 'btn-outline-primary' : 'btn-dark') }}"
                            onclick="openPaymentModal({{ $m->id_membership }}, '{{ addslashes($m->name) }}', {{ $m->price }}, {{ $m->duration_days }}, {{ $m->max_upload }}, {{ $isCurrent ? 'true' : 'false' }})">
                            @if($isCurrent)
                                <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Ini
                            @else
                                <i class="bi bi-credit-card me-1"></i> {{ $isDiamond ? 'Upgrade ke Diamond' : 'Pilih & Bayar Paket' }}
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="kk-card p-5 text-center" style="color:var(--text-muted);">
                <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                <p class="mb-0">Belum ada paket membership yang dikonfigurasi oleh admin.</p>
            </div>
        </div>
    @endforelse
</div>


{{-- ========================================================================= --}}
{{-- MODAL PEMBAYARAN & UPLOAD BUKTI TRANSFER --}}
{{-- ========================================================================= --}}
<div class="modal fade" id="modalPayment" tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            {{-- MODAL HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-0 d-flex align-items-start justify-content-between">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill fw-bold mb-1" style="background:var(--primary-light); color:var(--primary); font-size:11px;">
                        <i class="bi bi-wallet2 me-1"></i> PEMBAYARAN MEMBERSHIP
                    </span>
                    <h5 class="modal-title fw-bold" id="modalPaymentLabel" style="color:var(--text-dark);">
                        Form Pembayaran & Perpanjangan Paket
                    </h5>
                    <p class="text-muted small mb-0">Lakukan pembayaran ke rekening resmi Karyaku dan lampirkan foto bukti transfer.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body px-4 py-4 space-y-4">

                    {{-- RINGKASAN PAKET TERPILIH --}}
                    <div class="p-3.5 rounded-4 mb-4 border" style="background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%); border-color: #bae6fd !important;">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2.5 bg-white text-primary shadow-sm d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                    <i class="bi bi-gem fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small fw-semibold" id="modalPlanType">Paket Terpilih</span>
                                    <h5 class="fw-bold mb-0 text-dark" id="modalPlanName">-</h5>
                                    <small class="text-muted" id="modalPlanMeta">Durasi 30 Hari &bull; Batas 10 Produk</small>
                                </div>
                            </div>
                            <div class="text-sm-end">
                                <span class="text-muted small d-block">Total yang Harus Dibayar:</span>
                                <h4 class="fw-bold text-primary mb-0 font-monospace" id="modalPlanPrice">Rp 0</h4>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI REKENING TUJUAN KARYAKU --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center justify-content-between mb-2">
                            <span><i class="bi bi-building-fill-check text-primary me-1"></i> Rekening Resmi Tujuan Transfer Karyaku</span>
                            <span class="badge bg-light text-muted border fw-normal" style="font-size:11px;">Pilih salah satu & salin no. rekening</span>
                        </label>

                        <div class="row g-2.5">
                            @foreach($paymentMethods as $key => $pm)
                                <div class="col-sm-6">
                                    <div class="bank-card p-3 d-flex align-items-center justify-content-between" onclick="selectPaymentMethod('{{ $key }}', '{{ $pm['name'] }}', '{{ $pm['account_number'] }}', '{{ $pm['account_name'] }}')" id="bank_card_{{ $key }}">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center shrink-0" style="width:36px; height:36px; background:{{ $pm['color'] }}15; color:{{ $pm['color'] }};">
                                                <i class="bi {{ $pm['icon'] }} fs-5"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-truncate" style="font-size:12.5px;">{{ $pm['name'] }}</div>
                                                <div class="text-muted font-monospace text-truncate" style="font-size:11.5px;">{{ $pm['account_number'] }}</div>
                                                <div class="text-muted text-truncate" style="font-size:10px;">a.n {{ $pm['account_name'] }}</div>
                                            </div>
                                        </div>
                                        <button type="button" class="copy-btn shrink-0 ms-2" onclick="event.stopPropagation(); copyToClipboard('{{ $pm['account_number'] }}', this)" title="Salin Nomor Rekening">
                                            <i class="bi bi-clipboard me-1"></i> Salin
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PILIHAN METODE PEMBAYARAN --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="payment_method_input" class="form-label fw-bold small text-dark">
                                Metode Pembayaran yang Digunakan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3 text-sm py-2.5" id="payment_method_input" name="payment_method" required onchange="onPaymentMethodChange(this.value)">
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                @foreach($paymentMethods as $key => $pm)
                                    <option value="{{ $pm['name'] }}" data-bank="{{ $key }}">{{ $pm['name'] }} ({{ $pm['type'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="sender_name_input" class="form-label fw-bold small text-dark">
                                Nama Pemilik Rekening Pengirim <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <input type="text" class="form-control rounded-3 py-2.5 text-sm" id="sender_name_input" name="sender_name" value="{{ $user->name }}" placeholder="Contoh: Budi Santoso">
                        </div>
                    </div>

                    {{-- UNGGAH FOTO BUKTI TRANSFER --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center justify-content-between mb-1">
                            <span><i class="bi bi-camera-fill text-primary me-1"></i> Unggah Foto Bukti Transfer <span class="text-danger">*</span></span>
                            <span class="text-muted small">Format: JPG, JPEG, PNG, WEBP (Maks. 3 MB)</span>
                        </label>

                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('proofInput').click()">
                            <input type="file" id="proofInput" name="payment_proof" accept="image/jpeg,image/png,image/webp,image/jpg" class="d-none" required onchange="previewProofImage(this)">
                            
                            <div id="uploadPrompt">
                                <div class="rounded-circle bg-white text-primary shadow-sm mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="bi bi-cloud-arrow-up-fill fs-3"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Klik untuk Memilih Foto Bukti Transfer</h6>
                                <p class="text-muted small mb-0">atau seret dan lepas berkas gambar resi/screenshot pembayaran ke sini</p>
                            </div>

                            <div id="uploadPreview" class="d-none">
                                <div class="position-relative d-inline-block">
                                    <img id="previewImg" src="" alt="Preview Bukti Transfer" class="img-fluid rounded-3 border shadow-sm" style="max-height: 220px; object-fit: contain;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" style="width:28px; height:28px;" onclick="event.stopPropagation(); removeProofImage()" title="Hapus Foto">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <div class="mt-2 small text-dark fw-semibold" id="fileNameText"></div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size:11px;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Foto Siap Dikirim
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- CATATAN KETENTUAN --}}
                    <div class="p-3 bg-light rounded-3 text-muted" style="font-size:11.5px;">
                        <i class="bi bi-info-circle text-primary me-1"></i>
                        Setelah Anda mengirim bukti pembayaran, verifikator kami akan memeriksa transaksi dalam waktu maksimal 1x24 jam. Kuota dan masa aktif paket Anda akan langsung otomatis bertambah setelah disetujui.
                    </div>

                </div>

                {{-- MODAL FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold px-4 py-2.5 rounded-3 border" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitPayment" class="btn btn-primary fw-bold px-4 py-2.5 rounded-3 shadow">
                        <i class="bi bi-send-fill me-1"></i> Kirim Bukti Pembayaran
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- ========================================================================= --}}
{{-- MODAL VIEW BUKTI TRANSFER (UNTUK PREVIEW STATUS PENDING) --}}
{{-- ========================================================================= --}}
<div class="modal fade" id="modalViewProof" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="modalViewProofTitle">Bukti Transfer Pembayaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="viewProofImg" src="" alt="Bukti Transfer" class="img-fluid rounded-3 border shadow-sm" style="max-height:420px; object-fit:contain;">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-bold px-4 rounded-3 border" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Buka Modal Pembayaran dengan data paket yang dipilih
    function openPaymentModal(membershipId, name, price, duration, maxUpload, isCurrent) {
        const form = document.getElementById('paymentForm');
        form.action = "{{ url('penjual/membership') }}/" + membershipId + "/purchase";

        document.getElementById('modalPlanName').innerText = name;
        document.getElementById('modalPlanType').innerText = isCurrent ? 'Perpanjangan Paket Aktif' : 'Pilihan Paket Baru';
        document.getElementById('modalPlanMeta').innerText = 'Masa Aktif ' + duration + ' Hari • Batas Kuota ' + maxUpload + ' Produk';
        document.getElementById('modalPlanPrice').innerText = 'Rp ' + Number(price).toLocaleString('id-ID');

        // Reset file upload state
        removeProofImage();

        const modal = new bootstrap.Modal(document.getElementById('modalPayment'));
        modal.show();
    }

    // Pilih Metode Pembayaran dari kartu bank
    function selectPaymentMethod(key, name, accNumber, accName) {
        document.querySelectorAll('.bank-card').forEach(c => c.classList.remove('active'));
        const card = document.getElementById('bank_card_' + key);
        if (card) card.classList.add('active');

        const select = document.getElementById('payment_method_input');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].getAttribute('data-bank') === key || select.options[i].value === name) {
                select.selectedIndex = i;
                break;
            }
        }
    }

    function onPaymentMethodChange(val) {
        document.querySelectorAll('.bank-card').forEach(c => c.classList.remove('active'));
        const select = document.getElementById('payment_method_input');
        const selectedOption = select.options[select.selectedIndex];
        const key = selectedOption.getAttribute('data-bank');
        if (key) {
            const card = document.getElementById('bank_card_' + key);
            if (card) card.classList.add('active');
        }
    }

    // Fitur Salin Nomor Rekening
    function copyToClipboard(text, btnElement) {
        // Hapus NMID: atau format teks jika ada
        const cleanNumber = text.replace(/[^0-9]/g, '');
        const copyText = cleanNumber ? cleanNumber : text;

        navigator.clipboard.writeText(copyText).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
            btnElement.style.background = '#10b981';
            btnElement.style.color = '#fff';
            btnElement.style.borderColor = '#10b981';

            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.style.background = '';
                btnElement.style.color = '';
                btnElement.style.borderColor = '';
            }, 2000);
        }).catch(() => {
            alert('Nomor Rekening: ' + text);
        });
    }

    // Preview Foto Bukti Transfer
    function previewProofImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validasi ukuran (3 MB)
            if (file.size > 3 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar! Maksimal ukuran file adalah 3 MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('fileNameText').innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                document.getElementById('uploadPrompt').classList.add('d-none');
                document.getElementById('uploadPreview').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeProofImage() {
        const input = document.getElementById('proofInput');
        if (input) input.value = '';
        document.getElementById('previewImg').src = '';
        document.getElementById('fileNameText').innerText = '';
        document.getElementById('uploadPrompt').classList.remove('d-none');
        document.getElementById('uploadPreview').classList.add('d-none');
    }

    // Drag and drop events for upload zone
    const dropZone = document.getElementById('uploadZone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                const input = document.getElementById('proofInput');
                input.files = files;
                previewProofImage(input);
            }
        }, false);
    }

    // Buka Modal Bukti Transfer dari Banner Pending
    function openProofModal(imgUrl, planName) {
        document.getElementById('viewProofImg').src = imgUrl;
        document.getElementById('modalViewProofTitle').innerText = 'Bukti Pembayaran - Paket ' + planName;
        const modal = new bootstrap.Modal(document.getElementById('modalViewProof'));
        modal.show();
    }

    // Form submit confirmation & loading state
    document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
        const proofInput = document.getElementById('proofInput');
        if (!proofInput.files || !proofInput.files[0]) {
            e.preventDefault();
            alert('Silakan pilih foto bukti transfer pembayaran terlebih dahulu.');
            return false;
        }

        const btn = document.getElementById('btnSubmitPayment');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim Bukti...';
    });
</script>
@endpush
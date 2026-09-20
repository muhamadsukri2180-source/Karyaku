@extends('layouts.pembeli')
@section('title', 'Daftar Sebagai Penjual - Karyaku')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .seller-wizard-container {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .wizard-header-bg {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        padding: 32px;
        color: #ffffff;
        position: relative;
    }
    .step-section {
        display: none;
    }
    .step-section.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        padding: 16px 24px;
    }
    .step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    .step-badge.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }
    .step-badge.completed {
        background: #10b981;
        color: #ffffff;
    }
    .upload-zone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        background-color: #f8fafc;
        padding: 24px 16px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        overflow: hidden;
    }
    .upload-zone:hover {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .upload-preview {
        display: none;
        position: relative;
        z-index: 5;
    }
    .upload-preview img {
        max-height: 140px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }
    .plan-card-option {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        position: relative;
    }
    .plan-card-option:hover {
        border-color: #93c5fd;
        background: #f8fafc;
        transform: translateY(-2px);
    }
    .plan-card-option.active {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.08);
    }
    .plan-price-tag {
        font-size: 16px;
        font-weight: 800;
        color: #2563eb;
    }
    .checkout-summary-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 16px;
    }
    .seller-wizard-container .form-control,
    .seller-wizard-container .form-select {
        background-color: #f1f5f9 !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b;
        font-weight: 500;
    }
    .seller-wizard-container .form-control:focus,
    .seller-wizard-container .form-select:focus {
        background-color: #ffffff !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
</style>
@endpush

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white shadow-sm" style="width: 48px; height: 48px; flex-shrink: 0;">
            <i class="bi bi-shop-window fs-4"></i>
        </div>
        <div>
            <h4 class="fw-extrabold text-dark mb-0">Pendaftaran Penjual Karyaku</h4>
            <p class="text-muted small mb-0">Buka tokomu, jual aset digital premium, dan raih penghasilan tanpa batas.</p>
        </div>
    </div>
    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
        Kembali ke Dashboard
    </a>
</div>

<form id="formPendaftaran" action="{{ route('pembeli.seller.registration.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="seller-wizard-container">
        {{-- Progress Step Indicator --}}
        <div class="step-indicator">
            <div class="d-flex align-items-center gap-2">
                <div class="step-badge active" id="badge-1">1</div>
                <span class="small fw-bold text-dark d-none d-sm-inline">Data Diri & KTP</span>
            </div>
            <div class="text-muted opacity-50"><i class="bi bi-chevron-right"></i></div>
            <div class="d-flex align-items-center gap-2">
                <div class="step-badge" id="badge-2">2</div>
                <span class="small fw-semibold text-muted d-none d-sm-inline">Rekening Bank</span>
            </div>
            <div class="text-muted opacity-50"><i class="bi bi-chevron-right"></i></div>
            <div class="d-flex align-items-center gap-2">
                <div class="step-badge" id="badge-3">3</div>
                <span class="small fw-semibold text-muted d-none d-sm-inline">Paket & Pembayaran</span>
            </div>
        </div>

        <div class="p-4 p-md-5">
            {{-- STEP 1: DATA DIRI & KTP --}}
            <div class="step-section active" id="step-1">
                <div class="mb-4">
                    <h5 class="fw-extrabold text-dark mb-1"><i class="bi bi-person-badge-fill text-primary me-2"></i>Informasi Identitas Pemohon</h5>
                    <p class="text-muted small">Pastikan data diri dan foto KTP yang Anda unggah valid untuk mempercepat proses verifikasi.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">Nama Lengkap (Sesuai Akun)</label>
                        <input type="text" class="form-control rounded-3 py-2.5 bg-light" value="{{ $user->name }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">Email Terdaftar</label>
                        <input type="email" class="form-control rounded-3 py-2.5 bg-light" value="{{ (!empty($user->email) && !str_starts_with($user->email, '$') && str_contains($user->email, '@')) ? $user->email : '' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">NIK (16 Digit KTP) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" maxlength="16" inputmode="numeric"
                               class="form-control rounded-3 py-2.5 @error('nik') is-invalid @enderror"
                               value="{{ old('nik') }}" placeholder="Contoh: 3201xxxxxxxxxxxx" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone"
                               class="form-control rounded-3 py-2.5 @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark mb-1">Alamat Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                        <textarea name="address" rows="3"
                                  class="form-control rounded-3 @error('address') is-invalid @enderror"
                                  placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark mb-1">Upload Foto KTP Asli <span class="text-danger">*</span></label>
                        <div class="upload-zone @error('identity_document') is-invalid @enderror">
                            <input type="file" name="identity_document" id="input-ktp" accept="image/*" required onchange="handlePreview(this, 'preview-ktp', 'box-ktp', 'cancel-ktp')">
                            
                            <div id="preview-ktp" class="upload-preview">
                                <img src="" alt="Preview KTP" class="img-fluid mb-2">
                                <p class="text-primary small fw-bold mb-0"><i class="bi bi-arrow-repeat"></i> Klik untuk mengganti foto KTP</p>
                            </div>

                            <div id="box-ktp">
                                <i class="bi bi-cloud-arrow-up-fill text-primary fs-2 mb-2 d-block"></i>
                                <div class="fw-bold text-dark small mb-1">Pilih atau Seret Foto KTP di Sini</div>
                                <div class="text-muted" style="font-size: 11px;">Format: JPG, PNG, WEBP (Maksimal 3MB)</div>
                            </div>
                        </div>

                        <div id="cancel-ktp" class="text-center mt-2" style="display: none;">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1" onclick="clearUpload('input-ktp', 'preview-ktp', 'box-ktp', 'cancel-ktp')">
                                <i class="bi bi-trash3 me-1"></i> Batalkan Foto
                            </button>
                        </div>
                        @error('identity_document') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top">
                    <button type="button" class="btn btn-primary px-4.5 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" onclick="nextStep(2)">
                        Selanjutnya: Rekening Bank
                    </button>
                </div>
            </div>

            {{-- STEP 2: REKENING BANK --}}
            <div class="step-section" id="step-2">
                <div class="mb-4">
                    <h5 class="fw-extrabold text-dark mb-1"><i class="bi bi-bank2 text-primary me-2"></i>Rekening Pencairan Saldo</h5>
                    <p class="text-muted small">Masukkan rekening bank aktif atas nama Anda sendiri untuk memudahkan pencairan hasil penjualan produk.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
<<<<<<< HEAD
                        <label class="form-label small fw-bold text-dark mb-1">Bank <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select rounded-3 py-2.5 @error('bank_name') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih Bank Tujuan</option>
=======
                        <label class="form-label small fw-semibold text-dark mb-1">Bank <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select rounded-3 @error('bank_name') is-invalid @enderror" required>
                            <option value="">Pilih Bank</option>
>>>>>>> 841bec61725202c6848a92a7d418cf620928275d
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark mb-1">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control rounded-3 py-2.5 @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name', $user->name) }}" placeholder="Sesuai buku rekening" required>
                        @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark mb-1">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" inputmode="numeric"
                               class="form-control rounded-3 py-2.5 @error('account_number') is-invalid @enderror"
                               value="{{ old('account_number') }}" placeholder="Contoh: 1234567890" required>
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4.5 py-2.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" onclick="prevStep(1)">
                        Kembali
                    </button>
                    <button type="button" class="btn btn-primary px-4.5 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" onclick="nextStep(3)">
                        Selanjutnya: Paket & Pembayaran
                    </button>
                </div>
            </div>

            {{-- STEP 3: MEMBERSHIP & PEMBAYARAN --}}
            <div class="step-section" id="step-3">
                <div class="mb-4">
                    <h5 class="fw-extrabold text-dark mb-1"><i class="bi bi-gem text-primary me-2"></i>Pilih Paket & Selesaikan Pembayaran</h5>
                    <p class="text-muted small">Pilih paket membership toko dan unggah bukti transfer pendaftaran Anda.</p>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-dark mb-2">Pilih Paket Membership <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        @forelse ($memberships as $membership)
                            @php
                                $isSelected = old('membership_id', $selectedMembershipId) == $membership->id_membership;
                            @endphp
                            <div class="col-md-6">
                                <label class="plan-card-option h-100 d-block {{ $isSelected ? 'active' : '' }}">
                                    <div class="d-flex align-items-start gap-2">
                                        <input type="radio" name="membership_id" value="{{ $membership->id_membership }}"
                                               class="form-check-input mt-1 plan-radio"
                                               data-name="{{ $membership->name }}"
                                               data-price="{{ $membership->price }}"
                                               data-formatted-price="Rp {{ number_format($membership->price, 0, ',', '.') }}"
                                               {{ $isSelected ? 'checked' : '' }} required>

                                        <div class="flex-grow-1 ms-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark">{{ $membership->name }}</span>
                                                <span class="plan-price-tag">
                                                    Rp {{ number_format($membership->price, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <div class="text-muted mb-2" style="font-size: 11.5px;">
                                                <span class="badge bg-light text-dark border me-1">
                                                    <i class="bi bi-calendar3 me-1"></i>{{ $membership->duration_days }} Hari
                                                </span>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-cloud-upload me-1"></i>Maks {{ $membership->max_upload }} Produk
                                                </span>
                                            </div>

                                            @if ($membership->benefit)
                                                <div class="text-secondary small pt-2 border-top" style="font-size: 11px;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $membership->benefit }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning small mb-0">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum ada paket membership tersedia.
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @error('membership_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">Metode Transfer <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select rounded-3 py-2.5 @error('payment_method') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Rekening Tujuan Transfer</option>
                                @foreach ($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="checkout-summary-box mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">Paket Dipilih:</span>
                                <span class="small fw-bold text-dark" id="summaryPlanName">-</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Total Pembayaran:</span>
                                <span class="fw-extrabold text-primary fs-5" id="summaryTotalAmount">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">Upload Bukti Transfer <span class="text-danger">*</span></label>
                            <div class="upload-zone @error('payment_proof') is-invalid @enderror">
                                <input type="file" name="payment_proof" id="input-payment" accept="image/*" required onchange="handlePreview(this, 'preview-payment', 'box-payment', 'cancel-payment')">
                                
                                <div id="preview-payment" class="upload-preview">
                                    <img src="" alt="Preview Bukti" class="img-fluid mb-2">
                                    <p class="text-primary small fw-bold mb-0"><i class="bi bi-arrow-repeat"></i> Klik untuk mengganti bukti transfer</p>
                                </div>

                                <div id="box-payment">
                                    <i class="bi bi-receipt-cutoff text-primary fs-2 mb-2 d-block"></i>
                                    <div class="fw-bold text-dark small mb-1">Upload Struk / Bukti Transfer</div>
                                    <div class="text-muted" style="font-size: 11px;">Format: JPG, PNG, WEBP (Maksimal 3MB)</div>
                                </div>
                            </div>

                            <div id="cancel-payment" class="text-center mt-2" style="display: none;">
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1" onclick="clearUpload('input-payment', 'preview-payment', 'box-payment', 'cancel-payment')">
                                    <i class="bi bi-trash3 me-1"></i> Batalkan Bukti
                                </button>
                            </div>
                            @error('payment_proof') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between pt-4 border-top mt-3">
                    <button type="button" class="btn btn-outline-secondary px-4.5 py-2.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" onclick="prevStep(2)">
                        Kembali
                    </button>
                    <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" {{ $memberships->isEmpty() ? 'disabled' : '' }}>
                        Kirim Formulir Pendaftaran
                    </button>
                </div>
            </div>
<<<<<<< HEAD
=======

            {{-- 4. PEMBAYARAN & BUKTI TRANSFER --}}
            <div class="seller-form-card">
                <div class="seller-card-title">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                    <span>4. Metode & Bukti Pembayaran</span>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Metode Transfer <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select rounded-3 @error('payment_method') is-invalid @enderror" required>
                        <option value="">Pilih Rekening Tujuan Transfer</option>
                        @foreach ($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Total Ringkasan Biaya --}}
                <div class="checkout-summary-box mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Paket Dipilih:</span>
                        <span class="small fw-bold text-dark" id="summaryPlanName">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Total Pembayaran:</span>
                        <span class="fw-extrabold text-primary fs-5" id="summaryTotalAmount">Rp 0</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Upload Bukti Transfer <span class="text-danger">*</span></label>
                    <div class="upload-zone @error('payment_proof') is-invalid @enderror">
                        <input type="file" name="payment_proof" id="input-payment" accept="image/*" required onchange="handlePreview(this, 'preview-payment', 'box-payment', 'cancel-payment')">
                        
                        <div id="preview-payment" class="upload-preview">
                            <img src="" alt="Preview Bukti" class="img-fluid mb-2">
                            <p class="text-primary small fw-bold mb-0"><i class="bi bi-arrow-repeat"></i> Klik untuk mengganti bukti transfer</p>
                        </div>

                        <div id="box-payment">
                            <i class="bi bi-receipt-cutoff text-primary fs-2 mb-2 d-block"></i>
                            <div class="fw-bold text-dark small mb-1">Upload Struk / Bukti Transfer</div>
                            <div class="text-muted" style="font-size: 11px;">Format: JPG, PNG, WEBP (Maksimal 3MB)</div>
                        </div>
                    </div>

                    <div id="cancel-payment" class="text-center mt-2" style="display: none;">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1" onclick="clearUpload('input-payment', 'preview-payment', 'box-payment', 'cancel-payment')">
                            <i class="bi bi-trash3 me-1"></i> Batalkan Bukti
                        </button>
                    </div>
                    @error('payment_proof') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 mt-4" {{ $memberships->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-send-fill"></i> Kirim Formulir Pendaftaran
                </button>
            </div>

>>>>>>> 841bec61725202c6848a92a7d418cf620928275d
        </div>
    </div>
</form>

@push('scripts')
<script>
    function nextStep(step) {
        // Validasi sederhana sebelum pindah step jika di step 1 atau 2
        if (step === 2) {
            const nik = document.querySelector('input[name="nik"]').value;
            const phone = document.querySelector('input[name="phone"]').value;
            const address = document.querySelector('textarea[name="address"]').value;
            const ktp = document.querySelector('input[name="identity_document"]').files.length;
            
            if (!nik || !phone || !address || (ktp === 0 && !document.querySelector('#preview-ktp img').src)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Data',
                    text: 'Mohon isi semua data diri dan unggah foto KTP terlebih dahulu.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }
        }

        document.querySelectorAll('.step-section').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');

        // Update badge indicator
        for (let i = 1; i <= 3; i++) {
            const badge = document.getElementById('badge-' + i);
            badge.classList.remove('active', 'completed');
            if (i < step) {
                badge.classList.add('completed');
                badge.innerHTML = '<i class="bi bi-check"></i>';
            } else if (i === step) {
                badge.classList.add('active');
                badge.innerText = i;
            } else {
                badge.innerText = i;
            }
        }
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function prevStep(step) {
        document.querySelectorAll('.step-section').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');

        for (let i = 1; i <= 3; i++) {
            const badge = document.getElementById('badge-' + i);
            badge.classList.remove('active', 'completed');
            if (i < step) {
                badge.classList.add('completed');
                badge.innerHTML = '<i class="bi bi-check"></i>';
            } else if (i === step) {
                badge.classList.add('active');
                badge.innerText = i;
            } else {
                badge.innerText = i;
            }
        }
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function handlePreview(input, previewId, boxId, cancelBtnId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(boxId).style.display = 'none';
                const previewContainer = document.getElementById(previewId);
                previewContainer.style.display = 'block';
                previewContainer.querySelector('img').src = e.target.result;
                document.getElementById(cancelBtnId).style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            clearUpload(input.id, previewId, boxId, cancelBtnId);
        }
    }

    function clearUpload(inputId, previewId, boxId, cancelBtnId) {
        document.getElementById(inputId).value = "";
        document.getElementById(previewId).style.display = 'none';
        document.getElementById(cancelBtnId).style.display = 'none';
        document.getElementById(boxId).style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.plan-radio');
        const cards = document.querySelectorAll('.plan-card-option');
        const summaryTotal = document.getElementById('summaryTotalAmount');
        const summaryPlanName = document.getElementById('summaryPlanName');

        function updatePlanSelection() {
            radios.forEach((radio, idx) => {
                if (radio.checked) {
                    cards[idx].classList.add('active');
                    if (summaryTotal) summaryTotal.innerText = radio.dataset.formattedPrice;
                    if (summaryPlanName) summaryPlanName.innerText = radio.dataset.name;
                } else {
                    cards[idx].classList.remove('active');
                }
            });
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updatePlanSelection);
        });

        updatePlanSelection();
    });

    document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Kirim Pendaftaran Penjual?',
            text: 'Pastikan data identitas dan bukti pembayaran Anda sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim Sekarang',
            cancelButtonText: 'Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
                this.submit();
            }
        });
    });
</script>
@endpush

@endsection

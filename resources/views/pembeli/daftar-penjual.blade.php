@extends('layouts.pembeli')
@section('title', 'Daftar Sebagai Penjual - Karyaku')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Card Container */
    .seller-form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
    }
    .seller-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .seller-card-title i {
        font-size: 18px;
        color: #2563eb;
    }

    /* Modern Upload Box */
    .upload-zone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        background-color: #f8fafc;
        padding: 26px 16px;
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
        max-height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }

    /* Membership Selection Cards */
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

    /* Checkout summary */
    .checkout-summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
    }
</style>
@endpush

@section('content')

{{-- Header Banner --}}
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
    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3 px-3 py-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<form id="formPendaftaran" action="{{ route('pembeli.seller.registration.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- KOLOM KIRI (DATA DIRI & REKENING) --}}
        <div class="col-lg-7">
            
            {{-- 1. DATA IDENTITAS --}}
            <div class="seller-form-card">
                <div class="seller-card-title">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>1. Data Diri & Verifikasi KTP</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Nama Lengkap (Sesuai Akun)</label>
                        <input type="text" class="form-control rounded-3 bg-light" value="{{ $user->name }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Email Terdaftar</label>
                        <input type="email" class="form-control rounded-3 bg-light" value="{{ (!empty($user->email) && !str_starts_with($user->email, '$') && str_contains($user->email, '@')) ? $user->email : '' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark mb-1">NIK (16 Digit KTP) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" maxlength="16" inputmode="numeric"
                               class="form-control rounded-3 @error('nik') is-invalid @enderror"
                               value="{{ old('nik') }}" placeholder="Contoh: 3201xxxxxxxxxxxx" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark mb-1">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone"
                               class="form-control rounded-3 @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold text-dark mb-1">Alamat Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                        <textarea name="address" rows="3"
                                  class="form-control rounded-3 @error('address') is-invalid @enderror"
                                  placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold text-dark mb-1">Upload Foto KTP Asli <span class="text-danger">*</span></label>
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
            </div>

            {{-- 2. REKENING PENCAIRAN --}}
            <div class="seller-form-card">
                <div class="seller-card-title">
                    <i class="bi bi-bank2"></i>
                    <span>2. Rekening Bank (Untuk Pencairan Saldo Penjual)</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark mb-1">Bank <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select rounded-3 @error('bank_name') is-invalid @enderror" required>
                            <option value="">Pilih Bank</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark mb-1">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control rounded-3 @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name', $user->name) }}" placeholder="Sesuai buku rekening" required>
                        @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark mb-1">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" inputmode="numeric"
                               class="form-control rounded-3 @error('account_number') is-invalid @enderror"
                               value="{{ old('account_number') }}" placeholder="Contoh: 1234567890" required>
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (MEMBERSHIP & PEMBAYARAN) --}}
        <div class="col-lg-5">

            {{-- 3. PILIH PAKET MEMBERSHIP --}}
            <div class="seller-form-card">
                <div class="seller-card-title">
                    <i class="bi bi-gem"></i>
                    <span>3. Pilih Paket Membership</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse ($memberships as $membership)
                        @php
                            $isSelected = old('membership_id', $selectedMembershipId) == $membership->id_membership;
                        @endphp
                        <label class="plan-card-option {{ $isSelected ? 'active' : '' }}">
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

                                    <div class="text-muted" style="font-size: 11.5px;">
                                        <span class="badge bg-light text-dark border me-1">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $membership->duration_days }} Hari
                                        </span>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-cloud-upload me-1"></i>Maks {{ $membership->max_upload }} Produk
                                        </span>
                                    </div>

                                    @if ($membership->benefit)
                                        <div class="text-secondary small mt-2 pt-2 border-top" style="font-size: 11px;">
                                            <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $membership->benefit }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="alert alert-warning small mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum ada paket membership tersedia.
                        </div>
                    @endforelse
                </div>
                @error('membership_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            </div>

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

        </div>
    </div>
</form>

@push('scripts')
<script>
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

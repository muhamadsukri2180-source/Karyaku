@extends('layouts.pembeli')
@section('title', 'Daftar Sebagai Penjual')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Styling untuk Kotak Upload File */
    .upload-area {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background-color: #f8fafc;
        padding: 2rem 1rem;
        text-align: center;
        transition: all 0.3s ease-in-out;
        cursor: pointer;
        overflow: hidden;
    }
    .upload-area:hover {
        border-color: #0d6efd;
        background-color: #eff6ff;
    }
    .upload-area.is-invalid {
        border-color: #dc3545;
        background-color: #fff1f2;
    }
    .upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .preview-container {
        display: none;
        position: relative;
        z-index: 5;
    }
    .preview-image {
        max-height: 180px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .upload-placeholder i {
        font-size: 2.5rem;
        color: #94a3b8;
        transition: color 0.3s ease;
    }
    .upload-area:hover .upload-placeholder i {
        color: #0d6efd;
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary text-white shadow-sm" style="width:48px;height:48px;">
            <i class="bi bi-shop-window fs-4"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Formulir Pendaftaran Penjual</h4>
            <p class="text-muted mb-0 small">Lengkapi data diri, pilih paket membership, dan lakukan pembayaran untuk menjadi penjual di Karyaku</p>
        </div>
    </div>
</div>

<form id="formPendaftaran" action="{{ route('pembeli.seller.registration.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        {{-- KOLOM KIRI: DATA DIRI & REKENING --}}
        <div class="col-lg-7">
            
            {{-- 1. DATA DIRI --}}
            <div class="card-box p-4 mb-4">
                <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard fs-5"></i> 1. Data Diri & Identitas
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">NIK (16 Digit KTP) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" maxlength="16" inputmode="numeric"
                               class="form-control @error('nik') is-invalid @enderror"
                               value="{{ old('nik') }}" placeholder="Contoh: 3201xxxxxxxxxxxx" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" rows="3"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Nama jalan, No. rumah, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Foto KTP <span class="text-danger">*</span></label>
                        
                        {{-- KOTAK UPLOAD KTP --}}
                        <div class="upload-area @error('identity_document') is-invalid @enderror">
                            <input type="file" name="identity_document" id="input-ktp" accept="image/*" class="upload-input" required onchange="previewFile(this, 'preview-ktp', 'placeholder-ktp', 'btn-cancel-ktp')">
                            
                            <div id="preview-ktp" class="preview-container">
                                <img src="" alt="Preview KTP" class="preview-image img-fluid">
                                <p class="text-primary small fw-bold mt-2 mb-0"><i class="bi bi-pencil-square"></i> Klik gambar untuk mengubah</p>
                            </div>
                            
                            <div id="placeholder-ktp" class="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <h6 class="mt-3 fw-bold text-dark">Klik atau Drag & Drop foto KTP di sini</h6>
                                <p class="text-muted small mb-0">Format: JPG, PNG, WEBP (Max: 3MB)</p>
                            </div>
                        </div>
                        
                        {{-- TOMBOL BATALKAN KTP --}}
                        <div id="btn-cancel-ktp" class="text-center mt-2" style="display: none;">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="cancelUpload('input-ktp', 'preview-ktp', 'placeholder-ktp', 'btn-cancel-ktp')">
                                <i class="bi bi-trash3"></i> Batalkan Foto KTP
                            </button>
                        </div>
                        @error('identity_document') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- 2. REKENING PENCAIRAN --}}
            <div class="card-box p-4 mb-4">
                <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-bank fs-5"></i> 2. Rekening Bank (Untuk Pencairan Saldo Penjualan)
                </h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bank <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror" required>
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name', $user->name) }}" placeholder="Sesuai buku tabungan" required>
                        @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" inputmode="numeric"
                               class="form-control @error('account_number') is-invalid @enderror"
                               value="{{ old('account_number') }}" placeholder="Nomor rekening" required>
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: PAKET & PEMBAYARAN --}}
        <div class="col-lg-5">

            {{-- 3. PILIH PAKET MEMBERSHIP --}}
            <div class="card-box p-4 mb-4">
                <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-gem fs-5"></i> 3. Pilih Paket Penjual
                </h6>

                <div class="d-flex flex-column gap-3">
                    @forelse ($memberships as $membership)
                        @php
                            $isSelected = old('membership_id', $selectedMembershipId) == $membership->id_membership;
                        @endphp
                        <label class="card p-3 border cursor-pointer position-relative transition-all membership-option {{ $isSelected ? 'border-primary bg-primary-light shadow-sm' : 'border-light-subtle' }}"
                               style="border-radius: 12px; cursor: pointer;">
                            <div class="form-check d-flex align-items-start gap-2 mb-0">
                                <input type="radio" name="membership_id" value="{{ $membership->id_membership }}"
                                       class="form-check-input flex-shrink-0 mt-1 membership-radio"
                                       data-price="{{ $membership->price }}"
                                       data-formatted-price="Rp {{ number_format($membership->price, 0, ',', '.') }}"
                                       {{ $isSelected ? 'checked' : '' }} required>
                                
                                <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark fs-6">{{ $membership->name }}</span>
                                        <span class="fw-bold text-primary fs-6">
                                            Rp {{ number_format($membership->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-clock me-1"></i> Masa Aktif: {{ $membership->duration_days }} Hari &middot; 
                                        <i class="bi bi-cloud-upload me-1"></i> Max: {{ $membership->max_upload }} Produk
                                    </div>
                                    @if ($membership->benefit)
                                        <div class="text-secondary small mt-2 pt-2 border-top">
                                            <i class="bi bi-check-circle-fill text-success me-1"></i> {{ $membership->benefit }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="alert alert-warning mb-0 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum ada paket membership penjual yang tersedia saat ini. Silakan hubungi admin.
                        </div>
                    @endforelse
                </div>
                @error('membership_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            </div>

            {{-- 4. METODE PEMBAYARAN & BUKTI --}}
            <div class="card-box p-4 mb-4">
                <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-credit-card-2-front fs-5"></i> 4. Metode Pembayaran & Bukti Transfer
                </h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Pilih Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_method" id="paymentMethodSelect" class="form-select @error('payment_method') is-invalid @enderror" required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        @foreach ($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- INSTRUKSI REKENING TUJUAN --}}
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Total Pembayaran Paket</span>
                        <span class="fw-bold text-danger fs-5" id="summaryTotalAmount">
                            Rp 0
                        </span>
                    </div>
                    <p class="small text-muted mb-0">
                        Silakan selesaikan pembayaran sesuai metode yang dipilih di atas, kemudian unggah bukti transfer di bawah.
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Upload Bukti Transfer / Pembayaran <span class="text-danger">*</span></label>
                    
                    {{-- KOTAK UPLOAD BUKTI BAYAR --}}
                    <div class="upload-area @error('payment_proof') is-invalid @enderror">
                        <input type="file" name="payment_proof" id="input-payment" accept="image/*" class="upload-input" required onchange="previewFile(this, 'preview-payment', 'placeholder-payment', 'btn-cancel-payment')">
                        
                        <div id="preview-payment" class="preview-container">
                            <img src="" alt="Preview Bukti Pembayaran" class="preview-image img-fluid">
                            <p class="text-primary small fw-bold mt-2 mb-0"><i class="bi bi-pencil-square"></i> Klik gambar untuk mengubah</p>
                        </div>
                        
                        <div id="placeholder-payment" class="upload-placeholder">
                            <i class="bi bi-receipt"></i>
                            <h6 class="mt-3 fw-bold text-dark">Klik atau Drag & Drop bukti transfer di sini</h6>
                            <p class="text-muted small mb-0">Format: JPG, PNG, WEBP (Max: 3MB)</p>
                        </div>
                    </div>

                    {{-- TOMBOL BATALKAN BUKTI BAYAR --}}
                    <div id="btn-cancel-payment" class="text-center mt-2" style="display: none;">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="cancelUpload('input-payment', 'preview-payment', 'placeholder-payment', 'btn-cancel-payment')">
                            <i class="bi bi-trash3"></i> Batalkan Bukti Transfer
                        </button>
                    </div>
                    @error('payment_proof') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 mt-2" {{ $memberships->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-send-fill"></i> Kirim Pendaftaran Penjual
                </button>
            </div>

        </div>

    </div>
</form>

@push('scripts')
<script>
    // --- FUNGSI PREVIEW GAMBAR KOTAK UPLOAD ---
    function previewFile(input, previewId, placeholderId, cancelBtnId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(placeholderId).style.display = 'none';
                const previewContainer = document.getElementById(previewId);
                previewContainer.style.display = 'block';
                previewContainer.querySelector('img').src = e.target.result;
                document.getElementById(cancelBtnId).style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            cancelUpload(input.id, previewId, placeholderId, cancelBtnId);
        }
    }

    function cancelUpload(inputId, previewId, placeholderId, cancelBtnId) {
        document.getElementById(inputId).value = "";
        document.getElementById(previewId).style.display = 'none';
        document.getElementById(cancelBtnId).style.display = 'none';
        document.getElementById(placeholderId).style.display = 'block';
    }

    // --- LOGIKA UPDATE TOTAL HARGA MEMBERSHIP ---
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.membership-radio');
        const options = document.querySelectorAll('.membership-option');
        const summaryTotal = document.getElementById('summaryTotalAmount');

        function updateSelectedSummary() {
            radios.forEach((radio, idx) => {
                if (radio.checked) {
                    options[idx].classList.add('border-primary', 'bg-primary-light', 'shadow-sm');
                    options[idx].classList.remove('border-light-subtle');
                    if (summaryTotal) {
                        summaryTotal.innerText = radio.dataset.formattedPrice;
                    }
                } else {
                    options[idx].classList.remove('border-primary', 'bg-primary-light', 'shadow-sm');
                    options[idx].classList.add('border-light-subtle');
                }
            });
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updateSelectedSummary);
        });

        updateSelectedSummary();
    });

    // --- NOTIFIKASI SWEETALERT SAAT TOMBOL KIRIM DITEKAN ---
    document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Kirim Pendaftaran?',
            text: "Pastikan data diri dan bukti pembayaran yang diunggah sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Batal Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                
                this.submit();
            }
        });
    });

    @if (session('success'))
        Swal.fire({ 
            icon: 'success', 
            title: 'Berhasil!', 
            text: "{{ session('success') }}", 
            timer: 3000, 
            showConfirmButton: false 
        });
    @endif
    @if (session('error'))
        Swal.fire({ 
            icon: 'error', 
            title: 'Gagal!', 
            text: "{{ session('error') }}", 
            confirmButtonColor: '#dc3545' 
        });
    @endif
    @if ($errors->any())
        Swal.fire({ 
            icon: 'warning', 
            title: 'Validasi Gagal!', 
            html: '<ul class="text-start mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>', 
            confirmButtonColor: '#0d6efd' 
        });
    @endif
</script>
@endpush

@endsection
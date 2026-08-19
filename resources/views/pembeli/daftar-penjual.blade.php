@extends('layouts.pembeli')
@section('title', 'Daftar Sebagai Penjual')

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

<form action="{{ route('pembeli.seller.registration.store') }}" method="POST" enctype="multipart/form-data">
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
                        <input type="file" name="identity_document" accept="image/*"
                               class="form-control @error('identity_document') is-invalid @enderror" required>
                        <div class="form-text text-muted small">Upload foto KTP asli, terlihat jelas dan tidak buram (Format: JPG, PNG, WEBP, maks 3MB).</div>
                        @error('identity_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <input type="file" name="payment_proof" accept="image/*"
                           class="form-control @error('payment_proof') is-invalid @enderror" required>
                    <div class="form-text text-muted small">Format JPG, PNG, WEBP, maksimal 3MB.</div>
                    @error('payment_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
</script>
@endpush

@endsection

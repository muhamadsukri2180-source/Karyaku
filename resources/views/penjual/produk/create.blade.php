@extends('layouts.penjual')

@section('title', 'Tambah Produk Baru')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-light: #eff6ff;
        --primary-hover: #1d4ed8;
        --border-color: #cbd5e1;
        --shadow: 0 4px 20px rgba(15, 23, 42, .05);
        --text-muted: #64748b;
        --text-dark: #1e293b;
        --bg-input: #f8fafc;
    }

    .kk-card {
        background: #ffffff;
        border: 1px solid var(--border-color) !important;
        border-radius: 16px;
        box-shadow: var(--shadow);
    }

    .kk-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .kk-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Judul kelompok isian di dalam satu form */
    .kk-section {
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px dashed #e2e8f0;
    }

    .kk-section:first-of-type {
        margin-top: 0;
        padding-top: 0;
        border-top: 0;
    }

    .kk-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 14px;
    }

    .kk-section-title i {
        color: var(--primary);
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border-radius: 10px;
        border-color: var(--border-color);
        background-color: var(--bg-input);
        font-size: 14px;
        color: var(--text-dark);
        transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border-color: var(--border-color);
        background-color: #e2e8f0;
        color: var(--text-dark);
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .kk-hint {
        display: block;
        margin-top: 6px;
        font-size: 11.5px;
        color: var(--text-muted);
    }

    /* ====== KOTAK UNGGAH (dropzone) ====== */
    .kk-drop {
        position: relative;
        border: 2px dashed var(--border-color);
        border-radius: 14px;
        background: var(--bg-input);
        transition: border-color .18s ease, background-color .18s ease;
    }

    .kk-drop:hover,
    .kk-drop:focus-within {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .kk-drop.is-dragover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .kk-drop.is-filled {
        border-style: solid;
        border-color: #bfdbfe;
        background: #ffffff;
    }

    .kk-drop.is-error {
        border-color: #dc3545;
        background: #fef2f2;
    }

    /* Input file asli disembunyikan, label jadi area klik */
    .kk-drop input[type="file"] {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        overflow: hidden;
        clip: rect(0 0 0 0);
    }

    .kk-drop-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 6px;
        padding: 26px 16px;
        margin: 0;
        cursor: pointer;
        min-height: 168px;
    }

    .kk-drop-cloud {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1px solid #dbeafe;
        color: var(--primary);
        font-size: 1.6rem;
        margin-bottom: 4px;
        transition: transform .18s ease;
    }

    .kk-drop:hover .kk-drop-cloud,
    .kk-drop.is-dragover .kk-drop-cloud {
        transform: translateY(-3px);
    }

    .kk-drop-title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .kk-drop-title span {
        color: var(--primary);
        text-decoration: underline;
    }

    .kk-drop-hint {
        font-size: 11.5px;
        color: var(--text-muted);
    }

    .kk-drop-files {
        padding: 0 14px 14px;
    }

    .kk-file-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-light);
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 12.5px;
        color: var(--text-dark);
    }

    .kk-file-chip i {
        color: var(--primary);
    }

    .kk-file-chip .kk-file-name {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .kk-file-size {
        color: var(--text-muted);
        font-size: 11.5px;
        flex-shrink: 0;
    }

    .kk-drop-reset {
        border: 0;
        background: transparent;
        color: var(--text-muted);
        line-height: 1;
        padding: 2px 4px;
        border-radius: 6px;
    }

    .kk-drop-reset:hover {
        color: #dc3545;
        background: #fee2e2;
    }

    .kk-preview {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }

    .kk-preview img {
        height: 84px;
        width: 84px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .kk-preview img.kk-preview-wide {
        width: 100%;
        max-width: 240px;
        height: 130px;
    }

    .kk-preview video {
        width: 100%;
        max-height: 170px;
        border-radius: 10px;
        background: #0f172a;
    }

    /* ====== SIDEBAR ====== */
    .kk-side {
        position: sticky;
        top: 20px;
    }

    .tip-card {
        background: var(--primary-light);
        border: 1px solid #dbeafe !important;
    }

    .kk-quota-bar {
        height: 8px;
        border-radius: 99px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .kk-quota-bar span {
        display: block;
        height: 100%;
        border-radius: 99px;
        background: var(--primary);
    }

    .kk-steps {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 12.5px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .kk-steps li {
        display: flex;
        gap: 10px;
        padding-bottom: 14px;
        position: relative;
    }

    .kk-steps li:not(:last-child)::before {
        content: "";
        position: absolute;
        left: 11px;
        top: 26px;
        bottom: 2px;
        width: 1px;
        background: #dbeafe;
    }

    .kk-step-dot {
        width: 23px;
        height: 23px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #dbeafe;
        color: var(--primary);
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ====== ACTION BAR ====== */
    .action-bar {
        border-top: 1px solid #e2e8f0;
        margin-top: 24px;
        padding-top: 20px;
    }

    .btn-karyaku {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        padding: 10px 26px;
        border-radius: 10px;
    }

    .btn-karyaku:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #fff;
    }

    @media (max-width: 767px) {
        .action-bar {
            flex-direction: column-reverse;
        }
        .action-bar .btn {
            width: 100%;
        }
        .kk-drop-label {
            min-height: 150px;
            padding: 20px 12px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .kk-drop-cloud,
        .form-control,
        .form-select {
            transition: none;
        }
    }
</style>

{{-- =========================================================
     HEADER
========================================================= --}}
<div class="mb-4">
    <a href="{{ route('penjual.produk.index') }}" class="btn btn-sm fw-semibold mb-3 rounded-pill px-3 shadow-sm text-white" style="background-color: var(--primary);">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h4 class="fw-bold mb-1" style="color: var(--text-dark);">
        <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Unggah produk digital baru
    </h4>
    <p class="small mb-0" style="color: var(--text-muted);">
        Isi seluruh detail karya Anda dalam satu formulir, lalu kirim untuk diverifikasi admin.
    </p>
</div>

{{-- =========================================================
     ERROR GLOBAL
========================================================= --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong class="d-block mb-1">Ada data yang perlu diperbaiki.</strong>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- =========================================================
     SATU FORM TERPADU
========================================================= --}}
<form action="{{ route('penjual.produk.store') }}" method="POST" enctype="multipart/form-data" id="formProduk">
    @csrf

    <div class="row g-4">

        {{-- ============ KOLOM KIRI ============ --}}
        <div class="col-lg-8">
            <div class="kk-card p-4">
                <div class="kk-card-header">
                    <div class="kk-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-dark);">Formulir informasi produk</h6>
                        <small style="color: var(--text-muted);">Detail, harga, stok, dan berkas karya dikirim sekaligus.</small>
                    </div>
                </div>

                {{-- ---------- BAGIAN 1: DETAIL ---------- --}}
                <div class="kk-section">
                    <div class="kk-section-title">
                        <i class="bi bi-card-text"></i> Detail karya
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="title">
                            Nama / judul karya digital <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Contoh: Template UI Dashboard Tailwind" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="category_id">
                                Kategori produk <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_category }}" {{ old('category_id') == $cat->id_category ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="kk-hint">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>Kategori mengikuti data aktif dari admin.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="description">
                                Deskripsi lengkap produk <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      style="min-height: 46px;"
                                      placeholder="Jelaskan fitur produk, format berkas, dan cara pemakaian." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ---------- BAGIAN 2: HARGA & STOK ---------- --}}
                <div class="kk-section">
                    <div class="kk-section-title">
                        <i class="bi bi-tag-fill"></i> Harga dan stok
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="price">
                                Harga <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}"
                                       min="1000" step="500"
                                       class="form-control @error('price') is-invalid @enderror"
                                       placeholder="50000" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="kk-hint" id="priceWords">Minimal Rp1.000 per produk.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="stock">
                                Jumlah stok <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 99) }}" min="1"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   placeholder="99" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="kk-hint">Untuk produk digital tanpa batas, isi angka besar seperti 999.</small>
                        </div>
                    </div>
                </div>

                {{-- ---------- BAGIAN 3: MEDIA & BERKAS ---------- --}}
                <div class="kk-section">
                    <div class="kk-section-title">
                        <i class="bi bi-images"></i> Media dan berkas
                    </div>

                    <div class="row g-3">

                        {{-- FOTO SAMPUL --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Foto sampul utama <span class="text-danger">*</span>
                            </label>
                            <div class="kk-drop @error('thumbnail') is-error @enderror" data-preview="image">
                                <input type="file" name="thumbnail" id="thumbInput"
                                       accept="image/png,image/jpeg,image/jpg,image/webp" required>
                                <label class="kk-drop-label" for="thumbInput">
                                    <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <span class="kk-drop-title">Seret foto ke sini atau <span>pilih berkas</span></span>
                                    <span class="kk-drop-hint">PNG, JPG, WEBP &middot; maks. 4 MB</span>
                                </label>
                                <div class="kk-drop-files d-none"></div>
                            </div>
                            @error('thumbnail')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FOTO PENDUKUNG --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Foto pendukung <span class="fw-normal" style="color: var(--text-muted);">(opsional)</span>
                            </label>
                            <div class="kk-drop @error('images') is-error @enderror @error('images.*') is-error @enderror" data-preview="images" data-max="4">
                                <input type="file" name="images[]" id="imagesInput"
                                       accept="image/png,image/jpeg,image/jpg,image/webp" multiple>
                                <label class="kk-drop-label" for="imagesInput">
                                    <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <span class="kk-drop-title">Seret foto ke sini atau <span>pilih berkas</span></span>
                                    <span class="kk-drop-hint">Hingga 4 foto &middot; PNG, JPG, WEBP</span>
                                </label>
                                <div class="kk-drop-files d-none"></div>
                            </div>
                            @error('images') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- VIDEO --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Video preview <span class="fw-normal" style="color: var(--text-muted);">(opsional)</span>
                            </label>
                            <div class="kk-drop @error('video') is-error @enderror" data-preview="video">
                                <input type="file" name="video" id="videoInput"
                                       accept="video/mp4,video/webm,video/ogg,video/quicktime">
                                <label class="kk-drop-label" for="videoInput">
                                    <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <span class="kk-drop-title">Seret video ke sini atau <span>pilih berkas</span></span>
                                    <span class="kk-drop-hint">MP4, WEBM &middot; maks. 50 MB</span>
                                </label>
                                <div class="kk-drop-files d-none"></div>
                            </div>
                            @error('video') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- BERKAS DIGITAL --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Berkas digital karya <span class="text-danger">*</span>
                            </label>
                            <div class="kk-drop @error('file') is-error @enderror" data-preview="file">
                                <input type="file" name="file" id="fileInput"
                                       accept=".zip,.rar,.pdf,.7z" required>
                                <label class="kk-drop-label" for="fileInput">
                                    <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <span class="kk-drop-title">Seret berkas ke sini atau <span>pilih berkas</span></span>
                                    <span class="kk-drop-hint">ZIP, RAR, PDF &middot; maks. 50 MB</span>
                                </label>
                                <div class="kk-drop-files d-none"></div>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ---------- TOMBOL AKSI ---------- --}}
                <div class="action-bar d-flex justify-content-end gap-2">
                    <a href="{{ route('penjual.produk.index') }}" class="btn btn-light fw-semibold px-4 rounded-3">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-karyaku fw-bold" id="btnSubmit">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah produk
                    </button>
                </div>

            </div>
        </div>

        {{-- ============ KOLOM KANAN ============ --}}
        <div class="col-lg-4">
            <div class="kk-side">

                {{-- ALUR VERIFIKASI --}}
                <div class="kk-card tip-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="kk-icon bg-white shadow-sm">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-primary">Alur setelah diunggah</h6>
                    </div>
                    <ul class="kk-steps">
                        <li>
                            <span class="kk-step-dot">1</span>
                            <span>Produk tersimpan dengan status <strong class="text-dark">menunggu verifikasi</strong>.</span>
                        </li>
                        <li>
                            <span class="kk-step-dot">2</span>
                            <span>Admin meninjau deskripsi, media, dan berkas digital Anda.</span>
                        </li>
                        <li>
                            <span class="kk-step-dot">3</span>
                            <span>Setelah disetujui, produk otomatis terbit di marketplace.</span>
                        </li>
                    </ul>
                </div>

                {{-- MEMBERSHIP & KUOTA --}}
                <div class="kk-card p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-3 p-2 bg-warning-subtle text-warning d-flex">
                            <i class="bi bi-gem fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-dark);">Kuota paket Anda</h6>
                    </div>

                    <div class="d-flex justify-content-between small mb-1" style="color: var(--text-muted);">
                        <span>Paket aktif</span>
                        <strong class="text-dark">{{ $user->membership->name ?? 'Standar' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-2" style="color: var(--text-muted);">
                        <span>Batas unggah</span>
                        <strong class="text-dark">{{ $user->getMaxUploadLimit() }} produk</strong>
                    </div>

                    @php
                        $limitUpload = (int) $user->getMaxUploadLimit();
                        $terpakai    = isset($totalProduk) ? (int) $totalProduk : (int) ($user->products()->count() ?? 0);
                        $persen      = $limitUpload > 0 ? min(100, round(($terpakai / $limitUpload) * 100)) : 0;
                    @endphp

                    <div class="kk-quota-bar mb-2">
                        <span style="width: {{ $persen }}%;"></span>
                    </div>
                    <div class="small mb-3" style="color: var(--text-muted);">
                        Terpakai {{ $terpakai }} dari {{ $limitUpload }} produk.
                    </div>

                    <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-lightbulb-fill text-warning"></i>
                            <small style="color: var(--text-muted);">
                                Foto sampul yang tajam dan deskripsi yang rinci membuat produk lebih cepat lolos verifikasi.
                            </small>
                        </div>
                    </div>

                    <a href="{{ route('penjual.membership.index') }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold rounded-3">
                        <i class="bi bi-gem me-1"></i> Lihat paket membership
                    </a>
                </div>

            </div>
        </div>

    </div>
</form>

@endsection

{{-- =========================================================
     SCRIPT: DROPZONE, PREVIEW, DAN FORMAT HARGA
========================================================= --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const formatBytes = function (bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    };

    document.querySelectorAll('.kk-drop').forEach(function (zone) {
        const input   = zone.querySelector('input[type="file"]');
        const box     = zone.querySelector('.kk-drop-files');
        const mode    = zone.dataset.preview || 'file';
        const maxFile = parseInt(zone.dataset.max || '0', 10);
        if (!input || !box) return;

        const reset = function () {
            input.value = '';
            box.innerHTML = '';
            box.classList.add('d-none');
            zone.classList.remove('is-filled', 'is-error');
        };

        const render = function () {
            const files = Array.from(input.files || []);
            box.innerHTML = '';

            if (files.length === 0) {
                reset();
                return;
            }

            zone.classList.add('is-filled');
            box.classList.remove('d-none');

            // Ringkasan berkas
            const chip = document.createElement('div');
            chip.className = 'kk-file-chip';

            const iconClass = mode === 'video'
                ? 'bi-camera-video-fill'
                : (mode === 'file' ? 'bi-file-earmark-zip-fill' : 'bi-image-fill');

            const totalSize = files.reduce(function (sum, f) { return sum + f.size; }, 0);
            const label = files.length > 1 ? files.length + ' berkas dipilih' : files[0].name;

            chip.innerHTML =
                '<i class="bi ' + iconClass + '"></i>' +
                '<span class="kk-file-name"></span>' +
                '<span class="kk-file-size">' + formatBytes(totalSize) + '</span>' +
                '<button type="button" class="kk-drop-reset" title="Hapus pilihan"><i class="bi bi-x-lg"></i></button>';
            chip.querySelector('.kk-file-name').textContent = label;
            chip.querySelector('.kk-drop-reset').addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                reset();
            });
            box.appendChild(chip);

            // Pratinjau visual
            if (mode === 'image' || mode === 'images') {
                const wrap = document.createElement('div');
                wrap.className = 'kk-preview';
                box.appendChild(wrap);

                files.slice(0, maxFile > 0 ? maxFile : files.length).forEach(function (file) {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        if (mode === 'image') img.classList.add('kk-preview-wide');
                        wrap.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (mode === 'video' && files[0].type.startsWith('video/')) {
                const wrap = document.createElement('div');
                wrap.className = 'kk-preview';
                const video = document.createElement('video');
                video.controls = true;
                video.src = URL.createObjectURL(files[0]);
                wrap.appendChild(video);
                box.appendChild(wrap);
            }

            if (maxFile > 0 && files.length > maxFile) {
                const warn = document.createElement('div');
                warn.className = 'text-danger small mt-2';
                warn.textContent = 'Maksimal ' + maxFile + ' foto. Pilih ulang berkas Anda.';
                box.appendChild(warn);
                zone.classList.add('is-error');
            }
        };

        input.addEventListener('change', render);

        // Seret dan lepas
        ['dragenter', 'dragover'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'dragend', 'drop'].forEach(function (evt) {
            zone.addEventListener(evt, function () {
                zone.classList.remove('is-dragover');
            });
        });

        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            if (!e.dataTransfer || !e.dataTransfer.files.length) return;
            try {
                const dt = new DataTransfer();
                Array.from(e.dataTransfer.files).forEach(function (f) {
                    if (input.multiple || dt.items.length === 0) dt.items.add(f);
                });
                input.files = dt.files;
                render();
            } catch (err) {
                input.click();
            }
        });
    });

    // Harga dalam kata agar mudah diperiksa
    const price = document.getElementById('price');
    const priceWords = document.getElementById('priceWords');
    if (price && priceWords) {
        price.addEventListener('input', function () {
            const value = parseInt(price.value || '0', 10);
            priceWords.textContent = value > 0
                ? 'Harga jual: Rp' + value.toLocaleString('id-ID')
                : 'Minimal Rp1.000 per produk.';
        });
    }

    // Cegah klik ganda saat mengunggah
    const form = document.getElementById('formProduk');
    const btn  = document.getElementById('btnSubmit');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengunggah...';
        });
    }
});
</script>
@endpush
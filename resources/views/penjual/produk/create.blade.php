@extends('layouts.penjual')

@section('title', 'Tambah Produk Baru')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-light: #eff6ff;
        --primary-hover: #1d4ed8;
        --border-color: #e5e7eb;
        --shadow: 0 5px 20px rgba(15, 23, 42, .06);
        --text-muted: #64748b;
        --text-dark: #1e293b;
    }

    .kk-card {
        background: #fff;
        border: 1px solid var(--border-color) !important;
        border-radius: 18px;
        box-shadow: var(--shadow);
    }

    .kk-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-color);
    }

    .kk-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary);
        flex-shrink: 0;
    }

    .form-control,
    .form-select {
        min-height: 43px;
        border-radius: 10px;
        border-color: #dbe2ea;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .10);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border-color: #dbe2ea;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .category-select-wrap {
        position: relative;
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 7px;
        font-size: 11px;
        color: var(--text-muted);
    }

    .category-info i {
        color: var(--primary);
    }

    .file-box {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 12px;
        background: #f8fafc;
    }

    .file-box .form-control {
        background: #fff;
    }

    .tip-card {
        background: var(--primary-light);
        border: 1px solid #dbeafe !important;
    }

    .membership-card {
        background: #fff;
    }

    .action-bar {
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
        padding-top: 18px;
    }

    .btn-karyaku {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .btn-karyaku:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #fff;
    }

    .preview-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 10px;
    }

    .preview-box img {
        max-width: 100%;
        max-height: 170px;
        object-fit: contain;
    }

    @media (max-width: 767px) {
        .kk-card {
            border-radius: 15px;
        }

        .kk-card.p-4 {
            padding: 18px !important;
        }

        .action-bar {
            flex-direction: column-reverse;
        }

        .action-bar .btn {
            width: 100%;
        }
    }
</style>

{{-- =========================================================
     HEADER
========================================================= --}}
<div class="mb-4">

    <a href="{{ route('penjual.produk.index') }}"
       class="btn btn-outline-secondary btn-sm fw-semibold mb-2 rounded-3">

        <i class="bi bi-arrow-left me-1"></i>
        Kembali ke Daftar Produk

    </a>

    <h4 class="fw-bold mb-1" style="color: var(--text-dark);">
        <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>
        Unggah Produk Digital Baru
    </h4>

    <p class="small mb-0" style="color: var(--text-muted);">
        Lengkapi informasi karya digital Anda agar siap diverifikasi dan dipasarkan.
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

                <strong class="d-block mb-1">
                    Ada data yang perlu diperbaiki.
                </strong>

                <ul class="mb-0 ps-3 small">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        </div>

    </div>

@endif


<div class="row g-4">

    {{-- =====================================================
         FORM PRODUK
    ====================================================== --}}
    <div class="col-lg-8">

        <div class="kk-card p-4">

            <div class="kk-card-header">

                <div class="kk-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>

                <div>

                    <h6 class="fw-bold text-dark mb-0">
                        Informasi Produk
                    </h6>

                    <small class="text-muted">
                        Isi informasi utama karya Anda.
                    </small>

                </div>

            </div>


            <form action="{{ route('penjual.produk.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf


                {{-- =================================================
                     JUDUL
                ================================================== --}}
                <div class="mb-3">

                    <label class="form-label fw-bold small text-dark">

                        Nama / Judul Karya Digital

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Contoh: Template UI Dashboard Tailwind"
                        required
                    >

                    @error('title')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     KATEGORI + HARGA
                ================================================== --}}
                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Kategori Produk

                            <span class="text-danger">*</span>

                        </label>

                        <div class="category-select-wrap">

                            <select
                                name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih Kategori
                                </option>


                                {{-- KATEGORI DARI DATABASE --}}
                                @foreach($categories as $cat)

                                    <option
                                        value="{{ $cat->id_category }}"
                                        {{ old('category_id') == $cat->id_category ? 'selected' : '' }}
                                    >
                                        {{ $cat->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        @error('category_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="category-info">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                Kategori tersedia mengikuti kategori aktif yang dibuat admin.
                            </span>

                        </div>

                    </div>


                    {{-- HARGA --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Harga (Rp)

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light fw-bold small">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="price"
                                value="{{ old('price') }}"
                                min="1000"
                                step="500"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="Contoh: 50000"
                                required
                            >

                        </div>

                        @error('price')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     STOK
                ================================================== --}}
                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Jumlah Stok

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', 99) }}"
                            min="1"
                            class="form-control @error('stock') is-invalid @enderror"
                            placeholder="Contoh: 99"
                            required
                        >

                        <small
                            style="font-size: 11px; color: var(--text-muted);"
                        >
                            Untuk produk digital tanpa batas stok,
                            isi angka besar seperti 999.
                        </small>

                        @error('stock')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     DESKRIPSI
                ================================================== --}}
                <div class="mb-3">

                    <label class="form-label fw-bold small text-dark">

                        Deskripsi Lengkap Produk

                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Jelaskan fitur produk, format berkas, cara pemakaian, dan keunggulan karya digital Anda..."
                        required
                    >{{ old('description') }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     THUMBNAIL + GALERI
                ================================================== --}}
                <div class="row g-3 mb-3">

                    {{-- THUMBNAIL --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Foto Sampul Utama

                            <span class="text-danger">*</span>

                        </label>

                        <div class="file-box">

                            <input
                                type="file"
                                name="thumbnail"
                                id="thumbInput"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                class="form-control @error('thumbnail') is-invalid @enderror"
                                required
                            >

                            <small
                                class="d-block mt-2"
                                style="font-size: 11px; color: var(--text-muted);"
                            >
                                PNG, JPG, JPEG, WEBP — Maksimal 4MB.
                            </small>

                        </div>

                        @error('thumbnail')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror


                        {{-- PREVIEW --}}
                        <div
                            class="preview-box mt-2 text-center d-none"
                            id="previewContainer"
                        >

                            <small class="text-muted d-block mb-2">
                                Preview Foto Sampul
                            </small>

                            <img
                                id="thumbPreview"
                                src=""
                                alt="Preview Thumbnail"
                                class="rounded-3"
                            >

                        </div>

                    </div>


                    {{-- GALERI --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Foto Pendukung

                            <span class="text-muted fw-normal">
                                (Opsional)
                            </span>

                        </label>

                        <div class="file-box">

                            <input
                                type="file"
                                name="images[]"
                                id="imagesInput"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                multiple
                                class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                            >

                            <small
                                class="d-block mt-2"
                                style="font-size: 11px; color: var(--text-muted);"
                            >
                                Pilih hingga 4 foto pendukung.
                            </small>

                        </div>

                        @error('images')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                        @error('images.*')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                        {{-- PREVIEW GALERI --}}
                        <div
                            class="preview-box mt-2 text-center d-none"
                            id="imagesPreviewContainer"
                        >
                            <small class="text-muted d-block mb-2">
                                Preview Foto Pendukung
                            </small>
                            <div id="imagesPreviewList" class="d-flex flex-wrap gap-2 justify-content-center"></div>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     VIDEO + FILE
                ================================================== --}}
                <div class="row g-3 mb-4">

                    {{-- VIDEO --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Video Preview Produk

                            <span class="text-muted fw-normal">
                                (Opsional)
                            </span>

                        </label>

                        <div class="file-box">

                            <input
                                type="file"
                                name="video"
                                id="videoInput"
                                accept="video/mp4,video/webm,video/ogg,video/quicktime"
                                class="form-control @error('video') is-invalid @enderror"
                            >

                            <small
                                class="d-block mt-2"
                                style="font-size: 11px; color: var(--text-muted);"
                            >
                                MP4, WEBM, OGG — Maksimal 50MB.
                            </small>

                        </div>

                        @error('video')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                        {{-- PREVIEW VIDEO --}}
                        <div
                            class="preview-box mt-2 text-center d-none"
                            id="videoPreviewContainer"
                        >
                            <small class="text-muted d-block mb-2">
                                Preview Video
                            </small>
                            <video id="videoPreview" class="w-100 rounded-3" controls style="max-height: 200px;"></video>
                        </div>

                    </div>


                    {{-- FILE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-dark">

                            Berkas Digital Karya

                            <span class="text-danger">*</span>

                        </label>

                        <div class="file-box">

                            <input
                                type="file"
                                name="file"
                                class="form-control @error('file') is-invalid @enderror"
                                required
                            >

                            <small
                                class="d-block mt-2"
                                style="font-size: 11px; color: var(--text-muted);"
                            >
                                ZIP, PDF, RAR, dan lainnya — Maksimal 50MB.
                            </small>

                        </div>

                        @error('file')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     BUTTON
                ================================================== --}}
                <div
                    class="action-bar d-flex justify-content-end gap-2"
                >

                    <a
                        href="{{ route('penjual.produk.index') }}"
                        class="btn btn-light fw-semibold px-4"
                    >
                        <i class="bi bi-x-lg me-1"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-karyaku fw-bold px-4"
                    >
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>
                        Unggah Produk
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    <div class="col-lg-4">


        {{-- KETENTUAN --}}
        <div class="kk-card tip-card p-4 mb-4">

            <div class="d-flex align-items-center gap-2 mb-3">

                <div class="kk-icon bg-white">
                    <i class="bi bi-shield-check"></i>
                </div>

                <h6 class="fw-bold mb-0" style="color: var(--primary);">
                    Ketentuan Verifikasi
                </h6>

            </div>

            <ul
                class="small ps-3 mb-0"
                style="line-height: 1.7; color: var(--text-muted);"
            >

                <li class="mb-2">
                    Produk baru akan berstatus
                    <strong>Menunggu Verifikasi</strong>.
                </li>

                <li class="mb-2">
                    Tim admin / verifikator akan meninjau
                    karya dan berkas digital Anda.
                </li>

                <li>
                    Produk yang disetujui akan diterbitkan
                    di marketplace.
                </li>

            </ul>

        </div>


        {{-- MEMBERSHIP --}}
        <div class="kk-card membership-card p-4">

            <div class="d-flex align-items-center gap-2 mb-3">

                <div
                    class="rounded-3 p-2 bg-warning-subtle text-warning"
                >
                    <i class="bi bi-gem fs-5"></i>
                </div>

                <h6 class="fw-bold mb-0 text-dark">
                    Status Kuota Paket
                </h6>

            </div>


            <div class="small mb-2 text-muted">

                Paket Aktif:

                <strong class="text-dark">
                    {{ $user->membership->name ?? 'Standar' }}
                </strong>

            </div>


            <div class="small mb-3 text-muted">

                Batas Upload:

                <strong class="text-dark">
                    {{ $user->getMaxUploadLimit() }} Produk
                </strong>

            </div>


            <div
                class="p-3 rounded-3 bg-light border mb-3"
            >

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-lightbulb-fill text-warning"></i>

                    <small class="text-muted">
                        Gunakan thumbnail yang jelas agar produk
                        lebih menarik di marketplace.
                    </small>

                </div>

            </div>


            <a
                href="{{ route('penjual.membership.index') }}"
                class="btn btn-sm btn-outline-primary w-100 fw-semibold"
            >

                <i class="bi bi-gem me-1"></i>
                Lihat Paket Membership

            </a>

        </div>

    </div>

</div>

@endsection


{{-- =========================================================
     SCRIPT PREVIEW THUMBNAIL
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const thumbInput = document.getElementById('thumbInput');
    const previewContainer = document.getElementById('previewContainer');
    const thumbPreview = document.getElementById('thumbPreview');



    // =========================================================
    // PREVIEW FOTO SAMPUL
    // =========================================================
    if (thumbInput) {
        thumbInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file || !file.type.startsWith('image/')) {
                previewContainer.classList.add('d-none');
                thumbPreview.removeAttribute('src');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                thumbPreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // =========================================================
    // PREVIEW FOTO PENDUKUNG (GALERI)
    // =========================================================
    const imagesInput = document.getElementById('imagesInput');
    const imagesPreviewContainer = document.getElementById('imagesPreviewContainer');
    const imagesPreviewList = document.getElementById('imagesPreviewList');

    if (imagesInput) {
        imagesInput.addEventListener('change', function (event) {
            const files = event.target.files;
            
            // Clear existing previews
            imagesPreviewList.innerHTML = '';
            
            if (!files || files.length === 0) {
                imagesPreviewContainer.classList.add('d-none');
                return;
            }
            
            let hasImages = false;

            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    hasImages = true;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded-3 shadow-sm';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        imagesPreviewList.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (hasImages) {
                imagesPreviewContainer.classList.remove('d-none');
            } else {
                imagesPreviewContainer.classList.add('d-none');
            }
        });
    }

    // =========================================================
    // PREVIEW VIDEO
    // =========================================================
    const videoInput = document.getElementById('videoInput');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const videoPreview = document.getElementById('videoPreview');

    if (videoInput) {
        videoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            
            if (!file || !file.type.startsWith('video/')) {
                videoPreviewContainer.classList.add('d-none');
                videoPreview.removeAttribute('src');
                return;
            }
            
            const fileURL = URL.createObjectURL(file);
            videoPreview.src = fileURL;
            videoPreviewContainer.classList.remove('d-none');
        });
    }

});

</script>

@endpush

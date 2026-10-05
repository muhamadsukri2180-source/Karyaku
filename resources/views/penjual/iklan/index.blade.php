@extends('layouts.penjual')

@section('title', 'Iklan & Promosi Produk')

@section('content')

<style>
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
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { max-width: 720px; color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); transition: .2s ease; }
    .kk-card:hover { box-shadow: var(--shadow-hover); }
    .kk-section-title { font-size: 14px; font-weight: 800; color: var(--text-dark); }
    .kk-row { border: 1px solid var(--border-color); border-radius: 14px; transition: .2s ease; }
    .kk-row:hover { border-color: #cbd5e1; box-shadow: 0 5px 16px rgba(15,23,42,.06); }
    .kk-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 14px; flex-shrink: 0; }
    .ad-rules-box { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1px solid #bfdbfe; border-radius: 16px; }

    /* ====== KOTAK UNGGAH FILE (DROPZONE) ====== */
    .kk-drop {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        transition: border-color .18s ease, background-color .18s ease, transform .18s ease;
    }
    .kk-drop:hover, .kk-drop:focus-within {
        border-color: var(--primary);
        background: #eff6ff;
    }
    .kk-drop.is-dragover {
        border-color: var(--primary);
        background: #eff6ff;
        transform: scale(1.008);
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
        gap: 4px;
        padding: 16px 12px;
        margin: 0;
        cursor: pointer;
    }
    .kk-drop-cloud {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1px solid #dbeafe;
        color: var(--primary);
        font-size: 1.3rem;
        margin-bottom: 2px;
        transition: transform .18s ease, background-color .18s ease, color .18s ease;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
    }
    .kk-drop:hover .kk-drop-cloud, .kk-drop.is-dragover .kk-drop-cloud {
        transform: translateY(-3px);
        background: var(--primary);
        color: #ffffff;
    }
    .kk-drop-title {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-dark);
    }
    .kk-drop-title span {
        color: var(--primary);
        text-decoration: underline;
    }
    .kk-drop-hint {
        font-size: 11px;
        color: var(--text-muted);
    }
    .kk-drop-files {
        padding: 12px;
    }
    .kk-file-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 12px;
        color: var(--text-dark);
    }
    .kk-file-chip i { color: var(--primary); }
    .kk-file-chip .kk-file-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .kk-file-size { color: var(--text-muted); font-size: 11px; flex-shrink: 0; }
    .kk-drop-reset {
        border: 0;
        background: transparent;
        color: var(--text-muted);
        line-height: 1;
        padding: 3px 6px;
        border-radius: 6px;
        transition: .15s ease;
    }
    .kk-drop-reset:hover {
        color: #dc3545;
        background: #fee2e2;
    }
</style>

<div class="seller-page-head d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-megaphone-fill text-warning me-2"></i>Iklan & Promosi Produk</h4>
        <p class="small mb-0">Publikasikan iklan video produk Anda (Maksimal 10 detik & Maksimal 10 MB) untuk ditayangkan langsung pada Dashboard Pembeli Karyaku.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm flex-shrink-0 {{ !$bisaIklan ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#createAdModal" {{ !$bisaIklan ? 'disabled' : '' }}>
        <i class="bi bi-plus-lg me-1"></i> Tambah Iklan Baru
    </button>
</div>

@if(!$bisaIklan)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Paket Membership Inaktif / Kadaluarsa</h6>
                <p class="small mb-0 text-muted">Fitur publikasi iklan video produk hanya dapat digunakan oleh penjual dengan paket membership aktif.</p>
            </div>
        </div>
        <a href="{{ route('penjual.membership.index') }}" class="btn btn-warning btn-sm fw-bold px-4 py-2 rounded-3 text-dark">
            <i class="bi bi-star-fill me-1"></i> Beli / Perpanjang Paket
        </a>
    </div>
@endif

{{-- ATURAN UNGGAH IKLAN VIDEO --}}
<div class="ad-rules-box p-3.5 p-md-4 mb-4 shadow-sm">
    <div class="d-flex align-items-center gap-3 mb-2">
        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
            <i class="bi bi-camera-reels-fill fs-6"></i>
        </div>
        <h6 class="fw-bold mb-0 text-dark">Panduan Iklan Video Produk</h6>
    </div>
    <div class="row g-2 text-muted small mt-1" style="font-size: 12px;">
        <div class="col-md-4"><i class="bi bi-clock-history text-primary me-1"></i> Durasi Video: <strong>Maksimal 10 Detik</strong></div>
        <div class="col-md-4"><i class="bi bi-file-earmark-zip text-primary me-1"></i> Ukuran File: <strong>Maksimal 10 MB</strong></div>
        <div class="col-md-4"><i class="bi bi-display text-primary me-1"></i> Penayangan: <strong>Dashboard Pembeli</strong></div>
    </div>
</div>

<div class="row g-4">
    {{-- PRODUK SEDANG DIIKLANKAN (PUBLISHED ADS) --}}
    <div class="col-lg-6">
        <div class="kk-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="kk-section-title mb-0"><i class="bi bi-fire text-danger me-2"></i>Iklan Berhasil Dipublikasikan</h6>
                <span class="badge rounded-pill px-3 py-2 bg-success text-white">{{ $promotedProducts->count() }} Iklan</span>
            </div>

            @if($promotedProducts->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada iklan video yang sedang di-publish.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($promotedProducts as $prod)
                        <div class="kk-row p-3" style="background:#fffaf0;">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->title }}" class="kk-thumb border">
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-1 text-truncate small">{{ $prod->title }}</h6>
                                        <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 10px;">
                                            <i class="bi bi-broadcast me-1"></i> Status: Dipublikasikan
                                        </span>
                                    </div>
                                </div>
                                <form action="{{ route('penjual.iklan.cancel', $prod->id_product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold rounded-3 px-3">
                                        <i class="bi bi-stop-circle me-1"></i> Hentikan
                                    </button>
                                </form>
                            </div>

                            @if($prod->video_url)
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:11px;">
                                        <span class="text-muted"><i class="bi bi-film me-1"></i> Video Iklan (Max 10 Detik)</span>
                                    </div>
                                    <video controls class="w-100 rounded-3 border" style="max-height: 140px; object-fit: cover;" preload="metadata">
                                        <source src="{{ $prod->video_url }}" type="video/mp4">
                                        Browser Anda tidak mendukung pemutar video.
                                    </video>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- PILIH PRODUK & UNGGAH VIDEO IKLAN --}}
    <div class="col-lg-6">
        <div class="kk-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="kk-section-title mb-0"><i class="bi bi-plus-circle-fill me-2" style="color:var(--primary);"></i>Publikasikan Iklan Produk Baru</h6>
                <span class="badge rounded-pill px-3 py-2" style="background:var(--primary-light); color:var(--primary);">{{ $activeProducts->count() }} Produk</span>
            </div>

            @if($activeProducts->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-box fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada produk aktif yang siap diiklankan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($activeProducts as $prod)
                        <div class="kk-row p-3" style="background:#f8fafc;">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->title }}" class="kk-thumb border">
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-1 text-truncate small">{{ $prod->title }}</h6>
                                        <div class="small" style="font-size: 11px; color:var(--text-muted);">
                                            Rp {{ number_format($prod->price, 0, ',', '.') }} &bull; Terjual: {{ $prod->sold_count }}
                                        </div>
                                    </div>
                                </div>
                                @if($prod->is_promoted)
                                    <span class="badge px-3 py-2 bg-success text-white">Iklan Aktif</span>
                                @endif
                            </div>

                            <form action="{{ route('penjual.iklan.promote', $prod->id_product) }}" method="POST" enctype="multipart/form-data" class="mt-2 pt-2 border-top ad-form">
                                @csrf
                                <div class="mb-2.5 ad-form-group">
                                    <label class="form-label small fw-bold text-dark mb-1.5" style="font-size: 11.5px;">
                                        <i class="bi bi-camera-video-fill text-primary me-1"></i> Unggah Video Iklan (MP4, Maks 10MB & 10 Detik)
                                    </label>
                                    <div class="kk-drop" data-preview="video">
                                        <input type="file" name="ad_video" id="ad_video_inline_{{ $prod->id_product }}" class="ad-video-input" accept="video/mp4,video/webm,video/ogg,video/quicktime" onchange="validateAdVideo(this)">
                                        <label class="kk-drop-label py-3" for="ad_video_inline_{{ $prod->id_product }}">
                                            <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                                            <span class="kk-drop-title">Seret video ke sini atau <span>pilih berkas</span></span>
                                            <span class="kk-drop-hint">MP4, WebM, MOV &middot; Maks 10 MB & 10 Detik</span>
                                        </label>
                                        <div class="kk-drop-files d-none"></div>
                                    </div>
                                    <div class="form-text text-danger small d-none video-error-msg mt-1" style="font-size:10.5px;"></div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm fw-bold px-3 py-1.5 rounded-3 btn-publish-ad" style="background:var(--primary); color:#fff;">
                                        <i class="bi bi-broadcast me-1"></i> {{ $prod->is_promoted ? 'Perbarui Video & Publish' : 'Publish Iklan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL TAMBAH IKLAN BARU --}}
<div class="modal fade" id="createAdModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bi bi-megaphone-fill text-warning fs-5"></i> Publikasikan Iklan Produk Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penjual.iklan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Pilih Produk Aktif yang Ingin Diiklankan</label>
                        <select name="product_id" class="form-select form-select-sm rounded-3" required>
                            <option value="">Pilih Produk Aktif</option>
                            @foreach($activeProducts as $p)
                                <option value="{{ $p->id_product }}">{{ $p->title }} (Rp {{ number_format($p->price, 0, ',', '.') }}) {{ $p->is_promoted ? '[Iklan Aktif]' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 ad-form-group">
                        <label class="form-label fw-bold small text-dark mb-1.5">
                            <i class="bi bi-camera-video-fill text-primary me-1"></i> Unggah Video Iklan (Format Landscape 16:9, Maksimal 10 MB & Maksimal 10 Detik)
                        </label>
                        <div class="kk-drop" data-preview="video">
                            <input type="file" name="ad_video" id="ad_video_modal" accept="video/mp4,video/webm,video/ogg,video/quicktime" onchange="validateAdVideo(this)" required>
                            <label class="kk-drop-label py-4" for="ad_video_modal">
                                <span class="kk-drop-cloud"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                                <span class="kk-drop-title">Seret video ke sini atau <span>pilih berkas</span></span>
                                <span class="kk-drop-hint">Format: MP4, WebM, OGG, MOV (Landscape 16:9, Maks 10MB & 10s)</span>
                            </label>
                            <div class="kk-drop-files d-none"></div>
                        </div>
                        <div class="form-text text-danger small d-none video-error-msg mt-1" style="font-size:10.5px;"></div>
                        <span class="form-text text-muted small d-block mt-1" style="font-size:10.5px;">Format yang didukung: MP4, WebM, OGG, MOV (Ukuran Landscape 16:9, Maks 10MB & 10 Detik).</span>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light border btn-sm fw-bold px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 rounded-3 btn-publish-ad">
                        <i class="bi bi-broadcast me-1"></i> Publikasikan Iklan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function resetAdVideoInput(input) {
        input.value = "";
        const dropZone = input.closest('.kk-drop');
        if (dropZone) {
            dropZone.classList.remove('is-filled', 'is-error');
            const box = dropZone.querySelector('.kk-drop-files');
            if (box) {
                box.innerHTML = '';
                box.classList.add('d-none');
            }
            const label = dropZone.querySelector('.kk-drop-label');
            if (label) label.classList.remove('d-none');
        }
        const container = input.closest('.ad-form-group') || input.parentElement;
        if (container) {
            const errorMsg = container.querySelector('.video-error-msg');
            if (errorMsg) {
                errorMsg.textContent = "";
                errorMsg.classList.add('d-none');
            }
        }
        const form = input.form || input.closest('form');
        if (form) {
            const submitBtn = form.querySelector('.btn-publish-ad');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    function updateDropzonePreview(input, file, videoDuration = null) {
        const dropZone = input.closest('.kk-drop');
        if (!dropZone) return;
        const box = dropZone.querySelector('.kk-drop-files');
        const label = dropZone.querySelector('.kk-drop-label');
        if (!box) return;

        if (!file) {
            resetAdVideoInput(input);
            return;
        }

        dropZone.classList.add('is-filled');
        dropZone.classList.remove('is-error');
        box.classList.remove('d-none');
        if (label) label.classList.add('d-none');

        box.innerHTML = '';

        const chip = document.createElement('div');
        chip.className = 'kk-file-chip mb-2';

        const durStr = videoDuration ? ` &bull; ${Math.round(videoDuration)} detik` : '';

        chip.innerHTML = `
            <i class="bi bi-film fs-5 text-primary"></i>
            <div class="kk-file-info overflow-hidden flex-grow-1">
                <div class="kk-file-name text-truncate fw-bold small text-dark">${file.name}</div>
                <div class="kk-file-size text-muted" style="font-size:11px;">${formatBytes(file.size)}${durStr}</div>
            </div>
            <button type="button" class="kk-drop-reset btn btn-sm btn-light border-0 text-danger p-1" title="Hapus berkas">
                <i class="bi bi-x-lg fs-6"></i>
            </button>
        `;

        chip.querySelector('.kk-drop-reset').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetAdVideoInput(input);
        });

        box.appendChild(chip);

        const previewWrap = document.createElement('div');
        previewWrap.className = 'kk-preview mt-2';
        const videoElem = document.createElement('video');
        videoElem.controls = true;
        videoElem.className = 'w-100 rounded-3 border';
        videoElem.style.maxHeight = '140px';
        videoElem.style.objectFit = 'cover';
        videoElem.src = URL.createObjectURL(file);
        previewWrap.appendChild(videoElem);
        box.appendChild(previewWrap);
    }

    function validateAdVideo(input) {
        const file = input.files[0];
        const container = input.closest('.ad-form-group') || input.parentElement;
        const dropZone = input.closest('.kk-drop');
        const errorMsg = container ? container.querySelector('.video-error-msg') : (dropZone ? dropZone.parentElement.querySelector('.video-error-msg') : null);
        const form = input.form || input.closest('form');
        const submitBtn = form ? form.querySelector('.btn-publish-ad') : null;
        
        if (!file) {
            if (errorMsg) {
                errorMsg.textContent = "";
                errorMsg.classList.add('d-none');
            }
            if (dropZone) dropZone.classList.remove('is-error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        // Cek ukuran file (Maks 10 MB = 10 * 1024 * 1024 bytes)
        const maxSizeBytes = 10 * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            if (errorMsg) {
                errorMsg.textContent = "Ukuran file video melebihi batas 10 MB (File Anda: " + (file.size / (1024 * 1024)).toFixed(1) + " MB).";
                errorMsg.classList.remove('d-none');
            }
            if (dropZone) {
                dropZone.classList.add('is-error');
                dropZone.classList.remove('is-filled');
            }
            if (submitBtn) submitBtn.disabled = true;
            resetAdVideoInput(input);
            return;
        }

        updateDropzonePreview(input, file);

        // Cek durasi video (Maks 10 Detik)
        const video = document.createElement('video');
        video.preload = 'metadata';
        const objectUrl = URL.createObjectURL(file);
        let durationChecked = false;

        function handleDuration(duration) {
            if (durationChecked) return;
            durationChecked = true;
            try { window.URL.revokeObjectURL(objectUrl); } catch(e) {}

            if (duration && !isNaN(duration) && duration !== Infinity && duration > 0) {
                if (duration > 10.5) {
                    if (errorMsg) {
                        errorMsg.textContent = "Durasi video melebihi batas 10 detik (Durasi video Anda: " + Math.round(duration) + " detik). Silakan potong/pilih video maks 10 detik.";
                        errorMsg.classList.remove('d-none');
                    }
                    if (dropZone) {
                        dropZone.classList.add('is-error');
                        dropZone.classList.remove('is-filled');
                    }
                    if (submitBtn) submitBtn.disabled = true;
                    resetAdVideoInput(input);
                } else {
                    if (errorMsg) {
                        errorMsg.textContent = "";
                        errorMsg.classList.add('d-none');
                    }
                    if (dropZone) {
                        dropZone.classList.remove('is-error');
                        dropZone.classList.add('is-filled');
                    }
                    if (submitBtn) submitBtn.disabled = false;
                    updateDropzonePreview(input, file, duration);
                }
            } else {
                if (errorMsg) {
                    errorMsg.textContent = "";
                    errorMsg.classList.add('d-none');
                }
                if (dropZone) {
                    dropZone.classList.remove('is-error');
                    dropZone.classList.add('is-filled');
                }
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        video.onloadedmetadata = function() {
            if (video.duration === Infinity || isNaN(video.duration) || video.duration === 0) {
                video.currentTime = 1e101;
                video.ontimeupdate = function() {
                    this.ontimeupdate = null;
                    handleDuration(this.duration);
                };
                setTimeout(() => {
                    handleDuration(video.duration);
                }, 400);
            } else {
                handleDuration(video.duration);
            }
        };

        video.onerror = function() {
            handleDuration(0);
        };

        video.src = objectUrl;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.kk-drop').forEach(function(zone) {
            const input = zone.querySelector('input[type="file"]');
            if (!input) return;

            ['dragenter', 'dragover'].forEach(eventName => {
                zone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('is-dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('is-dragover');
                }, false);
            });

            zone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    input.files = files;
                    validateAdVideo(input);
                }
            }, false);
        });
    });
</script>
@endpush

@endsection

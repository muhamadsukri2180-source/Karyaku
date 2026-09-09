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
</style>

<div class="seller-page-head d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-megaphone-fill text-warning me-2"></i>Iklan & Promosi Produk</h4>
        <p class="small mb-0">Publikasikan iklan video produk Anda (Maksimal 10 detik & Maksimal 10 MB) untuk ditayangkan langsung pada Dashboard Pembeli Karyaku.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createAdModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Iklan Baru
    </button>
</div>

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
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-dark mb-1" style="font-size: 11px;">
                                        Unggah Video Iklan (MP4, Maks 10MB & 10 Detik)
                                    </label>
                                    <input type="file" name="ad_video" class="form-control form-control-sm ad-video-input" accept="video/mp4,video/webm,video/ogg,video/quicktime" onchange="validateAdVideo(this)">
                                    <div class="form-text text-danger small d-none video-error-msg" style="font-size:10px;"></div>
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
                            <option value="">-- Pilih Produk Aktif --</option>
                            @foreach($activeProducts as $p)
                                <option value="{{ $p->id_product }}">{{ $p->title }} (Rp {{ number_format($p->price, 0, ',', '.') }}) {{ $p->is_promoted ? '[Iklan Aktif]' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">
                            Unggah Video Iklan (Maksimal 10 MB & Maksimal 10 Detik)
                        </label>
                        <input type="file" name="ad_video" class="form-control form-control-sm rounded-3" accept="video/mp4,video/webm,video/ogg,video/quicktime" onchange="validateAdVideo(this)">
                        <div class="form-text text-danger small d-none video-error-msg" style="font-size:10px;"></div>
                        <span class="form-text text-muted small d-block" style="font-size:10.5px;">Format yang didukung: MP4, WebM, OGG, MOV. Maks 10MB & 10 Detik.</span>
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
    function validateAdVideo(input) {
        const file = input.files[0];
        const errorMsg = input.parentElement.querySelector('.video-error-msg');
        const submitBtn = input.form.querySelector('.btn-publish-ad');
        
        if (!file) {
            errorMsg.classList.add('d-none');
            submitBtn.disabled = false;
            return;
        }

        // Cek ukuran file (Maks 10 MB = 10 * 1024 * 1024 bytes)
        const maxSizeBytes = 10 * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            errorMsg.textContent = "Ukuran file video melebihi batas 10 MB (File Anda: " + (file.size / (1024 * 1024)).toFixed(1) + " MB).";
            errorMsg.classList.remove('d-none');
            input.value = "";
            return;
        }

        // Cek durasi video (Maks 10 Detik)
        const video = document.createElement('video');
        video.preload = 'metadata';
        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            const duration = video.duration;
            if (duration > 10.5) {
                errorMsg.textContent = "Durasi video melebihi batas 10 detik (Durasi video Anda: " + Math.round(duration) + " detik). Silakan potong/pilih video maks 10 detik.";
                errorMsg.classList.remove('d-none');
                input.value = "";
            } else {
                errorMsg.classList.add('d-none');
            }
        };
        video.src = URL.createObjectURL(file);
    }
</script>
@endpush

@endsection
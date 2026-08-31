@extends('layouts.penjual')
@section('title', 'Tambah Produk Baru')

@section('content')

<div class="mb-4">
    <a href="{{ route('penjual.produk.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h4 class="fw-bold text-dark mb-1">Unggah Produk Digital Baru</h4>
    <p class="text-muted small">Lengkapi informasi karya digital Anda agar siap diverifikasi dan dipasarkan.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-box p-4 border">
            <form action="{{ route('penjual.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama / Judul Karya Digital <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="form-control @error('title') is-invalid @enderror" 
                           placeholder="Contoh: Template UI Dashboard Tailwind, E-Book Panduan Desain 3D" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Kategori Produk <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_category }}" {{ old('category_id') == $cat->id_category ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold small">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" min="1000" step="500" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   placeholder="Contoh: 50000" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 99) }}" min="1" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               placeholder="Contoh: 99" required>
                        <small class="text-muted" style="font-size: 11px;">Untuk produk digital tanpa batas stok, isi angka besar (misal 999).</small>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Deskripsi Lengkap Produk <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" 
                              class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Jelaskan fitur produk, format berkas, cara pemakaian, dan keunggulan karya digital Anda..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Foto Sampul Utama (Thumbnail) <span class="text-danger">*</span></label>
                        <input type="file" name="thumbnail" accept="image/png,image/jpeg,image/jpg,image/webp" 
                               class="form-control @error('thumbnail') is-invalid @enderror" required id="thumbInput">
                        <small class="text-muted" style="font-size: 11px;">Format: PNG, JPG, JPEG, WEBP (Max 4MB).</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2 text-center d-none" id="previewContainer">
                            <img id="thumbPreview" class="img-fluid rounded-3 border shadow-sm" style="max-height: 140px;" alt="preview">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Foto Pendukung (Opsional, Maks 4 Foto)</label>
                        <input type="file" name="images[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Pilih hingga 4 foto pendukung karya (Total galeri maks 5 foto).</small>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Vidio Preview Produk (Opsional - 1 Vidio)</label>
                        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime" 
                               class="form-control @error('video') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Format: MP4, WEBM, OGG (Max 50MB). Opsional untuk demo karya.</small>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Berkas Digital Karya (.zip / .pdf / .rar / dll) <span class="text-danger">*</span></label>
                        <input type="file" name="file" 
                               class="form-control @error('file') is-invalid @enderror" required>
                        <small class="text-muted" style="font-size: 11px;">Berkas utama yang akan otomatis didownload pembeli setelah bayar (Max 50MB).</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('penjual.produk.index') }}" class="btn btn-light fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INFO MEMBERSHIP & TIPS --}}
    <div class="col-lg-4">
        <div class="card-box p-4 border mb-4 bg-primary-subtle bg-opacity-25">
            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle-fill me-2"></i>Ketentuan Verifikasi</h6>
            <ul class="small text-muted ps-3 mb-0" style="line-height: 1.6;">
                <li>Setiap produk yang baru diunggah akan berstatus <strong>Menunggu Verifikasi (Pending)</strong>.</li>
                <li>Tim admin / verifikator Karyaku akan meninjau keaslian dan kesesuaian berkas digital Anda.</li>
                <li>Setelah disetujui, karya Anda akan langsung terbit secara publik di marketplace.</li>
            </ul>
        </div>

        <div class="card-box p-4 border">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-gem text-warning me-2"></i>Status Kuota Paket</h6>
            <div class="small text-muted mb-2">Paket Aktif: <strong>{{ $user->membership->name ?? 'Standar' }}</strong></div>
            <div class="small text-muted mb-3">Batas Upload: <strong>{{ $user->getMaxUploadLimit() }} Produk</strong></div>
            <a href="{{ route('penjual.membership.index') }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                Lihat Paket Membership
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('thumbInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('thumbPreview');
                const container = document.getElementById('previewContainer');
                preview.src = e.target.result;
                container.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

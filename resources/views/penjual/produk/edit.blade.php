@extends('layouts.penjual')
@section('title', 'Edit Produk - ' . $product->title)

@section('content')

<div class="mb-4">
    <a href="{{ route('penjual.produk.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h4 class="fw-bold text-dark mb-1">Edit Produk Digital</h4>
    <p class="text-muted small">Perbarui data karya digital Anda. Jika produk sebelumnya ditolak, perubahan data akan otomatis mengajukan verifikasi ulang.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-box p-4 border">
            <form action="{{ route('penjual.produk.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama / Judul Karya Digital <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" 
                           class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Kategori Produk <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_category }}" {{ old('category_id', $product->category_id) == $cat->id_category ? 'selected' : '' }}>
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
                            <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" min="1000" step="500" 
                                   class="form-control @error('price') is-invalid @enderror" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 99) }}" min="1" 
                               class="form-control @error('stock') is-invalid @enderror" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Deskripsi Lengkap Produk <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" 
                              class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Foto Sampul Utama / Thumbnail (Opsional)</label>
                        @if($product->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="cover" class="img-fluid rounded-3 border" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/png,image/jpeg,image/jpg,image/webp" 
                               class="form-control @error('thumbnail') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengubah foto sampul utama.</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Tambah Foto Pendukung (Opsional, Maks 4 Foto)</label>
                        @if(!empty($product->images) && is_array($product->images))
                            <div class="d-flex gap-1 mb-2 flex-wrap">
                                @foreach($product->images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="images[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Pilih foto tambahan untuk memperbarui galeri produk (Maks 5 foto total).</small>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Vidio Preview Produk (Opsional - 1 Vidio)</label>
                        @if($product->video)
                            <div class="mb-2 small text-success">
                                <i class="bi bi-file-earmark-play-fill me-1"></i> Vidio preview sudah ada.
                            </div>
                        @endif
                        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime" 
                               class="form-control @error('video') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengganti berkas vidio.</small>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Berkas Digital Karya (Opsional)</label>
                        @if($product->file)
                            <div class="mb-2 small text-muted">
                                <i class="bi bi-file-earmark-check text-success me-1"></i> Berkas digital sudah tersimpan
                            </div>
                        @endif
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        <small class="text-muted" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengganti berkas file digital.</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('penjual.produk.index') }}" class="btn btn-light fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INFORMASI STATUS PRODUK --}}
    <div class="col-lg-4">
        <div class="card-box p-4 border">
            <h6 class="fw-bold text-dark mb-3">Status Produk Saat Ini</h6>
            <div class="mb-3">
                @if($product->status === 'active')
                    <span class="badge bg-success-subtle text-success p-2 w-100 fs-6"><i class="bi bi-check-circle-fill me-1"></i> Aktif di Marketplace</span>
                @elseif($product->status === 'pending')
                    <span class="badge bg-warning-subtle text-warning p-2 w-100 fs-6"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                @else
                    <span class="badge bg-danger-subtle text-danger p-2 w-100 fs-6"><i class="bi bi-x-circle-fill me-1"></i> Ditolak / Diblokir</span>
                @endif
            </div>

            @if($product->rejection_note)
                <div class="alert alert-danger p-3 small mb-0">
                    <strong>Catatan Petugas:</strong>
                    <p class="mb-0 mt-1">{{ $product->rejection_note }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

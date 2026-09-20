@extends('layouts.penjual')
@section('title', 'Edit Produk - ' . $product->title)

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-light: #eff6ff;
        --border-color: #cbd5e1;
        --shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06);
        --text-muted: #64748b;
        --text-dark: #0f172a;
    }
    .kk-card { 
        background: #fff; 
        border: 1px solid var(--border-color); 
        border-radius: 20px; 
        box-shadow: var(--shadow); 
    }
    /* Kotak form diberi warna abu keputihan agar kontras */
    .form-control, .form-select {
        border-radius: 12px;
        padding: 11px 16px;
        background-color: #f8fafc;
        border-color: var(--border-color);
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .form-label {
        font-weight: 700;
        font-size: 13px;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
</style>

<div class="mb-4">
    {{-- Tombol kembali warna biru --}}
    <a href="{{ route('penjual.produk.index') }}" class="btn btn-sm fw-semibold mb-3 rounded-pill px-3 shadow-sm text-white" style="background-color: var(--primary);">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h4 class="fw-bold mb-1" style="color:var(--text-dark);"><i class="bi bi-pencil-square me-2" style="color:var(--primary);"></i>Edit Produk Digital</h4>
    <p class="small mb-0" style="color:var(--text-muted);">Perbarui data karya digital Anda, atur status toko, dan kelola berkas dengan mudah.</p>
</div>

{{-- FORM UTAMA FULL WIDTH --}}
<div class="kk-card p-4.5 p-md-5 mb-5">
    
    {{-- INFORMASI STATUS PRODUK (BISA DIUBAH ATAU DIPANTAU) --}}
    <div class="p-4 mb-4 rounded-4 border bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="fs-4 text-primary"><i class="bi bi-info-circle-fill"></i></div>
            <div>
                <div class="fw-bold text-dark" style="font-size: 13.5px;">Status Produk Saat Ini</div>
                <div class="small text-muted">Anda dapat mengubah status aktif/nonaktif produk melalui pengaturan di bawah.</div>
            </div>
        </div>
        <div>
            @if($product->status === 'active')
                <span class="badge px-3 py-2 fs-6 fw-semibold rounded-pill shadow-sm" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle-fill me-1"></i> Aktif di Marketplace</span>
            @elseif($product->status === 'pending')
                <span class="badge px-3 py-2 fs-6 fw-semibold rounded-pill shadow-sm" style="background:#fff7ed; color:#f59e0b;"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
            @elseif($product->status === 'inactive')
                <span class="badge px-3 py-2 fs-6 fw-semibold rounded-pill shadow-sm" style="background:#f1f5f9; color:#475569;"><i class="bi bi-eye-slash-fill me-1"></i> Nonaktif (Disembunyikan)</span>
            @else
                <span class="badge px-3 py-2 fs-6 fw-semibold rounded-pill shadow-sm" style="background:#fef2f2; color:#ef4444;"><i class="bi bi-x-circle-fill me-1"></i> Ditolak / Diblokir</span>
            @endif
        </div>
    </div>

    @if($product->rejection_note)
        <div class="p-3.5 rounded-3 shadow-sm mb-4" style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c;">
            <div class="fw-bold mb-1 d-flex align-items-center gap-1.5" style="font-size: 13px;">
                <i class="bi bi-exclamation-triangle-fill"></i> Catatan Petugas:
            </div>
            <p class="mb-0 small" style="line-height: 1.5; color: var(--text-dark);">{{ $product->rejection_note }}</p>
        </div>
    @endif

    <form action="{{ route('penjual.produk.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label">Nama / Judul Karya Digital <span class="text-danger">*</span></label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}" 
                   class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Desain Template UI/UX SaaS Modern" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Kategori Produk <span class="text-danger">*</span></label>
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
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white fw-bold text-muted small" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">Rp</span>
                    <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" min="1000" step="500" 
                           class="form-control @error('price') is-invalid @enderror" style="border-radius: 0 12px 12px 0;" required>
                </div>
                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 99) }}" min="1" 
                       class="form-control @error('stock') is-invalid @enderror" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status aktif / nonaktif yang bisa diubah penjual --}}
            <div class="col-md-6">
                <label class="form-label">Ubah Status Produk <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>🟢 Aktif (Tampil di Toko)</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>🔴 Nonaktif (Sembunyikan)</option>
                </select>
                <small class="d-block mt-1" style="font-size: 11.5px; color: var(--text-muted);">Pilih nonaktif jika ingin menyembunyikan produk sementara waktu.</small>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Deskripsi Lengkap Produk <span class="text-danger">*</span></label>
            <textarea name="description" rows="6" 
                      class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan detail fitur, keunggulan, dan cara penggunaan karya Anda..." required>{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Foto Sampul Utama / Thumbnail</label>
                @if($product->thumbnail)
                    <div class="mb-2.5 p-2 bg-white rounded-3 border d-inline-block shadow-sm">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="cover" class="rounded-2 border" style="width: 70px; height: 70px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/png,image/jpeg,image/jpg,image/webp" 
                       class="form-control @error('thumbnail') is-invalid @enderror">
                <small class="d-block mt-1" style="font-size: 11.5px; color:var(--text-muted);">Biarkan kosong jika tidak ingin mengubah foto sampul utama.</small>
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Foto Pendukung (Galeri Opsional)</label>
                @if(!empty($product->images) && is_array($product->images))
                    <div class="d-flex gap-2 mb-2.5 flex-wrap p-2 bg-white rounded-3 border shadow-sm">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="rounded-2 border" style="width: 50px; height: 50px; object-fit: cover;">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="images[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple
                       class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                <small class="d-block mt-1" style="font-size: 11.5px; color:var(--text-muted);">Pilih foto tambahan untuk memperbarui galeri (Maks 5 foto total).</small>
                @error('images')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Video Preview Produk</label>
                @if($product->video)
                    <div class="mb-2 small text-success fw-semibold d-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-play-fill"></i> Video preview sudah terunggah.
                    </div>
                @endif
                <input type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime" 
                       class="form-control @error('video') is-invalid @enderror">
                <small class="d-block mt-1" style="font-size: 11.5px; color:var(--text-muted);">Biarkan kosong jika tidak ingin mengganti video.</small>
                @error('video')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Berkas Digital Karya</label>
                @if($product->file)
                    <div class="mb-2 small text-success fw-semibold d-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-check-fill"></i> Berkas digital sudah tersimpan aman.
                    </div>
                @endif
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                <small class="d-block mt-1" style="font-size: 11.5px; color:var(--text-muted);">Biarkan kosong jika tidak ingin mengganti file digital.</small>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Tombol Batal & Simpan diposisikan di TENGAH-TENGAH --}}
        <div class="d-flex justify-content-center align-items-center gap-3 pt-4 border-top mt-4">
            <a href="{{ route('penjual.produk.index') }}" class="btn btn-light fw-semibold px-5 rounded-pill border py-2.5">Batal</a>
            <button type="submit" class="btn fw-bold px-5 rounded-pill shadow-sm py-2.5 text-white" style="background:var(--primary);">
                <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
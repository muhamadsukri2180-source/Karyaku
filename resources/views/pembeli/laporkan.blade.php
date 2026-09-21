@extends('layouts.pembeli')
@section('title', 'Laporkan Pelanggaran - Karyaku')

@push('styles')
<style>
    .report-card-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .form-check-card {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 10px 16px;
        transition: all 0.2s ease;
        background: #f8fafc;
        cursor: pointer;
    }
    .form-check-card:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .form-check-card input:checked ~ label {
        color: var(--primary);
        font-weight: 700;
    }
    .report-card-box .form-control,
    .report-card-box .form-select {
        background-color: #f1f5f9 !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b;
        font-weight: 500;
    }
    .report-card-box .form-control:focus,
    .report-card-box .form-select:focus {
        background-color: #ffffff !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
</style>
@endpush

@section('content')

{{-- Header Halaman --}}
<div class="mb-4">
    <h4 class="fw-extrabold text-dark mb-1">
        <i class="bi bi-shield-exclamation text-danger me-2"></i>Laporkan Pelanggaran
    </h4>
    <p class="text-muted mb-0 small">
        Laporkan produk atau akun yang melanggar ketentuan Karyaku. Laporan Anda akan ditinjau secara ketat oleh tim admin.
    </p>
</div>

@if (session('error'))
    <div class="alert alert-danger rounded-3 small mb-3">{{ session('error') }}</div>
@endif
@if (session('success'))
    <div class="alert alert-success rounded-3 small mb-3">{{ session('success') }}</div>
@endif

@php
    $defaultTarget = request('product_id') ? 'produk' : old('target_type', 'produk');
@endphp

{{-- Card Form Laporan --}}
<div class="report-card-box p-4 p-md-5">
    <form action="{{ route('reports.store') }}" method="POST">
        @csrf

        {{-- Pilihan Tipe Target --}}
        <div class="mb-4">
            <label class="form-label small fw-bold text-dark mb-2">Apa yang ingin kamu laporkan? <span class="text-danger">*</span></label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check-card flex-fill">
                    <div class="form-check mb-0">
                        <input class="form-check-input target-type" type="radio" name="target_type" id="tProduk" value="produk" {{ $defaultTarget == 'produk' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold text-secondary cursor-pointer ms-1" for="tProduk">Produk Tertentu</label>
                    </div>
                </div>
                <div class="form-check-card flex-fill">
                    <div class="form-check mb-0">
                        <input class="form-check-input target-type" type="radio" name="target_type" id="tUser" value="pengguna" {{ old('target_type') == 'pengguna' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold text-secondary cursor-pointer ms-1" for="tUser">Pengguna (Pembeli / Penjual)</label>
                    </div>
                </div>
                <div class="form-check-card flex-fill">
                    <div class="form-check mb-0">
                        <input class="form-check-input target-type" type="radio" name="target_type" id="tLain" value="lainnya" {{ old('target_type') == 'lainnya' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold text-secondary cursor-pointer ms-1" for="tLain">Lainnya</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dropdown Produk --}}
        <div class="mb-4" id="groupProduk">
            <label class="form-label small fw-bold text-dark">Pilih Produk Yang Dilaporkan <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select rounded-3 py-2.5">
                <option value="">Pilih Produk yang sesuai pada daftar</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id_product }}" {{ old('product_id', request('product_id')) == $product->id_product ? 'selected' : '' }}>
                        {{ $product->title }} (Penjual: {{ $product->seller->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Dropdown Pengguna --}}
        <div class="mb-4 d-none" id="groupUser">
            <label class="form-label small fw-bold text-dark">Pilih Pengguna Yang Dilaporkan (Pembeli / Penjual) <span class="text-danger">*</span></label>
            <select name="reported_user_id" class="form-select rounded-3 py-2.5">
                <option value="">Pilih pengguna yang ingin dilaporkan</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id_user }}" {{ old('reported_user_id') == $u->id_user ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->role->role_name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Dropdown Alasan --}}
        <div class="mb-4">
            <label class="form-label small fw-bold text-dark">Alasan Laporan <span class="text-danger">*</span></label>
            <select name="reason" class="form-select rounded-3 py-2.5" required>
                <option value="">Pilih alasan utama pelaporan</option>
                <option value="Konten tidak sesuai / palsu" {{ old('reason') == 'Konten tidak sesuai / palsu' ? 'selected' : '' }}>Konten tidak sesuai / palsu</option>
                <option value="Penipuan / tidak mengirim pesanan" {{ old('reason') == 'Penipuan / tidak mengirim pesanan' ? 'selected' : '' }}>Penipuan / tidak mengirim pesanan</option>
                <option value="Pelanggaran hak cipta" {{ old('reason') == 'Pelanggaran hak cipta' ? 'selected' : '' }}>Pelanggaran hak cipta</option>
                <option value="Perilaku tidak sopan" {{ old('reason') == 'Perilaku tidak sopan' ? 'selected' : '' }}>Perilaku tidak sopan</option>
                <option value="Lainnya" {{ old('reason') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        {{-- Input Textarea --}}
        <div class="mb-4">
            <label class="form-label small fw-bold text-dark">Keterangan Detail <span class="text-danger">*</span></label>
            <textarea name="description" rows="4" class="form-control rounded-3" placeholder="Jelaskan kronologi, detail kejadian, atau bukti pelanggaran secara lengkap..." required>{{ old('description') }}</textarea>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex align-items-center gap-2 flex-wrap pt-3 border-top">
            <button type="submit" class="btn btn-danger fw-bold px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="font-size: 13.5px;">
                <i class="bi bi-send-fill"></i> Kirim Laporan
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary px-4 py-2.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 13.5px;">
                <i class="bi bi-clock-history"></i> Riwayat Laporan Saya
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const groupProduk = document.getElementById('groupProduk');
    const groupUser = document.getElementById('groupUser');

    function syncTargetType() {
        const selected = document.querySelector('.target-type:checked')?.value;
        if (groupProduk) groupProduk.classList.toggle('d-none', selected !== 'produk');
        if (groupUser) groupUser.classList.toggle('d-none', selected !== 'pengguna');
    }

    document.querySelectorAll('.target-type').forEach(el => el.addEventListener('change', syncTargetType));
    syncTargetType();
</script>
@endpush

@endsection

@extends('layouts.penjual')
@section('title', 'Laporan')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.10);
        --text-muted:#64748b;
        --text-dark:#1e293b;
    }
    .seller-page-head h4 { font-size: 22px; font-weight: 800; letter-spacing: -.3px; color: var(--text-dark); }
    .seller-page-head p { color: var(--text-muted); }
    .kk-card { background: #fff; border: 1px solid var(--border-color) !important; border-radius: 18px; box-shadow: var(--shadow); }
</style>

<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-shield-exclamation me-2" style="color:#ef4444;"></i>Laporan</h4>
    <p class="small mb-0">Laporkan pembeli, pengguna, atau produk yang bermasalah. Laporan Anda akan ditinjau oleh tim Verifikator, Admin, dan Customer Service Karyaku.</p>
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 small">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 small">{{ session('error') }}</div>
@endif

<div class="row g-4">
    {{-- FORM LAPORAN --}}
    <div class="col-lg-5">
        <div class="kk-card p-4 h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-dark);"><i class="bi bi-flag-fill me-2" style="color:var(--primary);"></i>Buat Laporan Baru</h6>

            <form action="{{ route('penjual.laporan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Apa yang ingin kamu laporkan? <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 flex-wrap mt-1">
                        <div class="form-check">
                            <input class="form-check-input target-type" type="radio" name="target_type" id="tPengguna" value="pengguna" {{ old('target_type', 'pengguna') == 'pengguna' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-medium" for="tPengguna">Pengguna (Pembeli / Penjual)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input target-type" type="radio" name="target_type" id="tProduk" value="produk" {{ old('target_type') == 'produk' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-medium" for="tProduk">Produk Tertentu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input target-type" type="radio" name="target_type" id="tLain" value="lainnya" {{ old('target_type') == 'lainnya' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-medium" for="tLain">Lainnya</label>
                        </div>
                    </div>
                    @error('target_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Dropdown Pengguna --}}
                <div class="mb-3" id="groupUser">
                    <label class="form-label fw-bold small text-dark">Pilih Pengguna Yang Dilaporkan (Pembeli / Penjual) <span class="text-danger">*</span></label>
                    <select name="reported_user_id" class="form-select @error('reported_user_id') is-invalid @enderror">
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id_user }}" {{ old('reported_user_id') == $u->id_user ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role->role_name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('reported_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Dropdown Produk --}}
                <div class="mb-3 d-none" id="groupProduk">
                    <label class="form-label fw-bold small text-dark">Pilih Produk Yang Dilaporkan <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id_product }}" {{ old('product_id') == $product->id_product ? 'selected' : '' }}>
                                {{ $product->title }} (Penjual: {{ $product->seller->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Alasan Laporan <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select @error('reason') is-invalid @enderror" required>
                        <option value="">-- Pilih Alasan --</option>
                        <option value="Penipuan / tidak membayar pesanan" {{ old('reason') == 'Penipuan / tidak membayar pesanan' ? 'selected' : '' }}>Penipuan / tidak membayar pesanan</option>
                        <option value="Perilaku tidak sopan" {{ old('reason') == 'Perilaku tidak sopan' ? 'selected' : '' }}>Perilaku tidak sopan</option>
                        <option value="Penyalahgunaan chat/komplain" {{ old('reason') == 'Penyalahgunaan chat/komplain' ? 'selected' : '' }}>Penyalahgunaan chat/komplain</option>
                        <option value="Konten tidak sesuai / palsu" {{ old('reason') == 'Konten tidak sesuai / palsu' ? 'selected' : '' }}>Konten tidak sesuai / palsu</option>
                        <option value="Pelanggaran hak cipta" {{ old('reason') == 'Pelanggaran hak cipta' ? 'selected' : '' }}>Pelanggaran hak cipta</option>
                        <option value="Lainnya" {{ old('reason') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark">Keterangan Detail <span class="text-danger">*</span></label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan kronologi atau bukti pelanggaran secara lengkap..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn fw-bold w-100 py-2 rounded-3" style="background:var(--primary); color:#fff;">
                    <i class="bi bi-send me-1"></i> Kirim Laporan
                </button>
            </form>
        </div>
    </div>

    {{-- RIWAYAT LAPORAN --}}
    <div class="col-lg-7">
        <div class="kk-card p-4 h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-dark);"><i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>Riwayat Laporan Saya</h6>

            @if($reports->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Kamu belum pernah mengirim laporan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($reports as $report)
                        @php
                            $statusBadge = match($report->status) {
                                'reviewed' => ['bg' => '#ecfdf5', 'color' => '#16a34a', 'label' => 'Ditindaklanjuti'],
                                'dismissed' => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Ditolak / Selesai'],
                                'escalated' => ['bg' => '#eef2ff', 'color' => '#4f46e5', 'label' => 'Dieskalasi'],
                                default => ['bg' => '#fff7ed', 'color' => '#f59e0b', 'label' => 'Sedang Ditinjau'],
                            };
                        @endphp
                        <div class="p-3 rounded-3" style="border:1px solid var(--border-color); background:#f8fafc;">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-1">
                                <div class="overflow-hidden">
                                    @if ($report->product)
                                        <div class="fw-bold small" style="color:var(--text-dark);">{{ $report->product->title }}</div>
                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Produk Digital</span>
                                    @elseif ($report->reportedUser)
                                        <div class="fw-bold small" style="color:var(--text-dark);">{{ $report->reportedUser->name }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Pengguna</span>
                                    @else
                                        <span class="text-muted small">Umum / Lainnya</span>
                                    @endif
                                </div>
                                <span class="badge px-3 py-1 rounded-pill flex-shrink-0" style="background:{{ $statusBadge['bg'] }}; color:{{ $statusBadge['color'] }}; font-size: 11px;">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>
                            <div class="small mb-1" style="color:var(--text-dark);"><span class="text-muted">Alasan:</span> {{ $report->reason }}</div>
                            @if($report->admin_note)
                                <div class="small mb-1" style="color:var(--text-muted);"><span class="fw-semibold">Catatan petugas:</span> {{ $report->admin_note }}</div>
                            @endif
                            <div style="font-size: 11px; color:var(--text-muted);">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $reports->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const groupProduk = document.getElementById('groupProduk');
        const groupUser = document.getElementById('groupUser');

        function syncTargetType() {
            const selected = document.querySelector('.target-type:checked')?.value;
            if (groupProduk) groupProduk.classList.toggle('d-none', selected !== 'produk');
            if (groupUser) groupUser.classList.toggle('d-none', selected !== 'pengguna');
        }

        document.querySelectorAll('.target-type').forEach(el => el.addEventListener('change', syncTargetType));
        syncTargetType();
    })();
</script>
@endpush

@endsection

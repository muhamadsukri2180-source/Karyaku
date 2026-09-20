@extends('layouts.penjual')
@section('title', 'Pusat Laporan & Pengaduan - Penjual')

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
    .report-card-item {
        border: 1px solid var(--border-color);
        background: #f8fafc;
        border-radius: 14px;
        transition: all .2s ease;
    }
    .report-card-item:hover {
        background: #fff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15,23,42,.06);
    }
    .report-card-item.is-pending {
        border-left: 4px solid #f59e0b;
        background: #fffdfa;
    }
    .report-card-item.is-resolved {
        border-left: 4px solid #10b981;
    }
    .nav-tabs-report .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 13px;
        border: none;
        border-bottom: 2.5px solid transparent;
        padding: 8px 14px;
        background: transparent;
        transition: all .2s;
    }
    .nav-tabs-report .nav-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: transparent;
        font-weight: 700;
    }
</style>

<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-shield-exclamation me-2" style="color:#ef4444;"></i>Pusat Laporan & Pengaduan</h4>
    <p class="small mb-0">Pantau laporan masuk terhadap toko/produk Anda serta riwayat laporan pelanggaran yang Anda ajukan.</p>
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 small mb-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 small mb-3">{{ session('error') }}</div>
@endif

<div class="row g-4">
    {{-- FORM BUAT LAPORAN BARU --}}
    <div class="col-lg-5">
        <div class="kk-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0" style="color:var(--text-dark);">
                    <i class="bi bi-flag-fill me-2" style="color:var(--primary);"></i>Buat Laporan Baru
                </h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size:10px;">Laporan Keluar</span>
            </div>

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
                        <option value=""> Pilih Pengguna </option>
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
                        <option value=""> Pilih Produk </option>
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
                        <option value=""> Pilih Alasan </option>
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

                <button type="submit" class="btn fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background:var(--primary); color:#fff;">
                    <i class="bi bi-send me-1"></i> Kirim Laporan
                </button>
            </form>
        </div>
    </div>

    {{-- RIWAYAT LAPORAN DENGAN SISTEM TAB --}}
    <div class="col-lg-7">
        <div class="kk-card p-4 h-100">
            {{-- NAV TABS --}}
            <ul class="nav nav-tabs nav-tabs-report border-bottom mb-3" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab', 'masuk') === 'masuk' ? 'active' : '' }}" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#tab-masuk" type="button" role="tab" aria-selected="true">
                        <i class="bi bi-inbox-fill me-1"></i> Laporan Masuk
                        @if($incomingReports->total() > 0)
                            <span class="badge {{ $pendingIncomingCount > 0 ? 'bg-danger' : 'bg-secondary' }} rounded-pill ms-1" style="font-size:10px;">
                                {{ $incomingReports->total() }}
                            </span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') === 'saya' ? 'active' : '' }}" id="saya-tab" data-bs-toggle="tab" data-bs-target="#tab-saya" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-clock-history me-1"></i> Laporan yang Saya Kirim
                        @if($reports->total() > 0)
                            <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size:10px;">
                                {{ $reports->total() }}
                            </span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabContent">
                {{-- ================= TAB 1: LAPORAN MASUK TERHADAP TOKO/PRODUK SAYA ================= --}}
                <div class="tab-pane fade {{ request('tab', 'masuk') === 'masuk' ? 'show active' : '' }}" id="tab-masuk" role="tabpanel">
                    @if($incomingReports->isEmpty())
                        <div class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-shield-check fs-1 d-block mb-2 text-success opacity-75"></i>
                            <h6 class="fw-bold text-dark mb-1">Toko & Produk Anda Bersih</h6>
                            <p class="small mb-0">Tidak ada laporan pelanggaran yang masuk terhadap akun atau produk Anda.</p>
                        </div>
                    @else
                        <div class="alert alert-light border small text-muted py-2 px-3 mb-3 rounded-3 d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle text-primary"></i>
                            <span>Laporan di bawah diajukan oleh pembeli/pengguna lain dan sedang/telah ditinjau oleh tim verifikator.</span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @foreach($incomingReports as $report)
                                @php
                                    $handledStatuses = ['reviewed', 'resolved', 'action_taken', 'escalated'];
                                    $isPending = $report->status === 'pending';
                                    $isResolved = in_array($report->status, $handledStatuses);

                                    $statusBadge = match(true) {
                                        $isResolved => ['bg' => '#ecfdf5', 'color' => '#16a34a', 'label' => 'Ditindaklanjuti'],
                                        $report->status === 'dismissed' => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Ditolak / Diabaikan'],
                                        $report->status === 'escalated' => ['bg' => '#eef2ff', 'color' => '#4f46e5', 'label' => 'Dieskalasi ke Admin'],
                                        default => ['bg' => '#fff7ed', 'color' => '#d97706', 'label' => 'Sedang Ditinjau'],
                                    };
                                @endphp
                                <div class="report-card-item p-3 {{ $isPending ? 'is-pending' : ($isResolved ? 'is-resolved' : '') }}">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-1.5">
                                        <div class="overflow-hidden">
                                            @if ($report->product)
                                                <div class="fw-bold small text-dark"><i class="bi bi-box-seam me-1 text-primary"></i>{{ $report->product->title }}</div>
                                                <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Produk Digital Anda</span>
                                            @else
                                                <div class="fw-bold small text-dark"><i class="bi bi-person-exclamation me-1 text-danger"></i>Akun Toko Anda Dilaporkan</div>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Profil Penjual</span>
                                            @endif
                                        </div>
                                        <span class="badge px-2.5 py-1 rounded-pill flex-shrink-0" style="background:{{ $statusBadge['bg'] }}; color:{{ $statusBadge['color'] }}; font-size: 11px;">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>

                                    <div class="small mb-1" style="color:var(--text-dark);">
                                        <span class="text-muted fw-medium">Alasan:</span> <span class="fw-semibold text-danger">{{ $report->reason }}</span>
                                    </div>

                                    @if($report->description)
                                        <div class="p-2.5 rounded-3 bg-white border small text-secondary mb-2" style="font-size:12px; line-height:1.45;">
                                            <span class="text-muted fw-medium">Keterangan Pelapor:</span> {{ $report->description }}
                                        </div>
                                    @endif

                                    @if($report->admin_note)
                                        <div class="p-2.5 rounded-3 mb-2 small border-start border-3 border-primary" style="background:#eff6ff; font-size:12px;">
                                            <span class="fw-bold text-primary"><i class="bi bi-shield-check me-1"></i>Catatan Petugas/Admin:</span>
                                            <div class="text-dark mt-0.5">{{ $report->admin_note }}</div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted" style="font-size: 11px;">
                                        <span><i class="bi bi-person me-1"></i>Pelapor: {{ $report->reporter->name ?? 'Pengguna' }}</span>
                                        <span><i class="bi bi-clock me-1"></i>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-3">{{ $incomingReports->links() }}</div>
                    @endif
                </div>

                {{-- ================= TAB 2: LAPORAN YANG SAYA AJUKAN (KELUAR) ================= --}}
                <div class="tab-pane fade {{ request('tab') === 'saya' ? 'show active' : '' }}" id="tab-saya" role="tabpanel">
                    @if($reports->isEmpty())
                        <div class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Laporan Terkirim</h6>
                            <p class="small mb-0">Kamu belum pernah mengirim laporan pelanggaran.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($reports as $report)
                                @php
                                    $handledStatuses = ['reviewed', 'resolved', 'action_taken', 'escalated'];
                                    $statusBadge = match(true) {
                                        in_array($report->status, $handledStatuses) => ['bg' => '#ecfdf5', 'color' => '#16a34a', 'label' => 'Ditindaklanjuti'],
                                        $report->status === 'dismissed' => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Ditolak / Selesai'],
                                        $report->status === 'escalated' => ['bg' => '#eef2ff', 'color' => '#4f46e5', 'label' => 'Dieskalasi'],
                                        default => ['bg' => '#fff7ed', 'color' => '#f59e0b', 'label' => 'Sedang Ditinjau'],
                                    };
                                @endphp
                                <div class="report-card-item p-3">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-1">
                                        <div class="overflow-hidden">
                                            @if ($report->product)
                                                <div class="fw-bold small" style="color:var(--text-dark);">{{ $report->product->title }}</div>
                                                <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Produk Digital</span>
                                            @elseif ($report->reportedUser)
                                                <div class="fw-bold small" style="color:var(--text-dark);">{{ $report->reportedUser->name }}</div>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Pengguna Terlapor</span>
                                            @else
                                                <span class="text-muted small">Umum / Lainnya</span>
                                            @endif
                                        </div>
                                        <span class="badge px-2.5 py-1 rounded-pill flex-shrink-0" style="background:{{ $statusBadge['bg'] }}; color:{{ $statusBadge['color'] }}; font-size: 11px;">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>
                                    <div class="small mb-1" style="color:var(--text-dark);"><span class="text-muted">Alasan:</span> {{ $report->reason }}</div>
                                    @if($report->description)
                                        <div class="text-muted small mb-1" style="font-size:12px;">{{ \Illuminate\Support\Str::limit($report->description, 100) }}</div>
                                    @endif
                                    @if($report->admin_note)
                                        <div class="p-2 rounded-3 bg-light border-start border-3 border-success small mb-1" style="font-size:11.5px;">
                                            <span class="fw-semibold text-dark">Catatan Petugas:</span> {{ $report->admin_note }}
                                        </div>
                                    @endif
                                    <div class="text-muted" style="font-size: 11px;"><i class="bi bi-clock me-1"></i>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-3">{{ $reports->links() }}</div>
                    @endif
                </div>
            </div>
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

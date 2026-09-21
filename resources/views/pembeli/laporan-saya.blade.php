@extends('layouts.pembeli')
@section('title', 'Pusat Laporan & Pengaduan - Karyaku')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --border-color:#e5e7eb;
        --shadow:0 5px 20px rgba(15,23,42,.06);
        --text-muted:#64748b;
        --text-dark:#1e293b;
        --bg-input:#f8fafc;
    }

    .pembeli-page-head h4 { font-size:22px; font-weight:800; letter-spacing:-.3px; color:var(--text-dark); }
    .pembeli-page-head p  { color:var(--text-muted); }

    .kk-card { background:#fff; border:1px solid var(--border-color) !important; border-radius:18px; box-shadow:var(--shadow); }

    .kk-card-title { display:flex; align-items:center; gap:9px; font-size:14px; font-weight:700; color:var(--text-dark); }
    .kk-card-title i { color:var(--primary); }

    /* Formulir */
    .form-label { font-size:12.5px; font-weight:600; color:var(--text-dark); margin-bottom:6px; }
    .form-control, .form-select { min-height:44px; border-radius:10px; border-color:var(--border-color); background:var(--bg-input); font-size:13.5px; color:var(--text-dark); }
    .form-control:focus, .form-select:focus { background:#fff; border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    textarea.form-control { min-height:110px; resize:vertical; }

    /* Pilihan target laporan berbentuk kartu */
    .target-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; }
    .target-opt input { position:absolute; opacity:0; pointer-events:none; }
    .target-opt label { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:5px; text-align:center; height:100%; padding:12px 8px; border:1px solid var(--border-color); border-radius:12px; background:var(--bg-input); font-size:11.5px; font-weight:600; color:var(--text-muted); cursor:pointer; transition:.18s ease; }
    .target-opt label i { font-size:17px; }
    .target-opt label:hover { border-color:var(--primary); color:var(--primary); }
    .target-opt input:checked + label { background:var(--primary-light); border-color:var(--primary); color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,.10); }
    .target-opt input:focus-visible + label { outline:2px solid var(--primary); outline-offset:2px; }

    .btn-kirim { background:var(--primary); color:#fff; font-weight:700; border:0; border-radius:11px; padding:11px 16px; width:100%; transition:.18s ease; }
    .btn-kirim:hover { background:#1d4ed8; color:#fff; }

    /* Tab riwayat */
    .nav-tabs-report { border-bottom:1px solid var(--border-color); gap:4px; }
    .nav-tabs-report .nav-link { color:var(--text-muted); font-weight:600; font-size:13px; border:0; border-bottom:2.5px solid transparent; padding:9px 14px; background:transparent; transition:.18s ease; }
    .nav-tabs-report .nav-link:hover { color:var(--primary); }
    .nav-tabs-report .nav-link.active { color:var(--primary); border-bottom-color:var(--primary); font-weight:700; }

    /* Kartu laporan */
    .report-item { border:1px solid var(--border-color); background:#fff; border-radius:14px; padding:15px 16px; transition:box-shadow .2s ease, border-color .2s ease; }
    .report-item:hover { border-color:#cbd5e1; box-shadow:0 4px 12px rgba(15,23,42,.06); }
    .report-item.is-pending  { box-shadow:inset 3px 0 0 #f59e0b; }
    .report-item.is-resolved { box-shadow:inset 3px 0 0 #10b981; }

    .report-title { font-size:13.5px; font-weight:700; color:var(--text-dark); line-height:1.45; }
    .kk-chip { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:4px 9px; border-radius:7px; }
    .kk-status-pill { font-size:11px; font-weight:700; padding:5px 11px; border-radius:999px; white-space:nowrap; }

    .report-reason { font-size:13px; color:var(--text-dark); margin:10px 0 8px; }
    .report-reason span { color:var(--text-muted); }

    .report-quote { background:var(--bg-input); border:1px solid var(--border-color); border-radius:10px; padding:10px 12px; font-size:12px; line-height:1.6; color:#475569; margin-bottom:8px; }
    .report-quote .lbl { display:block; font-size:10.5px; font-weight:700; color:var(--text-muted); margin-bottom:3px; }

    .report-admin { background:var(--primary-light); border:1px solid #dbeafe; border-left:3px solid var(--primary); border-radius:10px; padding:10px 12px; font-size:12px; line-height:1.6; color:var(--text-dark); margin-bottom:8px; }
    .report-admin .lbl { display:flex; align-items:center; gap:6px; font-size:10.5px; font-weight:700; color:var(--primary); margin-bottom:3px; }

    .report-meta { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; font-size:11px; color:#94a3b8; padding-top:9px; border-top:1px dashed var(--border-color); }

    .kk-empty { text-align:center; padding:50px 20px; color:var(--text-muted); }
    .kk-empty i.big { font-size:2.3rem; display:block; margin-bottom:10px; opacity:.6; }

    @media (max-width: 400px){ .target-grid { grid-template-columns:1fr; } }
    @media (prefers-reduced-motion: reduce){ .report-item, .target-opt label, .btn-kirim, .nav-tabs-report .nav-link { transition:none; } }
</style>

@php
    $petaStatus = function ($status) {
        return match (true) {
            in_array($status, ['reviewed', 'resolved', 'action_taken']) => ['bg' => '#ecfdf5', 'color' => '#16a34a', 'label' => 'Ditindaklanjuti'],
            $status === 'escalated'  => ['bg' => '#eef2ff', 'color' => '#4f46e5', 'label' => 'Dieskalasi ke admin'],
            $status === 'dismissed'  => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Ditolak / selesai'],
            default                  => ['bg' => '#fff7ed', 'color' => '#d97706', 'label' => 'Sedang ditinjau'],
        };
    };
    $tabAktif = request('tab', 'masuk');
    $defaultTarget = request('product_id') ? 'produk' : old('target_type', 'produk');
@endphp

{{-- ================= HEADER ================= --}}
<div class="pembeli-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-shield-exclamation me-2" style="color:#ef4444;"></i>Pusat laporan dan pengaduan</h4>
    <p class="small mb-0">Pantau laporan yang ditujukan terhadap akun Anda, dan lacak laporan yang Anda ajukan.</p>
</div>

@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
@endif

<div class="row g-4">

    {{-- ================= FORM LAPORAN BARU ================= --}}
    <div class="col-lg-5">
        <div class="kk-card p-4 h-100">

            <div class="d-flex align-items-center justify-content-between pb-3 mb-3" style="border-bottom:1px solid var(--border-color);">
                <div class="kk-card-title"><i class="bi bi-flag-fill"></i> Buat laporan baru</div>
                <span class="kk-chip" style="background:var(--primary-light); color:var(--primary);">Laporan keluar</span>
            </div>

            <form action="{{ route('reports.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Apa yang ingin dilaporkan? <span class="text-danger">*</span></label>
                    <div class="target-grid">
                        <div class="target-opt position-relative">
                            <input class="target-type" type="radio" name="target_type" id="tProduk" value="produk" {{ $defaultTarget == 'produk' ? 'checked' : '' }}>
                            <label for="tProduk"><i class="bi bi-box-seam-fill"></i> Produk</label>
                        </div>
                        <div class="target-opt position-relative">
                            <input class="target-type" type="radio" name="target_type" id="tPengguna" value="pengguna" {{ $defaultTarget == 'pengguna' ? 'checked' : '' }}>
                            <label for="tPengguna"><i class="bi bi-person-fill"></i> Pengguna</label>
                        </div>
                        <div class="target-opt position-relative">
                            <input class="target-type" type="radio" name="target_type" id="tLain" value="lainnya" {{ $defaultTarget == 'lainnya' ? 'checked' : '' }}>
                            <label for="tLain"><i class="bi bi-three-dots"></i> Lainnya</label>
                        </div>
                    </div>
                    @error('target_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3" id="groupProduk">
                    <label class="form-label" for="reportedProduct">Produk yang dilaporkan <span class="text-danger">*</span></label>
                    <select name="product_id" id="reportedProduct" class="form-select @error('product_id') is-invalid @enderror">
                        <option value="">Pilih produk yang sesuai pada daftar</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id_product }}" {{ old('product_id', request('product_id')) == $product->id_product ? 'selected' : '' }}>
                                {{ $product->title }} (Penjual: {{ $product->seller->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 d-none" id="groupUser">
                    <label class="form-label" for="reportedUser">Pengguna yang dilaporkan <span class="text-danger">*</span></label>
                    <select name="reported_user_id" id="reportedUser" class="form-select @error('reported_user_id') is-invalid @enderror">
                        <option value="">Pilih pengguna yang ingin dilaporkan</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id_user }}" {{ old('reported_user_id') == $u->id_user ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role->role_name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('reported_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="reason">Alasan laporan <span class="text-danger">*</span></label>
                    <select name="reason" id="reason" class="form-select @error('reason') is-invalid @enderror" required>
                        <option value="">Pilih alasan utama pelaporan</option>
                        @foreach ([
                            'Konten tidak sesuai / palsu',
                            'Penipuan / tidak mengirim pesanan',
                            'Pelanggaran hak cipta',
                            'Perilaku tidak sopan',
                            'Lainnya',
                        ] as $alasan)
                            <option value="{{ $alasan }}" {{ old('reason') == $alasan ? 'selected' : '' }}>{{ $alasan }}</option>
                        @endforeach
                    </select>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Keterangan detail <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4" maxlength="1000"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Jelaskan kronologi, detail kejadian, atau bukti pelanggaran secara lengkap..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="d-block mt-1" style="font-size:11px; color:var(--text-muted);">
                        <span id="hitungHuruf">0</span>/1000 karakter
                    </small>
                </div>

                <div class="d-flex align-items-start gap-2 p-3 rounded-3 mb-3" style="background:var(--bg-input); border:1px solid var(--border-color);">
                    <i class="bi bi-info-circle-fill" style="color:var(--primary);"></i>
                    <small style="color:var(--text-muted); font-size:11.5px; line-height:1.6;">
                        Laporan ditinjau tim verifikator dan admin. Laporan palsu atau berulang tanpa dasar dapat berdampak pada akun Anda.
                    </small>
                </div>

                <button type="submit" class="btn-kirim">
                    <i class="bi bi-send me-1"></i> Kirim laporan
                </button>
            </form>
        </div>
    </div>

    {{-- ================= RIWAYAT LAPORAN ================= --}}
    <div class="col-lg-7">
        <div class="kk-card p-4 h-100">

            <ul class="nav nav-tabs nav-tabs-report mb-3" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tabAktif === 'masuk' ? 'active' : '' }}" id="masuk-tab"
                            data-bs-toggle="tab" data-bs-target="#tab-masuk" type="button" role="tab">
                        <i class="bi bi-inbox-fill me-1"></i> Laporan masuk
                        @if($incomingReports->total() > 0)
                            <span class="badge {{ ($pendingIncomingCount ?? 0) > 0 ? 'bg-danger' : 'bg-secondary' }} rounded-pill ms-1" style="font-size:10px;">
                                {{ $incomingReports->total() }}
                            </span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tabAktif === 'saya' ? 'active' : '' }}" id="saya-tab"
                            data-bs-toggle="tab" data-bs-target="#tab-saya" type="button" role="tab">
                        <i class="bi bi-clock-history me-1"></i> Laporan saya
                        @if($reports->total() > 0)
                            <span class="badge rounded-pill ms-1" style="font-size:10px; background:var(--primary-light); color:var(--primary);">
                                {{ $reports->total() }}
                            </span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabContent">

                {{-- ---------- TAB 1: LAPORAN MASUK ---------- --}}
                <div class="tab-pane fade {{ $tabAktif === 'masuk' ? 'show active' : '' }}" id="tab-masuk" role="tabpanel">
                    @if($incomingReports->isEmpty())
                        <div class="kk-empty">
                            <i class="bi bi-shield-check big text-success"></i>
                            <h6 class="fw-bold mb-1" style="color:var(--text-dark);">Akun Anda bersih</h6>
                            <p class="small mb-0">Tidak ada pengaduan atau laporan pelanggaran terhadap akun Anda.</p>
                        </div>
                    @else
                        <div class="d-flex align-items-start gap-2 p-3 rounded-3 mb-3" style="background:var(--bg-input); border:1px solid var(--border-color);">
                            <i class="bi bi-info-circle" style="color:var(--primary);"></i>
                            <small style="color:var(--text-muted); font-size:11.5px;">
                                Laporan berikut diajukan pengguna lain dan sedang atau telah ditinjau tim verifikator.
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @foreach($incomingReports as $report)
                                @php
                                    $s = $petaStatus($report->status);
                                    $isPending  = $report->status === 'pending';
                                    $isResolved = in_array($report->status, ['reviewed', 'resolved', 'action_taken', 'escalated']);
                                @endphp

                                <div class="report-item {{ $isPending ? 'is-pending' : ($isResolved ? 'is-resolved' : '') }}">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div class="overflow-hidden">
                                            @if ($report->product)
                                                <div class="report-title"><i class="bi bi-box-seam me-1" style="color:var(--primary);"></i>{{ $report->product->title }}</div>
                                                <span class="kk-chip mt-1" style="background:var(--primary-light); color:var(--primary);">Produk digital</span>
                                            @else
                                                <div class="report-title"><i class="bi bi-person-exclamation me-1 text-danger"></i>Akun Anda dilaporkan</div>
                                                <span class="kk-chip mt-1" style="background:#f1f5f9; color:#475569;">Profil akun</span>
                                            @endif
                                        </div>
                                        <span class="kk-status-pill flex-shrink-0" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                                            {{ $s['label'] }}
                                        </span>
                                    </div>

                                    <div class="report-reason">
                                        <span>Alasan:</span> <strong class="text-danger">{{ $report->reason }}</strong>
                                    </div>

                                    @if($report->description)
                                        <div class="report-quote">
                                            <span class="lbl">Keterangan pelapor</span>
                                            {{ $report->description }}
                                        </div>
                                    @endif

                                    @if($report->admin_note)
                                        <div class="report-admin">
                                            <span class="lbl"><i class="bi bi-shield-check"></i> Catatan petugas</span>
                                            {{ $report->admin_note }}
                                        </div>
                                    @endif

                                    <div class="report-meta">
                                        <span><i class="bi bi-person me-1"></i>Pelapor: {{ $report->reporter->name ?? 'Pengguna' }}</span>
                                        <span><i class="bi bi-clock me-1"></i>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-3">{{ $incomingReports->links() }}</div>
                    @endif
                </div>

                {{-- ---------- TAB 2: LAPORAN YANG SAYA KIRIM ---------- --}}
                <div class="tab-pane fade {{ $tabAktif === 'saya' ? 'show active' : '' }}" id="tab-saya" role="tabpanel">
                    @if($reports->isEmpty())
                        <div class="kk-empty">
                            <i class="bi bi-inbox big"></i>
                            <h6 class="fw-bold mb-1" style="color:var(--text-dark);">Belum ada laporan terkirim</h6>
                            <p class="small mb-0">Laporan yang Anda ajukan akan tampil di sini beserta tindak lanjutnya.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($reports as $report)
                                @php $s = $petaStatus($report->status); @endphp

                                <div class="report-item">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div class="overflow-hidden">
                                            @if ($report->product)
                                                <div class="report-title">{{ $report->product->title }}</div>
                                                <span class="kk-chip mt-1" style="background:var(--primary-light); color:var(--primary);">Produk digital</span>
                                            @elseif ($report->reportedUser)
                                                <div class="report-title">{{ $report->reportedUser->name }}</div>
                                                <span class="kk-chip mt-1" style="background:#f1f5f9; color:#475569;">Pengguna terlapor</span>
                                            @else
                                                <div class="report-title">Laporan umum</div>
                                                <span class="kk-chip mt-1" style="background:#f1f5f9; color:#475569;">Lainnya</span>
                                            @endif
                                        </div>
                                        <span class="kk-status-pill flex-shrink-0" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                                            {{ $s['label'] }}
                                        </span>
                                    </div>

                                    <div class="report-reason">
                                        <span>Alasan:</span> <strong>{{ $report->reason }}</strong>
                                    </div>

                                    @if($report->description)
                                        <div class="report-quote">
                                            <span class="lbl">Keterangan Anda</span>
                                            {{ \Illuminate\Support\Str::limit($report->description, 160) }}
                                        </div>
                                    @endif

                                    @if($report->admin_note)
                                        <div class="report-admin">
                                            <span class="lbl"><i class="bi bi-shield-check"></i> Catatan petugas</span>
                                            {{ $report->admin_note }}
                                        </div>
                                    @endif

                                    <div class="report-meta">
                                        <span><i class="bi bi-send me-1"></i>Dikirim oleh Anda</span>
                                        <span><i class="bi bi-clock me-1"></i>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const groupProduk = document.getElementById('groupProduk');
    const groupUser   = document.getElementById('groupUser');

    function syncTargetType() {
        const dipilih = document.querySelector('.target-type:checked');
        const nilai = dipilih ? dipilih.value : null;
        if (groupProduk) groupProduk.classList.toggle('d-none', nilai !== 'produk');
        if (groupUser)   groupUser.classList.toggle('d-none', nilai !== 'pengguna');
    }

    document.querySelectorAll('.target-type').forEach(function (el) {
        el.addEventListener('change', syncTargetType);
    });
    syncTargetType();

    const desc = document.getElementById('description');
    const hitung = document.getElementById('hitungHuruf');
    if (desc && hitung) {
        const perbarui = function () { hitung.textContent = desc.value.length; };
        desc.addEventListener('input', perbarui);
        perbarui();
    }
});
</script>
@endpush
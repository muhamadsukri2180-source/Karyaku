@extends('layouts.penjual')
@section('title', 'Saldo & Penarikan Dana')

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
    .kk-stat { border-radius: 18px; transition: .2s ease; }
    .kk-stat:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    .kk-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .icon-green { background: #ecfdf5; color: #16a34a; }
    .icon-orange { background: #fff7ed; color: #f59e0b; }
    .icon-blue { background: var(--primary-light); color: var(--primary); }
</style>

<div class="seller-page-head mb-4">
    <h4 class="mb-1"><i class="bi bi-wallet2 me-2" style="color:#16a34a;"></i>Saldo & Penarikan Dana</h4>
    <p class="small mb-0">Lihat total pendapatan dan ajukan permintaan penarikan saldo ke rekening bank Anda.</p>
</div>

{{-- RINGKASAN SALDO --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="kk-card kk-stat p-4 h-100" style="background:#ecfdf5;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small fw-semibold" style="color:var(--text-muted);">Total Omset Penjualan</span>
                <div class="kk-icon icon-green"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <h3 class="fw-bold mb-1" style="color:#16a34a;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <small style="color:var(--text-muted);">Dari semua penjualan lunas</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kk-card kk-stat p-4 h-100" style="background:#fff7ed;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small fw-semibold" style="color:var(--text-muted);">Total Sudah Ditarik</span>
                <div class="kk-icon icon-orange"><i class="bi bi-arrow-bar-down"></i></div>
            </div>
            <h3 class="fw-bold mb-1" style="color:#f59e0b;">Rp {{ number_format($totalDitarik, 0, ',', '.') }}</h3>
            <small style="color:var(--text-muted);">Penarikan selesai & diproses</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kk-card kk-stat p-4 h-100" style="background:var(--primary-light);">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small fw-semibold" style="color:var(--text-muted);">Saldo Tersedia</span>
                <div class="kk-icon icon-blue"><i class="bi bi-wallet-fill"></i></div>
            </div>
            <h3 class="fw-bold mb-1" style="color:var(--primary);">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h3>
            <small style="color:var(--text-muted);">Siap untuk ditarik</small>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- FORM PENARIKAN --}}
    <div class="col-lg-5">
        <div class="kk-card p-4 h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-dark);"><i class="bi bi-send-fill me-2" style="color:var(--primary);"></i>Ajukan Penarikan Dana</h6>
            @if($saldoTersedia < 20000)
                <div class="alert p-3 small mb-3 rounded-3" style="background:#fff7ed; color:#b45309; border:1px solid #fed7aa;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Saldo Anda belum mencapai minimum penarikan sebesar <strong>Rp 20.000</strong>.</div>
            @endif
            <form action="{{ route('penjual.keuangan.tarik') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Bank / E-Wallet <span class="text-danger">*</span></label>
                    <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror" required>
                        <option value="">-- Pilih Bank / E-Wallet --</option>
                        <optgroup label="Bank Nasional">
                            <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                            <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                        </optgroup>
                        <optgroup label="E-Wallet">
                            <option value="GoPay" {{ old('bank_name') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                            <option value="OVO" {{ old('bank_name') == 'OVO' ? 'selected' : '' }}>OVO</option>
                            <option value="Dana" {{ old('bank_name') == 'Dana' ? 'selected' : '' }}>Dana</option>
                        </optgroup>
                    </select>
                    @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nomor Rekening / Dompet <span class="text-danger">*</span></label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" class="form-control @error('bank_account_number') is-invalid @enderror" placeholder="Contoh: 1234567890" required>
                    @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', Auth::user()->name) }}" class="form-control @error('bank_account_name') is-invalid @enderror" placeholder="Sesuai nama di buku tabungan" required>
                    @error('bank_account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark">Nominal Penarikan (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold small">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="20000" max="{{ $saldoTersedia }}" step="5000" class="form-control @error('amount') is-invalid @enderror" placeholder="Minimal 20.000" required>
                    </div>
                    <small style="font-size: 11px; color:var(--text-muted);">Saldo tersedia: Rp {{ number_format($saldoTersedia, 0, ',', '.') }} | Min penarikan: Rp 20.000</small>
                    @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn fw-bold w-100 py-2 rounded-3" style="background:var(--primary); color:#fff;" {{ $saldoTersedia < 20000 ? 'disabled' : '' }} onclick="return confirm('Yakin ingin mengajukan penarikan dana?')"><i class="bi bi-send me-1"></i> Ajukan Penarikan</button>
            </form>
        </div>
    </div>

    {{-- RIWAYAT PENARIKAN --}}
    <div class="col-lg-7">
        <div class="kk-card p-4 h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-dark);"><i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>Riwayat Pengajuan Penarikan</h6>
            @if($withdrawals->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i><p class="small mb-0">Belum ada pengajuan penarikan dana sebelumnya.</p></div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($withdrawals as $w)
                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between gap-3" style="border:1px solid var(--border-color); background:#f8fafc;">
                            <div class="overflow-hidden">
                                <div class="fw-bold small mb-1" style="color:var(--text-dark);">{{ $w->bank_name }} - {{ $w->bank_account_number }}</div>
                                <div style="font-size: 11px; color:var(--text-muted);">a/n {{ $w->bank_account_name }} &bull; {{ $w->created_at->translatedFormat('d M Y, H:i') }}</div>
                                @if($w->notes)<div class="small mt-1" style="color:var(--text-muted);">Catatan: {{ $w->notes }}</div>@endif
                            </div>
                            <div class="text-end flex-shrink-0">
                                <h6 class="fw-bold mb-1" style="color:var(--text-dark);">Rp {{ number_format($w->amount, 0, ',', '.') }}</h6>
                                @if($w->status === 'completed')
                                    <span class="badge" style="background:#ecfdf5; color:#16a34a;"><i class="bi bi-check-circle me-1"></i>Berhasil</span>
                                @elseif($w->status === 'pending')
                                    <span class="badge" style="background:#fff7ed; color:#f59e0b;"><i class="bi bi-clock me-1"></i>Diproses</span>
                                @else
                                    <span class="badge" style="background:#fef2f2; color:#ef4444;"><i class="bi bi-x-circle me-1"></i>Gagal/Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $withdrawals->links() }}</div>
            @endif
        </div>
    </div>
</div>

@endsection
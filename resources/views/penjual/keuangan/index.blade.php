@extends('layouts.penjual')
@section('title', 'Saldo & Penarikan Dana')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-wallet2 text-success me-2"></i>Saldo & Penarikan Dana</h4>
    <p class="text-muted small mb-0">Lihat total pendapatan dan ajukan permintaan penarikan saldo ke rekening bank Anda.</p>
</div>

{{-- RINGKASAN SALDO --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-box p-4 border h-100 bg-success-subtle bg-opacity-25 border-success-subtle">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Total Omset Penjualan</span>
                <div class="rounded-3 p-2 bg-success-subtle text-success"><i class="bi bi-graph-up-arrow fs-5"></i></div>
            </div>
            <h3 class="fw-bold text-success mb-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <small class="text-muted">Dari semua penjualan lunas</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box p-4 border h-100 bg-warning-subtle bg-opacity-25 border-warning-subtle">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Total Sudah Ditarik</span>
                <div class="rounded-3 p-2 bg-warning-subtle text-warning"><i class="bi bi-arrow-bar-down fs-5"></i></div>
            </div>
            <h3 class="fw-bold text-warning mb-1">Rp {{ number_format($totalDitarik, 0, ',', '.') }}</h3>
            <small class="text-muted">Penarikan selesai & diproses</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box p-4 border h-100 bg-primary-subtle bg-opacity-25 border-primary-subtle">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold">Saldo Tersedia</span>
                <div class="rounded-3 p-2 bg-primary-subtle text-primary"><i class="bi bi-wallet-fill fs-5"></i></div>
            </div>
            <h3 class="fw-bold text-primary mb-1">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h3>
            <small class="text-muted">Siap untuk ditarik</small>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- FORM PENARIKAN --}}
    <div class="col-lg-5">
        <div class="card-box p-4 border">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-send-fill text-primary me-2"></i>Ajukan Penarikan Dana</h6>

            @if($saldoTersedia < 20000)
                <div class="alert alert-warning p-3 small mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Saldo Anda belum mencapai minimum penarikan sebesar <strong>Rp 20.000</strong>.
                </div>
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
                    @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nomor Rekening / Dompet <span class="text-danger">*</span></label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                           class="form-control @error('bank_account_number') is-invalid @enderror"
                           placeholder="Contoh: 1234567890" required>
                    @error('bank_account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', Auth::user()->name) }}"
                           class="form-control @error('bank_account_name') is-invalid @enderror"
                           placeholder="Sesuai nama di buku tabungan" required>
                    @error('bank_account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark">Nominal Penarikan (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold small">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="20000" max="{{ $saldoTersedia }}" step="5000"
                               class="form-control @error('amount') is-invalid @enderror"
                               placeholder="Minimal 20.000" required>
                    </div>
                    <small class="text-muted" style="font-size: 11px;">Saldo tersedia: Rp {{ number_format($saldoTersedia, 0, ',', '.') }} | Min penarikan: Rp 20.000</small>
                    @error('amount')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary fw-bold w-100 py-2"
                        {{ $saldoTersedia < 20000 ? 'disabled' : '' }}
                        onclick="return confirm('Yakin ingin mengajukan penarikan dana?')">
                    <i class="bi bi-send me-1"></i> Ajukan Penarikan
                </button>
            </form>
        </div>
    </div>

    {{-- RIWAYAT PENARIKAN --}}
    <div class="col-lg-7">
        <div class="card-box p-4 border h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Pengajuan Penarikan</h6>

            @if($withdrawals->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-0">Belum ada pengajuan penarikan dana sebelumnya.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($withdrawals as $w)
                        <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between gap-3 bg-light-subtle">
                            <div class="overflow-hidden">
                                <div class="fw-bold text-dark small mb-1">{{ $w->bank_name }} - {{ $w->bank_account_number }}</div>
                                <div class="text-muted" style="font-size: 11px;">
                                    a/n {{ $w->bank_account_name }} &bull; {{ $w->created_at->translatedFormat('d M Y, H:i') }}
                                </div>
                                @if($w->notes)
                                    <div class="text-muted small mt-1">Catatan: {{ $w->notes }}</div>
                                @endif
                            </div>
                            <div class="text-end flex-shrink-0">
                                <h6 class="fw-bold text-dark mb-1">Rp {{ number_format($w->amount, 0, ',', '.') }}</h6>
                                @if($w->status === 'completed')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Berhasil</span>
                                @elseif($w->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning"><i class="bi bi-clock me-1"></i>Diproses</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i>Gagal/Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

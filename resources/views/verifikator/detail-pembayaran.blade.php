@extends('layouts.verifikator')

@section('title', 'Pemeriksaan Pembayaran')
@section('header_title', 'Pemeriksaan Pembayaran')
@section('header_subtitle', 'Validasi bukti resi transfer dan kesesuaian nominal tagihan.')

@section('header_right')
<a href="{{ route('verifikator.pembayaran') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Resi Gambar -->
    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="font-extrabold text-slate-900 text-base font-display mb-4 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-sky"></i> Bukti Resi Transfer
            </h3>
            <div class="bg-slate-100 border border-slate-200 rounded-xl overflow-hidden min-h-[260px] flex items-center justify-center p-2">
                @if($payment->payment_proof)
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" class="max-h-[380px] w-auto object-contain rounded-lg shadow-sm">
                @else
                    <p class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-image-slash mr-1"></i> Bukti transfer tidak diunggah.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Rincian Data Tagihan -->
    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
        <div>
            <h3 class="font-extrabold text-slate-900 text-base font-display mb-4 pb-2 border-b border-slate-100">Detail Transaksi Membership</h3>
            
            <div class="space-y-3.5 text-xs">
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <span class="text-slate-400 font-bold block uppercase text-[10px]">Nama Pemohon / Pengirim</span>
                    <strong class="text-slate-800 text-sm block mt-0.5">{{ $payment->user->name ?? '-' }}</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <span class="text-slate-400 font-bold block uppercase text-[10px]">Paket Membership</span>
                    <strong class="text-sky-600 text-sm block mt-0.5">{{ $payment->membership->name ?? '-' }}</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <span class="text-slate-400 font-bold block uppercase text-[10px]">Nominal Tagihan Harus Transfer</span>
                    <strong class="text-emerald-600 text-base block font-display mt-0.5">Rp {{ number_format($payment->payment_amount ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <span class="text-slate-400 font-bold block uppercase text-[10px]">Metode Pembayaran</span>
                    <strong class="text-slate-800 text-xs block mt-0.5">{{ $payment->payment_method ?? 'Transfer Bank' }}</strong>
                </div>
            </div>
        </div>

        @if($payment->status === 'pending')
        <div class="pt-4 border-t border-slate-100 space-y-3">
            <form id="approvePayForm" action="{{ route('verifikator.pembayaran.approve', $payment->id_identity_verification) }}" method="POST">
                @csrf
                <button type="button" onclick="confirmApprovePay()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check"></i> ✅ Konfirmasi Lunas (Approve)
                </button>
            </form>

            <form id="rejectPayForm" action="{{ route('verifikator.pembayaran.reject', $payment->id_identity_verification) }}" method="POST" class="space-y-3">
                @csrf
                <textarea id="payRejectionNote" name="rejection_note" required placeholder="Tuliskan catatan alasan penolakan (misal: nominal transfer kurang)..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-red-400 bg-slate-50 min-h-[70px]"></textarea>
                
                <button type="button" onclick="confirmRejectPay()" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i> ✕ Bukti Palsu / Tolak Transaksi
                </button>
            </form>
        </div>
        @else
        <div class="bg-slate-100 text-slate-700 border border-slate-200 rounded-xl p-3.5 font-bold text-xs text-center">
            Status Transaksi: {{ strtoupper($payment->status) }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmApprovePay() {
        Swal.fire({
            title: 'Konfirmasi Lunas?',
            text: "Status pembayaran akan diubah menjadi lunas dan membership pengguna diaktifkan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) document.getElementById('approvePayForm').submit(); });
    }

    function confirmRejectPay() {
        const note = document.getElementById('payRejectionNote').value;
        if (!note.trim()) {
            Swal.fire({ icon: 'warning', title: 'Catatan Wajib Diisi', text: 'Masukkan alasan penolakan pembayaran.', confirmButtonColor: '#0EA5E9' });
            return;
        }
        Swal.fire({
            title: 'Tolak Pembayaran?',
            text: "Notifikasi penolakan transaksi akan dikirimkan ke pengguna.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) document.getElementById('rejectPayForm').submit(); });
    }
</script>
@endpush
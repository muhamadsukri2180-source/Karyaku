@extends('layouts.pembeli')
@section('title', 'Laporkan Pelanggaran')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Laporkan Pelanggaran</h4>
    <p class="text-muted mb-0" style="font-size:13px;">Laporkan produk atau pengguna yang melanggar aturan Karyaku. Laporan kamu akan ditinjau oleh admin.</p>
</div>

<div class="card-box p-4" style="max-width:640px;">
    <form action="{{ route('reports.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label small fw-semibold">Apa yang ingin kamu laporkan?</label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check">
                    <input class="form-check-input target-type" type="radio" name="target_type" id="tProduk" value="produk" {{ old('target_type', 'produk') == 'produk' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="tProduk">Produk Tertentu</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input target-type" type="radio" name="target_type" id="tUser" value="pengguna" {{ old('target_type') == 'pengguna' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="tUser">Pengguna / Penjual</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input target-type" type="radio" name="target_type" id="tLain" value="lainnya" {{ old('target_type') == 'lainnya' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="tLain">Lainnya</label>
                </div>
            </div>
        </div>

        <div class="mb-3" id="groupProduk">
            <label class="form-label small fw-semibold">Pilih Produk</label>
            <select name="product_id" class="form-select">
                <option value="">-- Pilih Produk --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id_product }}" {{ old('product_id') == $product->id_product ? 'selected' : '' }}>
                        {{ $product->title }} ({{ $product->seller->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 d-none" id="groupUser">
            <label class="form-label small fw-semibold">Pilih Pengguna</label>
            <select name="reported_user_id" class="form-select">
                <option value="">-- Pilih Pengguna --</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id_user }}" {{ old('reported_user_id') == $u->id_user ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->role->role_name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Alasan Laporan</label>
            <select name="reason" class="form-select" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="Konten tidak sesuai / palsu">Konten tidak sesuai / palsu</option>
                <option value="Penipuan / tidak mengirim pesanan">Penipuan / tidak mengirim pesanan</option>
                <option value="Pelanggaran hak cipta">Pelanggaran hak cipta</option>
                <option value="Perilaku tidak sopan">Perilaku tidak sopan</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">Keterangan Tambahan</label>
            <textarea name="description" rows="4" class="form-control" placeholder="Jelaskan detail kejadian...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-send-fill"></i> Kirim Laporan</button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary px-4">Riwayat Laporan Saya</a>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const groupProduk = document.getElementById('groupProduk');
    const groupUser = document.getElementById('groupUser');

    function syncTargetType() {
        const selected = document.querySelector('.target-type:checked')?.value;
        groupProduk.classList.toggle('d-none', selected !== 'produk');
        groupUser.classList.toggle('d-none', selected !== 'pengguna');
    }

    document.querySelectorAll('.target-type').forEach(el => el.addEventListener('change', syncTargetType));
    syncTargetType();

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2500,
            background: '#ffffff',
            customClass: {
                popup: 'animated bounceIn'
            }
        });
    @endif
</script>
@endpush

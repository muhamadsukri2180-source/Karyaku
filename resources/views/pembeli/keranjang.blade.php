@extends('layouts.pembeli')
@section('title', 'Keranjang')

@section('content')

<h4 class="fw-bold mb-4">Keranjang Belanja</h4>

@if ($items->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
        Keranjang kamu masih kosong.
        <div class="mt-3"><a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm">Mulai Belanja</a></div>
    </div>
@else
<form action="{{ route('pembeli.checkout') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="card-box p-3 mb-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th style="width:110px;">Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id_cart }}" class="form-check-input cart-check" data-price="{{ $item->product->price ?? 0 }}" data-qty="{{ $item->quantity }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $item->product && $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://ui-avatars.com/api/?background=dbeafe&color=1e3a8a&name=' . urlencode($item->product->title ?? 'Produk') }}" style="width:50px;height:50px;border-radius:10px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold small">{{ $item->product->title ?? 'Produk telah dihapus' }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $item->product->seller->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">Rp{{ number_format($item->product->price ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('pembeli.keranjang.update', $item->id_cart) }}" method="POST" class="d-flex align-items-center gap-1">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width:60px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Perbarui"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                        </td>
                        <td class="fw-bold small" style="color:var(--coral);">Rp{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            <button type="submit" form="deleteCart{{ $item->id_cart }}" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-box p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="text-muted small">Total ({{ $items->count() }} item)</div>
            <div class="fw-bold" style="font-size:22px; color:var(--coral);" id="cartTotal">Rp0</div>
        </div>
        <button type="submit" class="btn btn-primary fw-semibold px-4 py-2"><i class="bi bi-bag-check-fill"></i> Checkout Item Terpilih</button>
    </div>
</form>

@foreach ($items as $item)
<form id="deleteCart{{ $item->id_cart }}" action="{{ route('pembeli.keranjang.destroy', $item->id_cart) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script>
    const checkAll = document.getElementById('checkAll');
    const cartChecks = document.querySelectorAll('.cart-check');
    const cartTotalEl = document.getElementById('cartTotal');

    function formatRupiah(num) {
        return 'Rp' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function recalcTotal() {
        let total = 0;
        cartChecks.forEach(cb => {
            if (cb.checked) total += parseFloat(cb.dataset.price) * parseInt(cb.dataset.qty);
        });
        cartTotalEl.textContent = formatRupiah(total);
    }

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            cartChecks.forEach(cb => cb.checked = checkAll.checked);
            recalcTotal();
        });
    }
    cartChecks.forEach(cb => cb.addEventListener('change', recalcTotal));

    document.getElementById('checkoutForm').addEventListener('submit', (e) => {
        const anyChecked = Array.from(cartChecks).some(cb => cb.checked);
        if (!anyChecked) {
            e.preventDefault();
            alert('Pilih minimal 1 produk untuk checkout.');
        }
    });

    recalcTotal();
</script>
@endif

@endsection

@extends('layouts.pembeli')
@section('title', 'Keranjang Saya')

@section('content')

<div class="mb-4 d-flex align-items-center justify-content-between">
    <div>
        <h4 class="fw-bold mb-1">Keranjang Belanja</h4>
        <p class="text-muted mb-0 small">Pilih item yang ingin Anda beli dan lanjutkan ke proses checkout.</p>
    </div>
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-semibold">
        <i class="bi bi-shop me-1"></i> Lanjut Belanja
    </a>
</div>

@if ($items->isEmpty())
    <div class="card-box p-5 text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width:70px;height:70px;">
            <i class="bi bi-cart-x fs-2"></i>
        </div>
        <h5 class="fw-bold">Keranjang Anda Kosong</h5>
        <p class="text-muted small mb-4">Belum ada produk yang ditambahkan ke keranjang belanja Anda.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-3">
            <i class="bi bi-bag-plus me-1"></i> Cari Produk Sekarang
        </a>
    </div>
@else
    <form action="{{ route('pembeli.checkout') }}" method="POST" id="checkoutForm">
        @csrf
        
        <div class="row g-4">
            {{-- DAFTAR ITEM KERANJANG --}}
            <div class="col-lg-8">
                <div class="card-box p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <div class="form-check mb-0">
                            <input type="checkbox" id="selectAll" class="form-check-input" checked>
                            <label for="selectAll" class="form-check-label fw-bold small">Pilih Semua ({{ $items->count() }} Item)</label>
                        </div>
                        <span class="text-muted small">Harga Satuan</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach ($items as $item)
                            @php
                                $product = $item->product;
                            @endphp
                            @if ($product)
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-white" data-cart-item>
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->id_cart }}" 
                                           class="form-check-input flex-shrink-0 item-checkbox"
                                           data-price="{{ $product->price }}"
                                           data-qty="{{ $item->quantity }}"
                                           checked>

                                    <img src="{{ $product->image_url ?? asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->title }}" 
                                         class="rounded-3 object-fit-cover flex-shrink-0"
                                         style="width: 75px; height: 75px;"
                                         onerror="this.src='https://placehold.co/100x100?text=Produk'">

                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold mb-1 text-truncate" style="font-size: 14px;">
                                            <a href="{{ route('pembeli.produk.detail', $product->id_product) }}" class="text-dark">{{ $product->title }}</a>
                                        </h6>
                                        <div class="text-muted small mb-1">
                                            Penjual: <strong>{{ $product->seller->name ?? 'Penjual' }}</strong>
                                        </div>
                                        <div class="fw-bold text-primary" style="font-size: 14px;">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        {{-- FORM UPDATE JUMLAH --}}
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <button type="button" class="btn btn-outline-secondary btn-qty-minus" data-id="{{ $item->id_cart }}">-</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                                   class="form-control text-center input-qty" 
                                                   data-id="{{ $item->id_cart }}" readonly>
                                            <button type="button" class="btn btn-outline-secondary btn-qty-plus" data-id="{{ $item->id_cart }}">+</button>
                                        </div>

                                        {{-- FORM HAPUS ITEM --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-cart" 
                                                data-url="{{ route('pembeli.keranjang.destroy', $item->id_cart) }}" 
                                                title="Hapus Item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RINGKASAN BELANJA --}}
            <div class="col-lg-4">
                <div class="card-box p-4 position-sticky" style="top: 90px;">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Belanja</h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Item Dipilih</span>
                        <span class="fw-bold small" id="selectedCount">0 Item</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Total Harga</span>
                        <strong class="text-primary fs-5" id="totalPriceDisplay">Rp 0</strong>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm" id="btnCheckout">
                        <i class="bi bi-credit-card me-1"></i> Checkout Sekarang
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- HIDDEN FORMS FOR DYNAMIC ACTIONS --}}
    <form id="updateQtyForm" method="POST" action="" style="display:none;">
        @csrf
        @method('PUT')
        <input type="hidden" name="quantity" id="updateQtyVal">
    </form>

    <form id="deleteCartForm" method="POST" action="" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        const totalPriceDisplay = document.getElementById('totalPriceDisplay');
        const btnCheckout = document.getElementById('btnCheckout');

        function calculateTotal() {
            let total = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const price = parseFloat(cb.dataset.price) || 0;
                    const qty = parseInt(cb.dataset.qty) || 1;
                    total += price * qty;
                    count++;
                }
            });

            if (selectedCount) selectedCount.innerText = count + ' Item';
            if (totalPriceDisplay) totalPriceDisplay.innerText = 'Rp ' + total.toLocaleString('id-ID');
            if (btnCheckout) btnCheckout.disabled = (count === 0);
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                calculateTotal();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!cb.checked && selectAll) selectAll.checked = false;
                calculateTotal();
            });
        });

        // Quantity Buttons Action
        document.querySelectorAll('.btn-qty-minus').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = btn.dataset.id;
                const input = document.querySelector(`.input-qty[data-id="${id}"]`);
                let val = parseInt(input.value) || 1;
                if (val > 1) {
                    val--;
                    submitQtyUpdate(id, val);
                }
            });
        });

        document.querySelectorAll('.btn-qty-plus').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = btn.dataset.id;
                const input = document.querySelector(`.input-qty[data-id="${id}"]`);
                let val = parseInt(input.value) || 1;
                val++;
                submitQtyUpdate(id, val);
            });
        });

        function submitQtyUpdate(id, qty) {
            const form = document.getElementById('updateQtyForm');
            form.action = `{{ url('pembeli/keranjang') }}/${id}`;
            document.getElementById('updateQtyVal').value = qty;
            form.submit();
        }

        // Delete Cart Action
        document.querySelectorAll('.btn-delete-cart').forEach(btn => {
            btn.addEventListener('click', function () {
                if (confirm('Yakin ingin menghapus item ini dari keranjang?')) {
                    const form = document.getElementById('deleteCartForm');
                    form.action = btn.dataset.url;
                    form.submit();
                }
            });
        });

        calculateTotal();
    });
</script>
@endpush

@endsection

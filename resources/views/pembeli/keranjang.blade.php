@extends('layouts.pembeli')

@section('title', 'Keranjang Belanja - Karyaku')

@push('styles')
<style>
    .cart-item-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        transition: var(--transition);
    }
    .cart-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-sm);
    }
    .cart-thumb {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        background: #f1f5f9;
        border: 1px solid var(--border-color);
    }
    .cart-summary-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 90px;
    }
    .qty-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: var(--transition);
        cursor: pointer;
    }
    .qty-btn:hover {
        background: var(--primary-light);
        color: var(--primary);
    }
</style>
@endpush

@section('content')

<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h4 class="fw-extrabold text-dark mb-1">
            <i class="bi bi-cart-fill text-primary me-2"></i>Keranjang Belanja
        </h4>
        <p class="text-muted small mb-0">Kelola dan pilih produk yang ingin Anda checkout sekarang.</p>
    </div>
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-3">
        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Lain
    </a>
</div>

@if($items->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-cart-x fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Keranjang Belanja Masih Kosong</h5>
        <p class="small text-muted mb-4">Anda belum menambahkan karya digital atau jasa apapun ke keranjang.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-semibold rounded-3 shadow-sm">
            <i class="bi bi-shop me-1"></i> Mulai Jelajahi Marketplace
        </a>
    </div>
@else
    <form id="checkoutForm" action="{{ route('pembeli.checkout') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- KOLOM KIRI: DAFTAR ITEM KERANJANG --}}
            <div class="col-lg-8">
                {{-- Header Pilih Semua --}}
                <div class="card-box p-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="form-check d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox" checked>
                        <label class="form-check-label fw-bold small text-dark cursor-pointer" for="selectAllCheckbox">
                            Pilih Semua ({{ $items->count() }} Item)
                        </label>
                    </div>
                    <span class="text-muted small">Centang item yang akan dibeli</span>
                </div>

                {{-- List Item --}}
                @foreach ($items as $item)
                    @php
                        $prod = $item->product;
                        $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=200&name=' . urlencode($prod->title ?? 'Produk'));
                    @endphp
                    <div class="cart-item-card" data-price="{{ $prod->price ?? 0 }}" data-qty="{{ $item->quantity }}">
                        <div class="d-flex align-items-center gap-3">
                            <input class="form-check-input item-checkbox" type="checkbox" name="cart_ids[]" value="{{ $item->id_cart }}" checked>
                            
                            <img src="{{ $thumb }}" alt="{{ $prod->title ?? 'Produk' }}" class="cart-thumb flex-shrink-0" onerror="this.src='https://placehold.co/100x100?text=Karyaku'">
                            
                            <div class="flex-grow-1 overflow-hidden">
                                <span class="badge bg-primary-subtle text-primary mb-1 fw-bold" style="font-size: 10px;">
                                    {{ $prod->category->name ?? 'Aset Digital' }}
                                </span>
                                <h6 class="fw-bold text-dark text-truncate mb-1" style="font-size: 14px;">
                                    <a href="{{ $prod ? route('pembeli.produk.detail', $prod->id_product) : '#' }}" class="text-dark text-decoration-none">
                                        {{ $prod->title ?? 'Produk tidak tersedia' }}
                                    </a>
                                </h6>
                                <div class="text-muted small mb-2" style="font-size: 11px;">
                                    <i class="bi bi-person me-1"></i> {{ $prod->seller->name ?? 'Kreator Karyaku' }}
                                </div>
                                <div class="fw-extrabold text-primary" style="font-size: 15px;">
                                    Rp {{ number_format($prod->price ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-end justify-content-between gap-2">
                                <button type="button" class="btn btn-outline-danger btn-sm border-0" title="Hapus dari Keranjang" onclick="document.getElementById('deleteForm{{ $item->id_cart }}').submit()">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                
                                <span class="badge bg-light text-secondary border px-2.5 py-1.5 fw-bold" style="font-size: 12px;">
                                    Qty: {{ $item->quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KOLOM KANAN: RINGKASAN BELANJA (STICKY) --}}
            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-2">Ringkasan Belanja</h5>

                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Total Item Dipilih:</span>
                        <strong class="text-dark" id="summaryCount">0 Item</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Biaya Platform:</span>
                        <strong class="text-success">GRATIS</strong>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-dark">Total Pembayaran:</span>
                        <span class="h4 fw-extrabold text-primary mb-0" id="summaryTotal">Rp 0</span>
                    </div>

                    <button type="submit" id="btnCheckout" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" style="font-size: 14px;">
                        <i class="bi bi-shield-check me-1"></i> Checkout Sekarang
                    </button>

                    <p class="text-muted text-center small mt-3 mb-0" style="font-size: 11px;">
                        <i class="bi bi-lock-fill me-1"></i> Transaksi terenkripsi dan 100% aman
                    </p>
                </div>
            </div>
        </div>
    </form>

    {{-- Form Delete Tersembunyi --}}
    @foreach ($items as $item)
        <form id="deleteForm{{ $item->id_cart }}" action="{{ route('pembeli.keranjang.destroy', $item->id_cart) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

@endsection

@push('scripts')
<script>
    (function() {
        const selectAll = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const summaryCount = document.getElementById('summaryCount');
        const summaryTotal = document.getElementById('summaryTotal');
        const btnCheckout = document.getElementById('btnCheckout');

        function calculateCart() {
            let total = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const card = cb.closest('.cart-item-card');
                    const price = parseFloat(card.getAttribute('data-price')) || 0;
                    const qty = parseInt(card.getAttribute('data-qty'), 10) || 1;
                    total += price * qty;
                    count += qty;
                }
            });

            if (summaryCount) summaryCount.textContent = count + ' Item';
            if (summaryTotal) summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            if (btnCheckout) {
                btnCheckout.disabled = count === 0;
                btnCheckout.textContent = count === 0 ? 'Pilih Minimal 1 Item' : 'Checkout (' + count + ' Item)';
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                calculateCart();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                if (selectAll) selectAll.checked = allChecked;
                calculateCart();
            });
        });

        calculateCart();
    })();
</script>
@endpush
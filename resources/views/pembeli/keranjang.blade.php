@extends('layouts.pembeli')

@section('title', 'Keranjang Belanja - Karyaku')

@push('styles')
<style>
    .cart-container-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .cart-header-bar {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        padding: 16px 24px;
    }
    .cart-item-row {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    .cart-item-row:last-child {
        border-bottom: none;
    }
    .cart-item-row:hover {
        background: #fafafa;
    }
    .cart-thumb {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: cover;
        background: #f1f5f9;
        border: 1px solid var(--border-color);
    }
    .cart-footer-summary {
        background: #f8fafc;
        border-top: 1px solid var(--border-color);
        padding: 24px;
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
    <a href="{{ route('pembeli.marketplace') }}" class="btn btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-soft);">
        Tambah Produk Lain
    </a>
</div>

@if($items->isEmpty())
    <div class="cart-container-box p-5 text-center text-muted">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-cart-x fs-1"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Keranjang Belanja Masih Kosong</h5>
        <p class="small text-muted mb-4">Anda belum menambahkan karya digital atau jasa apapun ke keranjang.</p>
        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-shop"></i> Mulai Jelajahi Marketplace
        </a>
    </div>
@else
    <form id="checkoutForm" action="{{ route('pembeli.checkout') }}" method="POST">
        @csrf

        <div class="cart-container-box mb-4">
            {{-- Header Pilih Semua --}}
            <div class="cart-header-bar d-flex align-items-center justify-content-between">
                <div class="form-check d-flex align-items-center gap-2 mb-0">
                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox" checked style="cursor: pointer;">
                    <label class="form-check-label fw-bold small text-dark cursor-pointer" for="selectAllCheckbox" style="cursor: pointer;">
                        Pilih Semua ({{ $items->count() }} Item)
                    </label>
                </div>
                <span class="text-muted" style="font-size: 11px;">Centang item yang akan dibeli</span>
            </div>

            {{-- List Item --}}
            <div class="d-flex flex-column">
                @foreach ($items as $item)
                    @php
                        $prod = $item->product;
                        $thumb = $prod && $prod->thumbnail ? asset('storage/' . $prod->thumbnail) : ($prod->image_url ?? 'https://ui-avatars.com/api/?background=eff6ff&color=2563eb&size=200&name=' . urlencode($prod->title ?? 'Produk'));
                    @endphp
                    <div class="cart-item-row" data-price="{{ $prod->price ?? 0 }}" data-qty="{{ $item->quantity }}">
                        <div class="d-flex align-items-center gap-3">
                            <input class="form-check-input item-checkbox flex-shrink-0" type="checkbox" name="cart_ids[]" value="{{ $item->id_cart }}" checked style="cursor: pointer;">
                            
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

                            <div class="d-flex flex-column align-items-end justify-content-between gap-3 flex-shrink-0">
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1" title="Hapus dari Keranjang" onclick="document.getElementById('deleteForm{{ $item->id_cart }}').submit()">
                                    <i class="bi bi-trash3-fill fs-6"></i>
                                </button>
                                
                                <span class="badge bg-light text-secondary border px-2.5 py-1.5 fw-bold" style="font-size: 11px;">
                                    Qty: {{ $item->quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Ringkasan Belanja di Bagian Bawah Kontainer --}}
            <div class="cart-footer-summary">
                <div class="row align-items-center justify-content-between g-3">
                    <div class="col-md-7">
                        <div class="d-flex flex-column gap-1 text-muted small">
                            <div class="d-flex align-items-center gap-3">
                                <span>Total Item Dipilih: <strong class="text-dark" id="summaryCount">0 Item</strong></span>
                                <span>&bull;</span>
                                <span>Biaya Platform: <strong class="text-success">GRATIS</strong></span>
                            </div>
                            <span style="font-size: 11px;"><i class="bi bi-lock-fill me-1 text-secondary"></i> Transaksi terenkripsi dan 100% aman</span>
                        </div>
                    </div>
                    
                    <div class="col-md-5 text-md-end">
                        <div class="d-flex align-items-center justify-content-md-end gap-3 mb-3">
                            <span class="fw-bold text-dark">Total Pembayaran:</span>
                            <span class="h4 fw-extrabold text-primary mb-0" id="summaryTotal">Rp 0</span>
                        </div>
                        <button type="submit" id="btnCheckout" class="btn btn-primary px-5 py-2.5 fw-bold rounded-3 shadow-sm" style="font-size: 14px;">
                            <i class="bi bi-shield-check me-1"></i> Checkout Sekarang
                        </button>
                    </div>
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
                    const row = cb.closest('.cart-item-row');
                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    const qty = parseInt(row.getAttribute('data-qty'), 10) || 1;
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
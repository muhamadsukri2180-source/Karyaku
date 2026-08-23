@extends('layouts.pembeli')

@section('title', 'Keranjang Saya')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e3a8a;
        --primary-darker: #14225c;
        --primary-light: #eff6ff;
        --primary-soft: #dbeafe;

        --coral: #ff7a59;
        --coral-dark: #f0623f;

        --white: #ffffff;

        --text-dark: #1e293b;
        --text-muted: #64748b;

        --border: #e5edff;

        --shadow: 0 8px 24px rgba(37, 99, 235, .08);
        --shadow-hover: 0 14px 30px rgba(37, 99, 235, .14);
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--primary-light);
        color: var(--text-dark);
    }

    a {
        text-decoration: none;
    }

    /* =========================
       BACKGROUND
    ========================= */

    .cart-bg-decor {
        position: fixed;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    .cart-bg-decor span {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(
            circle at 30% 30%,
            var(--primary-soft),
            transparent 70%
        );
        opacity: .5;
    }

    .cart-bg-decor span:nth-child(1) {
        width: 350px;
        height: 350px;
        right: -120px;
        top: -120px;
    }

    .cart-bg-decor span:nth-child(2) {
        width: 260px;
        height: 260px;
        left: -100px;
        bottom: -100px;
    }

    /* =========================
       PAGE
    ========================= */

    .cart-page-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px 60px;
    }

    /* =========================
       HEADER
    ========================= */

    .cart-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
    }

    .cart-page-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
    }

    .cart-page-header p {
        margin: 5px 0 0;
        color: var(--text-muted);
        font-size: 12px;
    }

    .back-market {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        transition: .2s;
    }

    .back-market:hover {
        background: var(--primary);
        color: #fff;
    }

    /* =========================
       CART LAYOUT
    ========================= */

    .cart-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        gap: 20px;
        align-items: start;
    }

    /* =========================
       CART BOX
    ========================= */

    .cart-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 17px 19px;
        border-bottom: 1px solid var(--border);
    }

    .cart-header h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .cart-header h5 i {
        color: var(--primary);
    }

    .select-all {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--text-muted);
        font-size: 11px;
        cursor: pointer;
    }

    .select-all input {
        accent-color: var(--primary);
        cursor: pointer;
    }

    /* =========================
       CART ITEM
    ========================= */

    .cart-item {
        display: grid;
        grid-template-columns:
            22px
            86px
            minmax(0, 1fr)
            auto
            34px;

        gap: 13px;
        align-items: center;

        padding: 15px 19px;

        border-bottom: 1px solid var(--border);

        transition: .2s;
    }

    .cart-item:last-child {
        border-bottom: 0;
    }

    .cart-item:hover {
        background: #fafcff;
    }

    .cart-check {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .cart-image {
        width: 86px;
        height: 74px;
        border-radius: 10px;
        overflow: hidden;
        background: var(--primary-light);
    }

    .cart-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-info {
        min-width: 0;
    }

    .cart-info h6 {
        margin: 0 0 5px;
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1.4;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cart-info h6 a {
        color: var(--text-dark);
    }

    .cart-info h6 a:hover {
        color: var(--primary);
    }

    .seller {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted);
        font-size: 10px;
    }

    .seller i {
        color: var(--primary);
    }

    .cart-price {
        margin-top: 7px;
        color: var(--coral);
        font-size: 13px;
        font-weight: 800;
    }

    /* =========================
       QUANTITY
    ========================= */

    .qty-form {
        display: flex;
        align-items: center;

        border: 1px solid var(--border);
        border-radius: 8px;

        overflow: hidden;
        background: #fff;
    }

    .qty-form button {
        width: 29px;
        height: 30px;

        border: 0;
        background: #f8faff;

        color: var(--primary);

        font-weight: 700;
        cursor: pointer;

        transition: .2s;
    }

    .qty-form button:hover:not(:disabled) {
        background: var(--primary-soft);
    }

    .qty-form button:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    .qty-form span {
        min-width: 31px;
        text-align: center;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================
       DELETE
    ========================= */

    .delete-btn {
        width: 32px;
        height: 32px;

        border: 0;
        border-radius: 8px;

        background: #fef2f2;
        color: #ef4444;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;
        transition: .2s;
    }

    .delete-btn:hover {
        background: #fee2e2;
        transform: scale(1.05);
    }

    /* =========================
       EMPTY CART
    ========================= */

    .empty-cart {
        text-align: center;
        padding: 70px 25px;
    }

    .empty-cart > i {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 75px;
        height: 75px;

        margin: 0 auto 16px;

        border-radius: 50%;

        background: var(--primary-light);
        color: var(--primary);

        font-size: 32px;
    }

    .empty-cart h4 {
        margin: 0 0 7px;
        font-size: 18px;
        font-weight: 700;
    }

    .empty-cart p {
        max-width: 430px;
        margin: 0 auto 18px;

        color: var(--text-muted);
        font-size: 11.5px;
    }

    .btn-marketplace {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 10px 16px;

        background: var(--primary);
        color: #fff;

        border-radius: 9px;

        font-size: 11.5px;
        font-weight: 700;
    }

    .btn-marketplace:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    /* =========================
       SUMMARY
    ========================= */

    .summary-box {
        background: #fff;

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);

        padding: 19px;

        position: sticky;
        top: 90px;
    }

    .summary-box h5 {
        margin: 0 0 17px;

        font-size: 14px;
        font-weight: 700;
    }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 11px;

        color: var(--text-muted);
        font-size: 11.5px;
    }

    .summary-row strong {
        color: var(--text-dark);
    }

    .summary-divider {
        border: 0;
        border-top: 1px solid var(--border);
        margin: 14px 0;
    }

    .summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 16px;
    }

    .summary-total span {
        font-size: 12px;
        font-weight: 600;
    }

    .summary-total strong {
        color: var(--coral);
        font-size: 18px;
        font-weight: 800;
    }

    /* =========================
       CHECKOUT
    ========================= */

    .checkout-btn {
        width: 100%;

        border: 0;

        padding: 12px;

        border-radius: 10px;

        background: var(--primary);
        color: #fff;

        font-family: 'Poppins', sans-serif;

        font-size: 12px;
        font-weight: 700;

        cursor: pointer;

        transition: .2s;
    }

    .checkout-btn:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .checkout-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    /* =========================
       PROMO
    ========================= */

    .promo-box {
        margin-top: 15px;

        padding: 14px;

        background: #fff;

        border: 1px solid var(--border);
        border-radius: 13px;

        box-shadow: var(--shadow);
    }

    .promo-title {
        display: flex;
        align-items: center;

        gap: 7px;

        margin-bottom: 9px;

        font-size: 11px;
        font-weight: 700;
    }

    .promo-form {
        display: flex;
        gap: 7px;
    }

    .promo-form input {
        min-width: 0;
        flex: 1;

        border: 1px solid var(--border);
        border-radius: 7px;

        padding: 9px;

        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
    }

    .promo-form input:focus {
        border-color: var(--primary);
    }

    .promo-form button {
        border: 0;

        padding: 8px 11px;

        border-radius: 7px;

        background: var(--primary-light);
        color: var(--primary);

        font-size: 10px;
        font-weight: 700;

        cursor: pointer;
    }

    .promo-form button:hover {
        background: var(--primary);
        color: #fff;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {

        .cart-layout {
            grid-template-columns: 1fr;
        }

        .summary-box {
            position: static;
        }
    }

    @media (max-width: 650px) {

        .cart-page-container {
            padding: 22px 12px 45px;
        }

        .cart-page-header {
            align-items: flex-start;
        }

        .cart-page-header h2 {
            font-size: 19px;
        }

        .cart-page-header p {
            font-size: 10px;
        }

        .back-market {
            padding: 8px 9px;
            font-size: 10px;
        }

        .cart-item {
            grid-template-columns:
                20px
                68px
                minmax(0, 1fr)
                32px;

            gap: 8px;
            padding: 13px 12px;
        }

        .cart-image {
            width: 68px;
            height: 62px;
        }

        .cart-info h6 {
            font-size: 11px;
        }

        .seller {
            font-size: 8.5px;
        }

        .cart-price {
            font-size: 11px;
        }

        .qty-form {
            grid-column: 3;
            justify-self: start;
            margin-top: 5px;
        }

        .delete-btn {
            grid-column: 4;
            grid-row: 1;
        }
    }

    @media (max-width: 400px) {

        .cart-page-header {
            flex-direction: column;
        }

        .back-market {
            align-self: flex-start;
        }

        .cart-header {
            padding: 13px;
        }

        .cart-header h5 {
            font-size: 12px;
        }
    }
</style>


{{-- BACKGROUND --}}
<div class="cart-bg-decor">
    <span></span>
    <span></span>
</div>


{{-- ================================
     MAIN
================================ --}}

<main class="cart-page-container">

    {{-- PAGE HEADER --}}
    <div class="cart-page-header">

        <div>

            <h2>
                <i class="bi bi-cart3 text-primary"></i>
                Keranjang Saya
            </h2>

            <p>
                Periksa kembali barang atau jasa yang ingin kamu beli.
            </p>

        </div>

        <a
            href="{{ route('pembeli.marketplace') }}"
            class="back-market"
        >
            <i class="bi bi-shop"></i>
            Lanjut Belanja
        </a>

    </div>


    {{-- ================================
         CART LAYOUT
    ================================= --}}

    <div class="cart-layout">

        {{-- ============================
             LEFT
        ============================= --}}

        <div>

            <div class="cart-box">

                {{-- CART HEADER --}}
                <div class="cart-header">

                    <h5>
                        <i class="bi bi-cart3"></i>
                        Produk di Keranjang
                    </h5>

                    @if($items->count())

                        <label class="select-all">

                            <input
                                type="checkbox"
                                id="selectAll"
                                checked
                            >

                            Pilih Semua

                        </label>

                    @endif

                </div>


                {{-- ============================
                     CART ITEMS
                ============================= --}}

                @if($items->count())

                    @foreach($items as $item)

                        @php
                            $product = $item->product;
                        @endphp

                        @if($product)

                            @php

                                $productName =
                                    $product->title
                                    ?? $product->name
                                    ?? 'Produk Karyaku';

                                $productPrice =
                                    $product->price ?? 0;

                                $quantity =
                                    $item->quantity ?? 1;

                                $cartId =
                                    $item->id_cart;

                                $sellerName =
                                    optional($product->seller)->name
                                    ?? 'Penjual';

                                if (
                                    !empty($product->image_url)
                                ) {

                                    $imageUrl =
                                        $product->image_url;

                                }
                                elseif (
                                    !empty($product->image)
                                ) {

                                    $imageUrl =
                                        asset(
                                            'storage/' .
                                            $product->image
                                        );

                                }
                                else {

                                    $imageUrl =
                                        'https://placehold.co/500x400?text=Produk';

                                }

                            @endphp


                            {{-- ITEM --}}
                            <div
                                class="cart-item"
                                data-cart-item
                            >

                                {{-- CHECKBOX --}}

                                <input
                                    type="checkbox"
                                    class="cart-check item-check"
                                    name="cart_ids[]"
                                    value="{{ $cartId }}"
                                    data-price="{{ $productPrice }}"
                                    data-quantity="{{ $quantity }}"
                                    checked
                                >


                                {{-- IMAGE --}}

                                <div class="cart-image">

                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $productName }}"
                                        onerror="this.src='https://placehold.co/500x400?text=Produk';"
                                    >

                                </div>


                                {{-- INFO --}}

                                <div class="cart-info">

                                    <h6>

                                        <a
                                            href="{{ route(
                                                'pembeli.produk.detail',
                                                $product->id_product
                                            ) }}"
                                        >
                                            {{ $productName }}
                                        </a>

                                    </h6>

                                    <div class="seller">

                                        <i class="bi bi-shop"></i>

                                        Penjual:
                                        {{ $sellerName }}

                                    </div>

                                    <div class="cart-price">

                                        Rp
                                        {{ number_format(
                                            $productPrice,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </div>

                                </div>


                                {{-- QUANTITY --}}

                                <form
                                    action="{{ route(
                                        'pembeli.keranjang.update',
                                        $cartId
                                    ) }}"
                                    method="POST"
                                    class="qty-form"
                                >

                                    @csrf

                                    @method('PUT')


                                    {{-- MINUS --}}

                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ max(
                                            1,
                                            $quantity - 1
                                        ) }}"
                                        {{ $quantity <= 1 ? 'disabled' : '' }}
                                    >
                                        −
                                    </button>


                                    {{-- JUMLAH --}}

                                    <span>
                                        {{ $quantity }}
                                    </span>


                                    {{-- PLUS --}}

                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ $quantity + 1 }}"
                                    >
                                        +
                                    </button>

                                </form>


                                {{-- DELETE --}}

                                <form
                                    action="{{ route(
                                        'pembeli.keranjang.destroy',
                                        $cartId
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus produk ini dari keranjang?'
                                    )"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="delete-btn"
                                        title="Hapus Produk"
                                    >

                                        <i class="bi bi-trash3"></i>

                                    </button>

                                </form>

                            </div>

                        @endif

                    @endforeach


                @else

                    {{-- EMPTY CART --}}

                    <div class="empty-cart">

                        <i class="bi bi-cart-x"></i>

                        <h4>
                            Keranjang Kamu Masih Kosong
                        </h4>

                        <p>
                            Yuk cari barang atau jasa yang kamu
                            butuhkan di marketplace.
                        </p>

                        <a
                            href="{{ route('pembeli.marketplace') }}"
                            class="btn-marketplace"
                        >

                            <i class="bi bi-shop"></i>

                            Belanja Sekarang

                        </a>

                    </div>

                @endif

            </div>


            {{-- ============================
                 PROMO
            ============================= --}}

            @if($items->count())

                <div class="promo-box">

                    <div class="promo-title">

                        <i
                            class="bi bi-tag-fill"
                            style="color:var(--coral);"
                        ></i>

                        Punya kode promo?

                    </div>

                    <div class="promo-form">

                        <input
                            type="text"
                            id="promoCode"
                            placeholder="Masukkan kode promo"
                        >

                        <button
                            type="button"
                            id="btnPromo"
                        >
                            Pakai Kode
                        </button>

                    </div>

                </div>

            @endif

        </div>


        {{-- ============================
             RIGHT SUMMARY
        ============================= --}}

        @if($items->count())

            <div class="summary-box">

                <h5>
                    Ringkasan Belanja
                </h5>


                {{-- SELECTED --}}

                <div class="summary-row">

                    <span>
                        Produk dipilih
                    </span>

                    <strong id="selectedCount">
                        0
                    </strong>

                </div>


                {{-- SUBTOTAL --}}

                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong id="subtotal">
                        Rp0
                    </strong>

                </div>


                {{-- SERVICE FEE --}}

                <div class="summary-row">

                    <span>
                        Biaya layanan
                    </span>

                    <strong id="serviceFee">
                        Rp0
                    </strong>

                </div>


                {{-- DISCOUNT --}}

                <div class="summary-row">

                    <span>
                        Diskon
                    </span>

                    <strong id="discount">
                        Rp0
                    </strong>

                </div>


                <hr class="summary-divider">


                {{-- TOTAL --}}

                <div class="summary-total">

                    <span>
                        Total Pembayaran
                    </span>

                    <strong id="total">
                        Rp0
                    </strong>

                </div>


                {{-- CHECKOUT FORM --}}

                <form
                    action="{{ route('pembeli.checkout') }}"
                    method="POST"
                    id="checkoutForm"
                >

                    @csrf

                    <div id="checkoutInputs"></div>

                    <button
                        type="submit"
                        class="checkout-btn"
                        id="checkoutBtn"
                        disabled
                    >

                        <i class="bi bi-credit-card me-1"></i>

                        Checkout Sekarang

                    </button>

                </form>

            </div>

        @endif

    </div>

</main>


{{-- ================================
     JAVASCRIPT
================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================
       ELEMENT
    ========================================= */

    const checkboxes =
        document.querySelectorAll('.item-check');

    const selectAll =
        document.getElementById('selectAll');

    const selectedCount =
        document.getElementById('selectedCount');

    const subtotalEl =
        document.getElementById('subtotal');

    const serviceFeeEl =
        document.getElementById('serviceFee');

    const discountEl =
        document.getElementById('discount');

    const totalEl =
        document.getElementById('total');

    const checkoutBtn =
        document.getElementById('checkoutBtn');

    const checkoutInputs =
        document.getElementById('checkoutInputs');


    /* =========================================
       FORMAT RUPIAH
    ========================================= */

    function rupiah(number) {

        return 'Rp' +
            Number(number).toLocaleString('id-ID');

    }


    /* =========================================
       HITUNG KERANJANG
    ========================================= */

    function calculateCart() {

        let count = 0;

        let subtotal = 0;


        /*
         * Bersihkan input checkout
         */

        if (checkoutInputs) {

            checkoutInputs.innerHTML = '';

        }


        /*
         * Loop checkbox
         */

        checkboxes.forEach(function (checkbox) {

            if (checkbox.checked) {

                const price =
                    Number(
                        checkbox.dataset.price || 0
                    );

                const quantity =
                    Number(
                        checkbox.dataset.quantity || 1
                    );


                subtotal +=
                    price * quantity;

                count++;


                /*
                 * Masukkan cart_id
                 * ke form checkout
                 */

                if (checkoutInputs) {

                    const input =
                        document.createElement('input');

                    input.type = 'hidden';

                    input.name = 'cart_ids[]';

                    input.value =
                        checkbox.value;

                    checkoutInputs.appendChild(
                        input
                    );

                }

            }

        });


        /*
         * BIAYA LAYANAN
         *
         * Kalau ada produk yang dipilih,
         * biaya layanan = Rp5.000
         */

        const serviceFee =
            count > 0
            ? 5000
            : 0;


        /*
         * DISKON
         */

        let discount = 0;


        /*
         * TOTAL
         */

        const total =
            subtotal +
            serviceFee -
            discount;


        /* =====================================
           UPDATE DISPLAY
        ===================================== */

        if (selectedCount) {

            selectedCount.textContent =
                count + ' Item';

        }


        if (subtotalEl) {

            subtotalEl.textContent =
                rupiah(subtotal);

        }


        if (serviceFeeEl) {

            serviceFeeEl.textContent =
                rupiah(serviceFee);

        }


        if (discountEl) {

            discountEl.textContent =
                discount > 0
                ? '-' + rupiah(discount)
                : 'Rp0';

        }


        if (totalEl) {

            totalEl.textContent =
                rupiah(total);

        }


        /*
         * BUTTON CHECKOUT
         */

        if (checkoutBtn) {

            checkoutBtn.disabled =
                count === 0;

        }

    }


    /* =========================================
       SELECT ALL
    ========================================= */

    if (selectAll) {

        selectAll.addEventListener(
            'change',
            function () {

                checkboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );


                selectAll.indeterminate =
                    false;


                calculateCart();

            }
        );

    }


    /* =========================================
       INDIVIDUAL CHECKBOX
    ========================================= */

    checkboxes.forEach(
        function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    if (selectAll) {

                        const allChecked =
                            [...checkboxes].every(
                                function (cb) {

                                    return cb.checked;

                                }
                            );


                        const anyChecked =
                            [...checkboxes].some(
                                function (cb) {

                                    return cb.checked;

                                }
                            );


                        selectAll.checked =
                            allChecked;


                        selectAll.indeterminate =
                            anyChecked &&
                            !allChecked;

                    }


                    calculateCart();

                }
            );

        }
    );


    /* =========================================
       PROMO
    ========================================= */

    const btnPromo =
        document.getElementById('btnPromo');


    if (btnPromo) {

        btnPromo.addEventListener(
            'click',
            function () {

                const promoInput =
                    document.getElementById(
                        'promoCode'
                    );


                const code =
                    promoInput
                    ? promoInput.value
                        .trim()
                        .toUpperCase()
                    : '';


                if (!code) {

                    alert(
                        'Masukkan kode promo terlebih dahulu.'
                    );

                    return;

                }


                /*
                 * CONTOH PROMO
                 */

                if (code === 'KARYAKU25') {

                    alert(
                        'Kode promo berhasil digunakan!'
                    );

                }
                else {

                    alert(
                        'Kode promo tidak ditemukan.'
                    );

                }

            }
        );

    }


    /* =========================================
       CHECKOUT VALIDATION
    ========================================= */

    const checkoutForm =
        document.getElementById('checkoutForm');


    if (checkoutForm) {

        checkoutForm.addEventListener(
            'submit',
            function (event) {

                const selected =
                    document.querySelectorAll(
                        '.item-check:checked'
                    );


                if (selected.length === 0) {

                    event.preventDefault();

                    alert(
                        'Pilih minimal satu produk untuk checkout.'
                    );

                    return;

                }


                /*
                 * Pastikan input cart_ids
                 * dibuat ulang sebelum submit.
                 */

                if (checkoutInputs) {

                    checkoutInputs.innerHTML = '';


                    selected.forEach(
                        function (checkbox) {

                            const input =
                                document.createElement(
                                    'input'
                                );

                            input.type = 'hidden';

                            input.name =
                                'cart_ids[]';

                            input.value =
                                checkbox.value;

                            checkoutInputs.appendChild(
                                input
                            );

                        }
                    );

                }

            }
        );

    }


    /* =========================================
       INITIAL CALCULATION
    ========================================= */

    calculateCart();

});

</script>

@endsection
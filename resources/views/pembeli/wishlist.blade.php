@extends('layouts.pembeli')

@section('title', 'Wishlist Saya')

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

        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e5edff;

        --radius: 18px;

        --shadow:
            0 8px 24px rgba(37, 99, 235, 0.08);

        --shadow-hover:
            0 16px 34px rgba(37, 99, 235, 0.16);
    }

    /* =====================================================
       BACKGROUND
    ===================================================== */

    .wishlist-page {
        position: relative;
        min-height: calc(100vh - 70px);
        padding-bottom: 50px;
    }

    .wishlist-bg {
        position: fixed;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    .wishlist-bg span {
        position: absolute;
        border-radius: 50%;
        background:
            radial-gradient(
                circle at 30% 30%,
                var(--primary-soft),
                transparent 70%
            );
        opacity: .5;
        animation: floatBlob 14s ease-in-out infinite;
    }

    .wishlist-bg span:nth-child(1) {
        width: 380px;
        height: 380px;
        top: -120px;
        right: -100px;
        animation-duration: 16s;
    }

    .wishlist-bg span:nth-child(2) {
        width: 260px;
        height: 260px;
        bottom: -80px;
        left: -60px;
        animation-duration: 20s;
        animation-delay: 2s;
    }

    @keyframes floatBlob {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(20px, -30px) scale(1.08);
        }
    }

    /* =====================================================
       HEADER
    ===================================================== */

    .wishlist-header-wrap {
        max-width: 1440px;
        margin: 0 auto;
        padding: 30px 28px 0;
    }

    .wishlist-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;

        background:
            linear-gradient(
                120deg,
                var(--primary-darker),
                var(--primary-dark) 60%,
                var(--primary)
            );

        border-radius: var(--radius);
        padding: 28px 32px;
        color: #fff;

        box-shadow: var(--shadow);

        position: relative;
        overflow: hidden;
    }

    .wishlist-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -80px;
        top: -100px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }

    .wishlist-header-content {
        position: relative;
        z-index: 2;
    }

    .wishlist-header h2 {
        font-size: 24px;
        font-weight: 800;
        margin: 0 0 7px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wishlist-header p {
        margin: 0;
        font-size: 13px;
        color: rgba(255,255,255,.8);
    }

    .wishlist-count {
        position: relative;
        z-index: 2;

        background: rgba(255,255,255,.15);

        padding: 12px 22px;
        border-radius: 14px;

        text-align: center;
        min-width: 110px;
    }

    .wishlist-count .number {
        font-size: 23px;
        font-weight: 800;
        line-height: 1;
    }

    .wishlist-count .label {
        font-size: 11px;
        color: rgba(255,255,255,.75);
        margin-top: 5px;
    }

    /* =====================================================
       ACTION
    ===================================================== */

    .wishlist-action {
        max-width: 1440px;
        margin: 22px auto 0;
        padding: 0 28px;

        display: flex;
        justify-content: flex-end;
    }

    .btn-marketplace {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        background: var(--coral);
        color: #fff;

        border: none;
        border-radius: 11px;

        padding: 11px 18px;

        font-size: 13px;
        font-weight: 700;

        transition: all .2s ease;
    }

    .btn-marketplace:hover {
        background: var(--coral-dark);
        color: #fff;
        transform: translateY(-2px);
    }

    /* =====================================================
       PRODUCT GRID
    ===================================================== */

    .wishlist-section {
        max-width: 1440px;
        margin: 24px auto 0;
        padding: 0 28px;
    }

    .product-grid-wishlist {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    @media(max-width:1200px) {
        .product-grid-wishlist {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media(max-width:768px) {
        .product-grid-wishlist {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:480px) {
        .product-grid-wishlist {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .wishlist-section {
            padding: 0 14px;
        }

        .wishlist-header-wrap {
            padding: 18px 14px 0;
        }

        .wishlist-header {
            padding: 22px 20px;
        }

        .wishlist-header h2 {
            font-size: 20px;
        }

        .wishlist-header p {
            font-size: 11px;
        }

        .wishlist-action {
            padding: 0 14px;
        }
    }

    /* =====================================================
       PRODUCT CARD
    ===================================================== */

    .wishlist-product-card {
        background: #fff;

        border-radius: 16px;

        overflow: hidden;

        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);

        transition:
            transform .25s ease,
            box-shadow .25s ease;

        display: flex;
        flex-direction: column;

        position: relative;
    }

    .wishlist-product-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
    }

    /* =====================================================
       PRODUCT IMAGE
    ===================================================== */

    .wishlist-product-thumb {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .wishlist-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;

        transition: transform .5s ease;
    }

    .wishlist-product-card:hover
    .wishlist-product-thumb img {
        transform: scale(1.08);
    }

    .wishlist-category {
        position: absolute;

        top: 10px;
        left: 10px;

        background: rgba(20,34,92,.78);

        color: #fff;

        font-size: 10px;
        font-weight: 700;

        padding: 5px 10px;

        border-radius: 20px;

        z-index: 3;
    }

    /* =====================================================
       REMOVE WISHLIST BUTTON
    ===================================================== */

    .wishlist-remove-top {
        position: absolute;

        top: 8px;
        right: 8px;

        width: 36px;
        height: 36px;

        border-radius: 50%;

        background: rgba(255,255,255,.95);

        border: none;

        display: flex;
        align-items: center;
        justify-content: center;

        color: var(--coral);

        font-size: 16px;

        cursor: pointer;

        z-index: 5;

        transition: all .2s ease;
    }

    .wishlist-remove-top:hover {
        background: var(--coral);
        color: #fff;
        transform: scale(1.08);
    }

    /* =====================================================
       PRODUCT BODY
    ===================================================== */

    .wishlist-product-body {
        padding: 13px;

        display: flex;
        flex-direction: column;

        gap: 6px;

        flex: 1;
    }

    .wishlist-product-title {
        font-size: 13px;
        font-weight: 600;

        line-height: 1.4;

        margin: 0;

        min-height: 36px;
    }

    .wishlist-product-title a {
        color: var(--text-dark);
        text-decoration: none;

        transition: color .2s ease;
    }

    .wishlist-product-title a:hover {
        color: var(--primary);
    }

    .wishlist-price {
        color: var(--coral);

        font-size: 15px;

        font-weight: 800;
    }

    .wishlist-meta {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 8px;

        font-size: 11px;

        color: var(--text-muted);
    }

    .wishlist-meta .sold {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .wishlist-meta .views {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* =====================================================
       SELLER
    ===================================================== */

    .wishlist-seller {
        display: flex;
        align-items: center;

        gap: 7px;

        font-size: 11px;
        color: var(--text-muted);

        min-width: 0;
    }

    .wishlist-seller img {
        width: 22px;
        height: 22px;

        border-radius: 50%;

        object-fit: cover;

        flex-shrink: 0;
    }

    .wishlist-seller span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* =====================================================
       BUTTONS
    ===================================================== */

    .btn-view-product {
        margin-top: 6px;

        width: 100%;

        border: none;

        background: var(--primary-light);

        color: var(--primary);

        font-weight: 700;

        font-size: 12px;

        padding: 9px 0;

        border-radius: 9px;

        text-align: center;

        transition: all .2s ease;
    }

    .btn-view-product:hover {
        background: var(--primary);
        color: #fff;
    }

    .btn-add-cart {
        width: 100%;

        border: none;

        background: var(--primary);

        color: #fff;

        font-weight: 700;

        font-size: 12px;

        padding: 9px 0;

        border-radius: 9px;

        transition: all .2s ease;
    }

    .btn-add-cart:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .btn-delete-wishlist {
        width: 100%;

        border: 1px solid #fecaca;

        background: #fff;

        color: #ef4444;

        font-weight: 700;

        font-size: 12px;

        padding: 9px 0;

        border-radius: 9px;

        transition: all .2s ease;
    }

    .btn-delete-wishlist:hover {
        background: #ef4444;

        border-color: #ef4444;

        color: #fff;
    }

    /* =====================================================
       EMPTY STATE
    ===================================================== */

    .wishlist-empty {
        max-width: 1440px;

        margin: 28px auto 0;

        padding: 0 28px;
    }

    .wishlist-empty-inner {
        background: #fff;

        border-radius: var(--radius);

        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);

        text-align: center;

        padding: 70px 20px;
    }

    .empty-icon {
        width: 108px;
        height: 108px;

        border-radius: 50%;

        background: var(--primary-light);

        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0 auto 20px;

        font-size: 46px;

        color: var(--coral);

        border: 2px dashed var(--primary-soft);
    }

    .wishlist-empty h4 {
        font-size: 19px;
        font-weight: 800;

        margin-bottom: 8px;
    }

    .wishlist-empty p {
        color: var(--text-muted);

        font-size: 13.5px;

        max-width: 400px;

        margin: 0 auto 22px;
    }

    .btn-shop-now {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        background: var(--coral);

        color: #fff;

        padding: 12px 26px;

        border-radius: 12px;

        font-weight: 700;

        font-size: 14px;

        text-decoration: none;

        transition: all .2s ease;
    }

    .btn-shop-now:hover {
        background: var(--coral-dark);
        color: #fff;

        transform: translateY(-2px);
    }

    /* =====================================================
       PAGINATION
    ===================================================== */

    .wishlist-pagination {
        margin-top: 28px;

        display: flex;
        justify-content: center;
    }

    .wishlist-pagination nav {
        display: inline-block;
    }

    .wishlist-pagination .pagination {
        margin-bottom: 0;
    }

    /* =====================================================
       ANIMATION
    ===================================================== */

    .wishlist-reveal {
        animation: fadeUp .5s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>


<div class="wishlist-page">

    {{-- BACKGROUND --}}
    <div class="wishlist-bg">
        <span></span>
        <span></span>
    </div>


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="wishlist-header-wrap">

        <div class="wishlist-header">

            <div class="wishlist-header-content">

                <h2>
                    <i class="bi bi-heart-fill"></i>
                    Wishlist Saya
                </h2>

                <p>
                    Kumpulan karya digital favorit yang kamu simpan untuk dibeli nanti.
                </p>

            </div>


            <div class="wishlist-count">

                <div class="number">
                    {{ $wishlists->total() ?? $wishlists->count() }}
                </div>

                <div class="label">
                    Item Wishlist
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ACTION BUTTON
    ====================================================== --}}

    <div class="wishlist-action">

        <a
            href="{{ route('pembeli.marketplace') }}"
            class="btn-marketplace"
        >

            <i class="bi bi-shop"></i>

            Jelajahi Marketplace

        </a>

    </div>


    {{-- =====================================================
         WISHLIST
    ====================================================== --}}

    @if($wishlists->count() > 0)

        <div class="wishlist-section">

            <div class="product-grid-wishlist">

                @foreach($wishlists as $wish)

                    @php
                        $product = $wish->product;
                    @endphp


                    @if($product)

                        <div
                            class="wishlist-product-card wishlist-reveal"
                            data-id="{{ $product->id_product }}"
                        >

                            {{-- PRODUCT IMAGE --}}

                            <div class="wishlist-product-thumb">

                                {{-- CATEGORY --}}

                                <span class="wishlist-category">

                                    {{ $product->category->name ?? 'Jasa' }}

                                </span>


                                {{-- REMOVE HEART --}}

                                <form
                                    action="{{ route('pembeli.wishlist.toggle', $product->id_product) }}"
                                    method="POST"
                                    class="wishlist-delete-form"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="wishlist-remove-top"
                                        title="Hapus dari Wishlist"
                                    >

                                        <i class="bi bi-heart-fill"></i>

                                    </button>

                                </form>


                                {{-- IMAGE --}}

                                <img
                                    src="{{ $product->image_url ?? asset('storage/' . ($product->image ?? '')) }}"
                                    alt="{{ $product->title }}"
                                    onerror="this.onerror=null; this.src='https://placehold.co/500x350?text=Produk+Karyaku';"
                                >

                            </div>


                            {{-- PRODUCT BODY --}}

                            <div class="wishlist-product-body">

                                {{-- TITLE --}}

                                <h6 class="wishlist-product-title">

                                    <a
                                        href="{{ route('pembeli.produk.detail', $product->id_product) }}"
                                    >

                                        {{ $product->title }}

                                    </a>

                                </h6>


                                {{-- PRICE --}}

                                <div class="wishlist-price">

                                    Rp {{ number_format($product->price, 0, ',', '.') }}

                                </div>


                                {{-- META --}}

                                <div class="wishlist-meta">

                                    <span class="sold">

                                        <i class="bi bi-bag-check"></i>

                                        {{ $product->sold_count ?? 0 }} Terjual

                                    </span>


                                    <span class="views">

                                        <i class="bi bi-eye"></i>

                                        {{ $product->view_count ?? 0 }}

                                    </span>

                                </div>


                                {{-- SELLER --}}

                                <div class="wishlist-seller">

                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Penjual') }}&background=dbeafe&color=1e3a8a"
                                        alt="seller"
                                    >

                                    <span>

                                        {{ $product->seller->name ?? 'Kreator Karyaku' }}

                                    </span>

                                </div>


                                {{-- DETAIL --}}

                                <a
                                    href="{{ route('pembeli.produk.detail', $product->id_product) }}"
                                    class="btn-view-product"
                                >

                                    <i class="bi bi-eye me-1"></i>

                                    Lihat Produk

                                </a>


                                {{-- ADD CART --}}

                                <form
                                    action="{{ route('pembeli.keranjang.store') }}"
                                    method="POST"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="{{ $product->id_product }}"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-add-cart"
                                    >

                                        <i class="bi bi-cart-plus-fill me-1"></i>

                                        Tambah ke Keranjang

                                    </button>

                                </form>


                                {{-- DELETE --}}

                                <form
                                    action="{{ route('pembeli.wishlist.toggle', $product->id_product) }}"
                                    method="POST"
                                    class="wishlist-delete-form"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn-delete-wishlist"
                                    >

                                        <i class="bi bi-trash3 me-1"></i>

                                        Hapus dari Wishlist

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            @if(method_exists($wishlists, 'links'))

                <div class="wishlist-pagination">

                    {{ $wishlists->links() }}

                </div>

            @endif

        </div>

    @else

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}

        <div class="wishlist-empty">

            <div class="wishlist-empty-inner">

                <div class="empty-icon">

                    <i class="bi bi-heart"></i>

                </div>


                <h4>
                    Wishlist Kamu Masih Kosong
                </h4>


                <p>

                    Belum ada karya digital yang kamu simpan.
                    Yuk jelajahi Marketplace dan temukan produk favoritmu!

                </p>


                <a
                    href="{{ route('pembeli.marketplace') }}"
                    class="btn-shop-now"
                >

                    <i class="bi bi-shop"></i>

                    Belanja Sekarang

                </a>

            </div>

        </div>

    @endif

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | CONFIRM HAPUS WISHLIST
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.wishlist-delete-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const yakin = confirm(
                'Apakah kamu yakin ingin menghapus produk ini dari wishlist?'
            );

            if (!yakin) {

                event.preventDefault();

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | ANIMATION DELAY
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.wishlist-product-card').forEach(function (card, index) {

        card.style.animationDelay = (index * 0.05) + 's';

    });

});

</script>

@endsection
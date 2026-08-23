@extends('layouts.pembeli')

@section('title', 'Marketplace - Karyaku')

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

        --border-color: #e5edff;

        --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
        --shadow-hover: 0 16px 34px rgba(37, 99, 235, 0.16);
    }

    /* =====================================================
       MARKETPLACE WRAPPER
    ===================================================== */

    .marketplace-page {
        width: 100%;
        font-family: 'Poppins', sans-serif;
    }

    /* =====================================================
       HEADER
    ===================================================== */

    .market-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
        margin-bottom: 24px;

        flex-wrap: wrap;
    }

    .market-header h2 {
        margin: 0;
        color: var(--text-dark);

        font-size: 25px;
        font-weight: 800;
    }

    .market-header p {
        margin: 5px 0 0;

        color: var(--text-muted);

        font-size: 13px;
    }

    /* =====================================================
       SEARCH
    ===================================================== */

    .market-search {
        width: 100%;
        max-width: 520px;
    }

    .search-combo {
        display: flex;

        overflow: hidden;

        background: white;

        border-radius: 12px;

        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);
    }

    .search-combo input {
        flex: 1;

        min-width: 0;

        border: 0;
        outline: 0;

        padding: 12px 15px;

        font-size: 12px;

        color: var(--text-dark);
    }

    .search-combo input::placeholder {
        color: #94a3b8;
    }

    .search-combo button {
        width: 90px;

        border: 0;

        background: var(--coral);

        color: white;

        font-size: 12px;
        font-weight: 700;

        transition: .2s;
    }

    .search-combo button:hover {
        background: var(--coral-dark);
    }

    /* =====================================================
       TOOLBAR
    ===================================================== */

    .market-toolbar {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 15px;

        margin-bottom: 18px;

        flex-wrap: wrap;
    }

    .filter-pills {
        display: flex;

        align-items: center;

        gap: 8px;

        flex-wrap: wrap;
    }

    .filter-pill {
        display: inline-flex;
        align-items: center;

        border: 1px solid var(--border-color);

        background: white;

        color: var(--text-dark);

        padding: 8px 15px;

        border-radius: 20px;

        font-size: 11px;

        font-weight: 600;

        cursor: pointer;

        transition: .2s;
    }

    .filter-pill:hover,
    .filter-pill.active {
        background: var(--primary);

        color: white;

        border-color: var(--primary);
    }

    .sort-wrapper {
        display: flex;

        align-items: center;

        gap: 8px;
    }

    .sort-wrapper label {
        color: var(--text-muted);

        font-size: 11px;

        font-weight: 600;
    }

    .sort-select {
        min-width: 145px;

        border: 1px solid var(--border-color);

        background: white;

        color: var(--text-dark);

        border-radius: 10px;

        padding: 8px 12px;

        font-size: 11px;

        font-weight: 600;

        outline: none;

        box-shadow: var(--shadow);

        cursor: pointer;
    }

    .result-count {
        color: var(--text-muted);

        font-size: 11px;

        white-space: nowrap;
    }

    /* =====================================================
       CATEGORY
    ===================================================== */

    .category-section {
        margin-bottom: 22px;
    }

    .category-scroll {
        display: flex;

        gap: 8px;

        overflow-x: auto;

        padding: 3px 2px 8px;

        scrollbar-width: thin;
    }

    .category-scroll::-webkit-scrollbar {
        height: 5px;
    }

    .category-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;

        border-radius: 20px;
    }

    .category-pill {
        flex-shrink: 0;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        border: 1px solid var(--border-color);

        background: white;

        color: var(--text-dark);

        padding: 8px 14px;

        border-radius: 20px;

        font-size: 10px;

        font-weight: 600;

        transition: .2s;

        white-space: nowrap;
    }

    .category-pill:hover {
        background: var(--primary-light);

        color: var(--primary);

        border-color: var(--primary-soft);
    }

    .category-pill.active {
        background: var(--primary);

        color: white;

        border-color: var(--primary);

        box-shadow: 0 6px 16px rgba(37, 99, 235, .18);
    }

    /* =====================================================
       PRODUCT GRID
    ===================================================== */

    .product-grid {
        display: grid;

        grid-template-columns: repeat(5, minmax(0, 1fr));

        gap: 18px;
    }

    /* =====================================================
       PRODUCT CARD
    ===================================================== */

    .product-card {
        position: relative;

        background: white;

        border-radius: 15px;

        overflow: hidden;

        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);

        transition: .25s;

        min-width: 0;
    }

    .product-card:hover {
        transform: translateY(-5px);

        box-shadow: var(--shadow-hover);
    }

    /* =====================================================
       PRODUCT IMAGE
    ===================================================== */

    .product-thumb {
        height: 165px;

        position: relative;

        overflow: hidden;

        background: #eaf1ff;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        display: block;

        transition: .4s;
    }

    .product-card:hover .product-thumb img {
        transform: scale(1.06);
    }

    /* =====================================================
       CATEGORY BADGE
    ===================================================== */

    .cat-badge {
        position: absolute;

        top: 9px;
        left: 9px;

        padding: 4px 9px;

        background: rgba(20, 34, 92, .85);

        color: white;

        border-radius: 20px;

        font-size: 9px;

        font-weight: 700;

        z-index: 2;
    }

    /* =====================================================
       WISHLIST
    ===================================================== */

    .wish-btn {
        position: absolute;

        top: 8px;
        right: 8px;

        width: 31px;
        height: 31px;

        border: 0;

        border-radius: 50%;

        background: rgba(255, 255, 255, .94);

        color: #64748b;

        display: flex;

        align-items: center;
        justify-content: center;

        z-index: 3;

        transition: .2s;

        cursor: pointer;
    }

    .wish-btn:hover,
    .wish-btn.active {
        color: var(--coral);

        transform: scale(1.05);
    }

    /* =====================================================
       PRODUCT BODY
    ===================================================== */

    .product-body {
        padding: 12px;

        display: flex;

        flex-direction: column;

        gap: 6px;
    }

    .product-body h6 {
        margin: 0;

        min-height: 38px;

        font-size: 12px;

        line-height: 1.5;

        font-weight: 600;
    }

    .product-body h6 a {
        color: var(--text-dark);

        text-decoration: none;

        transition: .2s;
    }

    .product-body h6 a:hover {
        color: var(--primary);
    }

    /* =====================================================
       PRICE
    ===================================================== */

    .product-price {
        color: var(--coral);

        font-size: 15px;

        font-weight: 800;
    }

    /* =====================================================
       META
    ===================================================== */

    .product-meta {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 5px;

        color: var(--text-muted);

        font-size: 9px;
    }

    .product-meta span {
        white-space: nowrap;
    }

    .product-meta i {
        color: #94a3b8;
    }

    /* =====================================================
       SELLER
    ===================================================== */

    .product-seller {
        display: flex;

        align-items: center;

        gap: 6px;

        color: var(--text-muted);

        font-size: 9px;

        min-width: 0;
    }

    .product-seller img {
        width: 20px;
        height: 20px;

        border-radius: 50%;

        flex-shrink: 0;
    }

    .product-seller span {
        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;
    }

    /* =====================================================
       ADD CART
    ===================================================== */

    .btn-add-cart {
        width: 100%;

        margin-top: 5px;

        border: 0;

        border-radius: 9px;

        background: var(--primary-light);

        color: var(--primary);

        padding: 8px;

        font-size: 10px;

        font-weight: 700;

        transition: .2s;

        cursor: pointer;
    }

    .btn-add-cart:hover {
        background: var(--primary);

        color: white;

        transform: translateY(-1px);
    }

    /* =====================================================
       EMPTY STATE
    ===================================================== */

    .empty-product {
        grid-column: 1 / -1;

        background: white;

        border-radius: 18px;

        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);

        padding: 70px 20px;

        text-align: center;
    }

    .empty-icon {
        width: 65px;
        height: 65px;

        margin: 0 auto 15px;

        border-radius: 50%;

        background: var(--primary-light);

        color: var(--primary);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 27px;
    }

    .empty-product h5 {
        color: var(--text-dark);

        font-size: 16px;

        font-weight: 700;

        margin-bottom: 7px;
    }

    .empty-product p {
        color: var(--text-muted);

        font-size: 12px;

        margin-bottom: 18px;
    }

    .btn-reset {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        border: 1px solid var(--primary);

        color: var(--primary);

        background: white;

        padding: 8px 15px;

        border-radius: 9px;

        font-size: 11px;

        font-weight: 700;

        text-decoration: none;

        transition: .2s;
    }

    .btn-reset:hover {
        background: var(--primary);

        color: white;
    }

    /* =====================================================
       PAGINATION
    ===================================================== */

    .market-pagination {
        margin-top: 25px;
    }

    .market-pagination nav {
        display: flex;

        justify-content: center;
    }

    .market-pagination .pagination {
        margin-bottom: 0;

        gap: 4px;
    }

    .market-pagination .page-link {
        border-radius: 8px !important;

        border: 1px solid var(--border-color);

        color: var(--primary);

        font-size: 11px;

        padding: 7px 11px;
    }

    .market-pagination .page-item.active .page-link {
        background: var(--primary);

        border-color: var(--primary);

        color: white;
    }

    .market-pagination .page-link:hover {
        background: var(--primary-light);

        color: var(--primary);
    }

    /* =====================================================
       ALERT
    ===================================================== */

    .market-alert {
        border: 0;

        border-radius: 10px;

        font-size: 11px;

        box-shadow: var(--shadow);
    }

    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media(max-width: 1250px) {

        .product-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

    }

    @media(max-width: 1000px) {

        .product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .market-header {
            align-items: flex-start;
        }

        .market-search {
            max-width: 100%;
        }

    }

    @media(max-width: 700px) {

        .product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 12px;
        }

        .product-thumb {
            height: 140px;
        }

        .market-header h2 {
            font-size: 21px;
        }

        .market-header p {
            font-size: 11px;
        }

        .market-toolbar {
            align-items: flex-start;
        }

        .sort-wrapper {
            width: 100%;

            justify-content: space-between;
        }

        .sort-select {
            flex: 1;
        }

    }

    @media(max-width: 450px) {

        .product-grid {
            gap: 10px;
        }

        .product-thumb {
            height: 125px;
        }

        .product-body {
            padding: 10px;
        }

        .product-body h6 {
            font-size: 11px;
        }

        .product-price {
            font-size: 13px;
        }

        .product-meta {
            font-size: 8px;
        }

        .product-seller {
            font-size: 8px;
        }

        .btn-add-cart {
            font-size: 9px;
        }

        .search-combo button {
            width: 65px;
        }

    }
</style>


<div class="marketplace-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="market-header">

        <div>
            <h2>Marketplace</h2>

            <p>
                Temukan berbagai barang dan jasa digital dari kreator Karyaku.
            </p>
        </div>


        {{-- SEARCH --}}

        <div class="market-search">

            <form
                action="{{ route('pembeli.marketplace') }}"
                method="GET"
                class="search-combo"
            >

                @if(request('category'))
                    <input
                        type="hidden"
                        name="category"
                        value="{{ request('category') }}"
                    >
                @endif

                @if(request('sort'))
                    <input
                        type="hidden"
                        name="sort"
                        value="{{ request('sort') }}"
                    >
                @endif

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari barang, jasa, kreator..."
                    autocomplete="off"
                >

                <button type="submit">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>

            </form>

        </div>

    </div>


    {{-- =====================================================
         SESSION MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success market-alert mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger market-alert mb-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}
        </div>

    @endif


    {{-- =====================================================
         CATEGORY
    ====================================================== --}}

    <div class="category-section">

        <div class="category-scroll">

            {{-- SEMUA --}}

            <a
                href="{{ route('pembeli.marketplace', request()->except(['category', 'page'])) }}"
                class="category-pill {{ !request('category') ? 'active' : '' }}"
            >
                <i class="bi bi-grid-fill"></i>
                Semua Kategori
            </a>


            {{-- DATABASE CATEGORY --}}

            @foreach($categories as $cat)

                <a
                    href="{{ route(
                        'pembeli.marketplace',
                        array_merge(
                            request()->except('page'),
                            ['category' => $cat->id_category]
                        )
                    ) }}"
                    class="category-pill {{ request('category') == $cat->id_category ? 'active' : '' }}"
                >

                    <i class="bi bi-tag-fill"></i>

                    {{ $cat->name }}

                </a>

            @endforeach

        </div>

    </div>


    {{-- =====================================================
         TOOLBAR
    ====================================================== --}}

    <div class="market-toolbar">

        <div class="filter-pills">

            {{-- TERLARIS --}}

            <a
                href="{{ route(
                    'pembeli.marketplace',
                    array_merge(
                        request()->except('page', 'sort'),
                        ['sort' => 'terlaris']
                    )
                ) }}"
                class="filter-pill {{ request('sort', 'terlaris') == 'terlaris' ? 'active' : '' }}"
            >
                <i class="bi bi-fire me-1"></i>
                Terlaris
            </a>


            {{-- TERBARU --}}

            <a
                href="{{ route(
                    'pembeli.marketplace',
                    array_merge(
                        request()->except('page', 'sort'),
                        ['sort' => 'terbaru']
                    )
                ) }}"
                class="filter-pill {{ request('sort') == 'terbaru' ? 'active' : '' }}"
            >
                <i class="bi bi-stars me-1"></i>
                Terbaru
            </a>


            {{-- TERMURAH --}}

            <a
                href="{{ route(
                    'pembeli.marketplace',
                    array_merge(
                        request()->except('page', 'sort'),
                        ['sort' => 'termurah']
                    )
                ) }}"
                class="filter-pill {{ request('sort') == 'termurah' ? 'active' : '' }}"
            >
                <i class="bi bi-arrow-down me-1"></i>
                Harga Terendah
            </a>


            {{-- TERMAHAL --}}

            <a
                href="{{ route(
                    'pembeli.marketplace',
                    array_merge(
                        request()->except('page', 'sort'),
                        ['sort' => 'termahal']
                    )
                ) }}"
                class="filter-pill {{ request('sort') == 'termahal' ? 'active' : '' }}"
            >
                <i class="bi bi-arrow-up me-1"></i>
                Harga Tertinggi
            </a>

        </div>


        {{-- RESULT COUNT --}}

        <div class="result-count">

            <i class="bi bi-box-seam me-1"></i>

            {{ $products->total() }} produk

        </div>

    </div>


    {{-- =====================================================
         PRODUCT GRID
    ====================================================== --}}

    <div class="product-grid mb-4">

        @forelse($products as $product)

            @php

                $isWishlisted = in_array(
                    $product->id_product,
                    $wishlistIds ?? []
                );

            @endphp


            {{-- PRODUCT CARD --}}

            <div class="product-card">


                {{-- PRODUCT IMAGE --}}

                <div class="product-thumb">

                    <img
                        src="{{ $product->image_url ?? asset('storage/' . $product->image) }}"
                        alt="{{ $product->title }}"
                        onerror="this.src='https://placehold.co/600x400/eaf1ff/2563eb?text=Produk+Karyaku'"
                    >


                    {{-- CATEGORY --}}

                    <span class="cat-badge">

                        {{ $product->category->name ?? 'Jasa' }}

                    </span>


                    {{-- WISHLIST --}}

                    <form
                        action="{{ route(
                            'pembeli.wishlist.toggle',
                            $product->id_product
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="wish-btn {{ $isWishlisted ? 'active' : '' }}"
                            title="Wishlist"
                        >

                            <i
                                class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"
                            ></i>

                        </button>

                    </form>

                </div>


                {{-- PRODUCT BODY --}}

                <div class="product-body">


                    {{-- TITLE --}}

                    <h6>

                        <a
                            href="{{ route(
                                'pembeli.produk.detail',
                                $product->id_product
                            ) }}"
                        >

                            {{ $product->title }}

                        </a>

                    </h6>


                    {{-- PRICE --}}

                    <div class="product-price">

                        Rp {{ number_format(
                            $product->price,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>


                    {{-- META --}}

                    <div class="product-meta">

                        <span>

                            <i class="bi bi-star-fill text-warning me-1"></i>

                            {{ $product->rating ?? '0.0' }}

                        </span>


                        <span>

                            <i class="bi bi-bag-check me-1"></i>

                            {{ $product->sold_count ?? 0 }}

                            Terjual

                        </span>

                    </div>


                    {{-- SELLER --}}

                    <div class="product-seller">

                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($product->seller->name ?? 'Penjual') }}&background=eff6ff&color=1e3a8a"
                            alt="seller"
                        >

                        <span>

                            {{ $product->seller->name ?? 'Kreator Karyaku' }}

                        </span>

                    </div>


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

                            Tambah Keranjang

                        </button>

                    </form>


                </div>

            </div>

        @empty


            {{-- EMPTY --}}

            <div class="empty-product">

                <div class="empty-icon">

                    <i class="bi bi-search"></i>

                </div>


                <h5>
                    Produk Tidak Ditemukan
                </h5>


                <p>

                    Tidak ada produk yang sesuai dengan
                    pencarian atau filter yang kamu pilih.

                </p>


                <a
                    href="{{ route('pembeli.marketplace') }}"
                    class="btn-reset"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Reset Pencarian

                </a>

            </div>

        @endforelse

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if($products->hasPages())

        <div class="market-pagination">

            {{ $products->appends(request()->query())->links() }}

        </div>

    @endif


</div>


{{-- =====================================================
     JAVASCRIPT
====================================================== --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | WISHLIST BUTTON
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.wish-btn').forEach(function (button) {

            button.addEventListener('click', function () {

                this.classList.add('active');

            });

        });


        /*
        |--------------------------------------------------------------------------
        | ADD TO CART FEEDBACK
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.btn-add-cart').forEach(function (button) {

            const form = button.closest('form');

            if (!form) {
                return;
            }


            form.addEventListener('submit', function () {

                button.disabled = true;

                button.innerHTML =
                    '<i class="bi bi-check2-circle me-1"></i> Menambahkan...';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | AUTO HIDE ALERT
        |--------------------------------------------------------------------------
        */

        setTimeout(function () {

            document.querySelectorAll('.market-alert').forEach(function (alert) {

                alert.style.transition = 'opacity .4s';

                alert.style.opacity = '0';

                setTimeout(function () {

                    alert.remove();

                }, 400);

            });

        }, 3000);

    });

</script>

@endsection
@extends('layouts.pembeli')

@section('title', 'Marketplace - Karyaku')

@push('styles')
<style>
    .market-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #ffffff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    .market-banner::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    .cat-chips-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 20px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .cat-chips-scroll::-webkit-scrollbar {
        display: none;
    }
    .cat-chip {
        white-space: nowrap;
        padding: 8px 16px;
        border-radius: 30px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }
    .cat-chip:hover {
        background: var(--primary-light);
        border-color: #bfdbfe;
        color: var(--primary);
    }
    .cat-chip.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .filter-bar {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.02);
    }
    
    .filter-sort-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 4px 10px;
        transition: all 0.2s ease;
    }
    .filter-sort-wrapper:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: #ffffff;
    }
    .filter-sort-select {
        border: none;
        background: transparent;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-dark);
        outline: none;
        padding: 4px 8px;
        cursor: pointer;
    }

    @media(max-width: 576px) {
        .market-banner { padding: 18px 16px; border-radius: 16px; margin-bottom: 18px; }
        .market-banner h3 { font-size: 1.25rem; }
        .market-banner p { font-size: 11.5px; }
        .cat-chip { padding: 6px 12px; font-size: 11.5px; border-radius: 20px; }
        .filter-bar { padding: 12px 14px; flex-direction: column; align-items: stretch; gap: 10px; border-radius: 14px; margin-bottom: 18px; }
        .filter-bar form { width: 100%; margin-left: 0 !important; }
        .filter-sort-wrapper { width: 100%; justify-content: space-between; }
        .filter-sort-select { flex: 1; font-size: 12px; }
    }
</style>
@endpush

@section('content')

    {{-- Banner Marketplace --}}
    <div class="market-banner">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill small fw-bold mb-2">
                    <i class="bi bi-shop me-1"></i> Katalog Resmi
                </span>
                <h3 class="fw-extrabold mb-1">Jelajahi Marketplace Digital</h3>
                <p class="text-white-50 mb-0 small">Temukan aset visual, desain grafis, 3D model, UI/UX, dan berkas kreatif langsung dari kreator.</p>
            </div>
            <div class="d-none d-md-block">
                <i class="bi bi-grid-3x3-gap-fill fs-1 text-white opacity-50"></i>
            </div>
        </div>
    </div>

    {{-- Kategori Horizontal Chips --}}
    <div class="cat-chips-scroll">
        <a href="{{ route('pembeli.marketplace', request()->except('category', 'page')) }}" 
           class="cat-chip {{ !request('category') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Semua Kategori
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('pembeli.marketplace', array_merge(request()->except('page'), ['category' => $cat->id_category])) }}" 
               class="cat-chip {{ request('category') == $cat->id_category ? 'active' : '' }}">
                <span>{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filter & Sorting Bar --}}
    <div class="filter-bar">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-light border text-secondary" style="font-size: 12px; font-weight: 600;">
                <span>{{ $products->total() }} Produk Tersedia</span>
            </div>
            @if(request('q'))
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill" style="font-size: 12px;">
                    <i class="bi bi-search"></i> Pencarian: "{{ request('q') }}"
                    <a href="{{ route('pembeli.marketplace', request()->except('q', 'page')) }}" class="text-primary ms-1 fw-bold text-decoration-none"><i class="bi bi-x-circle-fill"></i></a>
                </span>
            @endif
        </div>

        <form action="{{ route('pembeli.marketplace') }}" method="GET" class="d-flex align-items-center gap-2 ms-auto">
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            
            <div class="filter-sort-wrapper">
                <div class="d-flex align-items-center gap-1 text-muted ps-1" style="font-size: 12px;">
                    <i class="bi bi-sort-down-alt text-primary"></i>
                    <span class="d-none d-sm-inline fw-semibold">Urutkan:</span>
                </div>
                <select name="sort" id="sortSelect" class="filter-sort-select" onchange="this.form.submit()">
                    <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Paling Terlaris</option>
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Paling Terbaru</option>
                    <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Grid Produk --}}
    <div class="product-grid" id="marketProductGrid">
        @forelse ($products as $product)
            @include('pembeli.partials.product-card', ['product' => $product])
        @empty
            <div class="w-100 text-center py-5 bg-white rounded-4 border shadow-sm" style="grid-column: 1 / -1;">
                <i class="bi bi-search display-4 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Tidak Ada Produk yang Ditemukan</h5>
                <p class="text-muted small mb-3">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm px-4 py-2.5 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-arrow-repeat me-1"></i> Reset Filter Pencarian
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @endif

@endsection
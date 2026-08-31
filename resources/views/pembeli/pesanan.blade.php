@extends('layouts.pembeli')

@section('title', 'Pesanan Saya')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-receipt me-1 text-primary"></i>
                Daftar Pesanan Saya
            </h4>

            <p class="text-muted mb-0 small">
                Pantau status transaksi, riwayat belanja, dan unduh berkas digital pesanan Anda.
            </p>
        </div>

        <a href="{{ route('pembeli.download') }}"
           class="btn btn-outline-primary btn-sm fw-bold">

            <i class="bi bi-cloud-arrow-down-fill me-1"></i>
            File Download Saya

        </a>

    </div>


    {{-- TAB FILTER STATUS --}}
    <div class="card-box p-2 mb-4">
        <ul class="nav nav-pills gap-2">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'semua') === 'semua' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'semua']) }}">
                    <i class="bi bi-collection me-1"></i> Semua
                    <span class="badge {{ ($tab ?? 'semua') === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['semua'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'diproses' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'diproses']) }}">
                    <i class="bi bi-hourglass-split me-1"></i> Diproses
                    <span class="badge {{ ($tab ?? '') === 'diproses' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['diproses'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'selesai' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'selesai']) }}">
                    <i class="bi bi-check2-circle me-1"></i> Selesai
                    <span class="badge {{ ($tab ?? '') === 'selesai' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['selesai'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'dibatalkan' ? 'active fw-bold' : 'text-secondary' }}" 
                   href="{{ route('pembeli.pesanan', ['tab' => 'dibatalkan']) }}">
                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                    <span class="badge {{ ($tab ?? '') === 'dibatalkan' ? 'bg-white text-primary' : 'bg-light text-dark border' }} ms-1">{{ $counts['dibatalkan'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- DAFTAR PESANAN --}}
    @forelse ($orders as $order)

        <div class="card-box p-4 mb-3 border hover-shadow transition-all"
             style="border-radius: 16px;">


            {{-- HEADER PESANAN --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="d-flex align-items-center justify-content-center
                                bg-primary-light text-primary rounded-3"
                         style="width: 42px; height: 42px;">

                        <i class="bi bi-bag-check fs-5"></i>

                    </div>


                    <div>

                        <strong class="text-dark d-block"
                                style="font-size: 14px;">

                            Order #{{ $order->kode_order ?? $order->id_order }}

                        </strong>


                        <span class="text-muted small">

                            <i class="bi bi-calendar-event me-1"></i>

                            {{ $order->created_at
                                ? $order->created_at->format('d M Y, H:i') . ' WIB'
                                : '-' }}

                        </span>

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="d-flex align-items-center flex-wrap gap-2">

                    {{-- STATUS PEMBAYARAN --}}
                    @if ($order->payment_status === 'paid')

                        <span class="badge bg-success-subtle
                                     text-success
                                     border
                                     border-success-subtle
                                     px-3 py-2 rounded-pill"
                              style="font-size: 11px;">

                            <i class="bi bi-check-circle-fill me-1"></i>
                            Pembayaran Lunas

                        </span>

                    @else

                        <span class="badge bg-warning-subtle
                                     text-warning
                                     border
                                     border-warning-subtle
                                     px-3 py-2 rounded-pill"
                              style="font-size: 11px;">

                            <i class="bi bi-clock-fill me-1"></i>
                            Belum Dibayar

                        </span>

                    @endif


                    {{-- STATUS PESANAN --}}
                    <span class="badge bg-primary-subtle
                                 text-primary
                                 border
                                 border-primary-subtle
                                 px-3 py-2 rounded-pill
                                 text-capitalize"
                          style="font-size: 11px;">

                        <i class="bi bi-box-seam me-1"></i>

                        {{ $order->status ?? 'Diproses' }}

                    </span>

                </div>

            </div>


            {{-- LIST PRODUK --}}
            <div class="d-flex flex-column gap-3 mb-3">

                @forelse ($order->items as $item)

                    @php
                        $product = $item->product;

                        $productName =
                            $product->title
                            ?? $product->name
                            ?? $product->nama_produk
                            ?? 'Produk Digital';

                        $productPrice =
                            $item->price
                            ?? $product->price
                            ?? $product->harga
                            ?? 0;

                        $quantity =
                            $item->quantity
                            ?? $item->jumlah
                            ?? 1;

                        $subtotal =
                            $item->subtotal
                            ?? ($productPrice * $quantity);

                        $productImage =
                            $product->image_url
                            ?? $product->image
                            ?? $product->gambar
                            ?? null;


                        if ($productImage) {

                            if (
                                str_starts_with($productImage, 'http://') ||
                                str_starts_with($productImage, 'https://')
                            ) {

                                $imageUrl = $productImage;

                            } else {

                                $imageUrl =
                                    asset('storage/' . $productImage);

                            }

                        } else {

                            $imageUrl =
                                'https://placehold.co/100x100?text=Produk';

                        }
                    @endphp


                    <div class="d-flex align-items-center gap-3
                                p-2 rounded-3
                                border-bottom">

                        {{-- GAMBAR PRODUK --}}
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $productName }}"
                            class="rounded-3 object-fit-cover flex-shrink-0"
                            style="width: 60px; height: 60px;"
                            onerror="this.src='https://placehold.co/100x100?text=Produk'"
                        >


                        {{-- INFORMASI PRODUK --}}
                        <div class="flex-grow-1 overflow-hidden">

                            <h6 class="fw-bold mb-1 text-truncate"
                                style="font-size: 13.5px;">

                                {{ $productName }}

                            </h6>


                            <div class="text-muted small">

                                {{ $quantity }}
                                x
                                Rp {{ number_format($productPrice, 0, ',', '.') }}

                            </div>

                        </div>


                        {{-- SUBTOTAL --}}
                        <div class="fw-bold text-dark flex-shrink-0"
                             style="font-size: 14px;">

                            Rp {{ number_format($subtotal, 0, ',', '.') }}

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-3">

                        <i class="bi bi-box fs-3 d-block mb-2"></i>

                        Data produk tidak ditemukan.

                    </div>

                @endforelse

            </div>


            {{-- FOOTER PESANAN --}}
            <div class="d-flex flex-column flex-md-row
                        align-items-md-center
                        justify-content-between
                        gap-3
                        border-top pt-3">


                {{-- TOTAL --}}
                <div>

                    <span class="text-muted small">

                        Total Pembayaran:

                    </span>


                    <strong class="text-primary fs-6 ms-1">

                        Rp {{ number_format(
                            $order->total_price ?? $order->total ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}

                    </strong>

                </div>


                {{-- BUTTON --}}
                <div class="d-flex flex-wrap align-items-center gap-2">


                    {{-- DOWNLOAD JIKA SUDAH DIBAYAR --}}
                    @if ($order->payment_status === 'paid')

                        <a href="{{ route('pembeli.download') }}"
                           class="btn btn-sm btn-outline-success fw-bold">

                            <i class="bi bi-cloud-arrow-down-fill me-1"></i>

                            Unduh Berkas

                        </a>

                    @endif


                    {{-- DETAIL PESANAN --}}
                    <a href="{{ route('pembeli.pesanan.detail', $order->id_order) }}"
                       class="btn btn-sm btn-primary fw-bold px-3">

                        Detail Pesanan

                        <i class="bi bi-chevron-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    @empty


        {{-- JIKA BELUM ADA PESANAN --}}
        <div class="card-box p-5 text-center">

            <div class="d-inline-flex
                        align-items-center
                        justify-content-center
                        bg-primary-light
                        text-primary
                        rounded-circle
                        mb-3"
                 style="width: 70px; height: 70px;">

                <i class="bi bi-journal-x fs-2"></i>

            </div>


            <h5 class="fw-bold">

                Belum Ada Pesanan

            </h5>


            <p class="text-muted small mb-4">

                Anda belum melakukan transaksi apapun di Karyaku.

            </p>


            <a href="{{ route('pembeli.marketplace') }}"
               class="btn btn-primary
                      px-4 py-2
                      fw-bold rounded-3">

                <i class="bi bi-shop me-1"></i>

                Mulai Belanja Sekarang

            </a>

        </div>

    @endforelse


    {{-- PAGINATION --}}
    @if ($orders->hasPages())

        <div class="d-flex justify-content-center mt-4">

            {{ $orders->links() }}

        </div>

    @endif

@endsection
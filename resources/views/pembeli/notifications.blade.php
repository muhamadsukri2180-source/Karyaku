@extends('layouts.pembeli')

@section('title', 'Semua Notifikasi')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Semua Notifikasi
            </h4>

            <p class="text-muted mb-0">
                Lihat seluruh informasi dan pemberitahuan untuk akun kamu.
            </p>
        </div>

        {{-- Tombol kembali --}}
        <a href="{{ route('pembeli.dashboard') }}"
           class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>


    {{-- =========================================================
         DAFTAR NOTIFIKASI
    ========================================================== --}}

    @if ($notifications->count() > 0)

        <div class="card-box">

            @foreach ($notifications as $notif)

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | CEK NOTIFIKASI BARU
                    |--------------------------------------------------------------------------
                    */

                    $isNew = false;

                    if ($notif->created_at) {
                        $isNew = $notif->created_at->greaterThan(
                            now()->subDays(3)
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DATA NOTIFIKASI
                    |--------------------------------------------------------------------------
                    */

                    $title = $notif->name ?? 'Notifikasi';

                    $description = $notif->description
                        ?? 'Tidak ada informasi tambahan.';

                @endphp


                <div class="notification-item
                    d-flex
                    gap-3
                    p-4
                    {{ !$loop->last ? 'border-bottom' : '' }}">


                    {{-- =================================================
                         ICON
                    ================================================== --}}

                    <div
                        class="notification-icon
                               d-flex
                               align-items-center
                               justify-content-center
                               rounded-3
                               flex-shrink-0"
                        style="
                            width:48px;
                            height:48px;
                            background:var(--primary-light);
                            color:var(--primary);
                        "
                    >

                        <i class="bi bi-bell-fill fs-5"></i>

                    </div>


                    {{-- =================================================
                         ISI NOTIFIKASI
                    ================================================== --}}

                    <div class="flex-fill">

                        {{-- Judul --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            <div class="fw-bold">

                                {{ $title }}

                            </div>


                            {{-- Badge Baru --}}

                            @if ($isNew)

                                <span
                                    class="badge rounded-pill bg-danger-subtle text-danger"
                                >
                                    Baru
                                </span>

                            @endif

                        </div>


                        {{-- Deskripsi --}}

                        <div class="text-muted mt-2">

                            {{ $description }}

                        </div>


                        {{-- Tanggal --}}

                        @if ($notif->created_at)

                            <div
                                class="text-muted mt-2"
                                style="font-size:12px;"
                            >

                                <i class="bi bi-clock me-1"></i>

                                {{ $notif->created_at->translatedFormat('d F Y, H:i') }}

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}

        @if ($notifications->hasPages())

            <div class="mt-4 d-flex justify-content-center">

                {{ $notifications->links() }}

            </div>

        @endif


    @else

        {{-- =========================================================
             JIKA TIDAK ADA NOTIFIKASI
        ========================================================== --}}

        <div class="card-box p-5 text-center">

            <div
                class="d-flex
                       align-items-center
                       justify-content-center
                       rounded-circle
                       mx-auto
                       mb-4"
                style="
                    width:80px;
                    height:80px;
                    background:var(--primary-light);
                    color:var(--primary);
                "
            >

                <i class="bi bi-bell-slash fs-1"></i>

            </div>


            <h5 class="fw-bold mb-2">

                Belum Ada Notifikasi

            </h5>


            <p class="text-muted mb-4">

                Saat ini belum ada notifikasi yang tersedia
                untuk akun kamu.

            </p>


            <a
                href="{{ route('pembeli.dashboard') }}"
                class="btn btn-primary"
            >

                <i class="bi bi-house-door-fill me-1"></i>

                Kembali ke Dashboard

            </a>

        </div>

    @endif

</div>


{{-- =============================================================
     STYLE TAMBAHAN
============================================================= --}}

<style>

    .notification-item {
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .notification-icon {
        min-width: 48px;
    }

    .card-box {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    @media (max-width: 576px) {

        .notification-item {
            padding: 20px !important;
        }

        .notification-icon {
            width: 42px !important;
            height: 42px !important;
            min-width: 42px !important;
        }

    }

</style>

@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Karyaku - Marketplace Jasa Digital Kreator Indonesia</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
          rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        primaryHover: '#1D4ED8',
                        accent: '#F97316',
                        accentHover: '#EA580C',

                        bgLight: '#F8FAFC',
                        cardWhite: '#FFFFFF',

                        textMain: '#0F172A',
                        textMuted: '#64748B',

                        borderSoft: '#E2E8F0'
                    },

                    fontFamily: {
                        display: ['Sora', 'sans-serif'],
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: #0F172A;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .font-display {
            font-family: 'Sora', sans-serif;
        }

        :focus-visible {
            outline: 2px solid #F97316;
            outline-offset: 2px;
            border-radius: 5px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity .6s ease,
                transform .6s ease;
        }

        .reveal.in-view {
            opacity: 1;
            transform: none;
        }

        #mobileMenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
        }

        #mobileMenu.open {
            max-height: 500px;
        }

        .benefit-track {
            transition: transform .5s ease;
        }

        .package-card {
            transition:
                transform .3s ease,
                box-shadow .3s ease,
                border-color .3s ease;
        }

        .package-card:hover {
            transform: translateY(-7px);
        }

        .shine {
            position: relative;
            overflow: hidden;
        }

        .shine::after {
            content: "";
            position: absolute;
            top: 0;
            left: -120%;
            width: 60%;
            height: 100%;
            transform: skewX(-20deg);
            background: rgba(255,255,255,.15);
            transition: left .7s ease;
        }

        .shine:hover::after {
            left: 150%;
        }

        .check-icon {
            width: 21px;
            height: 21px;
            min-width: 21px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>

<body class="antialiased selection:bg-primary selection:text-white">

<!-- ========================================================= -->
<!-- NAVBAR -->
<!-- ========================================================= -->

<header class="sticky top-0 z-50 bg-gradient-to-r from-blue-700 to-primary shadow-md">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">

        <div class="flex justify-between items-center">

            <!-- LOGO -->
            <a href="#hero" class="flex items-center gap-2.5">

                <div class="w-9 h-9 rounded-lg bg-white text-primary flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-white leading-none font-display">
                        Karyaku<span class="text-accent">.</span>
                    </h1>

                    <span class="text-[9px] text-blue-100 font-medium tracking-wide">
                        DIGITAL MARKETPLACE
                    </span>
                </div>

            </a>

            <!-- DESKTOP NAV -->
            <nav class="hidden lg:flex items-center space-x-7 text-[13px] font-semibold text-blue-100">

                <a href="#hero"
                   class="hover:text-white transition">
                    Beranda
                </a>

                <a href="#kategori"
                   class="hover:text-white transition">
                    Kategori
                </a>

                <a href="#cara-kerja"
                   class="hover:text-white transition">
                    Cara Kerja
                </a>

                <a href="#karya-pilihan"
                   class="hover:text-white transition">
                    Karya Pilihan
                </a>

                <a href="#paket-penjual"
                   class="hover:text-white transition">
                    Paket Penjual
                </a>

            </nav>

            <!-- ACTION -->
            <div class="flex items-center gap-2">

                <!-- MASUK -->
                <a href="{{ url('/auth/login') }}"
                   class="hidden sm:inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg transition">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Masuk
                </a>

                <!-- MASUK SEBAGAI PENJUAL / DAFTAR PENJUAL -->
                <a href="{{ url('/auth/login?role=penjual') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 text-xs font-bold text-white bg-accent hover:bg-accentHover rounded-lg transition shadow-lg shadow-orange-900/20">

                    <i class="fa-solid fa-store"></i>

                    <span class="hidden sm:inline">
                        Masuk sebagai Penjual
                    </span>

                    <span class="sm:hidden">
                        Penjual
                    </span>

                </a>

                <!-- MOBILE -->
                <button id="menuToggle"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-blue-400 text-white hover:bg-blue-600">

                    <i class="fa-solid fa-bars" id="menuIcon"></i>

                </button>

            </div>

        </div>

    </div>


    <!-- MOBILE MENU -->

    <div id="mobileMenu"
         class="lg:hidden bg-white border-t border-slate-200 shadow-xl">

        <nav class="flex flex-col px-4 py-4 space-y-1 text-sm font-semibold text-slate-600">

            <a href="#hero"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-house w-5"></i>
                Beranda
            </a>

            <a href="#kategori"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-grid-2 w-5"></i>
                Kategori
            </a>

            <a href="#cara-kerja"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-list-check w-5"></i>
                Cara Kerja
            </a>

            <a href="#karya-pilihan"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-star w-5"></i>
                Karya Pilihan
            </a>

            <a href="#benefit-penjual"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-rocket w-5"></i>
                Benefit Penjual
            </a>

            <a href="#paket-penjual"
               class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-primary">
                <i class="fa-solid fa-box-open w-5"></i>
                Paket Penjual
            </a>

            <div class="h-px bg-slate-200 my-2"></div>

            <a href="{{ url('/auth/login') }}"
               class="px-4 py-3 rounded-lg bg-slate-100 text-center">
                Masuk
            </a>

            <a href="{{ url('/auth/login?role=penjual') }}"
               class="px-4 py-3 rounded-lg bg-accent text-white text-center">
                <i class="fa-solid fa-store mr-2"></i>
                Masuk sebagai Penjual
            </a>

        </nav>

    </div>

</header>


<!-- ========================================================= -->
<!-- HERO -->
<!-- ========================================================= -->

<section id="hero"
         class="relative pt-16 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-gradient-to-br from-blue-50 via-white to-orange-50 border-b border-slate-200">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">

        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-blue-100/60 blur-3xl"></div>

        <div class="absolute top-[45%] -left-40 w-[450px] h-[450px] rounded-full bg-orange-100/50 blur-3xl"></div>

    </div>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- LEFT -->

            <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0 reveal">

                <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-blue-100 text-primary text-xs font-bold tracking-wide mb-6 border border-blue-200">

                    <i class="fa-solid fa-sparkles text-accent"></i>

                    Ruang Karya Digital Indonesia

                </span>


                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 text-textMain leading-[1.15] tracking-tight">

                    Temukan Jasa Digital untuk

                    <span class="text-primary">
                        Kebutuhanmu.
                    </span>

                </h1>


                <p class="text-base sm:text-lg text-textMuted mb-8 leading-relaxed">

                    Temukan kreator berbakat, beli karya digital,
                    atau buka toko sendiri dan mulai menghasilkan
                    dari keahlianmu di Karyaku.

                </p>


                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">

                    <a href="#kategori"
                       class="inline-flex items-center justify-center gap-2 bg-primary text-white px-7 py-3.5 rounded-xl text-sm font-bold hover:bg-primaryHover transition shadow-lg shadow-primary/30">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Cari Jasa

                    </a>


                    <a href="#paket-penjual"
                       class="inline-flex items-center justify-center gap-2 bg-white text-primary border border-blue-200 px-7 py-3.5 rounded-xl text-sm font-bold hover:bg-blue-50 transition shadow-sm">

                        <i class="fa-solid fa-store"></i>

                        Mulai Jualan
                    </a>
                </div>
            </div>


            <!-- RIGHT IMAGE -->

            <div class="hidden lg:grid grid-cols-2 gap-4 reveal">

                <div class="space-y-4 pt-12">

                    <img
                        src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80"
                        class="rounded-2xl object-cover h-48 w-full shadow-lg border border-white">

                    <img
                        src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80"
                        class="rounded-2xl object-cover h-64 w-full shadow-lg border border-white">

                </div>


                <div class="space-y-4">

                    <img
                        src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80"
                        class="rounded-2xl object-cover h-64 w-full shadow-lg border border-white">

                    <img
                        src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80"
                        class="rounded-2xl object-cover h-48 w-full shadow-lg border border-white">

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- KATEGORI -->
<!-- ========================================================= -->

<section id="kategori" class="py-20 lg:py-24 bg-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12 reveal">

            <span class="text-primary text-xs font-bold uppercase tracking-widest">
                Explore
            </span>

            <h2 class="font-display text-2xl sm:text-3xl font-bold text-textMain mt-2 mb-3">
                Eksplorasi Kategori Jasa
            </h2>

            <p class="text-textMuted text-sm sm:text-base">
                Temukan layanan digital yang sesuai dengan kebutuhan proyekmu.
            </p>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


            <!-- CATEGORY -->

            <div onclick="openModal('canva')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="relative h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-image"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        Desain Poster Canva
                    </h3>

                    <p class="text-sm text-textMuted">
                        Poster promosi, event, menu restoran dan kebutuhan visual.
                    </p>

                </div>

            </div>


            <!-- CATEGORY -->

            <div onclick="openModal('blender')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-cube"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        Model 3D Blender
                    </h3>

                    <p class="text-sm text-textMuted">
                        Karakter, aset game, arsitektur dan objek 3D.
                    </p>

                </div>

            </div>


            <!-- CATEGORY -->

            <div onclick="openModal('logo')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-signature"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        Logo & Branding
                    </h3>

                    <p class="text-sm text-textMuted">
                        Logo, brand identity, packaging dan kebutuhan branding.
                    </p>

                </div>

            </div>


            <!-- CATEGORY -->

            <div onclick="openModal('sosmed')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-hashtag"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        Konten Media Sosial
                    </h3>

                    <p class="text-sm text-textMuted">
                        Feed, story, reels, TikTok dan konten digital lainnya.
                    </p>

                </div>

            </div>


            <!-- CATEGORY -->

            <div onclick="openModal('uiux')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-pen-ruler"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        UI/UX Design
                    </h3>

                    <p class="text-sm text-textMuted">
                        Website, aplikasi mobile, wireframe dan prototype.
                    </p>

                </div>

            </div>


            <!-- CATEGORY -->

            <div onclick="openModal('ilustrasi')"
                 class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition">

                <div class="h-44 overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-paintbrush"></i>
                        </div>

                        <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg">
                            Lihat Detail
                        </span>

                    </div>

                    <h3 class="font-bold text-lg mb-2">
                        Ilustrasi Digital
                    </h3>

                    <p class="text-sm text-textMuted">
                        Vektor, ilustrasi karakter, karikatur dan ilustrasi buku.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- CARA KERJA -->
<!-- ========================================================= -->

<section id="cara-kerja"
         class="py-20 lg:py-24 bg-bgLight border-y border-borderSoft">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-14 reveal">

            <span class="text-primary text-xs font-bold uppercase tracking-widest">
                Simple Process
            </span>

            <h2 class="font-display text-2xl sm:text-3xl font-bold mt-2 mb-3">
                Cara Kerja Karyaku
            </h2>

            <p class="text-textMuted text-sm sm:text-base">
                Mudah untuk pembeli maupun penjual.
            </p>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">

            <div class="hidden md:block absolute top-6 left-[16%] right-[16%] h-px bg-borderSoft"></div>


            <div class="reveal relative z-10 text-center flex flex-col items-center">

                <div class="w-12 h-12 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">
                    1
                </div>

                <h3 class="font-bold mb-2">
                    Cari Kreator
                </h3>

                <p class="text-textMuted text-sm leading-relaxed max-w-xs">
                    Temukan jasa atau karya sesuai kebutuhanmu.
                </p>

            </div>


            <div class="reveal relative z-10 text-center flex flex-col items-center">

                <div class="w-12 h-12 bg-white border-2 border-accent text-accent rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">
                    2
                </div>

                <h3 class="font-bold mb-2">
                    Pesan & Bayar
                </h3>

                <p class="text-textMuted text-sm leading-relaxed max-w-xs">
                    Lakukan pemesanan dengan sistem pembayaran yang aman.
                </p>

            </div>


            <div class="reveal relative z-10 text-center flex flex-col items-center">

                <div class="w-12 h-12 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">
                    3
                </div>

                <h3 class="font-bold mb-2">
                    Terima Hasil
                </h3>

                <p class="text-textMuted text-sm leading-relaxed max-w-xs">
                    Terima karya dan berikan ulasan kepada kreator.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- KARYA PILIHAN -->
<!-- ========================================================= -->

<section id="karya-pilihan"
         class="py-20 lg:py-24 bg-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4 reveal">

            <div>

                <span class="text-primary text-xs font-bold uppercase tracking-widest">
                    Marketplace
                </span>

                <h2 class="font-display text-2xl sm:text-3xl font-bold mt-2 mb-2">
                    Karya & Jasa Pilihan
                </h2>

                <p class="text-textMuted text-sm sm:text-base">
                    Karya menarik dari kreator Karyaku.
                </p>

            </div>

            <a href="#"
               class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-borderSoft font-bold text-sm hover:bg-slate-50">
                Lihat Semua
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


            <!-- PRODUCT 1 -->

            <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition">

                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">

                    <div class="absolute top-3 left-3 z-10 bg-white px-3 py-1.5 rounded-md shadow-sm text-[10px] font-bold">
                        <i class="fa-solid fa-bolt text-accent mr-1"></i>
                        DIPROMOSIKAN
                    </div>

                    <img
                        src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex items-center gap-2 mb-2">

                        <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <span class="text-xs font-bold text-textMuted">
                            Dinda Studio
                        </span>

                    </div>

                    <h4 class="font-bold mb-4">
                        Desain Poster Promosi Kafe Modern
                    </h4>

                    <div class="pt-4 border-t border-borderSoft flex justify-between">

                        <span class="text-sm font-bold">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            4.9
                            <span class="text-textMuted text-xs">
                                (120)
                            </span>
                        </span>

                        <span class="font-mono font-bold text-primary">
                            Rp75.000
                        </span>

                    </div>

                </div>

            </div>


            <!-- PRODUCT 2 -->

            <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition">

                <div class="aspect-[4/3] overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex items-center gap-2 mb-2">

                        <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <span class="text-xs font-bold text-textMuted">
                            Rangga 3D
                        </span>

                    </div>

                    <h4 class="font-bold mb-4">
                        Model 3D Karakter Game
                    </h4>

                    <div class="pt-4 border-t border-borderSoft flex justify-between">

                        <span class="text-sm font-bold">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            5.0
                        </span>

                        <span class="font-mono font-bold text-primary">
                            Rp480.000
                        </span>

                    </div>

                </div>

            </div>


            <!-- PRODUCT 3 -->

            <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition">

                <div class="aspect-[4/3] overflow-hidden bg-slate-100">

                    <img
                        src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                </div>

                <div class="p-5">

                    <div class="flex items-center gap-2 mb-2">

                        <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <span class="text-xs font-bold text-textMuted">
                            Kirana Design
                        </span>

                    </div>

                    <h4 class="font-bold mb-4">
                        Paket Logo & Brand Identity
                    </h4>

                    <div class="pt-4 border-t border-borderSoft flex justify-between">

                        <span class="text-sm font-bold">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            4.8
                        </span>

                        <span class="font-mono font-bold text-primary">
                            Rp150.000
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- BENEFIT PENJUAL - SLIDER -->
<!-- ========================================================= -->

<section id="benefit-penjual"
         class="py-20 lg:py-28 bg-slate-50 text-slate-900 overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER -->

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10 reveal">

            <div>

                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-widest">

                    <i class="fa-solid fa-store"></i>

                    Untuk Penjual

                </span>

                <h2 class="font-display text-3xl sm:text-4xl font-bold mt-4 mb-3 text-slate-900">

                    Kenapa Jualan di
                    <span class="text-blue-600">
                        Karyaku?
                    </span>

                </h2>

                <p class="text-slate-600 max-w-2xl text-sm sm:text-base">
                    Bukan cuma tempat upload produk. Karyaku membantu
                    penjual menampilkan karya, mendapatkan pelanggan,
                    dan mengembangkan toko.
                </p>

            </div>


            <!-- ARROWS -->

            <div class="flex gap-2">

                <button id="benefitPrev"
                        class="w-11 h-11 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 transition shadow-sm">

                    <i class="fa-solid fa-arrow-left"></i>

                </button>

                <button id="benefitNext"
                        class="w-11 h-11 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">

                    <i class="fa-solid fa-arrow-right"></i>

                </button>

            </div>

        </div>


        <!-- SLIDER -->

        <div class="overflow-hidden">

            <div id="benefitTrack"
                 class="benefit-track flex gap-5">


                <!-- BENEFIT 1 (Highlight Card) -->

                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">

                    <div class="h-full p-7 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 text-white border border-blue-500 shadow-xl">

                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-6 text-white">

                            <i class="fa-solid fa-chart-line text-2xl"></i>

                        </div>

                        <h3 class="font-bold text-xl mb-3 text-white">
                            Dashboard Penjual
                        </h3>

                        <p class="text-blue-100 text-sm leading-relaxed">
                            Pantau produk, pesanan, pendapatan,
                            performa toko dan aktivitas pelanggan
                            dari satu dashboard.
                        </p>

                    </div>

                </div>


                <!-- BENEFIT 2 -->

                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">

                    <div class="h-full p-7 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mb-6">

                            <i class="fa-solid fa-bullhorn text-2xl"></i>

                        </div>

                        <h3 class="font-bold text-xl mb-3 text-slate-900">
                            Promosikan Produk
                        </h3>

                        <p class="text-slate-600 text-sm leading-relaxed">
                            Gunakan slot iklan untuk membuat produk
                            lebih mudah ditemukan oleh calon pembeli.
                        </p>
                    </div>
                </div>

                <!-- BENEFIT 3 -->
                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">
                    <div class="h-full p-7 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <i class="fa-solid fa-shop text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-slate-900">
                            Etalase Toko
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Bangun toko profesional dengan profil,
                            katalog produk dan identitas toko sendiri.
                        </p>
                    </div>
                </div>

                <!-- BENEFIT 4 -->
                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">
                    <div class="h-full p-7 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center mb-6">
                            <i class="fa-solid fa-medal text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-slate-900">
                            Badge Penjual
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Dapatkan badge sesuai performa dan paket
                            untuk meningkatkan kepercayaan calon pembeli.
                        </p>
                    </div>
                </div>

                <!-- BENEFIT 5 -->
                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">
                    <div class="h-full p-7 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
                        <div class="w-14 h-14 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center mb-6">
                            <i class="fa-solid fa-magnifying-glass-chart text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-slate-900">
                            Analitik Toko
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Lihat produk yang paling banyak dilihat,
                            diminati dan menghasilkan penjualan.
                        </p>
                    </div>
                </div>

                <!-- BENEFIT 6 -->
                <div class="benefit-slide min-w-[85%] sm:min-w-[48%] lg:min-w-[31.5%]">
                    <div class="h-full p-7 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-6">
                            <i class="fa-solid fa-headset text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-slate-900">
                            Dukungan Penjual
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Dapatkan bantuan ketika mengalami masalah
                            dalam mengelola toko atau pesanan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- DOTS -->
        <div id="benefitDots"
             class="flex justify-center gap-2 mt-8">
            <button class="benefit-dot w-7 h-2 rounded-full bg-blue-600"></button>
            <button class="benefit-dot w-2 h-2 rounded-full bg-slate-300"></button>
            <button class="benefit-dot w-2 h-2 rounded-full bg-slate-300"></button>
            <button class="benefit-dot w-2 h-2 rounded-full bg-slate-300"></button>
        </div>
    </div>
</section>

<section id="kreator"
         class="py-20 lg:py-28 bg-primary text-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div class="reveal">

                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-600 border border-blue-400 text-xs font-bold uppercase tracking-widest">

                    <i class="fa-solid fa-store text-orange-300"></i>

                    Seller Center

                </span>


                <h2 class="font-display text-3xl sm:text-4xl font-bold leading-tight mt-5 mb-5">

                    Punya Karya?

                    <br>

                    <span class="text-orange-300">
                        Saatnya Dijual.
                    </span>

                </h2>


                <p class="text-blue-100 leading-relaxed mb-8 max-w-xl">

                    Jadikan keahlianmu sebagai peluang.
                    Buat toko, upload karya, pasang iklan,
                    dan mulai menjangkau pembeli di Karyaku.

                </p>


                <div class="flex flex-col sm:flex-row gap-3">

                    <a href="{{ url('/auth/login?role=penjual') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-accent hover:bg-accentHover rounded-xl font-bold text-sm transition shadow-lg">

                        <i class="fa-solid fa-store"></i>

                        Masuk sebagai Penjual

                    </a>


                    <a href="#paket-penjual"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold text-sm transition">

                        Lihat Paket

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>


            <!-- STATS -->
            <div class="grid grid-cols-2 gap-4 reveal">
                <div class="bg-white/10 border border-white/10 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-white text-primary flex items-center justify-center mb-5">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="font-display text-2xl font-bold">
                        50+
                    </div>
                    <p class="text-blue-200 text-xs mt-1">
                        Produk Diamond
                    </p>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-orange-500 text-white flex items-center justify-center mb-5">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="font-display text-2xl font-bold">
                        10+
                    </div>
                    <p class="text-blue-200 text-xs mt-1">
                        Slot Iklan
                    </p>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-white text-primary flex items-center justify-center mb-5">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="font-display text-2xl font-bold">
                        Real-time
                    </div>
                    <p class="text-blue-200 text-xs mt-1">
                        Statistik Toko
                    </p>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-orange-500 text-white flex items-center justify-center mb-5">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="font-display text-2xl font-bold">
                        Aman
                    </div>
                    <p class="text-blue-200 text-xs mt-1">
                        Sistem Transaksi
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================= -->
<!-- PAKET PENJUAL -->
<!-- ========================================================= -->
<section id="paket-penjual" class="py-20 lg:py-28 bg-bgLight border-y border-borderSoft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- HEADER -->
        <div class="text-center max-w-3xl mx-auto mb-14 reveal">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100 text-primary text-xs font-bold uppercase tracking-widest">
                <i class="fa-solid fa-crown"></i>
                Seller Plans
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-textMain mt-4 mb-4">
                Pilih Paket yang Cocok
                <span class="text-primary">untuk Toko Kamu</span>
            </h2>
            <p class="text-textMuted text-sm sm:text-base">
                Mulai dari toko kecil sampai toko dengan banyak produk. Upgrade paket kapan saja sesuai perkembangan tokomu.
            </p>
        </div>

        <!-- PACKAGES -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-7 items-stretch">
            <!-- ================================================= -->
            <!-- BRONZE (Tema Cokelat / Bronze) -->
            <!-- ================================================= -->
            <div class="package-card reveal bg-gradient-to-b from-amber-900 via-amber-950 to-stone-900 text-amber-50 border-2 border-amber-600/60 rounded-3xl p-7 shadow-xl flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mb-4 border border-amber-500/30">
                            <i class="fa-solid fa-medal text-xl"></i>
                        </div>
                        <h3 class="font-display text-xl font-bold text-amber-100">Bronze</h3>
                        <p class="text-sm text-amber-200/70 mt-1">Cocok untuk penjual pemula</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-[10px] font-bold uppercase">Starter</span>
                </div>
                <div class="mb-7">
                    <span class="text-3xl font-display font-extrabold text-amber-400">15+</span>
                    <span class="text-amber-200/70 text-sm">produk</span>
                </div>
                <div class="space-y-4 flex-grow">
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        Maksimal 15 produk
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        1 slot iklan
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        Profil toko
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        Dashboard penjualan
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        Statistik dasar
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-amber-500/20 text-amber-400"><i class="fa-solid fa-check text-xs"></i></span>
                        Badge Bronze
                    </div>
                    <div class="flex gap-3 text-sm text-amber-200/30">
                        <span class="check-icon bg-amber-900/40 text-amber-200/30"><i class="fa-solid fa-xmark text-xs"></i></span>
                        Prioritas pencarian
                    </div>
                    <div class="flex gap-3 text-sm text-amber-200/30">
                        <span class="check-icon bg-amber-900/40 text-amber-200/30"><i class="fa-solid fa-xmark text-xs"></i></span>
                        Analitik lanjutan
                    </div>
                </div>
                <a href="{{ url('/auth/login?role=penjual&package=bronze') }}" class="shine inline-block mt-8 w-full text-center py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 font-extrabold text-sm transition-all duration-200 shadow-lg shadow-amber-500/20 focus:ring-4 focus:ring-amber-500/30">Pilih Bronze</a>
            </div>

            <!-- ================================================= -->
            <!-- PLATINUM (Tema Silver / Platinum Slate) -->
            <!-- ================================================= -->
            <div class="package-card popular-package reveal bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 text-slate-100 border-2 border-slate-400/80 rounded-3xl p-7 relative flex flex-col shadow-2xl">
                <!-- POPULAR BADGE -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="px-5 py-2 rounded-full bg-slate-200 text-slate-950 text-xs font-bold shadow-lg flex items-center gap-1">
                        <i class="fa-solid fa-fire text-amber-600"></i>
                        PALING POPULER
                    </span>
                </div>
                <div class="flex justify-between items-start mb-6 mt-2">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-slate-700/60 text-slate-200 flex items-center justify-center mb-4 border border-slate-500/40">
                            <i class="fa-solid fa-gem text-xl"></i>
                        </div>
                        <h3 class="font-display text-xl font-bold text-slate-100">Platinum</h3>
                        <p class="text-sm text-slate-300/80 mt-1">Untuk toko yang berkembang</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-slate-700/60 border border-slate-500/40 text-slate-200 text-[10px] font-bold uppercase">Pro</span>
                </div>
                <div class="mb-7">
                    <span class="text-3xl font-display font-extrabold text-slate-200">35+</span>
                    <span class="text-slate-300/80 text-sm">produk</span>
                </div>
                <div class="space-y-4 flex-grow">
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Minimal hingga 35+ produk
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        3 slot iklan aktif
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Profil toko profesional
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Dashboard penjualan
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Statistik & analitik
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Badge Platinum
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Prioritas pencarian
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Produk lebih mudah ditemukan
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="check-icon bg-slate-700/60 text-slate-200"><i class="fa-solid fa-check text-xs"></i></span>
                        Dukungan penjual
                    </div>
                </div>
                <a href="{{ url('/auth/login?role=penjual&package=platinum') }}" class="shine inline-block mt-8 w-full text-center py-3.5 rounded-xl bg-slate-200 hover:bg-white text-slate-950 font-extrabold text-sm transition-all duration-200 shadow-lg shadow-slate-200/20 focus:ring-4 focus:ring-slate-300/30">Pilih Platinum</a>
            </div>

            <!-- ================================================= -->
            <!-- DIAMOND (Tema Biru Gelap / Navy) -->
            <!-- ================================================= -->
            <div class="package-card reveal bg-gradient-to-b from-blue-950 via-slate-950 to-blue-950 text-white border-2 border-sky-400/70 rounded-3xl p-7 relative flex flex-col overflow-hidden shadow-2xl">
                <div class="absolute -right-20 -top-20 w-56 h-56 bg-sky-500/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center mb-4 border border-sky-500/30">
                                <i class="fa-solid fa-crown text-xl"></i>
                            </div>
                            <h3 class="font-display text-xl font-bold text-sky-100">Diamond</h3>
                            <p class="text-sm text-slate-400 mt-1">Untuk toko profesional</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-sky-500/20 border border-sky-500/30 text-sky-300 text-[10px] font-bold uppercase">Ultimate</span>
                    </div>
                    <div class="mb-7">
                        <span class="text-3xl font-display font-extrabold text-sky-400">50+</span>
                        <span class="text-slate-400 text-sm">produk</span>
                    </div>
                    <div class="space-y-4 flex-grow">
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            50+ produk
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            10+ slot iklan
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Profil toko premium
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Dashboard profesional
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Analitik penjualan lengkap
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Badge Diamond
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Prioritas pencarian tertinggi
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Prioritas promosi
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Statistik pelanggan
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Dukungan prioritas
                        </div>
                        <div class="flex gap-3 text-sm">
                            <span class="check-icon bg-sky-500/20 text-sky-400"><i class="fa-solid fa-check text-xs"></i></span>
                            Fitur toko premium
                        </div>
                    </div>
                    <a href="{{ url('/auth/login?role=penjual&package=diamond') }}" class="shine inline-block relative z-10 mt-8 w-full text-center py-3.5 rounded-xl bg-sky-400 hover:bg-sky-300 text-slate-950 font-extrabold text-sm transition-all duration-200 shadow-lg shadow-sky-400/30 focus:ring-4 focus:ring-sky-400/30">Pilih Diamond</a>
                </div>
            </div>
        </div>

        <!-- SMALL NOTE -->
        <div class="text-center mt-8 text-xs text-textMuted reveal">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Batas produk dan slot iklan mengikuti paket aktif penjual.
        </div>
    </div>
</section>

<!-- ========================================================= -->
<!-- FOOTER -->
<!-- ========================================================= -->
<footer class="bg-white py-5 border-t border-borderSoft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                </div>
                <div>
                    <span class="font-display font-bold text-sm leading-none block">
                        Karyaku<span class="text-accent">.</span>
                    </span>
                    <p class="text-[10px] text-textMuted leading-tight">
                        Digital Marketplace Indonesia
                    </p>
                </div>
            </div>

            <p class="text-xs text-textMuted text-center">
                &copy; 2026 Karyaku. Hak Cipta Dilindungi.
            </p>

            <div class="flex items-center gap-2">
                <a href="#" class="w-8 h-8 rounded-lg bg-slate-50 text-textMuted hover:text-primary hover:bg-slate-100 flex items-center justify-center transition-colors text-xs" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#" class="w-8 h-8 rounded-lg bg-slate-50 text-textMuted hover:text-primary hover:bg-slate-100 flex items-center justify-center transition-colors text-xs" aria-label="Twitter">
                    <i class="fa-brands fa-twitter"></i>
                </a>
                <a href="#" class="w-8 h-8 rounded-lg bg-slate-50 text-textMuted hover:text-primary hover:bg-slate-100 flex items-center justify-center transition-colors text-xs" aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</footer>


<!-- ========================================================= -->
<!-- MODAL KATEGORI -->
<!-- ========================================================= -->

<div id="categoryModal"
     class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-200"
     onclick="closeModal()">

    <div id="modalContent"
         class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden scale-95 transition-transform duration-200"
         onclick="event.stopPropagation()">


        <div class="flex justify-between items-center px-5 py-4 border-b border-borderSoft">

            <h3 id="modalTitle"
                class="font-bold text-base">
                Kategori
            </h3>

            <button onclick="closeModal()"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <div id="modalBody"
             class="p-5 space-y-3 max-h-[60vh] overflow-y-auto bg-slate-50">
        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

    /* ========================================================
       CATEGORY DATA
    ======================================================== */

    const categoryData = {

        canva: {
            title: 'Desain Poster Canva',
            items: [
                {
                    title: 'Poster Promosi',
                    desc: 'Desain visual untuk memasarkan produk, jasa atau promo.'
                },
                {
                    title: 'Poster Event',
                    desc: 'Poster untuk seminar, webinar, konser dan acara komunitas.'
                },
                {
                    title: 'Menu Restoran',
                    desc: 'Desain menu yang modern, rapi dan mudah dibaca.'
                }
            ]
        },

        blender: {
            title: 'Model 3D Blender',
            items: [
                {
                    title: 'Model Karakter',
                    desc: 'Pembuatan karakter 3D untuk game dan animasi.'
                },
                {
                    title: 'Visualisasi Arsitektur',
                    desc: 'Visualisasi bangunan, interior dan eksterior.'
                },
                {
                    title: 'Aset Game',
                    desc: 'Pembuatan objek dan properti 3D untuk kebutuhan game.'
                }
            ]
        },

        logo: {
            title: 'Logo & Branding',
            items: [
                {
                    title: 'Logo Minimalis',
                    desc: 'Logo modern yang mudah dikenali.'
                },
                {
                    title: 'Brand Identity',
                    desc: 'Identitas visual lengkap untuk brand.'
                },
                {
                    title: 'Packaging',
                    desc: 'Desain kemasan produk profesional.'
                }
            ]
        },

        sosmed: {
            title: 'Konten Media Sosial',
            items: [
                {
                    title: 'Instagram Feed',
                    desc: 'Desain feed dan carousel Instagram.'
                },
                {
                    title: 'Reels & TikTok',
                    desc: 'Editing video pendek untuk media sosial.'
                },
                {
                    title: 'Thumbnail',
                    desc: 'Thumbnail YouTube yang menarik perhatian.'
                }
            ]
        },

        uiux: {
            title: 'UI/UX Design',
            items: [
                {
                    title: 'Mobile App',
                    desc: 'Desain aplikasi Android maupun iOS.'
                },
                {
                    title: 'Website',
                    desc: 'Desain landing page dan website.'
                },
                {
                    title: 'Prototype',
                    desc: 'Wireframe dan prototype interaktif.'
                }
            ]
        },

        ilustrasi: {
            title: 'Ilustrasi Digital',
            items: [
                {
                    title: 'Ilustrasi Vektor',
                    desc: 'Ilustrasi digital dengan format vektor.'
                },
                {
                    title: 'Karikatur',
                    desc: 'Karikatur dan ilustrasi karakter.'
                },
                {
                    title: 'Ilustrasi Buku',
                    desc: 'Ilustrasi untuk buku dan cerita anak.'
                }
            ]
        }

    };


    /* ========================================================
       MODAL
    ======================================================== */

    function openModal(categoryId) {

        const data = categoryData[categoryId];

        if (!data) return;

        document.getElementById('modalTitle').textContent =
            data.title;

        const body =
            document.getElementById('modalBody');

        body.innerHTML = '';

        data.items.forEach(item => {

            body.innerHTML += `

                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">

                    <button
                        onclick="toggleAccordion(this)"
                        class="w-full px-4 py-4 text-left flex justify-between items-center font-bold text-sm">

                        ${item.title}

                        <i class="fa-solid fa-chevron-down text-primary transition-transform"></i>

                    </button>

                    <div class="hidden px-4 pb-4 text-xs text-slate-500 leading-relaxed border-t border-slate-100 pt-3">

                        ${item.desc}

                    </div>

                </div>

            `;

        });


        const modal =
            document.getElementById('categoryModal');

        const box =
            document.getElementById('modalContent');

        modal.classList.remove('hidden');

        setTimeout(() => {

            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');

        }, 10);

    }


    function closeModal() {

        const modal =
            document.getElementById('categoryModal');

        const box =
            document.getElementById('modalContent');

        modal.classList.add('opacity-0');
        box.classList.add('scale-95');

        setTimeout(() => {

            modal.classList.add('hidden');

        }, 200);

    }


    function toggleAccordion(button) {

        const content =
            button.nextElementSibling;

        const icon =
            button.querySelector('i');

        content.classList.toggle('hidden');

        icon.classList.toggle('rotate-180');

    }


    /* ========================================================
       MOBILE MENU
    ======================================================== */

    const menuToggle =
        document.getElementById('menuToggle');

    const mobileMenu =
        document.getElementById('mobileMenu');

    const menuIcon =
        document.getElementById('menuIcon');


    menuToggle.addEventListener('click', () => {

        const open =
            mobileMenu.classList.toggle('open');

        menuIcon.classList.toggle(
            'fa-bars',
            !open
        );

        menuIcon.classList.toggle(
            'fa-xmark',
            open
        );

    });


    mobileMenu.querySelectorAll('a')
        .forEach(link => {

            link.addEventListener('click', () => {

                mobileMenu.classList.remove('open');

                menuIcon.classList.add('fa-bars');
                menuIcon.classList.remove('fa-xmark');

            });

        });


    /* ========================================================
       REVEAL ANIMATION
    ======================================================== */

    const revealElements =
        document.querySelectorAll('.reveal');


    const revealObserver =
        new IntersectionObserver(

            entries => {

                entries.forEach(entry => {

                    if (entry.isIntersecting) {

                        entry.target.classList.add('in-view');

                        revealObserver.unobserve(
                            entry.target
                        );

                    }

                });

            },

            {
                threshold: 0.1
            }

        );


    revealElements.forEach(element => {

        revealObserver.observe(element);

    });


    /* ========================================================
       BENEFIT SLIDER
    ======================================================== */

    const track =
        document.getElementById('benefitTrack');

    const prevButton =
        document.getElementById('benefitPrev');

    const nextButton =
        document.getElementById('benefitNext');

    const dots =
        document.querySelectorAll('.benefit-dot');


    let benefitIndex = 0;


    function getSlidesPerView() {

        if (window.innerWidth >= 1024) {

            return 3;

        }

        if (window.innerWidth >= 640) {

            return 2;

        }

        return 1;

    }


    function updateSlider() {

        const slides =
            document.querySelectorAll('.benefit-slide');

        const perView =
            getSlidesPerView();

        const maxIndex =
            Math.max(
                0,
                slides.length - perView
            );

        if (benefitIndex > maxIndex) {

            benefitIndex = maxIndex;

        }

        const slide =
            slides[0];

        if (!slide) return;

        const slideWidth =
            slide.getBoundingClientRect().width;

        const gap = 20;

        track.style.transform =
            `translateX(-${benefitIndex * (slideWidth + gap)}px)`;


        dots.forEach((dot, index) => {

            dot.classList.remove(
                'bg-primary',
                'w-7'
            );

            dot.classList.add(
                'bg-slate-700',
                'w-2'
            );

            if (
                index ===
                Math.min(
                    benefitIndex,
                    dots.length - 1
                )
            ) {

                dot.classList.remove(
                    'bg-slate-700',
                    'w-2'
                );

                dot.classList.add(
                    'bg-primary',
                    'w-7'
                );

            }

        });

    }


    nextButton.addEventListener('click', () => {

        const slides =
            document.querySelectorAll('.benefit-slide');

        const perView =
            getSlidesPerView();

        const maxIndex =
            Math.max(
                0,
                slides.length - perView
            );

        benefitIndex++;

        if (benefitIndex > maxIndex) {

            benefitIndex = 0;

        }

        updateSlider();

    });


    prevButton.addEventListener('click', () => {

        const slides =
            document.querySelectorAll('.benefit-slide');

        const perView =
            getSlidesPerView();

        const maxIndex =
            Math.max(
                0,
                slides.length - perView
            );

        benefitIndex--;

        if (benefitIndex < 0) {

            benefitIndex = maxIndex;

        }

        updateSlider();

    });


    dots.forEach((dot, index) => {

        dot.addEventListener('click', () => {

            benefitIndex = index;

            updateSlider();

        });

    });


    /* AUTO SLIDE */

    let autoSlide =
        setInterval(() => {

            nextButton.click();

        }, 5000);


    const benefitSection =
        document.getElementById('benefit-penjual');


    benefitSection.addEventListener(
        'mouseenter',
        () => clearInterval(autoSlide)
    );


    benefitSection.addEventListener(
        'mouseleave',
        () => {

            autoSlide =
                setInterval(() => {

                    nextButton.click();

                }, 5000);

        }
    );


    window.addEventListener(
        'resize',
        updateSlider
    );


    updateSlider();


    /* ========================================================
       ESC CLOSE MODAL
    ======================================================== */

    document.addEventListener(
        'keydown',
        event => {

            if (event.key === 'Escape') {

                closeModal();

            }

        }
    );

</script>

</body>
</html>
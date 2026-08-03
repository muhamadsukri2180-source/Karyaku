<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Marketplace Jasa Digital Kreator Indonesia</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
                        skyDeep: '#0B3D62',
                        skyDeeper: '#082C48',
                        skyPale: '#EFF8FF',
                        coral: '#FF7A59',
                        coralHover: '#F0623F',
                        mint: '#14B8A6',
                        ink: '#0F2A44',
                        mist: '#E1EFFB'
                    },
                    fontFamily: {
                        display: ['"Sora"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    },
                    boxShadow: {
                        glowCoral: '0 8px 30px -6px rgba(255,122,89,0.55)',
                        glowSky: '0 8px 30px -6px rgba(14,165,233,0.5)',
                        card: '0 4px 24px -6px rgba(11,61,98,0.12)'
                    }
                }
            }
        }
    </script>

    <style>
        :root{
            --sky:#0EA5E9;
            --coral:#FF7A59;
            --mint:#14B8A6;
        }
        html{ scroll-behavior: smooth; overflow-x: hidden; }
        body{ font-family:'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        h1,h2,h3,.font-display{ font-family:'Sora', sans-serif; }
        .font-mono{ font-family:'JetBrains Mono', monospace; }

        :focus-visible{ outline:2px solid var(--coral); outline-offset:3px; border-radius:4px; }

        .reveal{ opacity:0; transform: translateY(26px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.in-view{ opacity:1; transform:none; }

        /* Hero: base solid dark-blue backdrop (bukan gradasi terang) supaya teks putih selalu kontras */
        .hero-bg{
            background: radial-gradient(120% 140% at 15% 10%, #0F4A78 0%, #0B3D62 45%, #082441 100%);
        }
        .dynamic-wash{
            background: linear-gradient(120deg, rgba(14,165,233,0.22), rgba(255,255,255,0.02) 45%, rgba(20,184,166,0.16));
            background-size: 220% 220%;
            animation: washMove 14s ease-in-out infinite;
            mix-blend-mode: soft-light;
        }
        @keyframes washMove{
            0%{ background-position: 0% 30%; }
            50%{ background-position: 100% 70%; }
            100%{ background-position: 0% 30%; }
        }

        .btn{
            position:relative; overflow:hidden; isolation:isolate;
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
        }
        .btn::before{
            content:""; position:absolute; inset:0;
            background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,0.45) 50%, transparent 80%);
            transform: translateX(-120%); transition: transform .6s ease; z-index:1; pointer-events:none;
        }
        .btn:hover::before{ transform: translateX(120%); }
        .btn:hover{ transform: translateY(-3px); }
        .btn:active{ transform: translateY(-1px) scale(0.98); }
        .btn > *{ position:relative; z-index:2; }

        .btn-primary{ background-image: linear-gradient(135deg, var(--coral), #FF9A6B); box-shadow: 0 10px 26px -8px rgba(255,122,89,0.6); }
        .btn-primary:hover{ box-shadow: 0 16px 34px -8px rgba(240,98,63,0.7); }

        .pulse-ring{ position:relative; }
        .pulse-ring::after{
            content:""; position:absolute; inset:-4px; border-radius:9999px; border:2px solid var(--coral);
            animation: pulseRing 2.2s ease-out infinite;
        }
        @keyframes pulseRing{
            0%{ transform:scale(0.85); opacity:.9; }
            100%{ transform:scale(1.9); opacity:0; }
        }

        @media (prefers-reduced-motion: reduce){
            *{ animation:none !important; transition:none !important; }
        }

        #mobileMenu{ max-height:0; overflow:hidden; transition:max-height .35s ease; }
        #mobileMenu.open{ max-height:480px; }

        .grain-overlay{
            position:fixed; inset:0; z-index:70; pointer-events:none;
            opacity:0.04; mix-blend-mode:overlay;
            background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }

        .marquee-track{ display:flex; width:max-content; animation: marqueeScroll 32s linear infinite; }
        .marquee-track:hover{ animation-play-state:paused; }
        @keyframes marqueeScroll{
            from{ transform:translateX(0); }
            to{ transform:translateX(-50%); }
        }

        .ring-fill{ transition: stroke-dashoffset 1.4s ease; }
        .reveal.in-view .ring-fill{ stroke-dashoffset: var(--offset); }
        [data-tilt]{ transition: transform .18s ease-out; }

        /* Signature hero catalog stack */
        .float-card{ animation: floatCard 6s ease-in-out infinite; }
        .float-card.delay-1{ animation-delay: .8s; }
        .float-card.delay-2{ animation-delay: 1.6s; }
        @keyframes floatCard{
            0%, 100%{ transform: translateY(0) rotate(var(--rot,0deg)); }
            50%{ transform: translateY(-14px) rotate(var(--rot,0deg)); }
        }
        .price-tag{
            font-family:'JetBrains Mono', monospace;
        }

        /* Galeri kategori: overlay gelap tipis di bawah gambar supaya label tetap terbaca */
        .cat-card{ position:relative; overflow:hidden; border-radius:1rem; }
        .cat-card img{ transition: transform .5s ease; }
        .cat-card:hover img{ transform: scale(1.08); }
        .cat-overlay{
            position:absolute; inset:0;
            background: linear-gradient(to top, rgba(8,36,65,0.92) 0%, rgba(8,36,65,0.35) 55%, rgba(8,36,65,0) 100%);
        }
    </style>
</head>
<body class="bg-skyPale text-ink antialiased">
    <div class="grain-overlay"></div>

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 bg-gradient-to-r from-skyDeep via-skyDeep to-sky text-white shadow-lg shadow-skyDeep/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="relative w-10 h-10 rounded-xl border-2 border-white/50 flex items-center justify-center bg-white/10 shrink-0">
                    <span class="absolute inline-flex h-full w-full rounded-xl bg-coral/30"></span>
                    <i class="fa-solid fa-layer-group text-lg relative"></i>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-bold leading-none tracking-wide font-display">Karyaku</h1>
                    <span class="text-[10px] sm:text-xs text-sky-100">Marketplace Jasa Digital</span>
                </div>
            </div>

            <nav class="hidden md:flex space-x-6 lg:space-x-8 text-sm font-medium">
                <a href="#hero" class="relative py-1 hover:text-coral transition group">Beranda
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#kategori" class="relative py-1 hover:text-coral transition group">Kategori Jasa
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#cara-kerja" class="relative py-1 hover:text-coral transition group">Cara Kerja
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#karya-pilihan" class="relative py-1 hover:text-coral transition group">Karya Pilihan
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#kreator" class="relative py-1 hover:text-coral transition group">Untuk Kreator
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#kontak" class="relative py-1 hover:text-coral transition group">Kontak
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>

             <a href="#kategori" class="relative py-1 hover:text-coral transition group">Jelajahi Katalog
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>


                <div class="flex items-center gap-3">
                <a href="{{ route('auth.login') }}" class="btn btn-primary pulse-ring hidden sm:inline-flex items-center gap-2 text-white px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold">
                    <i class="fa-solid fa-bag-shopping"></i>
                    Masuk
                </a>
                <button id="menuToggle" aria-label="Buka menu" aria-expanded="false" class="md:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition">
                    <i class="fa-solid fa-bars text-lg" id="menuIcon"></i>
                </button>

            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden bg-skyDeeper/95 border-t border-white/10">
            <nav class="flex flex-col px-6 py-4 space-y-3 text-sm font-medium">
                <a href="#hero" class="hover:text-coral transition py-1">Beranda</a>
                <a href="#kategori" class="hover:text-coral transition py-1">Kategori Jasa</a>
                <a href="#cara-kerja" class="hover:text-coral transition py-1">Cara Kerja</a>
                <a href="#karya-pilihan" class="hover:text-coral transition py-1">Karya Pilihan</a>
                <a href="#kreator" class="hover:text-coral transition py-1">Untuk Kreator</a>
                <a href="#kontak" class="hover:text-coral transition py-1">Kontak</a>
                <a href="{{ route('auth.login') }}" class="btn btn-primary inline-flex items-center justify-center gap-2 text-white px-5 py-2.5 rounded-xl text-sm font-bold mt-2">
                    <i class="fa-solid fa-bag-shopping"></i> Masuk
                </a>
            </nav>
        </div>
    </header>

   <!-- SECTION 1: HERO -->
    <section id="hero" class="relative overflow-hidden min-h-[70vh] sm:min-h-[75vh] flex items-center py-16 hero-bg">
        <div class="absolute inset-0 dynamic-wash"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_0.85fr] gap-12 items-center">
                <div class="max-w-2xl">

                    <h1 class="font-display text-3xl sm:text-5xl md:text-6xl font-bold mb-6 leading-[1.1] text-white reveal">
                        Karyaku: Beli & Jual
                        <span class="bg-gradient-to-r from-[#FFE1CE] via-[#FFB29A] to-[#FF9A6B] bg-clip-text text-transparent">Karya Digital Siap Pakai</span>
                    </h1>

                    <p class="text-base sm:text-xl text-white/95 max-w-xl mb-8 font-medium leading-relaxed reveal">
                        Poster Canva, model &amp; animasi 3D Blender, logo, konten sosial media — cari kreator terbaik atau jual hasil karyamu langsung ke pembeli, dengan pembayaran yang aman.
                    </p>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-10 reveal">
                        <a href="#kategori" class="btn btn-primary pulse-ring inline-flex items-center justify-center gap-2 text-white px-6 sm:px-8 py-3.5 rounded-xl text-sm sm:text-base font-bold">
                            <i class="fa-solid fa-cart-shopping"></i> Mulai Belanja Karya
                        </a>
                        <a href="#kreator" class="btn inline-flex items-center justify-center gap-2 bg-white text-skyDeep px-6 sm:px-8 py-3.5 rounded-xl text-sm sm:text-base font-bold shadow-lg hover:bg-skyPale transition">
                            <i class="fa-solid fa-store"></i> Buka Etalase Jasa
                        </a>
                    </div>
                </div>

                <!-- SIGNATURE: floating catalog stack -->
                <div class="relative reveal hidden lg:block h-[420px]">
                    <div class="float-card absolute top-0 left-6 w-56 bg-white rounded-2xl shadow-2xl p-3 rotate-[-6deg]" style="--rot:-6deg;">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=500&q=80" alt="Poster promosi karya kreator" class="rounded-xl h-32 w-full object-cover">
                        <div class="flex items-center justify-between mt-2 px-1">
                            <span class="text-[11px] font-bold text-ink">Poster Promo Kafe</span>
                            <span class="price-tag text-[11px] font-bold text-coral">Rp75rb</span>
                        </div>
                    </div>
                    <div class="float-card delay-1 absolute top-32 right-2 w-52 bg-white rounded-2xl shadow-2xl p-3 rotate-[5deg]" style="--rot:5deg;">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=500&q=80" alt="Model 3D karya kreator" class="rounded-xl h-28 w-full object-cover">
                        <div class="flex items-center justify-between mt-2 px-1">
                            <span class="text-[11px] font-bold text-ink">Aset 3D Low-Poly</span>
                            <span class="price-tag text-[11px] font-bold text-coral">Rp250rb</span>
                        </div>
                    </div>
                    <div class="float-card delay-2 absolute bottom-2 left-16 w-52 bg-white rounded-2xl shadow-2xl p-3 rotate-[3deg]" style="--rot:3deg;">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=500&q=80" alt="Desain logo karya kreator" class="rounded-xl h-28 w-full object-cover">
                        <div class="flex items-center justify-between mt-2 px-1">
                            <span class="text-[11px] font-bold text-ink">Logo Brand Minimalis</span>
                            <span class="price-tag text-[11px] font-bold text-coral">Rp120rb</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- SECTION 3: KATEGORI JASA (galeri karya asli penjual) -->
    <section id="kategori" class="relative overflow-hidden py-16 sm:py-20 bg-skyDeep text-white">
        <div class="pointer-events-none absolute -top-20 -right-20 w-80 h-80 bg-sky/25 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 w-72 h-72 bg-coral/15 rounded-full blur-3xl"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <h2 class="font-display text-2xl sm:text-3xl font-bold mb-4">Semua Jasa Digital, Satu Etalase</h2>
                <p class="text-sky-100 text-sm md:text-base leading-relaxed">
                    Dari poster promosi sampai aset 3D untuk game, temukan kreator yang cocok dengan kebutuhanmu.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Contoh karya desain poster Canva" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-coral/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-image"></i></span>
                        <p class="text-sm font-bold text-white">Desain Poster Canva</p>
                    </div>
                </a>
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Contoh karya model 3D Blender" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-sky/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-cube"></i></span>
                        <p class="text-sm font-bold text-white">Model & Animasi 3D Blender</p>
                    </div>
                </a>
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Contoh karya logo dan branding" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-mint/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-signature"></i></span>
                        <p class="text-sm font-bold text-white">Logo & Branding</p>
                    </div>
                </a>
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80" alt="Contoh karya konten media sosial" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-coral/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-hashtag"></i></span>
                        <p class="text-sm font-bold text-white">Konten Media Sosial</p>
                    </div>
                </a>
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80" alt="Contoh karya UI/UX design" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-sky/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-pen-ruler"></i></span>
                        <p class="text-sm font-bold text-white">UI/UX Design</p>
                    </div>
                </a>
                <a href="#" class="reveal cat-card group h-48 sm:h-56 block">
                    <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80" alt="Contoh karya ilustrasi digital" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-mint/90 flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-paintbrush"></i></span>
                        <p class="text-sm font-bold text-white">Ilustrasi Digital</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 4: CARA KERJA -->
    <section id="cara-kerja" class="relative overflow-hidden py-16 sm:py-20 bg-skyPale">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <h3 class="text-sky font-bold text-sm tracking-wider uppercase mb-2">Langkah Mudah</h3>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Cara Belanja Karya di Karyaku</h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">Tiga langkah dari mencari kreator hingga karya sampai ke tanganmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="reveal relative bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-coral/15 text-coral border border-coral/30 rounded-xl flex items-center justify-center text-xl sm:text-2xl font-bold mb-6 font-display">1</div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 text-slate-900">Cari & Pilih Kreator</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Jelajahi katalog karya berdasarkan kategori, harga, dan rating kreator yang sesuai kebutuhanmu.</p>
                </div>
                <div class="reveal relative bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-sky/15 text-sky border border-sky/30 rounded-xl flex items-center justify-center text-xl sm:text-2xl font-bold mb-6 font-display">2</div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 text-slate-900">Pesan & Bayar Aman</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Sampaikan detail kebutuhan lalu bayar lewat sistem escrow — dana tertahan sampai kamu setujui hasilnya.</p>
                </div>
                <div class="reveal relative bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-mint/15 text-mint border border-mint/30 rounded-xl flex items-center justify-center text-xl sm:text-2xl font-bold mb-6 font-display">3</div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 text-slate-900">Terima Hasil & Beri Rating</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Unduh hasil karya, ajukan revisi jika perlu, lalu beri rating untuk bantu kreator lain menemukan pembeli.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: KARYA PILIHAN -->
    <section id="karya-pilihan" class="relative overflow-hidden py-16 sm:py-20 bg-white">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <h3 class="text-sky font-bold text-sm tracking-wider uppercase mb-2">Karya Pilihan</h3>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Paling Diminati Minggu Ini</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="reveal group rounded-xl overflow-hidden shadow-sm border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-coral"></div>
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Karya poster promosi" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full">Terlaris</span>
                            <span class="price-tag text-xs font-bold text-coral">Rp75.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mt-2">Desain Poster Promosi Kafe</h4>
                        <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-user mr-1"></i> oleh Dinda Studio · <i class="fa-solid fa-star text-amber-400 ml-1"></i> 4.9</p>
                    </div>
                </div>
                <div class="reveal group rounded-xl overflow-hidden shadow-sm border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-sky"></div>
                    <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Karya model 3D" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full">Terlaris</span>
                            <span class="price-tag text-xs font-bold text-coral">Rp480.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mt-2">Model 3D Karakter Game</h4>
                        <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-user mr-1"></i> oleh Rangga.blend · <i class="fa-solid fa-star text-amber-400 ml-1"></i> 5.0</p>
                    </div>
                </div>
                <div class="reveal group rounded-xl overflow-hidden shadow-sm border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-mint"></div>
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Karya logo brand" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full">Terlaris</span>
                            <span class="price-tag text-xs font-bold text-coral">Rp150.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mt-2">Paket Logo & Brand Kit</h4>
                        <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-user mr-1"></i> oleh Kirana Design · <i class="fa-solid fa-star text-amber-400 ml-1"></i> 4.8</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: UNTUK KREATOR -->
    <section id="kreator" class="relative overflow-hidden py-16 sm:py-20 bg-skyPale">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="reveal grid grid-cols-1 md:grid-cols-2 gap-10 items-center bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-10">
                <div>
                    <span class="inline-flex items-center gap-2 bg-coral/10 text-coral px-3 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase mb-4">
                        <i class="fa-solid fa-store"></i> Untuk Kreator
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mb-4">Jual Karyamu, Dapatkan Pembeli Baru Setiap Hari</h2>
                    <p class="text-slate-600 text-sm sm:text-base mb-6 leading-relaxed">
                        Buka etalase gratis, unggah portofolio, tentukan harga, dan terima pesanan tanpa perlu platform terpisah untuk desain poster, model 3D, atau jasa kreatif lainnya.
                    </p>
                    <a href="{{ route('auth.register') }}" class="btn btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-bold">
                        <i class="fa-solid fa-rocket"></i> Buka Etalase Sekarang
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-skyPale rounded-2xl p-5 text-center">
                        <i class="fa-solid fa-wallet text-2xl text-coral mb-2"></i>
                        <p class="text-xs font-semibold text-slate-700">Pencairan Dana Cepat</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-5 text-center">
                        <i class="fa-solid fa-percent text-2xl text-sky mb-2"></i>
                        <p class="text-xs font-semibold text-slate-700">Biaya Platform Transparan</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-5 text-center">
                        <i class="fa-solid fa-chart-line text-2xl text-mint mb-2"></i>
                        <p class="text-xs font-semibold text-slate-700">Statistik Etalase Lengkap</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-5 text-center">
                        <i class="fa-solid fa-comments text-2xl text-coral mb-2"></i>
                        <p class="text-xs font-semibold text-slate-700">Chat Langsung ke Pembeli</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 7: CONTACT US -->
    <section id="kontak" class="relative overflow-hidden py-16 sm:py-20 bg-white">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="reveal grid grid-cols-1 md:grid-cols-2 gap-12 bg-skyPale p-6 sm:p-12 rounded-3xl shadow-card border border-slate-200">
                <div>
                    <h3 class="text-sky font-bold text-sm tracking-wider uppercase mb-2">Hubungi Kami</h3>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mb-4">Ada Pertanyaan tentang Karyaku?</h2>
                    <p class="text-slate-600 text-sm mb-8">Tim support Karyaku siap membantu pembeli maupun kreator, 24/7.</p>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-coral/15 text-coral flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Email Resmi</p>
                                <p class="font-bold text-slate-800">support@karyaku.id</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky/15 text-sky flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Hotline / WhatsApp</p>
                                <p class="font-bold text-slate-800">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" placeholder="nama@email.com" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pesan</label>
                        <textarea rows="4" placeholder="Tuliskan pertanyaan atau kebutuhan jasamu..." class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky"></textarea>
                    </div>
                    <button type="button" class="btn w-full bg-gradient-to-r from-coral to-skyDeep text-white py-3.5 rounded-xl font-bold shadow-glowSky">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-skyDeeper text-slate-400 py-8 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs space-y-2">
            <p>&copy; 2026 Karyaku. Hak Cipta Dilindungi.</p>
            <p>Ruang Karya Digital Kreator Indonesia.</p>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');
        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            menuIcon.classList.toggle('fa-bars', !isOpen);
            menuIcon.classList.toggle('fa-xmark', isOpen);
        });
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuIcon.classList.add('fa-bars');
                menuIcon.classList.remove('fa-xmark');
            });
        });

        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach(el => revealObserver.observe(el));

        // Rating counter (dijalankan hanya kalau elemennya memang ada di halaman)
        const ratingEl = document.getElementById('ratingNumber');
        if (ratingEl) {
            const ratingObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseFloat(ratingEl.dataset.target);
                        let current = 0;
                        const step = target / 40;
                        const timer = setInterval(() => {
                            current += step;
                            if (current >= target) { current = target; clearInterval(timer); }
                            ratingEl.textContent = current.toFixed(1);
                        }, 25);
                        ratingObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.4 });
            ratingObserver.observe(ratingEl);
        }
    </script>
</body>
</html>
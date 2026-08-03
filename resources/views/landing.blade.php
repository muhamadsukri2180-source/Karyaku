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

        .float-card{ animation: floatCard 6s ease-in-out infinite; }
        .float-card.delay-1{ animation-delay: .8s; }
        .float-card.delay-2{ animation-delay: 1.6s; }
        @keyframes floatCard{
            0%, 100%{ transform: translateY(0) rotate(var(--rot,0deg)); }
            50%{ transform: translateY(-14px) rotate(var(--rot,0deg)); }
        }

        .price-tag{ font-family:'JetBrains Mono', monospace; }

        .cat-card{ position:relative; overflow:hidden; border-radius:1rem; cursor: pointer; }
        .cat-card img{ transition: transform .5s ease; }
        .cat-card:hover img{ transform: scale(1.08); }
        .cat-overlay{
            position:absolute; inset:0;
            background: linear-gradient(to top, rgba(8,36,65,0.92) 0%, rgba(8,36,65,0.45) 55%, rgba(8,36,65,0) 100%);
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
                    <span class="text-[10px] sm:text-xs text-sky-100">Marketplace Jasa</span>
                </div>
            </div>

            <nav class="hidden md:flex space-x-6 lg:space-x-8 text-sm font-medium">
                <a href="#hero" class="relative py-1 hover:text-coral transition group">Beranda
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#kategori" class="relative py-1 hover:text-coral transition group">Kategori
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#cara-kerja" class="relative py-1 hover:text-coral transition group">Cara Kerja
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#karya-pilihan" class="relative py-1 hover:text-coral transition group">Karya Pilihan
                    <span class="absolute left-1/2 -translate-x-1/2 bottom-0 h-0.5 w-0 bg-coral transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <!-- Tombol MASUK yang diubah menjadi solid/full seperti tombol utama -->
                <a href="auth/login" class="btn btn-primary hidden sm:inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-coral/30 hover:shadow-coral/50 transition-all duration-300">
                    <i class="fa-solid fa-right-to-bracket text-sm"></i> MASUK
                </a>
                <button id="menuToggle" aria-label="Buka menu" aria-expanded="false" class="md:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-bars text-lg text-white" id="menuIcon"></i>
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
                <a href="auth/login" class="btn btn-primary inline-flex items-center justify-center gap-2 text-white px-5 py-2.5 rounded-xl text-sm font-bold mt-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </a>
            </nav>
        </div>
    </header>

    <!-- SECTION 1: HERO -->
    <section id="hero" class="relative overflow-hidden py-16 md:py-24 lg:py-32 flex items-center hero-bg">
        <div class="absolute inset-0 dynamic-wash"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-10 items-center">
                
                <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0">
                    <h1 class="font-display text-4xl lg:text-5xl xl:text-6xl font-bold mb-6 leading-tight text-white reveal">
                        Karyaku: Beli & Jual
                        <span class="bg-gradient-to-r from-[#FFE1CE] via-[#FFB29A] to-[#FF9A6B] bg-clip-text text-transparent block sm:inline">Karya Digital Siap Pakai</span>
                    </h1>
                    <p class="text-base sm:text-lg text-white/95 max-w-xl mx-auto lg:mx-0 mb-8 font-medium leading-relaxed reveal">
                        Poster Canva, model & animasi 3D Blender, logo, konten sosial media — cari kreator terbaik atau jual hasil karyamu langsung ke pembeli, dengan pembayaran yang aman.
                    </p>
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-8 lg:mb-10 justify-center lg:justify-start reveal">
                        <a href="#kategori" class="btn btn-primary inline-flex items-center justify-center gap-2 text-white px-6 sm:px-8 py-3.5 rounded-xl text-sm font-bold">
                            <i class="fa-solid fa-cart-shopping"></i> Mulai Belanja Karya
                        </a>
                        <a href="#kreator" class="btn inline-flex items-center justify-center gap-2 bg-white text-skyDeep px-6 sm:px-8 py-3.5 rounded-xl text-sm font-bold shadow-lg hover:bg-skyPale transition">
                            <i class="fa-solid fa-store"></i> Buka Etalase Jasa
                        </a>
                    </div>
                </div>

                <!-- SIGNATURE: floating catalog stack -->
                <div class="relative reveal hidden md:block h-[380px] lg:h-[420px] scale-90 lg:scale-100 origin-center lg:origin-right mx-auto w-full max-w-sm">
                    <div class="float-card absolute top-0 left-0 lg:left-6 w-52 bg-white rounded-2xl shadow-2xl p-2.5 rotate-[-6deg]" style="--rot:-6deg;">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=500&q=80" alt="Poster promosi karya kreator" class="rounded-xl h-36 w-full object-cover">
                    </div>
                    <div class="float-card delay-1 absolute top-32 right-0 lg:right-2 w-48 bg-white rounded-2xl shadow-2xl p-2.5 rotate-[5deg]" style="--rot:5deg;">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=500&q=80" alt="Model 3D karya kreator" class="rounded-xl h-32 w-full object-cover">
                    </div>
                    <div class="float-card delay-2 absolute bottom-2 left-10 lg:left-16 w-48 bg-white rounded-2xl shadow-2xl p-2.5 rotate-[3deg]" style="--rot:3deg;">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=500&q=80" alt="Desain logo karya kreator" class="rounded-xl h-32 w-full object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3: KATEGORI JASA -->
    <section id="kategori" class="relative overflow-hidden py-16 lg:py-24 bg-skyDeep text-white">
        <div class="pointer-events-none absolute -top-20 -right-20 w-80 h-80 bg-sky/25 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 w-72 h-72 bg-coral/15 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <h2 class="font-display text-2xl sm:text-3xl font-bold mb-4">Semua Jasa Digital, Satu Etalase</h2>
                <p class="text-sky-100 text-sm sm:text-base leading-relaxed">
                    Dari poster promosi sampai aset 3D untuk game, temukan kreator yang cocok dengan kebutuhanmu.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                <!-- Kategori 1: Canva -->
                <div onclick="openModal('canva')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Desain Poster Canva" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-coral/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-image"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">Desain Poster Canva</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-coral group-hover:border-coral">Lihat</span>
                    </div>
                </div>

                <!-- Kategori 2: 3D Blender -->
                <div onclick="openModal('blender')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Model 3D Blender" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-sky/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-cube"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">Model & Animasi 3D Blender</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-sky group-hover:border-sky">Lihat</span>
                    </div>
                </div>

                <!-- Kategori 3: Logo & Branding -->
                <div onclick="openModal('logo')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Logo & Branding" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-mint/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-signature"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">Logo & Branding</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-mint group-hover:border-mint">Lihat</span>
                    </div>
                </div>

                <!-- Kategori 4: Sosial Media -->
                <div onclick="openModal('sosmed')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80" alt="Konten Media Sosial" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-coral/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-hashtag"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">Konten Media Sosial</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-coral group-hover:border-coral">Lihat</span>
                    </div>
                </div>

                <!-- Kategori 5: UI/UX -->
                <div onclick="openModal('uiux')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80" alt="UI/UX Design" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-sky/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-pen-ruler"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">UI/UX Design</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-sky group-hover:border-sky">Lihat</span>
                    </div>
                </div>

                <!-- Kategori 6: Ilustrasi -->
                <div onclick="openModal('ilustrasi')" class="reveal cat-card group h-56 sm:h-60 block">
                    <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80" alt="Ilustrasi Digital" class="w-full h-full object-cover">
                    <div class="cat-overlay"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-mint/90 flex items-center justify-center text-base shrink-0"><i class="fa-solid fa-paintbrush"></i></span>
                            <p class="text-sm sm:text-base font-bold text-white leading-tight">Ilustrasi Digital</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-bold text-white transition border border-white/30 shrink-0 group-hover:bg-mint group-hover:border-mint">Lihat</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: CARA KERJA -->
    <section id="cara-kerja" class="relative overflow-hidden py-16 lg:py-24 bg-skyPale">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16 reveal">
                <h3 class="text-sky font-bold text-sm tracking-wider uppercase mb-2">Langkah Mudah</h3>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Cara Belanja Karya di Karyaku</h2>
                <p class="text-slate-600 text-sm sm:text-base mt-3">Tiga langkah dari mencari kreator hingga karya sampai ke tanganmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <div class="reveal relative bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-100 text-center md:text-left">
                    <div class="w-12 h-12 bg-coral/15 text-coral border border-coral/30 rounded-xl flex items-center justify-center text-xl font-bold mb-5 font-display mx-auto md:mx-0">1</div>
                    <h3 class="text-lg font-bold mb-3 text-slate-900">Cari & Pilih Kreator</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Jelajahi katalog karya berdasarkan kategori, harga, dan rating kreator yang sesuai kebutuhanmu.</p>
                </div>
                <div class="reveal relative bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-100 text-center md:text-left">
                    <div class="w-12 h-12 bg-sky/15 text-sky border border-sky/30 rounded-xl flex items-center justify-center text-xl font-bold mb-5 font-display mx-auto md:mx-0">2</div>
                    <h3 class="text-lg font-bold mb-3 text-slate-900">Pesan & Bayar Aman</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Sampaikan detail kebutuhan lalu bayar lewat sistem escrow — dana tertahan sampai kamu setujui hasilnya.</p>
                </div>
                <div class="reveal relative bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-100 text-center md:text-left">
                    <div class="w-12 h-12 bg-mint/15 text-mint border border-mint/30 rounded-xl flex items-center justify-center text-xl font-bold mb-5 font-display mx-auto md:mx-0">3</div>
                    <h3 class="text-lg font-bold mb-3 text-slate-900">Terima Hasil & Beri Rating</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Unduh hasil karya, ajukan revisi jika perlu, lalu beri rating untuk bantu kreator lain menemukan pembeli.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: KARYA PILIHAN / PRODUK -->
    <section id="karya-pilihan" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <h3 class="text-sky font-bold text-sm tracking-wider uppercase mb-2">Katalog Unggulan</h3>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Karya Pilihan Kreator</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="reveal group rounded-2xl overflow-hidden shadow-sm hover:shadow-card transition-shadow border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-coral"></div>
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Karya poster promosi" class="w-full h-52 object-cover">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] sm:text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full uppercase tracking-wide">Terlaris</span>
                            <span class="price-tag text-sm font-bold text-coral">Rp75.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug">Desain Poster Promosi Kafe</h4>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5"><i class="fa-solid fa-user"></i> Dinda Studio &bull; <i class="fa-solid fa-star text-amber-400"></i> 4.9</p>
                    </div>
                </div>
                <div class="reveal group rounded-2xl overflow-hidden shadow-sm hover:shadow-card transition-shadow border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-sky"></div>
                    <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Karya model 3D" class="w-full h-52 object-cover">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] sm:text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full uppercase tracking-wide">Terlaris</span>
                            <span class="price-tag text-sm font-bold text-coral">Rp480.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug">Model 3D Karakter Game</h4>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5"><i class="fa-solid fa-user"></i> Rangga.blend &bull; <i class="fa-solid fa-star text-amber-400"></i> 5.0</p>
                    </div>
                </div>
                <div class="reveal group rounded-2xl overflow-hidden shadow-sm hover:shadow-card transition-shadow border border-slate-100 bg-skyPale">
                    <div class="h-1 bg-mint"></div>
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Karya logo brand" class="w-full h-52 object-cover">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] sm:text-xs font-bold text-mint bg-mint/10 px-2.5 py-1 rounded-full uppercase tracking-wide">Terlaris</span>
                            <span class="price-tag text-sm font-bold text-coral">Rp150.000</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug">Paket Logo & Brand Kit</h4>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5"><i class="fa-solid fa-user"></i> Kirana Design &bull; <i class="fa-solid fa-star text-amber-400"></i> 4.8</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: UNTUK KREATOR -->
    <section id="kreator" class="relative overflow-hidden py-16 lg:py-24 bg-skyPale">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="reveal grid grid-cols-1 lg:grid-cols-2 gap-10 items-center bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-10 lg:p-14">
                <div class="text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 bg-coral/10 text-coral px-3 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase mb-4">
                        <i class="fa-solid fa-store"></i> Untuk Kreator
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mb-4">Jual Karyamu, Dapatkan Pembeli Baru Setiap Hari</h2>
                    <p class="text-slate-600 text-sm sm:text-base mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Buka etalase gratis, unggah portofolio, tentukan harga, dan terima pesanan tanpa perlu platform terpisah untuk desain poster, model 3D, atau jasa kreatif lainnya.
                    </p>
                    <a href="#kategori" class="btn btn-primary inline-flex items-center gap-2 text-white px-6 py-3.5 rounded-xl text-sm font-bold">
                        <i class="fa-solid fa-rocket"></i> Buka Etalase Sekarang
                    </a>
                </div>
                
                <div class="grid grid-cols-2 gap-3 sm:gap-5">
                    <div class="bg-skyPale rounded-2xl p-4 sm:p-6 text-center border border-sky/5">
                        <i class="fa-solid fa-wallet text-2xl sm:text-3xl text-coral mb-3"></i>
                        <p class="text-xs sm:text-sm font-semibold text-slate-700">Pencairan Dana Cepat</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-4 sm:p-6 text-center border border-sky/5">
                        <i class="fa-solid fa-percent text-2xl sm:text-3xl text-sky mb-3"></i>
                        <p class="text-xs sm:text-sm font-semibold text-slate-700">Biaya Transparan</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-4 sm:p-6 text-center border border-sky/5">
                        <i class="fa-solid fa-chart-line text-2xl sm:text-3xl text-mint mb-3"></i>
                        <p class="text-xs sm:text-sm font-semibold text-slate-700">Statistik Lengkap</p>
                    </div>
                    <div class="bg-skyPale rounded-2xl p-4 sm:p-6 text-center border border-sky/5">
                        <i class="fa-solid fa-comments text-2xl sm:text-3xl text-coral mb-3"></i>
                        <p class="text-xs sm:text-sm font-semibold text-slate-700">Chat Langsung</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-skyDeeper text-slate-400 py-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center justify-center space-y-3">
            <div class="flex items-center space-x-2 text-white mb-2">
                <i class="fa-solid fa-layer-group text-coral"></i>
                <span class="font-display font-bold text-lg">Karyaku</span>
            </div>
            <p class="text-xs sm:text-sm text-center">&copy; 2026 Karyaku. Hak Cipta Dilindungi.</p>
            <p class="text-xs text-slate-500 text-center">Ruang Karya Digital Kreator Indonesia.</p>
        </div>
    </footer>

    <!-- MODAL POPUP KATEGORI -->
    <div id="categoryModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-skyDeeper/70 p-4 transition-opacity duration-300 opacity-0" onclick="closeModal()">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" id="modalContent" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-skyPale">
                <h3 id="modalTitle" class="font-display font-bold text-lg text-skyDeep">Judul Kategori</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 hover:bg-coral hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="modalBody" class="p-5 space-y-3 max-h-[60vh] overflow-y-auto">
            </div>
        </div>
    </div>

    <!-- SCRIPT UTAMA -->
    <script>
        const categoryData = {
            'canva': {
                title: 'Desain Poster Canva',
                color: 'coral',
                items: [
                    { title: 'Poster Promosi & Diskon', desc: 'Desain visual kreatif untuk memasarkan produk, jasa, atau mengumumkan diskon spesial toko Anda.' },
                    { title: 'Poster Event / Webinar', desc: 'Media informasi digital untuk pendaftaran konser, seminar online, atau acara komunitas.' },
                    { title: 'Menu Restoran / Kafe', desc: 'Pembuatan daftar menu yang estetik dan mudah dibaca oleh pelanggan.' }
                ]
            },
            'blender': {
                title: 'Model & Animasi 3D Blender',
                color: 'sky',
                items: [
                    { title: 'Model Karakter 3D', desc: 'Pembuatan karakter manusia, hewan, atau maskot unik untuk keperluan game dan animasi.' },
                    { title: 'Visualisasi Arsitektur', desc: 'Render 3D realistis untuk desain interior ruangan maupun eksterior bangunan.' },
                    { title: 'Aset Prop & Objek', desc: 'Pembuatan model benda mati (senjata, mobil, furniture) dengan resolusi low-poly maupun high-poly.' }
                ]
            },
            'logo': {
                title: 'Logo & Branding',
                color: 'mint',
                items: [
                    { title: 'Logo Minimalis', desc: 'Desain logo yang simpel, modern, dan mudah diingat (memorable) oleh pelanggan.' },
                    { title: 'Brand Identity Guideline', desc: 'Dokumen lengkap yang berisi aturan penggunaan warna, tipografi, dan gaya visual brand Anda.' },
                    { title: 'Desain Kemasan (Packaging)', desc: 'Rancangan visual untuk kotak produk atau label botol agar terlihat profesional di pasaran.' }
                ]
            },
            'sosmed': {
                title: 'Konten Media Sosial',
                color: 'coral',
                items: [
                    { title: 'Feed & Story Instagram', desc: 'Templat postingan grid atau carousel edukasi yang berkesinambungan dan estetik.' },
                    { title: 'Edit Video Reels / TikTok', desc: 'Jasa memotong dan mengedit video pendek vertikal dengan subtitle dinamis yang viral.' },
                    { title: 'Thumbnail YouTube', desc: 'Desain sampul video yang menarik perhatian (clickbait) namun tetap relevan dengan isi video.' }
                ]
            },
            'uiux': {
                title: 'UI/UX Design',
                color: 'sky',
                items: [
                    { title: 'Desain Aplikasi Mobile', desc: 'Merancang antarmuka pengguna (User Interface) untuk aplikasi iOS atau Android yang mudah digunakan.' },
                    { title: 'Desain Landing Page', desc: 'Pembuatan layout website satu halaman yang dioptimalkan untuk konversi dan penjualan.' },
                    { title: 'Wireframing & Prototyping', desc: 'Kerangka dasar aplikasi yang bisa diklik (interaktif) sebelum masuk ke tahap pemrograman.' }
                ]
            },
            'ilustrasi': {
                title: 'Ilustrasi Digital',
                color: 'mint',
                items: [
                    { title: 'Ilustrasi Vektor', desc: 'Gambar digital berbasis garis yang bersih dan rapi, cocok untuk dicetak besar tanpa pecah.' },
                    { title: 'Karikatur & Wajah', desc: 'Lukisan digital wajah dengan gaya kartun atau semi-realistis, sangat cocok untuk kado.' },
                    { title: 'Ilustrasi Buku Anak', desc: 'Pembuatan adegan cerita dengan warna-warni ceria yang ramah untuk bacaan anak-anak.' }
                ]
            }
        };

        function openModal(categoryId) {
            const data = categoryData[categoryId];
            if(!data) return;

            document.getElementById('modalTitle').textContent = data.title;
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            data.items.forEach(item => {
                let accent = data.color === 'coral' ? 'text-coral' : data.color === 'sky' ? 'text-sky' : 'text-mint';
                let bgAccent = data.color === 'coral' ? 'bg-coral/5 hover:bg-coral/10' : data.color === 'sky' ? 'bg-sky/5 hover:bg-sky/10' : 'bg-mint/5 hover:bg-mint/10';

                modalBody.innerHTML += `
                    <div class="border border-slate-100 rounded-xl overflow-hidden bg-white shadow-sm">
                        <button onclick="toggleAccordion(this)" class="w-full text-left px-4 py-3.5 flex justify-between items-center font-bold text-sm text-slate-800 ${bgAccent} transition">
                            ${item.title}
                            <i class="fa-solid fa-chevron-down ${accent} transition-transform duration-300 transform"></i>
                        </button>
                        <div class="accordion-content px-4 py-3 text-sm text-slate-500 hidden border-t border-slate-100 bg-slate-50/50 leading-relaxed">
                            ${item.desc}
                        </div>
                    </div>
                `;
            });

            const modal = document.getElementById('categoryModal');
            const modalBox = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            void modal.offsetWidth; 
            modal.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
        }

        function closeModal() {
            const modal = document.getElementById('categoryModal');
            const modalBox = document.getElementById('modalContent');
            
            modal.classList.add('opacity-0');
            modalBox.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function toggleAccordion(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('i');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

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
    </script>
</body>
</html>
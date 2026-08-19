<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Marketplace Jasa Digital Kreator Indonesia</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Sesuai dengan Dashboard (Biru Vibrant & Oranye)
                        primary: '#2563EB',       // Blue 600 (Vibrant Blue)
                        primaryHover: '#1D4ED8',  // Blue 700
                        accent: '#F97316',        // Orange 500
                        accentHover: '#EA580C',   // Orange 600
                        bgLight: '#F8FAFC',       // Slate 50 (Background utama)
                        cardWhite: '#FFFFFF',     // White Cards
                        textMain: '#0F172A',      // Slate 900
                        textMuted: '#64748B',     // Slate 500
                        borderSoft: '#E2E8F0'     // Slate 200
                    },
                    fontFamily: {
                        display: ['"Sora"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        }
    </script>

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        h1, h2, h3, h4, h5, .font-display { font-family: 'Sora', sans-serif; }
        
        /* Focus State */
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

        /* Animasi reveal ringan */
        .reveal { opacity: 0; transform: translateY(15px); transition: opacity 0.5s ease-out, transform 0.5s ease-out; }
        .reveal.in-view { opacity: 1; transform: none; }

        /* Mobile menu transisi */
        #mobileMenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        #mobileMenu.open { max-height: 300px; }

        /* Custom Scrollbar Light */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>
<body class="antialiased selection:bg-primary selection:text-white">

    <!-- NAVBAR (Biru Vibrant seperti di Dashboard) -->
    <header class="sticky top-0 z-50 bg-gradient-to-r from-blue-700 to-primary shadow-md transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-lg font-bold text-white leading-none font-display">Karyaku<span class="text-accent">.</span></h1>
                </div>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-8 text-[14px] font-medium text-blue-100">
                <a href="#hero" class="hover:text-white transition-colors">Beranda</a>
                <a href="#kategori" class="hover:text-white transition-colors">Kategori</a>
                <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
                <a href="#karya-pilihan" class="hover:text-white transition-colors">Karya Pilihan</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="auth/login" class="inline-flex items-center justify-center px-6 py-2 text-sm font-bold text-white bg-accent hover:bg-accentHover rounded-lg transition-all shadow-md">
                    Masuk
                </a>
                <!-- Mobile Toggle -->
                <button id="menuToggle" class="md:hidden w-9 h-9 flex items-center justify-center rounded-md border border-blue-400 text-white hover:bg-blue-600 transition-colors">
                    <i class="fa-solid fa-bars text-sm" id="menuIcon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden bg-white border-t border-borderSoft shadow-xl">
            <nav class="flex flex-col px-4 py-3 space-y-1 text-sm font-medium text-textMuted">
                <a href="#hero" class="block px-3 py-2 rounded-md hover:bg-bgLight hover:text-primary transition-colors">Beranda</a>
                <a href="#kategori" class="block px-3 py-2 rounded-md hover:bg-bgLight hover:text-primary transition-colors">Kategori Jasa</a>
                <a href="#cara-kerja" class="block px-3 py-2 rounded-md hover:bg-bgLight hover:text-primary transition-colors">Cara Kerja</a>
                <a href="#karya-pilihan" class="block px-3 py-2 rounded-md hover:bg-bgLight hover:text-primary transition-colors">Karya Pilihan</a>
                <div class="h-px bg-borderSoft my-2"></div>
                <a href="auth/login" class="block px-3 py-2 text-center rounded-md bg-accent text-white font-bold hover:bg-accentHover transition-colors">Masuk</a>
            </nav>
        </div>
    </header>

    <!-- SECTION 1: HERO (Light Mesh Gradient) -->
    <section id="hero" class="relative pt-16 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-gradient-to-br from-blue-50 via-white to-orange-50 border-b border-borderSoft">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] rounded-full bg-blue-100/50 blur-3xl"></div>
            <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] rounded-full bg-orange-100/40 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Kiri: Tipografi -->
                <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0 reveal">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full bg-blue-100 text-primary text-xs font-bold tracking-wide mb-6 border border-blue-200 shadow-sm">
                        <i class="fa-solid fa-sparkles text-accent"></i> Ruang Karya Digital Indonesia
                    </span>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 text-textMain leading-[1.15] tracking-tight">
                        Temukan Jasa Digital untuk <span class="text-primary">Kebutuhan Bisnismu.</span>
                    </h1>
                    <p class="text-base sm:text-lg text-textMuted mb-8 leading-relaxed">
                        Dari desain poster, model 3D, hingga UI/UX. Beli karya langsung jadi atau sewa jasa kreator profesional dengan sistem pembayaran yang aman.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="#kategori" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-7 py-3.5 rounded-xl text-sm font-bold hover:bg-primaryHover transition-colors shadow-lg shadow-primary/30">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari Kreator
                        </a>
                    </div>
                </div>

                <!-- Kanan: Masonry Image Grid -->
                <div class="hidden lg:grid grid-cols-2 gap-4 reveal">
                    <div class="space-y-4 pt-12">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=400&q=80" alt="UI Design" class="rounded-2xl object-cover h-48 w-full shadow-lg border border-white">
                        <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=400&q=80" alt="Illustration" class="rounded-2xl object-cover h-64 w-full shadow-lg border border-white">
                    </div>
                    <div class="space-y-4">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=400&q=80" alt="Branding" class="rounded-2xl object-cover h-64 w-full shadow-lg border border-white">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=400&q=80" alt="3D Model" class="rounded-2xl object-cover h-48 w-full shadow-lg border border-white">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: KATEGORI JASA (White Background) -->
    <section id="kategori" class="py-20 lg:py-24 bg-cardWhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
                <div class="max-w-2xl">
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-textMain mb-3">Eksplorasi Kategori Jasa</h2>
                    <p class="text-textMuted text-sm sm:text-base">Temukan layanan yang sesuai dengan kebutuhan proyek Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Card Kategori 1 -->
                <div onclick="openModal('canva')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Desain Poster Canva" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">Desain Poster Canva</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Template siap pakai dan jasa desain poster promosi, event, dan menu restoran.</p>
                    </div>
                </div>

                <!-- Card Kategori 2 -->
                <div onclick="openModal('blender')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Model 3D Blender" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-cube"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">Model 3D Blender</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Aset karakter, visualisasi arsitektur, dan properti 3D untuk game & animasi.</p>
                    </div>
                </div>

                <!-- Card Kategori 3 -->
                <div onclick="openModal('logo')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Logo & Branding" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">Logo & Branding</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Identitas merek profesional, pedoman visual, dan desain kemasan produk.</p>
                    </div>
                </div>

                <!-- Card Kategori 4 -->
                <div onclick="openModal('sosmed')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80" alt="Konten Media Sosial" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">Konten Media Sosial</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Manajemen feed, desain story, dan editing video pendek untuk Instagram & TikTok.</p>
                    </div>
                </div>

                <!-- Card Kategori 5 -->
                <div onclick="openModal('uiux')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80" alt="UI/UX Design" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">UI/UX Design</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Desain antarmuka aplikasi mobile, website, dan prototipe interaktif figma.</p>
                    </div>
                </div>

                <!-- Card Kategori 6 -->
                <div onclick="openModal('ilustrasi')" class="reveal group cursor-pointer bg-white border border-borderSoft rounded-2xl overflow-hidden hover:border-primary/50 hover:shadow-xl transition-all duration-300 flex flex-col shadow-sm">
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80" alt="Ilustrasi Digital" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center border border-blue-100">
                                <i class="fa-solid fa-paintbrush"></i>
                            </div>
                            <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-textMain mb-2 text-lg group-hover:text-primary transition-colors">Ilustrasi Digital</h3>
                        <p class="text-sm text-textMuted line-clamp-2">Seni vektor, karikatur, gambar buku anak, dan berbagai gaya ilustrasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: CARA KERJA (Light Background) -->
    <section id="cara-kerja" class="py-20 lg:py-24 bg-bgLight border-y border-borderSoft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-textMain mb-3">Cara Kerja Karyaku</h2>
                <p class="text-textMuted text-sm sm:text-base">Sistem yang aman dan mudah bagi pembeli maupun kreator.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Garis penghubung -->
                <div class="hidden md:block absolute top-6 left-[16%] right-[16%] h-px bg-borderSoft z-0"></div>

                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">1</div>
                    <h3 class="font-bold text-textMain mb-2">Cari Kreator</h3>
                    <p class="text-textMuted text-sm leading-relaxed max-w-xs">Jelajahi portofolio, baca ulasan, dan temukan kreator yang gaya kerjanya cocok dengan Anda.</p>
                </div>
                
                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border-2 border-accent text-accent rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">2</div>
                    <h3 class="font-bold text-textMain mb-2">Pesan & Bayar Aman</h3>
                    <p class="text-textMuted text-sm leading-relaxed max-w-xs">Dana ditahan oleh sistem kami (Escrow) hingga Anda menyetujui hasil akhir dari kreator.</p>
                </div>
                
                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-sm">3</div>
                    <h3 class="font-bold text-textMain mb-2">Terima Hasil</h3>
                    <p class="text-textMuted text-sm leading-relaxed max-w-xs">Unduh file final berkualitas tinggi, berikan ulasan, dan proyek selesai dengan aman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: KARYA PILIHAN (White Background) -->
    <section id="karya-pilihan" class="py-20 lg:py-24 bg-cardWhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4 reveal">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-textMain mb-2">Karya & Jasa Pilihan</h2>
                    <p class="text-textMuted text-sm sm:text-base">Layanan dengan rating tertinggi dan yang sedang dipromosikan.</p>
                </div>
                <a href="#" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-borderSoft bg-white text-textMain font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm">
                    Lihat Semua Karya <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Produk 1 -->
                <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition-all duration-200 flex flex-col relative">
                    <!-- Badge Promosi -->
                    <div class="absolute top-3 left-3 z-10 bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-md border border-borderSoft shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                        <span class="text-[10px] font-bold text-textMain uppercase tracking-wider">Dipromosikan</span>
                    </div>

                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Karya poster" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-[10px] text-primary"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-textMuted">Dinda Studio</span>
                        </div>
                        <h4 class="font-bold text-textMain text-base mb-3 leading-snug group-hover:text-primary transition-colors">Desain Poster Promosi Kafe Modern (Canva)</h4>
                        
                        <div class="mt-auto pt-4 border-t border-borderSoft flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-textMain font-bold">
                                <i class="fa-solid fa-star text-yellow-400 text-xs"></i> 4.9 <span class="text-textMuted font-medium text-xs">(120)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-primary">Rp75.000</span>
                        </div>
                    </div>
                </div>

                <!-- Produk 2 -->
                <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition-all duration-200 flex flex-col">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Model 3D" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-[10px] text-primary"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-textMuted">Rangga 3D</span>
                        </div>
                        <h4 class="font-bold text-textMain text-base mb-3 leading-snug group-hover:text-primary transition-colors">Model 3D Karakter Game (Siap Rigging)</h4>
                        
                        <div class="mt-auto pt-4 border-t border-borderSoft flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-textMain font-bold">
                                <i class="fa-solid fa-star text-yellow-400 text-xs"></i> 5.0 <span class="text-textMuted font-medium text-xs">(45)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-primary">Rp480.000</span>
                        </div>
                    </div>
                </div>

                <!-- Produk 3 -->
                <div class="reveal group border border-borderSoft rounded-xl overflow-hidden bg-white hover:shadow-lg transition-all duration-200 flex flex-col">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Logo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-[10px] text-primary"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-textMuted">Kirana Design</span>
                        </div>
                        <h4 class="font-bold text-textMain text-base mb-3 leading-snug group-hover:text-primary transition-colors">Paket Pembuatan Logo & Brand Identity</h4>
                        
                        <div class="mt-auto pt-4 border-t border-borderSoft flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-textMain font-bold">
                                <i class="fa-solid fa-star text-yellow-400 text-xs"></i> 4.8 <span class="text-textMuted font-medium text-xs">(89)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-primary">Rp150.000</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center sm:hidden reveal">
                <a href="#" class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-lg border border-borderSoft bg-white text-textMain font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm">
                    Lihat Semua Karya <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 5: UNTUK KREATOR (Vibrant Blue - Contrast Section) -->
    <section id="kreator" class="py-20 lg:py-28 bg-primary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                
                <!-- Kiri: Tipografi -->
                <div class="w-full lg:w-5/12 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-blue-600 border border-blue-500 text-[11px] font-bold tracking-widest uppercase mb-5 text-white shadow-sm">
                        <i class="fa-solid fa-store text-accent"></i> Fitur Penjual
                    </div>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold leading-tight mb-5 text-white">
                        Ubah Keahlian Jadi Pendapatan.
                    </h2>
                    <p class="text-blue-100 text-sm sm:text-base leading-relaxed mb-8">
                        Daftar sebagai pengguna untuk mencari inspirasi, dan <strong>upgrade ke Paket Kreator</strong> langsung dari dashboard-mu kapan pun kamu siap menawarkan jasa.
                    </p>
                    <a href="auth/login" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-accent text-white font-bold text-sm hover:bg-accentHover transition-colors shadow-lg shadow-black/20">
                        Buka Toko Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Kanan: Bento Grid Layout -->
                <div class="w-full lg:w-7/12 grid gap-4 reveal">
                    <!-- Highlight Card -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 sm:p-8 rounded-2xl relative overflow-hidden hover:bg-white/15 transition-colors">
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg mb-2 text-white">Satu Akun, Multi Peran</h3>
                            <p class="text-blue-100 text-sm leading-relaxed max-w-sm">
                                Beli jasa dari desainer lain, lalu buka toko milikmu sendiri menggunakan satu akun yang sama. Tidak perlu repot mendaftar dua kali.
                            </p>
                        </div>
                        <i class="fa-solid fa-user-tie absolute -right-4 -bottom-4 text-[100px] text-white opacity-10 pointer-events-none"></i>
                    </div>

                    <!-- 2 Grid Bawah -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white text-primary flex items-center justify-center mb-4 shadow-sm">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h4 class="font-bold text-white mb-1.5 text-sm">Dashboard Terpusat</h4>
                            <p class="text-xs text-blue-100 leading-relaxed">Kelola pesanan, chat dengan klien, dan pantau penghasilan di satu tempat.</p>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-accent text-white flex items-center justify-center mb-4 shadow-sm">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <h4 class="font-bold text-white mb-1.5 text-sm">Keamanan Escrow</h4>
                            <p class="text-xs text-blue-100 leading-relaxed">Fokus berkarya. Sistem pembayaran kami menjamin dana cair otomatis saat selesai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER (Light Background) -->
    <footer class="bg-white py-10 border-t border-borderSoft">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-[12px]"></i>
                </div>
                <span class="font-display font-bold text-base text-textMain">Karyaku<span class="text-accent">.</span></span>
            </div>
            
            <p class="text-xs sm:text-sm text-textMuted text-center md:text-left">
                &copy; 2026 Karyaku. Hak Cipta Dilindungi.
            </p>
            
            <div class="flex gap-4">
                <a href="#" class="text-textMuted hover:text-primary transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-textMuted hover:text-primary transition-colors"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="text-textMuted hover:text-primary transition-colors"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <!-- MODAL POPUP KATEGORI (Light Theme) -->
    <div id="categoryModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 transition-opacity duration-200 opacity-0" onclick="closeModal()">
        <div class="bg-white border border-borderSoft w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-200 flex flex-col" id="modalContent" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-5 py-4 border-b border-borderSoft bg-white">
                <h3 id="modalTitle" class="font-bold text-base text-textMain">Judul Kategori</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-textMuted hover:bg-slate-200 hover:text-textMain transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div id="modalBody" class="p-5 space-y-3 max-h-[60vh] overflow-y-auto bg-slate-50">
                <!-- Diisi via JS -->
            </div>
        </div>
    </div>

    <script>
        const categoryData = {
            'canva': {
                title: 'Desain Poster Canva',
                items: [
                    { title: 'Poster Promosi & Diskon', desc: 'Desain visual kreatif untuk memasarkan produk, jasa, atau mengumumkan diskon spesial toko Anda.' },
                    { title: 'Poster Event / Webinar', desc: 'Media informasi digital untuk pendaftaran konser, seminar online, atau acara komunitas.' },
                    { title: 'Menu Restoran / Kafe', desc: 'Pembuatan daftar menu yang estetik dan mudah dibaca oleh pelanggan.' }
                ]
            },
            'blender': {
                title: 'Model & Animasi 3D Blender',
                items: [
                    { title: 'Model Karakter 3D', desc: 'Pembuatan karakter manusia, hewan, atau maskot unik untuk keperluan game dan animasi.' },
                    { title: 'Visualisasi Arsitektur', desc: 'Render 3D realistis untuk desain interior ruangan maupun eksterior bangunan.' },
                    { title: 'Aset Prop & Objek', desc: 'Pembuatan model benda mati (senjata, mobil, furniture) dengan resolusi low-poly maupun high-poly.' }
                ]
            },
            'logo': {
                title: 'Logo & Branding',
                items: [
                    { title: 'Logo Minimalis', desc: 'Desain logo yang simpel, modern, dan mudah diingat (memorable) oleh pelanggan.' },
                    { title: 'Brand Identity Guideline', desc: 'Dokumen lengkap yang berisi aturan penggunaan warna, tipografi, dan gaya visual brand Anda.' },
                    { title: 'Desain Kemasan (Packaging)', desc: 'Rancangan visual untuk kotak produk atau label botol agar terlihat profesional di pasaran.' }
                ]
            },
            'sosmed': {
                title: 'Konten Media Sosial',
                items: [
                    { title: 'Feed & Story Instagram', desc: 'Templat postingan grid atau carousel edukasi yang berkesinambungan dan estetik.' },
                    { title: 'Edit Video Reels / TikTok', desc: 'Jasa memotong dan mengedit video pendek vertikal dengan subtitle dinamis yang viral.' },
                    { title: 'Thumbnail YouTube', desc: 'Desain sampul video yang menarik perhatian (clickbait) namun tetap relevan dengan isi video.' }
                ]
            },
            'uiux': {
                title: 'UI/UX Design',
                items: [
                    { title: 'Desain Aplikasi Mobile', desc: 'Merancang antarmuka pengguna (User Interface) untuk aplikasi iOS atau Android yang mudah digunakan.' },
                    { title: 'Desain Landing Page', desc: 'Pembuatan layout website satu halaman yang dioptimalkan untuk konversi dan penjualan.' },
                    { title: 'Wireframing & Prototyping', desc: 'Kerangka dasar aplikasi yang bisa diklik (interaktif) sebelum masuk ke tahap pemrograman.' }
                ]
            },
            'ilustrasi': {
                title: 'Ilustrasi Digital',
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
                modalBody.innerHTML += `
                    <div class="border border-borderSoft rounded-xl overflow-hidden bg-white shadow-sm mb-2">
                        <button onclick="toggleAccordion(this)" class="w-full text-left px-4 py-3.5 flex justify-between items-center font-bold text-sm text-textMain hover:bg-slate-50 transition-colors">
                            ${item.title}
                            <i class="fa-solid fa-chevron-down text-primary text-xs transition-transform duration-200 transform"></i>
                        </button>
                        <div class="accordion-content px-4 py-3 text-[13px] text-textMuted hidden border-t border-borderSoft bg-slate-50 leading-relaxed">
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
            }, 200); 
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
            menuIcon.classList.toggle('fa-bars', !isOpen);
            menuIcon.classList.toggle('fa-xmark', isOpen);
        });
        
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
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
        }, { threshold: 0.1 });
        revealEls.forEach(el => revealObserver.observe(el));
    </script>
</body>
</html>
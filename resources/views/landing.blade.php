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
                    // Palet Warna Deep Navy & Warm Orange Theme (Dark Elegant Concept)
                    colors: {
                        primary: '#3B82F6',       // Blue Accent
                        primaryHover: '#2563EB',  // Darker Blue Accent
                        accent: '#DD6B20',        // Warm Orange
                        accentHover: '#C05621',   // Darker Warm Orange
                        bgDark: '#0B132B',        // Deep Navy Utama (Hero, Cara Kerja, Kreator)
                        bgDarkAlt: '#111A35',     // Alternate Deep Navy (Kategori, Karya Pilihan)
                        bgFooter: '#070C1E',      // Darkest Navy (Footer)
                        cardDark: '#1C2541',      // Dark Card Background
                        cardBorder: '#2A365C'     // Border Tipis Card
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0B132B; color: #F1F5F9; }
        h1, h2, h3, h4, h5, .font-display { font-family: 'Sora', sans-serif; }
        
        /* Focus State */
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

        /* Animasi reveal ringan */
        .reveal { opacity: 0; transform: translateY(15px); transition: opacity 0.5s ease-out, transform 0.5s ease-out; }
        .reveal.in-view { opacity: 1; transform: none; }

        /* Mobile menu transisi */
        #mobileMenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        #mobileMenu.open { max-height: 300px; }

        /* Custom Scrollbar Dark */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B132B; }
        ::-webkit-scrollbar-thumb { background: #2A365C; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3B82F6; }
    </style>
</head>
<body class="antialiased selection:bg-accent selection:text-white">

    <!-- NAVBAR (Deep Navy with Glassmorphism) -->
    <header class="sticky top-0 z-50 bg-bgDark/90 backdrop-blur-md border-b border-cardBorder transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-lg font-bold text-white leading-none font-display">Karyaku<span class="text-accent">.</span></h1>
                </div>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-8 text-[14px] font-medium text-slate-300">
                <a href="#hero" class="hover:text-white transition-colors">Beranda</a>
                <a href="#kategori" class="hover:text-white transition-colors">Kategori</a>
                <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
                <a href="#karya-pilihan" class="hover:text-white transition-colors">Karya Pilihan</a>
            </nav>

            <!-- Actions (Hanya Tombol Masuk yang Diperbarui) -->
            <div class="flex items-center gap-3">
                <a href="auth/login" class="inline-flex items-center justify-center px-6 py-2 text-sm font-bold text-white bg-accent hover:bg-accentHover rounded-lg transition-all shadow-md shadow-accent/20">
                    Masuk
                </a>
                <!-- Mobile Toggle -->
                <button id="menuToggle" aria-label="Buka menu" aria-expanded="false" class="md:hidden w-9 h-9 flex items-center justify-center rounded-md border border-cardBorder text-slate-300 hover:bg-cardDark transition-colors">
                    <i class="fa-solid fa-bars text-sm" id="menuIcon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden bg-bgDark border-t border-cardBorder shadow-xl">
            <nav class="flex flex-col px-4 py-3 space-y-1 text-sm font-medium text-slate-300">
                <a href="#hero" class="block px-3 py-2 rounded-md hover:bg-cardDark hover:text-white transition-colors">Beranda</a>
                <a href="#kategori" class="block px-3 py-2 rounded-md hover:bg-cardDark hover:text-white transition-colors">Kategori Jasa</a>
                <a href="#cara-kerja" class="block px-3 py-2 rounded-md hover:bg-cardDark hover:text-white transition-colors">Cara Kerja</a>
                <a href="#karya-pilihan" class="block px-3 py-2 rounded-md hover:bg-cardDark hover:text-white transition-colors">Karya Pilihan</a>
                <div class="h-px bg-cardBorder my-2"></div>
                <a href="auth/login" class="block px-3 py-2 text-center rounded-md bg-accent text-white font-bold hover:bg-accentHover transition-colors">Masuk</a>
            </nav>
        </div>
    </header>

    <!-- SECTION 1: HERO (Deep Navy Background) -->
    <section id="hero" class="relative pt-16 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-bgDark border-b border-cardBorder">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Kiri: Tipografi -->
                <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0 reveal">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-cardDark text-blue-400 text-xs font-bold tracking-wide mb-6 border border-cardBorder shadow-sm">
                        <i class="fa-solid fa-sparkles mr-1 text-accent"></i> Ruang Karya Digital Indonesia
                    </span>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 text-white leading-[1.15] tracking-tight">
                        Temukan Jasa Digital untuk <span class="text-blue-400">Kebutuhan Bisnismu.</span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-300 mb-8 leading-relaxed">
                        Dari desain poster, model 3D, hingga UI/UX. Beli karya langsung jadi atau sewa jasa kreator profesional dengan sistem pembayaran yang aman.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="#kategori" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-7 py-3.5 rounded-xl text-sm font-bold hover:bg-primaryHover transition-colors shadow-lg shadow-primary/20">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari Kreator
                        </a>
                    </div>
                </div>

                <!-- Kanan: Masonry Image Grid dengan Dark Frame -->
                <div class="hidden lg:grid grid-cols-2 gap-4 reveal">
                    <div class="space-y-4 pt-12">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=400&q=80" alt="UI Design" class="rounded-2xl object-cover h-48 w-full shadow-md border border-cardBorder">
                        <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=400&q=80" alt="Illustration" class="rounded-2xl object-cover h-64 w-full shadow-md border border-cardBorder">
                    </div>
                    <div class="space-y-4">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=400&q=80" alt="Branding" class="rounded-2xl object-cover h-64 w-full shadow-md border border-cardBorder">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=400&q=80" alt="3D Model" class="rounded-2xl object-cover h-48 w-full shadow-md border border-cardBorder">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: KATEGORI JASA (Alternate Navy Layer) -->
    <section id="kategori" class="py-20 lg:py-24 bg-bgDarkAlt">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
                <div class="max-w-2xl">
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mb-3">Eksplorasi Kategori Jasa</h2>
                    <p class="text-slate-400 text-sm sm:text-base">Temukan layanan yang sesuai dengan kebutuhan proyek Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                
                <!-- Card Kategori 1 -->
                <div onclick="openModal('canva')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Desain Poster Canva" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">Desain Poster Canva</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Template siap pakai dan jasa desain poster promosi, event, dan menu restoran.</p>
                    </div>
                </div>

                <!-- Card Kategori 2 -->
                <div onclick="openModal('blender')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Model 3D Blender" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-cube"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">Model 3D Blender</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Aset karakter, visualisasi arsitektur, dan properti 3D untuk game & animasi.</p>
                    </div>
                </div>

                <!-- Card Kategori 3 -->
                <div onclick="openModal('logo')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Logo & Branding" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">Logo & Branding</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Identitas merek profesional, pedoman visual, dan desain kemasan produk.</p>
                    </div>
                </div>

                <!-- Card Kategori 4 -->
                <div onclick="openModal('sosmed')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=600&q=80" alt="Konten Media Sosial" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">Konten Media Sosial</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Manajemen feed, desain story, dan editing video pendek untuk Instagram & TikTok.</p>
                    </div>
                </div>

                <!-- Card Kategori 5 -->
                <div onclick="openModal('uiux')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=600&q=80" alt="UI/UX Design" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">UI/UX Design</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Desain antarmuka aplikasi mobile, website, dan prototipe interaktif figma.</p>
                    </div>
                </div>

                <!-- Card Kategori 6 -->
                <div onclick="openModal('ilustrasi')" class="reveal group cursor-pointer bg-cardDark border border-cardBorder rounded-2xl overflow-hidden hover:border-primary/60 transition-all duration-300 flex flex-col shadow-md">
                    <div class="relative h-44 overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=600&q=80" alt="Ilustrasi Digital" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 text-blue-400 flex items-center justify-center border border-primary/30">
                                <i class="fa-solid fa-paintbrush"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-300 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">Lihat Detail</span>
                        </div>
                        <h3 class="font-bold text-white mb-2 text-lg group-hover:text-blue-400 transition-colors">Ilustrasi Digital</h3>
                        <p class="text-sm text-slate-300 line-clamp-2">Seni vektor, karikatur, gambar buku anak, dan berbagai gaya ilustrasi.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3: CARA KERJA (Deep Navy Bordered Layer) -->
    <section id="cara-kerja" class="py-20 lg:py-24 bg-bgDark border-y border-cardBorder">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mb-3">Cara Kerja Karyaku</h2>
                <p class="text-slate-400 text-sm sm:text-base">Sistem yang aman dan mudah bagi pembeli maupun kreator.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Garis penghubung (hanya desktop) -->
                <div class="hidden md:block absolute top-6 left-[16%] right-[16%] h-px bg-cardBorder z-0"></div>

                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-cardDark border-2 border-primary text-blue-400 rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-md">1</div>
                    <h3 class="font-bold text-white mb-2">Cari Kreator</h3>
                    <p class="text-slate-300 text-sm leading-relaxed max-w-xs">Jelajahi portofolio, baca ulasan, dan temukan kreator yang gaya kerjanya cocok dengan Anda.</p>
                </div>
                
                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-cardDark border-2 border-accent text-accent rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-md">2</div>
                    <h3 class="font-bold text-white mb-2">Pesan & Bayar Aman</h3>
                    <p class="text-slate-300 text-sm leading-relaxed max-w-xs">Dana ditahan oleh sistem kami (Escrow) hingga Anda menyetujui hasil akhir dari kreator.</p>
                </div>
                
                <div class="reveal relative z-10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-cardDark border-2 border-primary text-blue-400 rounded-full flex items-center justify-center font-bold text-lg mb-5 shadow-md">3</div>
                    <h3 class="font-bold text-white mb-2">Terima Hasil</h3>
                    <p class="text-slate-300 text-sm leading-relaxed max-w-xs">Unduh file final berkualitas tinggi, berikan ulasan, dan proyek selesai dengan aman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: KARYA PILIHAN (Alternate Navy Layer) -->
    <section id="karya-pilihan" class="py-20 lg:py-24 bg-bgDarkAlt">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4 reveal">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mb-2">Karya & Jasa Pilihan</h2>
                    <p class="text-slate-400 text-sm sm:text-base">Layanan dengan rating tertinggi dan yang sedang dipromosikan.</p>
                </div>
                <a href="#" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-cardBorder bg-cardDark text-white font-bold text-sm hover:bg-slate-800 transition-colors shadow-sm">
                    Lihat Semua Karya <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Produk 1 (Dengan Badge Promosi) -->
                <div class="reveal group border border-cardBorder rounded-xl overflow-hidden bg-cardDark hover:border-slate-500 transition-all duration-200 flex flex-col relative shadow-md">
                    <!-- Badge Promosi -->
                    <div class="absolute top-3 left-3 z-10 bg-bgDark/90 backdrop-blur-sm px-2.5 py-1 rounded-md border border-cardBorder shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                        <span class="text-[10px] font-bold text-white uppercase tracking-wider">Dipromosikan</span>
                    </div>

                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Karya poster" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-bgDark border border-cardBorder flex items-center justify-center text-[10px] text-blue-400"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-slate-300">Dinda Studio</span>
                        </div>
                        <h4 class="font-bold text-white text-base mb-3 leading-snug hover:text-blue-400 cursor-pointer transition-colors">Desain Poster Promosi Kafe Modern (Canva)</h4>
                        
                        <div class="mt-auto pt-4 border-t border-cardBorder/60 flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-white font-bold">
                                <i class="fa-solid fa-star text-accent text-xs"></i> 4.9 <span class="text-slate-400 font-medium text-xs">(120)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-accent">Rp75.000</span>
                        </div>
                    </div>
                </div>

                <!-- Produk 2 -->
                <div class="reveal group border border-cardBorder rounded-xl overflow-hidden bg-cardDark hover:border-slate-500 transition-all duration-200 flex flex-col shadow-md">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=600&q=80" alt="Model 3D" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-bgDark border border-cardBorder flex items-center justify-center text-[10px] text-blue-400"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-slate-300">Rangga 3D</span>
                        </div>
                        <h4 class="font-bold text-white text-base mb-3 leading-snug hover:text-blue-400 cursor-pointer transition-colors">Model 3D Karakter Game (Siap Rigging)</h4>
                        
                        <div class="mt-auto pt-4 border-t border-cardBorder/60 flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-white font-bold">
                                <i class="fa-solid fa-star text-accent text-xs"></i> 5.0 <span class="text-slate-400 font-medium text-xs">(45)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-accent">Rp480.000</span>
                        </div>
                    </div>
                </div>

                <!-- Produk 3 -->
                <div class="reveal group border border-cardBorder rounded-xl overflow-hidden bg-cardDark hover:border-slate-500 transition-all duration-200 flex flex-col shadow-md">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=600&q=80" alt="Logo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-bgDark border border-cardBorder flex items-center justify-center text-[10px] text-blue-400"><i class="fa-solid fa-user"></i></span>
                            <span class="text-xs font-bold text-slate-300">Kirana Design</span>
                        </div>
                        <h4 class="font-bold text-white text-base mb-3 leading-snug hover:text-blue-400 cursor-pointer transition-colors">Paket Pembuatan Logo & Brand Identity</h4>
                        
                        <div class="mt-auto pt-4 border-t border-cardBorder/60 flex items-center justify-between">
                            <div class="flex items-center gap-1 text-sm text-white font-bold">
                                <i class="fa-solid fa-star text-accent text-xs"></i> 4.8 <span class="text-slate-400 font-medium text-xs">(89)</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-accent">Rp150.000</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Lihat Semua (Mobile) -->
            <div class="mt-8 text-center sm:hidden reveal">
                <a href="#" class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-lg border border-cardBorder bg-cardDark text-white font-bold text-sm hover:bg-slate-800 transition-colors shadow-sm">
                    Lihat Semua Karya <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 5: UNTUK KREATOR (Deep Navy with Bento Grid) -->
    <section id="kreator" class="py-20 lg:py-28 bg-bgDark border-t border-cardBorder text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                
                <!-- Kiri: Tipografi -->
                <div class="w-full lg:w-5/12 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-cardBorder bg-cardDark text-[11px] font-bold tracking-widest uppercase mb-5 text-blue-400">
                        <i class="fa-solid fa-store text-accent"></i> Fitur Penjual
                    </div>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold leading-tight mb-5 text-white">
                        Ubah Keahlian Jadi Pendapatan.
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
                        Daftar sebagai pengguna untuk mencari inspirasi, dan <strong>upgrade ke Paket Kreator</strong> langsung dari dashboard-mu kapan pun kamu siap menawarkan jasa.
                    </p>
                    <a href="auth/login" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-accent text-white font-bold text-sm hover:bg-accentHover transition-colors shadow-lg shadow-accent/20">
                        Buka Toko Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Kanan: Bento Grid Layout -->
                <div class="w-full lg:w-7/12 grid gap-4 reveal">
                    <!-- Highlight Card -->
                    <div class="bg-cardDark border border-cardBorder p-6 sm:p-8 rounded-2xl relative overflow-hidden hover:border-slate-500 transition-colors">
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg mb-2 text-white">Satu Akun, Multi Peran</h3>
                            <p class="text-slate-300 text-sm leading-relaxed max-w-sm">
                                Beli jasa dari desainer lain, lalu buka toko milikmu sendiri menggunakan satu akun yang sama. Tidak perlu repot mendaftar dua kali.
                            </p>
                        </div>
                        <i class="fa-solid fa-user-tie absolute -right-4 -bottom-4 text-[100px] text-white opacity-5 pointer-events-none"></i>
                    </div>

                    <!-- 2 Grid Bawah -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-cardDark border border-cardBorder p-6 rounded-2xl hover:border-slate-500 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-bgDark border border-cardBorder flex items-center justify-center text-blue-400 mb-4">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h4 class="font-bold text-white mb-1.5 text-sm">Dashboard Terpusat</h4>
                            <p class="text-xs text-slate-300 leading-relaxed">Kelola pesanan, chat dengan klien, dan pantau penghasilan di satu tempat.</p>
                        </div>
                        
                        <div class="bg-cardDark border border-cardBorder p-6 rounded-2xl hover:border-slate-500 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-bgDark border border-cardBorder flex items-center justify-center text-accent mb-4">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <h4 class="font-bold text-white mb-1.5 text-sm">Keamanan Escrow</h4>
                            <p class="text-xs text-slate-300 leading-relaxed">Fokus berkarya. Sistem pembayaran kami menjamin dana cair otomatis saat selesai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER (Darkest Navy Penutup Halaman) -->
    <footer class="bg-bgFooter py-10 border-t border-cardBorder/40">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-[12px]"></i>
                </div>
                <span class="font-display font-bold text-base text-white">Karyaku<span class="text-accent">.</span></span>
            </div>
            
            <p class="text-xs sm:text-sm text-slate-400 text-center md:text-left">
                &copy; 2026 Karyaku. Hak Cipta Dilindungi.
            </p>
            
            <div class="flex gap-4">
                <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <!-- MODAL POPUP KATEGORI (Dark Theme) -->
    <div id="categoryModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/75 backdrop-blur-sm p-4 transition-opacity duration-200 opacity-0" onclick="closeModal()">
        <div class="bg-cardDark border border-cardBorder w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-200 flex flex-col" id="modalContent" onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-5 py-4 border-b border-cardBorder bg-cardDark">
                <h3 id="modalTitle" class="font-bold text-base text-white">Judul Kategori</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-bgDark text-slate-300 hover:bg-slate-800 hover:text-white transition-colors border border-cardBorder">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div id="modalBody" class="p-5 space-y-3 max-h-[60vh] overflow-y-auto bg-bgDark">
                <!-- Diisi via JS -->
            </div>
        </div>
    </div>

    <!-- SCRIPT UTAMA -->
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
                    <div class="border border-cardBorder rounded-xl overflow-hidden bg-cardDark shadow-sm mb-2">
                        <button onclick="toggleAccordion(this)" class="w-full text-left px-4 py-3.5 flex justify-between items-center font-bold text-sm text-white hover:bg-slate-800 transition-colors">
                            ${item.title}
                            <i class="fa-solid fa-chevron-down text-blue-400 text-xs transition-transform duration-200 transform"></i>
                        </button>
                        <div class="accordion-content px-4 py-3 text-[13px] text-slate-300 hidden border-t border-cardBorder bg-bgDark leading-relaxed">
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
        }, { threshold: 0.1 });
        revealEls.forEach(el => revealObserver.observe(el));
    </script>
</body>
</html>
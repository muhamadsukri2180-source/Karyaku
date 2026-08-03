<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marketplace - Karyaku</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    :root{
        --primary: #2563eb;
        --primary-dark: #1e3a8a;
        --primary-darker: #14225c;
        --primary-light: #eff6ff;
        --primary-soft: #dbeafe;
        --coral: #FF7A59;
        --coral-dark: #F0623F;
        --white: #ffffff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e5edff;
        --radius: 18px;
        --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
        --shadow-hover: 0 16px 34px rgba(37, 99, 235, 0.16);
    }
    *{ box-sizing: border-box; }
    body{
        font-family: 'Poppins', sans-serif;
        background: var(--primary-light);
        color: var(--text-dark);
        overflow-x: hidden;
    }

    /* ---------------- Background decor ---------------- */
    .bg-decor{ position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
    .bg-decor span{
        position: absolute; border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, var(--primary-soft), transparent 70%);
        opacity: .5; animation: floatBlob 14s ease-in-out infinite;
    }
    .bg-decor span:nth-child(1){ width: 380px; height: 380px; top: -120px; right: -100px; animation-duration: 16s; }
    .bg-decor span:nth-child(2){ width: 260px; height: 260px; bottom: -80px; left: -60px; animation-duration: 20s; animation-delay: 2s; }
    @keyframes floatBlob{ 0%,100%{ transform: translate(0,0) scale(1); } 50%{ transform: translate(20px,-30px) scale(1.08); } }

    .app-wrapper{ display: flex; min-height: 100vh; }

    /* ---------------- Sidebar ---------------- */
    .sidebar{
        width: 272px; min-width: 272px;
        background: linear-gradient(180deg, var(--primary-darker) 0%, var(--primary-dark) 100%);
        color: var(--white); min-height: 100vh; position: sticky; top: 0;
        display: flex; flex-direction: column; padding: 24px 18px; z-index: 1030;
    }
    .sidebar .brand{ display: flex; align-items: center; gap: 12px; padding: 6px 10px 26px 10px; border-bottom: 1px solid rgba(255,255,255,0.12); margin-bottom: 20px; }
    .sidebar .brand-icon{ width: 42px; height: 42px; background: var(--white); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; }
    .sidebar .brand-text h5{ margin: 0; font-weight: 700; font-size: 16px; color: var(--white); }
    .sidebar .brand-text small{ color: rgba(255,255,255,0.6); font-size: 11px; }
    .sidebar .nav-section-title{ font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,0.45); padding: 10px 12px 6px; }
    .sidebar .nav-link{ position: relative; display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.78); padding: 11px 14px; border-radius: 12px; font-size: 14.5px; font-weight: 500; margin-bottom: 5px; transition: all .25s ease; }
    .sidebar .nav-link i{ font-size: 18px; width: 22px; text-align: center; }
    .sidebar .nav-link:hover{ background: rgba(255,255,255,0.08); color: var(--white); transform: translateX(4px); }
    .sidebar .nav-link.active{ background: var(--white); color: var(--primary-dark); font-weight: 600; box-shadow: 0 8px 20px rgba(0,0,0,0.18); }
    .sidebar .nav-link .badge-count{ margin-left: auto; background: var(--primary); color: var(--white); font-size: 11px; padding: 2px 8px; border-radius: 20px; }
    .sidebar .nav-link.active .badge-count{ background: var(--primary-soft); color: var(--primary-dark); }
    .sidebar .sidebar-footer{ margin-top: auto; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.12); }
    .sidebar .logout-link{ display: flex; align-items: center; gap: 10px; color: #fecaca; padding: 10px 14px; border-radius: 12px; font-size: 14px; font-weight: 500; text-decoration: none; transition: .2s; }
    .sidebar .logout-link:hover{ background: rgba(255,255,255,0.08); color: #fff; padding-left: 18px; }

    @media (max-width: 992px){
        .sidebar{ position: fixed; left: -290px; transition: left .3s ease; box-shadow: 10px 0 30px rgba(0,0,0,0.25); }
        .sidebar.show{ left: 0; }
        .sidebar-overlay{ display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.45); z-index: 1020; }
        .sidebar-overlay.show{ display: block; }
    }

    .main-content{ flex: 1; min-width: 0; padding: 0 0 50px; }

    /* ================= TOP MARKETPLACE BAR (mirip landing) ================= */
    .market-topbar{
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary));
        padding: 22px 28px;
        position: sticky; top: 0; z-index: 1010;
        box-shadow: 0 10px 30px rgba(20,34,92,0.18);
    }
    .market-topbar .row-top{ display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }

    .mobile-toggle{
        width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.12);
        border: none; color: #fff; display: none; align-items: center; justify-content: center; flex-shrink: 0;
    }
    @media (max-width: 992px){ .mobile-toggle{ display: flex; } }

    /* search + category combo, ala Shopee */
    .search-combo{
        flex: 1 1 420px; display: flex; background: #fff; border-radius: 12px;
        overflow: hidden; box-shadow: 0 8px 22px rgba(0,0,0,0.15);
    }
    .search-combo select{
        border: none; background: var(--primary-light); color: var(--text-dark);
        font-size: 13.5px; font-weight: 600; padding: 0 12px; max-width: 168px;
        border-right: 1px solid var(--border-color); outline: none;
    }
    .search-combo input{
        border: none; flex: 1; padding: 12px 14px; font-size: 14px; outline: none; min-width: 0;
    }
    .search-combo button{
        border: none; background: var(--coral); color: #fff; padding: 0 20px;
        font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 6px;
        transition: background .2s ease;
    }
    .search-combo button:hover{ background: var(--coral-dark); }

    .market-actions{ display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

    .btn-jual{
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--coral); color: #fff; border: none;
        padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 13.5px;
        white-space: nowrap; transition: all .2s ease; text-decoration: none;
    }
    .btn-jual:hover{ background: var(--coral-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,122,89,0.35); }

    .icon-btn-light{
        width: 42px; height: 42px; border-radius: 12px;
        background: rgba(255,255,255,0.12); border: none;
        display: flex; align-items: center; justify-content: center;
        color: #fff; position: relative; font-size: 18px; text-decoration: none;
        transition: all .2s ease; flex-shrink: 0;
    }
    .icon-btn-light:hover{ background: rgba(255,255,255,0.22); color: #fff; transform: translateY(-2px); }
    .icon-btn-light .dot{
        position: absolute; top: 6px; right: 6px; min-width: 16px; height: 16px; padding: 0 3px;
        background: var(--coral); border-radius: 20px; border: 2px solid var(--primary-dark);
        font-size: 9.5px; font-weight: 700; display: flex; align-items: center; justify-content: center;
    }

    .user-chip{
        display: flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.12); padding: 6px 14px 6px 6px; border-radius: 30px;
        flex-shrink: 0; text-decoration: none; transition: background .2s ease;
    }
    .user-chip:hover{ background: rgba(255,255,255,0.2); }
    .user-chip img{ width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    .user-chip .name{ font-size: 13px; font-weight: 600; line-height: 1.1; color: #fff; }
    .user-chip .role{ font-size: 10.5px; color: rgba(255,255,255,0.65); }

    /* ---- kategori chip strip ---- */
    .kategori-strip{
        display: flex; gap: 22px; overflow-x: auto; padding: 16px 28px 4px;
        scrollbar-width: none;
    }
    .kategori-strip::-webkit-scrollbar{ display: none; }
    .kategori-item{
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        flex-shrink: 0; text-decoration: none; color: rgba(255,255,255,0.85);
        font-size: 12px; font-weight: 600; width: 78px; text-align: center;
    }
    .kategori-item .ic{
        width: 52px; height: 52px; border-radius: 16px; background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        transition: all .2s ease; border: 1px solid rgba(255,255,255,0.14);
    }
    .kategori-item:hover .ic{ background: var(--coral); transform: translateY(-3px); border-color: var(--coral); }
    .kategori-item:hover{ color: #fff; }
    .kategori-item.active .ic{ background: var(--coral); border-color: var(--coral); }

    /* ================= BANNER PROMO ================= */
    .promo-banner{
        margin: 22px 28px 0; border-radius: 18px; overflow: hidden; position: relative;
        background: linear-gradient(120deg, #0F4A78, #0B3D62 55%, #082441);
        min-height: 190px; display: flex; align-items: center; padding: 0 40px;
        box-shadow: var(--shadow);
    }
    .promo-banner::after{
        content: ""; position: absolute; width: 260px; height: 260px; border-radius: 50%;
        background: rgba(255,122,89,0.18); right: -60px; top: -60px;
    }
    .promo-banner .txt{ position: relative; z-index: 1; color: #fff; max-width: 460px; }
    .promo-banner .txt span.tag{
        background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px;
        font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
    }
    .promo-banner .txt h2{ font-weight: 800; font-size: 26px; margin: 10px 0 8px; }
    .promo-banner .txt p{ font-size: 13.5px; color: rgba(255,255,255,0.85); margin-bottom: 16px; }

    /* ================= PRODUCT SECTION ================= */
    .section-wrap{ padding: 30px 28px 0; }
    .section-head{ display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px; }
    .section-head h4{ font-weight: 700; font-size: 18px; margin: 0; }
    .section-head p{ margin: 2px 0 0; color: var(--text-muted); font-size: 12.5px; }

    .filter-pills{ display: flex; gap: 8px; flex-wrap: wrap; }
    .filter-pill{
        border: 1px solid var(--border-color); background: #fff; color: var(--text-dark);
        padding: 7px 15px; border-radius: 20px; font-size: 12.5px; font-weight: 600; cursor: pointer;
        transition: all .2s ease;
    }
    .filter-pill.active, .filter-pill:hover{ background: var(--primary); color: #fff; border-color: var(--primary); }

    .product-grid{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    @media (max-width: 1200px){ .product-grid{ grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px){ .product-grid{ grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px){ .product-grid{ grid-template-columns: 1fr 1fr; gap: 12px; } }

    .product-card{
        background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color);
        box-shadow: var(--shadow); transition: transform .25s ease, box-shadow .25s ease; position: relative;
        display: flex; flex-direction: column;
    }
    .product-card:hover{ transform: translateY(-6px); box-shadow: var(--shadow-hover); }

    .product-thumb{ position: relative; height: 150px; overflow: hidden; }
    .product-thumb img{ width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
    .product-card:hover .product-thumb img{ transform: scale(1.08); }

    .product-thumb .cat-badge{
        position: absolute; top: 10px; left: 10px;
        background: rgba(20,34,92,0.75); color: #fff; font-size: 10px; font-weight: 700;
        padding: 4px 10px; border-radius: 20px; backdrop-filter: blur(2px);
    }
    .wish-btn{
        position: absolute; top: 8px; right: 8px; width: 30px; height: 30px; border-radius: 50%;
        background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: 14px; transition: all .2s ease;
    }
    .wish-btn:hover, .wish-btn.active{ color: var(--coral); background: #fff; }

    .product-body{ padding: 12px 13px 14px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
    .product-body h6{ font-size: 13px; font-weight: 600; margin: 0; line-height: 1.35; min-height: 34px; }
    .product-price{ color: var(--coral); font-weight: 800; font-size: 15px; }
    .product-price small{ color: var(--text-muted); font-weight: 500; font-size: 11px; text-decoration: line-through; margin-left: 6px; }

    .product-meta{ display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--text-muted); }
    .product-meta .rating{ color: #f59e0b; font-weight: 600; }
    .product-seller{ display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .product-seller img{ width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }

    .btn-add-cart{
        margin-top: 6px; width: 100%; border: none; background: var(--primary-light); color: var(--primary);
        font-weight: 700; font-size: 12px; padding: 8px 0; border-radius: 9px; display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all .2s ease;
    }
    .btn-add-cart:hover{ background: var(--primary); color: #fff; }

    .load-more-wrap{ text-align: center; margin: 30px 0 6px; }
    .btn-load-more{
        border: 1px solid var(--primary); background: #fff; color: var(--primary);
        padding: 10px 26px; border-radius: 12px; font-weight: 700; font-size: 13.5px; transition: all .2s ease;
    }
    .btn-load-more:hover{ background: var(--primary); color: #fff; }

    .reveal{ opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .reveal.active{ opacity: 1; transform: translateY(0); }

    ::-webkit-scrollbar{ width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb{ background: var(--primary-soft); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover{ background: var(--primary); }
</style>
</head>
<body>

<div class="bg-decor"><span></span><span></span></div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="sidebar" id="sidebarPembeli">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-bag-check-fill"></i></div>
            <div class="brand-text">
                <h5>Karyaku</h5>
                <small>Marketplace Pembeli</small>
            </div>
        </div>

        <div class="nav-section-title">Menu Utama</div>
        <nav class="nav flex-column">
            <a href="#" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="#" class="nav-link active">
                <i class="bi bi-shop"></i> Marketplace
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-heart-fill"></i> Wishlist
                <span class="badge-count">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-cart-fill"></i> Keranjang
                <span class="badge-count">3</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-receipt"></i> Pesanan Saya
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-cloud-arrow-down-fill"></i> Download Saya
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-person-fill"></i> Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="#" class="logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </aside>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="main-content">

        {{-- ============= TOP MARKETPLACE BAR (search + kategori + keranjang + daftar penjual) ============= --}}
        <div class="market-topbar">
            <div class="row-top">
                <button class="mobile-toggle" id="btnToggleSidebar"><i class="bi bi-list fs-5"></i></button>

                <form class="search-combo" onsubmit="return false;">
                    <select aria-label="Pilih kategori">
                        <option value="">Semua Kategori</option>
                        <option value="canva">Desain Poster Canva</option>
                        <option value="blender">Model & Animasi 3D</option>
                        <option value="logo">Logo & Branding</option>
                        <option value="sosmed">Konten Media Sosial</option>
                        <option value="uiux">UI/UX Design</option>
                        <option value="ilustrasi">Ilustrasi Digital</option>
                    </select>
                    <input type="text" placeholder="Cari jasa, kreator, atau kata kunci...">
                    <button type="submit"><i class="bi bi-search"></i><span class="d-none d-sm-inline">Cari</span></button>
                </form>

                <div class="market-actions">
                    <a href="#" class="btn-jual d-none d-md-inline-flex">
                        <i class="bi bi-shop-window"></i> Daftar Sebagai Penjual
                    </a>
                    <a href="#" class="icon-btn-light" title="Wishlist">
                        <i class="bi bi-heart"></i><span class="dot">5</span>
                    </a>
                    <a href="keranjang.html" class="icon-btn-light" title="Keranjang">
                        <i class="bi bi-cart3"></i><span class="dot">3</span>
                    </a>
                    <button class="icon-btn-light" title="Notifikasi">
                        <i class="bi bi-bell"></i><span class="dot">2</span>
                    </button>
                    <a href="#" class="user-chip">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ffffff&color=1e3a8a" alt="avatar">
                        <div class="d-none d-lg-block">
                            <div class="name">Budi Santoso</div>
                            <div class="role">Pembeli</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- kategori strip --}}
            <div class="kategori-strip">
                <a href="#" class="kategori-item active"><span class="ic"><i class="bi bi-grid-fill"></i></span>Semua</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-image"></i></span>Poster Canva</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-badge-3d"></i></span>3D Blender</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-vector-pen"></i></span>Logo & Brand</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-hash"></i></span>Sosial Media</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-phone"></i></span>UI/UX</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-palette"></i></span>Ilustrasi</a>
                <a href="#" class="kategori-item"><span class="ic"><i class="bi bi-film"></i></span>Video Edit</a>
            </div>
        </div>

        {{-- ============= BANNER PROMO ============= --}}
        <div class="promo-banner">
            <div class="txt">
                <span class="tag">Promo Kreator</span>
                <h2>Diskon 25% Jasa Desain Pilihan</h2>
                <p>Berlaku untuk kategori Poster Canva & Logo Branding sampai akhir bulan ini.</p>
                <a href="#produk" class="btn-jual" style="background:#fff; color:var(--primary-dark);">Lihat Promo <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        {{-- ============= PRODUK / BARANG YANG DIJUAL ============= --}}
        <div class="section-wrap" id="produk">
            <div class="section-head">
                <div>
                    <h4>Barang & Jasa Tersedia</h4>
                    <p>Karya digital dari kreator terverifikasi, siap kamu pesan sekarang</p>
                </div>
                <div class="filter-pills">
                    <button class="filter-pill active">Semua</button>
                    <button class="filter-pill">Terlaris</button>
                    <button class="filter-pill">Terbaru</button>
                    <button class="filter-pill">Rating Tertinggi</button>
                    <button class="filter-pill">Harga Terendah</button>
                </div>
            </div>

            <div class="product-grid" id="productGrid">
                <!-- Produk 1 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">Poster Canva</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=400&q=80" alt="Desain Poster Promosi Kafe">
                    </div>
                    <div class="product-body">
                        <h6>Desain Poster Promosi Kafe & Resto</h6>
                        <div class="product-price">Rp75.000 <small>Rp100.000</small></div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.9</span>
                            <span>Terjual 320</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Dinda+Studio&background=dbeafe&color=1e3a8a" alt="">
                            Dinda Studio
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 2 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">3D Blender</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=400&q=80" alt="Model 3D Karakter Game">
                    </div>
                    <div class="product-body">
                        <h6>Model 3D Karakter Game Low-Poly</h6>
                        <div class="product-price">Rp480.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 5.0</span>
                            <span>Terjual 128</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Rangga&background=dbeafe&color=1e3a8a" alt="">
                            Rangga.blend
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 3 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">Logo & Brand</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=400&q=80" alt="Paket Logo & Brand Kit">
                    </div>
                    <div class="product-body">
                        <h6>Paket Logo & Brand Identity Kit</h6>
                        <div class="product-price">Rp150.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.8</span>
                            <span>Terjual 210</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Kirana+Design&background=dbeafe&color=1e3a8a" alt="">
                            Kirana Design
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 4 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">Sosial Media</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=400&q=80" alt="Konten Feed Instagram">
                    </div>
                    <div class="product-body">
                        <h6>Paket 15 Feed & Story Instagram</h6>
                        <div class="product-price">Rp120.000 <small>Rp160.000</small></div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.7</span>
                            <span>Terjual 176</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Sasi+Creative&background=dbeafe&color=1e3a8a" alt="">
                            Sasi Creative
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 5 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">UI/UX</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=400&q=80" alt="Desain UI Aplikasi Mobile">
                    </div>
                    <div class="product-body">
                        <h6>Desain UI Aplikasi Mobile Lengkap</h6>
                        <div class="product-price">Rp650.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.9</span>
                            <span>Terjual 84</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Nadia+UX&background=dbeafe&color=1e3a8a" alt="">
                            Nadia UX
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 6 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">Ilustrasi</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=400&q=80" alt="Ilustrasi Vektor Karakter">
                    </div>
                    <div class="product-body">
                        <h6>Ilustrasi Vektor Karakter Custom</h6>
                        <div class="product-price">Rp95.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.8</span>
                            <span>Terjual 260</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Ilma+Art&background=dbeafe&color=1e3a8a" alt="">
                            Ilma.art
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 7 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">Poster Canva</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=400&q=80" alt="Poster Event Webinar">
                    </div>
                    <div class="product-body">
                        <h6>Desain Poster Event & Webinar</h6>
                        <div class="product-price">Rp65.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 4.6</span>
                            <span>Terjual 142</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Studio+Elang&background=dbeafe&color=1e3a8a" alt="">
                            Studio Elang
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>

                <!-- Produk 8 -->
                <div class="product-card reveal">
                    <div class="product-thumb">
                        <span class="cat-badge">3D Blender</span>
                        <button class="wish-btn"><i class="bi bi-heart"></i></button>
                        <img src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?auto=format&fit=crop&w=400&q=80" alt="Visualisasi Arsitektur">
                    </div>
                    <div class="product-body">
                        <h6>Render Visualisasi Interior Rumah</h6>
                        <div class="product-price">Rp720.000</div>
                        <div class="product-meta">
                            <span class="rating"><i class="bi bi-star-fill"></i> 5.0</span>
                            <span>Terjual 56</span>
                        </div>
                        <div class="product-seller">
                            <img src="https://ui-avatars.com/api/?name=Vio+3D&background=dbeafe&color=1e3a8a" alt="">
                            Vio 3D Studio
                        </div>
                        <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    </div>
                </div>
            </div>

            <div class="load-more-wrap">
                <button class="btn-load-more">Muat Lebih Banyak <i class="bi bi-chevron-down"></i></button>
            </div>
        </div>

    </main>
</div>

<script>
    // Toggle sidebar mobile
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar   = document.getElementById('sidebarPembeli');
    const overlay   = document.getElementById('sidebarOverlay');
    if (btnToggle) {
        btnToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Klik card produk -> buka halaman detail (kecuali tombol wishlist/keranjang)
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', (e) => {
            if (e.target.closest('.wish-btn') || e.target.closest('.btn-add-cart')) return;
            window.location.href = 'produk-detail.html';
        });
    });

    // Wishlist toggle
    document.querySelectorAll('.wish-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const icon = btn.querySelector('i');
            icon.classList.toggle('bi-heart');
            icon.classList.toggle('bi-heart-fill');
        });
    });

    // Filter pill active state
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
        });
    });

    // Kategori strip active state
    document.querySelectorAll('.kategori-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.kategori-item').forEach(k => k.classList.remove('active'));
            item.classList.add('active');
        });
    });

    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealEls.forEach(el => observer.observe(el));

    // Add to cart mini feedback
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Ditambahkan';
            btn.style.background = 'var(--primary)';
            btn.style.color = '#fff';
            setTimeout(() => {
                btn.innerHTML = original;
                btn.style.background = '';
                btn.style.color = '';
            }, 1200);
        });
    });
</script>
</body>
</html>
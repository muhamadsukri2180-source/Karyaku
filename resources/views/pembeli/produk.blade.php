<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Produk - Karyaku</title>

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
    body{ font-family: 'Poppins', sans-serif; background: var(--primary-light); color: var(--text-dark); }

    /* ---------------- Topbar sederhana (konsisten dengan marketplace) ---------------- */
    .market-topbar{
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary));
        padding: 16px 28px; position: sticky; top: 0; z-index: 1010;
        box-shadow: 0 10px 30px rgba(20,34,92,0.18);
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    }
    .brand-mini{ display:flex; align-items:center; gap:10px; color:#fff; text-decoration:none; flex-shrink:0; }
    .brand-mini .ic{ width:38px; height:38px; background:#fff; color:var(--primary); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; }
    .brand-mini span{ font-weight:700; font-size:16px; }

    .search-combo{ flex: 1 1 360px; display: flex; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 22px rgba(0,0,0,0.15); }
    .search-combo input{ border: none; flex: 1; padding: 11px 14px; font-size: 13.5px; outline: none; min-width: 0; }
    .search-combo button{ border: none; background: var(--coral); color: #fff; padding: 0 18px; font-weight: 700; font-size: 13.5px; }

    .icon-btn-light{
        width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.12); border: none;
        display: flex; align-items: center; justify-content: center; color: #fff; position: relative; font-size: 17px;
        text-decoration:none; flex-shrink:0;
    }
    .icon-btn-light .dot{
        position: absolute; top: 5px; right: 5px; min-width: 15px; height: 15px; padding: 0 3px;
        background: var(--coral); border-radius: 20px; border: 2px solid var(--primary-dark);
        font-size: 9px; font-weight: 700; display: flex; align-items: center; justify-content: center;
    }

    /* ---------------- Breadcrumb ---------------- */
    .breadcrumb-wrap{ padding: 18px 28px 0; font-size: 12.5px; color: var(--text-muted); }
    .breadcrumb-wrap a{ color: var(--text-muted); text-decoration: none; }
    .breadcrumb-wrap a:hover{ color: var(--primary); }

    /* ---------------- Detail section ---------------- */
    .detail-wrap{ max-width: 1180px; margin: 0 auto; padding: 18px 28px 60px; }
    .detail-card{ background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow); padding: 26px; }

    .gallery-main{ border-radius: 16px; overflow: hidden; height: 360px; margin-bottom: 12px; background: var(--primary-light); }
    .gallery-main img{ width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumbs{ display: flex; gap: 10px; }
    .gallery-thumbs img{
        width: 68px; height: 68px; object-fit: cover; border-radius: 10px; cursor: pointer;
        border: 2px solid transparent; transition: border-color .2s ease;
    }
    .gallery-thumbs img.active, .gallery-thumbs img:hover{ border-color: var(--primary); }

    .cat-badge-detail{
        display: inline-block; background: var(--primary-soft); color: var(--primary-dark);
        font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 20px; margin-bottom: 10px;
    }
    .detail-title{ font-weight: 800; font-size: 22px; margin-bottom: 8px; line-height: 1.3; }
    .detail-meta{ display: flex; align-items: center; gap: 16px; font-size: 13px; color: var(--text-muted); margin-bottom: 16px; flex-wrap: wrap; }
    .detail-meta .rating{ color: #f59e0b; font-weight: 700; }

    .price-block{ background: var(--primary-light); border-radius: 14px; padding: 16px 18px; margin-bottom: 18px; }
    .price-block .price{ font-weight: 800; font-size: 26px; color: var(--coral); }
    .price-block .price small{ font-size: 14px; color: var(--text-muted); text-decoration: line-through; font-weight: 500; margin-left: 8px; }
    .price-block .discount-tag{ background: var(--coral); color: #fff; font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 20px; margin-left: 8px; }

    .seller-card{
        display: flex; align-items: center; gap: 12px; border: 1px solid var(--border-color);
        border-radius: 14px; padding: 12px 14px; margin-bottom: 18px;
    }
    .seller-card img{ width: 46px; height: 46px; border-radius: 50%; object-fit: cover; }
    .seller-card .info{ flex: 1; }
    .seller-card .info .nm{ font-weight: 700; font-size: 13.5px; }
    .seller-card .info .st{ font-size: 11.5px; color: var(--text-muted); }
    .seller-card a{
        border: 1px solid var(--primary); color: var(--primary); border-radius: 10px;
        padding: 7px 14px; font-size: 12.5px; font-weight: 700; text-decoration: none; transition: all .2s ease;
    }
    .seller-card a:hover{ background: var(--primary); color: #fff; }

    .qty-box{ display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .qty-control{ display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; }
    .qty-control button{ width: 36px; height: 36px; border: none; background: var(--primary-light); color: var(--primary); font-weight: 700; }
    .qty-control input{ width: 46px; text-align: center; border: none; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); font-weight: 700; }

    .action-row{ display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-detail-cart{
        flex: 1; min-width: 180px; background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary);
        border-radius: 12px; padding: 12px 0; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all .2s ease;
    }
    .btn-detail-cart:hover{ background: var(--primary); color: #fff; }
    .btn-detail-buy{
        flex: 1; min-width: 180px; background: var(--coral); color: #fff; border: none;
        border-radius: 12px; padding: 12px 0; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all .2s ease;
    }
    .btn-detail-buy:hover{ background: var(--coral-dark); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,122,89,.3); }
    .wish-round{
        width: 48px; height: 48px; border-radius: 12px; border: 1px solid var(--border-color); background: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--text-muted); transition: all .2s ease; flex-shrink:0;
    }
    .wish-round:hover, .wish-round.active{ color: var(--coral); border-color: var(--coral); }

    /* Tabs deskripsi */
    .tab-nav{ display: flex; gap: 6px; border-bottom: 1px solid var(--border-color); margin: 30px 0 18px; }
    .tab-nav button{
        border: none; background: transparent; padding: 10px 18px; font-weight: 700; font-size: 13.5px;
        color: var(--text-muted); border-bottom: 3px solid transparent; transition: all .2s ease;
    }
    .tab-nav button.active{ color: var(--primary); border-color: var(--primary); }
    .tab-panel{ display: none; font-size: 13.5px; color: var(--text-dark); line-height: 1.8; }
    .tab-panel.active{ display: block; }

    .review-item{ display: flex; gap: 12px; padding: 14px 0; border-bottom: 1px solid var(--border-color); }
    .review-item img{ width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
    .review-item .nm{ font-weight: 700; font-size: 13px; }
    .review-item .rt{ color: #f59e0b; font-size: 12px; }
    .review-item p{ font-size: 12.5px; color: var(--text-muted); margin: 4px 0 0; }

    /* Produk lain dari kreator */
    .other-title{ font-weight: 700; font-size: 16px; margin: 34px 0 14px; }
    .other-grid{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    @media (max-width: 992px){ .other-grid{ grid-template-columns: repeat(2, 1fr); } }
    .other-card{ background: #fff; border-radius: 14px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow); transition: transform .2s ease; }
    .other-card:hover{ transform: translateY(-4px); }
    .other-card img{ width: 100%; height: 110px; object-fit: cover; }
    .other-card .b{ padding: 10px; }
    .other-card h6{ font-size: 12.5px; font-weight: 600; margin: 0 0 6px; min-height: 32px; }
    .other-card .p{ color: var(--coral); font-weight: 800; font-size: 13px; }

    @media (max-width: 768px){
        .detail-wrap{ padding: 14px 16px 40px; }
        .detail-card{ padding: 16px; }
        .gallery-main{ height: 240px; }
    }
</style>
</head>
<body>

<div class="market-topbar">
    <a href="marketplace-pembeli.html" class="brand-mini"><span class="ic"><i class="bi bi-bag-check-fill"></i></span><span>Karyaku</span></a>
    <form class="search-combo" onsubmit="return false;">
        <input type="text" placeholder="Cari jasa, kreator, atau kata kunci...">
        <button type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="#" class="icon-btn-light" title="Wishlist"><i class="bi bi-heart"></i><span class="dot">5</span></a>
    <a href="keranjang" class="icon-btn-light" title="Keranjang"><i class="bi bi-cart3"></i><span class="dot">3</span></a>
</div>

<div class="breadcrumb-wrap">
    <a href="marketplace-pembeli.html">Marketplace</a> / <a href="marketplace-pembeli.html">Poster Canva</a> / <span>Desain Poster Promosi Kafe & Resto</span>
</div>

<div class="detail-wrap">
    <div class="detail-card">
        <div class="row g-4">
            <!-- Gallery -->
            <div class="col-lg-6">
                <div class="gallery-main">
                    <img id="mainImg" src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=80" alt="Desain Poster Promosi Kafe">
                </div>
                <div class="gallery-thumbs">
                    <img class="active" src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=200&q=80" onclick="swapImg(this)">
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=200&q=80" onclick="swapImg(this)">
                    <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=200&q=80" onclick="swapImg(this)">
                    <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=200&q=80" onclick="swapImg(this)">
                </div>
            </div>

            <!-- Info -->
            <div class="col-lg-6">
                <span class="cat-badge-detail">Poster Canva</span>
                <h1 class="detail-title">Desain Poster Promosi Kafe & Resto</h1>
                <div class="detail-meta">
                    <span class="rating"><i class="bi bi-star-fill"></i> 4.9 (312 ulasan)</span>
                    <span><i class="bi bi-bag-check"></i> Terjual 320</span>
                    <span><i class="bi bi-chat-dots"></i> Respon cepat</span>
                </div>

                <div class="price-block">
                    <span class="price">Rp75.000</span>
                    <small>Rp100.000</small>
                    <span class="discount-tag">Hemat 25%</span>
                </div>

                <div class="seller-card">
                    <img src="https://ui-avatars.com/api/?name=Dinda+Studio&background=dbeafe&color=1e3a8a" alt="">
                    <div class="info">
                        <div class="nm">Dinda Studio</div>
                        <div class="st"><i class="bi bi-geo-alt"></i> Bandung &bull; Online 5 menit lalu</div>
                    </div>
                    <a href="#"><i class="bi bi-chat"></i> Chat</a>
                </div>

                <div class="qty-box">
                    <span style="font-size:13px; font-weight:600;">Jumlah Paket</span>
                    <div class="qty-control">
                        <button onclick="changeQty(-1)">-</button>
                        <input type="text" id="qtyInput" value="1" readonly>
                        <button onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="action-row">
                    <button class="wish-round" id="wishDetail" onclick="toggleWishDetail()"><i class="bi bi-heart"></i></button>
                    <button class="btn-detail-cart" onclick="addToCartFeedback(this)"><i class="bi bi-cart-plus"></i> Tambah Keranjang</button>
                    <a href="keranjang" class="btn-detail-buy"><i class="bi bi-lightning-charge-fill"></i> Beli Sekarang</a>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tab-nav">
            <button class="active" onclick="switchTab(this,'deskripsi')">Deskripsi</button>
            <button onclick="switchTab(this,'ulasan')">Ulasan (312)</button>
            <button onclick="switchTab(this,'faq')">FAQ</button>
        </div>

        <div class="tab-panel active" id="tab-deskripsi">
            <p>Dapatkan desain poster promosi yang menarik perhatian pelanggan untuk kafe atau restoran kamu. Termasuk revisi hingga 2 kali, format siap cetak (PDF/JPG), dan file mentah Canva agar mudah kamu edit sendiri di kemudian hari.</p>
            <ul>
                <li>Waktu pengerjaan 1-2 hari kerja</li>
                <li>2x revisi minor</li>
                <li>Ukuran A3 & Instagram Post</li>
                <li>File final PDF, JPG, dan link Canva</li>
            </ul>
        </div>
        <div class="tab-panel" id="tab-ulasan">
            <div class="review-item">
                <img src="https://ui-avatars.com/api/?name=Rizky+A&background=dbeafe&color=1e3a8a" alt="">
                <div>
                    <div class="nm">Rizky A. <span class="rt">★★★★★</span></div>
                    <p>Hasilnya rapi dan sesuai brief, komunikasi juga responsif. Recommended!</p>
                </div>
            </div>
            <div class="review-item">
                <img src="https://ui-avatars.com/api/?name=Melati+P&background=dbeafe&color=1e3a8a" alt="">
                <div>
                    <div class="nm">Melati P. <span class="rt">★★★★★</span></div>
                    <p>Cepat banget prosesnya, revisi juga gampang. Puas dengan hasilnya.</p>
                </div>
            </div>
        </div>
        <div class="tab-panel" id="tab-faq">
            <p><strong>Apakah bisa request warna sesuai brand saya?</strong><br>Bisa, cukup sertakan warna atau logo brand di catatan pesanan.</p>
            <p><strong>Berapa lama waktu pengerjaan?</strong><br>Estimasi 1-2 hari kerja setelah brief lengkap diterima.</p>
        </div>

        <!-- Produk lain -->
        <div class="other-title">Produk Lain dari Dinda Studio</div>
        <div class="other-grid">
            <div class="other-card">
                <img src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=300&q=80" alt="">
                <div class="b"><h6>Poster Event & Webinar</h6><span class="p">Rp65.000</span></div>
            </div>
            <div class="other-card">
                <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=300&q=80" alt="">
                <div class="b"><h6>Paket Feed Instagram</h6><span class="p">Rp120.000</span></div>
            </div>
            <div class="other-card">
                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=300&q=80" alt="">
                <div class="b"><h6>Menu Digital Kafe</h6><span class="p">Rp55.000</span></div>
            </div>
            <div class="other-card">
                <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=300&q=80" alt="">
                <div class="b"><h6>Logo Sederhana</h6><span class="p">Rp90.000</span></div>
            </div>
        </div>
    </div>
</div>

<script>
    function swapImg(el){
        document.getElementById('mainImg').src = el.src.replace('w=200','w=800');
        document.querySelectorAll('.gallery-thumbs img').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    let qty = 1;
    function changeQty(delta){
        qty = Math.max(1, qty + delta);
        document.getElementById('qtyInput').value = qty;
    }

    function toggleWishDetail(){
        const btn = document.getElementById('wishDetail');
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    }

    function switchTab(btn, id){
        document.querySelectorAll('.tab-nav button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + id).classList.add('active');
    }

    function addToCartFeedback(btn){
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Ditambahkan';
        btn.style.background = 'var(--primary)';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.background = '';
            btn.style.color = '';
        }, 1200);
    }
</script>
</body>
</html>
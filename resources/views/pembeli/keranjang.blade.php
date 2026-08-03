<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang Saya - Karyaku</title>

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
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e5edff;
        --radius: 18px;
        --shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
    }
    *{ box-sizing: border-box; }
    body{ font-family: 'Poppins', sans-serif; background: var(--primary-light); color: var(--text-dark); }

    .market-topbar{
        background: linear-gradient(120deg, var(--primary-darker), var(--primary-dark) 60%, var(--primary));
        padding: 16px 28px; position: sticky; top: 0; z-index: 1010;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
        box-shadow: 0 10px 30px rgba(20,34,92,0.18);
    }
    .brand-mini{ display:flex; align-items:center; gap:10px; color:#fff; text-decoration:none; }
    .brand-mini .ic{ width:38px; height:38px; background:#fff; color:var(--primary); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; }
    .brand-mini span{ font-weight:700; font-size:16px; }
    .topbar-title{ color:#fff; font-weight:700; font-size:16px; margin-left:8px; }

    .cart-wrap{ max-width: 1080px; margin: 26px auto 60px; padding: 0 28px; }
    .cart-head{ display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
    .cart-head h2{ font-weight: 800; font-size: 22px; margin: 0; }
    .cart-head p{ margin: 2px 0 0; color: var(--text-muted); font-size: 13px; }

    .cart-list{ background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
    .cart-select-all{ display: flex; align-items: center; gap: 10px; padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-size: 13.5px; font-weight: 600; }
    .cart-select-all input{ width: 18px; height: 18px; accent-color: var(--primary); }

    .cart-item{ display: flex; align-items: center; gap: 14px; padding: 18px 20px; border-bottom: 1px solid var(--border-color); }
    .cart-item:last-child{ border-bottom: none; }
    .cart-item input[type=checkbox]{ width: 18px; height: 18px; accent-color: var(--primary); flex-shrink:0; }
    .cart-item img{ width: 72px; height: 72px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
    .cart-item .info{ flex: 1; min-width: 0; }
    .cart-item .info h6{ font-size: 14px; font-weight: 700; margin: 0 0 4px; }
    .cart-item .info .seller{ font-size: 12px; color: var(--text-muted); }
    .cart-item .price{ font-weight: 800; color: var(--coral); font-size: 14.5px; margin-top: 4px; }

    .qty-control{ display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; flex-shrink: 0; }
    .qty-control button{ width: 30px; height: 30px; border: none; background: var(--primary-light); color: var(--primary); font-weight: 700; }
    .qty-control input{ width: 38px; text-align: center; border: none; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); font-weight: 700; font-size: 13px; }

    .remove-btn{ border: none; background: transparent; color: var(--text-muted); font-size: 17px; transition: color .2s ease; flex-shrink:0; }
    .remove-btn:hover{ color: #ef4444; }

    .promo-box{ background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow); padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .promo-box input{ flex: 1; min-width: 200px; border: 1px solid var(--border-color); border-radius: 10px; padding: 9px 14px; font-size: 13px; outline: none; }
    .promo-box input:focus{ border-color: var(--primary); }
    .promo-box button{ background: var(--primary); color: #fff; border: none; border-radius: 10px; padding: 9px 20px; font-weight: 700; font-size: 13px; }

    .summary-card{ background: #fff; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow); padding: 22px; }
    .summary-card h5{ font-weight: 700; font-size: 15.5px; margin-bottom: 16px; }
    .summary-row{ display: flex; justify-content: space-between; font-size: 13.5px; color: var(--text-muted); margin-bottom: 10px; }
    .summary-row.total{ color: var(--text-dark); font-weight: 800; font-size: 16px; border-top: 1px solid var(--border-color); padding-top: 14px; margin-top: 6px; }
    .summary-row.total span:last-child{ color: var(--coral); }

    .btn-checkout{ width: 100%; background: var(--coral); color: #fff; border: none; border-radius: 12px; padding: 13px 0; font-weight: 700; font-size: 14.5px; margin-top: 16px; transition: all .2s ease; }
    .btn-checkout:hover{ background: var(--coral-dark); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,122,89,.3); }
    .btn-checkout:disabled{ background: #cbd5e1; cursor: not-allowed; transform:none; box-shadow:none; }

    .empty-cart{ text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-cart i{ font-size: 48px; color: var(--border-color); margin-bottom: 14px; }
    .empty-cart a{ display: inline-flex; margin-top: 14px; background: var(--primary); color: #fff; text-decoration: none; padding: 10px 22px; border-radius: 10px; font-weight: 700; font-size: 13.5px; }

    @media (max-width: 768px){
        .cart-wrap{ padding: 0 14px; }
        .cart-item{ flex-wrap: wrap; }
        .cart-item .info{ flex-basis: 100%; order: 3; }
    }
</style>
</head>
<body>

<div class="market-topbar">
    <a href="marketplace-pembeli.html" class="brand-mini"><span class="ic"><i class="bi bi-bag-check-fill"></i></span><span>Karyaku</span></a>
    <span class="topbar-title">/ Keranjang Saya</span>
</div>

<div class="cart-wrap">
    <div class="cart-head">
        <div>
            <h2>Keranjang Saya</h2>
            <p>3 jasa siap kamu checkout</p>
        </div>
        <a href="marketplace-pembeli.html" style="color:var(--primary); font-weight:600; font-size:13px; text-decoration:none;"><i class="bi bi-arrow-left"></i> Lanjut Belanja</a>
    </div>

    <div class="cart-list" id="cartList">
        <div class="cart-select-all">
            <input type="checkbox" id="selectAll" checked>
            <label for="selectAll">Pilih Semua Item</label>
        </div>

        <div class="cart-item" data-price="75000">
            <input type="checkbox" class="item-check" checked>
            <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=200&q=80" alt="">
            <div class="info">
                <h6>Desain Poster Promosi Kafe & Resto</h6>
                <span class="seller"><i class="bi bi-shop"></i> Dinda Studio</span>
                <div class="price">Rp75.000</div>
            </div>
            <div class="qty-control">
                <button onclick="changeQty(this,-1)">-</button>
                <input type="text" class="qty-val" value="1" readonly>
                <button onclick="changeQty(this,1)">+</button>
            </div>
            <button class="remove-btn" onclick="removeItem(this)"><i class="bi bi-trash3"></i></button>
        </div>

        <div class="cart-item" data-price="480000">
            <input type="checkbox" class="item-check" checked>
            <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=200&q=80" alt="">
            <div class="info">
                <h6>Model 3D Karakter Game Low-Poly</h6>
                <span class="seller"><i class="bi bi-shop"></i> Rangga.blend</span>
                <div class="price">Rp480.000</div>
            </div>
            <div class="qty-control">
                <button onclick="changeQty(this,-1)">-</button>
                <input type="text" class="qty-val" value="1" readonly>
                <button onclick="changeQty(this,1)">+</button>
            </div>
            <button class="remove-btn" onclick="removeItem(this)"><i class="bi bi-trash3"></i></button>
        </div>

        <div class="cart-item" data-price="150000">
            <input type="checkbox" class="item-check" checked>
            <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=200&q=80" alt="">
            <div class="info">
                <h6>Paket Logo & Brand Identity Kit</h6>
                <span class="seller"><i class="bi bi-shop"></i> Kirana Design</span>
                <div class="price">Rp150.000</div>
            </div>
            <div class="qty-control">
                <button onclick="changeQty(this,-1)">-</button>
                <input type="text" class="qty-val" value="1" readonly>
                <button onclick="changeQty(this,1)">+</button>
            </div>
            <button class="remove-btn" onclick="removeItem(this)"><i class="bi bi-trash3"></i></button>
        </div>
    </div>

    <div class="promo-box">
        <i class="bi bi-tag-fill" style="color:var(--coral); font-size:18px;"></i>
        <input type="text" placeholder="Masukkan kode promo">
        <button>Pakai Kode</button>
    </div>

    <div class="summary-card">
        <h5>Ringkasan Belanja</h5>
        <div class="summary-row"><span>Subtotal</span><span id="sumSubtotal">Rp705.000</span></div>
        <div class="summary-row"><span>Biaya Layanan</span><span id="sumFee">Rp5.000</span></div>
        <div class="summary-row"><span>Diskon</span><span id="sumDiskon">-Rp0</span></div>
        <div class="summary-row total"><span>Total Pembayaran</span><span id="sumTotal">Rp710.000</span></div>
        <button class="btn-checkout" id="btnCheckout"><i class="bi bi-lock-fill"></i> Checkout Sekarang</button>
    </div>
</div>

<script>
    const FEE = 5000;

    function formatRp(num){
        return 'Rp' + num.toLocaleString('id-ID');
    }

    function recalc(){
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const checked = item.querySelector('.item-check').checked;
            if(!checked) return;
            const price = parseInt(item.dataset.price, 10);
            const qty = parseInt(item.querySelector('.qty-val').value, 10);
            subtotal += price * qty;
        });
        const anyChecked = document.querySelectorAll('.item-check:checked').length > 0;
        const fee = anyChecked ? FEE : 0;
        const total = subtotal + fee;

        document.getElementById('sumSubtotal').textContent = formatRp(subtotal);
        document.getElementById('sumFee').textContent = formatRp(fee);
        document.getElementById('sumTotal').textContent = formatRp(total);

        const btn = document.getElementById('btnCheckout');
        btn.disabled = !anyChecked;

        // update select all state
        const allChecks = document.querySelectorAll('.item-check');
        document.getElementById('selectAll').checked = allChecks.length > 0 && [...allChecks].every(c => c.checked);
    }

    function changeQty(btn, delta){
        const item = btn.closest('.cart-item');
        const input = item.querySelector('.qty-val');
        let val = Math.max(1, parseInt(input.value, 10) + delta);
        input.value = val;
        recalc();
    }

    function removeItem(btn){
        const item = btn.closest('.cart-item');
        item.remove();
        recalc();
        if(document.querySelectorAll('.cart-item').length === 0){
            document.getElementById('cartList').innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <div style="font-weight:700; color:var(--text-dark); font-size:15px;">Keranjang kamu masih kosong</div>
                    <p style="margin:4px 0 0; font-size:13px;">Yuk mulai jelajahi karya digital dari kreator terbaik</p>
                    <a href="marketplace-pembeli.html">Mulai Belanja</a>
                </div>`;
        }
    }

    document.querySelectorAll('.item-check').forEach(chk => chk.addEventListener('change', recalc));
    document.getElementById('selectAll').addEventListener('change', function(){
        document.querySelectorAll('.item-check').forEach(c => c.checked = this.checked);
        recalc();
    });

    document.getElementById('btnCheckout').addEventListener('click', function(){
        if(this.disabled) return;
        this.innerHTML = '<i class="bi bi-check2-circle"></i> Pesanan Diproses...';
        setTimeout(() => { this.innerHTML = '<i class="bi bi-lock-fill"></i> Checkout Sekarang'; }, 1500);
    });

    recalc();
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Center') - Karyaku</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
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
            --border-color: #e2e8f0;
            --radius: 16px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 10px 25px rgba(37, 99, 235, 0.12);
        }

        * { box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; color: var(--text-dark); margin: 0; }
        a { text-decoration: none; }

        /* SIDEBAR STYLES */
        .seller-sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1020;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--coral), var(--coral-dark));
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(255, 122, 89, 0.3);
        }

        .brand-title h6 { margin: 0; font-weight: 800; font-size: 16px; color: var(--text-dark); }
        .brand-title small { font-size: 11px; color: var(--text-muted); font-weight: 600; }

        .sidebar-nav {
            padding: 16px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
            flex: 1;
        }

        .nav-category-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 14px 6px;
        }

        .seller-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s ease;
        }

        .seller-nav-link i { font-size: 17px; width: 22px; text-align: center; }
        .seller-nav-link:hover { background: var(--primary-light); color: var(--primary); }
        .seller-nav-link.active { background: var(--primary); color: #fff; font-weight: 600; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background: #fafafa;
        }

        /* MAIN CONTENT AREA */
        .seller-main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .seller-header {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1010;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
        }

        .seller-content {
            padding: 28px 32px 60px;
            flex: 1;
        }

        .card-box {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .hover-shadow { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-shadow:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

        @media (max-width: 992px) {
            .seller-sidebar { transform: translateX(-100%); }
            .seller-sidebar.show { transform: translateX(0); }
            .seller-main { margin-left: 0; }
            .seller-header { padding: 0 16px; }
            .seller-content { padding: 20px 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="seller-sidebar" id="sellerSidebar">
        <div>
            <div class="sidebar-brand">
                <div class="brand-badge"><i class="bi bi-shop"></i></div>
                <div class="brand-title">
                    <h6>Seller Center</h6>
                    <small>Karyaku Kreator</small>
                </div>
            </div>

            <div class="sidebar-nav">
                <div class="nav-category-label">Menu Utama</div>
                <a href="{{ route('penjual.dashboard') }}" class="seller-nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="{{ route('penjual.produk.index') }}" class="seller-nav-link {{ request()->routeIs('penjual.produk*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i> Produk Saya
                </a>
                <a href="{{ route('penjual.pesanan.index') }}" class="seller-nav-link {{ request()->routeIs('penjual.pesanan*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff"></i> Pesanan Masuk
                </a>
                <a href="{{ route('penjual.iklan.index') }}" class="seller-nav-link {{ request()->routeIs('penjual.iklan*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone-fill"></i> Iklan & Promosi
                </a>

                <div class="nav-category-label">Keuangan & Paket</div>
                <a href="{{ route('penjual.keuangan.index') }}" class="seller-nav-link {{ request()->routeIs('penjual.keuangan*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> Saldo & Penarikan
                </a>
                <a href="{{ route('penjual.membership.index') }}" class="seller-nav-link {{ request()->routeIs('penjual.membership*') ? 'active' : '' }}">
                    <i class="bi bi-gem"></i> Paket Membership
                </a>

                <div class="nav-category-label">Mode Pembeli</div>
                <a href="{{ route('pembeli.marketplace') }}" class="seller-nav-link text-primary fw-semibold bg-primary-subtle bg-opacity-50">
                    <i class="bi bi-bag-check-fill text-primary"></i> Belanja Karya Lain
                </a>
                <a href="{{ route('pembeli.dashboard') }}" class="seller-nav-link text-secondary">
                    <i class="bi bi-person-workspace"></i> Dashboard Pembeli
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            @php
                $sideUser = Auth::user();
                $sideMembership = $sideUser->membership->name ?? 'Standar';
            @endphp
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ $sideUser->avatar ? asset('storage/' . $sideUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($sideUser->name) . '&background=2563eb&color=fff' }}" 
                     class="rounded-circle border" style="width: 38px; height: 38px;" alt="avatar">
                <div class="overflow-hidden">
                    <div class="fw-bold text-dark text-truncate small">{{ $sideUser->name }}</div>
                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">{{ $sideMembership }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="seller-main">
        <!-- TOPBAR -->
        <header class="seller-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" type="button" id="sidebarToggleBtn">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark d-none d-sm-block">@yield('title', 'Seller Center')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                </a>

                <a href="{{ route('pembeli.marketplace') }}" class="btn btn-outline-secondary btn-sm fw-semibold d-none d-md-inline-flex">
                    <i class="bi bi-shop me-1"></i> Ke Marketplace
                </a>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="seller-content">
            {{-- ALERT SESSION --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show card-box p-3 border-0 border-start border-4 border-success mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="fw-medium small">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show card-box p-3 border-0 border-start border-4 border-danger mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <span class="fw-medium small">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggleBtn')?.addEventListener('click', function() {
            document.getElementById('sellerSidebar')?.classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>

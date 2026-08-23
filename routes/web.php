<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\SellerRegistrationController;
use App\Http\Controllers\VerifikatorController;
use App\Http\Controllers\CsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC / LANDING PAGE
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('landing');


// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');

    // --- RUTE LUPA & RESET PASSWORD ---
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->middleware('guest')->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->middleware('guest')->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.store');
});

// Logout (Harus Authenticated)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==========================================
// 3. ADMIN ROUTES (AUTH + ROLE: ADMIN)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [AdminController::class, 'dashboardChartData'])->name('dashboard.chartData');

    // 2. Maintenance Mode & Backup
    Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('maintenance');
    Route::post('/toggle-maintenance', [AdminController::class, 'toggleMaintenance'])->name('toggleMaintenance');
    Route::post('/backup/create', [AdminController::class, 'createBackup'])->name('backup.create');
    Route::get('/backup/{filename}/download', [AdminController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/{filename}', [AdminController::class, 'deleteBackup'])->name('backup.delete');

    // 3. Manajemen Pengguna (User, Verifikator, & Penjual)
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('admin/users/{id}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');

    Route::get('/users/verifikator', [AdminController::class, 'verifikator'])->name('users.verifikator');
    Route::post('/users/add-verifier', [AdminController::class, 'addVerifier'])->name('users.addVerifier');
    Route::put('/users/verifier/{id}', [AdminController::class, 'updateVerifier'])->name('users.updateVerifier');
    Route::delete('/users/verifier/{id}', [AdminController::class, 'deleteVerifier'])->name('users.deleteVerifier');

    Route::post('/users/approve-seller/{id}', [AdminController::class, 'approveSeller'])->name('users.approveSeller');
    Route::post('/users/reject-seller/{id}', [AdminController::class, 'rejectSeller'])->name('users.rejectSeller');

    // 4. Manajemen Akun & Layanan Customer Service
    Route::get('/manajemen/akun-service', [AdminController::class, 'serviceAccounts'])->name('manajemen.akun_service');
    Route::post('/manajemen/akun-service', [AdminController::class, 'storeServiceAccount'])->name('manajemen.akun_service.store');
    Route::delete('/manajemen/akun-service/{id}', [AdminController::class, 'deleteServiceAccount'])->name('manajemen.akun_service.destroy');
    Route::put('/manajemen/ticket/{id}', [AdminController::class, 'updateTicketStatus'])->name('manajemen.ticket.update');

    // 5. Katalog & Kategori
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('products.approve');
    Route::post('/products/takedown/{id}', [AdminController::class, 'takedownProduct'])->name('products.takedown');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // 6. Transaksi & Keuangan
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionDetail'])->name('transactions.detail');

    // 7. Penarikan Saldo (Withdrawal)
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawals.process');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

    // 8. Membership Card Management
    Route::get('/memberships', [AdminController::class, 'memberships'])->name('memberships');
    Route::post('/memberships', [AdminController::class, 'storeMembership'])->name('memberships.store');
    Route::put('/memberships/{id}', [AdminController::class, 'updateMembership'])->name('memberships.update');
    Route::delete('/memberships/{id}', [AdminController::class, 'deleteMembership'])->name('memberships.delete');

    // 9. Laporan Pelanggaran (Sistem)
    Route::get('/pelanggaran', [AdminController::class, 'pelanggaran'])->name('pelanggaran');
    Route::post('/pelanggaran/user/{id}', [AdminController::class, 'tindakUserPelanggaran'])->name('pelanggaran.user');
    Route::post('/pelanggaran/produk/{id}', [AdminController::class, 'tindakProdukPelanggaran'])->name('pelanggaran.produk');

    // 10. Profile Admin
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // 11. Manajemen Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::put('/notifications/{id}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // 12. Keamanan System & Monitoring IP
    Route::get('/security/verify', [AdminController::class, 'securityVerifyPage'])->name('security.verify');
    Route::post('/security/verify', [AdminController::class, 'securityProcessVerify'])->name('security.process_verify');

    Route::get('/security/ip-monitor', [AdminController::class, 'securityIndex'])->name('security.index');
    Route::post('/security/allowed-ip', [AdminController::class, 'securityStoreAllowedIp'])->name('security.allowed_ip.store');
    Route::delete('/security/allowed-ip/{id}', [AdminController::class, 'securityDestroyAllowedIp'])->name('security.allowed_ip.destroy');
    Route::post('/security/toggle/{id}', [AdminController::class, 'securityToggleStatus'])->name('security.toggle');
    Route::delete('/security/log/{id}', [AdminController::class, 'securityDestroyLog'])->name('security.log.destroy');
});


/*
|--------------------------------------------------------------------------
| Verifikator Routes (Admin & CS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:verifikator'])
    ->prefix('verifikator')
    ->name('verifikator.')
    ->group(function () {

        // ==========================================
        // 1. DASHBOARD & PENDAFTARAN (READ-ONLY CS & ADMIN)
        // ==========================================
        Route::get('/dashboard', [VerifikatorController::class, 'dashboard'])->name('dashboard');

        // Pendaftaran / Identitas KTP
        Route::get('/identitas', [VerifikatorController::class, 'identitas'])->name('identitas');
        Route::get('/pendaftaran/{id}', [VerifikatorController::class, 'show'])->name('pendaftaran.show');

        // Modul Produk & Jasa
        Route::get('/produk', [VerifikatorController::class, 'produk'])->name('produk');
        Route::get('/produk/{id}', [VerifikatorController::class, 'showProduk'])->name('produk.show');

        // Modul Transaksi Pembayaran
        Route::get('/pembayaran', [VerifikatorController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [VerifikatorController::class, 'showPembayaran'])->name('pembayaran.show');

        // Modul Laporan Pelanggaran
        Route::get('/laporan', [VerifikatorController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/{id}', [VerifikatorController::class, 'showLaporan'])->name('laporan.show');

        // ==========================================
        // 2. EKSEKUSI / ACTIONS (KHUSUS ADMIN)
        // ==========================================
        
        // Eksekusi Pendaftaran / Identitas
        Route::post('/pendaftaran/{id}/approve', [VerifikatorController::class, 'approve'])->name('pendaftaran.approve');
        Route::post('/pendaftaran/{id}/reject', [VerifikatorController::class, 'reject'])->name('pendaftaran.reject');

        // Eksekusi Produk
        Route::post('/produk/{id}/approve', [VerifikatorController::class, 'approveProduk'])->name('produk.approve');
        Route::post('/produk/{id}/reject', [VerifikatorController::class, 'rejectProduk'])->name('produk.reject');

        // Eksekusi Pembayaran
        Route::post('/pembayaran/{id}/approve', [VerifikatorController::class, 'approvePembayaran'])->name('pembayaran.approve');
        Route::post('/pembayaran/{id}/reject', [VerifikatorController::class, 'rejectPembayaran'])->name('pembayaran.reject');

        // Eksekusi Action Laporan
        Route::post('/laporan/{id}/action', [VerifikatorController::class, 'actionLaporan'])->name('laporan.action');
    });
    


// ==========================================
// 5. PENJUAL ROUTES
// ==========================================
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualController::class, 'dashboard'])->name('dashboard');
});


// ==========================================
// 6. PEMBELI ROUTES
// ==========================================
Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/dashboard', [PembeliController::class, 'dashboard'])->name('dashboard');

    Route::get('/marketplace', [PembeliController::class, 'marketplace'])->name('marketplace');
    Route::get('/produk/{id}', [PembeliController::class, 'produkDetail'])->name('produk.detail');

    Route::get('/keranjang', [PembeliController::class, 'keranjangIndex'])->name('keranjang');
    Route::post('/keranjang', [PembeliController::class, 'keranjangStore'])->name('keranjang.store');
    Route::put('/keranjang/{id}', [PembeliController::class, 'keranjangUpdate'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [PembeliController::class, 'keranjangDestroy'])->name('keranjang.destroy');

    Route::post('/checkout', [PembeliController::class, 'checkout'])->name('checkout');

    Route::post('/wishlist/{productId}', [PembeliController::class, 'wishlistToggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [PembeliController::class, 'wishlistIndex'])->name('wishlist');

    Route::get('/pesanan', [PembeliController::class, 'pesananIndex'])->name('pesanan');
    Route::get('/pesanan/{id}', [PembeliController::class, 'pesananDetail'])->name('pesanan.detail');

    Route::get('/download', [PembeliController::class, 'downloadIndex'])->name('download');

    Route::get('/profile', [PembeliController::class, 'profile'])->name('profile');
    Route::put('/profile', [PembeliController::class, 'updateProfile'])->name('profile.update');

    // Customer Service (User / Pembeli Side)
    Route::get('/customer-service', [CustomerServiceController::class, 'userIndex'])->name('service.index');
    Route::post('/customer-service', [CustomerServiceController::class, 'userStore'])->name('service.store');

    // Membership -> Upgrade jadi Penjual
    Route::get('/membership', [PembeliController::class, 'membershipIndex'])->name('membership');
    Route::post('/membership/{id}/purchase', [PembeliController::class, 'membershipPurchase'])->name('membership.purchase');

    // Notifikasi dari Admin
    Route::get('/notifikasi', [PembeliController::class, 'notificationsIndex'])->name('notifications');

    // Pendaftaran Menjadi Penjual
    Route::get('/daftar-penjual', [SellerRegistrationController::class, 'create'])->name('seller.registration.create');
    Route::get('/daftar-penjual-alias', [SellerRegistrationController::class, 'create'])->name('daftar.penjual');
    Route::post('/daftar-penjual', [SellerRegistrationController::class, 'store'])->name('seller.registration.store');
    Route::get('/daftar-penjual/status', [SellerRegistrationController::class, 'status'])->name('seller.registration.status');
    Route::delete('/daftar-penjual/cancel', [SellerRegistrationController::class, 'cancel'])->name('seller.registration.cancel');

    Route::get('/peringatan', [PembeliController::class, 'peringatanIndex'])->name('peringatan');
});


// ==========================================
// 7. LAPORAN PELANGGARAN (pembeli & penjual, siapapun yang login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/riwayat', [ReportController::class, 'index'])->name('reports.index');
});


// ==========================================
// 8. CUSTOMER SERVICE ROUTES (AUTH + ROLE: CUSTOMER_SERVICE)
// ==========================================
Route::middleware(['auth', 'role:customer_service'])->prefix('cs')->name('cs.')->group(function () {
    Route::get('/dashboard', [CsController::class, 'dashboard'])->name('dashboard');

    Route::get('/laporan', [CsController::class, 'laporan'])->name('laporan');
    Route::post('/laporan/{id}/tindak', [CsController::class, 'tindakLaporan'])->name('laporan.tindak');

    Route::get('/transaksi', [CsController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/{id}', [CsController::class, 'transaksiDetail'])->name('transaksi.detail');

    Route::get('/notifikasi', [CsController::class, 'notifikasi'])->name('notifikasi');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\NotificationController;

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

    Route::get('/users/verifikator', [AdminController::class, 'verifikator'])->name('users.verifikator');
    Route::post('/users/add-verifier', [AdminController::class, 'addVerifier'])->name('users.addVerifier');
    Route::put('/users/verifier/{id}', [AdminController::class, 'updateVerifier'])->name('users.updateVerifier');
    Route::delete('/users/verifier/{id}', [AdminController::class, 'deleteVerifier'])->name('users.deleteVerifier');

    Route::post('/users/approve-seller/{id}', [AdminController::class, 'approveSeller'])->name('users.approveSeller');
    Route::post('/users/reject-seller/{id}', [AdminController::class, 'rejectSeller'])->name('users.rejectSeller');

    // 4. Katalog & Kategori
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('products.approve');
    Route::post('/products/takedown/{id}', [AdminController::class, 'takedownProduct'])->name('products.takedown');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // 5. Transaksi & Keuangan
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionDetail'])->name('transactions.detail');

    // 6. Penarikan Saldo (Withdrawal)
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawals.process');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

    // 7. Membership Card Management
    Route::get('/memberships', [AdminController::class, 'memberships'])->name('memberships');
    Route::post('/memberships', [AdminController::class, 'storeMembership'])->name('memberships.store');
    Route::put('/memberships/{id}', [AdminController::class, 'updateMembership'])->name('memberships.update');
    Route::delete('/memberships/{id}', [AdminController::class, 'deleteMembership'])->name('memberships.delete');

    // 8. Laporan Pelanggaran (Sistem)
    Route::get('/pelanggaran', [AdminController::class, 'pelanggaran'])->name('pelanggaran');
    Route::post('/pelanggaran/user/{id}', [AdminController::class, 'tindakUserPelanggaran'])->name('pelanggaran.user');
    Route::post('/pelanggaran/produk/{id}', [AdminController::class, 'tindakProdukPelanggaran'])->name('pelanggaran.produk');

    // 9. Profile Admin
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');



    // ==========================================
    // 9. MANAJEMEN NOTIFIKASI
    // ==========================================
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::put('/notifications/{id}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');



});


// ==========================================
// 4. VERIFIKATOR ROUTES
// ==========================================
Route::middleware(['auth', 'role:verifikator'])->prefix('verifikator')->group(function () {
    Route::get('/dashboard', function () {
        return view('verifikator.dashboard');
    })->name('verifikator.dashboard');
});


// ==========================================
// 5. PENJUAL ROUTES
// ==========================================
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->group(function () {
    Route::get('/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');
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
});
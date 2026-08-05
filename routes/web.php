<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PembeliController;

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
});

// Logout (Harus Authenticated)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==========================================
// 3. ADMIN ROUTES (AUTH + ROLE: ADMIN)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // ---------- 1. Dashboard & Maintenance Mode ----------
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/chart-data', [AdminController::class, 'dashboardChartData'])->name('admin.dashboard.chartData');
    Route::post('/toggle-maintenance', [AdminController::class, 'toggleMaintenance'])->name('admin.toggleMaintenance');

    // Halaman khusus Maintenance & Backup (admin/sistem/maintenance.blade.php)
    Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('admin.maintenance');
    Route::post('/maintenance/backup', [AdminController::class, 'createBackup'])->name('admin.maintenance.backup');
    Route::get('/maintenance/backup/{filename}/download', [AdminController::class, 'downloadBackup'])->name('admin.maintenance.backup.download');
    Route::delete('/maintenance/backup/{filename}', [AdminController::class, 'deleteBackup'])->name('admin.maintenance.backup.delete');

    // ---------- 2. Manajemen Pengguna (User, Verifikator, & Penjual) ----------
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Akun Verifikator (halaman terpisah: admin/manajemen/akun_verifikator.blade.php)
    Route::get('/users/verifikator', [AdminController::class, 'verifikator'])->name('admin.users.verifikator');
    Route::post('/users/add-verifier', [AdminController::class, 'addVerifier'])->name('admin.users.addVerifier');
    Route::put('/users/verifier/{id}', [AdminController::class, 'updateVerifier'])->name('admin.users.updateVerifier');
    Route::delete('/users/verifier/{id}', [AdminController::class, 'deleteVerifier'])->name('admin.users.deleteVerifier');

    // Verifikasi identitas kreator
    Route::post('/users/approve-seller/{id}', [AdminController::class, 'approveSeller'])->name('admin.users.approveSeller');
    Route::post('/users/reject-seller/{id}', [AdminController::class, 'rejectSeller'])->name('admin.users.rejectSeller');

    // ---------- 3. Katalog & Kategori ----------
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
    Route::post('/products/takedown/{id}', [AdminController::class, 'takedownProduct'])->name('admin.products.takedown');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Kategori Jasa (halaman terpisah: admin/katalog/kategori_jasa.blade.php)
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // ---------- 4. Transaksi & Keuangan ----------
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])->name('admin.transactions.export');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionDetail'])->name('admin.transactions.detail');

    // Penarikan Saldo (halaman terpisah: admin/keuangan/penarikan_saldo.blade.php)
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('admin.withdrawals');
    Route::post('/withdrawals/{id}/process', [AdminController::class, 'processWithdrawal'])->name('admin.withdrawals.process');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');

    // ---------- 5. Membership Card Management ----------
    Route::get('/memberships', [AdminController::class, 'memberships'])->name('admin.memberships');
    Route::post('/memberships', [AdminController::class, 'storeMembership'])->name('admin.memberships.store');
    Route::put('/memberships/{id}', [AdminController::class, 'updateMembership'])->name('admin.memberships.update');
    Route::delete('/memberships/{id}', [AdminController::class, 'deleteMembership'])->name('admin.memberships.delete');

    // ---------- 6. Profile Admin ----------
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
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
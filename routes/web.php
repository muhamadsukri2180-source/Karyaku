<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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
    
    // 1. Dashboard & Maintenance Mode
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/toggle-maintenance', [AdminController::class, 'toggleMaintenance'])->name('admin.toggleMaintenance');

    // 2. Manajemen Pengguna (User, Verifikator, & Penjual)
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/add-verifier', [AdminController::class, 'addVerifier'])->name('admin.users.addVerifier');
    Route::post('/users/approve-seller/{id}', [AdminController::class, 'approveSeller'])->name('admin.users.approveSeller');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // 3. Katalog & Kategori
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
    Route::post('/products/takedown/{id}', [AdminController::class, 'takedownProduct'])->name('admin.products.takedown');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // 4. Transaksi & Keuangan
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');

    // 5. Membership Card Management
    Route::get('/memberships', [AdminController::class, 'memberships'])->name('admin.memberships');
    Route::put('/memberships/{id}', [AdminController::class, 'updateMembership'])->name('admin.memberships.update');

    // 6. Profile Admin
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
Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', function () {
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');
});
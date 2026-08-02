<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth routes (hanya bisa diakses kalau BELUM login)
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
});

// Logout (harus sudah login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard per role (harus login + role sesuai)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:verifikator'])->group(function () {
    Route::get('/verifikator/dashboard', function () {
        return view('verifikator.dashboard');
    })->name('verifikator.dashboard');
});

Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/penjual/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');
});

Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', function () {
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');
});
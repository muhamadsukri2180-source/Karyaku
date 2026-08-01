<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/auth/login', function () {
    return view('auth.login');
})->name('auth.login');

Route::get('/auth/register', function () {
    return view('auth.register');
})->name('auth.register');

Route::get('/penjual/dashboard', function () {
    return view('penjual.dashboard');
})->name('penjual.dashboard');

Route::get('/pembeli/dashboard', function () {
    return view('pembeli.dashboard');
})->name('pembeli.dashboard');

Route::get('/verifikator/dashboard', function () {
    return view('verifikator.dashboard');
})->name('verifikator.dashboard');


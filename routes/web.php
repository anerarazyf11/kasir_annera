<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama (Home)
Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware('auth');

// Route Guest (Hanya bisa diakses jika BELUM login)
Route::view('login', 'auth.login')->name('login')->middleware('guest');
Route::post('login', [AuthController::class, 'login'])->middleware('guest');

// Route Auth (Hanya bisa diakses SETELAH login)
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

    // Route Profile (Tambahan agar navbar tidak error)
    Route::get('/profile', function() {
        return redirect('/');
    })->name('profile.show');

    // Route Fitur Kasir & Master Data
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::patch('/cart/{hash}', [CartController::class, 'update']);
    Route::delete('/cart/{hash}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    Route::resource('transaksi', TransaksiController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('stok', StokController::class);
    Route::resource('user', UserController::class);
});
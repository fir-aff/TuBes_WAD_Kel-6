<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ReviewController;
use App\Models\Menu;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== HALAMAN UTAMA ====================
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// ==================== AUTENTIKASI ====================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ==================== DASHBOARD UMUM ====================
Route::middleware('auth')->get('/dashboard', function () {
    return 'Selamat datang di dashboard, ' . auth()->user()->name;
});

// ==================== ADMIN ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'showUsers']);
    Route::post('/users/{id}/promote', [AdminController::class, 'promote']);
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetPassword']);
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});


// ==================== PENJUAL ====================
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->group(function () {
    Route::get('/manajemen', function () {
        return view('penjual.manajemen');
    })->name('manajemen');

    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/menu', [MenuController::class, 'store']);
    Route::put('/menu/{id}', [MenuController::class, 'update']);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

    // Order
    Route::get('/orderan', [OrderController::class, 'index'])->name('orderan.index');
    Route::post('/orderan/{id}/selesai', [OrderController::class, 'selesaikan'])->name('order.selesaikan');
    Route::post('/orderan/{id}/batal', [OrderController::class, 'batal'])->name('order.batal');

    // Delete Orderan
    Route::delete('/orderan/{id}/hapus', [OrderController::class, 'hapus'])->name('order.hapus');
});

// ==================== PELANGGAN ====================
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pelanggan', function () {
        return 'Selamat datang Pelanggan: ' . auth()->user()->name;
    });

    // Histori pesanan
    Route::get('/pelanggan/pesanan', [OrderController::class, 'history'])->name('order.history');
});

// ==================== CART ====================
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('show');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/hapus', [CartController::class, 'remove'])->name('remove');
});

// ==================== ORDER (PEMESANAN) ====================
Route::post('/order/selesai', [OrderController::class, 'store'])->name('order.done');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders/reorder/{order}', [OrderController::class, 'reorder'])->name('orders.reorder'); // Kamu perlu implementasikan ini

// Routes untuk Ulasan              
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/product/{menu}', [ReviewController::class, 'showProductReviews'])->name('reviews.show_product');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// ==================== PROFIL ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

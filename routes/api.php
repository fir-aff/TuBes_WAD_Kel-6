<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\CartController; // Jika menggunakan CartController untuk API

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes (tidak memerlukan otentikasi)
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

    // API Menu (untuk pembeli/umum)
Route::get('/menus', [MenuApiController::class, 'index']); // Get all menus
Route::get('/menus/{menu}', [MenuApiController::class, 'show']); // Get specific menu

// Group Routes yang Membutuhkan Otentikasi (menggunakan middleware 'auth:sanctum')
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // API Menu (untuk Penjual/Admin - memerlukan Role Middleware)
    // Asumsi Anda punya Role Middleware yang bisa dicek di API
    Route::middleware('role:penjual')->group(function () {
        Route::post('/menus', [MenuApiController::class, 'store']);
        Route::put('/menus/{menu}', [MenuApiController::class, 'update']);
        Route::delete('/menus/{menu}', [MenuApiController::class, 'destroy']);
    });
});




    // // API Cart (keranjang belanja)
    // Route::get('/cart', [CartController::class, 'index']); // Get user's cart
    // Route::post('/cart/add', [CartController::class, 'add']); // Add item to cart
    // Route::put('/cart/{menu_id}', [CartController::class, 'update']); // Update item quantity
    // Route::delete('/cart/{menu_id}', [CartController::class, 'remove']); // Remove item from cart
    // Route::post('/cart/checkout', [CartController::class, 'checkout']); // Process checkout

    // // API Order (untuk user yang login) - Riwayat Pesanan
    // // Anda bisa tambahkan OrderApiController jika ingin API terpisah untuk order
    // // Atau gunakan OrderController yang sudah ada jika metode-nya diubah untuk return JSON
    // // Contoh sederhana jika OrderController dibuat API-friendly:
    // // Route::get('/orders', [OrderController::class, 'indexApi']); // Ambil order user
    // // Route::post('/orders/{id}/selesai', [OrderController::class, 'selesaikanApi']); // Contoh

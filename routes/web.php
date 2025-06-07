<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Models\Menu;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal
Route::get('/', function () {
    $menus = Menu::all();
    return view('welcome', compact('menus'));
});

// Halaman utama
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Autentikasi
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard umum setelah login
Route::middleware('auth')->get('/dashboard', function () {
    return 'Selamat datang di dashboard, ' . auth()->user()->name;
});

// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'showUsers']);
    Route::post('/admin/users/{id}/promote', [AdminController::class, 'promote']);
});

// ================= PENJUAL =================
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->group(function () {
    Route::get('/manajemen', function () {
        return view('penjual.manajemen');
    });

    // Menu penjual
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/menu', [MenuController::class, 'store']);
    Route::put('/menu/{id}', [MenuController::class, 'update']);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

    // Manajemen pesanan
    Route::get('/orderan', [OrderController::class, 'index'])->name('order.index');
    Route::post('/orderan/{id}/selesai', [OrderController::class, 'selesaikan'])->name('order.selesaikan');
    Route::post('/orderan/{id}/batal', [OrderController::class, 'batal'])->name('order.batal');
});

// ================= PELANGGAN =================
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pelanggan', function () {
        return 'Selamat datang Pelanggan: ' . auth()->user()->name;
    });

    // Histori pesanan
    Route::get('/pelanggan/pesanan', [OrderController::class, 'history'])->name('order.history');
});


// Fallback: dashboard umum jika belum diarahkan
Route::middleware('auth')->get('/dashboard', function () {
    return 'Selamat datang di dashboard, ' . auth()->user()->name;
});


// ================= CART =================
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('show');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/hapus', [CartController::class, 'remove'])->name('remove');
});

// ================= ORDER =================
// Disimpan di luar grup agar bisa diakses oleh pelanggan
Route::post('/order/selesai', [OrderController::class, 'store'])->name('order.selesai');


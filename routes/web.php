<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
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
})->name('welcome');

// Halaman register & login
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD berdasarkan role

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'showUsers']);
    Route::post('/admin/users/{id}/promote', [AdminController::class, 'promote']);
});

Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->group(function () {
    Route::get('/manajemen', function () {
        return view('penjual.manajemen');
    });

    Route::get('/menu', [MenuController::class, 'index'])-> name('menu');
    Route::post('/menu', [MenuController::class, 'store']);
    Route::put('/menu/{id}', [MenuController::class, 'update']);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

    // untuk nanti
    Route::get('/pesanan', function () {
        return '<h3>Halaman Pemesanan (sementara kosong)</h3>';
    });
});


Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pelanggan', function () {
        return 'Selamat datang Pelanggan: ' . auth()->user()->name;
    });
});

// Fallback: dashboard umum jika belum diarahkan
Route::middleware('auth')->get('/dashboard', function () {
    return 'Selamat datang di dashboard, ' . auth()->user()->name;
});

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('show');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/hapus', [CartController::class, 'remove'])->name('remove');
});

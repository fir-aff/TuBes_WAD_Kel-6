<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\AuthApiController;

// Rute publik (tidak perlu login)
Route::get('/menus', [MenuApiController::class, 'index']);
Route::get('/menus/{id}', [MenuApiController::class, 'show']);

// Rute yang memerlukan otentikasi (contoh: untuk Penjual)
Route::middleware('auth:sanctum', 'role:penjual')->group(function () {
    Route::post('/menus', [MenuApiController::class, 'store']);
    Route::put('/menus/{id}', [MenuApiController::class, 'update']);
    Route::delete('/menus/{id}', [MenuApiController::class, 'destroy']);
});

// Rute untuk otentikasi API
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);
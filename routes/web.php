<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin,user')->group(function () {
        Route::get('/dashboard', function () {
            return view('/admin/dashboard');
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/tables', function () {
            return view('/user/tables');
        });
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/fetch', [UserController::class, 'fetchUsers']);
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/fetch', [ProductController::class, 'fetchProducts']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });
});

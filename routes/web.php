<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin,user')->group(function () {
        Route::get('/dashboard', function () {
            return view('/admin/dashboard');
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/fetch', [ProductController::class, 'fetchProducts']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);
        Route::get('/purchase-orders/fetch', [PurchaseOrderController::class, 'fetchPurchaseOrders']);
        Route::get('/purchase-orders/new-purchase-order', [PurchaseOrderController::class, 'newPurchaseOrder']);
        Route::get('/purchase-orders/edit-purchase-order/{id}', [PurchaseOrderController::class, 'editPurchaseOrder']);
        Route::get('/purchase-orders/search-product-by-name', [PurchaseOrderController::class, 'searchProductByName']);
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store']);
        Route::put('/purchase-orders/{id}', [PurchaseOrderController::class, 'update']);
        Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/fetch', [UserController::class, 'fetchUsers']);
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/warehouses', [WarehouseController::class, 'index']);
        Route::get('/warehouses/fetch', [WarehouseController::class, 'fetchWarehouses']);
        Route::post('/warehouses', [WarehouseController::class, 'store']);
        Route::put('/warehouses/{id}', [WarehouseController::class, 'update']);
        Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy']);

        Route::get('/shelves', [ShelfController::class, 'index']);
        Route::get('/shelves/fetch', [ShelfController::class, 'fetchShelfs']);
        Route::post('/shelves', [ShelfController::class, 'store']);
        Route::put('/shelves/{id}', [ShelfController::class, 'update']);
        Route::delete('/shelves/{id}', [ShelfController::class, 'destroy']);
    });
});

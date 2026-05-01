<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  —  Base URL: /api/v1
|--------------------------------------------------------------------------
|
| Auth:    POST /api/v1/login          → returns Bearer token
|          POST /api/v1/logout         → revokes token
|          GET  /api/v1/me             → current user
|
| Public:  GET  /api/v1/products       → list products (search/filter/sort)
|          GET  /api/v1/products/{id}  → single product
|          GET  /api/v1/categories     → list categories
|          GET  /api/v1/categories/{id}
|
| User:    GET  /api/v1/my/orders      → own orders
|          GET  /api/v1/my/orders/{no} → single own order
|          PUT  /api/v1/my/profile     → update own profile
|
| Admin:   GET  /api/v1/admin/dashboard
|          CRUD /api/v1/admin/products
|          CRUD /api/v1/admin/categories
|          CRUD /api/v1/admin/orders
|          CRUD /api/v1/admin/users
|
*/

Route::prefix('v1')->group(function () {

    // ── Auth ──────────────────────────────────────────────
    Route::post('login',  [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // ── Public: Products & Categories ─────────────────────
    Route::get('products',          [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('categories',          [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // ── Authenticated User ─────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('my/orders',           [OrderController::class, 'myOrders']);
        Route::get('my/orders/{orderNumber}', [OrderController::class, 'myOrder']);
        Route::put('my/profile',          [UserController::class, 'updateProfile']);
    });

    // ── Admin ──────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'api.admin'])->prefix('admin')->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);

        // Products
        Route::get('products',             [ProductController::class, 'index']);
        Route::post('products',            [ProductController::class, 'store']);
        Route::get('products/{product}',   [ProductController::class, 'show']);
        Route::post('products/{product}',  [ProductController::class, 'update']); // POST for file upload
        Route::delete('products/{product}',[ProductController::class, 'destroy']);

        // Categories
        Route::apiResource('categories', CategoryController::class);

        // Orders
        Route::get('orders',           [OrderController::class, 'index']);
        Route::get('orders/{order}',   [OrderController::class, 'show']);
        Route::patch('orders/{order}', [OrderController::class, 'update']);

        // Users
        Route::get('users',          [UserController::class, 'index']);
        Route::post('users',         [UserController::class, 'store']);
        Route::get('users/{user}',   [UserController::class, 'show']);
        Route::put('users/{user}',   [UserController::class, 'update']);
        Route::delete('users/{user}',[UserController::class, 'destroy']);
    });
});

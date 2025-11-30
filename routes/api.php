<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Tenant Routes
Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
// Route::post('/store-tenants', [TenantController::class, 'storeTenants'])->name('tenants.store');
Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::post('/tenants-store', [TenantController::class, 'storeTenants'])->name('tenants.store');
    Route::put('/tenants/{id}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');
});

<<<<<<< HEAD
// Item routes
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);

// Protected routes (requires Sanctum login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
});
=======
// Order Route
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
>>>>>>> 82764006e97471ededd4779a5ee5e24195fd802a

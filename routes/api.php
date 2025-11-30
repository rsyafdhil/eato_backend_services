<?php

use App\Http\Controllers\ApiController;
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
*/

////////////////////
/// AUTH ROUTES ///
////////////////////

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    ////////////////////
    /// ITEM ROUTES ///
    ////////////////////

    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);

    ////////////////////
    /// ORDER ROUTES //
    ////////////////////
    
    Route::post('/orders', [OrderController::class, 'store']);
});

////////////////////
/// PUBLIC DATA ///
////////////////////

// Tenant (public)
Route::get('/tenants', [ApiController::class, 'getTenants']);
Route::get('/tenants/{id}', [ApiController::class, 'getTenantById']);
Route::get('/tenants/{tenantId}/items', [ApiController::class, 'getItemsByTenant']);

// Item (public)
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);

/////////////////////
/// ADMIN ROUTES ///
/////////////////////

Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::post('/tenants', [TenantController::class, 'storeTenants']);
    Route::put('/tenants/{id}', [TenantController::class, 'update']);
    Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
});
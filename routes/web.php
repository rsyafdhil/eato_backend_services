<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tenants', [TenantController::class, 'getTenants'])->name('fe.tenants.index');
Route::get('/tenants/create', [TenantController::class, 'createPage'])->name('fe.tenants.create');
Route::get('/tenants/edit/{id}', [TenantController::class, 'editPage'])->name('fe.tenants.edit');
Route::post('/tenants/store', [TenantController::class, 'store'])->name('fe.tenants.store');
Route::put('/tenants/update/{id}', [TenantController::class, 'update'])->name('fe.tenants.update');
Route::delete('/tenants/delete/{id}', [TenantController::class, 'destroyFe'])->name('fe.tenants.destroy');

// FE items
Route::get('/items', [ItemController::class, 'index'])->name('fe.items.index');
Route::get('/items/create', [ItemController::class, 'create'])->name('fe.items.create');
Route::post('/items/store', [ItemController::class, 'store'])->name('fe.items.store');

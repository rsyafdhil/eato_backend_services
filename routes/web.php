<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TenantController;
use App\Models\Category;
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
Route::get('items/{id}/edit', [ItemController::class, 'edit'])->name('fe.items.edit');
Route::put('items/{id}/update', [ItemController::class, 'update'])->name('fe.items.update');

//FE Category
Route::get('/category', [CategoryController::class, 'index'])->name('fe.category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('fe.category.create');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('fe.category.edit');
Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('fe.category.update');
Route::post('/category/store', [CategoryController::class, 'store'])->name('fe.category.store');
Route::delete('/category/{id}/destroy', [CategoryController::class, 'destroy'])->name('fe.category.destroy');

//FE Sub Category
Route::get('/sub-category', [CategoryController::class, 'getSubCategory'])->name('fe.subcat.index');
Route::get('/sub-category/create', [CategoryController::class, 'createSubCat'])->name('fe.subcat.create');
Route::get('/sub-category/{id}/edit', [CategoryController::class, 'editSubCat'])->name('fe.subcat.edit');
Route::post('/sub-category/store', [CategoryController::class, 'storeSubCat'])->name('fe.subcat.store');
Route::put('/sub-category/{id}/update', [CategoryController::class, 'updateSubCat'])->name('fe.subcat.update');
Route::delete('/sub-category/{id}/destroy', [CategoryController::class, 'destroySubCat'])->name('fe.subcat.destroy');


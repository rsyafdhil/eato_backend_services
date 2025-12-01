    <?php

    use App\Http\Controllers\ApiController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\TenantController;
    use App\Http\Controllers\ItemController;
    use App\Http\Controllers\MidtransWebhookController;
    use App\Http\Controllers\FavoriteController;
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
        /// ORDER ROUTES ///
        ////////////////////

        // Status order global
        Route::get('/orders/{id}/status', [OrderController::class, 'checkStatus']);
        Route::get('/user/cred', [OrderController::class, 'getUserCred']);

        // Route untuk user biasa
        Route::middleware('role:user|admin|owner')->group(function () {
            Route::get('/orders', [OrderController::class, 'getUserOrders']);
            Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);
            Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        });

        // Route untuk merchant
        Route::middleware('role:owner')->group(function () {
            Route::get('/merchant/orders/{merchantId}', [OrderController::class, 'getMerchantOrders']);
            Route::put('/orders/{orderId}/update-status', [OrderController::class, 'updateStatusPemesanan']); // ✅ Tambah ini
            Route::patch('/merchant/orders/{orderItemId}/status', [OrderController::class, 'updateStatusItem']);
        });
    });

    Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);

    ////////////////////
    /// PUBLIC DATA ///
    ////////////////////

    // Tenant (public)
    Route::get('/tenants', [ApiController::class, 'getTenants']);
    Route::get('/tenants/{id}', [ApiController::class, 'getTenantById']);
    Route::get('/tenants/{tenantId}/items', [ApiController::class, 'getItemsByTenant']);

    // Item (public)
    Route::get('/items', [ItemController::class, 'apiIndex']);
    Route::get('/items/{id}', [ItemController::class, 'show']);

    /////////////////////
    /// ADMIN ROUTES ///
    /////////////////////

    Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
        Route::post('/tenants', [TenantController::class, 'storeTenants']);
        Route::put('/tenants/{id}', [TenantController::class, 'update']);
        Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
    });

    // Favorites routes
    Route::get('/favorites/{user_id}', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{user_id}/{item_id}', [FavoriteController::class, 'destroy']);

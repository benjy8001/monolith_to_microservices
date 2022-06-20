<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Checkout\LinkController as CheckoutLinkController;
use App\Http\Controllers\Checkout\OrderController as CheckoutOrderController;
use App\Http\Controllers\Influencer\LinkController;
use App\Http\Controllers\Influencer\ProductController as InfluencerProductController;
use App\Http\Controllers\Influencer\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user', [AuthController::class, 'user']);

// Admin
Route::prefix('admin')->group(function () {

    Route::middleware(['scope.admin'])->group(function () {
        Route::get('user', [AuthController::class, 'user']);

        Route::get('chart', [DashboardController::class, 'chart']);
        Route::post('upload', [ImageController::class, 'upload']);
        Route::get('export', [OrderController::class, 'export']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('orders', OrderController::class)->only('index', 'show');
        Route::apiResource('permissions', PermissionController::class)->only('index');
    });
});

// Influencer
Route::prefix('influencer')->group(function () {
    Route::get('products', [InfluencerProductController::class, 'index']);

    Route::middleware(['scope.influencer'])->group(function () {
        Route::get('user', [AuthController::class, 'user']);

        Route::post('links', [LinkController::class, 'store']);
        Route::get('stats', [StatsController::class, 'index']);
        Route::get('rankings', [StatsController::class, 'rankings']);
    });
});

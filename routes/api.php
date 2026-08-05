<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ShipmentController;

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

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::post('/{order}/payments', [PaymentController::class, 'store']);
    Route::post('/{order}/shipments', [ShipmentController::class, 'store']);
});

Route::prefix('webhooks')->group(function () {
    Route::post('/payment', [WebhookController::class, 'payment']);
});

Route::prefix('shipments')->group(function () {
    Route::get('/search-destinations', [ShipmentController::class,'searchDestination']);
    Route::post('/calculate-cost', [ShipmentController::class,'calculateCost']);
});
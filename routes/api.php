<?php

use App\Http\Controllers\Api\CreditAllocationController;
use App\Http\Controllers\Api\PricingSchemeController;
use App\Http\Controllers\Api\SmsOrderController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware(['auth:sanctum', 'abilities:profile.read'])->group(function (): void {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::prefix('backoffice')->group(function (): void {
        Route::middleware(['auth:sanctum', 'abilities:backoffice.manage', 'can:backoffice.manage'])->group(function (): void {
            Route::get('pricing-schemes', [PricingSchemeController::class, 'index']);
            Route::post('pricing-schemes', [PricingSchemeController::class, 'store']);

            Route::get('sms-orders', [SmsOrderController::class, 'index']);
            Route::post('sms-orders', [SmsOrderController::class, 'store']);
            Route::post('sms-orders/{orderId}/allocate', [CreditAllocationController::class, 'allocate']);
        });
    });
});

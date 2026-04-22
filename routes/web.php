<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LegacyModuleController;
use App\Http\Controllers\Web\PricingSchemeController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SmsOrderController;
use App\Http\Controllers\Web\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('web.dashboard'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('web.login.submit');
});

Route::middleware(['auth', 'webrole:administrator,reseller,agent'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');

    Route::get('/pricing', [PricingSchemeController::class, 'index'])->name('web.pricing.index');
    Route::post('/pricing', [PricingSchemeController::class, 'store'])->name('web.pricing.store');

    Route::get('/orders', [SmsOrderController::class, 'index'])->name('web.orders.index');
    Route::post('/orders', [SmsOrderController::class, 'store'])->name('web.orders.store');
    Route::post('/orders/{orderId}/allocate', [SmsOrderController::class, 'allocate'])->name('web.orders.allocate');

    Route::get('/users', [UserManagementController::class, 'index'])->name('web.users.index');
    Route::post('/users/{id}/status', [UserManagementController::class, 'updateStatus'])->name('web.users.status');

    Route::get('/reports', [ReportController::class, 'index'])->name('web.reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('web.reports.print');
    Route::get('/sender-ids', [LegacyModuleController::class, 'senderIds'])->name('web.sender-ids.index');
    Route::post('/sender-ids', [LegacyModuleController::class, 'storeSenderId'])->name('web.sender-ids.store');
    Route::post('/sender-ids/{id}/status', [LegacyModuleController::class, 'updateSenderStatus'])->name('web.sender-ids.status');
    Route::get('/sales', [LegacyModuleController::class, 'sales'])->name('web.sales.index');
    Route::get('/clients', [LegacyModuleController::class, 'clients'])->name('web.clients.index');
    Route::post('/clients', [LegacyModuleController::class, 'storeClient'])->name('web.clients.store');
    Route::get('/resellers', [LegacyModuleController::class, 'resellers'])->name('web.resellers.index');
    Route::post('/resellers', [LegacyModuleController::class, 'storeReseller'])->name('web.resellers.store');
    Route::get('/agents', [LegacyModuleController::class, 'agents'])->name('web.agents.index');
    Route::post('/agents', [LegacyModuleController::class, 'storeAgent'])->name('web.agents.store');
    Route::get('/scheduled', [LegacyModuleController::class, 'scheduled'])->name('web.scheduled.index');
    Route::get('/history', [LegacyModuleController::class, 'history'])->name('web.history.index');
    Route::get('/account', [LegacyModuleController::class, 'account'])->name('web.account.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');
});

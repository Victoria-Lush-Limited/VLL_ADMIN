<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminVerificationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditPurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PricingManageController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ScheduledMessageController;
use App\Http\Controllers\SenderController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('admin.guest')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin')->name('logout');

Route::middleware(['auth:admin', 'admin.state'])->group(function () {
    Route::get('/verification', [AdminVerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/verification', [AdminVerificationController::class, 'verify'])->name('admin.verification.verify');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');

    Route::get('/credits/buy', [CreditPurchaseController::class, 'create'])->name('credits.create');
    Route::post('/credits/buy', [CreditPurchaseController::class, 'store'])->name('credits.store');
    Route::get('/credits/pay/{order}', [CreditPurchaseController::class, 'pay'])->name('credits.pay');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{client}/password', [ClientController::class, 'updatePassword'])->name('clients.password');
    Route::post('/clients/{client}/transfer', [ClientController::class, 'transfer'])->name('clients.transfer');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::get('/resellers', [ResellerController::class, 'index'])->name('resellers.index');
    Route::post('/resellers', [ResellerController::class, 'store'])->name('resellers.store');
    Route::get('/resellers/{reseller}', [ResellerController::class, 'show'])->name('resellers.show');
    Route::put('/resellers/{reseller}', [ResellerController::class, 'update'])->name('resellers.update');
    Route::post('/resellers/{reseller}/password', [ResellerController::class, 'updatePassword'])->name('resellers.password');
    Route::delete('/resellers/{reseller}', [ResellerController::class, 'destroy'])->name('resellers.destroy');

    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
    Route::put('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
    Route::post('/agents/{agent}/password', [AgentController::class, 'updatePassword'])->name('agents.password');
    Route::delete('/agents/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');

    Route::get('/senders', [SenderController::class, 'index'])->name('senders.index');
    Route::post('/senders', [SenderController::class, 'store'])->name('senders.store');
    Route::put('/senders/{sender}', [SenderController::class, 'update'])->name('senders.update');
    Route::delete('/senders/{sender}', [SenderController::class, 'destroy'])->name('senders.destroy');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales/{order}/allocate', [SalesController::class, 'allocate'])->name('sales.allocate');
    Route::delete('/sales/{order}', [SalesController::class, 'destroy'])->name('sales.destroy');

    Route::get('/scheduled', [ScheduledMessageController::class, 'index'])->name('scheduled.index');
    Route::post('/scheduled/cancel', [ScheduledMessageController::class, 'cancel'])->name('scheduled.cancel');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    Route::get('/pricing', [PricingManageController::class, 'index'])->name('pricing.index');
    Route::post('/pricing/schemes', [PricingManageController::class, 'storeScheme'])->name('pricing.schemes.store');
    Route::get('/pricing/schemes/{scheme}', [PricingManageController::class, 'show'])->name('pricing.show');
    Route::delete('/pricing/schemes/{scheme}', [PricingManageController::class, 'destroyScheme'])->name('pricing.schemes.destroy');
    Route::post('/pricing/schemes/{scheme}/tiers', [PricingManageController::class, 'storeTier'])->name('pricing.tiers.store');
    Route::delete('/pricing/schemes/{scheme}/tiers/{tier}', [PricingManageController::class, 'destroyTier'])->name('pricing.tiers.destroy');
});

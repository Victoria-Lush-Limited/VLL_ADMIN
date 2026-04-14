<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appUrl = config('app.url');
        if (! app()->environment('testing') && is_string($appUrl) && $appUrl !== '') {
            URL::forceRootUrl(rtrim($appUrl, '/'));
        }

        RateLimiter::for('login', function (Request $request) {
            $key = $request->ip().'|'.strtolower((string) $request->input('user_id'));

            return Limit::perMinute(6)->by($key);
        });

        Paginator::useBootstrapFive();

        Route::pattern('client', '[A-Za-z0-9@._+\-]+');
        Route::pattern('reseller', '[A-Za-z0-9@._+\-]+');
        Route::pattern('agent', '[A-Za-z0-9@._+\-]+');

        try {
            if (Schema::hasTable('app')) {
                $row = DB::table('app')->first();
                if ($row && isset($row->app_name) && is_string($row->app_name) && $row->app_name !== '') {
                    Config::set('app.name', $row->app_name);
                }
            }
        } catch (\Throwable) {
            // Database not configured yet
        }

        View::composer('layouts.admin', function ($view) {
            if (! Auth::guard('admin')->check()) {
                return;
            }
            try {
                $uid = Auth::guard('admin')->user()->user_id;
                $bal = DB::table('transactions')
                    ->where('user_id', $uid)
                    ->selectRaw('COALESCE(SUM(allocated),0) - COALESCE(SUM(consumed),0) as bal')
                    ->value('bal');
                $view->with('adminSmsBalance', (int) $bal);
            } catch (\Throwable) {
                $view->with('adminSmsBalance', 0);
            }
        });
    }
}

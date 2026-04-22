<?php

namespace App\Providers;

use App\Models\SmsOrder;
use App\Models\User;
use App\Policies\SmsOrderPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Paginator::useBootstrapFive();

        Gate::policy(SmsOrder::class, SmsOrderPolicy::class);

        Gate::define('backoffice.manage', function (User $user): bool {
            return in_array($user->account_type, ['administrator', 'reseller', 'agent'], true);
        });
    }
}

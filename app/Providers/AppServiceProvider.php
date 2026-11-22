<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        
        // Gunakan Bootstrap 5 untuk pagination agar rapi dan tidak membesar
        Paginator::useBootstrapFive();

        // Pastikan URL selalu HTTPS di production (via env APP_ENV=production)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}

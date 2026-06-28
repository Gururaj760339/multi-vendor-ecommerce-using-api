<?php

namespace App\Providers;

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
        Gate::define('isAdmin', function($user){
            return $user->role === 'admin';
        });

        Gate::define('isVendor', function($user){
            return $user->role === 'vendor';
        });

        Gate::define('isCustomer', function($user){
            return $user->role === 'customer';
        });

        Gate::define('isAdminOrVendor', function($user){
            return $user->role === 'vendor' || $user->role === 'admin';
        });
    }
}

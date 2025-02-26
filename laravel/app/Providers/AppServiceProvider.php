<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //$this->app->singleton(AuthService::class, AuthService::class);
        Passport::tokensExpireIn(now()->addDays(1));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Request::macro('expectsJson', function () {
            return true;
        });
    }
}

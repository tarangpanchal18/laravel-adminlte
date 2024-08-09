<?php

namespace App\Providers;

use App\Services\CustomLoggerService;
use Illuminate\Support\ServiceProvider;

class CustomLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('customlogger', function ($app) {
            return new CustomLoggerService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

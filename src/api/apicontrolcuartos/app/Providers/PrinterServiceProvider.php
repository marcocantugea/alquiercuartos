<?php

namespace App\Providers;

use App\Services\PrinterService;
use App\Services\PrintLayoutService;
use Illuminate\Support\ServiceProvider;

class PrinterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PrinterService::class, function ($app) {
            return new PrinterService();
        });

        $this->app->singleton(PrintLayoutService::class, function ($app) {
            return new PrintLayoutService();
        });
        
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
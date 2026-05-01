<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GeoService;

class GeoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GeoService::class, function ($app) {
            return new GeoService();
        });
    }

    public function boot()
    {
        //
    }
}

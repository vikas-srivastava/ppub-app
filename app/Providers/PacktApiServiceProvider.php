<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Packt\ApiClient;

class PacktApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ApiClient::class, function ($app){
            return new ApiClient(
                config('services.packt-api.uri'),
                config('services.packt-api.token')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

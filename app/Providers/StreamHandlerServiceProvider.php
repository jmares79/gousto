<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StreamHandlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\StreamHandlerInterface',
            'App\Service\StreamHandlerService'
        );
    }
}

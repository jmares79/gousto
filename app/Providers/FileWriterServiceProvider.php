<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\File\FileWriterService;

class FileWriterServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\FileWriterInterface', FileWriterService::class);
    }
}

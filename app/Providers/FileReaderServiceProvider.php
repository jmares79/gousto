<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\File\FileReaderService;

class FileReaderServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\FileReaderInterface', FileReaderService::class);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\ImageHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageHelper::class, function(){
            return new ImageHelper;
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

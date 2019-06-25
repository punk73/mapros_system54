<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lineprocess;
use App\Observers\LineprocessObserver;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //register lineprocess observer
        Lineprocess::observe(LineprocessObserver::class);
        Voyager::addAction(\App\Actions\Approved::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('TCG\Voyager\Http\Controllers\Controller', 
            'App\Vendor\Controllers\Controller');
    }
}

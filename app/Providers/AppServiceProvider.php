<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lineprocess;
use App\Observers\LineprocessObserver;

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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

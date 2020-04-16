<?php

namespace App\Providers;

use App\Models\ChangeRequest;
use App\Observers\ChangeRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ChangeRequest::observe(ChangeRequestObserver::class);
    }
}

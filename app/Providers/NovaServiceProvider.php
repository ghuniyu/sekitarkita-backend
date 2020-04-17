<?php

namespace App\Providers;

use App\Nova\Metrics\DevicePerDay;
use App\Nova\Metrics\Devices;
use App\Nova\Metrics\HealthyUser;
use App\Nova\Metrics\InteractionPerDay;
use App\Nova\Metrics\InteractionPerHour;
use App\Nova\Metrics\InteractionPerMinutes;
use App\Nova\Metrics\NearbyDevices;
use App\Nova\Metrics\NewDevice;
use App\Nova\Metrics\NewInteraction;
use App\Nova\Metrics\ODPUser;
use App\Nova\Metrics\OnlineDevice;
use App\Nova\Metrics\PDPUser;
use Ericlagarda\NovaTextCard\TextCard;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ActionEvent::saving(function ($actionEvent) {
            /*if(in_array($actionEvent->name, ['Create', 'Update', 'Delete']))
                return false;*/
            return false;
        });

        parent::boot();

        $this->app->alias(
            \App\Http\Controllers\NovaLoginController::class,
            \Laravel\Nova\Http\Controllers\LoginController::class
        );
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new TextCard())
                ->width('1/3')
                ->heading(
                    '<div class="mb-2"><h1 class="text-xl text-80 mb-2">Mapping Device</h1>')
                ->text('<a href="' . route('mapping.member') . '"  target="_blank" class="btn btn-default btn-primary">Lihat Data Visual Network</a>')
                ->headingAsHtml()
                ->textAsHtml(),
            new OnlineDevice,
            new InteractionPerMinutes,
            new InteractionPerHour,
            new InteractionPerDay,
            new DevicePerDay,

            new NewDevice,
            new NewInteraction,

            new Devices,
            new NearbyDevices,
            new HealthyUser,
            new PDPUser,
            new ODPUser,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
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

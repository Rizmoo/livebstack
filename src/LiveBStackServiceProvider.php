<?php

namespace Oxalistech\LiveBStack;

use Illuminate\Support\ServiceProvider;
use Oxalistech\LiveBStack\commands\MakeCrudComponent;

class LiveBStackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livebstack'),
        ], 'livebstack-views');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/livebstack.php' => config_path('livebstack.php'),
        ], 'livebstack-config');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livebstack');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCrudComponent::class,
            ]);
        }
    }

    public function register()
    {
//        $this->mergeConfigFrom(
//            __DIR__.'/../config/livebstack.php', 'livebstack'
//        );
//
//        $this->app->singleton('livebstack', function ($app) {
//            return new LiveBStack;
//        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/livebstack.php', 'livebstack'
        );
    }
}

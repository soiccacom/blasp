<?php

namespace Blaspsoft\Blasp;
 
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('blasp.php'),
            ], 'blasp-config');
        }

        app('validator')->extend('blasp_check', function ($attribute, $value, $parameters, $validator,) {
            return ! app('blasp')->check($value)->hasProfanity;
        }, 'The :attribute contains profanity.');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blasp');

        $this->app->bind('blasp', function () {
            return new \Blaspsoft\Blasp\BlaspService();
        });
    }
}

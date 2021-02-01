<?php

namespace MuhmdRaouf\LaravelParatest;

use Illuminate\Support\ServiceProvider;
use MuhmdRaouf\LaravelParatest\Console\DbCreateCommand as DbCreateCLICommand;
use MuhmdRaouf\LaravelParatest\Console\DbReCreateCommand as DbReCreateCLICommand;
use MuhmdRaouf\LaravelParatest\Console\DbDropCommand as DbDropCLICommand;

class LaravelParatestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('paratest.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                DbCreateCLICommand::class,
                DbReCreateCLICommand::class,
                DbDropCLICommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'paratest');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-paratest', function () {
            return new LaravelParatest;
        });
    }
}

<?php

namespace Picobaz\GridView;

use Illuminate\Support\ServiceProvider;

/**
 * GridView Service Provider
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView
 */
class GridViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gridview');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/gridview.php' => config_path('gridview.php'),
        ], 'gridview-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/gridview'),
        ], 'gridview-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/gridview/assets'),
        ], 'gridview-assets');

        // Publish migrations (if any)
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'gridview-migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeSearchModelCommand::class,
            ]);
        }

        // Register helper function
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/gridview.php',
            'gridview'
        );

        // Register the main class
        $this->app->singleton('gridview', function ($app) {
            return new GridView();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['gridview'];
    }
}
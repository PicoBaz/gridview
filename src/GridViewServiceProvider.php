<?php

namespace Picobaz\GridView;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class GridViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/gridview.php', 'gridview');
        $this->app->singleton('gridview', function () {
            return new GridView([]);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/gridview.php' => config_path('gridview.php'),
        ], 'gridview-config');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/gridview'),
        ], 'gridview-views');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'gridview');

        Blade::directive('gridview', function ($expression) {
            return "<?php echo gridview($expression); ?>";
        });
    }
}
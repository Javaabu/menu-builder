<?php

namespace Javaabu\MenuBuilder;

use Illuminate\Support\ServiceProvider;

class MenuBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/menu-builder'),
            ], 'menu-builder-views');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu-builder');
    }

    public function register(): void
    {
        $this->app->singleton('menu_builder', function ($app) {
            return new MenuBuilder();
        });

        require_once __DIR__ . '/helpers.php';
    }
}

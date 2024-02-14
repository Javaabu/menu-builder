<?php

namespace Javaabu\MenuBuilder;

use Javaabu\Sidebar\Sidebar;
use Illuminate\Support\ServiceProvider;

class MenuBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu-builder');
        $this->registerPublishables();
    }

    public function register(): void
    {
        $this->app->singleton('menu_builder', function ($app) {
            return new MenuBuilder();
        });

        require_once __DIR__ . '/helpers.php';
    }

    private function registerPublishables(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/menu-builder.php', 'menu_builder');
    }
}

<?php

namespace Javaabu\MenuBuilder;

use Illuminate\Support\Traits\Macroable;
use Javaabu\MenuBuilder\Menu\Menu;

class MenuBuilder
{
    use Macroable;

    protected static string $defaultFramework = 'bootstrap-5';

    public static function make(string $menu): Menu
    {
        return new $menu();
    }

    public static function defaultFramework(): string
    {
        return static::$defaultFramework;
    }

    public static function defaultView(string $framework = ''): string
    {
        return 'menu-builder::' . ($framework ?: static::defaultFramework()) . '.menu';
    }

    public static function useBootstrap5(): void
    {
        static::$defaultFramework = 'bootstrap-5';
    }

    public static function useMaterialAdmin26(): void
    {
        static::$defaultFramework = 'material-admin-26';
    }


}

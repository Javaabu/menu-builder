<?php


if (! function_exists('menu_builder')) {

    function menu_builder(): \Javaabu\MenuBuilder\MenuBuilder
    {
        return app('menu_builder');
    }
}

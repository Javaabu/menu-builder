<?php

namespace Javaabu\MenuBuilder\Menu;


class Menu
{
    public function getMenuItemView(): string
    {
        return "menu-builder::menu-item";
    }

    public function getChildMenuItemView(): string
    {
        return "menu-builder::child-menu-item";
    }
}

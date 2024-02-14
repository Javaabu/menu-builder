<?php

namespace Javaabu\MenuBuilder\Menu;

use Javaabu\MenuBuilder\Support\MenuItem;
use App\Http\Controllers\Admin\ApplicationsController;

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

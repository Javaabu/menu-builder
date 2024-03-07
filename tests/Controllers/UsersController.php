<?php

namespace Javaabu\MenuBuilder\Tests\Controllers;

use Javaabu\MenuBuilder\Menu\MenuItem;

class UsersController
{
    public function index()
    {
        $active_item = MenuItem::make('Users')->controller(UsersController::class);
        $inactive_item = MenuItem::make('Home')->route('web.home');

        return view('active-link', compact('active_item', 'inactive_item'));
    }
}

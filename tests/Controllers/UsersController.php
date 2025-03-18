<?php

namespace Javaabu\MenuBuilder\Tests\Controllers;

use Javaabu\MenuBuilder\Menu\MenuItem;
use Javaabu\MenuBuilder\Tests\Models\User;

class UsersController
{
    public function index()
    {
        $active_item = MenuItem::make('Users')->controller(UsersController::class);
        $inactive_item = MenuItem::make('Home')->route('web.home');

        return view('active-link', compact('active_item', 'inactive_item'));
    }

    public function show($user)
    {
        $user = User::find($user);

        return view('user', compact('user'));
    }

    public function localeIndex(string $locale)
    {
        return view('locale-index', compact('locale'));
    }
}

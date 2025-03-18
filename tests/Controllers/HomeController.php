<?php

namespace Javaabu\MenuBuilder\Tests\Controllers;

class HomeController
{
    public function index(string $locale)
    {
        return view('locale-index', compact('locale'));
    }
}

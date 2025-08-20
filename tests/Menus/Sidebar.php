<?php

namespace Javaabu\MenuBuilder\Tests\Menus;

use Javaabu\MenuBuilder\Menu\Menu;
use Javaabu\MenuBuilder\Menu\MenuItem;

class Sidebar extends Menu
{
    protected string $icon_prefix = 'zmdi zmdi-';
    protected string $id = 'side-bar';

    public function menuItems(): array
    {
        return [
            MenuItem::make('Dashboard')
                ->cssClass('custom-class')
                ->route('home')
                ->icon('view-quilt'),

            MenuItem::make('Products')
                ->icon('shopping-cart')
                ->children([
                    MenuItem::make('Products')
                        ->count(21)
                        ->url('http://localhost/products'),

                    MenuItem::make('Categories')
                        ->permissions('view_categories')
                        ->url('http://localhost/categories')
                        ->badge(__('New')),

                    MenuItem::make('Brands')
                        ->url('http://localhost/brands')
                ]),

            MenuItem::make('Returns')
                ->url('http://localhost/returns')
                ->icon('assignment-return')
                ->children([
                    MenuItem::make('Return Values')
                        ->permissions('view_returns')
                        ->url('http://localhost/return-values'),
                ]),

            MenuItem::make('Returns')
                ->url('http://localhost/returns')
                ->target('_blank')
                ->icon('assignment-return')
                ->badge(__('Error'), 'text-bg-danger'),
        ];
    }
}

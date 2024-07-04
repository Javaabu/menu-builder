## Laravel Menu Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/javaabu/menu-builder.svg?style=flat-square)](https://packagist.org/packages/javaabu/menu-builder)
[![Test Status](../../actions/workflows/run-tests.yml/badge.svg)](../../actions/workflows/run-tests.yml)
[![Quality Score](https://img.shields.io/scrutinizer/g/javaabu/menu-builder.svg?style=flat-square)](https://scrutinizer-ci.com/g/javaabu/menu-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/javaabu/menu-builder.svg?style=flat-square)](https://packagist.org/packages/javaabu/menu-builder)

This Laravel package makes it breeze to set up and render menus for different parts of your application. The Menu Builder class is highly configurable, allowing you to define the structure and behavior of your menus in a flexible and maintainable way.

## Installation
You can install the package via composer:

```bash
composer require javaabu/menu-builder
```

## Usage

### Defining a Menu

Each menu is defined by a class that extends `Javaabu\MenuBuilder\Menu\Menu`.
Those classes must define `menuItems()` method.

Here is an example menu class:

```php
<?php

namespace App\Menus;

use Javaabu\MenuBuilder\Menu\Menu;
use Javaabu\MenuBuilder\Menu\MenuItem;

class AdminSidebar extends Menu implements IsMenu
{
    protected string $id = 'side-bar';
    protected string $icon_prefix = 'zmdi zmdi-';
    protected ?string $guard = 'web_admin';

    public function menuItems(): array
    {
        return [
            MenuItem::make(__('Dashboard'))
                    ->route('admin.home')
                    ->icon('zmdi-view-quilt'),
                    
            MenuItem::make(__('Users'))
                ->controller(\App\Http\Controllers\Admin\UsersController::class)
                ->can('viewAny', \App\Models\User::class)
                ->icon('zmdi-accounts')
                ->count(App\Models\User::userVisible()->pending(), 'approve_users'),

            MenuItem::make(__('Roles'))
                ->url('/roles?foo=bars')
                ->permissions('view_roles')
                ->active(function (Request $request) {
                    return $request->query('foo') == 'bars';
                }),
        ];
    }
}
```
The `$id` is an optional property you can set to give an id to the menu.
The `$icon_prefix` is an optional property you can set to give a prefix to icons of all the menu items.
The `$guard` is an optional property you can set to specify the guard used to find the current user.
The `menuItems` method returns an array that defines the items in the menu. Each item is created with the `MenuItem::make` method.
The `MenuItem::make` method accepts the label for the menu item and returns a `MenuItem` instance.

You may use any of the following methods to set the link of the menu item. The link would be generated using the last called method:
- `route` - sets the link using the `route()` helper. The active state would be automatically determined using the given route.
- `controller` - sets the link using the `index` action of the given controller. The active state would be true if the current controller is the given controller.
- `url` - sets the link directly using a string. The active state would be true if the current full url matches the given url.

When the link is set using any of the above methods, the active state will be determined automatically. If you want to customize the active state logic, you can use the following method:
- `active` - sets the condition to check if the menu item is active. Accepts a boolean or a closure.

You may use any of the following methods to set the conditions to determine whether to show the item to the current user:
- `permissions` - This defines a permission or an array of permissions that the user must have to see the menu item. Any of the permissions is sufficient to view the menu item.
- `can` - This defines the required permissions the user must have to see the menu item using the `can` helper. This method also accepts a closure.

If you call both `permissions` and `can`, then the conditions defined in both methods should be met to show the item

You may use the following methods to further configure the menu item:
- `icon` - sets the icon of the menu item
- `count` - sets the notification count of the menu item. The method can accept an int, closure or even an eloquent `Builder` instance. You can specify the permissions required to show the count using the 2nd argument of this method.
- `children` - sets the children of the menu item. Note that the default views support only 2 levels of items. If you want more levels or infinite levels, you can supply your own view when rendering the menu.
- `hideIfNoChildrenVisible` - hides the menu item if it has children but doesn't have any visible children. By default, this option is active for items with blank links.
- `dontHideIfNoChildrenVisible` - don't hide the menu item even if it has no visible children

### Displaying a Menu

After you have defined a menu class, you can display it in a view file by calling the `links()` method.

Here's a sample code to display a menu:

```php
$sidebar = new \App\Menus\AdminSidebar();

{!! $sidebar->links() !!}
```

When displaying the menu, you can use the following methods to further customize how the menu is displayed:
`iconPrefix` - sets the icon prefix used by all the menu items
`guard` - sets the guard used to find the current user
`id` - sets the css id of the menu

### Changing the CSS Framework

By default, the menu would be rendered as a Bootstrap 5 menu. The package support Bootstrap 5 and Material Admin 2.6 CSS frameworks. To change the framework used, you can call any of the following methods:

```php
// render as Bootstrap 5
{!! $sidebar->useBootstrap5()->links() !!}

// render as Material Admin 2.6
{!! $sidebar->useMaterialAdmin26()->links() !!}
```

You can also change the default framework by calling the `MenuBuilder`'s `useBootstrap5` and `useMaterialAdmin26` methods within the `boot` method of your `App\Providers\AppServiceProvider` class.

```php
use Javaabu\MenuBuilder\MenuBuilder;
 
/**
 * Bootstrap any application services.
 */
public function boot(): void
{
    MenuBuilder::useBootstrap5();
    MenuBuilder::useMaterialAdmin26();
}
```

### Customizing the rendered menu

The `links` method will accept your own view if you want to render the menu on your own. The view will receive the following date:
`$items` - Array of root level menu items visible to the current user
`$user` - Current user
`$icon_prefix` - Prefix to use for the items' icons
`$id` - The CSS ID of the menu

The following methods will be available for the menu items:
```php
$item->getLink() // returns the link of the item
$item->getLabel() // returns the label of the item
$item->isActive() // checks if the item is currently active
$item->hasActiveChild($user) // checks if the item has any visible child that is currently active
$item->hasIcon() // checks if the item has an icon defined
$item->getIcon($icon_prefix) // returns the item's icon with the prefix prepended
$item->getAggregatedCount($user) // get the count of the item + the count of all visible child items, will return 0 if the current user can't see the count
$item->getVisibleCount($user) // get the count of the item, will return 0 if the current user can't see the count
$item->getVisibleChildren($user) // returns an array of all visible child items

```

If you want to customize the default views, you can publish the package views and customize them. To publish the view files to `resources/views/vendor/menu-builder`, run:

```bash
php artisan vendor:publish --provider="Javaabu\MenuBuilder\MenuBuilderServiceProvider" --tag="menu-builder-views"
```

This comprehensive Laravel package streamlines the process of creating custom menus while providing flexibility for your unique needs.

## Credits
- [Hussain Afeef](https://github.com/ibnnajjaar)
- [Arushad Ahmed (@dash8x)](http://arushad.com)
- [Javaabu](https://github.com/Javaabu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.




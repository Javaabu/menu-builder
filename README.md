

## Laravel Menu Builder
This Laravel package makes it breeze to set up and render menus for different parts of your application. The Menu Builder class is highly configurable, allowing you to define the structure and behavior of your menus in a flexible and maintainable way.

## Installation
You can install the package via composer:

```bash
composer require javaabu/menu-builder
```

To publish the config file to config/menu-builder.php run:

```bash
php artisan vendor:publish --provider="Javaabu\MenuBuilder\MenuBuilderServiceProvider" --tag="config"
```

## Usage
To configure your menus, you add them to an array in the package's configuration file. Each array key represents a menu identifier, and each corresponding value is the fully qualified name of a `Menu` class that tells Menu Builder how to render that menu.

Here is an example configuration:

```php
<?php
// config/menu-builder.php
return [
    'menus' => [
        'admin' => \App\Helpers\Sidebar\AdminMenu::class,
        'public' => \App\Helpers\Sidebar\PublicMenu::class,
    ],
];
```

In this example, two menus are defined: 'admin' and 'public'. `\App\Helpers\Sidebar\AdminMenu` and `\App\Helpers\Sidebar\PublicMenu` are Menu classes that tell Menu Builder how to render the 'admin' and 'public' menus, respectively.

### Displaying a Menu

After you have defined a menu in the configuration file, you can display it in a view file with the `menu_builder()` helper function. This function takes a menu identifier (i.e. 'admin', 'public') as an argument and returns the HTML to render the menu.

Here's a sample code to display a menu:

```php
{!! menu_builder()->render('public') !!}
```
The above code will render the 'public' menu.

### Defining a Menu

Each menu is defined by a class that extends `Javaabu\MenuBuilder\Menu\Menu` and implementes the `Javaabu\MenuBuilder\Contracts\IsMenu` interface. 
Those classes must define `menuItems()` method.

Here is an example menu class:

```php
<?php

namespace App\Helpers\Sidebar;

use Javaabu\MenuBuilder\Menu\Menu;
use Javaabu\MenuBuilder\Support\MenuItem;
use Javaabu\MenuBuilder\Contracts\IsMenu;
use Javaabu\MenuBuilder\Support\ChildMenuItem;
use App\Http\Controllers\Admin\ApplicationsController;

class AdminMenu extends Menu implements IsMenu
{
    public function menuItems(): array
    {
        return [
            MenuItem::make(__('Dashboard'))
                    ->link(route('admin.home'))
                    ->icon('fa-duotone fa-tachometer-alt')
                    ->active(request()->routeIs('admin.home')),

            MenuItem::make(__('Applications'))
                    ->link(route('admin.applications.index'))
                    ->icon('fa-duotone fa-file-invoice')
                    ->permissions(\App\Models\ApplicationType::getViewPermissionList())
                    ->count(function () {
                        return \App\Models\Application::pending()->count();
                    })
                    ->active(request()->routeIs('admin.applications.*')),
        ];
    }
}
```

The `menuItems` method returns an array that defines the items in the menu. Each item is created with the `MenuItem::make` method. 
The `MenuItem::make` method accepts the label for the menu item and returns a `MenuItem` instance. 
You may use the following methods to further configure the menu item:
- `link` - sets the link of the menu item
- `icon` - sets the icon of the menu item
- `permissions` - This defines a permission or an array of permissions that the user must have to see the menu item. Any of the permissions is sufficient to view the menu item.
- `count` - sets the notification count of the menu item
- `active` - sets the condition to check if the menu item is active
- `hidden` - sets the condition to check if the menu item is hidden
- `children` - sets the children of the menu item

### Customizing the Menu
Additionally, you may define the attributes passed to the wrapping `ul` item by defining the `wrapperAttributes` method on the menu class:

```php
public function wrapperAttributes(): array
{
    return [
        'id'    => 'sidebar-links',
        'class' => 'navigation'
    ];
}
```

You may also override the views used to render the menu items and child menu items by defining the `getMenuItemView` and `getChildMenuItemView` methods on the menu class:

```php
public function getMenuItemView(): string
{
    return "vendor/sidebar/bootstrap-five/menu-item";
}

public function getChildMenuItemView(): string
{
    return "vendor/sidebar/bootstrap-five/child-menu-item";
}
```

When overriding the views, you may use the following methods in your views:
```php
$getLabel() // returns the label of the menu item
$getLink() // returns the link of the menu item
$hasPermissionToSee() // returns true if the user has permission to see the menu item
$hasCount() // returns true if the menu item has a count
$getCount() // returns the count of the menu item
$isActive() // returns true if the menu item is active
$isHidden() // returns true if the menu item is hidden
```
In addition to the above methods, the following methods are available to the parent menu items:
```php
$hasIcon() // returns true if the menu item has an icon
$getIcon() // returns the icon of the menu item
$hasChildren() // returns true if the menu item has children
$hasActiveChild() // returns true if the menu item has an active child
$renderChildren() // returns the HTML to render the children of the menu item
```

This comprehensive Laravel package streamlines the process of creating custom menus while providing flexibility for your unique needs.

## Credits
- [Hussain Afeef](https://github.com/ibnnajjaar)
- [Javaabu](https://github.com/Javaabu)


## License
Only for Javaabu's internal use. Not for public use.




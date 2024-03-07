<?php

namespace Javaabu\MenuBuilder;

use Illuminate\Support\Traits\Macroable;
use Javaabu\MenuBuilder\Contracts\IsMenu;
use Javaabu\MenuBuilder\Menu\MenuItem;

class MenuBuilder
{
    use Macroable;

    public function render(string $menu_name): string
    {
        $menu = config('menu-builder.menus');
        $menu_class = $menu[$menu_name] ?? null;
        if (! $menu_class) {
            return '';
        }

        /* @var IsMenu $menu_class */
        $menu_class = new $menu_class;
        $menu_items = $menu_class->menuItems();
        $attributes = $menu_class->wrapperAttributes();

        $attributes_html = $this->getAttributesHtml($attributes);

        $html = "<ul " . $attributes_html . ">";

        foreach ($menu_items as $item) {
            /* @var MenuItem $item */
            $html .= $item->setView($menu_class->getMenuItemView())
                          ->setChildView($menu_class->getChildMenuItemView())
                          ->toHtml();
        }

        $html .= "</ul>";

        return $html;
    }

    public function getAttributesHtml(array $attributes): string
    {
        $attributes_html = '';
        foreach ($attributes as $key => $value) {
            $attributes_html .= " {$key}=\"{$value}\"";
        }

        return $attributes_html;
    }

}

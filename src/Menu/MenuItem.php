<?php

namespace Javaabu\MenuBuilder\Menu;

use Closure;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Traits\Macroable;
use Javaabu\MenuBuilder\Traits\CanActivate;
use Javaabu\MenuBuilder\Traits\CanHaveChildren;
use Javaabu\MenuBuilder\Traits\HasBadge;
use Javaabu\MenuBuilder\Traits\HasCan;
use Javaabu\MenuBuilder\Traits\HasController;
use Javaabu\MenuBuilder\Traits\HasCount;
use Javaabu\MenuBuilder\Traits\HasCssClass;
use Javaabu\MenuBuilder\Traits\HasIcon;
use Javaabu\MenuBuilder\Traits\HasPermission;
use Javaabu\MenuBuilder\Traits\HasRoute;
use Javaabu\MenuBuilder\Traits\HasRoutePatterns;
use Javaabu\MenuBuilder\Traits\HasUrl;

class MenuItem
{
    use HasRoute;
    use HasRoutePatterns;
    use HasController;
    use HasUrl;
    use CanActivate;
    use HasIcon;
    use HasPermission;
    use HasCan;
    use HasCount;
    use HasBadge;
    use HasCssClass;
    use CanHaveChildren;
    use Macroable;

    protected string $label;


    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public static function make(string $label): self
    {
        return new static($label);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    protected function evaluate($value, array $parameters = [])
    {
        if (! $value instanceof Closure) {
            return $value;
        }

        return app()->call($value, $parameters);
    }

    protected function clearLink(): self
    {
        return $this->clearRoute()
                    ->clearController()
                    ->clearUrl();
    }

    public function getLink(): string
    {
        // get link by route
        if ($this->hasUrl()) {
            return $this->generateUrlLink();
        } elseif ($this->hasRoute()) {
            return $this->generateRouteLink();
        } elseif ($this->hasController()) {
            return $this->generateControllerLink();
        }

        return '';
    }

    public function canView(?Authorizable $user = null): bool
    {
        $can_view_main_item = $this->hasAnyPermission($user) && $this->userCanViewWithCan($user);

        if ($can_view_main_item && $this->hasChildren() && $this->shouldHideIfNoChildrenVisible()) {
            $can_view_main_item &= $this->hasVisibleChild($user);
        }

        return $can_view_main_item;
    }
}

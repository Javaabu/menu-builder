<?php

namespace Javaabu\MenuBuilder\Traits;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Javaabu\MenuBuilder\Menu\MenuItem;

trait CanHaveChildren
{
    protected array $children = [];

    public function children(array $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return ! empty($this->getChildren());
    }

    public function hasActiveChild(): bool
    {
        return collect($this->getChildren())
                    ->contains(function (MenuItem $child) {
                        return $child->isActive() || $child->hasActiveChild();
                    });
    }

    public function hasVisibleChild(?Authorizable $user = null): bool
    {
        return collect($this->getChildren())
            ->contains(function (MenuItem $child) use ($user) {
                return $child->canView($user) || $child->hasVisibleChild($user);
            });
    }

    public function getAggregatedCount(?Authorizable $user = null): int
    {
        return $this->getVisibleCount($user) +
            collect($this->getChildren())
                ->sum(function (MenuItem $child) use ($user) {
                    return $child->getAggregatedCount($user);
                });
    }
}

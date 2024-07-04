<?php

namespace Javaabu\MenuBuilder\Traits;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Javaabu\MenuBuilder\Menu\MenuItem;

trait CanHaveChildren
{
    protected array $children = [];
    protected ?array $visible_children;

    protected ?bool $hide_if_no_children_visible = null;


    public function hideIfNoChildrenVisible(): self
    {
        $this->hide_if_no_children_visible = true;

        return $this;
    }

    public function dontHideIfNoChildrenVisible(): self
    {
        $this->hide_if_no_children_visible = true;

        return $this;
    }

    public function shouldHideIfNoChildrenVisible(): bool
    {
        if (is_null($this->hide_if_no_children_visible)) {
            return empty($this->getUrl());
        }

        return $this->hide_if_no_children_visible;
    }

    public function children(array $children): self
    {
        $this->visible_children = null;
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

    public function hasActiveChild(?Authorizable $user = null): bool
    {
        return collect($this->getVisibleChildren($user))
                    ->contains(function (MenuItem $child) {
                        return $child->isActive() || $child->hasActiveChild();
                    });
    }

    public function getVisibleChildren(?Authorizable $user = null): array
    {
        if (isset($this->visible_children)) {
            return $this->visible_children;
        }

        return $this->visible_children =
            collect($this->getChildren())
                ->filter(function (MenuItem $item) use ($user) {
                    return $item->canView($user);
                })
                ->all();
    }

    public function hasVisibleChild(?Authorizable $user = null): bool
    {
        return ! empty($this->getVisibleChildren($user));
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

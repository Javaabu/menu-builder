<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Builder;

trait HasCount
{
    protected Closure | Builder | int $count = 0;
    protected Closure | array $count_permissions = [];

    public function count(Closure | Builder | int $count, Closure | array | string $count_permissions = []): self
    {
        $this->count = $count;
        $this->count_permissions = is_string($count_permissions) ? [$count_permissions] : $count_permissions;

        return $this;
    }

    public function getCountPermissions(): Closure | array
    {
        return $this->count_permissions;
    }

    public function hasCount(?Authorizable $user = null): bool
    {
        return $this->getCount($user) > 0;
    }

    public function getVisibleCount(?Authorizable $user = null): int
    {
        if (! $this->shouldShowCount($user)) {
            return 0;
        }

        return $this->getCount($user);
    }

    public function getCount(?Authorizable $user = null): int
    {
        if ($this->count instanceof Builder) {
            return $this->count->count();
        } elseif ($this->count instanceof Closure) {
            return $this->evaluate($this->count, compact('user'));
        }

        return $this->count;
    }

    public function shouldShowCount(?Authorizable $user = null): bool
    {
        if ($permissions = $this->getCountPermissions()) {
            if ($permissions instanceof Closure) {
                return $this->evaluate($permissions, ['user' => $user]);
            } elseif ($user) {
                return $user->canAny($permissions);
            }

            return false;
        }

        return true;
    }
}

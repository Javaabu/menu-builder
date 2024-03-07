<?php

namespace Javaabu\MenuBuilder\Traits;

use Illuminate\Contracts\Auth\Access\Authorizable;

trait HasPermission
{
    protected array $permissions = [];

    public function permissions(array | string $permissions): self
    {
        $this->permissions = is_array($permissions) ? $permissions : func_get_args();
        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    protected function hasPermissions(): bool
    {
        return ! empty($this->getPermissions());
    }

    protected function hasAnyPermission(?Authorizable $user = null): bool
    {
        if ($this->hasPermissions()) {

            if (! $user) {
                return false;
            }

            return $user->canAny($this->getPermissions());
        }

        return true;
    }
}

<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait HasPermission
{
    public Closure | array $permissions = [];

    public function permissions(Closure | array | string $permission): self
    {
        $this->permissions = is_string($permission) ? [$permission] : $permission;
        return $this;
    }

    public function getPermissions(): array
    {
        return $this->evaluate($this->permissions);
    }

    public function hasPermissionToSee(): bool
    {
        return auth()->user() && auth()->user()->canAny($this->getPermissions());
    }
}

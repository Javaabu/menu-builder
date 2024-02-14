<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait CanActivate
{
    public Closure | bool | null $active = null;

    public function active(Closure | bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function isActive(): bool
    {
        if (! isset($this->active)) {
            return false;
        }

        return $this->evaluate($this->active);
    }
}

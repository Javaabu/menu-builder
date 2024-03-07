<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;

trait CanActivate
{
    protected Closure | bool | null $active = null;

    public function active(Closure | bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isActive(): bool
    {
        if (isset($this->active)) {
            return $this->evaluate($this->active, ['request' => request()]);
        } elseif ($this->hasUrl()) {
            return $this->checkActiveFromUrl();
        } elseif ($this->hasRoute()) {
            return $this->checkActiveFromRoute();
        } elseif ($this->hasController()) {
            return $this->checkActiveFromController();
        }

        return false;
    }
}

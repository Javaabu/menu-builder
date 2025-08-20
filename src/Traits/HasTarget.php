<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;

trait HasTarget
{
    protected Closure | string | null $target = null;

    public function target(Closure | string | null $badge): self
    {
        $this->target = $badge;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->evaluate($this->target);
    }

    public function hasTarget(): bool
    {
        return ! empty($this->getTarget());
    }
}

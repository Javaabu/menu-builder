<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait CanBeHidden
{
    public Closure | bool | null $hide = null;

    public function hide(Closure | bool $hide): self
    {
        $this->hide = $hide;
        return $this;
    }

    public function isHidden(): bool
    {
        if (! isset($this->hide)) {
            return false;
        }

        return $this->evaluate($this->hide);
    }
}

<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait HasIcon
{
    public Closure | string | null $icon = null;

    public function icon(Closure | string | null $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getIcon(): string | null
    {
        return $this->evaluate($this->icon);
    }

    public function hasIcon(): bool
    {
        return ! is_null($this->getIcon());
    }
}

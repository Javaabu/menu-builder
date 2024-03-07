<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;

trait HasIcon
{
    protected Closure | string | null $icon = null;

    public function icon(Closure | string | null $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon($prefix = ''): ?string
    {
        return $prefix . $this->evaluate($this->icon);
    }

    public function hasIcon(): bool
    {
        return ! empty($this->getIcon());
    }
}

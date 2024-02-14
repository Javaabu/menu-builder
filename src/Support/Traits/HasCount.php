<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait HasCount
{
    public Closure | int $count = 0;

    public function count(Closure | int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function hasCount(): bool
    {
        return $this->getCount() > 0;
    }

    public function getCount(): string
    {
        return $this->evaluate($this->count);
    }
}

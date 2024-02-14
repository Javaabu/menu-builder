<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;

trait HasLink
{
    public Closure | string | null $link = null;

    public function link(Closure | string |null $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getLink(): string | null
    {
        return $this->evaluate($this->link);
    }
}

<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;

trait HasBadge
{
    protected Closure | string | null $badge = null;
    protected Closure | string | null $badge_class = null;

    public function badge(Closure | string | null $badge, Closure | string | null $badge_class = null): self
    {
        $this->badge = $badge;
        $this->badge_class = $badge_class;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->evaluate($this->badge);
    }

    public function getBadgeClass(): ?string
    {
        return $this->evaluate($this->badge_class);
    }

    public function hasBadge(): bool
    {
        return ! empty($this->getBadge());
    }
}

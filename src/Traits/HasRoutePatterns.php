<?php

namespace Javaabu\MenuBuilder\Traits;

trait HasRoutePatterns
{
    protected array $route_patterns = [];

    public function routePattern(array | string $patterns): self
    {
        $this->route_patterns = is_array($patterns) ? $patterns : func_get_args();;

        return $this;
    }

    public function getRoutePatterns(): array
    {
        return $this->route_patterns;
    }

    public function hasRoutePatterns(): bool
    {
        return ! empty($this->getRoutePatterns());
    }

    protected function checkActiveFromRoutePatterns(): bool
    {
        return request()->routeIs($this->getRoutePatterns());
    }
}

<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait HasRoute
{
    protected ?string $route = null;
    protected $route_parameters = [];
    protected bool $route_absolute = true;

    public function route(string $route, $parameters = [], $absolute = true): self
    {
        $this->clearLink();

        $this->route = $route;
        $this->route_parameters = $parameters;
        $this->route_absolute = $absolute;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getRouteParameters()
    {
        return $this->route_parameters;
    }

    public function isRouteAbsolute(): bool
    {
        return $this->route_absolute;
    }

    public function hasRoute(): bool
    {
        return ! empty($this->getRoute());
    }

    protected function checkActiveFromRoute(): bool
    {
        return request()->route()->getName() == $this->getRoute() && $this->routeParametersMatches();
    }

    protected function routeParametersMatches(): bool
    {
        $current_parameters = array_values($this->getCurrentRequestParameters());
        $defined_parameters = $this->getRouteParameters();

        if ($defined_parameters) {
            foreach ($defined_parameters as $index => $value) {
                $current_value = $current_parameters[$index] ?? null;

                if ($value instanceof Model) {
                    if ($value->getRouteKey() != $current_value) {
                        return false;
                    }
                } elseif ($value != $current_value) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function getCurrentRequestParameters(): array
    {
        $parameters_names = request()->route()->parameterNames();
        $parameters = [];

        foreach ($parameters_names as $name) {
            $parameters[$name] = request()->route()->parameter($name);
        }

        return $parameters;
    }

    protected function clearRoute(): self
    {
        $this->route = null;
        $this->route_parameters = [];
        $this->route_absolute = true;

        return $this;
    }

    protected function generateRouteLink(): string
    {
        return route($this->getRoute(), $this->getRouteParameters(), $this->isRouteAbsolute());
    }
}

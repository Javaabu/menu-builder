<?php

namespace Javaabu\MenuBuilder\Menu;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\View\View;
use Javaabu\MenuBuilder\MenuBuilder;

abstract class Menu
{
    protected string $icon_prefix = '';

    protected string $default_framework = '';

    protected ?string $guard = null;

    public function getGuard(): ?string
    {
        return $this->guard;
    }

    public function getIconPrefix(): ?string
    {
        return $this->icon_prefix;
    }

    public function getCurrentUser(): ?Authorizable
    {
        return auth()->guard($this->getGuard())->user();
    }

    public function getVisibleMenuItems(?Authorizable $user = null): array
    {
        return collect($this->menuItems())
                ->filter(function (MenuItem $item) use ($user) {
                    return $item->canView($user);
                })
                ->all();
    }

    public function guard(string $guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    public function iconPrefix(string $icon_prefix): self
    {
        $this->icon_prefix = $icon_prefix;

        return $this;
    }

    public function links(string $view = ''): string
    {
        if (! $view) {
            $view = MenuBuilder::defaultView($this->getDefaultFramework());
        }

        return $this->getView($view)->render();
    }

    protected function getViewData(): array
    {
        $user = $this->getCurrentUser();

        return [
            'items' => $this->getVisibleMenuItems($user),
            'user' => $user,
            'icon_prefix' => $this->getIconPrefix(),
        ];
    }

    public function getView(string $view): View
    {
        return view($view, $this->getViewData());
    }

    public function getDefaultFramework(): string
    {
        return $this->default_framework;
    }

    public function useBootstrap5(): self
    {
        $this->default_framework = 'bootstrap-5';

        return $this;
    }

    public function useMaterialAdmin26(): self
    {
        $this->default_framework = 'material-admin-26';

        return $this;
    }

    public abstract function menuItems(): array;
}

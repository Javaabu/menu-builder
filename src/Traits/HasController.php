<?php

namespace Javaabu\MenuBuilder\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasController
{
    protected ?string $controller = null;

    public function controller(string $controller): self
    {
        $this->clearLink();

        $this->controller = $controller;

        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function hasController(): bool
    {
        return ! empty($this->getController());
    }

    protected function checkActiveFromController(): bool
    {
        return $this->getCurrentController() == $this->getController();
    }

    protected function getCurrentController(): ?string
    {
        return get_class(request()->route()->getController());
    }

    protected function clearController(): self
    {
        $this->controller = null;

        return $this;
    }

    protected function generateControllerLink(): string
    {
        return action([$this->getController(), $this->getControllerMethod()]);
    }

    protected function getControllerMethod(): string
    {
        return 'index';
    }
}

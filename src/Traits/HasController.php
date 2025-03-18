<?php

namespace Javaabu\MenuBuilder\Traits;

trait HasController
{
    protected ?string $controller = null;
    protected string $controller_method = 'index';
    protected array $controller_params = [];

    public function controller(string $controller, array $params = [], ?string $controller_method = 'index'): self
    {
        $this->clearLink();

        $this->controller = $controller;
        $this->controller_method = $controller_method;
        $this->controller_params = $params;

        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function hasController(): bool
    {
        return !empty($this->getController());
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
        return action([$this->getController(), $this->getControllerMethod()], $this->getControllerParams());
    }

    public function getControllerParams(): array
    {
        return $this->controller_params;
    }

    protected function getControllerMethod(): string
    {
        return $this->controller_method;
    }
}

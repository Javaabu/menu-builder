<?php

namespace Javaabu\MenuBuilder\Support\Traits;

use Closure;
use Exception;
use Illuminate\View\View;
use Javaabu\Paperless\Support\Components\Component;

trait HasView
{
    protected array $viewData = [];
    protected string | Closure | null $defaultView = null;


    public function setView(string | Closure | null $view): static
    {
        $this->defaultView = $view;
        return $this;
    }

    public function getDefaultView(): ?string
    {
        return $this->evaluate($this->defaultView);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function extractPublicMethods(): array
    {
        $reflection = new \ReflectionClass($this);
        $public_methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = [];

        foreach ($public_methods as $method) {
            $methods[$method->getName()] = \Closure::fromCallable([$this, $method->getName()]);
        }

        return $methods;
    }

    public function render(): View
    {
        return view(
            $this->getDefaultView(),
            [
                ...$this->extractPublicMethods(),
                ...$this->viewData,
            ]
        );
    }
}

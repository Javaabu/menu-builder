<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;
use Javaabu\MenuBuilder\Support\ChildMenuItem;

trait CanHaveChildren
{
    public array $children = [];
    protected string | Closure | null $childView = null;

    public function children(array $children): self
    {
        $this->children = $children;
        return $this;
    }

    public function getChildren(): array
    {
        return $this->evaluate($this->children);
    }

    public function hasChildren(): bool
    {
        return count($this->getChildren()) > 0;
    }

    public function hasActiveChild(): bool
    {
        return collect($this->getChildren())->contains(fn ($child) => $child->isActive());
    }

    public function setChildView(string | Closure | null $view): static
    {
        $this->childView = $view;
        return $this;
    }

    public function getChildView(): ?string
    {
        return $this->evaluate($this->childView);
    }

    public function renderChildren(): string
    {
        $html = "<ul>";
        foreach ($this->getChildren() as $child) {
            /* @var ChildMenuItem $child */
            $html .= $child->setView($this->getChildView())
                           ->toHtml();
        }

        $html .= "</ul>";
        return $html;
    }
}

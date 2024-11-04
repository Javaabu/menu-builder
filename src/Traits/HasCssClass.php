<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;

trait HasCssClass
{
    protected Closure | string | null $css_class = null;

    public function cssClass(Closure | string | null $css_class): self
    {
        $this->css_class = $css_class;

        return $this;
    }

    public function getCssClass(): ?string
    {
        return $this->evaluate($this->css_class);
    }

    public function hasCssClass(): bool
    {
        return ! empty($this->getCssClass());
    }
}

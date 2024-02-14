<?php

namespace Javaabu\MenuBuilder\Support;

use Closure;
use Illuminate\Support\Traits\Macroable;

class ChildMenuItem
{
    use Traits\HasView;
    use Traits\HasLink;
    use Traits\HasPermission;
    use Traits\HasCount;
    use Traits\CanActivate;
    use Traits\CanBeHidden;
    use Macroable;

    public function __construct(
        public string $label
    )
    {
    }

    public static function make(string $label): self
    {
        return new static($label);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    protected function evaluate($value)
    {
        if (! $value instanceof Closure) {
            return $value;
        }

        return app()->call($value);
    }
}

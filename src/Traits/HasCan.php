<?php

namespace Javaabu\MenuBuilder\Traits;

use Closure;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

trait HasCan
{
    protected Closure | string | null $can = null;
    protected array $can_arguments = [];

    public function can(Closure | string $can, $can_arguments = []): self
    {
        $this->can = $can;
        $this->can_arguments = Arr::wrap($can_arguments);

        return $this;
    }

    public function getCan(): Closure | string | null
    {
        return $this->can;
    }

    public function getCanArguments(): array
    {
        return $this->can_arguments;
    }

    protected function hasCan(): bool
    {
        return ! empty($this->getCan());
    }

    protected function userCanViewWithCan(?Authorizable $user = null): bool
    {
        if ($this->hasCan()) {

            $can = $this->getCan();

            if ($can instanceof Closure) {
                return $this->evaluate($can, ['user' => $user]);
            } elseif ($user) {
                return $user->can($can, $this->getCanArguments());
            }

            return false;
        }

        return true;
    }
}

<?php

namespace Javaabu\MenuBuilder\Tests\Policies;

use Javaabu\MenuBuilder\Tests\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->name == 'John';
    }

    public function update(User $user, User $user_object): bool
    {
        return $user->id == $user_object->id;
    }
}

<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function updateRole(User $user, User $target): bool
    {
        return $user->hasRole(Role::ADMIN) && $user->id !== $target->id;
    }
}

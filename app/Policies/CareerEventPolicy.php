<?php

namespace App\Policies;

use App\Models\CareerEvent;
use App\Models\Role;
use App\Models\User;

class CareerEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::MAHASISWA);
    }

    public function view(User $user, CareerEvent $event): bool
    {
        return $user->hasRole(Role::ADMIN) || ($user->hasRole(Role::MAHASISWA) && $event->isPublished());
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function update(User $user, CareerEvent $event): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function delete(User $user, CareerEvent $event): bool
    {
        return $user->hasRole(Role::ADMIN);
    }
}


<?php

namespace App\Policies;

use App\Models\Profession;
use App\Models\Role;
use App\Models\User;

class ProfessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::MAHASISWA);
    }

    public function view(User $user, Profession $profession): bool
    {
        return $user->hasRole(Role::ADMIN) || ($user->hasRole(Role::MAHASISWA) && $profession->is_active && $profession->publication_status === 'published');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function update(User $user, Profession $profession): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function delete(User $user, Profession $profession): bool
    {
        return $user->hasRole(Role::ADMIN);
    }
}

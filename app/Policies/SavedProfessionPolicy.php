<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\SavedProfession;
use App\Models\User;

class SavedProfessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::MAHASISWA);
    }

    public function view(User $user, SavedProfession $savedProfession): bool
    {
        return $user->hasRole(Role::MAHASISWA) && $user->id === $savedProfession->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::MAHASISWA);
    }

    public function delete(User $user, SavedProfession $savedProfession): bool
    {
        return $user->hasRole(Role::MAHASISWA) && $user->id === $savedProfession->user_id;
    }
}


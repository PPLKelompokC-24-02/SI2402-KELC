<?php

namespace App\Policies;

use App\Models\ActionPlan;
use App\Models\Role;
use App\Models\User;

class ActionPlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::MAHASISWA);
    }

    public function view(User $user, ActionPlan $actionPlan): bool
    {
        return $user->id === $actionPlan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::MAHASISWA);
    }

    public function update(User $user, ActionPlan $actionPlan): bool
    {
        return $user->id === $actionPlan->user_id;
    }

    public function delete(User $user, ActionPlan $actionPlan): bool
    {
        return $user->id === $actionPlan->user_id;
    }
}

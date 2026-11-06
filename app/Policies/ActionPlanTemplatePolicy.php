<?php

namespace App\Policies;

use App\Models\ActionPlanTemplate;
use App\Models\Role;
use App\Models\User;

class ActionPlanTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::MAHASISWA);
    }

    public function view(User $user, ActionPlanTemplate $actionPlanTemplate): bool
    {
        return $user->hasRole(Role::ADMIN) || ($user->hasRole(Role::MAHASISWA) && $actionPlanTemplate->is_active);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function update(User $user, ActionPlanTemplate $actionPlanTemplate): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function delete(User $user, ActionPlanTemplate $actionPlanTemplate): bool
    {
        return $user->hasRole(Role::ADMIN);
    }
}

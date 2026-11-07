<?php

namespace App\Policies;

use App\Models\CurriculumVitae;
use App\Models\User;

class CurriculumVitaePolicy
{
    public function view(User $user, CurriculumVitae $curriculumVitae): bool
    {
        return $user->id === $curriculumVitae->user_id;
    }

    public function update(User $user, CurriculumVitae $curriculumVitae): bool
    {
        return $user->id === $curriculumVitae->user_id && $user->hasRole('mahasiswa');
    }

    public function delete(User $user, CurriculumVitae $curriculumVitae): bool
    {
        return $user->id === $curriculumVitae->user_id && $user->hasRole('mahasiswa');
    }
}


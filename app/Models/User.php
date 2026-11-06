<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'nim', 'phone', 'bio'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function actionPlans(): HasMany
    {
        return $this->hasMany(ActionPlan::class);
    }

    public function savedProfessions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'saved_professions')->withTimestamps();
    }

    public function savedProfessionPivots(): HasMany
    {
        return $this->hasMany(SavedProfession::class);
    }

    public function hasSavedProfession(int|Profession $profession): bool
    {
        $id = $profession instanceof Profession ? $profession->id : $profession;

        return $this->savedProfessions()->where('professions.id', $id)->exists();
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role?->name, $roles, true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

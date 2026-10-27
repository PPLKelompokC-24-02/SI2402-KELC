<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class AppLayout
{
    public static function data(User $user): array
    {
        $user->loadMissing('role');

        return [
            'user' => $user,
            'roleLabel' => config('navigation.roles.'.$user->role->name),
            'initials' => Str::of($user->name)->squish()->explode(' ')->filter()->take(2)
                ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode(''),
            'navigation' => Navigation::forRole($user->role->name),
        ];
    }
}

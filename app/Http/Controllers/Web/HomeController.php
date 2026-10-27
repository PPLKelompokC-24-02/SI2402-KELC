<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('web')->loadMissing('role');
        $role = $user->role->name;
        $initials = Str::of($user->name)->squish()->explode(' ')->filter()->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');

        return view('home', [
            'user' => $user,
            'roleLabel' => config('navigation.roles.'.$role),
            'initials' => $initials,
            'navigation' => Navigation::forRole($role),
        ]);
    }
}

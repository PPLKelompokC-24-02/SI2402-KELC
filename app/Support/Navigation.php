<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Navigation
{
    public static function forRole(string $role): array
    {
        return array_map(function (array $section) {
            $section['items'] = array_map(function (array $item) {
                $route = Route::getRoutes()->getByName($item['route']);
                $method = $item['method'] ?? 'GET';
                // An API route is not a page, even if its name matches a menu item.
                $available = $route && in_array('web', $route->middleware(), true)
                    && in_array($method, $route->methods(), true);
                $item['url'] = $available ? route($item['route']) : null;
                $item['active'] = $available && request()->routeIs($item['active'] ?? $item['route']);

                return $item;
            }, $section['items']);

            return $section;
        }, config('navigation.menus.'.$role, []));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserRoleController extends Controller
{
    public function index(CatalogRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);

        return UserResource::collection(User::with('role')->orderBy('id')->paginate($request->integer('per_page', 15))->withQueryString());
    }

    public function update(UpdateUserRoleRequest $request, User $user): UserResource
    {
        Gate::authorize('updateRole', $user);
        DB::transaction(function () use ($request, $user) {
            // Serialize administrator changes to preserve at least one administrator.
            $admins = User::whereHas('role', fn ($query) => $query->where('name', Role::ADMIN))->orderBy('id')->lockForUpdate()->get();
            abort_unless($request->user()->fresh()->hasRole(Role::ADMIN), 403, 'Hanya admin yang dapat mengubah role.');
            $target = User::lockForUpdate()->findOrFail($user->id);
            $role = Role::where('name', $request->validated('role'))->firstOrFail();
            abort_if($target->hasRole(Role::ADMIN) && $role->name !== Role::ADMIN && $admins->count() <= 1, 403, 'Admin terakhir tidak dapat diubah.');

            if ($target->role_id !== $role->id) {
                $target->role()->associate($role);
                $target->save();
                $target->tokens()->delete();
            }
        });

        return (new UserResource($user->refresh()->load('role')))->additional(['message' => 'Role pengguna berhasil diperbarui. Pengguna perlu login ulang jika role berubah.']);
    }
}

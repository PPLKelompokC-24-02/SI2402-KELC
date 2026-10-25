<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = new User($request->safe()->only(['name', 'email', 'password', 'nim', 'phone']));
            $user->role()->associate(Role::where('name', Role::MAHASISWA)->firstOrFail());
            $user->save();

            return $this->tokenResponse($user, 'Registrasi berhasil.', 201);
        });
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::with('role')->where('email', $request->validated('email'))->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        if (Hash::needsRehash($user->password)) {
            $user->update(['password' => $request->validated('password')]);
        }

        return $this->tokenResponse($user, 'Login berhasil.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    public function me(Request $request): UserResource
    {
        return (new UserResource($request->user()->load('role')))->additional(['message' => 'Profil pengguna.']);
    }

    public function updateProfile(UpdateProfileRequest $request): UserResource
    {
        $user = $request->user();
        DB::transaction(function () use ($request, $user) {
            $data = $request->safe()->except(['current_password', 'password_confirmation', 'role', 'role_id']);
            if (isset($data['email']) && $data['email'] !== $user->email) {
                $user->email_verified_at = null;
            }
            $user->fill($data)->save();

            if (isset($data['password'])) {
                $user->tokens()->where('id', '!=', $user->currentAccessToken()->getKey())->delete();
            }
        });

        return (new UserResource($user->load('role')))->additional(['message' => 'Profil berhasil diperbarui.']);
    }

    private function tokenResponse(User $user, string $message, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => [
                'token' => $user->createToken('career-compass')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => new UserResource($user->loadMissing('role')),
            ],
        ], $status);
    }
}

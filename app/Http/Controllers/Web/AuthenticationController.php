<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebLoginRequest;
use App\Http\Requests\WebRegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticationController extends Controller
{
    public function loginPage(): View
    {
        return view('auth.index', ['register' => false]);
    }

    public function registerPage(): View
    {
        return view('auth.index', ['register' => true]);
    }

    public function login(WebLoginRequest $request): JsonResponse|RedirectResponse
    {
        if (! Auth::guard('web')->attempt($request->safe()->only(['email', 'password']), $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'Email atau kata sandi tidak sesuai. Silakan coba lagi.']);
        }

        $request->session()->regenerate();

        return $this->authenticated($request, 'Berhasil masuk. Selamat datang kembali.');
    }

    public function register(WebRegisterRequest $request): JsonResponse|RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = new User($request->safe()->only(['name', 'email', 'password']));
            $user->role()->associate(Role::where('name', Role::MAHASISWA)->firstOrFail());
            $user->save();

            return $user;
        });

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return $this->authenticated($request, 'Akunmu berhasil dibuat. Selamat datang di Career Compass.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Kamu sudah keluar dari akun.');
    }

    private function authenticated(Request $request, string $message): JsonResponse|RedirectResponse
    {
        $request->session()->flash('status', $message);

        return $request->expectsJson()
            ? response()->json(['message' => $message, 'redirect' => route('home')])
            : redirect()->route('home');
    }
}

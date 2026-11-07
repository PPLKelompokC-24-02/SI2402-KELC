<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function user(string $role = 'mahasiswa'): User
    {
        return User::where('email', $role.'@careercompass.test')->firstOrFail();
    }

    protected function tokenFor(string $role = 'mahasiswa'): string
    {
        return $this->user($role)->createToken('test')->plainTextToken;
    }

    protected function authenticate(string $role = 'mahasiswa'): static
    {
        $this->app['auth']->forgetGuards();

        return $this->withToken($this->tokenFor($role));
    }
}

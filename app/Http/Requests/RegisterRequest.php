<?php

namespace App\Http\Requests;

use App\Rules\PasswordInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => mb_strtolower(trim($this->email))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', new PasswordInput, 'confirmed', Password::min(8)->letters()->numbers()],
            'nim' => ['nullable', 'string', 'max:30', 'unique:users,nim'],
            'phone' => ['nullable', 'string', 'max:25'],
            'role' => ['prohibited'],
            'role_id' => ['prohibited'],
        ];
    }
}

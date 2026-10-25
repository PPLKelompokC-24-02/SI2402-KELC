<?php

namespace App\Http\Requests;

use App\Rules\PasswordInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'nim' => ['sometimes', 'nullable', 'string', 'max:30', Rule::unique('users', 'nim')->ignore($this->user()->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:25'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'password' => ['sometimes', 'required', 'string', new PasswordInput, 'confirmed', Password::min(8)->letters()->numbers()],
            'current_password' => ['required_with:password', 'string', new PasswordInput, 'current_password:sanctum'],
            'role' => ['prohibited'],
            'role_id' => ['prohibited'],
        ];
    }
}

<?php

namespace App\Http\Requests;

class WebLoginRequest extends LoginRequest
{
    public function rules(): array
    {
        return [...parent::rules(), 'remember' => ['sometimes', 'boolean']];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }
}

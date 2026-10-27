<?php

namespace App\Http\Requests;

class WebRegisterRequest extends RegisterRequest
{
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan masuk ke akunmu.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Gunakan minimal 8 karakter untuk kata sandi.',
            'password.letters' => 'Kata sandi harus mengandung huruf.',
            'password.numbers' => 'Kata sandi harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi kata sandi belum sesuai.',
        ];
    }
}

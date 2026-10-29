<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordInput implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Bcrypt accepts at most 72 bytes; validate bytes, including multibyte text.
        if (is_string($value) && (strlen($value) > 72 || str_contains($value, "\0"))) {
            $fail('Password maksimal 72 byte dan tidak boleh mengandung karakter null.');
        }
    }
}

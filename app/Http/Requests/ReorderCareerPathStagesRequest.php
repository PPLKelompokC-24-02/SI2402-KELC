<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class ReorderCareerPathStagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(Role::ADMIN) ?? false;
    }

    public function rules(): array
    {
        return [
            'stage_ids' => ['required', 'array', 'min:1'],
            'stage_ids.*' => ['required', 'integer', 'distinct', 'exists:career_path_stages,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib dikirimkan.',
            'array' => ':attribute harus berupa daftar data.',
            'min.array' => ':attribute minimal memiliki :min data.',
            'exists' => ':attribute tidak ditemukan pada database.',
            'distinct' => ':attribute tidak boleh duplikat.',
        ];
    }

    public function attributes(): array
    {
        return [
            'stage_ids' => 'Daftar tahapan',
            'stage_ids.*' => 'Tahapan',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Validation\Rule;

class ExploreProfessionsRequest extends CatalogRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(Role::MAHASISWA) ?? false;
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'category' => ['nullable', 'integer', 'exists:career_categories,id'],
            'tab' => ['nullable', Rule::in(['overview', 'responsibilities', 'competencies', 'path', 'courses', 'tools'])],
        ];
    }

    public function messages(): array
    {
        return [
            'keyword.string' => 'Kata kunci harus berupa teks.',
            'keyword.max' => 'Kata kunci maksimal 150 karakter.',
            'category.integer' => 'Kategori yang dipilih tidak valid.',
            'category.exists' => 'Kategori yang dipilih tidak tersedia.',
            'tab.in' => 'Bagian informasi profesi tidak tersedia.',
        ];
    }
}

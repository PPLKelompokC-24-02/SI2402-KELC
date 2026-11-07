<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CareerPathStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(Role::ADMIN) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'competency_ids' => ['nullable', 'array', 'max:100'],
            'competency_ids.*' => ['required', 'integer', 'distinct', 'exists:competencies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.array' => ':attribute maksimal :max isian.',
            'exists' => ':attribute tidak tersedia pada master data.',
            'distinct' => ':attribute tidak boleh duplikat.',
            'integer' => ':attribute harus berupa angka bilangan bulat.',
            'min.numeric' => ':attribute minimal bernilai :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Nama tahapan',
            'description' => 'Deskripsi tahapan',
            'sort_order' => 'Urutan tahapan',
            'competency_ids' => 'Kompetensi',
            'competency_ids.*' => 'Kompetensi',
        ];
    }
}

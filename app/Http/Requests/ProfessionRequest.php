<?php

namespace App\Http\Requests;

use App\Models\Profession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profession = $this->route('profession');

        return $profession instanceof Profession
            ? $this->user()->can('update', $profession)
            : $this->user()->can('create', Profession::class);
    }

    public function rules(): array
    {
        $profession = $this->route('profession');
        $presence = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'name' => [...$presence, 'string', 'max:150', Rule::unique('professions', 'name')->ignore($profession?->id)],
            'description' => [...$presence, 'string', 'max:20000'],
            'responsibilities' => [...$presence, 'array', 'list', 'min:1', 'max:50'],
            'responsibilities.*' => ['required', 'string', 'max:2000'],
            'career_category_id' => [...$presence, 'integer', 'exists:career_categories,id'],
            'competency_ids' => [...$presence, 'array', 'list', 'min:1', 'max:100'],
            'competency_ids.*' => ['required', 'integer', 'distinct', 'exists:competencies,id'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ];
    }
}

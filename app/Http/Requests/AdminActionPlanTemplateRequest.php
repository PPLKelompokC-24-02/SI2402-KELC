<?php

namespace App\Http\Requests;

use App\Models\ActionPlanTemplate;
use Illuminate\Foundation\Http\FormRequest;

class AdminActionPlanTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $template = $this->route('action_template') ?? $this->route('actionTemplate');

        return $template instanceof ActionPlanTemplate
            ? ($this->user()?->can('update', $template) ?? false)
            : ($this->user()?->can('create', ActionPlanTemplate::class) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if (is_string($this->input('title'))) {
            $merge['title'] = trim($this->input('title'));
        }

        if (is_string($this->input('description'))) {
            $merge['description'] = trim($this->input('description'));
        }

        if ($this->has('is_active')) {
            $merge['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }

        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'filled', 'max:255'],
            'description' => ['required', 'string', 'filled', 'max:5000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'filled' => ':attribute tidak boleh hanya berisi spasi kosong.',
            'title.required' => 'Judul template aktivitas wajib diisi.',
            'title.filled' => 'Judul template aktivitas wajib diisi dan tidak boleh hanya berisi spasi kosong.',
            'description.required' => 'Deskripsi template aktivitas wajib diisi.',
            'description.filled' => 'Deskripsi template aktivitas wajib diisi dan tidak boleh hanya berisi spasi kosong.',
            'max.string' => ':attribute maksimal :max karakter.',
            'boolean' => 'Format status aktif tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul template',
            'description' => 'Deskripsi template',
            'is_active' => 'Status aktif',
        ];
    }
}

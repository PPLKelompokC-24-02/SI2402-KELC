<?php

namespace App\Http\Requests;

use App\Models\ActionPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareerActionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actionPlan = $this->route('action_plan') ?? $this->route('actionPlan');

        return $actionPlan instanceof ActionPlan
            ? ($this->user()?->can('update', $actionPlan) ?? false)
            : ($this->user()?->can('create', ActionPlan::class) ?? false);
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

        if ($this->has('due_date')) {
            $dueDate = $this->input('due_date');
            $merge['due_date'] = (is_string($dueDate) && trim($dueDate) !== '') ? trim($dueDate) : null;
        }

        if (! $this->has('status') && $this->isMethod('POST')) {
            $merge['status'] = ActionPlan::STATUS_NOT_STARTED;
        }

        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'filled', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(ActionPlan::STATUSES)],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'filled' => ':attribute tidak boleh hanya berisi spasi kosong.',
            'title.required' => 'Judul aktivitas wajib diisi.',
            'title.filled' => 'Judul aktivitas wajib diisi dan tidak boleh hanya berisi spasi kosong.',
            'title.max' => 'Judul aktivitas maksimal :max karakter.',
            'description.max' => 'Deskripsi aktivitas maksimal :max karakter.',
            'due_date.date' => 'Format tenggat waktu tidak valid.',
            'status.required' => 'Status aktivitas wajib dipilih.',
            'status.in' => 'Status aktivitas yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul aktivitas',
            'description' => 'Deskripsi aktivitas',
            'due_date' => 'Tenggat waktu',
            'status' => 'Status pelaksanaan',
        ];
    }
}


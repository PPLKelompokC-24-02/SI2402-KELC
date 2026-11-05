<?php

namespace App\Http\Requests;

use App\Models\ActionPlanTemplate;
use Illuminate\Validation\Rule;

class AdminActionPlanTemplateIndexRequest extends CatalogRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', ActionPlanTemplate::class) ?? false;
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'status' => ['nullable', Rule::in(['active', 'inactive', 'all'])],
        ];
    }
}


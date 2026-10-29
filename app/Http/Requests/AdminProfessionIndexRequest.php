<?php

namespace App\Http\Requests;

use App\Models\Profession;
use Illuminate\Validation\Rule;

class AdminProfessionIndexRequest extends CatalogRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Profession::class) ?? false;
    }

    public function rules(): array
    {
        return [...parent::rules(), 'status' => ['nullable', Rule::in(['draft', 'published', 'inactive'])]];
    }
}

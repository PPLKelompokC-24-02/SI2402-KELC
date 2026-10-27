<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateRole', $this->route('user'));
    }

    public function rules(): array
    {
        return ['role' => ['required', 'string', Rule::in([Role::ADMIN, Role::ASSESSOR, Role::MAHASISWA])]];
    }
}

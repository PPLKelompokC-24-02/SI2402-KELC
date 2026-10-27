<?php

namespace App\Http\Requests;

use App\Models\Profession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class WebProfessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profession = $this->route('profession');

        return $profession instanceof Profession
            ? $this->user()->can('update', $profession)
            : $this->user()->can('create', Profession::class);
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('industries_text'))) {
            $this->merge(['industries' => array_values(array_filter(array_map('trim', explode(',', $this->input('industries_text')))))]);
        }
        foreach (['prospects', 'responsibilities', 'competency_ids'] as $field) {
            if (is_array($this->input($field))) {
                $this->merge([$field => array_values(array_filter($this->input($field), fn ($value) => $value !== null && $value !== ''))]);
            }
        }
    }

    public function rules(): array
    {
        $publish = $this->input('action') === 'publish';
        $required = $publish ? 'required' : 'nullable';
        $profession = $this->route('profession');

        return [
            'action' => ['required', Rule::in(['draft', 'publish'])],
            'name' => ['required', 'string', 'max:150', Rule::unique('professions', 'name')->ignore($profession?->id)],
            'career_category_id' => [$required, 'integer', 'exists:career_categories,id'],
            'summary' => [$required, 'string', 'max:200'],
            'description' => [$required, 'string', 'max:20000'],
            'illustration' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'remove_illustration' => ['sometimes', 'boolean'],
            'entry_level' => [$required, Rule::in(config('professions.entry_levels'))],
            'career_start' => [$required, Rule::in(config('professions.career_levels'))],
            'career_end' => [$required, Rule::in(config('professions.career_levels'))],
            'industries' => [$required, 'array', 'max:30'],
            'industries.*' => ['required', 'string', 'max:100', 'distinct'],
            'prospects' => [$required, 'array', 'max:20'],
            'prospects.*' => ['required', 'string', 'max:1000'],
            'responsibilities' => [$required, 'array', 'max:50'],
            'responsibilities.*' => ['required', 'string', 'max:2000'],
            'competency_ids' => [$required, 'array', 'max:100'],
            'competency_ids.*' => ['required', 'integer', 'distinct', 'exists:competencies,id'],
            'tools' => ['nullable', 'array', 'max:30'],
            'tools.*' => ['array:key,name,usage,logo,remove_logo'],
            'tools.*.key' => ['nullable', 'uuid', 'distinct'],
            'tools.*.name' => [$required, 'string', 'max:150'],
            'tools.*.usage' => [$required, 'string', 'max:1000'],
            'tools.*.logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'tools.*.remove_logo' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            if ($this->input('action') !== 'publish') {
                return;
            }
            $profession = $this->route('profession');
            if (! $this->hasFile('illustration') && (! $profession?->illustration_path || ! Storage::disk('local')->exists($profession->illustration_path) || $this->boolean('remove_illustration'))) {
                $validator->errors()->add('illustration', 'Unggah ilustrasi profesi sebelum menerbitkan.');
            }
            $levels = config('professions.career_levels');
            $start = array_search($this->input('career_start'), $levels, true);
            $end = array_search($this->input('career_end'), $levels, true);
            if ($start !== false && $end !== false && $start > $end) {
                $validator->errors()->add('career_end', 'Jenjang akhir tidak boleh lebih rendah dari jenjang awal.');
            }
        }];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi sebelum diterbitkan.',
            'name.required' => 'Nama profesi wajib diisi, termasuk untuk menyimpan draf.',
            'name.unique' => 'Nama profesi sudah digunakan.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.array' => ':attribute maksimal :max isian.',
            'exists' => ':attribute tidak tersedia pada master data.',
            'distinct' => ':attribute tidak boleh duplikat.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa PNG atau JPG.',
            'illustration.max' => 'Ilustrasi profesi maksimal 2 MB.',
            'tools.*.logo.max' => 'Logo tool maksimal 1 MB.',
            'in' => 'Pilihan :attribute tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama profesi', 'career_category_id' => 'Kategori', 'summary' => 'Deskripsi singkat',
            'description' => 'Deskripsi lengkap', 'entry_level' => 'Tingkat awal', 'career_start' => 'Jenjang awal',
            'career_end' => 'Jenjang akhir', 'industries' => 'Bidang industri', 'prospects' => 'Prospek karier',
            'responsibilities' => 'Tanggung jawab', 'competency_ids' => 'Kompetensi', 'illustration' => 'Ilustrasi profesi',
            'prospects.*' => 'Prospek karier', 'responsibilities.*' => 'Tanggung jawab', 'competency_ids.*' => 'Kompetensi',
            'tools.*.name' => 'Nama tool', 'tools.*.usage' => 'Kegunaan tool', 'tools.*.logo' => 'Logo tool',
        ];
    }
}

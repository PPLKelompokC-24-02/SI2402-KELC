<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurriculumVitaeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('web') !== null && $this->user('web')->hasRole('mahasiswa');
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:3000'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'portfolio_url' => ['nullable', 'string', 'max:255'],
            'github_url' => ['nullable', 'string', 'max:255'],
            'educations' => ['nullable', 'array'],
            'skills' => ['nullable', 'array'],
            'experiences' => ['nullable', 'array'],
            'projects' => ['nullable', 'array'],
            'certifications' => ['nullable', 'array'],
            'template' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap profil CV wajib diisi.',
            'full_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email kontak CV wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'summary.max' => 'Ringkasan profil maksimal 3000 karakter.',
        ];
    }

    public function sanitizedData(): array
    {
        $validated = $this->validated();

        // Bersihkan data pendidikan yang kosong
        $educations = [];
        if (!empty($validated['educations']) && is_array($validated['educations'])) {
            foreach ($validated['educations'] as $edu) {
                if (!empty(trim($edu['institution'] ?? '')) || !empty(trim($edu['degree'] ?? ''))) {
                    $educations[] = [
                        'institution' => trim($edu['institution'] ?? ''),
                        'degree' => trim($edu['degree'] ?? ''),
                        'field_of_study' => trim($edu['field_of_study'] ?? ''),
                        'start_year' => trim($edu['start_year'] ?? ''),
                        'end_year' => trim($edu['end_year'] ?? ''),
                        'gpa' => trim($edu['gpa'] ?? ''),
                        'description' => trim($edu['description'] ?? ''),
                    ];
                }
            }
        }

        // Bersihkan data keterampilan yang kosong
        $skills = [];
        if (!empty($validated['skills']) && is_array($validated['skills'])) {
            foreach ($validated['skills'] as $skill) {
                if (!empty(trim($skill['category'] ?? '')) || !empty(trim($skill['items'] ?? ''))) {
                    $skills[] = [
                        'category' => trim($skill['category'] ?? ''),
                        'items' => trim($skill['items'] ?? ''),
                    ];
                }
            }
        }

        // Bersihkan data pengalaman yang kosong
        $experiences = [];
        if (!empty($validated['experiences']) && is_array($validated['experiences'])) {
            foreach ($validated['experiences'] as $exp) {
                if (!empty(trim($exp['position'] ?? '')) || !empty(trim($exp['company'] ?? ''))) {
                    $experiences[] = [
                        'position' => trim($exp['position'] ?? ''),
                        'company' => trim($exp['company'] ?? ''),
                        'location' => trim($exp['location'] ?? ''),
                        'start_date' => trim($exp['start_date'] ?? ''),
                        'end_date' => trim($exp['end_date'] ?? ''),
                        'is_current' => !empty($exp['is_current']),
                        'description' => trim($exp['description'] ?? ''),
                    ];
                }
            }
        }

        // Bersihkan data proyek yang kosong
        $projects = [];
        if (!empty($validated['projects']) && is_array($validated['projects'])) {
            foreach ($validated['projects'] as $proj) {
                if (!empty(trim($proj['title'] ?? ''))) {
                    $projects[] = [
                        'title' => trim($proj['title'] ?? ''),
                        'role' => trim($proj['role'] ?? ''),
                        'period' => trim($proj['period'] ?? ''),
                        'url' => trim($proj['url'] ?? ''),
                        'technologies' => trim($proj['technologies'] ?? ''),
                        'description' => trim($proj['description'] ?? ''),
                    ];
                }
            }
        }

        // Bersihkan data sertifikasi yang kosong
        $certifications = [];
        if (!empty($validated['certifications']) && is_array($validated['certifications'])) {
            foreach ($validated['certifications'] as $cert) {
                if (!empty(trim($cert['name'] ?? ''))) {
                    $certifications[] = [
                        'name' => trim($cert['name'] ?? ''),
                        'issuer' => trim($cert['issuer'] ?? ''),
                        'issue_date' => trim($cert['issue_date'] ?? ''),
                        'expiration_date' => trim($cert['expiration_date'] ?? ''),
                        'credential_id' => trim($cert['credential_id'] ?? ''),
                        'credential_url' => trim($cert['credential_url'] ?? ''),
                    ];
                }
            }
        }

        return [
            'full_name' => trim($validated['full_name']),
            'headline' => trim($validated['headline'] ?? '') ?: null,
            'summary' => trim($validated['summary'] ?? '') ?: null,
            'email' => trim($validated['email']),
            'phone' => trim($validated['phone'] ?? '') ?: null,
            'address' => trim($validated['address'] ?? '') ?: null,
            'linkedin_url' => trim($validated['linkedin_url'] ?? '') ?: null,
            'portfolio_url' => trim($validated['portfolio_url'] ?? '') ?: null,
            'github_url' => trim($validated['github_url'] ?? '') ?: null,
            'educations' => $educations,
            'skills' => $skills,
            'experiences' => $experiences,
            'projects' => $projects,
            'certifications' => $certifications,
            'template' => $validated['template'] ?? 'ats-classic',
        ];
    }
}


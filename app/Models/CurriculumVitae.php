<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumVitae extends Model
{
    use HasFactory;

    protected $table = 'curriculum_vitaes';

    protected $fillable = [
        'user_id',
        'full_name',
        'headline',
        'summary',
        'email',
        'phone',
        'address',
        'linkedin_url',
        'portfolio_url',
        'github_url',
        'educations',
        'skills',
        'experiences',
        'projects',
        'certifications',
        'template',
    ];

    protected function casts(): array
    {
        return [
            'educations' => 'array',
            'skills' => 'array',
            'experiences' => 'array',
            'projects' => 'array',
            'certifications' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEducationsList(): array
    {
        return is_array($this->educations) ? $this->educations : [];
    }

    public function getSkillsList(): array
    {
        return is_array($this->skills) ? $this->skills : [];
    }

    public function getExperiencesList(): array
    {
        return is_array($this->experiences) ? $this->experiences : [];
    }

    public function getProjectsList(): array
    {
        return is_array($this->projects) ? $this->projects : [];
    }

    public function getCertificationsList(): array
    {
        return is_array($this->certifications) ? $this->certifications : [];
    }
}


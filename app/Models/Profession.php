<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profession extends Model
{
    protected $attributes = ['is_active' => true, 'publication_status' => 'published'];

    protected $fillable = [
        'name', 'description', 'responsibilities', 'career_category_id', 'is_active',
        'summary', 'illustration_path', 'entry_level', 'career_start', 'career_end',
        'industries', 'prospects', 'tools', 'publication_status',
    ];

    protected function casts(): array
    {
        return ['responsibilities' => 'array', 'is_active' => 'boolean', 'industries' => 'array', 'prospects' => 'array', 'tools' => 'array'];
    }

    public function careerCategory(): BelongsTo
    {
        return $this->belongsTo(CareerCategory::class);
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class)->withTimestamps();
    }
}

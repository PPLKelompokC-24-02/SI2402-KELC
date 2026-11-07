<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CareerPathStage extends Model
{
    protected $fillable = [
        'profession_id',
        'title',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'career_path_stage_competency')->withTimestamps();
    }
}

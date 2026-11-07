<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competency extends Model
{
    protected $fillable = ['name', 'description'];

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class)->withTimestamps();
    }

    public function careerPathStages(): BelongsToMany
    {
        return $this->belongsToMany(CareerPathStage::class, 'career_path_stage_competency')->withTimestamps();
    }
}

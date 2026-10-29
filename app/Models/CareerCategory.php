<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function professions(): HasMany
    {
        return $this->hasMany(Profession::class);
    }
}

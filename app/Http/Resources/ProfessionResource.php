<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'responsibilities' => $this->responsibilities ?? [],
            'is_active' => $this->is_active,
            'career_category' => $this->whenLoaded('careerCategory', fn () => $this->careerCategory?->only(['id', 'name', 'description'])),
            'competencies' => $this->whenLoaded('competencies', fn () => $this->competencies->map(fn ($competency) => [
                'id' => $competency->id,
                'name' => $competency->name,
                'description' => $competency->description,
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

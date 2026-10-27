<?php

namespace Tests\Feature;

use App\Models\Profession;
use Illuminate\Database\QueryException;

class DatabaseIntegrityTest extends ApiTestCase
{
    public function test_referenced_category_cannot_be_physically_deleted(): void
    {
        $this->expectException(QueryException::class);
        Profession::firstOrFail()->careerCategory->delete();
    }

    public function test_referenced_competency_cannot_be_physically_deleted(): void
    {
        $this->expectException(QueryException::class);
        Profession::firstOrFail()->competencies()->firstOrFail()->delete();
    }

    public function test_linked_profession_cannot_be_physically_deleted(): void
    {
        $this->expectException(QueryException::class);
        Profession::firstOrFail()->delete();
    }
}

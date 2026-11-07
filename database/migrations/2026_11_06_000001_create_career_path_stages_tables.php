<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('career_path_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained('professions')->cascadeOnDelete();
            $table->string('title', 150);
            $table->text('description');
            $table->unsignedInteger('sort_order');
            $table->timestamps();

            $table->index(['profession_id', 'sort_order']);
        });

        Schema::create('career_path_stage_competency', function (Blueprint $table) {
            $table->foreignId('career_path_stage_id')->constrained('career_path_stages')->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained('competencies')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['career_path_stage_id', 'competency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_path_stage_competency');
        Schema::dropIfExists('career_path_stages');
    }
};

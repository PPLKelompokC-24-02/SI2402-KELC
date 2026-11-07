<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('professions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150)->unique();
            $table->text('description');
            $table->json('responsibilities');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('competency_profession', function (Blueprint $table) {
            $table->foreignId('competency_id')->constrained()->restrictOnDelete();
            $table->foreignId('profession_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->primary(['competency_id', 'profession_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_profession');
        Schema::dropIfExists('professions');
        Schema::dropIfExists('competencies');
        Schema::dropIfExists('career_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculum_vitaes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            
            // Profil
            $table->string('full_name');
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();

            // Kontak
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('github_url')->nullable();

            // Data Terstruktur (Pendidikan, Keterampilan, Pengalaman, Proyek, Sertifikasi)
            $table->json('educations')->nullable();
            $table->json('skills')->nullable();
            $table->json('experiences')->nullable();
            $table->json('projects')->nullable();
            $table->json('certifications')->nullable();

            $table->string('template', 50)->default('ats-classic');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_vitaes');
    }
};


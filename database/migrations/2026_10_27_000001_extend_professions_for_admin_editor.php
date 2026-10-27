<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professions', function (Blueprint $table) {
            $table->unsignedBigInteger('career_category_id')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->json('responsibilities')->nullable()->change();
            $table->string('summary', 200)->nullable();
            $table->string('illustration_path')->nullable();
            $table->string('entry_level', 30)->nullable();
            $table->string('career_start', 30)->nullable();
            $table->string('career_end', 30)->nullable();
            $table->json('industries')->nullable();
            $table->json('prospects')->nullable();
            $table->json('tools')->nullable();
            $table->string('publication_status', 20)->default('published')->index();
        });
    }

    public function down(): void
    {
        Schema::table('professions', function (Blueprint $table) {
            $table->dropIndex(['publication_status']);
            $table->dropColumn(['summary', 'illustration_path', 'entry_level', 'career_start', 'career_end', 'industries', 'prospects', 'tools', 'publication_status']);
            $table->unsignedBigInteger('career_category_id')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->json('responsibilities')->nullable(false)->change();
        });
    }
};

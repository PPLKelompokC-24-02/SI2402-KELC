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
        Schema::create('career_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 250)->unique();
            $table->string('category', 100)->index();
            $table->longText('description');
            $table->string('organizer', 150);
            $table->dateTime('start_at')->index();
            $table->dateTime('end_at')->nullable();
            $table->string('location', 255);
            $table->string('poster_path')->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->string('registration_url', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_events');
    }
};


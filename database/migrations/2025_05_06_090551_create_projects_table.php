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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable(); // GitHub/Demo
            $table->json('tech_stack')->nullable(); // Array of tech used
            $table->string('images')->nullable(); // image in project
            $table->string('thumbnail')->nullable(); // thumbnail image
            $table->string('slug')->unique();
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

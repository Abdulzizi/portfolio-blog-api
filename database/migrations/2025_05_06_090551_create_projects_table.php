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
            $table->string('link')->nullable();
            $table->json('tech_stack')->nullable();
            $table->string('images')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('slug')->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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

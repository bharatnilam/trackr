<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('release_year');
            $table->string('pg_rating')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('director')->nullable();
            $table->string('genre')->nullable();
            $table->text('actors')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('poster_image_url')->nullable();
            $table->string('external_id')->nullable();
            $table->string('available_platforms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

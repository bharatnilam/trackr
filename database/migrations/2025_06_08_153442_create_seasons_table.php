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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->constrained('tv_shows')->onDelete('cascade');
            $table->integer('season_number');
            $table->string('title')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('poster_image_url')->nullable();
            $table->date('released_at')->nullable();
            $table->integer('number_of_episodes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};

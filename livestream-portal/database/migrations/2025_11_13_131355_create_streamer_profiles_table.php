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
        Schema::create('streamer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('headline')->nullable();
            $table->string('category')->index();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedInteger('experience_years')->default(0);
            $table->unsignedInteger('hours_streamed')->default(0);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->json('social_links')->nullable();
            $table->json('portfolio_media')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('primary_language')->nullable();
            $table->string('secondary_languages')->nullable();
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streamer_profiles');
    }
};

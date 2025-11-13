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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('livestream_platform')->nullable();
            $table->string('location')->nullable();
            $table->decimal('budget', 12, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->integer('duration_hours')->nullable();
            $table->date('start_date')->nullable();
            $table->date('application_deadline')->nullable();
            $table->boolean('is_remote')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};

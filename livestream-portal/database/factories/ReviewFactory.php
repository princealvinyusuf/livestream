<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(3, 5);
        $published = fake()->boolean(70);

        return [
            'job_id' => Job::factory(),
            'employer_id' => User::factory()->employer(),
            'streamer_id' => User::factory()->streamer(),
            'rating' => $rating,
            'comment' => fake()->paragraph(),
            'tags' => fake()->randomElements(['komunikatif', 'profesional', 'tepat waktu', 'ramah', 'sales tinggi'], 3),
            'is_public' => $published,
            'published_at' => $published ? fake()->dateTimeBetween('-2 months', 'now') : null,
        ];
    }
}

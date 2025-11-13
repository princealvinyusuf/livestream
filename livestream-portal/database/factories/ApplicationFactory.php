<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'reviewed', 'accepted', 'rejected']);
        $respondedAt = in_array($status, ['reviewed', 'accepted', 'rejected'], true)
            ? fake()->dateTimeBetween('-1 month', 'now')
            : null;

        return [
            'job_id' => Job::factory(),
            'streamer_id' => User::factory()->streamer(),
            'employer_id' => User::factory()->employer(),
            'status' => $status,
            'cover_letter' => fake()->paragraph(),
            'proposed_rate' => fake()->randomFloat(2, 200000, 2000000),
            'proposed_duration_hours' => fake()->numberBetween(2, 6),
            'is_invited' => fake()->boolean(20),
            'invited_at' => fake()->boolean(20) ? fake()->dateTimeBetween('-2 months', 'now') : null,
            'responded_at' => $respondedAt,
            'accepted_at' => $status === 'accepted' ? $respondedAt : null,
            'declined_at' => $status === 'rejected' ? $respondedAt : null,
            'attachments' => [
                [
                    'name' => 'Portfolio.pdf',
                    'url' => 'https://example.com/portfolio/'.fake()->uuid().'.pdf',
                ],
            ],
            'meta' => [
                'avg_viewers' => fake()->numberBetween(200, 2000),
                'conversion_rate' => fake()->randomFloat(2, 1, 7),
            ],
        ];
    }
}

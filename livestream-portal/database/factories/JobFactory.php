<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $categories = ['Fashion', 'Electronics', 'Beauty', 'Home & Living', 'Food & Beverage', 'Automotive', 'Gaming'];
        $status = fake()->randomElement(['draft', 'open', 'ongoing', 'completed']);
        $startDate = fake()->dateTimeBetween('+3 days', '+1 month');

        return [
            'employer_id' => User::factory()->employer(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(5),
            'category' => fake()->randomElement($categories),
            'livestream_platform' => fake()->randomElement(['TikTok', 'Shopee Live', 'Lazada Live', 'Tokopedia']),
            'location' => fake()->randomElement(['Remote', fake()->city()]),
            'budget' => fake()->randomFloat(2, 500000, 5000000),
            'currency' => 'IDR',
            'duration_hours' => fake()->numberBetween(2, 8),
            'start_date' => $startDate,
            'application_deadline' => fake()->dateTimeBetween('now', '+2 months'),
            'is_remote' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
            'status' => $status,
            'published_at' => $status !== 'draft' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'description' => fake()->paragraphs(3, true),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StreamerProfile>
 */
class StreamerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Fashion', 'Electronics', 'Beauty', 'Food & Beverage', 'Home & Living', 'Gaming'];
        $languages = ['Indonesia', 'English', 'Mandarin', 'Korean'];
        $category = fake()->randomElement($categories);
        $primaryLanguage = fake()->randomElement($languages);

        return [
            'headline' => fake()->sentence(6),
            'category' => $category,
            'city' => fake()->city(),
            'country' => fake()->country(),
            'bio' => fake()->paragraph(),
            'experience_years' => fake()->numberBetween(1, 6),
            'hours_streamed' => fake()->numberBetween(100, 2000),
            'hourly_rate' => fake()->randomFloat(2, 10, 100),
            'social_links' => [
                'instagram' => 'https://instagram.com/'.fake()->userName(),
                'tiktok' => 'https://tiktok.com/@'.fake()->userName(),
            ],
            'portfolio_media' => [
                [
                    'type' => 'image',
                    'url' => 'https://picsum.photos/seed/'.fake()->uuid().'/640/360',
                    'title' => $category.' Showcase',
                ],
            ],
            'rating' => fake()->randomFloat(2, 3.5, 5),
            'review_count' => fake()->numberBetween(0, 250),
            'is_available' => fake()->boolean(),
            'primary_language' => $primaryLanguage,
            'secondary_languages' => fake()->randomElement(array_diff($languages, [$primaryLanguage])),
            'available_from' => fake()->time('H:00'),
            'available_to' => fake()->time('H:00'),
            'verified_at' => fake()->boolean(40) ? fake()->dateTimeBetween('-3 months') : null,
        ];
    }
}

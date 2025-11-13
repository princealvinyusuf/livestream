<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployerProfile>
 */
class EmployerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Fashion', 'Electronics', 'Beauty', 'Food & Beverage', 'Lifestyle', 'Sports'];

        return [
            'company_name' => fake()->company(),
            'brand_name' => fake()->companySuffix(),
            'website' => fake()->optional()->url(),
            'contact_email' => fake()->companyEmail(),
            'contact_phone' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'about' => 'Kami adalah brand '.$categories[array_rand($categories)].' yang mencari live streamer profesional.',
            'team_size' => fake()->numberBetween(5, 200),
            'social_links' => [
                'instagram' => 'https://instagram.com/'.fake()->userName(),
                'youtube' => 'https://youtube.com/@'.fake()->domainWord(),
            ],
            'preferred_categories' => fake()->randomElements($categories, fake()->numberBetween(1, 3)),
            'is_verified' => fake()->boolean(60),
            'verified_at' => fake()->boolean(40) ? fake()->dateTimeBetween('-6 months') : null,
        ];
    }
}

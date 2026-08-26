<?php

namespace Database\Factories;

use App\Models\QuoteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteRequest>
 */
class QuoteRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_type' => fake()->randomElement(['maatkast', 'dressing', 'keuken', 'tv-meubel']),
            'dimensions_are_approximate' => true,
            'width_mm' => fake()->numberBetween(1200, 4500),
            'height_mm' => fake()->numberBetween(1800, 2800),
            'depth_mm' => fake()->numberBetween(350, 700),
            'layout_columns' => fake()->numberBetween(2, 5),
            'finish' => fake()->randomElement(['licht-eiken', 'naturel-eiken', 'olijfbrons', 'ivoor']),
            'features' => ['legplanken', 'laden'],
            'style' => fake()->randomElement(['licht-hout', 'warm-neutraal', 'donker-hout']),
            'budget' => 'gebalanceerd',
            'timing' => 'binnen-6-maanden',
            'notes' => fake()->sentence(),
            'attachments' => null,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'postal_code' => (string) fake()->numberBetween(1000, 9999),
            'consent_at' => now(),
            'status' => 'new',
        ];
    }
}

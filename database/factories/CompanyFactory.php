<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => null,
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'instagram_url' => null,
            'instagram_followers_at_registration' => null,
            'instagram_followers_recorded_at' => null,
            'logo_path' => null,
            'is_published' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => true,
        ]);
    }
}

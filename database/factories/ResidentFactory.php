<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => Household::factory(),
            'name' => $this->faker->name(),
            'id_number' => $this->faker->unique()->numerify('32##############'),
            'birth_date' => $this->faker->dateTimeBetween('-70 years', '-1 year')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'relationship' => $this->faker->randomElement(['head', 'spouse', 'child', 'parent', 'other']),
            'occupation' => $this->faker->optional(0.8)->jobTitle(),
            'phone' => $this->faker->optional(0.6)->phoneNumber(),
            'email' => $this->faker->optional(0.4)->email(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'moved', 'deceased']),
            'moved_in_date' => $this->faker->optional(0.7)->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'moved_out_date' => null,
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }

    /**
     * Indicate that the resident is the household head.
     */
    public function head(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'head',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the resident is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
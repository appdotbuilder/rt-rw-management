<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_number' => $this->faker->unique()->numerify('##'),
            'rt_number' => $this->faker->randomElement(['01', '02', '03', '04', '05']),
            'rw_number' => $this->faker->randomElement(['01', '02', '03']),
            'head_name' => $this->faker->name(),
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'email' => $this->faker->optional(0.5)->email(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'moved']),
            'resident_count' => $this->faker->numberBetween(1, 8),
            'monthly_contribution' => $this->faker->randomFloat(2, 25000, 150000),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the household is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the household is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
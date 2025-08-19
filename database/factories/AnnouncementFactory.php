<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['general', 'urgent', 'event', 'meeting', 'maintenance', 'security'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        
        return [
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement($types),
            'priority' => $this->faker->randomElement($priorities),
            'created_by' => User::factory(),
            'publish_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 month', '+1 week'),
            'expires_at' => $this->faker->optional(0.6)->dateTimeBetween('+1 week', '+3 months'),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'send_notification' => $this->faker->boolean(30),
            'target_audience' => $this->faker->optional(0.3)->randomElements(['RT01', 'RT02', 'RT03'], 2),
            'attachments' => null,
            'view_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the announcement is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'publish_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the announcement is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'type' => 'urgent',
            'status' => 'published',
        ]);
    }
}
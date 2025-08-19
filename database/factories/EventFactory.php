<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['meeting', 'social', 'religious', 'security', 'gotong_royong', 'celebration', 'other'];
        $statuses = ['planned', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        
        $startDate = $this->faker->dateTimeBetween('-1 month', '+3 months');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 8) . ' hours');
        
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(2, true),
            'type' => $this->faker->randomElement($types),
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'location' => $this->faker->randomElement([
                'Balai RT 01',
                'Masjid Al-Ikhlas',
                'Lapangan Komplek',
                'Rumah Pak RT',
                'Aula Kelurahan',
                'Taman Bermain'
            ]),
            'organizer_id' => User::factory(),
            'max_participants' => $this->faker->optional(0.6)->numberBetween(20, 100),
            'participation_fee' => $this->faker->randomFloat(2, 0, 50000),
            'status' => $this->faker->randomElement($statuses),
            'agenda' => $this->faker->optional(0.7)->text(300),
            'required_items' => $this->faker->optional(0.5)->randomElements([
                'Kursi plastik',
                'Peralatan makan',
                'Sapu dan pel',
                'Gotong royong',
                'Snack'
            ], 2),
            'target_participants' => $this->faker->optional(0.4)->randomElements(['RT01', 'RT02', 'RT03'], 2),
            'requires_registration' => $this->faker->boolean(40),
            'registration_deadline' => $this->faker->optional(0.5)->dateTimeBetween('now', $startDate),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        $startDate = $this->faker->dateTimeBetween('+1 day', '+1 month');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 4) . ' hours');
        
        return $this->state(fn (array $attributes) => [
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the event is a meeting.
     */
    public function meeting(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'meeting',
            'requires_registration' => false,
            'participation_fee' => 0,
        ]);
    }
}
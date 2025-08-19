<?php

namespace Database\Factories;

use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdministrativeLetter>
 */
class AdministrativeLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['permit', 'certificate', 'recommendation', 'notification', 'other'];
        $statuses = ['draft', 'pending', 'approved', 'rejected', 'completed'];
        
        return [
            'letter_number' => $this->generateLetterNumber(),
            'type' => $this->faker->randomElement($types),
            'subject' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(3, true),
            'requester_id' => Resident::factory(),
            'household_id' => Household::factory(),
            'approved_by' => null,
            'status' => $this->faker->randomElement($statuses),
            'request_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'approved_date' => null,
            'completed_date' => null,
            'approval_notes' => null,
            'rejection_reason' => null,
            'required_documents' => $this->faker->optional(0.6)->randomElements([
                'KTP',
                'KK',
                'Surat Keterangan RT',
                'Foto 3x4',
                'Materai 10000'
            ], 3),
            'attachments' => null,
        ];
    }

    /**
     * Generate a letter number.
     */
    protected function generateLetterNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "SURAT/{$month}/{$year}";
        $number = $this->faker->unique()->numberBetween(1, 9999);
        
        return "{$prefix}/" . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Indicate that the letter is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the letter is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_date' => $this->faker->dateTimeBetween($attributes['request_date'] ?? '-1 month', 'now'),
            'approval_notes' => $this->faker->optional(0.7)->sentence(),
        ]);
    }
}
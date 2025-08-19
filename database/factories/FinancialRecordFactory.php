<?php

namespace Database\Factories;

use App\Models\Household;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialRecord>
 */
class FinancialRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['income', 'expense', 'contribution', 'fine', 'donation'];
        $categories = ['monthly_fee', 'security', 'cleaning', 'utilities', 'events', 'maintenance', 'emergency', 'other'];
        $paymentMethods = ['cash', 'bank_transfer', 'digital_wallet', 'other'];
        $statuses = ['pending', 'completed', 'cancelled', 'refunded'];
        
        $type = $this->faker->randomElement($types);
        $amount = match($type) {
            'contribution', 'monthly_fee' => $this->faker->randomFloat(2, 25000, 150000),
            'fine' => $this->faker->randomFloat(2, 10000, 100000),
            'donation' => $this->faker->randomFloat(2, 50000, 500000),
            'expense' => $this->faker->randomFloat(2, 100000, 2000000),
            default => $this->faker->randomFloat(2, 50000, 1000000),
        };
        
        return [
            'record_number' => $this->generateRecordNumber(),
            'type' => $type,
            'category' => $this->faker->randomElement($categories),
            'description' => $this->getDescriptionByType($type),
            'amount' => $amount,
            'household_id' => in_array($type, ['contribution', 'fine']) ? Household::factory() : null,
            'recorded_by' => User::factory(),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_reference' => $this->faker->optional(0.7)->numerify('TXN###########'),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional(0.3)->sentence(),
            'attachments' => null,
        ];
    }

    /**
     * Generate a record number.
     */
    protected function generateRecordNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $number = $this->faker->unique()->numberBetween(1, 9999);
        
        return "KU/{$month}/{$year}/" . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get description based on transaction type.
     */
    protected function getDescriptionByType(string $type): string
    {
        return match($type) {
            'contribution' => 'Iuran bulanan RT/RW',
            'expense' => $this->faker->randomElement([
                'Pembelian alat kebersihan',
                'Biaya listrik pos ronda',
                'Perbaikan jalan komplek',
                'Biaya keamanan',
                'Pembelian tanaman hias'
            ]),
            'income' => $this->faker->randomElement([
                'Sewa tempat parkir',
                'Hasil kerja bakti',
                'Penjualan sampah',
                'Bunga tabungan RT'
            ]),
            'fine' => 'Denda keterlambatan iuran',
            'donation' => $this->faker->randomElement([
                'Sumbangan warga baru',
                'Donasi untuk kegiatan 17 Agustus',
                'Bantuan korban bencana'
            ]),
            default => $this->faker->sentence(6),
        };
    }

    /**
     * Indicate that the record is income.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
        ]);
    }

    /**
     * Indicate that the record is expense.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
        ]);
    }

    /**
     * Indicate that the record is a monthly contribution.
     */
    public function monthlyContribution(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'contribution',
            'category' => 'monthly_fee',
            'description' => 'Iuran bulanan RT/RW',
            'status' => 'completed',
        ]);
    }
}
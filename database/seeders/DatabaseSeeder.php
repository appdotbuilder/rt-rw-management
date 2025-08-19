<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Household;
use App\Models\Resident;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\FinancialRecord;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create system administrator
        $admin = User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@rtrw.com',
            'role' => 'system_admin',
            'is_active' => true,
        ]);

        // Create RT/RW heads
        $rtHead1 = User::factory()->create([
            'name' => 'Bapak RT 01',
            'email' => 'rt01@rtrw.com',
            'role' => 'rt_head',
            'rt_number' => '01',
            'rw_number' => '01',
            'is_active' => true,
        ]);

        $rtHead2 = User::factory()->create([
            'name' => 'Bapak RT 02',
            'email' => 'rt02@rtrw.com',
            'role' => 'rt_head',
            'rt_number' => '02',
            'rw_number' => '01',
            'is_active' => true,
        ]);

        $rwHead = User::factory()->create([
            'name' => 'Bapak RW 01',
            'email' => 'rw01@rtrw.com',
            'role' => 'rw_head',
            'rt_number' => null,
            'rw_number' => '01',
            'is_active' => true,
        ]);

        // Create management staff
        $management = User::factory()->create([
            'name' => 'Tim Management',
            'email' => 'management@rtrw.com',
            'role' => 'management',
            'is_active' => true,
        ]);

        // Create test user (resident)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'resident',
            'is_active' => true,
        ]);

        // Create households with residents
        $households = Household::factory(25)
            ->active()
            ->create()
            ->each(function ($household) {
                // Create household head
                Resident::factory()
                    ->head()
                    ->create([
                        'household_id' => $household->id,
                        'name' => $household->head_name,
                    ]);

                // Create additional family members
                $additionalMembers = random_int(0, $household->resident_count - 1);
                if ($additionalMembers > 0) {
                    Resident::factory($additionalMembers)
                        ->active()
                        ->create([
                            'household_id' => $household->id,
                            'relationship' => fake()->randomElement(['spouse', 'child', 'parent', 'other']),
                        ]);
                }
            });

        // Create announcements
        Announcement::factory(15)
            ->published()
            ->create([
                'created_by' => fake()->randomElement([$admin->id, $rtHead1->id, $rtHead2->id, $rwHead->id]),
            ]);

        // Create some urgent announcements
        Announcement::factory(3)
            ->urgent()
            ->create([
                'created_by' => fake()->randomElement([$rtHead1->id, $rtHead2->id, $rwHead->id]),
            ]);

        // Create events
        Event::factory(10)
            ->upcoming()
            ->create([
                'organizer_id' => fake()->randomElement([$rtHead1->id, $rtHead2->id, $rwHead->id, $management->id]),
            ]);

        Event::factory(5)
            ->meeting()
            ->create([
                'organizer_id' => fake()->randomElement([$rtHead1->id, $rtHead2->id, $rwHead->id]),
            ]);

        // Create financial records
        // Monthly contributions from households
        $households->random(20)->each(function ($household) use ($admin, $management) {
            FinancialRecord::factory()
                ->monthlyContribution()
                ->create([
                    'household_id' => $household->id,
                    'amount' => $household->monthly_contribution,
                    'recorded_by' => fake()->randomElement([$admin->id, $management->id]),
                    'transaction_date' => fake()->dateTimeBetween('-3 months', 'now'),
                ]);
        });

        // General income and expenses
        FinancialRecord::factory(20)
            ->income()
            ->create([
                'recorded_by' => fake()->randomElement([$admin->id, $management->id]),
            ]);

        FinancialRecord::factory(15)
            ->expense()
            ->create([
                'recorded_by' => fake()->randomElement([$admin->id, $management->id]),
            ]);
    }
}

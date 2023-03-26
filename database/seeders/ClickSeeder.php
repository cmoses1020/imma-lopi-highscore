<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')
            ->merge([null]);

        $clicks = 0;
        while ($clicks < 50000) {
            $id = $userIds->random();

            $createdClicks = Click::factory()
                ->count(rand(1, 2))
                ->create(['user_id' => $id]);
            $this->command->info("Created {$createdClicks->count()} clicks for user ".($id ?? 'anonymous'));

            $clicks += $createdClicks->count();
        }
    }
}

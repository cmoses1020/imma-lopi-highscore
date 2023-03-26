<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Click;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(50)
            ->create();

        $clicks = 0;

        while ($clicks < 50000) {
            $user = $users->random();

            $createdClicks = Click::factory()
                ->count(rand(1, 50))
                ->create(['user_id' => $user->id]);
            $this->command->info("Created {$createdClicks->count()} clicks for user {$user->id} ({$user->name})");

            $clicks += $createdClicks->count();
        }

        Click::factory()
            ->count(rand(1, 400))
            ->create(['user_id' => null]);
    }
}

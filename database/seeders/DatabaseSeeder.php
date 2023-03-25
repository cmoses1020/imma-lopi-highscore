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
        User::factory()
            ->count(100)
            ->afterCreating(fn ($user) => Click::factory()->count(rand(400, 700))->create(['user_id' => $user->id]))
            ->create();

        Click::factory()
            ->count(rand(1, 400))
            ->create(['user_id' => null]);
    }
}

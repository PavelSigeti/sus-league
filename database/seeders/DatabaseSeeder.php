<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(24)->create();

        $this->call(UserSeeder::class);
        $this->call(TournamentSeeder::class);
        $this->call(StageSeeder::class);
        $this->call(StageUserSeeder::class);
        $this->call(UniversitySeeder::class);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

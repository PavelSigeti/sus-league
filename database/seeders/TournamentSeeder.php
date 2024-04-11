<?php

namespace Database\Seeders;

use App\Models\Tournament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tournament::query()->insert([
            'title' => 'Первый турик',
            'yacht' => 'J70',
        ]);
        Tournament::query()->insert([
            'title' => 'Второй турик',
            'yacht' => 'SB20',
        ]);
    }
}

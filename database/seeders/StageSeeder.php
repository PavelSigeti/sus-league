<?php

namespace Database\Seeders;

use App\Models\Stage;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stage::query()->insert([
            'tournament_id' => 1,
            'register_start' => Carbon::now(),
            'register_end' => Carbon::tomorrow(),
            'race_start' => Carbon::tomorrow(),
            'title' => '1 этап J70',
        ]);

        Stage::query()->insert([
            'tournament_id' => 2,
            'register_start' => Carbon::now(),
            'register_end' => Carbon::tomorrow(),
            'race_start' => Carbon::tomorrow(),
            'title' => '1 этап J70',
        ]);
    }
}

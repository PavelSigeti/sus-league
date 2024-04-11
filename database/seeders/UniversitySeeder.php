<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        University::query()->create([
            'name' => 'Университет ИТМО',
        ]);
        University::query()->create([
            'name' => 'СПбГУ',
        ]);
        University::query()->create([
            'name' => 'ТНУ',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::query()->create([
            'title' => 'Регламент',
            'slug' => 'reglament',
            'text' => 'Регламент'
        ]);

        Page::query()->create([
            'title' => 'Инструкция',
            'slug' => 'info',
            'text' => 'Инструкция'
        ]);

        Page::query()->create([
            'title' => 'Политика конфиденциальности',
            'slug' => 'privacy',
            'text' => 'Политика конфиденциальности'
        ]);
    }
}

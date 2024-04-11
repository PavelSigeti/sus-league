<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([
            'name' => 'Кирилл',
            'surname' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123123qq'),
//            'team_id' => 1,
            'university_id' => 1,
            'date' => '2000-01-01',
            'role' => 'admin',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

    }
}

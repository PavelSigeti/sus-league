<?php

namespace Database\Seeders;

use App\Models\StageUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class StageUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user)
        {
            StageUser::query()->insert([
                'stage_id' => 1,
                'user_id' => $user->id,
                'team_id' => $user->team_id,
            ]);
        }
        $k = 0;
        foreach ($users as $user)
        {
            $k++;
            if($k < 5) {
                StageUser::query()->insert([
                    'stage_id' => 2,
                    'user_id' => $user->id,
                    'team_id' => $user->team_id,
                ]);
                continue;
            }
            break;
        }
    }
}

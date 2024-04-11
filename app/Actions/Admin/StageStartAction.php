<?php

namespace App\Actions\Admin;

use App\Models\Race;

class StageStartAction
{
    public function __invoke($stage)
    {
        $playersAmount = $stage->users->count();

        if($playersAmount === 0) {
            return ['error' => 'Нет пользователей'];
        }

        if ($playersAmount > 18) {
            $groupAmount = (int)ceil($playersAmount / 18);
            $users = $stage->users->shuffle()->keyBy('id')->toArray();
            $userGroups = array_chunk($users, ceil($playersAmount/ $groupAmount) );

            for ($i=1; $i<=$groupAmount; $i++) {
                $race[] = Race::query()->create([
                    'status' => 'group',
                    'stage_id' => $stage->id,
                    'group_id' => $i,
                ]);
            }
            foreach ($userGroups as $key => $userGroup) {
                foreach ($userGroup as $user) {
                    $race[$key]->users()->attach($user['id']);
                }
            }

            $stage->update([
                'status' => 'group',
            ]);

            return ['status' => 'group'];

        } else {
            if(Race::query()->where('stage_id', $stage->id)->count() === 0) {
                $race = Race::query()->create([
                    'stage_id' => $stage->id,
                    'status' => 'default',
                ]);
                $users = $stage->users->keyBy('id')->toArray();
                foreach ($users as $user) {
                    $race->users()->attach($user['id']);
                }

                $stage->update([
                    'status' => 'default',
                ]);
                return ['status' => 'default'];
            }
            return ['error' => 'Ошибка, гонка уже созданая'];
        }
    }
}

<?php

namespace App\Actions\Admin;

use App\Models\Race;

class CreateRaceAction
{
    public function __invoke($request, $users) {
        $race = Race::query()->create([
            'stage_id' => $request->stage_id,
            'group_id' => $request->group_id,
            'status' => $request->status,
        ]);

        foreach ($users as $user) {
            $race->users()->attach($user['user_id']);
        }

        return $race;
    }
}

<?php

namespace App\Actions\Admin;

use App\Models\Race;

class CreateRaceAction
{
    public function __invoke($request, $teams) {
        $race = Race::query()->create([
            'stage_id' => $request->stage_id,
            'group_id' => $request->group_id,
            'status' => $request->status,
        ]);

        foreach ($teams as $team) {
            $race->teams()->attach($team['team_id']);
        }

        return $race;
    }
}

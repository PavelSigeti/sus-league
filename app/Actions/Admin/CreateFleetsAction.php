<?php

namespace App\Actions\Admin;

use App\Models\Race;

class CreateFleetsAction
{
    public function __invoke($groups, $stage)
    {
        $userAmount = $stage->users->count();

        $fleetsAmount = count($groups);

        $userFleets = [];
        $takeFromGroup = (int)floor(18/$fleetsAmount);

        foreach ($groups as $group) {
            $parts = $group->chunk($takeFromGroup)->toArray();
            foreach ($parts as $key => $part)
            {
                if(!isset($userFleets[$key])) {
                    $userFleets[$key] = $part;
                } else {
                    $userFleets[$key] = array_merge($userFleets[$key], $part);
                }

            }
        }

        $status = 'fleet';
        $stageId = $stage->id;
        $currentGroupId = 1;

        foreach ($userFleets as $fleetPart) {
            $race = Race::query()->create([
                'stage_id' => $stageId,
                'group_id' => $currentGroupId,
                'status' => $status,
            ]);
            foreach ($fleetPart as $userId) {
                $race->users()->attach($userId);
            }
            $currentGroupId++;
        }

        $stage->update([
            'status' => 'fleet',
        ]);

        return $stage;
    }
}

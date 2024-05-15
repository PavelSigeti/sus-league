<?php

namespace App\Http\Repositories;

use App\Http\Repositories\CoreRepository;
use App\Models\Team as Model;
class TeamRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getTeamsAmount($id)
    {
        return $this->startConditions()->where('user_id', $id)->count();
    }

    public function getById($id) {
        return $this->startConditions()->find($id);
    }

    public function getGroupData($stageId, $groupId, $status)
    {
        $result = $this->startConditions()
            ->join('race_team', 'teams.id', '=', 'race_team.team_id')
            ->join('races', 'race_team.race_id', '=', 'races.id')
//            ->join('stage_user', function($join) {
//                $join->on('users.id', '=', 'stage_user.user_id');
//                $join->on('races.stage_id', '=', 'stage_user.stage_id');
//            })
            ->select('race_team.race_id', 'teams.id', 'race_team.place',
                'races.group_id', 'races.stage_id',
                'races.status', 'teams.name',
            )
            ->where('races.stage_id', $stageId)
            ->where('races.group_id', $groupId)
            ->where('races.status', $status)
            ->orderBy('race_team.race_id')
            ->get()
            ->groupBy(['id']);

        return $result;
    }
}

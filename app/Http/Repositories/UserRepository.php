<?php

namespace App\Http\Repositories;

use App\Models\User as Model;

class UserRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getStageData($id)
    {
        $result = $this->startConditions()
            ->join('race_user', 'users.id', '=', 'race_user.user_id')
            ->join('stage_user', 'users.id', '=', 'stage_user.user_id')
            ->join('races', 'race_user.race_id', '=', 'races.id')
            ->select('race_user.race_id', 'users.id', 'race_user.place',
                'races.group_id', 'users.name', 'races.stage_id',
                'races.status',
            )
            ->where('races.stage_id', $id)
            ->orderBy('race_user.race_id')
            ->get()
            ->groupBy(['status', 'group_id', 'id']);

        return $result;
    }

    public function getGroupData($stageId, $groupId, $status)
    {
        $result = $this->startConditions()
            ->join('race_user', 'users.id', '=', 'race_user.user_id')
            ->join('races', 'race_user.race_id', '=', 'races.id')
            ->join('stage_user', function($join) {
                $join->on('users.id', '=', 'stage_user.user_id');
                $join->on('races.stage_id', '=', 'stage_user.stage_id');
            })
            ->select('race_user.race_id', 'users.id', 'race_user.place',
                'races.group_id', 'races.stage_id',
                'races.status', 'users.name', 'users.surname', 'stage_user.nickname'
            )
            ->where('races.stage_id', $stageId)
            ->where('races.group_id', $groupId)
            ->where('races.status', $status)
            ->orderBy('race_user.race_id')
            ->get()
            ->groupBy(['id']);

        return $result;
    }

    public function getSettings($id)
    {
        $result = $this->startConditions()
            ->select(['email', 'university_id', 'universities.name as university'])
            ->leftJoin('universities', 'users.university_id', '=', 'universities.id')
            ->find($id);

        return $result;
    }
}

<?php

namespace App\Http\Repositories;

use App\Models\Race as Model;

class RaceRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getStageRaces($stageId, $status, $groupId)
    {
        $columns = [
            'id as race_id', 'group_id', 'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->where('stage_id', $stageId)
            ->where('status', $status)
            ->where('group_id', $groupId)
            ->get();

        return $result;
    }

    public function getGroupIdsById($stageId)
    {

        $result = $this->startConditions()
            ->where('stage_id', $stageId)
            ->get()
            ->groupBy('status')
            ->map(function ($item) {
                return $item->unique('group_id')->pluck('group_id');
            });

        return $result;
    }

    public function getRaceUsers($id)
    {
        $columns = [
            'user_id', 'nickname',
        ];

        $result = $this->startConditions()
            ->join('race_user', 'races.id', '=', 'race_user.race_id')
            ->join('users', 'race_user.user_id', '=', 'users.id')
            ->select($columns)
            ->where('race_id',$id)
            ->orderBy('user_id')
            ->get();

        return $result;
    }

    public function getRacePlace($id)
    {
        $columns = [
            'user_id', 'place',
        ];

        $result = $this->startConditions()
            ->join('race_user', 'races.id', '=', 'race_user.race_id')
            ->join('users', 'race_user.user_id', '=', 'users.id')
            ->select($columns)
            ->where('race_id',$id)
            ->orderBy('user_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['user_id'] => $item['place']];
            });

        return $result;
    }

    public function getById($id)
    {
        $result = $this->startConditions()
            ->find($id);

        return $result;
    }

    public function getRacesAmount($stageId, $groupId, $status)
    {
        $result = $this->startConditions()
            ->where('stage_id', $stageId)
            ->where('group_id', $groupId)
            ->where('status', $status)
            ->count();

        return $result;
    }

    public function getStageStatusGroup($id)
    {
        $result = $this->startConditions()
            ->where('stage_id', $id)
            ->get()
            ->groupBy('status')
            ->map(function ($item) {
                return $item->unique('group_id')->pluck('group_id');
            });
        if($result->has('group') && $result->has('fleet')) {
            return $result->forget('group');
        }
        return $result;
    }

    public function getFirstRaceInGroup($stageId, $groupId, $status)
    {
        $result = $this->startConditions()
            ->where('stage_id', $stageId)
            ->where('group_id', $groupId)
            ->where('status', $status)
            ->min('id');

        return $result;
    }
}

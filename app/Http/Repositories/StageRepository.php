<?php

namespace App\Http\Repositories;

use App\Models\Stage as Model;

class StageRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getByTournamentId($id)
    {
        $columns = [
            'race_start', 'id', 'title',
        ];
        $result = $this->startConditions()
            ->select($columns)
            ->where('tournament_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return $result;
    }

    public function getById($id)
    {
        $result = $this->startConditions()
            ->find($id);

        return $result;
    }

    public function getByIdWithUsers($id)
    {
        $columns = [
            'status','title', 'register_start',
            'register_end', 'race_start', 'description',
            'id',
        ];
        $result = $this->startConditions()
            ->select($columns)
            ->with('users:name,surname,nickname,id')
            ->find($id);

        return $result;
    }

    public function getByIdWithUsersAdmin($id)
    {
        $result = $this->startConditions()
            ->with('users:name,surname,nickname,id')
            ->find($id);

        return $result;
    }

    public function getByIdWithRaces($id)
    {
        $result = $this->startConditions()
            ->with('races')
            ->find($id);

        return $result;
    }

    public function getStageDrops($id, $status) {
        $dropField = [
            'default' => 'race_amount_drop',
            'group' => 'race_amount_group_drop',
            'fleet' => 'race_amount_fleet_drop',
        ];
        $result = $this->startConditions()
            ->find($id)[$dropField[$status]];

        return $result;
    }

    public function getActual($id)
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'excerpt', 'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->withExists(['users' => function($query) use ($id){
                $query->where('user_id', $id);
            }])
            ->where('status', '!=', 'finished')
            ->orderBy('race_start')
            ->get();

        return $result;
    }
    public function getActualDashboard($id)
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->withExists(['users' => function($query) use ($id){
                $query->where('user_id', $id);
            }])
            ->where('status', '!=', 'finished')
            ->orderBy('race_start')
            ->limit(2)
            ->get();

        return $result;
    }

    public function getEnded($id)
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'excerpt', 'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->withExists(['users' => function($query) use ($id){
                $query->where('user_id', $id);
            }])
            ->where('status', 'finished')
            ->orderBy('race_start')
            ->get();

        return $result;
    }

    public function getAllEnded()
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'excerpt', 'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->where('status', 'finished')
            ->orderBy('race_start')
            ->get();

        return $result;
    }

    public function getAllActual()
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'excerpt', 'status',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->where('status', '!=', 'finished')
            ->orderBy('race_start')
            ->get();

        return $result;
    }


    public function getUserStages($id)
    {
        $columns = [
            'stages.id', 'register_start', 'register_end',
            'race_start', 'stages.title', 'tournaments.title as tournament',
            'excerpt', 'status', 'stage_user.stage_id',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->join('tournaments', 'stages.tournament_id', '=', 'tournaments.id')
            ->join('stage_user', 'stages.id', 'stage_user.stage_id')
            ->where('user_id', $id)
            ->orderBy('race_start', 'desc')
            ->get()
            ->map(function ($item) {
                $item['users_exists'] = true;
                return $item;
            });

        return $result;
    }
}

<?php

namespace App\Http\Repositories;


use App\Models\StageResult as Model;
use Illuminate\Support\Facades\DB;
use App\Models\StageTeam;

class StageResultRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getUsersRating($year = null)
    {
        $columns = [
            'name', 'surname', 'user_id', 'patronymic',
            DB::raw('SUM(result) as total')
        ];
    
        $query = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->join('stages', 'stage_results.stage_id', '=', 'stages.id')
            ->select($columns)
            ->groupBy('user_id')
            ->orderBy('total', 'DESC');
    
        if ($year) {
            $query->whereYear('stages.register_start', $year);
        }
    
        return $query->paginate(50);
    }

    public function getUniversityRating($year = null)
    {
        $columns = [
            'universities.name',
            DB::raw('SUM(result) as total')
        ];

        $query = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->join('universities', 'users.university_id', 'universities.id')
            ->join('stages', 'stage_results.stage_id', '=', 'stages.id')
            ->select($columns)
            ->groupBy('university_id')
            ->orderBy('total', 'DESC');

        if ($year) {
            $query->whereYear('stages.register_start', $year);
        }

        return $query->paginate(10);
    }

    public function getTeamRating($year = null)
    {
        $columns = [
            'teams.name',
            DB::raw('SUM(result) as total')
        ];
    
        $query = StageTeam::query()
            ->join('teams', 'stage_team.team_id', '=', 'teams.id')
            ->join('stages', 'stage_team.stage_id', '=', 'stages.id')
            ->select($columns)
            ->groupBy('team_id')
            ->orderBy('total', 'DESC');
    
        if ($year) {
            $query->whereYear('stages.register_start', $year);
        }
    
        return $query->paginate(10);
    }

    public function getUserPersonalRatingData($year)
    {    
    return DB::table('stage_results')
        ->join('users', 'stage_results.user_id', '=', 'users.id')
        ->whereYear('stage_results.created_at', $year)
        ->select([
            'users.id',
            'users.name',
            'users.surname', 
            DB::raw('SUM(result) as total')
        ])
        ->groupBy('users.id')
        ->orderBy('total', 'DESC')
        ->get();
    }

    public function getUserTeamData($userId, $year)
    {
        $result = $this->startConditions()
            ->join('stage_user', function($join) {
                $join->on('stage_results.stage_id', '=', 'stage_user.stage_id');
                $join->on('stage_results.user_id', '=', 'stage_user.user_id');
            })
            ->join('teams', 'stage_user.team_id', '=', 'teams.id')
            ->where('stage_results.user_id', $userId)
            ->whereYear('stage_results.created_at', $year)
            ->select('teams.id', 'teams.name', DB::raw('COUNT(*) as participation'))
            ->groupBy('teams.id', 'teams.name')
            ->orderByDesc('participation')
            ->first();
        return $result;
    }

    public function getTeamRatings($year)
    {
        $result = $this->startConditions()
            ->join('stage_user', function($join) {
                $join->on('stage_results.stage_id', '=', 'stage_user.stage_id');
                $join->on('stage_results.user_id', '=', 'stage_user.user_id');
            })
            ->join('teams', 'stage_user.team_id', '=', 'teams.id')
            ->whereYear('stage_results.created_at', $year)
            ->select([
                'teams.id',
                'teams.name',
                DB::raw('SUM(result) as total')
            ])
            ->groupBy('teams.id')
            ->orderBy('total', 'DESC')
            ->get();
        return $result;
    }   

 public function getUserUniversityId($userId)
{
    return DB::table('users')
        ->where('id', $userId)
        ->value('university_id');
}

public function getUniversityRatings($year)
{
    $result = $this->startConditions()
        ->join('users', 'stage_results.user_id', '=', 'users.id')
        ->join('universities', 'users.university_id', '=', 'universities.id')
        ->whereYear('stage_results.created_at', $year)
        ->select([
            'universities.id',
            'universities.name',
            DB::raw('SUM(result) as total')
        ])
        ->groupBy('universities.id')
        ->orderBy('total', 'DESC')
        ->get();
    return $result;
}
}

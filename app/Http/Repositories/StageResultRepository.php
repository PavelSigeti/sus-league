<?php

namespace App\Http\Repositories;


use App\Models\StageResult as Model;
use Illuminate\Support\Facades\DB;

class StageResultRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getUsersRating()
    {
        $columns = [
            'name', 'surname', 'user_id', 'patronymic',
            DB::raw('SUM(result) as total')
        ];


        $result = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->select($columns)
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->paginate(50);


        return $result;
    }

    public function getUniversityRating()
    {
        $columns = [
            'universities.name',
            DB::raw('SUM(result) as total')
        ];


        $result = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->join('universities', 'users.university_id', 'universities.id')
            ->select($columns)
            ->groupBy('university_id')
            ->orderBy('total', 'DESC')
            ->paginate(10);


        return $result;
    }

    public function getTeamRating()
    {
        $columns = [
            'teams.name',
            DB::raw('SUM(result) as total')
        ];


        $result = $this->startConditions()
            ->join('stage_user', function($join) {
                $join->on('stage_results.stage_id', '=', 'stage_user.stage_id');
                $join->on('stage_results.user_id', '=', 'stage_user.user_id');
            })
            ->join('teams', 'stage_user.team_id', '=', 'teams.id')
            ->select($columns)
            ->groupBy('team_id')
            ->orderBy('total', 'DESC')
            ->paginate(10);

        return $result;
    }
    public function getUserPersonalRating($id)
    {
        $currentYear = 2024; //now()->year; 
    
        $columns = [
            'users.id',
            'users.name',
            'users.surname',
            DB::raw('SUM(result) as total')
        ];
    
        $ratings = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->whereYear('stage_results.created_at', $currentYear)
            ->select($columns)
            ->groupBy('users.id')
            ->orderBy('total', 'DESC')
            ->get();
    
        $rankedUsers = $ratings->map(function ($item, $index) use ($id) {
            return [
                'name' => ($index + 1) . '. ' . $item->name . ' ' . $item->surname,
                'score' => $item->total,
                'user_id' => $item->id,
                'is_user' => $item->id == $id
            ];
        });
    
        $userIndex = $rankedUsers->search(fn($item) => $item['user_id'] == $id);
    
        if ($userIndex !== false) {
            if ($userIndex < 3) {
                $result = $rankedUsers->slice(0, 4)->values();
            } else {
                $result = $rankedUsers->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedUsers[$userIndex]);
            }
        } else {
            $result = $rankedUsers->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }


    public function getUserTeamRating($id)
    {
        $currentYear = 2024; //now()->year;
    
        $team = $this->startConditions()
            ->join('stage_user', function($join) {
                $join->on('stage_results.stage_id', '=', 'stage_user.stage_id');
                $join->on('stage_results.user_id', '=', 'stage_user.user_id');
            })
            ->join('teams', 'stage_user.team_id', '=', 'teams.id')
            ->where('stage_results.user_id', $id)
            ->whereYear('stage_results.created_at', $currentYear)
            ->select('teams.id', 'teams.name', DB::raw('COUNT(*) as participation'))
            ->groupBy('teams.id', 'teams.name')
            ->orderByDesc('participation')
            ->first();
    
        if (!$team) {
            return response()->json([]);
        }
    
        $columns = [
            'teams.id',
            'teams.name',
            DB::raw('SUM(result) as total')
        ];
    
        $ratings = $this->startConditions()
            ->join('stage_user', function($join) {
                $join->on('stage_results.stage_id', '=', 'stage_user.stage_id');
                $join->on('stage_results.user_id', '=', 'stage_user.user_id');
            })
            ->join('teams', 'stage_user.team_id', '=', 'teams.id')
            ->whereYear('stage_results.created_at', $currentYear)
            ->select($columns)
            ->groupBy('teams.id')
            ->orderBy('total', 'DESC')
            ->get();
    
        $rankedTeams = $ratings->map(function ($item, $index) use ($team) {
            return [
                'name' => ($index + 1) . '. ' . $item->name,
                'score' => $item->total,
                'team_id' => $item->id,
                'is_user' => $item->id == $team->id
            ];
        });
    
        $teamIndex = $rankedTeams->search(fn($item) => $item['team_id'] == $team->id);
    
        if ($teamIndex !== false) {
            if ($teamIndex < 3) {
                $result = $rankedTeams->slice(0, 4)->values();
            } else {
                $result = $rankedTeams->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedTeams[$teamIndex]);
            }
        } else {
            $result = $rankedTeams->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }
    
    
    public function getUserUniversityRating($id)
    {
        $currentYear = 2024; //now()->year;
    
        $userUniversity = DB::table('users')
            ->where('id', $id)
            ->value('university_id');
    
        if (!$userUniversity) {
            return response()->json([]);
        }
    
        $columns = [
            'universities.id',
            'universities.name',
            DB::raw('SUM(result) as total')
        ];
    
        $ratings = $this->startConditions()
            ->join('users', 'stage_results.user_id', '=', 'users.id')
            ->join('universities', 'users.university_id', '=', 'universities.id')
            ->whereYear('stage_results.created_at', $currentYear)
            ->select($columns)
            ->groupBy('universities.id')
            ->orderBy('total', 'DESC')
            ->get();
    
        $rankedUniversities = $ratings->map(function ($item, $index) use ($userUniversity) {
            return [
                'name' => ($index + 1) . '. ' . $item->name,
                'score' => $item->total,
                'university_id' => $item->id,
                'is_user' => $item->id == $userUniversity
            ];
        });
    
        $universityIndex = $rankedUniversities->search(fn($item) => $item['university_id'] == $userUniversity);
    
        if ($universityIndex !== false) {
            if ($universityIndex < 3) {
                $result = $rankedUniversities->slice(0, 4)->values();
            } else {
                $result = $rankedUniversities->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedUniversities[$universityIndex]);
            }
        } else {
            $result = $rankedUniversities->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }
}

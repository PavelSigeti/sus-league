<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Repositories\StageResultRepository;
use App\Models\StageResult;
use App\Models\StageTeam;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    protected $stageResultRepository;

    public function __construct()
    {
        $this->stageResultRepository = app(StageResultRepository::class);
    }

    public function usersRating()
    {
        return $this->stageResultRepository->getUsersRating();
    }

    public function universityRating()
    {
        return $this->stageResultRepository->getUniversityRating();
    }

    public function teamRating()
    {
        $columns = [
            'teams.name',
            DB::raw('SUM(result) as total')
        ];


        $result = StageTeam::query()
            ->join('teams', 'stage_team.team_id', '=', 'teams.id')
            ->select($columns)
            ->groupBy('team_id')
            ->orderBy('total', 'DESC')
            ->paginate(10);

        return $result;

    }
}

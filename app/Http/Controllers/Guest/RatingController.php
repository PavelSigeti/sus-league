<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Repositories\StageResultRepository;
use App\Models\StageTeam;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    protected $stageResultRepository;

    public function __construct()
    {
        $this->stageResultRepository = app(StageResultRepository::class);
    }

    public function usersRating($year = null)
    {
        return $this->stageResultRepository->getUsersRating($year);
    }

    public function universityRating($year = null)
    {
        return $this->stageResultRepository->getUniversityRating($year);
    }

    public function teamRating($year = null)
    {
        return $this->stageResultRepository->getTeamRating($year);

    }
}

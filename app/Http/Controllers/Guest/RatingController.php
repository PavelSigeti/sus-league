<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Repositories\StageResultRepository;

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
        return $this->stageResultRepository->getTeamRating();
    }
}

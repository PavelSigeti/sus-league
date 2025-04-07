<?php

namespace App\Http\Controllers\User;

use App\Http\Repositories\StageResultRepository;
use App\Http\Controllers\Controller;


class RatingController extends Controller
{
    protected $stageResultRepository;

    public function __construct()
    {
        $this->stageResultRepository = app(StageResultRepository::class);
    }

    public function userPersonalRating($id)
    {
        //TODO:: Перенести логику из репозиториев в контроллеры
        return $this->stageResultRepository->getUserPersonalRating($id);
    }

    public function userTeamRating($id)
    {
        return $this->stageResultRepository->getUserTeamRating($id);
    }

    public function userUniversityRating($id)
    {
        return $this->stageResultRepository->getUserUniversityRating($id);
    }

}
